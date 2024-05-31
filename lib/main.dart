import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/ui/initialization.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  bool isLogin = await UserLoginModel().getLogin();
  String? role = await UserLoginModel().getRole();
  runApp(MyApp(isLogin: isLogin, role: role));
}

class MyApp extends StatelessWidget {
  final bool? isLogin;
  final String? role;

  const MyApp({super.key, this.isLogin, this.role});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: "Application",
      builder: EasyLoading.init(),
      home: InitializationWrapper(isLogin: isLogin, role: role),
    );
  }
}

