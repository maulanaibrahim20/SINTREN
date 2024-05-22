import 'dart:convert';
import 'dart:developer';

import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:http/http.dart';
import 'package:sintren_mobile/config/config_app.dart';
import 'package:sintren_mobile/helpers/penyuluh_dbhelper.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/pengairan_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sqflite/sqflite.dart';

class PadiService {
  Future<void> getPengairan() async {
    try {
      final db = await PenyuluhDatabaseHelper().database;
      await db.delete('pengairan');

      final Response result = await get(
        Uri.parse('${ConfigApp().baseUrl}pengairan'),
      );

      if (result.statusCode != 200) {
        throw Exception(
            "Failed to get pengairan: ${result.statusCode} - ${result.body}");
      }

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);

      List<PengairanModel> desa = (jsonResult['data'] as List)
          .map((element) => PengairanModel.fromJson(element))
          .toList();

      Batch batch = db.batch();
      for (var item in desa) {
        batch.insert('pengairan', item.toMap());
      }
      await batch.commit(noResult: true);
    } catch (e) {
      throw Exception("Failed to save pengairan to database: $e");
    }
  }

  Future<bool> store(Map<String, dynamic> data) async {
    try {
      final Response result = await post(
        Uri.parse('${ConfigApp().baseUrl}padi/store'),
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
        Uri.parse('${ConfigApp().baseUrl}padi/update/$id'),
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

  Future<List<DetailPadiModel>> getByUser({required String id}) async {
    try {
      final Response result = await get(
        Uri.parse('${ConfigApp().baseUrl}padi/showByUser/$id'),
      );

      if (result.statusCode != 200) {
        log("Failed to get data1: ${result.statusCode} - ${result.body}");
        return [];
      }
      log("Result body => ${result.body}");

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);

      List<DetailPadiModel> laporan = [];

      for (var element in jsonResult['data'] as List) {
        DetailPadiModel detailPadiModel = DetailPadiModel.fromJson(element);

        laporan.add(detailPadiModel);
      }
      return laporan;
    } catch (e) {
      log("Error getting padi: $e");
      EasyLoading.showToast("Failed to get data");
      return [];
    }
  }

  Future<void> getDetailPadiByUser() async {
    try {
      final db = await PenyuluhDatabaseHelper().database;
      await db.delete('detailPadi');

      final id = await UserLoginModel().getUserId();
      final Response result = await get(
        Uri.parse('${ConfigApp().baseUrl}padi/showByUser/$id'),
      );

      if (result.statusCode != 200) {
        log("Failed to get detail padi: ${result.statusCode} - ${result.body}");
      }

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);

      List<DetailPadiModel> detail = [];
      for (var element in jsonResult['data'] as List) {
        DetailPadiModel detailPadiModel = DetailPadiModel.fromJson(element);
        detail.add(detailPadiModel);
      }

      if (detail.isNotEmpty) {
        log(detail[0].pengairanName);
        Batch batch = db.batch();
        for (var item in detail) {
          batch.insert('detailPadi', item.toMap());
        }
        await batch.commit(noResult: true);
      }
    } catch (e) {
      log("Failed to get detail padi: $e");
    }
  }

  Future<bool> deletaDetailById(int id) async {
    try {
      final Response result = await delete(
        Uri.parse('${ConfigApp().baseUrl}padi/deletaDetailById/$id'),
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
