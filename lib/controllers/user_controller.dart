import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/helpers/penyuluh_dbhelper.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/user_service.dart';

class UserController {
  TextEditingController name = TextEditingController();
  TextEditingController email = TextEditingController();
  TextEditingController username = TextEditingController();
  TextEditingController phone = TextEditingController();
  TextEditingController address = TextEditingController();
  TextEditingController oldPass = TextEditingController();
  TextEditingController newPass = TextEditingController();
  TextEditingController confirmPass = TextEditingController();
  TextEditingController usernameC = TextEditingController();
  TextEditingController passwordC = TextEditingController();

  Future<String> login() async {
    EasyLoading.show(status: "Loading...");
    final result = await UserService().login(
      username: usernameC.text,
      password: passwordC.text,
    );
    EasyLoading.dismiss();
    if (!result) {
      EasyLoading.showToast("Login gagal");
    }

    final role = await UserLoginModel().getRole();
    return role ?? "";
  }

  Future<void> updateProfil() async {
    EasyLoading.show(status: "Loading...");
    final id = await UserLoginModel().getUserId();
    final data = {
      "name": name.text,
      "email": email.text,
      "username": username.text,
      "alamat": address.text,
      "no_telp": phone.text
    };

    final result =
        await UserService().updateProfile(id: id.toString(), data: data);

    if (!result) {
      EasyLoading.showToast("Update Gagal");
    } else {
      await UserService().getUser(id: id.toString());
      EasyLoading.dismiss();
      EasyLoading.showToast("Update Berhasil");
    }
  }

  Future<void> changePassword() async {
    EasyLoading.show(status: "Loading...");
    final id = await UserLoginModel().getUserId();
    final data = {
      "current_password": oldPass.text,
      "new_password": newPass.text,
      "confirm_password": confirmPass.text,
    };

    final result =
        await UserService().changePassword(id: id.toString(), data: data);

    if (!result) {
      EasyLoading.showToast("Update Gagal");
    } else {
      EasyLoading.dismiss();
      EasyLoading.showToast("Update Berhasil");
    }
  }

  Future<void> getUser() async {
    EasyLoading.show(status: "Loading");
    final nameT = await UserLoginModel().getName();
    final emailT = await UserLoginModel().getEmail();
    final usernameT = await UserLoginModel().getUsername();
    final addressT = await UserLoginModel().getAddress();
    final phoneT = await UserLoginModel().getPhone();
    EasyLoading.dismiss();

    name.text = nameT.toString();
    email.text = emailT.toString();
    username.text = usernameT.toString();
    phone.text = phoneT.toString();
    address.text = addressT.toString();
  }

  Future<List<DesaModel>> getAssignment() async {
    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query('desa');

    return List<DesaModel>.from(maps.map((map) => DesaModel.fromJson(map)));
  }
}
