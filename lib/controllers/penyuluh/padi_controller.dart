import 'dart:developer';

import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/kesimpulan_data_padi_model.dart';
import 'package:sintren_mobile/models/padi_model.dart';
import 'package:sintren_mobile/models/pengairan_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/penyuluh/padi_service.dart';
import 'package:sqflite/sqflite.dart';

class PadiController {
  final List<String> jenisLahan = ["sawah", "non sawah"];
  final List<String> bantuan = ["bantuan pemerintah", "non bantuan pemerintah"];
  final List<String> tipeData = ["panen", "tanam", "puso/rusak"];

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
        "id_jenis_padi": map['id_jenis_padi'],
        "date": map['date'],
        "id_jenis_pengairan": map['jenis_lahan'] == 'non sawah'
            ? null
            : map['id_jenis_pengairan'],
        "tipe_data": map['tipe_data'],
        "nilai": map['nilai'],
      };

      final result = await PadiService().store(data);

      if (!result) {
        EasyLoading.showToast("Gagal menyimpan data");
        return false;
      }

      final dataForDatabase = Map<String, dynamic>.from(data)
        ..['id_jenis_pengairan'] ??= ''
        ..['desa_name'] = map['desa_name']
        ..['pengairan_name'] = map['pengairan_name'] ?? ''
        ..['padi_name'] = map["padi_name"];

      final db = await DatabaseHelper().database;
      await db.insert(
        'detailPadi',
        dataForDatabase,
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
        "id_jenis_padi": map['id_jenis_padi'],
        "date": map['date'],
        "id_jenis_pengairan": map['jenis_lahan'] == 'non sawah'
            ? null
            : map['id_jenis_pengairan'],
        "tipe_data": map['tipe_data'],
        "nilai": map['nilai'],
      };

      final result = await PadiService().update(data, dataId);

      if (!result) {
        EasyLoading.showToast("Gagal mengupdate data");
        return false;
      }

      final db = await DatabaseHelper().database;
      final localData = Map<String, dynamic>.from(data)..remove("user_id");

      await db.update(
        'detailPadi',
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
      final result = await PadiService().deleteDetailById(id);

      if (!result) {
        EasyLoading.showToast("Gagal menghapus data");
        return;
      }

      final db = await DatabaseHelper().database;
      await db.delete(
        'detailPadi',
        where: "id = ?",
        whereArgs: [id],
      );

      EasyLoading.showToast("Berhasil menghapus data");
    } catch (e) {
      EasyLoading.showToast("Gagal menghapus data");
      log("Delete error: $e");
    } finally {
      EasyLoading.dismiss();
    }
  }

  Future<List<PengairanModel>> getPengairan() async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query('pengairan');

      return List<PengairanModel>.from(
          maps.map((map) => PengairanModel.fromJson(map)));
    } catch (e) {
      log("Get pengairan error: $e");
      return [];
    }
  }

  Future<List<PadiModel>> getPadi() async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query('padi');

      return List<PadiModel>.from(maps.map((map) => PadiModel.fromJson(map)));
    } catch (e) {
      log("Get padi error: $e");
      return [];
    }
  }

  Future<List<DetailPadiModel>> getDetailPadiByUser(
      String date, String desaId) async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query(
        'detailPadi',
        where: 'date LIKE ? AND desa_id = ?',
        whereArgs: ['%$date%', desaId],
        orderBy: 'date DESC',
      );
      return List<DetailPadiModel>.from(
          maps.map((map) => DetailPadiModel.fromMap(map)));
    } catch (e) {
      log("Get detail padi by user error: $e");
      return [];
    }
  }

  Future<DetailPadiModel?> getDetailPadiById(int? id) async {
    try {
      final db = await DatabaseHelper().database;
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
    } catch (e) {
      log("Get detail padi by id error: $e");
      return null;
    }
  }

  Future<Map<String, JenisPadi>> getKesimpulanDataPadi(
      String date, String desaId) async {
    try {
      List<DetailPadiModel> groupedData =
          await getDetailPadiByUser(date, desaId);

      var groupedByAll = <String, Map<String, Map<String, Map<String, int>>>>{};

      for (var row in groupedData) {
        groupedByAll
            .putIfAbsent(row.padiName, () => {})
            .putIfAbsent(row.jenisLahan, () => {})
            .putIfAbsent(row.jenisBantuan, () => {})
            .update(row.tipeData, (value) => value + row.nilai,
                ifAbsent: () => row.nilai);
      }

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

            bantuanData[jenisBantuan] = JenisBantuan(
                tipeData: tipeDataEntries, total: totalJenisBantuan);
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
    } catch (e) {
      log("Get kesimpulan data padi error: $e");
      return {};
    }
  }

  Future<Map<String, JenisPengairan>> getKesimpulanDataPengairan(
      String date, String desaId) async {
    try {
      List<DetailPadiModel> groupedData =
          await getDetailPadiByUser(date, desaId);

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

      groupedByPengairan.forEach((jenisPengairan, jenisPengairanEntry) {
        int totalJenisPengairan = jenisPengairanEntry.pengairanData.values.fold(
            0, (sum, pengairanDataEntry) => sum + pengairanDataEntry.total);
        jenisPengairanEntry.total = totalJenisPengairan;
      });

      return groupedByPengairan;
    } catch (e) {
      log("Get kesimpulan data pengairan error: $e");
      return {};
    }
  }
}
