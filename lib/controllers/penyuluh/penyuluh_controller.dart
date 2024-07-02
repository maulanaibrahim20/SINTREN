import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:sintren_mobile/services/penyuluh/padi_service.dart';
import 'package:sintren_mobile/services/penyuluh/palawija_service.dart';
import 'package:sintren_mobile/services/penyuluh/penyuluh_service.dart';

class PenyuluhController {
  Future<List<DesaModel>> getDesa() async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query('desa');

      return List<DesaModel>.from(maps.map((map) => DesaModel.fromJson(map)));
    } catch (e) {
      log("Get desa error: $e");
      return [];
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
            WHERE status = 'tolak' AND
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

  Future<List<HistoriPenyuluhanModel>> getHistoriPenyuluhanBulanIni() async {
    try {
      final db = await DatabaseHelper().database;
      final DateTime now = DateTime.now();
      final String currentMonthYear =
          '${now.year}-${now.month.toString().padLeft(2, '0')}';
      final DateTime lastMonthDate = DateTime(now.year, now.month - 1, now.day);
      final String lastMonthYear =
          '${lastMonthDate.year}-${lastMonthDate.month.toString().padLeft(2, '0')}';

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
            WHERE status = 'tolak' AND
                  strftime('%Y-%m', status_data.date) = strftime('%Y-%m', combined_data.date) AND
                  status_data.desa_id = combined_data.desa_id
            ) AS total_tunggu
        FROM (
            SELECT date, desa_id, desa_name, nilai FROM detailPadi
            UNION ALL
            SELECT date, desa_id, desa_name, nilai FROM detailPalawija
        ) AS combined_data
        WHERE strftime('%Y-%m', date) = ?
        GROUP BY
            month_year,
            desa_id
        ORDER BY
            month_year DESC, desa_id;
      ''';

      final List<Map<String, dynamic>> mapsCurrentMonth =
          await db.rawQuery(query, [currentMonthYear]);

      final Set<String> desaIdsCurrentMonth =
          mapsCurrentMonth.map((map) => map['desa_id'] as String).toSet();

      final List<Map<String, dynamic>> mapsLastMonth =
          await db.rawQuery(query, [lastMonthYear]);

      final List<Map<String, dynamic>> mapsFilteredLastMonth = mapsLastMonth
          .where((map) => !desaIdsCurrentMonth.contains(map['desa_id']))
          .toList();

      final List<Map<String, dynamic>> combinedMaps = [
        ...mapsCurrentMonth,
        ...mapsFilteredLastMonth
      ];

      return combinedMaps
          .map((map) => HistoriPenyuluhanModel.fromJson(map))
          .toList();
    } catch (e) {
      log("Get histori penyuluhan bulan ini error: $e");
      return [];
    }
  }

  Future<void> synchronizeData(ValueNotifier<String> statusNotifier) async {
    try {
      statusNotifier.value = 'Memulai sinkronisasi...';
      await initializeDateFormatting('id_ID', null);

      statusNotifier.value = 'Mendapatkan data pengairan...';
      await PadiService().getPengairan();

      statusNotifier.value = 'Mendapatkan data padi...';
      await PadiService().getPadi();

      statusNotifier.value = 'Mendapatkan data palawija...';
      await PalawijaService().getPalawija();

      statusNotifier.value = 'Mendapatkan data desa...';
      await PenyuluhService().getDataPenyuluhanDesa();

      statusNotifier.value = 'Mendapatkan data penyuluhan...';
      await PadiService().getDetailPadiByUser();

      statusNotifier.value = 'Sinkronisasi selesai...';
      await PalawijaService().getDetailPalawijaByUser();
    } catch (error) {
      statusNotifier.value = 'Error: ${error.toString()}';
      throw Exception("Internal Server Error");
    }
  }
}
