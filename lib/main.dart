import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/padi_service.dart';
import 'package:sintren_mobile/services/user_service.dart';
import 'package:sintren_mobile/ui/admin/admin_landing_view.dart';
import 'package:sintren_mobile/ui/login_view.dart';
import 'package:sintren_mobile/ui/penyuluh/penyuluh_home_view.dart';
import 'package:sintren_mobile/ui/splash_screen.dart';

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

  Future<void> _initializeData() async {
    await initializeDateFormatting('id_ID', null);
    await UserService().getAssignment();
    await PadiService().getPengairan();
    await PadiService().getDetailPadiByUser();
  }

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: "Application",
      builder: EasyLoading.init(),
      home: FutureBuilder(
        future: _initializeData(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const SplashScreen();
          } else {
            // return AdminLandingView();
            if (isLogin ?? false) {
              if (role == "PENYULUH") {
                return const PenyuluhHomeView();
              } else if (role == "PERTANIAN" || role == "UPTD") {
                return const AdminLandingView();
              }
            }
            return const LoginView();
          }
        },
      ),
    );
  }
}
