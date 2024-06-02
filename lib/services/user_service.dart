import 'dart:convert';
import 'dart:developer';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:http/http.dart';
import 'package:sintren_mobile/config/config_app.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/models/verify_model.dart';
import 'package:sqflite/sqflite.dart';

class UserService {
  Future<bool> login(
      {required String username, required String password}) async {
    final String url = '${ConfigApp().baseUrl}login';
    final Map<String, dynamic> data = {
      "username": username,
      "password": password,
    };

    try {
      final Response response = await post(
        Uri.parse(url),
        headers: {
          "Content-Type": "application/json",
        },
        body: jsonEncode(data),
      );

      if (response.statusCode != 200) {
        log("Login failed: ${response.statusCode}");
        return false;
      }

      final Map<String, dynamic> jsonResult = jsonDecode(response.body);
      if (jsonResult['data'] == null) {
        log("Login failed: user not found");
        return false;
      }

      final Map<String, dynamic> dataJson = jsonResult['data'];
      final Map<String, dynamic> detail = dataJson['detail'];
      final Map<String, dynamic>? kecamatan = dataJson['kecamatan'];

      updateUserLoginModel(dataJson, detail, kecamatan);
      log("Login successful");
      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Login error: $e");
      return false;
    }
  }

  void updateUserLoginModel(Map<String, dynamic> dataJson,
      Map<String, dynamic> detail, Map<String, dynamic>? kecamatan) {
    UserLoginModel()
      ..setLogin(true)
      ..setEmail(dataJson['email'])
      ..setName(dataJson['name'])
      ..setRole(dataJson['role_name'])
      ..setUserId(dataJson['id'])
      ..setUsername(dataJson['username'])
      ..setKecamatanId(detail['kecamatan_id'].toString())
      ..setKecamatanName(kecamatan?['name'] ?? "")
      ..setAddress(detail['alamat'])
      ..setPhone(detail['no_telp']);
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
      EasyLoading.showToast("Internal Server Error");
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
      EasyLoading.showToast("Internal Server Error");
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
      final kecamatan = dataJson['detail'];

      updateUserLoginModel(dataJson, detail, kecamatan);

      return true;
    } catch (e) {
      log("Error getting user: $e");
      EasyLoading.showToast("Internal Server Error");
      return false;
    }
  }

  Future<bool> getVerify() async {
    try {
      final db = await DatabaseHelper().database;
      await db.delete('verify');

      final Response result =
          await get(Uri.parse('${ConfigApp().baseUrl}getVerify'));

      if (result.statusCode != 200) {
        log("Failed to get verify: ${result.statusCode} - ${result.body}");
        return false;
      }

      final Map<String, dynamic> jsonResult = jsonDecode(result.body);
      if (jsonResult['data'] == null) {
        log("Failed to get verify: data is null");
        return false;
      }

      List<VerifyModel> padi = (jsonResult['data'] as List)
          .map((element) => VerifyModel.fromJson(element))
          .toList();

      if (padi.isNotEmpty) {
        Batch batch = db.batch();
        for (var item in padi) {
          batch.insert(
            'verify',
            item.toMap(),
            conflictAlgorithm: ConflictAlgorithm.replace,
          );
        }
        await batch.commit(noResult: true);
        log("Get verify sukses");
      }
      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Failed to save verify to database: $e");
      throw Exception("Internal Server Error");
    }
  }

  Future<bool> storeVerify(Map<String, dynamic> data) async {
    try {
      final Response result = await post(
        Uri.parse('${ConfigApp().baseUrl}verify/store'),
        headers: {
          "Content-Type": "application/json",
        },
        body: jsonEncode(data),
      );

      if (result.statusCode != 201) {
        log("Gagal menyimpan data: ${result.statusCode} - ${result.body}");
        return false;
      }

      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Gagal menyimpan data: $e");
      return false;
    }
  }

   Future<bool> updateVerify(Map<String, dynamic> data, String id) async {
    try {
      final Response result = await patch(
        Uri.parse('${ConfigApp().baseUrl}verify/update/$id'),
        headers: {
          "Content-Type": "application/json",
        },
        body: jsonEncode(data),
      );

      if (result.statusCode != 200) {
        log("Gagal mengupdate data: ${result.statusCode} - ${result.body}");
        return false;
      }

      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Gagal mengupdate data: $e");
      return false;
    }
  }


}
