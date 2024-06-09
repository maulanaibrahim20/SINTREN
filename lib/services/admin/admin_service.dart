import 'dart:convert';
import 'dart:developer';

import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/config/config_app.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
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
}
