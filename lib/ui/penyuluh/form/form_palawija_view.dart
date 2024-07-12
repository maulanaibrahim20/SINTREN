import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/penyuluh/palawija_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/models/palawija_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/components/dropdown_button_component.dart';
import 'package:sintren_mobile/ui/components/textformfield_component.dart';
import 'package:sintren_mobile/ui/penyuluh/detail_penyuluhan_view.dart';

class FormPalawijaView extends StatefulWidget {
  const FormPalawijaView(
      {super.key, this.detail, required this.onCreate, this.desa, this.date});

  final DetailPalawijaModel? detail;
  final bool onCreate;
  final DesaModel? desa;
  final String? date;

  @override
  State<FormPalawijaView> createState() => _FormPalawijaViewState();
}

class _FormPalawijaViewState extends State<FormPalawijaView> {
  final formKey = GlobalKey<FormState>();
  final palawijaC = PalawijaController();
  late List<DesaModel> desaList;
  late List<PalawijaModel> palawijaList;
  late String selectedJenisLahanValue;
  late String selectedBantuanValue;
  late DesaModel? selectedDesaValue;
  late PalawijaModel? selectedJenisPalawijaValue;
  late String selectedTipeDataValue;
  TextEditingController value = TextEditingController();
  TextEditingController date = TextEditingController();
  bool _isLoading = true;
  bool _isKomaFound = false;

  @override
  void initState() {
    _initializeData();
    super.initState();
  }

  Future<void> _initializeData() async {
    desaList = await palawijaC.getDesa();
    palawijaList = await palawijaC.getPalawija();
    setState(() {
      if (widget.detail != null) {
        value.text = widget.detail!.nilai.toString();
        date.text = widget.detail!.date;
        selectedDesaValue =
            DesaModel(id: widget.detail!.desaId, name: widget.detail!.desaName);
        selectedBantuanValue = widget.detail!.jenisBantuan;
        selectedJenisLahanValue = widget.detail!.jenisLahan;
        selectedJenisPalawijaValue = PalawijaModel(
            id: widget.detail!.idJenisPalawija,
            name: widget.detail!.palawijaName);
        selectedTipeDataValue = widget.detail!.tipeData;
      } else {
        selectedJenisLahanValue = '';
        selectedBantuanValue = '';
        selectedDesaValue = null;
        selectedJenisPalawijaValue = null;
        selectedTipeDataValue = '';
      }
      if (widget.desa != null) {
        selectedDesaValue = widget.desa;
      }
      _isLoading = false;
    });
  }

