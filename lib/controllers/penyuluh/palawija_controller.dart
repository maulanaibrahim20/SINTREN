import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/helpers/penyuluh_dbhelper.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/pengairan_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/padi_service.dart';

class PalawijaController {
  late String selectedJenisLahanValue;
  late String selectedBantuanValue;
  late DesaModel? selectedDesaValue;
  late String selectedJenisPalawijaValue;
  late String selectedTipeDataValue;
  TextEditingController value = TextEditingController();
  late Future<List<DetailPadiModel>> detailPalawija;

  List<String> jenisLahan = ["Lahan Sawah", "Lahan Non-Sawah"];
  List<String> bantuan = ["Bantuan Pemerintah", "Bantuan Non-Pemerintah"];
  List<String> tipeData = ["panen", "tanam", "puso/rusak"];
  late Future<List<DesaModel>> desa;
  List<String> jenisPalawija = [
    "Hibrida",
    "Inhibrida",
  ];

  PalawijaController() {
    selectedJenisLahanValue = '';
    selectedBantuanValue = '';
    selectedDesaValue = null;
    selectedJenisPalawijaValue = '';
    selectedTipeDataValue = '';
  }

  String toCamelCase(String input) {
    if (input.isEmpty) {
      return input;
    }

    List<String> words = input.split(' ');
    List<String> capitalizedWords = words.map((word) {
      if (word.isEmpty) {
        return word;
      }
      return word[0].toUpperCase() + word.substring(1).toLowerCase();
    }).toList();

    return capitalizedWords.join(' ');
  }

  Future<void> store() async {
    EasyLoading.show(status: "Loading...");
    final id = await UserLoginModel().getUserId();
    final kecamatanId = await UserLoginModel().getKecamatanId();
    final data = {
      "user_id": id,
      "desa_id": selectedDesaValue?.id,
      "kecamatan_id": kecamatanId,
      "jenis_lahan": selectedJenisLahanValue,
      "jenis_bantuan": selectedBantuanValue,
      "jenis_palawija": selectedJenisPalawijaValue,
      "tipe_data": selectedTipeDataValue,
      "nilai": value.text
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

  Future<void> update(String dataId) async {
    EasyLoading.show(status: "Loading...");
    final id = await UserLoginModel().getUserId();
    final kecamatanId = await UserLoginModel().getKecamatanId();
    final data = {
      "user_id": id,
      "desa_id": selectedDesaValue?.id,
      "kecamatan_id": kecamatanId,
      "jenis_lahan": selectedJenisLahanValue,
      "jenis_bantuan": selectedBantuanValue,
      "jenis_padi": selectedJenisPalawijaValue,
      "tipe_data": selectedTipeDataValue,
      "nilai": value.text
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

  Future<List<PengairanModel>> getPengiran() async {
    await PadiService().getPengairan();

    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query('pengairan');

    return List<PengairanModel>.from(
        maps.map((map) => PengairanModel.fromJson(map)));
  }

  Future<List<DesaModel>> getAssignment() async {
    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query('desa');

    return List<DesaModel>.from(maps.map((map) => DesaModel.fromJson(map)));
  }

  Future<List<DetailPadiModel>> getDetailPadiByUser() async {
    await PadiService().getDetailPadiByUser();

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
