import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/components/textformfield_component.dart';

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
              .copyWith(fontSize: 20, fontWeight: FontWeight.w500),
        ),
      ),
      bottomNavigationBar: Container(
        margin: const EdgeInsets.all(10),
        height: 50,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(10),
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
          icon: Icon(Icons.save_rounded, color: ColorTheme().whiteColor),
          label: Text(
            'SIMPAN',
            style: StyleTheme().styleWhite.copyWith(
                  fontSize: 14,
                  fontWeight: FontWeight.bold,
                ),
          ),
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.transparent,
            side: BorderSide(color: ColorTheme().primaryColor, width: 2),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(10),
            ),
          ),
        ),
      ),
      body: Card(
        margin: const EdgeInsets.all(10),
        surfaceTintColor: ColorTheme().whiteColor,
        child: Padding(
          padding: const EdgeInsets.symmetric(vertical: 15, horizontal: 10),
          child: Form(
            key: formKey,
            child: ListView(
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
                const SizedBox(
                  height: 10,
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
                const SizedBox(
                  height: 10,
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
                const SizedBox(
                  height: 10,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
