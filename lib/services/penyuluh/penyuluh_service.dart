import 'dart:convert';
import 'dart:developer';

import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/config/config_app.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:http/http.dart';
import 'package:sqflite/sqflite.dart';

class PenyuluhService {
  Future<bool> getDataPenyuluhanDesa() async {
    final String url = '${ConfigApp().baseUrl}admin/getDesa';
    final dbHelper = DatabaseHelper();

    try {
      final db = await dbHelper.database;
      await db.delete('desa');

      final Response response = await get(Uri.parse('$url/dinas'));

      if (response.statusCode != 200) {
        log("Failed to get desa: ${response.statusCode} - ${response.body}");
        return false;
      }

      final Map<String, dynamic> jsonResult = jsonDecode(response.body);
      if (jsonResult['data'] == null) {
        log("Failed to get desa: data is null");
        return false;
      }

      List<LuasWilayahModel> desa = (jsonResult['data'] as List)
          .map((element) => LuasWilayahModel.fromJson(element))
          .toList();

      Batch batch = db.batch();
      for (var item in desa) {
        batch.insert(
          'desa',
          item.toMap(),
          conflictAlgorithm: ConflictAlgorithm.replace,
        );
      }

      await batch.commit(noResult: true);
      log("Get data penyuluhan desa sukses");
      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Failed to get desa: $e");
      throw Exception("Internal Server Error");
    }
  }
}
