import 'dart:developer';

import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/helpers/penyuluh_dbhelper.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/kesimpulan_data_padi_model.dart';
import 'package:sintren_mobile/models/padi_model.dart';
import 'package:sintren_mobile/models/pengairan_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/padi_service.dart';

class PadiController {
  List<String> jenisLahan = ["sawah", "non sawah"];
  List<String> bantuan = ["bantuan pemerintah", "non bantuan pemerintah"];
  List<String> tipeData = ["panen", "tanam", "puso/rusak"];

  Future<bool> store(Map<String, dynamic> map) async {
    EasyLoading.show(status: "Loading...");
    final id = await UserLoginModel().getUserId();
    final kecamatanId = await UserLoginModel().getKecamatanId();
    final data = {
      "user_id": id,
      "desa_id": map['desa_id'],
      "kecamatan_id": kecamatanId,
      "jenis_lahan": map['jenis_lahan'],
      "jenis_bantuan": map['jenis_bantuan'],
      "id_jenis_padi": map['id_jenis_padi'],
      "date": map['date'],
      "id_jenis_pengairan": map['jenis_lahan'] == 'Lahan Non-Sawah'
          ? null
          : map['id_jenis_pengairan'],
      "tipe_data": map['tipe_data'],
      "nilai": map['nilai'],
    };

    log(data.toString());

    final result = await PadiService().store(data);

    if (!result) {
      EasyLoading.showToast("Gagal menyimpan data");
      return false;
    } else {
      EasyLoading.dismiss();
      EasyLoading.showToast("Berhasil menyimpan data");
      return true;
    }
  }

  Future<bool> update(String dataId, Map<String, dynamic> map) async {
    EasyLoading.show(status: "Loading...");
    final id = await UserLoginModel().getUserId();
    final kecamatanId = await UserLoginModel().getKecamatanId();
    final data = {
      "user_id": id,
      "desa_id": map['desa_id'],
      "kecamatan_id": kecamatanId,
      "jenis_lahan": map['jenis_lahan'],
      "jenis_bantuan": map['jenis_bantuan'],
      "id_jenis_padi": map['id_jenis_padi'],
      "date": map['date'],
      "id_jenis_pengairan": map['jenis_lahan'] == 'Lahan Non-Sawah'
          ? null
          : map['id_jenis_pengairan'],
      "tipe_data": map['tipe_data'],
      "nilai": map['nilai'],
    };

    final result = await PadiService().update(data, dataId);

    if (!result) {
      EasyLoading.showToast("Gagal mengupdate data");
      return false;
    } else {
      final db = await PenyuluhDatabaseHelper().database;

      final localData = Map<String, dynamic>.from(data)
        ..removeWhere((key, value) => key == "user_id");

      await db.update(
        'detailPadi',
        localData,
        where: 'id = ?',
        whereArgs: [dataId],
      );
      EasyLoading.dismiss();
      EasyLoading.showToast("Berhasil mengupdate data");
      return true;
    }
  }

  Future<void> deleteDetailById(int id) async {
    EasyLoading.show(status: "Loading...");
    final result = await PadiService().deletaDetailById(id);

    if (!result) {
      EasyLoading.showToast("Gagal menghapus data");
    } else {
      final db = await PenyuluhDatabaseHelper().database;
      await db.delete(
        'detailPadi',
        where: "id = ?",
        whereArgs: [id],
      );
      EasyLoading.dismiss();
      EasyLoading.showToast("Berhasil menghapus data");
    }
  }

  Future<List<PengairanModel>> getPengairan() async {
    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query('pengairan');

    return List<PengairanModel>.from(
        maps.map((map) => PengairanModel.fromJson(map)));
  }

  Future<List<PadiModel>> getPadi() async {
    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query('padi');

    return List<PadiModel>.from(maps.map((map) => PadiModel.fromJson(map)));
  }

  Future<List<DesaModel>> getDesa() async {
    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query('desa');

    return List<DesaModel>.from(maps.map((map) => DesaModel.fromJson(map)));
  }

