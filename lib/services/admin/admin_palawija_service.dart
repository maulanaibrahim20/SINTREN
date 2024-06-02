import 'dart:convert';
import 'dart:developer';

import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/config/config_app.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:http/http.dart';
import 'package:sqflite/sqflite.dart';

class AdminPalawijaService {
  final String baseUrl = ConfigApp().baseUrl;

  Future<bool> getDetailPalawijaByKecamatan() async {
    try {
      final db = await DatabaseHelper().database;
      await db.delete('detailPalawija');

      final String? id = await UserLoginModel().getKecamatanId();
      final String? role = await UserLoginModel().getRole();

      Response result = await get(
        Uri.parse('${baseUrl}palawija/showByKecamatan/$id'),
      );

      if (role == "PERTANIAN") {
        result = await get(
          Uri.parse('${baseUrl}padi/showByKecamatan/dinas'),
        );
      }

      if (result.statusCode != 200) {
        log("Failed to get detail palawija: ${result.statusCode} - ${result.body}");
        return false;
      }

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);
      if (jsonResult['data'] == null) {
        log("Failed to get detail palawija: data is null");
        return false;
      }

      List<DetailPalawijaModel> detail = (jsonResult['data'] as List)
          .map((element) => DetailPalawijaModel.fromJson(element))
          .toList();

      if (detail.isNotEmpty) {
        Batch batch = db.batch();
        for (var item in detail) {
          batch.insert(
            'detailPalawija',
            item.toMap(),
            conflictAlgorithm: ConflictAlgorithm.replace,
          );
        }
        await batch.commit(noResult: true);
        log("Get detail palawija sukses");
      }
      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Failed to get detail palawija: $e");
      throw Exception("Internal Server Error");
    }
  }
}
