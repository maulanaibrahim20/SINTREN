import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/detail_combined_model.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/admin/admin_padi_service.dart';
import 'package:sintren_mobile/services/admin/admin_palawija_service.dart';
import 'package:sintren_mobile/services/admin/admin_service.dart';
import 'package:sintren_mobile/services/user_service.dart';

class AdminController {
  Future<void> synchronizeData(ValueNotifier<String> statusNotifier) async {
    try {
      statusNotifier.value = 'Memulai sinkronisasi...';
      await initializeDateFormatting('id_ID', null);

      statusNotifier.value = 'Mendapatkan data desa...';
      await AdminService().getDataPenyuluhanDesa();

      statusNotifier.value = 'Mendapatkan data penyuluhan...';
      await AdminPadiService().getDetailPadiByKecamatan();

      statusNotifier.value = 'Sinkronisasi selesai...';
      await AdminPalawijaService().getDetailPalawijaByKecamatan();
    } catch (error) {
      statusNotifier.value = 'Error: ${error.toString()}';
      throw Exception("Internal Server Error");
    }
  }

  Future<List<HistoriPenyuluhanModel>> getHistoriPenyuluhan() async {
    try {
      final db = await DatabaseHelper().database;
      const String query = '''
        SELECT
            strftime('%Y-%m', date) AS month_year,
            desa_id,
            desa_name,
            SUM(nilai) AS total_nilai,
            (SELECT COUNT(*) FROM (
                SELECT date, desa_id, desa_name, status FROM detailPadi
                UNION ALL
                SELECT date, desa_id, desa_name, status FROM detailPalawija
            ) AS status_data
            WHERE status = 'tunggu' AND
                  strftime('%Y-%m', status_data.date) = strftime('%Y-%m', combined_data.date) AND
                  status_data.desa_id = combined_data.desa_id
            ) AS total_tunggu
        FROM (
            SELECT date, desa_id, desa_name, nilai FROM detailPadi
            UNION ALL
            SELECT date, desa_id, desa_name, nilai FROM detailPalawija
        ) AS combined_data
        GROUP BY
            month_year,
            desa_id
        ORDER BY
            month_year DESC, desa_id;
      ''';

      final List<Map<String, dynamic>> maps = await db.rawQuery(query);

      return maps.map((map) => HistoriPenyuluhanModel.fromJson(map)).toList();
    } catch (e) {
      log("Get histori penyuluhan error: $e");
      return [];
    }
  }

  Future<List<LuasWilayahModel>> getLuasLahanDesa() async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query('desa');

      return maps.map((map) => LuasWilayahModel.fromMap(map)).toList();
    } catch (e) {
      log("Get luas lahan desa error: $e");
      return [];
    }
  }

  Future<double> getTotalLuasLahanKecamatan() async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query('desa');

      List<LuasWilayahModel> luasWilayahList =
          maps.map((map) => LuasWilayahModel.fromMap(map)).toList();

      double totalLuasLahan =
          luasWilayahList.fold(0, (sum, item) => sum + item.totalLuasLahan);

      return totalLuasLahan;
    } catch (e) {
      log("Get total luas lahan desa error: $e");
      return 0;
    }
  }

  Future<double> getTotalNilaiPenyuluhanBulanIni() async {
    try {
      List<HistoriPenyuluhanModel> historiList = await getHistoriPenyuluhan();

      final now = DateTime.now();
      final currentMonthYear =
          '${now.year}-${now.month.toString().padLeft(2, '0')}';

      List<HistoriPenyuluhanModel> currentMonthHistori =
          historiList.where((item) => item.date == currentMonthYear).toList();

      double sumTotalNilai =
          currentMonthHistori.fold(0, (sum, item) => sum + item.nilai);

      return sumTotalNilai;
    } catch (e) {
      log("Get sum total nilai error: $e");
      return 0;
    }
  }

  Future<List<DesaModel>> getDesa() async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query('desa');

      return maps.map((map) => DesaModel.fromJson(map)).toList();
    } catch (e) {
      log("Get desa error: $e");
      return [];
    }
  }

  Future<bool> verify(
      {required String dataId,
      required Map<String, dynamic> map,
      required bool isPalawija}) async {
    EasyLoading.show(status: "Loading...");
    try {
      final id = await UserLoginModel().getUserId();
      final data = {
        "user_id": id,
        "status": map['status'],
        "catatan": map['catatan'],
        "tipe": isPalawija ? 'palawija' : 'padi',
      };

      final result = await UserService().verify(data, dataId);

      if (!result) {
        EasyLoading.showToast("Gagal mengupdate data");
        return false;
      }

      final db = await DatabaseHelper().database;
      final localData = {
        "status": map['status'],
        "catatan": map['catatan'],
      };

      String dbName = 'detailPadi';

      if (isPalawija) {
        dbName = 'detailPalawija';
      }

      await db.update(
        dbName,
        localData,
        where: 'id = ?',
        whereArgs: [dataId],
      );

      EasyLoading.showToast("Berhasil verifikasi data");
      return true;
    } catch (e) {
      EasyLoading.showToast("Gagal verifikasi data");
      log("Verify error: $e");
      return false;
    } finally {
      EasyLoading.dismiss();
    }
  }

  Future<List<DetailCombinedModel>> getDetailCombinedByStatus() async {
    try {
      final db = await DatabaseHelper().database;

      // Query for detailPadi with status = 'tunggu'
      final List<Map<String, dynamic>> padiMaps = await db.query(
        'detailPadi',
        where: 'status = ?',
        whereArgs: ['tunggu'],
        orderBy: 'date DESC',
      );

      List<DetailCombinedModel> detailPadiList = padiMaps.map((map) {
        var padiModel = DetailPadiModel.fromMap(map);
        return DetailCombinedModel(
            date: padiModel.date, type: 'padi', data: padiModel);
      }).toList();

      // Query for detailPalawija with status = 'tunggu'
      final List<Map<String, dynamic>> palawijaMaps = await db.query(
        'detailPalawija',
        where: 'status = ?',
        whereArgs: ['tunggu'],
        orderBy: 'date DESC',
      );

      List<DetailCombinedModel> detailPalawijaList = palawijaMaps.map((map) {
        var palawijaModel = DetailPalawijaModel.fromMap(map);
        return DetailCombinedModel(
            date: palawijaModel.date, type: 'palawija', data: palawijaModel);
      }).toList();

      // Combine results
      List<DetailCombinedModel> combinedList = [
        ...detailPadiList,
        ...detailPalawijaList
      ];

      // Sort combined list by date in descending order
      combinedList.sort((a, b) => b.date.compareTo(a.date));

      return combinedList;
    } catch (e) {
      log("Get detail combined by status tunggu error: $e");
      return [];
    }
  }
}