  Future<List<DetailPadiModel>> getDetailPadiByUser(
      String date, String desaId) async {
    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query(
      'detailPadi',
      where: 'date LIKE ? AND desa_id = ?',
      whereArgs: ['%$date%', desaId],
    );

    return List<DetailPadiModel>.from(
        maps.map((map) => DetailPadiModel.fromMap(map)));
  }

  Future<DetailPadiModel?> getDetailPadiById(int? id) async {
    final db = await PenyuluhDatabaseHelper().database;
    final maps = await db.query(
      'detailPadi',
      where: 'id = ?',
      whereArgs: [id],
    );

    if (maps.isNotEmpty) {
      return DetailPadiModel.fromMap(maps.first);
    } else {
      return null;
    }
  }

  Future<List<Map<String, dynamic>>> getGroupedData(
      String date, String desaId) async {
    final db = await PenyuluhDatabaseHelper().database;

    final List<Map<String, dynamic>> result = await db.rawQuery('''
    SELECT pengairan_name, jenis_lahan, tipe_data, SUM(nilai) as total_nilai
    FROM detailPadi
    WHERE date LIKE ? AND desa_id = ?
    GROUP BY pengairan_name, jenis_lahan, tipe_data
  ''', ['%$date%', desaId]);

    return result;
  }

  Future<Map<String, JenisPadi>> getKesimpulanDataPadi(
      String date, String desaId) async {
    List<DetailPadiModel> groupedData =
        await PadiController().getDetailPadiByUser(date, desaId);

    // Data structure to hold the aggregated data
    var groupedByAll = <String, Map<String, Map<String, Map<String, int>>>>{};

    // Aggregate the data
    for (var row in groupedData) {
      groupedByAll
          .putIfAbsent(row.padiName, () => {})
          .putIfAbsent(row.jenisLahan, () => {})
          .putIfAbsent(row.jenisBantuan, () => {})
          .update(row.tipeData, (value) => value + row.nilai,
              ifAbsent: () => row.nilai);
    }

    // Convert aggregated data into the desired structure
    var result = <String, JenisPadi>{};

    groupedByAll.forEach((jenisPadi, lahanMap) {
      var lahanData = <String, JenisLahan>{};
      int totalJenisPadi = 0;

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
        totalJenisPadi += totalJenisLahan;
      });

      result[jenisPadi] =
          JenisPadi(jenisLahan: lahanData, total: totalJenisPadi);
    });

    return result;
  }

  Future<Map<String, JenisPengairan>> getKesimpulanDataPengairan(
      String date, String desaId) async {
    List<DetailPadiModel> groupedData =
        await PadiController().getDetailPadiByUser(date, desaId);

    var groupedByPengairan = <String, JenisPengairan>{};

    for (var row in groupedData) {
      if (row.jenisLahan != 'Lahan Sawah') continue;

      String jenisPengairan = row.pengairanName;
      String tipeData = row.tipeData;
      int nilai = row.nilai;

      var jenisPengairanEntry = groupedByPengairan.putIfAbsent(
          jenisPengairan, () => JenisPengairan(pengairanData: {}));
      var pengairanDataEntry = jenisPengairanEntry.pengairanData
          .putIfAbsent(tipeData, () => PengairanData(tipeData: {}, total: 0));
      var tipeDataEntry = pengairanDataEntry.tipeData
          .putIfAbsent(tipeData, () => TipeData(data: {}));

      tipeDataEntry.data[tipeData] =
          (tipeDataEntry.data[tipeData] ?? 0) + nilai;
      pengairanDataEntry.total += nilai;
    }

    // Calculate total values for each JenisPengairan
    groupedByPengairan.forEach((jenisPengairan, jenisPengairanEntry) {
      int totalJenisPengairan = jenisPengairanEntry.pengairanData.values
          .fold(0, (sum, pengairanDataEntry) => sum + pengairanDataEntry.total);
      jenisPengairanEntry.total = totalJenisPengairan;
    });

    return groupedByPengairan;
  }
}
