import 'dart:convert';
import 'dart:developer';

import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:http/http.dart';
import 'package:sintren_mobile/config/config_app.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/models/palawija_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sqflite/sqflite.dart';

class PalawijaService {
  final String baseUrl = ConfigApp().baseUrl;

  Future<bool> getPalawija() async {
    try {
      final db = await DatabaseHelper().database;
      await db.delete('palawija');

      final Response result = await get(Uri.parse('${baseUrl}palawija'));

      if (result.statusCode != 200) {
        log("Failed to get palawija: ${result.statusCode} - ${result.body}");
        return false;
      }

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);
      if (jsonResult['data'] == null) {
        log("Failed to get palawija: Invalid response structure");
        return false;
      }

      List<PalawijaModel> palawija = (jsonResult['data'] as List)
          .map((element) => PalawijaModel.fromJson(element))
          .toList();

      if (palawija.isNotEmpty) {
        Batch batch = db.batch();
        for (var item in palawija) {
          batch.insert(
            'palawija',
            item.toMap(),
            conflictAlgorithm: ConflictAlgorithm.replace,
          );
        }
        await batch.commit(noResult: true);
        log("Get palawija sukses");
      }
      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Failed to save palawija to database: $e");
      throw Exception("Internal Server Error");
    }
  }

  Future<Map<String, dynamic>?> store(Map<String, dynamic> data) async {
    try {
      final Response result = await post(
        Uri.parse('${baseUrl}palawija/store'),
        headers: {
          "Content-Type": "application/json",
        },
        body: jsonEncode(data),
      );

      if (result.statusCode != 201) {
        log("Gagal menyimpan data: ${result.statusCode} - ${result.body}");
        return null;
      }

      final Map<String, dynamic> responseBody = jsonDecode(result.body);
      return responseBody;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Gagal menyimpan data: $e");
      return null;
    }
  }

  Future<bool> update(Map<String, dynamic> data, String id) async {
    try {
      final Response result = await patch(
        Uri.parse('${baseUrl}palawija/update/$id'),
        headers: {
          "Content-Type": "application/json",
        },
        body: jsonEncode(data),
      );

      if (result.statusCode != 200) {
        log("Gagal mengupdate data: ${result.statusCode} - ${result.body}");
        return false;
      }

      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Gagal mengupdate data: $e");
      return false;
    }
  }

  Future<bool> getDetailPalawijaByUser() async {
    try {
      final db = await DatabaseHelper().database;
      await db.delete('detailPalawija');

      final String? userId = await UserLoginModel().getUserId();
      final Response result = await get(
        Uri.parse('${baseUrl}palawija/showByUser/$userId'),
      );

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

  Future<bool> deleteDetailById(int id) async {
    try {
      final Response result = await delete(
        Uri.parse('${baseUrl}palawija/deleteDetailById/$id'),
      );

      if (result.statusCode != 200) {
        log("Gagal menghapus data: ${result.statusCode} - ${result.body}");
        return false;
      }

      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Gagal menghapus data: $e");
      return false;
    }
  }
}
