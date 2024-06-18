import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/components/textformfield_component.dart';

class ChangePasswordView extends StatefulWidget {
  const ChangePasswordView({super.key});

  @override
  State<ChangePasswordView> createState() => _ChangePasswordViewState();
}

class _ChangePasswordViewState extends State<ChangePasswordView> {
  final userC = UserController();
  final formKey = GlobalKey<FormState>();
  TextEditingController oldPass = TextEditingController();
  TextEditingController newPass = TextEditingController();
  TextEditingController confirmPass = TextEditingController();

  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      appBar: AppBar(
        elevation: 0,
        centerTitle: false,
        foregroundColor: ColorTheme().whiteColor,
        flexibleSpace: Container(
          decoration: BoxDecoration(gradient: ColorTheme().linearColor),
        ),
        title: Text(
          "Ubah Password",
          style: StyleTheme()
              .styleWhite
              .copyWith(fontSize: 20.sp, fontWeight: FontWeight.w500),
        ),
      ),
      bottomNavigationBar: Container(
        margin: EdgeInsets.all(10.w),
        height: 50.h,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(10.r),
          gradient: ColorTheme().linearColor,
        ),
        child: ElevatedButton.icon(
          onPressed: () {
            if (formKey.currentState!.validate()) {
              userC
                  .changePassword(
                      oldPass: oldPass.text,
                      newPass: newPass.text,
                      confirmPass: confirmPass.text)
                  .then((value) => Navigator.pop(context));
            }
          },
          icon: Icon(Icons.save_rounded,
              color: ColorTheme().whiteColor, size: 20.sp),
          label: Text(
            'SIMPAN',
            style: StyleTheme().styleWhite.copyWith(
                  fontSize: 14.sp,
                  fontWeight: FontWeight.bold,
                ),
          ),
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.transparent,
            side: BorderSide(color: ColorTheme().primaryColor, width: 2.w),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(10.r),
            ),
          ),
        ),
      ),
      body: Card(
        elevation: 3,
        margin: EdgeInsets.all(10.w),
        surfaceTintColor: ColorTheme().whiteColor,
        color: ColorTheme().whiteColor,
        child: Padding(
          padding: EdgeInsets.symmetric(vertical: 15.h, horizontal: 10.w),
          child: Form(
            key: formKey,
            child: ListView(
              shrinkWrap: true,
              children: [
                TextFormFieldComponent(
                  controller: oldPass,
                  icon: Icons.lock,
                  hint: 'Masukkan Password Lama',
                  label: 'Password Lama',
                  validator: (value) {
                    return value == null || value.isEmpty
                        ? "Password lama tidak boleh kosong"
                        : null;
                  },
                  inputType: TextInputType.name,
                  obsecure: true,
                  maxLine: 1,
                ),
                SizedBox(
                  height: 10.h,
                ),
                TextFormFieldComponent(
                  controller: newPass,
                  icon: Icons.lock,
                  hint: 'Masukkan Password Baru',
                  label: 'Password Baru',
                  validator: (value) {
                    return value == null || value.isEmpty
                        ? "Password baru tidak boleh kosong"
                        : null;
                  },
                  inputType: TextInputType.name,
                  obsecure: true,
                  maxLine: 1,
                ),
                SizedBox(
                  height: 10.h,
                ),
                TextFormFieldComponent(
                  controller: confirmPass,
                  icon: Icons.lock,
                  hint: 'Masukkan Konfirmasi Password',
                  label: 'Konfirmasi Password',
                  validator: (value) {
                    return value == null || value.isEmpty
                        ? "Konfirmasi password tidak boleh kosong"
                        : null;
                  },
                  inputType: TextInputType.name,
                  obsecure: true,
                  maxLine: 1,
                ),
                SizedBox(
                  height: 10.h,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
