import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:intl/intl.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/penyuluh/penyuluh_controller.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/models/verify_model.dart';
import 'package:sintren_mobile/services/user_service.dart';
import 'package:sqflite/sqflite.dart';

class UserController {
  Future<void> logout() async {
    EasyLoading.show(status: "Loading...");
    await UserLoginModel().clearPreferences();
    EasyLoading.dismiss();
  }

  String dateNow() {
    DateTime now = DateTime.now();
    String formattedDate =
        DateFormat('MMMM yyyy').format(now); // Format bulan dan tahun

    return formattedDate;
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

  Future<String> login(
      {required String username, required String password}) async {
    EasyLoading.show(status: "Loading...");
    try {
      final bool result = await UserService().login(
        username: username,
        password: password,
      );

      if (!result) {
        EasyLoading.showToast("Login gagal");
        return "";
      }

      final String? role = await UserLoginModel().getRole();
      final ValueNotifier<String> statusNotifier =
          ValueNotifier<String>('Memulai aplikasi...');
      if (role == "PENYULUH") {
        await PenyuluhController()
            .synchronizeData(statusNotifier)
            .timeout(const Duration(minutes: 1));
      } else if (role == "PERTANIAN" || role == "UPTD") {
        await AdminController()
            .synchronizeData(statusNotifier)
            .timeout(const Duration(minutes: 1));
      }
      EasyLoading.dismiss();
      return role ?? "";
    } catch (e) {
      EasyLoading.dismiss();
      EasyLoading.showToast("Internal Server Error");
      log("Login error: $e");
      return "";
    }
  }

  Future<void> updateProfil(Map<String, dynamic> newData) async {
    EasyLoading.show(status: "Loading...");
    try {
      final String? id = await UserLoginModel().getUserId();
      final Map<String, dynamic> data = {
        "name": newData['name'],
        "email": newData['email'],
        "username": newData['username'],
        "alamat": newData['address'],
        "no_telp": newData['phone']
      };

      final bool result =
          await UserService().updateProfile(id: id.toString(), data: data);

      if (!result) {
        EasyLoading.showToast("Update Gagal");
      } else {
        await UserService().getUser(id: id.toString());
        EasyLoading.showToast("Update Berhasil");
      }
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Update profile error: $e");
    } finally {
      EasyLoading.dismiss();
    }
  }

  Future<void> changePassword({
    required String oldPass,
    required String newPass,
    required String confirmPass,
  }) async {
    EasyLoading.show(status: "Loading...");
    try {
      final String? id = await UserLoginModel().getUserId();
      final Map<String, dynamic> data = {
        "current_password": oldPass,
        "new_password": newPass,
        "confirm_password": confirmPass,
      };

      final bool result =
          await UserService().changePassword(id: id.toString(), data: data);

      if (!result) {
        EasyLoading.showToast("Update Gagal");
      } else {
        EasyLoading.showToast("Update Berhasil");
      }
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Change password error: $e");
    } finally {
      EasyLoading.dismiss();
    }
  }

  Future<Map<String, dynamic>> getUser() async {
    EasyLoading.show(status: "Loading...");
    try {
      final String? name = await UserLoginModel().getName();
      final String? email = await UserLoginModel().getEmail();
      final String? username = await UserLoginModel().getUsername();
      final String? address = await UserLoginModel().getAddress();
      final String? phone = await UserLoginModel().getPhone();

      return {
        'name': name,
        'email': email,
        'username': username,
        'address': address,
        'phone': phone,
      };
    } catch (e) {
      log("Get user error: $e");
      return {};
    } finally {
      EasyLoading.dismiss();
    }
  }

  String convertDate(String date) {
    final DateTime parsedDate = DateTime.parse('$date-01');
    return DateFormat('MMMM yyyy', 'id_ID').format(parsedDate);
  }

  Future<List<VerifyModel>> getVerify() async {
    try {
      final db = await DatabaseHelper().database;
      final kecamatanId = await UserLoginModel().getKecamatanId();
      final List<Map<String, dynamic>> maps = await db.query(
        'verify',
        where: 'kecamatan_id LIKE ?',
        whereArgs: ['%$kecamatanId%'],
      );
      return List<VerifyModel>.from(
          maps.map((map) => VerifyModel.fromJson(map)));
    } catch (e) {
      log("Get verify error: $e");
      return [];
    }
  }

  bool getStatusVerify(
      List<VerifyModel> listVerify, String date, String desaId) {
    if (listVerify.isEmpty) {
      return false;
    }

    final filteredList = listVerify.where((verifyModel) {
      return verifyModel.date == date && verifyModel.desaId == desaId;
    });

    if (filteredList.isEmpty) {
      return false;
    }

    final VerifyModel verify = filteredList.first;

    return verify.isVerify == 'true';
  }

  VerifyModel? getDataVerify(
      List<VerifyModel> listVerify, String date, String desaId) {
    if (listVerify.isEmpty) {
      return null;
    }

    final filteredList = listVerify.where((verifyModel) {
      return verifyModel.date == date && verifyModel.desaId == desaId;
    });

    if (filteredList.isEmpty) {
      return null;
    }

    final VerifyModel verify = filteredList.first;

    return verify;
  }

  Future<bool> storeVerify(Map<String, dynamic> map) async {
    EasyLoading.show(status: "Loading...");
    try {
      final String? id = await UserLoginModel().getUserId();
      final String? kecamatanId = await UserLoginModel().getKecamatanId();

      final data = {
        "user_id": id,
        "desa_id": map['desa_id'],
        "kecamatan_id": kecamatanId,
        "date": map['date'],
        "isVerify": map['isVerify'],
      };

      final result = await UserService().storeVerify(data);

      if (!result) {
        EasyLoading.showToast("Gagal menyimpan data");
        return false;
      }

      final dataForDatabase = Map<String, dynamic>.from(data)
        ..['isVerify'] = map['isVerify'].toString();

      final db = await DatabaseHelper().database;
      await db.insert(
        'verify',
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

  Future<bool> updateVerify(String dataId, Map<String, dynamic> map) async {
    EasyLoading.show(status: "Loading...");
    try {
      final id = await UserLoginModel().getUserId();
      final kecamatanId = await UserLoginModel().getKecamatanId();
      final data = {
        "user_id": id,
        "desa_id": map['desa_id'],
        "kecamatan_id": kecamatanId,
        "date": map['date'],
        "isVerify": map['isVerify'],
      };

      final result = await UserService().updateVerify(data, dataId);

      if (!result) {
        EasyLoading.showToast("Gagal mengupdate data");
        return false;
      }

      final db = await DatabaseHelper().database;
      final localData = Map<String, dynamic>.from(data)
        ..['isVerify'] = map['isVerify'].toString();

      await db.update(
        'verify',
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
}
