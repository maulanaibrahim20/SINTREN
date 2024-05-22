import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/penyuluh/palawija_controller.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/components/dropdown_button_component.dart';
import 'package:sintren_mobile/ui/penyuluh/components/textformfield_component.dart';
import 'package:sintren_mobile/ui/penyuluh/histori_penyuluhan_view.dart';

class FormPalawijaView extends StatefulWidget {
  const FormPalawijaView({super.key, this.detail, required this.onCreate});

  final DetailPalawijaModel? detail;
  final bool onCreate;

  @override
  State<FormPalawijaView> createState() => _FormPalawijaViewState();
}

class _FormPalawijaViewState extends State<FormPalawijaView> {
  final formKey = GlobalKey<FormState>();
  final palawijaC = PalawijaController();
  late List<DesaModel> _desaList;
  bool _isLoading = true;

  @override
  void initState() {
    _initializeData();
    super.initState();
  }

  Future<void> _initializeData() async {
    _desaList = await palawijaC.getAssignment();
    setState(() {
      if (widget.detail != null) {
        palawijaC.value =
            TextEditingController(text: widget.detail!.nilai.toString());
        palawijaC.selectedDesaValue =
            DesaModel(id: widget.detail!.desaId, name: widget.detail!.desaName);
        palawijaC.selectedBantuanValue = widget.detail!.jenisBantuan;
        palawijaC.selectedJenisLahanValue = widget.detail!.jenisLahan;
        palawijaC.selectedJenisPalawijaValue = widget.detail!.jenisPalawija;
        palawijaC.selectedTipeDataValue = widget.detail!.tipeData;
      }
      _isLoading = false;
    });
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
              if (widget.onCreate) {
                palawijaC.store().then((value) => Navigator.pushAndRemoveUntil(
                      context,
                      MaterialPageRoute(
                          builder: (context) => HistoriPenyuluhanView()),
                      (Route<dynamic> route) => route.isFirst,
                    ));
              } else {
                palawijaC
                    .update(widget.detail!.id.toString())
                    .then((value) => Navigator.pop(context));
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
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.stretch,
                    children: [
                      DropdownButtonComponent(
                        icon: Icons.villa,
                        label: 'Desa',
                        selectedItem: palawijaC.selectedDesaValue,
                        items: _desaList.map((desa) {
                          return DropdownMenuItem<DesaModel>(
                            value: desa,
                            child: Text(palawijaC.toCamelCase(desa.name)),
                          );
                        }).toList(),
                        hint: 'Pilih Desa',
                        validator: (value) =>
                            value == null ? 'Pilih desa terlebih dahulu' : null,
                        onChanged: (newValue) {
                          log(newValue!.id.toString());
                          setState(() {
                            palawijaC.selectedDesaValue = newValue;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            palawijaC.selectedDesaValue = newValue!;
                          });
                        },
                      ),
                      const SizedBox(height: 10),
                      DropdownButtonComponent(
                        icon: Icons.date_range,
                        label: "Jenis Lahan",
                        selectedItem: palawijaC.selectedJenisLahanValue.isEmpty
                            ? null
                            : palawijaC.selectedJenisLahanValue,
                        items: palawijaC.jenisLahan.map(
                          (value) {
                            return DropdownMenuItem<String>(
                              value: value,
                              child: Text(value),
                            );
                          },
                        ).toList(),
                        hint: "Pilih Jenis Lahan",
                        validator: (value) => value == null
                            ? "Pilih jenis lahan terlebih dahulu"
                            : null,
                        onChanged: (newValue) {
                          setState(() {
                            palawijaC.selectedJenisLahanValue = newValue!;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            palawijaC.selectedJenisLahanValue = newValue!;
                          });
                        },
                      ),
                      const SizedBox(height: 10),
                      DropdownButtonComponent(
                        icon: Icons.date_range,
                        label: "Jenis Padi",
                        selectedItem: palawijaC.selectedJenisPalawijaValue.isEmpty
                            ? null
                            : palawijaC.selectedJenisPalawijaValue,
                        items: palawijaC.jenisPalawija.map(
                          (value) {
                            return DropdownMenuItem<String>(
                              value: value,
                              child: Text(value),
                            );
                          },
                        ).toList(),
                        hint: "Pilih Jenis Palawija",
                        validator: (value) => value == null
                            ? "Pilih jenis padi terlebih dahulu"
                            : null,
                        onChanged: (newValue) {
                          setState(() {
                            palawijaC.selectedJenisPalawijaValue = newValue!;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            palawijaC.selectedJenisPalawijaValue = newValue!;
                          });
                        },
                      ),
                      const SizedBox(height: 10),
                      DropdownButtonComponent(
                        icon: Icons.date_range,
                        label: "Jenis Bantuan",
                        selectedItem: palawijaC.selectedBantuanValue.isEmpty
                            ? null
                            : palawijaC.selectedBantuanValue,
                        items: palawijaC.bantuan.map(
                          (value) {
                            return DropdownMenuItem<String>(
                              value: value,
                              child: Text(value),
                            );
                          },
                        ).toList(),
                        hint: "Pilih Jenis Bantuan",
                        validator: (value) => value == null
                            ? "Pilih jenis bantuan terlebih dahulu"
                            : null,
                        onChanged: (newValue) {
                          setState(() {
                            palawijaC.selectedBantuanValue = newValue!;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            palawijaC.selectedBantuanValue = newValue!;
                          });
                        },
                      ),
                      const SizedBox(height: 10),
                      DropdownButtonComponent(
                        icon: Icons.type_specimen,
                        label: "Jenis Data",
                        selectedItem: palawijaC.selectedTipeDataValue.isEmpty
                            ? null
                            : palawijaC.selectedTipeDataValue,
                        items: palawijaC.tipeData.map(
                          (value) {
                            return DropdownMenuItem<String>(
                              value: value,
                              child: Text(palawijaC.toCamelCase(value)),
                            );
                          },
                        ).toList(),
                        hint: "Pilih Jenis Data",
                        validator: (value) => value == null
                            ? "Pilih jenis data terlebih dahulu"
                            : null,
                        onChanged: (newValue) {
                          setState(() {
                            palawijaC.selectedTipeDataValue = newValue!;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            palawijaC.selectedTipeDataValue = newValue!;
                          });
                        },
                      ),
                      const SizedBox(height: 10),
                      TextFormFieldComponent(
                        controller: palawijaC.value = TextEditingController(
                            text: widget.detail == null
                                ? ""
                                : widget.detail!.nilai.toString()),
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
                            palawijaC.value.text = value!;
                          });
                        },
                      ),
                    ],
                  ),
                ),
              ),
            ),
    );
  }
}
