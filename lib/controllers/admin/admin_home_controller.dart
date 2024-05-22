import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/models/user_login_model.dart';

class AdminHomeController {
  Future<void> logout() async {
    EasyLoading.show(status: "Loading...");
    await UserLoginModel().clearPreferences();
    EasyLoading.dismiss();
  }
}