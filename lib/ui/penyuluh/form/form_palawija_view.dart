import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/components/dropdown_button_component.dart';

class FormPalawijaView extends StatefulWidget {
  const FormPalawijaView({super.key});

  @override
  State<FormPalawijaView> createState() => _FormPalawijaViewState();
}

class _FormPalawijaViewState extends State<FormPalawijaView> {
  List<String> jenisLahan = ["Lahan Sawah", "Lahan Non-Sawah"];
  List<String> bantuan = [
    "Tidak Ada",
    "Bantuan Pemerintah",
    "Bantuan Non-Pemerintah"
  ];
  List<String> jenisPengairan = [
    "Sawah Irigasi",
    "Sawah Tadah Hujan",
    "Sawah Rawa Pasang Surut",
    "Sawah Rawa Lebak"
  ];
  List<String> jenisPadi = [
    "Hibrida",
    "Inhibrida",
  ];

  final formKeyCP = GlobalKey<FormState>();

  @override
  Widget build(BuildContext context) {
    String? selectedJenisLahanValue =
        jenisLahan.isNotEmpty ? jenisLahan[0] : null;
    String? selectedBantuanValue = bantuan.isNotEmpty ? bantuan[0] : null;
    String? selectedJenisPengairanValue =
        jenisPengairan.isNotEmpty ? jenisPengairan[0] : null;
    String? selectedJenisPadiValue = jenisPadi.isNotEmpty ? jenisPadi[0] : null;

    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      appBar: AppBar(
        flexibleSpace: Container(
          decoration: BoxDecoration(gradient: ColorTheme().linearColor),
        ),
        foregroundColor: ColorTheme().whiteColor,
        title: Text(
          "Tambah Padi",
          style: StyleTheme()
              .styleWhite
              .copyWith(fontSize: 20, fontWeight: FontWeight.bold),
        ),
      ),
      bottomNavigationBar: Container(
        margin: const EdgeInsets.all(10),
        height: 50,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(10),
          gradient: ColorTheme().linearColor,
        ),
        child: ElevatedButton(
          onPressed: () {
            // Aksi yang akan dilakukan ketika tombol ditekan
          },
          style: ElevatedButton.styleFrom(
            backgroundColor: Colors.transparent,
            side: BorderSide(color: ColorTheme().primaryColor, width: 2),
            shape: RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(10),
            ),
          ),
          child: Row(
            mainAxisSize: MainAxisSize
                .min, // Menentukan agar Row menyesuaikan ukuran minimum yang diperlukan
            children: [
              Text(
                'SIMPAN',
                style: TextStyle(
                  fontWeight: FontWeight.bold,
                  fontSize: 14,
                  color: ColorTheme().whiteColor,
                ),
              ),
              const SizedBox(width: 8), // Jarak antara label dan ikon
              Icon(
                Icons.send,
                color: ColorTheme().whiteColor,
              ),
            ],
          ),
        ),
      ),
      body: Container(
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 20),
        child: Column(
          children: [
            // DropdownButtonComponent(
            //   icon: Icons.date_range,
            //   label: "Jenis Lahan",
            //   selectedItem: selectedJenisLahanValue!,
            //   items: jenisLahan.map(
            //     (value) {
            //       return DropdownMenuItem<String>(
            //         value: value,
            //         child: Text(value),
            //       );
            //     },
            //   ).toList(),
            //   hint: "Jenis Lahan",
            //   validator: (value) =>
            //       value == null ? "Pilih jenis lahan terlebih dahulu" : null,
            //   onChanged: (newValue) {
            //     setState(() {
            //       selectedJenisLahanValue = newValue!;
            //     });
            //   },
            // ),
            // const SizedBox(height: 10),
            // DropdownButtonComponent(
            //   icon: Icons.date_range,
            //   label: "Jenis Pengairan",
            //   selectedItem: selectedJenisPengairanValue!,
            //   items: jenisPengairan.map(
            //     (value) {
            //       return DropdownMenuItem<String>(
            //         value: value,
            //         child: Text(value),
            //       );
            //     },
            //   ).toList(),
            //   hint: "Jenis Pengairan",
            //   validator: (value) => value == null
            //       ? "Pilih jenis pengairan terlebih dahulu"
            //       : null,
            //   onChanged: (newValue) {
            //     setState(() {
            //       selectedJenisPengairanValue = newValue!;
            //     });
            //   },
            // ),
            // const SizedBox(height: 10),
            // DropdownButtonComponent(
            //   icon: Icons.date_range,
            //   label: "Jenis Padi",
            //   selectedItem: selectedJenisPadiValue!,
            //   items: jenisPadi.map(
            //     (value) {
            //       return DropdownMenuItem<String>(
            //         value: value,
            //         child: Text(value),
            //       );
            //     },
            //   ).toList(),
            //   hint: "Jenis Padi",
            //   validator: (value) =>
            //       value == null ? "Pilih jenis padi terlebih dahulu" : null,
            //   onChanged: (newValue) {
            //     setState(() {
            //       selectedJenisPadiValue = newValue!;
            //     });
            //   },
            // ),
            // const SizedBox(height: 10),
            // DropdownButtonComponent(
            //   icon: Icons.date_range,
            //   label: "Bantuan",
            //   selectedItem: selectedBantuanValue!,
            //   items: bantuan.map(
            //     (value) {
            //       return DropdownMenuItem<String>(
            //         value: value,
            //         child: Text(value),
            //       );
            //     },
            //   ).toList(),
            //   hint: "Bantuan",
            //   validator: (value) =>
            //       value == null ? "Pilih bantuan terlebih dahulu" : null,
            //   onChanged: (newValue) {
            //     setState(() {
            //       selectedBantuanValue = newValue!;
            //     });
            //   },
            // ),
          ],
        ),
      ),
    );
  }
}
