import 'dart:convert';
import 'dart:developer';

import 'package:http/http.dart';
import 'package:sintren_mobile/config/config_app.dart';
import 'package:sintren_mobile/helpers/penyuluh_dbhelper.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/models/palawija_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sqflite/sqflite.dart';

class PalawijaService {
  Future<void> getPalawija() async {
    try {
      final db = await PenyuluhDatabaseHelper().database;
      await db.delete('palawija');

      final Response result = await get(
        Uri.parse('${ConfigApp().baseUrl}palawija'),
      );

      if (result.statusCode != 200) {
        throw Exception(
            "Failed to get palawija: ${result.statusCode} - ${result.body}");
      }

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);

      List<PalawijaModel> palawija = (jsonResult['data'] as List)
          .map((element) => PalawijaModel.fromJson(element))
          .toList();

      Batch batch = db.batch();
      for (var item in palawija) {
        batch.insert('palawija', item.toMap());
      }
      await batch.commit(noResult: true);
      log("get palawija sukses");
    } catch (e) {
      throw Exception("Failed to save palawija to database: $e");
    }
  }

  Future<bool> store(Map<String, dynamic> data) async {
    try {
      final Response result = await post(
        Uri.parse('${ConfigApp().baseUrl}palawija/store'),
        headers: {
          "Content-Type": "application/json",
        },
        body: jsonEncode(data),
      );

      if (result.statusCode != 201) {
        log("Gagal menyimpan data: ${result.statusCode} - ${result.body}");
        return false;
      }

      return true;
    } catch (e) {
      log("Gagal menyimpan data: $e");
      return false;
    }
  }

  Future<bool> update(Map<String, dynamic> data, String id) async {
    try {
      final Response result = await patch(
        Uri.parse('${ConfigApp().baseUrl}palawija/update/$id'),
        headers: {
          "Content-Type": "application/json",
        },
        body: jsonEncode(data),
      );

      if (result.statusCode != 200) {
        log("Gagal mengupdate data1: ${result.statusCode} - ${result.body}");
        return false;
      }

      return true;
    } catch (e) {
      log("Gagal mengupdate data2: $e");
      return false;
    }
  }

  Future<void> getDetailPalawijaByUser() async {
    try {
      final db = await PenyuluhDatabaseHelper().database;
      await db.delete('detailPalawija');

      final id = await UserLoginModel().getUserId();
      final Response result = await get(
        Uri.parse('${ConfigApp().baseUrl}palawija/showByUser/$id'),
      );

      if (result.statusCode != 200) {
        log("Failed to get detail palawija: ${result.statusCode} - ${result.body}");
      }

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);

      List<DetailPalawijaModel> detail = [];
      for (var element in jsonResult['data'] as List) {
        DetailPalawijaModel detailPalawijaModel =
            DetailPalawijaModel.fromJson(element);
        detail.add(detailPalawijaModel);
      }

      if (detail.isNotEmpty) {
        Batch batch = db.batch();
        for (var item in detail) {
          batch.insert('detailPalawija', item.toMap());
        }
        await batch.commit(noResult: true);
        log("get detail palawija sukses");
      }
    } catch (e) {
      log("Failed to get detail palawija: $e");
    }
  }

  Future<bool> deletaDetailById(int id) async {
    try {
      final Response result = await delete(
        Uri.parse('${ConfigApp().baseUrl}palawija/deletaDetailById/$id'),
      );

      if (result.statusCode != 200) {
        log("Gagal menghapus data: ${result.statusCode} - ${result.body}");
        return false;
      }

      return true;
    } catch (e) {
      log("Gagal menghapus data: $e");
      return false;
    }
  }
}
