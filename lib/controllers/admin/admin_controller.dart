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
import 'package:sintren_mobile/models/penyuluh_model.dart';
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
      await AdminPalawijaService().getDetailPalawijaByKecamatan();

      statusNotifier.value = 'Sinkronisasi selesai...';
      await AdminService().getPenyuluh();
    } catch (error) {
      statusNotifier.value = 'Error: ${error.toString()}';
      throw Exception("Internal Server Error");
    }
  }

  Future<List<HistoriPenyuluhanModel>> getHistoriPenyuluhan({
    required bool isMonthNow,
  }) async {
    try {
      final db = await DatabaseHelper().database;
      final DateTime now = DateTime.now();
      final String currentMonthYear =
          '${now.year}-${now.month.toString().padLeft(2, '0')}';

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
      WHERE strftime('%Y-%m', date) LIKE ?
      GROUP BY
          month_year,
          desa_id
      ORDER BY
          month_year DESC, desa_id;
    ''';

      final List<String> args = isMonthNow ? [currentMonthYear] : ['%'];

      final List<Map<String, dynamic>> maps = await db.rawQuery(query, args);

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
      List<HistoriPenyuluhanModel> historiList =
          await getHistoriPenyuluhan(isMonthNow: true);

      double sumTotalNilai =
          historiList.fold(0, (sum, item) => sum + item.nilai);

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

      final List<Map<String, dynamic>> padiMaps = await db.query(
        'detailPadi',
        where: 'status = ?',
        whereArgs: ['tunggu'],
        orderBy: '''
        COALESCE(updated_at, created_at) DESC
      ''',
      );

      List<DetailCombinedModel> detailPadiList = padiMaps.map((map) {
        var padiModel = DetailPadiModel.fromMap(map);
        return DetailCombinedModel(
            date: padiModel.date, type: 'padi', data: padiModel);
      }).toList();

      final List<Map<String, dynamic>> palawijaMaps = await db.query(
        'detailPalawija',
        where: 'status = ?',
        whereArgs: ['tunggu'],
        orderBy: '''
        COALESCE(updated_at, created_at) DESC
      ''',
      );

      List<DetailCombinedModel> detailPalawijaList = palawijaMaps.map((map) {
        var palawijaModel = DetailPalawijaModel.fromMap(map);
        return DetailCombinedModel(
            date: palawijaModel.date, type: 'palawija', data: palawijaModel);
      }).toList();

      List<DetailCombinedModel> combinedList = [
        ...detailPadiList,
        ...detailPalawijaList
      ];

      combinedList.sort((a, b) {
        final dateA = a.data.updatedAt ?? a.data.createdAt;
        final dateB = b.data.updatedAt ?? b.data.createdAt;
        return dateB.compareTo(dateA);
      });

      return combinedList;
    } catch (e) {
      log("Get detail combined by status tunggu error: $e");
      return [];
    }
  }

  Future<List<Penyuluh>> getPenyuluh() async {
    final db = await DatabaseHelper().database;
    final List<Map<String, dynamic>> penyuluhMaps = await db.query('penyuluh');

    List<Penyuluh> penyuluhList = [];
    for (var penyuluhMap in penyuluhMaps) {
      final List<Map<String, dynamic>> penugasanMaps = await db.query(
          'penugasan',
          where: 'user_id = ?',
          whereArgs: [penyuluhMap['id']]);

      List<Penugasan> penugasanList = penugasanMaps
          .map((penugasanMap) => Penugasan(
                id: penugasanMap['id'],
                desaId: penugasanMap['desa_id'],
                desaName: penugasanMap['desa_name'],
              ))
          .toList();

      penyuluhList.add(Penyuluh(
        id: penyuluhMap['id'],
        name: penyuluhMap['name'],
        email: penyuluhMap['email'],
        alamat: penyuluhMap['alamat'],
        noTelp: penyuluhMap['no_telp'],
        penugasan: penugasanList,
      ));
    }

    return penyuluhList;
  }
}
