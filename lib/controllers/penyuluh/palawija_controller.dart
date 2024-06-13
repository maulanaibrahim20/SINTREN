import 'dart:developer';

import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:intl/intl.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/models/kesimpulan_data_palawija_model.dart';
import 'package:sintren_mobile/models/palawija_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/penyuluh/palawija_service.dart';
import 'package:sqflite/sqflite.dart';

class PalawijaController {
  final List<String> jenisLahan = ["sawah", "non sawah"];
  final List<String> bantuan = ["bantuan pemerintah", "non bantuan pemerintah"];
  final List<String> tipeData = [
    "panen",
    "tanam",
    "puso/rusak",
    "panen muda",
    "panen hijauan pakan ternak"
  ];

  String getDateTimeNow() {
    DateTime now = DateTime.now();
    return DateFormat('yyyy-MM-dd HH:mm:ss').format(now);
  }

  Future<bool> store(Map<String, dynamic> map) async {
    EasyLoading.show(status: "Loading...");
    try {
      final id = await UserLoginModel().getUserId();
      final kecamatanId = await UserLoginModel().getKecamatanId();
      final data = {
        "user_id": id,
        "desa_id": map['desa_id'],
        "kecamatan_id": kecamatanId,
        "jenis_lahan": map['jenis_lahan'],
        "jenis_bantuan": map['jenis_bantuan'],
        "date": map['date'],
        "id_jenis_palawija": map['id_jenis_palawija'],
        "tipe_data": map['tipe_data'],
        "nilai": map['nilai']
      };

      final response = await PalawijaService().store(data);

      if (response == null || response['status'] != 'success') {
        EasyLoading.showToast("Gagal menyimpan data");
        return false;
      }

      final responseData = response['data'];

      final detailPadi = DetailPalawijaModel.fromJson({
        ...responseData,
        'desa_name': map['desa_name'],
        'palawija_name': map["palawija_name"],
        'status': 'tunggu',
        'catatan': '',
      });

      final db = await DatabaseHelper().database;
      await db.insert(
        'detailPalawija',
        detailPadi.toMap(),
        conflictAlgorithm: ConflictAlgorithm.replace,
      );

      EasyLoading.showToast("Berhasil menyimpan data");
      return true;
    } catch (e) {
      EasyLoading.showToast("Gagal menyimpan data");
      log("Store error: $e");
      return false;
    } finally {
      EasyLoading.dismiss();
    }
  }

  Future<bool> update(String dataId, Map<String, dynamic> map) async {
    EasyLoading.show(status: "Loading...");
    try {
      final id = await UserLoginModel().getUserId();
      final kecamatanId = await UserLoginModel().getKecamatanId();
      final data = {
        "user_id": id,
        "desa_id": map['desa_id'],
        "kecamatan_id": kecamatanId,
        "jenis_lahan": map['jenis_lahan'],
        "jenis_bantuan": map['jenis_bantuan'],
        "date": map['date'],
        "id_jenis_palawija": map['id_jenis_palawija'],
        "tipe_data": map['tipe_data'],
        "nilai": map['nilai']
      };

      final result = await PalawijaService().update(data, dataId);

      if (!result) {
        EasyLoading.showToast("Gagal mengupdate data");
        return false;
      }

      final db = await DatabaseHelper().database;
      final localData = Map<String, dynamic>.from(data)
        ..remove("user_id")
        ..['status'] = 'tunggu'
        ..['catatan'] = ''
        ..['updated_at'] = getDateTimeNow();

      await db.update(
        'detailPalawija',
        localData,
        where: 'id = ?',
        whereArgs: [dataId],
      );

      EasyLoading.showToast("Berhasil mengupdate data");
      return true;
    } catch (e) {
      EasyLoading.showToast("Gagal mengupdate data");
      log("Update error: $e");
      return false;
    } finally {
      EasyLoading.dismiss();
    }
  }

