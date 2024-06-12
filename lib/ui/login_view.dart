import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/initialization.dart';

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

  bool isKeyboardVisible = false;
  final FocusNode _fnUsername = FocusNode();
  final FocusNode _fnPassword = FocusNode();

  @override
  void initState() {
    super.initState();
    _fnUsername.addListener(_onFocusChange);
    _fnPassword.addListener(_onFocusChange);
  }

  @override
  void dispose() {
    _fnUsername.removeListener(_onFocusChange);
    _fnPassword.removeListener(_onFocusChange);
    super.dispose();
  }

  void _onFocusChange() {
    setState(() {
      isKeyboardVisible = _fnUsername.hasFocus || _fnPassword.hasFocus;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      resizeToAvoidBottomInset: true,
      body: SingleChildScrollView(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.start,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            Container(
              width: MediaQuery.of(context).size.width,
              height: isKeyboardVisible ? 100 : 320,
              decoration: BoxDecoration(
                gradient: ColorTheme().linearColor,
                borderRadius: const BorderRadius.vertical(
                  bottom: Radius.elliptical(200, 30),
                ),
              ),
              child: Visibility(
                visible: !isKeyboardVisible,
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
            ),
            const SizedBox(height: 40),
            Card(
              surfaceTintColor: ColorTheme().whiteColor,
              margin: const EdgeInsets.symmetric(horizontal: 20),
              elevation: isKeyboardVisible ? 5 : 0,
              child: Container(
                padding: const EdgeInsets.symmetric(vertical: 20),
                height: 400,
                child: Column(
                  children: [
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
                                focusNode: _fnUsername,
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
                                focusNode: _fnPassword,
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
                                      await loginC
                                          .login(
                                              username: username.text,
                                              password: password.text)
                                          .then((value) {
                                        Navigator.pushAndRemoveUntil(
                                          context,
                                          MaterialPageRoute(
                                              builder: (_) =>
                                                  InitializationWrapper(
                                                    isLogin: true,
                                                    role: value,
                                                  )),
                                          (route) => false,
                                        );
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
                                        color: ColorTheme().primaryColor,
                                        width: 2),
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(30),
                                    ),
                                  ),
                                ),
                              ),
                            ],
                          ),
                        )),
                  ],
                ),
              ),
            )
          ],
        ),
      ),
    );
  }
}
