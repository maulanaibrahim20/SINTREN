import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
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
              height: isKeyboardVisible ? 100.h : 320.h,
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
                          width: 200.w,
                          height: 200.h,
                          fit: BoxFit.fill,
                        ),
                      ),
                    ),
                    Text(
                      "SINTREN",
                      style: StyleTheme().styleWhite.copyWith(
                          fontSize: 32.sp, fontWeight: FontWeight.bold),
                    ),
                  ],
                ),
              ),
            ),
            SizedBox(height: 40.h),
            Card(
              surfaceTintColor: ColorTheme().whiteColor,
              margin: EdgeInsets.symmetric(horizontal: 20.w),
              elevation: isKeyboardVisible ? 5 : 0,
              child: Container(
                padding: EdgeInsets.symmetric(vertical: 20.h),
                child: Column(
                  children: [
                    Text(
                      "LOGIN",
                      style: StyleTheme().stylePrimary.copyWith(
                          fontSize: 30.sp, fontWeight: FontWeight.bold),
                    ),
                    SizedBox(height: 20.h),
                    Text(
                      "Silahkan Login Terlebih Dahulu",
                      style: StyleTheme().styleBlack.copyWith(
                          fontSize: 16.sp, fontWeight: FontWeight.w500),
                    ),
                    Form(
                        key: formKey,
                        child: Padding(
                          padding: EdgeInsets.all(20.w),
                          child: Column(
                            children: [
                              SizedBox(
                                height: 60.h,
                                child: TextFormField(
                                  style: TextStyle(fontSize: 14.sp),
                                  focusNode: _fnUsername,
                                  controller: username,
                                  keyboardType: TextInputType.name,
                                  decoration: InputDecoration(
                                    contentPadding: EdgeInsets.symmetric(
                                        vertical: 15.h, horizontal: 10.w),
                                    isDense: false,
                                    border: OutlineInputBorder(
                                      borderRadius: BorderRadius.circular(10.r),
                                    ),
                                    prefixIcon: Icon(
                                      Icons.person,
                                      color: ColorTheme().primaryColor,
                                      size: 25.sp,
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
                              ),
                              SizedBox(
                                height: 10.h,
                              ),
                              SizedBox(
                                height: 60.h,
                                child: TextFormField(
                                  style: TextStyle(fontSize: 14.sp),
                                  focusNode: _fnPassword,
                                  controller: password,
                                  keyboardType: TextInputType.name,
                                  obscureText: true,
                                  decoration: InputDecoration(
                                    contentPadding: EdgeInsets.symmetric(
                                        vertical: 15.h, horizontal: 10.w),
                                    isDense: false,
                                    border: OutlineInputBorder(
                                      borderRadius: BorderRadius.circular(10.r),
                                    ),
                                    prefixIcon: Icon(
                                      Icons.lock,
                                      color: ColorTheme().primaryColor,
                                      size: 25.sp,
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
                              ),
                              SizedBox(
                                height: 10.h,
                              ),
                              Container(
                                width: MediaQuery.of(context).size.width,
                                height: 60.h,
                                decoration: BoxDecoration(
                                  borderRadius: BorderRadius.circular(10.r),
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
                                      color: ColorTheme().whiteColor,
                                      size: 14.sp),
                                  label: Text(
                                    'LOGIN',
                                    style: StyleTheme().styleWhite.copyWith(
                                          fontSize: 14.sp,
                                          fontWeight: FontWeight.bold,
                                        ),
                                  ),
                                  style: ElevatedButton.styleFrom(
                                    backgroundColor: Colors.transparent,
                                    side: BorderSide(
                                        color: ColorTheme().primaryColor,
                                        width: 2.w),
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(10.r),
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
            ),
            SizedBox(height: 10.sp),
          ],
        ),
      ),
    );
  }
}
