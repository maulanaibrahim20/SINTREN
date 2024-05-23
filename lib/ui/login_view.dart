import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/ui/admin/admin_landing_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/penyuluh_home_view.dart';

class LoginView extends StatefulWidget {
  const LoginView({super.key});

  @override
  State<LoginView> createState() => _LoginViewState();
}

class _LoginViewState extends State<LoginView> {
  final loginC = UserController();
  final formKey = GlobalKey<FormState>();
  TextEditingController username = TextEditingController();
  TextEditingController password = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      resizeToAvoidBottomInset: false,
      body: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Container(
            width: MediaQuery.of(context).size.width,
            height: MediaQuery.of(context).size.height * 0.4,
            decoration: BoxDecoration(
              gradient: ColorTheme().linearColor,
              borderRadius: const BorderRadius.vertical(
                bottom: Radius.elliptical(200, 30),
              ),
            ),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                ClipRect(
                  child: Align(
                    alignment: Alignment.center,
                    heightFactor: 0.5,
                    child: Image.asset(
                      'assets/images/pertanian.png',
                      width: 200,
                      height: 200,
                      fit: BoxFit.fill,
                    ),
                  ),
                ),
                Text(
                  "SINTREN",
                  style: StyleTheme()
                      .styleWhite
                      .copyWith(fontSize: 32, fontWeight: FontWeight.bold),
                ),
              ],
            ),
          ),
          const SizedBox(height: 50),
          Text(
            "LOGIN",
            style: StyleTheme()
                .stylePrimary
                .copyWith(fontSize: 30, fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 20),
          Text(
            "Silahkan Login Terlebih Dahulu",
            style: StyleTheme()
                .styleBlack
                .copyWith(fontSize: 16, fontWeight: FontWeight.w500),
          ),
          Form(
              key: formKey,
              child: Padding(
                padding: const EdgeInsets.all(20),
                child: Column(
                  children: [
                    TextFormField(
                      controller: username,
                      keyboardType: TextInputType.name,
                      decoration: InputDecoration(
                        isDense: true,
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(30),
                        ),
                        prefixIcon: Icon(
                          Icons.person,
                          color: ColorTheme().primaryColor,
                          size: 25,
                        ),
                        hintText: "Username/Email",
                        labelText: "Username/Email",
                      ),
                      validator: (value) {
                        return value == null || value.isEmpty
                            ? "username/email tidak boleh kosong"
                            : null;
                      },
                    ),
                    const SizedBox(
                      height: 20,
                    ),
                    TextFormField(
                      controller: password,
                      keyboardType: TextInputType.name,
                      obscureText: true,
                      decoration: InputDecoration(
                        isDense: true,
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(30),
                        ),
                        prefixIcon: Icon(
                          Icons.lock,
                          color: ColorTheme().primaryColor,
                          size: 25,
                        ),
                        hintText: "Password",
                        labelText: "Password",
                      ),
                      validator: (value) {
                        return value == null || value.isEmpty
                            ? "password tidak boleh kosong"
                            : null;
                      },
                    ),
                    const SizedBox(
                      height: 20,
                    ),
                    Container(
                      width: MediaQuery.of(context).size.width,
                      height: 60,
                      decoration: BoxDecoration(
                        borderRadius: BorderRadius.circular(30),
                        gradient: ColorTheme().linearColor,
                      ),
                      child: ElevatedButton.icon(
                        onPressed: () async {
                          if (formKey.currentState!.validate()) {
                            await loginC.login(username: username.text, password: password.text).then((value) {
                              if (value == "PENYULUH") {
                                Navigator.pushAndRemoveUntil(
                                  context,
                                  MaterialPageRoute(
                                      builder: (_) => const PenyuluhHomeView()),
                                  (route) => false,
                                );
                                EasyLoading.showSuccess("Berhasil Login");
                              } else if (value == "UPTD" ||
                                  value == "PERTANIAN") {
                                Navigator.pushAndRemoveUntil(
                                  context,
                                  MaterialPageRoute(
                                      builder: (_) => const AdminLandingView()),
                                  (route) => false,
                                );
                                EasyLoading.showSuccess("Berhasil Login");
                              }
                            });
                          }
                        },
                        icon: Icon(Icons.login_rounded,
                            color: ColorTheme().whiteColor),
                        label: Text(
                          'LOGIN',
                          style: StyleTheme().styleWhite.copyWith(
                                fontSize: 14,
                                fontWeight: FontWeight.bold,
                              ),
                        ),
                        style: ElevatedButton.styleFrom(
                          backgroundColor: Colors.transparent,
                          side: BorderSide(
                              color: ColorTheme().primaryColor, width: 2),
                          shape: RoundedRectangleBorder(
                            borderRadius: BorderRadius.circular(30),
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ))
        ],
      ),
    );
  }
}
