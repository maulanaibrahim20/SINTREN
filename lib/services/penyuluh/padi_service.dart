import 'dart:convert';
import 'dart:developer';

import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:http/http.dart';
import 'package:sintren_mobile/config/config_app.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/padi_model.dart';
import 'package:sintren_mobile/models/pengairan_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sqflite/sqflite.dart';

class PadiService {
  final String baseUrl = ConfigApp().baseUrl;

  Future<bool> getPengairan() async {
    try {
      final db = await DatabaseHelper().database;
      await db.delete('pengairan');

      final Response result = await get(Uri.parse('${baseUrl}pengairan'));

      if (result.statusCode != 200) {
        log("Failed to get pengairan: ${result.statusCode} - ${result.body}");
        return false;
      }

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);
      if (jsonResult['data'] == null) {
        log("Failed to get pengairan: data is null");
        return false;
      }

      List<PengairanModel> pengairan = (jsonResult['data'] as List)
          .map((element) => PengairanModel.fromJson(element))
          .toList();

      if (pengairan.isNotEmpty) {
        Batch batch = db.batch();
        for (var item in pengairan) {
          batch.insert(
            'pengairan',
            item.toMap(),
            conflictAlgorithm: ConflictAlgorithm.replace,
          );
        }
        await batch.commit(noResult: true);
        log("Get pengairan sukses");
      }
      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Failed to save pengairan to database: $e");
      throw Exception("Internal Server Error");
    }
  }

  Future<bool> getPadi() async {
    try {
      final db = await DatabaseHelper().database;
      await db.delete('padi');

      final Response result = await get(Uri.parse('${baseUrl}padi'));

      if (result.statusCode != 200) {
        log("Failed to get padi: ${result.statusCode} - ${result.body}");
        return false;
      }

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);
      if (jsonResult['data'] == null) {
        log("Failed to get padi: data is null");
        return false;
      }

      List<PadiModel> padi = (jsonResult['data'] as List)
          .map((element) => PadiModel.fromJson(element))
          .toList();

      if (padi.isNotEmpty) {
        Batch batch = db.batch();
        for (var item in padi) {
          batch.insert(
            'padi',
            item.toMap(),
            conflictAlgorithm: ConflictAlgorithm.replace,
          );
        }
        await batch.commit(noResult: true);
        log("Get padi sukses");
      }
      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Failed to save padi to database: $e");
      throw Exception("Internal Server Error");
    }
  }

  Future<bool> store(Map<String, dynamic> data) async {
    try {
      final Response result = await post(
        Uri.parse('${baseUrl}padi/store'),
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
      EasyLoading.showToast("Internal Server Error");
      log("Gagal menyimpan data: $e");
      return false;
    }
  }

  Future<bool> update(Map<String, dynamic> data, String id) async {
    try {
      final Response result = await patch(
        Uri.parse('${baseUrl}padi/update/$id'),
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

  Future<bool> getDetailPadiByUser() async {
    try {
      final db = await DatabaseHelper().database;
      await db.delete('detailPadi');

      final String? id = await UserLoginModel().getUserId();
      final Response result = await get(
        Uri.parse('${baseUrl}padi/showByUser/$id'),
      );

      if (result.statusCode != 200) {
        log("Failed to get detail padi: ${result.statusCode} - ${result.body}");
        return false;
      }

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);
      if (jsonResult['data'] == null) {
        log("Failed to get detail padi: data is null");
        return false;
      }

      List<DetailPadiModel> detail = (jsonResult['data'] as List)
          .map((element) => DetailPadiModel.fromJson(element))
          .toList();

      if (detail.isNotEmpty) {
        Batch batch = db.batch();
        for (var item in detail) {
          batch.insert(
            'detailPadi',
            item.toMap(),
            conflictAlgorithm: ConflictAlgorithm.replace,
          );
        }
        await batch.commit(noResult: true);
        log("Get detail padi sukses");
      }

      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Failed to get detail padi: $e");
      throw Exception("Internal Server Error");
    }
  }

  Future<bool> deleteDetailById(int id) async {
    try {
      final Response result = await delete(
        Uri.parse('${baseUrl}padi/deleteDetailById/$id'),
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
