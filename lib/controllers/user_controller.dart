import 'dart:developer';

import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:intl/intl.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/user_service.dart';

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

  String normalizeDate(String date) {
    final DateTime parsedDate = DateTime.parse(date);
    return DateFormat('dd MMMM yyyy', 'id_ID').format(parsedDate);
  }
}
