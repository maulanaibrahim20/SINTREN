import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/penyuluh/padi_controller.dart';
import 'package:sintren_mobile/controllers/penyuluh/penyuluh_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/padi_model.dart';
import 'package:sintren_mobile/models/pengairan_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/components/dropdown_button_component.dart';
import 'package:sintren_mobile/ui/penyuluh/components/textformfield_component.dart';
import 'package:sintren_mobile/ui/penyuluh/detail_penyuluhan_view.dart';

class FormPadiView extends StatefulWidget {
  const FormPadiView(
      {super.key, this.detail, required this.onCreate, this.desa, this.date});

  final DetailPadiModel? detail;
  final bool onCreate;
  final DesaModel? desa;
  final String? date;

  @override
  State<FormPadiView> createState() => _FormPadiViewState();
}

class _FormPadiViewState extends State<FormPadiView> {
  final formKey = GlobalKey<FormState>();
  final padiC = PadiController();
  late List<DesaModel> desaList;
  late List<PengairanModel> pengiranList;
  late List<PadiModel> padiList;
  bool _isLoading = true;
  late String selectedJenisLahanValue;
  late String selectedBantuanValue;
  late PengairanModel? selectedJenisPengairanValue;
  late DesaModel? selectedDesaValue;
  late PadiModel? selectedJenisPadiValue;
  late String selectedTipeDataValue;
  TextEditingController value = TextEditingController();
  TextEditingController date = TextEditingController();

  @override
  void initState() {
    _initializeData();
    super.initState();
  }

  Future<void> _initializeData() async {
    desaList = await PenyuluhController().getDesa();
    pengiranList = await padiC.getPengairan();
    padiList = await padiC.getPadi();
    setState(() {
      if (widget.detail != null) {
        value.text = widget.detail!.nilai.toString();
        date.text = widget.detail!.date;
        selectedDesaValue =
            DesaModel(id: widget.detail!.desaId, name: widget.detail!.desaName);
        selectedBantuanValue = widget.detail!.jenisBantuan;
        selectedJenisLahanValue = widget.detail!.jenisLahan;
        selectedJenisPadiValue = PadiModel(
            id: widget.detail!.idJenisPadi, name: widget.detail!.padiName);
        selectedTipeDataValue = widget.detail!.tipeData;
        selectedJenisPengairanValue = PengairanModel(
            id: widget.detail!.idJenisPengairan,
            name: widget.detail!.pengairanName);
      } else {
        selectedJenisLahanValue = '';
        selectedBantuanValue = '';
        selectedJenisPengairanValue = null;
        selectedDesaValue = null;
        selectedJenisPadiValue = null;
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
    DateTime initialDate = DateTime.now();

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
      initialDate: initialDate,
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
          widget.onCreate ? "Tambah Padi" : "Edit Padi",
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
        child: ElevatedButton.icon(
          onPressed: () {
            if (formKey.currentState!.validate()) {
              final data = {
                "desa_id": selectedDesaValue!.id,
                "desa_name": selectedDesaValue!.name,
                "jenis_lahan": selectedJenisLahanValue,
                "jenis_bantuan": selectedBantuanValue,
                "id_jenis_padi": selectedJenisPadiValue!.id,
                "padi_name": selectedJenisPadiValue!.name,
                "date": date.text,
                "id_jenis_pengairan": selectedJenisPengairanValue?.id,
                "pengairan_name": selectedJenisPengairanValue?.name,
                "tipe_data": selectedTipeDataValue,
                "nilai": value.text,
              };

              if (widget.onCreate) {
                padiC.store(data).then((value) {
                  if (value) {
                    Navigator.pushAndRemoveUntil(
                      context,
                      MaterialPageRoute(
                        builder: (context) => DetailPenyuluhanView(
                          index: 0,
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
                padiC.update(widget.detail!.id.toString(), data).then((value) {
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
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : Card(
              surfaceTintColor: ColorTheme().whiteColor,
              margin: const EdgeInsets.all(10),
              child: Padding(
                padding: const EdgeInsets.all(10),
                child: Form(
                  key: formKey,
                  child: ListView(
                    children: [
                      TextFormFieldComponent(
                        style: StyleTheme().styleBlack.copyWith(
                            fontWeight: FontWeight.w500, fontSize: 15),
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
                      const SizedBox(height: 10),
                      DropdownButtonComponent(
                        icon: Icons.villa,
                        label: 'Desa',
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
                      const SizedBox(height: 10),
                      DropdownButtonComponent(
                        icon: Icons.date_range,
                        label: "Jenis Lahan",
                        selectedItem: selectedJenisLahanValue.isEmpty
                            ? null
                            : selectedJenisLahanValue,
                        items: padiC.jenisLahan.map(
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
                      const SizedBox(height: 10),
                      if (selectedJenisLahanValue == 'non sawah') ...[
                        const SizedBox.shrink()
                      ] else ...[
                        DropdownButtonComponent(
                          icon: Icons.water,
                          label: 'Pengairan',
                          selectedItem: selectedJenisPengairanValue,
                          items: pengiranList.map((pengiran) {
                            return DropdownMenuItem<PengairanModel>(
                              value: pengiran,
                              child: Text(
                                  UserController().toCamelCase(pengiran.name)),
                            );
                          }).toList(),
                          hint: 'Pilih Pengiran',
                          validator: (value) => value == null
                              ? 'Pilih pengairan terlebih dahulu'
                              : null,
                          onChanged: (newValue) {
                            setState(() {
                              selectedJenisPengairanValue = newValue!;
                            });
                          },
                          onSaved: (newValue) {
                            setState(() {
                              selectedJenisPengairanValue = newValue!;
                            });
                          },
                        ),
                        const SizedBox(height: 10),
                      ],
                      DropdownButtonComponent(
                        icon: Icons.date_range,
                        label: "Jenis Padi",
                        selectedItem: selectedJenisPadiValue,
                        items: padiList.map((padi) {
                          return DropdownMenuItem<PadiModel>(
                            value: padi,
                            child:
                                Text(UserController().toCamelCase(padi.name)),
                          );
                        }).toList(),
                        hint: "Pilih Jenis Padi",
                        validator: (value) => value == null
                            ? "Pilih jenis padi terlebih dahulu"
                            : null,
                        onChanged: (newValue) {
                          setState(() {
                            selectedJenisPadiValue = newValue!;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            selectedJenisPadiValue = newValue!;
                          });
                        },
                      ),
                      const SizedBox(height: 10),
                      DropdownButtonComponent(
                        icon: Icons.date_range,
                        label: "Jenis Bantuan",
                        selectedItem: selectedBantuanValue.isEmpty
                            ? null
                            : selectedBantuanValue,
                        items: padiC.bantuan.map(
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
                      const SizedBox(height: 10),
                      DropdownButtonComponent(
                        icon: Icons.type_specimen,
                        label: "Jenis Data",
                        selectedItem: selectedTipeDataValue.isEmpty
                            ? null
                            : selectedTipeDataValue,
                        items: padiC.tipeData.map(
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
                      const SizedBox(height: 10),
                      TextFormFieldComponent(
                        controller: value,
                        icon: Icons.numbers,
                        hint: "Masukkan Nilai",
                        label: "Nilai",
                        validator: (value) => value == null
                            ? "Masukkan nilai terlebih dahulu"
                            : null,
                        inputType: TextInputType.number,
                        obsecure: false,
                        onSaved: (value) {
                          setState(() {
                            value.text = value!;
                          });
                        },
                        style: StyleTheme().styleBlack.copyWith(
                              fontWeight: FontWeight.w500,
                              fontSize: 15,
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
