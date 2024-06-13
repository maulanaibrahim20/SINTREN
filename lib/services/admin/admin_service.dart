import 'dart:convert';
import 'dart:developer';

import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/config/config_app.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:sintren_mobile/models/penyuluh_model.dart';
import 'package:sintren_mobile/models/prediksi_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sqflite/sqflite.dart';
import 'package:http/http.dart';

class AdminService {
  Future<bool> getDataPenyuluhanDesa() async {
    final String url = '${ConfigApp().baseUrl}admin/getDesa';
    final dbHelper = DatabaseHelper();
    final userModel = UserLoginModel();

    try {
      final Database db = await dbHelper.database;
      await db.delete('desa');

      final String? id = await userModel.getKecamatanId();
      final String? role = await userModel.getRole();

      Response response = await get(Uri.parse('$url/$id'));

      if (role == "PERTANIAN") {
        response = await get(Uri.parse('$url/dinas'));
      }

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

  Future<PrediksiModel> getPrediksiPadi() async {
    final String url = '${ConfigApp().baseUrl}padi/prediksiSp';
    try {
      final Response response = await get(Uri.parse(url));

      if (response.statusCode == 200) {
        final Map<String, dynamic> jsonResult = jsonDecode(response.body);
        if (jsonResult['status'] == 'success') {
          final List<int> labels = List<int>.from(jsonResult['data']['labels']);
          final List<int> actualData =
              List<int>.from(jsonResult['data']['actualData']);
          final List<double> predictedData =
              List<double>.from(jsonResult['data']['predictedData']);
          final double mape = jsonResult['data']['mape'];

          List<DataItem> result = [];
          for (int i = 0; i < labels.length; i++) {
            result.add(DataItem(
              label: labels[i],
              actualData: actualData[i],
              predictedData: predictedData[i],
            ));
          }

          return PrediksiModel(result: result, mape: mape);
        } else {
          log("Request failed with status: ${jsonResult['status']}");
          throw Exception('Failed to load data');
        }
      } else {
        log("HTTP request failed with status code: ${response.statusCode}");
        throw Exception('Failed to load data');
      }
    } catch (e) {
      log("Error occurred while fetching prediction data: $e");
      throw Exception('Internal Server Error');
    }
  }

  Future<void> getPenyuluh() async {
    try {
      final db = await DatabaseHelper().database;
      await db.delete('penugasan');
      await db.delete('penyuluh');
      final String? id = await UserLoginModel().getKecamatanId();
      final String url = '${ConfigApp().baseUrl}admin/getPenyuluh/$id';
      final response = await get(Uri.parse(url));

      if (response.statusCode == 200) {
        var data = json.decode(response.body)['data'] as List;
        List<Penyuluh> penyuluh =
            data.map((penyuluh) => Penyuluh.fromJson(penyuluh)).toList();

        for (var penyuluh in penyuluh) {
          await db.insert('penyuluh', penyuluh.toMap(),
              conflictAlgorithm: ConflictAlgorithm.replace);

          for (var penugasan in penyuluh.penugasan) {
            await db.insert(
                'penugasan',
                {
                  'id': penugasan.id,
                  'user_id': penyuluh.id,
                  'desa_id': penugasan.desaId,
                  'desa_name': penugasan.desaName,
                },
                conflictAlgorithm: ConflictAlgorithm.replace);
          }
        }
        log('Data penyuluh berhasil diambil dan disimpan.');
      } else {
        log("HTTP request failed with status code: ${response.statusCode}");
        throw Exception('Failed to load data');
      }
    } catch (e) {
      log('Terjadi kesalahan saat mengambil data penyuluh: $e');
      throw Exception('Failed to load data');
    }
  }

  Future<Map<String, dynamic>?> addPenugasan(Map<String, dynamic> data) async {
    try {
      final Response result = await post(
        Uri.parse('${ConfigApp().baseUrl}admin/addPenugasan'),
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

  Future<bool> deletePenugasan(int id) async {
    try {
      final Response result = await delete(
        Uri.parse('${ConfigApp().baseUrl}admin/deletePenugasan/$id'),
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
