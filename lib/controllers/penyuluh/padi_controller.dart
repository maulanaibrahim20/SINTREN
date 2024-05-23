import 'dart:developer';

import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/helpers/penyuluh_dbhelper.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/pengairan_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/padi_service.dart';

class PadiController {
  List<String> jenisLahan = ["Lahan Sawah", "Lahan Non-Sawah"];
  List<String> bantuan = ["Bantuan Pemerintah", "Bantuan Non-Pemerintah"];
  List<String> tipeData = ["panen", "tanam", "puso/rusak"];
  List<String> jenisPadi = [
    "Hibrida",
    "Inhibrida",
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
      "jenis_padi": map['jenis_padi'],
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
      "jenis_padi": map['jenis_padi'],
      "date": map['date'],
      "id_jenis_pengairan": map['jenis_lahan'] == 'Lahan Non-Sawah'
          ? null
          : map['id_jenis_pengairan'],
      "tipe_data": map['tipe_data'],
      "nilai": map['nilai'],
    };

    log(data.toString());

    final result = await PadiService().update(data, dataId);

    if (!result) {
      EasyLoading.showToast("Gagal mengupdate data");
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

  Future<List<DesaModel>> getDesa() async {
    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query('desa');

    return List<DesaModel>.from(maps.map((map) => DesaModel.fromJson(map)));
  }

  Future<List<DetailPadiModel>> getDetailPadiByUser() async {
    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query('detailPadi');

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
}
