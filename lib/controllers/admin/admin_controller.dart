import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
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
      await AdminPalawijaService().getDetailPalawijaByKecamatan();

      statusNotifier.value = 'Sinkronisasi selesai...';
      await UserService().getVerify();
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
            SUM(nilai) AS total_nilai
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
}