  Future<void> _selectDate(BuildContext context) async {
    final DateTime now = DateTime.now();
    DateTime firstDate = DateTime(now.year, now.month - 1, 1);
    DateTime lastDate = DateTime(now.year, now.month + 1, 0);

    if (widget.date != null || widget.detail != null) {
      late DateTime parsedDate;
      if (widget.date != null) {
        parsedDate = DateTime.parse(widget.date!);
      }

      if (widget.detail != null) {
        parsedDate = DateTime.parse(widget.detail!.date);
      }

      firstDate = DateTime(parsedDate.year, parsedDate.month, 1);
      lastDate = DateTime(parsedDate.year, parsedDate.month + 1, 0);
    }

    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: firstDate,
      lastDate: lastDate,
    );
    if (picked != null) {
      setState(() {
        date.text = "${picked.toLocal()}".split(' ')[0];
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      appBar: AppBar(
        flexibleSpace: Container(
          decoration: BoxDecoration(gradient: ColorTheme().linearColor),
        ),
        foregroundColor: ColorTheme().whiteColor,
        title: Text(
          widget.onCreate ? "Tambah Palawija" : "Edit Palawija",
          style: StyleTheme()
              .styleWhite
              .copyWith(fontSize: 20.sp, fontWeight: FontWeight.bold),
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
                "desa_id": selectedDesaValue!.id,
                "desa_name": selectedDesaValue!.name,
                "jenis_lahan": selectedJenisLahanValue,
                "jenis_bantuan": selectedBantuanValue,
                "id_jenis_palawija": selectedJenisPalawijaValue!.id,
                "palawija_name": selectedJenisPalawijaValue!.name,
                "date": date.text,
                "tipe_data": selectedTipeDataValue,
                "nilai": value.text,
              };

              if (widget.onCreate) {
                palawijaC.store(data).then((value) {
                  if (value) {
                    Navigator.pushAndRemoveUntil(
                      context,
                      MaterialPageRoute(
                        builder: (context) => DetailPenyuluhanView(
                          index: 1,
                          date: date.text.substring(0, 7),
                          desaId: selectedDesaValue!.id,
                          desaName: selectedDesaValue!.name,
                        ),
                      ),
                      (route) => false,
                    );
                  }
                });
              } else {
                palawijaC
                    .update(widget.detail!.id.toString(), data)
                    .then((value) {
                  if (value) {
                    Navigator.pop(context);
                  }
                });
              }
            }
          },
          icon: Icon(Icons.save_rounded, color: ColorTheme().whiteColor),
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
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : Card(
              surfaceTintColor: ColorTheme().whiteColor,
              color: ColorTheme().whiteColor,
              margin: EdgeInsets.all(10.w),
              child: Padding(
                padding: EdgeInsets.all(10.w),
                child: Form(
                  key: formKey,
                  child: ListView(
                    shrinkWrap: true,
                    children: [
                      TextFormFieldComponent(
                        style: StyleTheme().styleBlack.copyWith(
                            fontWeight: FontWeight.w500, fontSize: 15.sp),
                        readOnly: true,
                        icon: Icons.date_range_rounded,
                        hint: "Pilih Tanggal",
                        label: "Tanggal Penyuluhan",
                        validator: (value) {
                          if (value == null || value.isEmpty) {
                            return 'Please select a date';
                          }
                          return null;
                        },
                        obsecure: false,
                        controller: date,
                        onTap: () {
                          _selectDate(context);
                        },
                      ),
                      SizedBox(height: 10.h),
                      DropdownButtonComponent(
                        label: 'Pilih Desa',
                        selectedItem: selectedDesaValue,
                        items: desaList.map((desa) {
                          return DropdownMenuItem<DesaModel>(
                            value: desa,
                            child:
                                Text(UserController().toCamelCase(desa.name)),
                          );
                        }).toList(),
                        hint: 'Pilih Desa',
                        validator: (value) =>
                            value == null ? 'Pilih desa terlebih dahulu' : null,
                        onChanged: (newValue) {
                          log(newValue!.id.toString());
                          setState(() {
                            selectedDesaValue = newValue;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            selectedDesaValue = newValue!;
                          });
                        },
                      ),
                      SizedBox(height: 10.h),
                      DropdownButtonComponent(
                        label: "Pilih Jenis Lahan",
                        selectedItem: selectedJenisLahanValue.isEmpty
                            ? null
                            : selectedJenisLahanValue,
                        items: palawijaC.jenisLahan.map(
                          (value) {
                            return DropdownMenuItem<String>(
                              value: value,
                              child: Text(UserController().toCamelCase(value)),
                            );
                          },
                        ).toList(),
                        hint: "Pilih Jenis Lahan",
                        validator: (value) => value == null
                            ? "Pilih jenis lahan terlebih dahulu"
                            : null,
                        onChanged: (newValue) {
                          setState(() {
                            selectedJenisLahanValue = newValue!;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            selectedJenisLahanValue = newValue!;
                          });
                        },
                      ),
                      SizedBox(height: 10.h),
                      DropdownButtonComponent(
                        label: 'Pilih Jenis Palawija',
                        selectedItem: selectedJenisPalawijaValue,
                        items: palawijaList.map((palawija) {
                          return DropdownMenuItem<PalawijaModel>(
                            value: palawija,
                            child: Text(
                                UserController().toCamelCase(palawija.name)),
                          );
                        }).toList(),
                        hint: 'Pilih Jenis Palawija',
                        validator: (value) => value == null
                            ? 'Pilih jenis palawija terlebih dahulu'
                            : null,
                        onChanged: (newValue) {
                          setState(() {
                            selectedJenisPalawijaValue = newValue;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            selectedJenisPalawijaValue = newValue!;
                          });
                        },
                      ),
                      SizedBox(height: 10.h),
                      DropdownButtonComponent(
                        label: "Pilih Jenis Bantuan",
                        selectedItem: selectedBantuanValue.isEmpty
                            ? null
                            : selectedBantuanValue,
                        items: palawijaC.bantuan.map(
                          (value) {
                            return DropdownMenuItem<String>(
                              value: value,
                              child: Text(UserController().toCamelCase(value)),
                            );
                          },
                        ).toList(),
                        hint: "Pilih Jenis Bantuan",
                        validator: (value) => value == null
                            ? "Pilih jenis bantuan terlebih dahulu"
                            : null,
                        onChanged: (newValue) {
                          setState(() {
                            selectedBantuanValue = newValue!;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            selectedBantuanValue = newValue!;
                          });
                        },
                      ),
                      SizedBox(height: 10.h),
                      DropdownButtonComponent(
                        label: "Pilih Jenis Data",
                        selectedItem: selectedTipeDataValue.isEmpty
                            ? null
                            : selectedTipeDataValue,
                        items: palawijaC.tipeData.map(
                          (value) {
                            return DropdownMenuItem<String>(
                              value: value,
                              child: Text(UserController().toCamelCase(value)),
                            );
                          },
                        ).toList(),
                        hint: "Pilih Jenis Data",
                        validator: (value) => value == null
                            ? "Pilih jenis data terlebih dahulu"
                            : null,
                        onChanged: (newValue) {
                          setState(() {
                            selectedTipeDataValue = newValue!;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            selectedTipeDataValue = newValue!;
                          });
                        },
                      ),
                      SizedBox(height: 10.h),
                      TextFormFieldComponent(
                        controller: value,
                        hint: "Masukkan Nilai (hektar), Contoh : 1000",
                        label: "Masukkan Nilai (hektar)",
                        validator: (value) => value == null || value.isEmpty
                            ? "Masukkan nilai terlebih dahulu"
                            : null,
                        inputType: TextInputType.number,
                        obsecure: false,
                        onSaved: (value) {
                          setState(() {
                            this.value.text = value!;
                          });
                        },
                        onChanged: (String value) {
                          setState(() {
                            if (value.contains(',')) {
                              _isKomaFound = true;
                            } else {
                              _isKomaFound = false;
                            }
                          });
                        },
                        style: StyleTheme().styleBlack.copyWith(
                              fontWeight: FontWeight.w500,
                              fontSize: 15.sp,
                            ),
                      ),
                      Visibility(
                        visible: _isKomaFound,
                        child: Padding(
                          padding: EdgeInsets.symmetric(horizontal: 10.w),
                          child: Text(
                            "Tidak boleh menggunakan tanda koma",
                            style: StyleTheme()
                                .styleBlack
                                .copyWith(color: Colors.red),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
            ),
    );
  }
}
