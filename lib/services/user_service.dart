import 'dart:convert';
import 'dart:developer';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:http/http.dart';
import 'package:sintren_mobile/config/config_app.dart';
import 'package:sintren_mobile/helpers/penyuluh_dbhelper.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sqflite/sqflite.dart';

class UserService {
  Future<bool> login(
      {required String username, required String password}) async {
    try {
      final Map<String, dynamic> data = {
        "username": username,
        "password": password,
      };

      final Response result = await post(
        Uri.parse('${ConfigApp().baseUrl}login'),
        headers: {
          "Content-Type": "application/json",
        },
        body: jsonEncode(data),
      );

      log("Result body => ${result.body}");

      if (result.statusCode != 200) {
        return false;
      }

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);
      final dataJson = jsonResult['data'];
      final detail = dataJson['detail'];

      // Update the UserLoginModel with the retrieved data
      UserLoginModel()
        ..setLogin(true)
        ..setEmail(dataJson['email'])
        ..setName(dataJson['name'])
        ..setRole(dataJson['role_name'])
        ..setUserId(dataJson['id'])
        ..setUsername(dataJson['username'])
        ..setKecamatanId(detail['kecamatan_id'].toString())
        ..setAddress(detail['alamat'])
        ..setPhone(detail['no_telp']);

      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Login error: $e");
      return false;
    }
  }

  Future<bool> updateProfile({
    required String id,
    required Map<String, dynamic> data,
  }) async {
    try {
      final Response result = await patch(
        Uri.parse('${ConfigApp().baseUrl}users/$id'),
        headers: {
          "Content-Type": "application/json",
        },
        body: jsonEncode(data),
      );

      if (result.statusCode != 200) {
        log("Failed to update profile: ${result.statusCode} - ${result.body}");
        return false;
      }

      return true;
    } catch (e) {
      log("Error updating profile: $e");
      EasyLoading.showToast("Failed to update profile");
      return false;
    }
  }

  Future<bool> changePassword({
    required String id,
    required Map<String, dynamic> data,
  }) async {
    try {
      final Response result = await patch(
        Uri.parse('${ConfigApp().baseUrl}changePassword/$id'),
        headers: {
          "Content-Type": "application/json",
        },
        body: jsonEncode(data),
      );

      if (result.statusCode != 200) {
        log("Failed to update profile: ${result.statusCode} - ${result.body}");
        return false;
      }

      return true;
    } catch (e) {
      log("Error updating profile: $e");
      EasyLoading.showToast("Failed to update profile");
      return false;
    }
  }

  Future<bool> getUser({required String id}) async {
    try {
      final Response result = await get(
        Uri.parse('${ConfigApp().baseUrl}getUserById/$id'),
      );

      if (result.statusCode != 200) {
        log("Failed to get user: ${result.statusCode} - ${result.body}");
        return false;
      }

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);
      final dataJson = jsonResult['data'];
      final detail = dataJson['detail'];

      UserLoginModel()
        ..setLogin(true)
        ..setEmail(dataJson['email'])
        ..setName(dataJson['name'])
        ..setRole(dataJson['role_name'])
        ..setUserId(dataJson['id'])
        ..setUsername(dataJson['username'])
        ..setKecamatanId(detail['kecamatan_id'].toString())
        ..setAddress(detail['alamat'])
        ..setPhone(detail['no_telp']);

      return true;
    } catch (e) {
      log("Error getting user: $e");
      EasyLoading.showToast("Failed to get user data");
      return false;
    }
  }

  Future<void> getAssignment() async {
    try {
      final db = await PenyuluhDatabaseHelper().database;
      await db.delete('desa');

      final id = await UserLoginModel().getUserId();
      final Response result = await get(
        Uri.parse('${ConfigApp().baseUrl}getAssignment/$id'),
      );

      if (result.statusCode != 200) {
        log(
            "Failed to get desa: ${result.statusCode} - ${result.body}");
      }

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);

      List<DesaModel> desa = (jsonResult['data'] as List)
          .map((element) => DesaModel.fromJson(element))
          .toList();

      Batch batch = db.batch();
      for (var item in desa) {
        batch.insert('desa', item.toMap());
      }
      await batch.commit(noResult: true);
    } catch (e) {
      log("Failed to getAssignment: $e");
    }
  }
}