  Future<void> deleteDetailById(int id) async {
    EasyLoading.show(status: "Loading...");
    try {
      final result = await PalawijaService().deleteDetailById(id);

      if (!result) {
        EasyLoading.showToast("Gagal menghapus data");
        return;
      }

      if (result) {
        final db = await DatabaseHelper().database;
        await db.delete(
          'detailPalawija',
          where: "id = ?",
          whereArgs: [id],
        );

        EasyLoading.showToast("Berhasil menghapus data");
      }
    } catch (e) {
      EasyLoading.showToast("Gagal menghapus data");
      log("Delete error: $e");
    } finally {
      EasyLoading.dismiss();
    }
  }

  Future<List<PalawijaModel>> getPalawija() async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query('palawija');
      return List<PalawijaModel>.from(
          maps.map((map) => PalawijaModel.fromJson(map)));
    } catch (e) {
      log("Get palawija error: $e");
      return [];
    }
  }

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

  Future<List<DetailPalawijaModel>> getDetailPalawijaByUser(
      String date, String desaId) async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query(
        'detailPalawija',
        where: 'date LIKE ? AND desa_id = ?',
        whereArgs: ['%$date%', desaId],
        orderBy: '''
          COALESCE(updated_at, created_at) DESC
        ''',
      );

      return List<DetailPalawijaModel>.from(
          maps.map((map) => DetailPalawijaModel.fromMap(map)));
    } catch (e) {
      log("Get detail palawija by user error: $e");
      return [];
    }
  }

  Future<DetailPalawijaModel?> getDetailPalawijaById(int? id) async {
    try {
      final db = await DatabaseHelper().database;
      final maps = await db.query(
        'detailPalawija',
        where: 'id = ?',
        whereArgs: [id],
      );
      if (maps.isNotEmpty) {
        return DetailPalawijaModel.fromMap(maps.first);
      } else {
        return null;
      }
    } catch (e) {
      log("Get detail palawija by id error: $e");
      return null;
    }
  }

  Future<Map<String, JenisPalawija>> getKesimpulanDataPalawija(
      String date, String desaId) async {
    try {
      List<DetailPalawijaModel> groupedData =
          await getDetailPalawijaByUser(date, desaId);

      var groupedByAll =
          <String, Map<String, Map<String, Map<String, double>>>>{};

      for (var row in groupedData) {
        groupedByAll
            .putIfAbsent(row.palawijaName, () => {})
            .putIfAbsent(row.jenisLahan, () => {})
            .putIfAbsent(row.jenisBantuan, () => {})
            .update(row.tipeData, (value) => value + row.nilai,
                ifAbsent: () => row.nilai);
      }

      var result = <String, JenisPalawija>{};

      groupedByAll.forEach((jenisPalawija, lahanMap) {
        var lahanData = <String, JenisLahan>{};
        double totalJenisPalawija = 0;

        lahanMap.forEach((jenisLahan, bantuanMap) {
          var bantuanData = <String, JenisBantuan>{};
          double totalJenisLahan = 0;

          bantuanMap.forEach((jenisBantuan, tipeDataMap) {
            var tipeDataEntries = <String, TipeData>{};
            double totalJenisBantuan = 0;

            tipeDataMap.forEach((tipeData, nilai) {
              tipeDataEntries[tipeData] = TipeData(data: {tipeData: nilai});
              totalJenisBantuan += nilai;
            });

            bantuanData[jenisBantuan] = JenisBantuan(
                tipeData: tipeDataEntries, total: totalJenisBantuan);
            totalJenisLahan += totalJenisBantuan;
          });

          lahanData[jenisLahan] =
              JenisLahan(jenisBantuan: bantuanData, total: totalJenisLahan);
          totalJenisPalawija += totalJenisLahan;
        });

        result[jenisPalawija] =
            JenisPalawija(jenisLahan: lahanData, total: totalJenisPalawija);
      });

      return result;
    } catch (e) {
      log("Get kesimpulan data palawija error: $e");
      return {};
    }
  }
}
