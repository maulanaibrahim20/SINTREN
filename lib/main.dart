import 'package:firebase_core/firebase_core.dart';
import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/config/firebase_options.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/ui/initialization.dart';
import 'package:sintren_mobile/ui/penyuluh/histori_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/uptd/uptd_verify_view.dart';

final navigatorKey = GlobalKey<NavigatorState>();

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await Firebase.initializeApp(
    options: DefaultFirebaseOptions.currentPlatform,
  );
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
          navigatorKey: navigatorKey,
          home:
              InitializationWrapper(isLogin: widget.isLogin, role: widget.role),
          routes: {
            UptdVerifyView.route: (context) => const UptdVerifyView(),
            HistoriPenyuluhanView.route: (context) =>
                const HistoriPenyuluhanView()
          },
        );
      },
    );
  }
}
