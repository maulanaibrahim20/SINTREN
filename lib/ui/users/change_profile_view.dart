import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/components/textformfield_component.dart';

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
                const SizedBox(
                  height: 10,
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
                const SizedBox(
                  height: 10,
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
                const SizedBox(
                  height: 10,
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
                const SizedBox(
                  height: 10,
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
