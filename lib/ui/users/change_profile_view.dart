import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/components/textformfield_component.dart';

class ChangeProfileView extends StatefulWidget {
  const ChangeProfileView({super.key});

  @override
  State<ChangeProfileView> createState() => _ChangeProfileViewState();
}

class _ChangeProfileViewState extends State<ChangeProfileView> {
  final userC = UserController();
  final formKey = GlobalKey<FormState>();
  TextEditingController name = TextEditingController();
  TextEditingController email = TextEditingController();
  TextEditingController username = TextEditingController();
  TextEditingController phone = TextEditingController();
  TextEditingController address = TextEditingController();

  Future<void> _initializeData() async {
    Map<String, dynamic> data = await userC.getUser();
    setState(() {
      name.text = data['name'];
      email.text = data['email'];
      username.text = data['username'];
      phone.text = data['phone'];
      address.text = data['address'];
    });
  }

  @override
  void initState() {
    _initializeData();
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
          "Ubah Profile",
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
              final data = {
                'name': name.text,
                'username': username.text,
                'email': email.text,
                'phone': phone.text,
                'address': address.text,
              };
              userC.updateProfil(data).then((value) => Navigator.pop(context));
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
                  controller: name,
                  icon: Icons.person,
                  hint: 'Masukkan Nama Lengkap',
                  label: 'Nama Lengkap',
                  validator: (value) {
                    return value == null || value.isEmpty
                        ? "Nama tidak boleh kosong"
                        : null;
                  },
                  inputType: TextInputType.name,
                  obsecure: false,
                ),
                SizedBox(
                  height: 10.h,
                ),
                TextFormFieldComponent(
                  controller: username,
                  icon: Icons.account_circle,
                  hint: 'Masukkan Username',
                  label: 'Username',
                  validator: (value) {
                    return value == null || value.isEmpty
                        ? "Username tidak boleh kosong"
                        : null;
                  },
                  inputType: TextInputType.name,
                  obsecure: false,
                ),
                SizedBox(
                  height: 10.h,
                ),
                TextFormFieldComponent(
                  controller: email,
                  icon: Icons.email,
                  hint: 'Masukkan Email',
                  label: 'Email',
                  validator: (value) {
                    return value == null || value.isEmpty
                        ? "Email tidak boleh kosong"
                        : null;
                  },
                  inputType: TextInputType.emailAddress,
                  obsecure: false,
                ),
                SizedBox(
                  height: 10.h,
                ),
                TextFormFieldComponent(
                  controller: phone,
                  icon: Icons.phone_android,
                  hint: 'Masukkan Nomor HP',
                  label: 'Nomor HP',
                  validator: (value) {
                    return value == null || value.isEmpty
                        ? "Nomor HP tidak boleh kosong"
                        : null;
                  },
                  inputType: TextInputType.number,
                  obsecure: false,
                ),
                SizedBox(
                  height: 10.h,
                ),
                TextFormFieldComponent(
                  controller: address,
                  icon: Icons.location_on,
                  maxLine: 5,
                  hint: 'Masukkan Alamat',
                  label: 'Alamat',
                  validator: (value) {
                    return value == null || value.isEmpty
                        ? "Alamat tidak boleh kosong"
                        : null;
                  },
                  inputType: TextInputType.streetAddress,
                  obsecure: false,
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
