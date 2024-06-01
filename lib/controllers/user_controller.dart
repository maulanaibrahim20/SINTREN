import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'package:intl/intl.dart';
import 'package:sintren_mobile/helpers/penyuluh_dbhelper.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/padi_service.dart';
import 'package:sintren_mobile/services/palawija_service.dart';
import 'package:sintren_mobile/services/user_service.dart';

class UserController {
  Future<void> logout() async {
    EasyLoading.show(status: "Loading...");
    await UserLoginModel().clearPreferences();
    EasyLoading.dismiss();
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

      await Future.wait([
        PadiService().getPengairan(),
        PadiService().getPadi(),
        PalawijaService().getPalawija(),
        UserService().getDataPenyuluhanDesa(),
        PadiService().getDetailPadiByUser(),
        PalawijaService().getDetailPalawijaByUser(),
      ]);

      final String? role = await UserLoginModel().getRole();
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

  Future<List<DesaModel>> getDesa() async {
    try {
      final db = await PenyuluhDatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query('desa');

      return maps.map((map) => DesaModel.fromJson(map)).toList();
    } catch (e) {
      log("Get desa error: $e");
      return [];
    }
  }

  Future<List<HistoriPenyuluhanModel>> getHistoriPenyuluhan() async {
    try {
      final db = await PenyuluhDatabaseHelper().database;
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
      final db = await PenyuluhDatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query('desa');

      return maps.map((map) => LuasWilayahModel.fromMap(map)).toList();
    } catch (e) {
      log("Get luas lahan desa error: $e");
      return [];
    }
  }

  String convertDate(String date) {
    final DateTime parsedDate = DateTime.parse('$date-01');
    return DateFormat('MMMM yyyy', 'id_ID').format(parsedDate);
  }

  Future<List<HistoriPenyuluhanModel>> getHistoriPenyuluhanBulanIni() async {
    try {
      final db = await PenyuluhDatabaseHelper().database;
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
            SUM(nilai) AS total_nilai
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
      await UserService().getDataPenyuluhanDesa();

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
