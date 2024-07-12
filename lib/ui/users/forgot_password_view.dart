import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/services/user_service.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class ForgotPassword extends StatefulWidget {
  const ForgotPassword({super.key});

  @override
  State<ForgotPassword> createState() => _ForgotPasswordState();
}

class _ForgotPasswordState extends State<ForgotPassword> {
  final userC = UserController();
  final userS = UserService();
  final formKey = GlobalKey<FormState>();
  TextEditingController number = TextEditingController();

  bool isKeyboardVisible = false;
  final FocusNode _fnnumber = FocusNode();

  @override
  void initState() {
    super.initState();
    _fnnumber.addListener(_onFocusChange);
  }

  @override
  void dispose() {
    _fnnumber.removeListener(_onFocusChange);
    super.dispose();
  }

  void _onFocusChange() {
    setState(() {
      isKeyboardVisible = _fnnumber.hasFocus;
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
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        ClipRect(
                          child: Align(
                            alignment: Alignment.center,
                            heightFactor: 1,
                            child: Image.asset(
                              'assets/images/pertanian.png',
                              width: 100.w,
                              height: 110.h,
                              fit: BoxFit.fill,
                            ),
                          ),
                        ),
                        SizedBox(width: 20.w),
                        ClipRect(
                          child: Align(
                            alignment: Alignment.center,
                            heightFactor: 1,
                            child: Image.asset(
                              'assets/images/logo-polindra.png',
                              width: 100.w,
                              height: 100.h,
                              fit: BoxFit.fill,
                            ),
                          ),
                        ),
                      ],
                    ),
                    SizedBox(height: 20.h),
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
                            crossAxisAlignment: CrossAxisAlignment.end,
                            children: [
                              TextFormField(
                                style: TextStyle(fontSize: 14.sp),
                                focusNode: _fnnumber,
                                controller: number,
                                keyboardType: TextInputType.phone,
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
                                  hintText: "Contoh: +6289321456780",
                                  labelText: "Masukkan No.Telepon/Whatsapp",
                                ),
                                validator: (value) {
                                  return value == null || value.isEmpty
                                      ? "Nomor tidak boleh kosong"
                                      : null;
                                },
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
                                      await userS
                                          .checkNotelp(number.text)
                                          .then((value) async {
                                        EasyLoading.showInfo("Nomor ditemukan");
                                        if (value) {
                                          await userC.sendCode(number.text);
                                          EasyLoading.showInfo(
                                              "Kode telah dikirim ke Whatsapp");
                                        }
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
