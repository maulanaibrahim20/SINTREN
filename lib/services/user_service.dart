import 'dart:convert';
import 'dart:developer';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:http/http.dart';
import 'package:sintren_mobile/config/config_app.dart';
import 'package:sintren_mobile/models/user_login_model.dart';

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
      final Map<String, dynamic>? kecamatan =
          dataJson['kecamatan'] != "" ? dataJson['kecamatan'] : null;

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

  Future<bool> verify(Map<String, dynamic> data, String id) async {
    try {
      final Response result = await patch(
        Uri.parse('${ConfigApp().baseUrl}verify/$id'),
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

  Future<bool> checkNotelp(String number) async {
    final String url = '${ConfigApp().baseUrl}checkNotelp';
    final Map<String, dynamic> data = {
      "no_telp": number,
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
        log("Number not found: ${response.statusCode}");
        return false;
      }

      final Map<String, dynamic> jsonResult = jsonDecode(response.body);
      if (jsonResult['user'] == null) {
        log("Login failed: user not found");
        return false;
      }
      return true;
    } catch (e) {
      EasyLoading.showToast("Internal Server Error");
      log("Check Error: $e");
      return false;
    }
  }
}
