import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/ui/initialization.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  bool isLogin = await UserLoginModel().getLogin();
  String? role = await UserLoginModel().getRole();
  runApp(MyApp(isLogin: isLogin, role: role));
}

class MyApp extends StatefulWidget {
  final bool? isLogin;
  final String? role;

  const MyApp({super.key, this.isLogin, this.role});

  @override
  State<MyApp> createState() => _MyAppState();
}

class _MyAppState extends State<MyApp> {
  @override
  Widget build(BuildContext context) {
    return ScreenUtilInit(
      minTextAdapt: true,
      splitScreenMode: true,
      designSize: const Size(
          412, 915), // Ukuran desain sesuai dengan mockup yang digunakan
      builder: (context, child) {
        return MaterialApp(
          debugShowCheckedModeBanner: false,
          title: "Application",
          builder: EasyLoading.init(),
          home:
              InitializationWrapper(isLogin: widget.isLogin, role: widget.role),
        );
      },
    );
  }
}
