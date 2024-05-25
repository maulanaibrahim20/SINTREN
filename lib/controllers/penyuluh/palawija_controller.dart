import 'dart:developer';

import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/helpers/penyuluh_dbhelper.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/models/kesimpulan_data_palawija_model.dart';
import 'package:sintren_mobile/models/palawija_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/palawija_service.dart';

class PalawijaController {
  List<String> jenisLahan = ["Lahan Sawah", "Lahan Non-Sawah"];
  List<String> bantuan = ["Bantuan Pemerintah", "Bantuan Non-Pemerintah"];
  List<String> tipeData = [
    "panen",
    "tanam",
    "puso/rusak",
    "panen_muda",
    "panen_hijauan_pakan_ternak"
  ];

  Future<void> store(Map<String, dynamic> map) async {
    EasyLoading.show(status: "Loading...");
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

    log(data.toString());

    final result = await PalawijaService().store(data);

    if (!result) {
      EasyLoading.showToast("Gagal menyimpan data");
    } else {
      EasyLoading.dismiss();
      EasyLoading.showToast("Berhasil menyimpan data");
    }
  }

  Future<void> update(String dataId, Map<String, dynamic> map) async {
    EasyLoading.show(status: "Loading...");
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

    log(data.toString());

    final result = await PalawijaService().update(data, dataId);

    if (!result) {
      EasyLoading.showToast("Gagal mengupdate data");
    } else {
      final db = await PenyuluhDatabaseHelper().database;

      final localData = Map<String, dynamic>.from(data)
        ..removeWhere((key, value) => key == "user_id");

      await db.update(
        'detailPalawija',
        localData,
        where: 'id = ?',
        whereArgs: [dataId],
      );
      EasyLoading.dismiss();
      EasyLoading.showToast("Berhasil mengupdate data");
    }
  }

  Future<void> deleteDetailById(int id) async {
    EasyLoading.show(status: "Loading...");
    final result = await PalawijaService().deletaDetailById(id);

    if (!result) {
      EasyLoading.showToast("Gagal menghapus data");
    } else {
      final db = await PenyuluhDatabaseHelper().database;
      await db.delete(
        'detailPalawija',
        where: "id = ?",
        whereArgs: [id],
      );
      EasyLoading.dismiss();
      EasyLoading.showToast("Berhasil menghapus data");
    }
  }

  Future<List<PalawijaModel>> getPalawija() async {
    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query('palawija');

    return List<PalawijaModel>.from(
        maps.map((map) => PalawijaModel.fromJson(map)));
  }

  Future<List<DesaModel>> getDesa() async {
    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query('desa');

    return List<DesaModel>.from(maps.map((map) => DesaModel.fromJson(map)));
  }

  Future<List<DetailPalawijaModel>> getDetailPalawijaByUser(
      String date, String desaId) async {
    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query(
      'detailPalawija',
      where: 'date LIKE ? AND desa_id = ?',
      whereArgs: ['%$date%', desaId],
    );

    return List<DetailPalawijaModel>.from(
        maps.map((map) => DetailPalawijaModel.fromMap(map)));
  }

  Future<DetailPalawijaModel?> getDetailPadiById(int? id) async {
    final db = await PenyuluhDatabaseHelper().database;
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
  }

  Future<Map<String, JenisPalawija>> getKesimpulanDataPalawija(
      String date, String desaId) async {
    List<DetailPalawijaModel> groupedData =
        await PalawijaController().getDetailPalawijaByUser(date, desaId);

    // Data structure to hold the aggregated data
    var groupedByAll = <String, Map<String, Map<String, Map<String, int>>>>{};

    // Aggregate the data
    for (var row in groupedData) {
      groupedByAll
          .putIfAbsent(row.palawijaName, () => {})
          .putIfAbsent(row.jenisLahan, () => {})
          .putIfAbsent(row.jenisBantuan, () => {})
          .update(row.tipeData, (value) => value + row.nilai,
              ifAbsent: () => row.nilai);
    }

    // Convert aggregated data into the desired structure
    var result = <String, JenisPalawija>{};

    groupedByAll.forEach((jenisPalawija, lahanMap) {
      var lahanData = <String, JenisLahan>{};
      int totalJenisPalawija = 0;

      lahanMap.forEach((jenisLahan, bantuanMap) {
        var bantuanData = <String, JenisBantuan>{};
        int totalJenisLahan = 0;

        bantuanMap.forEach((jenisBantuan, tipeDataMap) {
          var tipeDataEntries = <String, TipeData>{};
          int totalJenisBantuan = 0;

          tipeDataMap.forEach((tipeData, nilai) {
            tipeDataEntries[tipeData] = TipeData(data: {tipeData: nilai});
            totalJenisBantuan += nilai;
          });

          bantuanData[jenisBantuan] =
              JenisBantuan(tipeData: tipeDataEntries, total: totalJenisBantuan);
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
  }
}
