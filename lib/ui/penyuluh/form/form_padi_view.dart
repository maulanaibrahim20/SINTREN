import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/penyuluh/padi_controller.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/pengairan_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/components/dropdown_button_component.dart';
import 'package:sintren_mobile/ui/penyuluh/components/textformfield_component.dart';
import 'package:sintren_mobile/ui/penyuluh/histori_penyuluhan_view.dart';

class FormPadiView extends StatefulWidget {
  const FormPadiView({super.key, this.detail, required this.onCreate});

  final DetailPadiModel? detail;
  final bool onCreate;

  @override
  State<FormPadiView> createState() => _FormPadiViewState();
}

class _FormPadiViewState extends State<FormPadiView> {
  final formKey = GlobalKey<FormState>();
  final padiC = PadiController();
  late List<DesaModel> _desaList;
  late List<PengairanModel> _pengiranList;
  bool _isLoading = true;

  @override
  void initState() {
    _initializeData();
    super.initState();
  }

  Future<void> _initializeData() async {
    _desaList = await padiC.getAssignment();
    _pengiranList = await padiC.getPengiran();
    setState(() {
      if (widget.detail != null) {
        padiC.value =
            TextEditingController(text: widget.detail!.nilai.toString());
        padiC.selectedDesaValue =
            DesaModel(id: widget.detail!.desaId, name: widget.detail!.desaName);
        padiC.selectedBantuanValue = widget.detail!.jenisBantuan;
        padiC.selectedJenisLahanValue = widget.detail!.jenisLahan;
        padiC.selectedJenisPadiValue = widget.detail!.jenisPadi;
        padiC.selectedTipeDataValue = widget.detail!.tipeData;
        padiC.selectedJenisPengairanValue = PengairanModel(
            id: widget.detail!.idJenisPengairan,
            name: widget.detail!.pengairanName);
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
        child: ElevatedButton.icon(
          onPressed: () {
            if (formKey.currentState!.validate()) {
              if (widget.onCreate) {
                padiC.store().then((value) => Navigator.pushAndRemoveUntil(
                      context,
                      MaterialPageRoute(
                          builder: (context) => HistoriPenyuluhanView()),
                      (Route<dynamic> route) => route.isFirst,
                    ));
              } else {
                padiC
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
                        selectedItem: padiC.selectedDesaValue,
                        items: _desaList.map((desa) {
                          return DropdownMenuItem<DesaModel>(
                            value: desa,
                            child: Text(padiC.toCamelCase(desa.name)),
                          );
                        }).toList(),
                        hint: 'Pilih Desa',
                        validator: (value) =>
                            value == null ? 'Pilih desa terlebih dahulu' : null,
                        onChanged: (newValue) {
                          log(newValue!.id.toString());
                          setState(() {
                            padiC.selectedDesaValue = newValue;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            padiC.selectedDesaValue = newValue!;
                          });
                        },
                      ),
                      const SizedBox(height: 10),
                      DropdownButtonComponent(
                        icon: Icons.date_range,
                        label: "Jenis Lahan",
                        selectedItem: padiC.selectedJenisLahanValue.isEmpty
                            ? null
                            : padiC.selectedJenisLahanValue,
                        items: padiC.jenisLahan.map(
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
                            padiC.selectedJenisLahanValue = newValue!;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            padiC.selectedJenisLahanValue = newValue!;
                          });
                        },
                      ),
                      const SizedBox(height: 10),
                      if (padiC.selectedJenisLahanValue ==
                          'Lahan Non-Sawah') ...[
                        const SizedBox.shrink()
                      ] else ...[
                        DropdownButtonComponent(
                          icon: Icons.water,
                          label: 'Pengairan',
                          selectedItem: padiC.selectedJenisPengairanValue,
                          items: _pengiranList.map((pengiran) {
                            return DropdownMenuItem<PengairanModel>(
                              value: pengiran,
                              child: Text(padiC.toCamelCase(pengiran.name)),
                            );
                          }).toList(),
                          hint: 'Pilih Pengiran',
                          validator: (value) => value == null
                              ? 'Pilih pengairan terlebih dahulu'
                              : null,
                          onChanged: (newValue) {
                            setState(() {
                              padiC.selectedJenisPengairanValue = newValue!;
                            });
                          },
                          onSaved: (newValue) {
                            setState(() {
                              padiC.selectedJenisPengairanValue = newValue!;
                            });
                          },
                        ),
                        const SizedBox(height: 10),
                      ],
                      DropdownButtonComponent(
                        icon: Icons.date_range,
                        label: "Jenis Padi",
                        selectedItem: padiC.selectedJenisPadiValue.isEmpty
                            ? null
                            : padiC.selectedJenisPadiValue,
                        items: padiC.jenisPadi.map(
                          (value) {
                            return DropdownMenuItem<String>(
                              value: value,
                              child: Text(value),
                            );
                          },
                        ).toList(),
                        hint: "Pilih Jenis Padi",
                        validator: (value) => value == null
                            ? "Pilih jenis padi terlebih dahulu"
                            : null,
                        onChanged: (newValue) {
                          setState(() {
                            padiC.selectedJenisPadiValue = newValue!;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            padiC.selectedJenisPadiValue = newValue!;
                          });
                        },
                      ),
                      const SizedBox(height: 10),
                      DropdownButtonComponent(
                        icon: Icons.date_range,
                        label: "Jenis Bantuan",
                        selectedItem: padiC.selectedBantuanValue.isEmpty
                            ? null
                            : padiC.selectedBantuanValue,
                        items: padiC.bantuan.map(
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
                            padiC.selectedBantuanValue = newValue!;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            padiC.selectedBantuanValue = newValue!;
                          });
                        },
                      ),
                      const SizedBox(height: 10),
                      DropdownButtonComponent(
                        icon: Icons.type_specimen,
                        label: "Jenis Data",
                        selectedItem: padiC.selectedTipeDataValue.isEmpty
                            ? null
                            : padiC.selectedTipeDataValue,
                        items: padiC.tipeData.map(
                          (value) {
                            return DropdownMenuItem<String>(
                              value: value,
                              child: Text(padiC.toCamelCase(value)),
                            );
                          },
                        ).toList(),
                        hint: "Pilih Jenis Data",
                        validator: (value) => value == null
                            ? "Pilih jenis data terlebih dahulu"
                            : null,
                        onChanged: (newValue) {
                          setState(() {
                            padiC.selectedTipeDataValue = newValue!;
                          });
                        },
                        onSaved: (newValue) {
                          setState(() {
                            padiC.selectedTipeDataValue = newValue!;
                          });
                        },
                      ),
                      const SizedBox(height: 10),
                      TextFormFieldComponent(
                        controller: padiC.value = TextEditingController(
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
                            padiC.value.text = value!;
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
