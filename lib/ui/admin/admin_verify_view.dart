import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/detail_combined_model.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class AdminVerifyView extends StatefulWidget {
  const AdminVerifyView({super.key});

  @override
  State<AdminVerifyView> createState() => _AdminVerifyViewState();
}

class _AdminVerifyViewState extends State<AdminVerifyView> {
  TextEditingController ulasan = TextEditingController();
  final formKey = GlobalKey<FormState>();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      appBar: AppBar(
        foregroundColor: ColorTheme().whiteColor,
        title: Text(
          'List Verifikasi',
          style: StyleTheme().styleWhite.copyWith(
                fontSize: 20,
                fontWeight: FontWeight.w500,
              ),
        ),
        backgroundColor: ColorTheme().primaryColor,
      ),
      floatingActionButton: FloatingActionButton(
        shape: const CircleBorder(),
        heroTag: 'sinkron_detail',
        onPressed: () {
          setState(() {});
        },
        backgroundColor: ColorTheme().primaryColor,
        foregroundColor: ColorTheme().whiteColor,
        child: const Icon(
          Icons.refresh_rounded,
        ),
      ),
      body: Padding(
        padding: const EdgeInsets.symmetric(vertical: 10),
        child: _listVerify(),
      ),
    );
  }

  FutureBuilder<List<DetailCombinedModel>> _listVerify() {
    return FutureBuilder<List<DetailCombinedModel>>(
      future: AdminController().getDetailCombinedByStatus(),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        } else if (snapshot.hasError) {
          return const Center(
            child: Text('Error Data Tidak Ditemukan'),
          );
        } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
          return Align(
            alignment: Alignment.topCenter,
            child: Container(
              width: MediaQuery.of(context).size.width,
              height: 50,
              margin: const EdgeInsets.symmetric(horizontal: 15, vertical: 5),
              padding: const EdgeInsets.symmetric(horizontal: 15),
              decoration: BoxDecoration(
                color: Colors.green,
                borderRadius: BorderRadius.circular(10),
              ),
              child: Center(
                child: Row(
                  children: [
                    Icon(
                      Icons.verified_outlined,
                      color: ColorTheme().whiteColor,
                    ),
                    const SizedBox(width: 10),
                    Text(
                      'Semua Data Sudah Diverifikasi',
                      style: StyleTheme()
                          .styleWhite
                          .copyWith(fontSize: 14, fontWeight: FontWeight.bold),
                    ),
                  ],
                ),
              ),
            ),
          );
        } else {
          return Column(
            children: [
              Container(
                width: MediaQuery.of(context).size.width,
                height: 50,
                margin: const EdgeInsets.symmetric(horizontal: 15, vertical: 5),
                padding: const EdgeInsets.symmetric(horizontal: 15),
                decoration: BoxDecoration(
                  color: ColorTheme().primaryColor,
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Center(
                    child: Row(
                  children: [
                    Icon(
                      Icons.error_outline,
                      color: ColorTheme().whiteColor,
                    ),
                    const SizedBox(width: 10),
                    Text(
                      '${snapshot.data!.length} Data Menunggu Diverifikasi',
                      style: StyleTheme()
                          .styleWhite
                          .copyWith(fontSize: 14, fontWeight: FontWeight.bold),
                    ),
                  ],
                )),
              ),
              Expanded(
                child: ListView.builder(
                  padding: EdgeInsets.zero,
                  shrinkWrap: true,
                  itemCount: snapshot.data!.length,
                  itemBuilder: (context, index) {
                    var item = snapshot.data![index];
                    DetailPadiModel? dataPadi;
                    DetailPalawijaModel? dataPalawija;
                    if (item.type == "padi") {
                      dataPadi = item.data;
                    }
                    if (item.type == "palawija") {
                      dataPalawija = item.data;
                    }
                    return Card(
                      surfaceTintColor: ColorTheme().whiteColor,
                      margin: const EdgeInsets.symmetric(
                          vertical: 5, horizontal: 15),
                      elevation: 3,
                      child: ConstrainedBox(
                        constraints: const BoxConstraints(),
                        child: Padding(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 20, vertical: 10),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    item.type == "padi"
                                        ? dataPadi?.padiName ?? ""
                                        : dataPalawija?.palawijaName ?? "",
                                    style: StyleTheme().styleBlack.copyWith(
                                        fontWeight: FontWeight.w500,
                                        fontSize: 16),
                                  ),
                                  Container(
                                    margin:
                                        const EdgeInsets.symmetric(vertical: 3),
                                    padding: const EdgeInsets.symmetric(
                                        horizontal: 8, vertical: 3),
                                    decoration: BoxDecoration(
                                      color: Colors.amber,
                                      borderRadius: BorderRadius.circular(5),
                                    ),
                                    child: Text(
                                      "Membutuhkan Verifikasi",
                                      style: StyleTheme().styleWhite.copyWith(
                                            fontWeight: FontWeight.w500,
                                          ),
                                    ),
                                  )
                                ],
                              ),
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    UserController().toCamelCase(
                                        item.type == "padi"
                                            ? dataPadi?.jenisBantuan ?? ""
                                            : dataPalawija?.jenisBantuan ?? ""),
                                    style: StyleTheme().styleBlack,
                                  ),
                                  Text(
                                    item.type == "padi"
                                        ? dataPadi?.date ?? ""
                                        : dataPalawija?.date ?? "",
                                    style: StyleTheme().styleBlack,
                                  ),
                                ],
                              ),
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    'Lahan ${UserController().toCamelCase(item.type == "padi" ? dataPadi?.jenisLahan ?? "" : dataPalawija?.jenisLahan ?? "")}',
                                    style: StyleTheme().styleBlack,
                                  ),
                                  Text(
                                    item.type == "padi"
                                        ? UserController().toCamelCase(
                                            dataPadi?.pengairanName ?? "")
                                        : "",
                                    style: StyleTheme().styleBlack,
                                  ),
                                ],
                              ),
                              const Divider(),
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    UserController().toCamelCase(
                                        item.type == "padi"
                                            ? dataPadi?.tipeData ?? ""
                                            : dataPalawija?.tipeData ?? ""),
                                    style: StyleTheme().styleBlack.copyWith(
                                        fontSize: 14,
                                        fontWeight: FontWeight.w500),
                                  ),
                                  Text(
                                    "${item.type == "padi" ? dataPadi?.nilai : dataPalawija?.nilai} hektar",
                                    style: StyleTheme().styleBlack.copyWith(
                                          fontSize: 14,
                                          fontWeight: FontWeight.bold,
                                        ),
                                  ),
                                ],
                              ),
                              const Divider(),
                              const SizedBox(height: 5),
                              Row(
                                children: [
                                  Expanded(
                                    flex: 1,
                                    child: ElevatedButton.icon(
                                      onPressed: () async {
                                        bool? shouldVerify =
                                            await _showVerifyDialog(context);
                                        if (shouldVerify == true) {
                                          await AdminController().verify(
                                            dataId: item.type == "padi"
                                                ? dataPadi!.id.toString()
                                                : dataPalawija!.id.toString(),
                                            map: {
                                              "status": "terima",
                                              "catatan": "oke"
                                            },
                                            isPalawija: item.type == "padi"
                                                ? false
                                                : true,
                                          );
                                          setState(() {});
                                        }
                                      },
                                      icon: const Icon(Icons.verified_outlined,
                                          color: Colors.green),
                                      label: Text('Verifikasi',
                                          style: StyleTheme()
                                              .stylePrimary
                                              .copyWith(
                                                  fontSize: 14,
                                                  color: Colors.green)),
                                      style: ElevatedButton.styleFrom(
                                        surfaceTintColor:
                                            ColorTheme().whiteColor,
                                        side: const BorderSide(
                                            color: Colors.green, width: 2),
                                        shape: RoundedRectangleBorder(
                                          borderRadius:
                                              BorderRadius.circular(30),
                                        ),
                                      ),
                                    ),
                                  ),
                                  const SizedBox(width: 10),
                                  Expanded(
                                    flex: 1,
                                    child: ElevatedButton.icon(
                                      onPressed: () async {
                                        bool? shouldReject =
                                            await _showRejectedDialog(context);
                                        if (shouldReject == true) {
                                          await AdminController().verify(
                                            dataId: item.type == "padi"
                                                ? dataPadi!.id.toString()
                                                : dataPalawija!.id.toString(),
                                            map: {
                                              "status": "tolak",
                                              "catatan": ulasan.text
                                            },
                                            isPalawija: item.type == "padi"
                                                ? false
                                                : true,
                                          );
                                          setState(() {});
                                        }
                                      },
                                      icon: const Icon(Icons.dangerous_outlined,
                                          color: Colors.red),
                                      label: Text('Tolak',
                                          style: StyleTheme()
                                              .stylePrimary
                                              .copyWith(
                                                  fontSize: 14,
                                                  color: Colors.red)),
                                      style: ElevatedButton.styleFrom(
                                        surfaceTintColor:
                                            ColorTheme().whiteColor,
                                        side: const BorderSide(
                                            color: Colors.red, width: 2),
                                        shape: RoundedRectangleBorder(
                                          borderRadius:
                                              BorderRadius.circular(30),
                                        ),
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 5),
                            ],
                          ),
                        ),
                      ),
                    );
                  },
                ),
              ),
            ],
          );
        }
      },
    );
  }

  Future<bool> _showVerifyDialog(BuildContext context) async {
    return await showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Column(
            children: [
              Text('Konfirmasi Aksi'),
              Divider(),
            ],
          ),
          content: Text(
            "Apakah anda yakin ingin memverifikasi data ini?",
            style: StyleTheme().styleBlack.copyWith(fontSize: 14),
          ),
          actions: <Widget>[
            TextButton(
              onPressed: () async {
                Navigator.of(context).pop(false);
              },
              child: Text(
                "Tidak",
                style: StyleTheme()
                    .stylePrimary
                    .copyWith(fontSize: 14, color: Colors.red),
              ),
            ),
            TextButton(
              onPressed: () async {
                Navigator.of(context).pop(true);
              },
              child: Text(
                "Ya",
                style: StyleTheme().stylePrimary.copyWith(fontSize: 14),
              ),
            ),
          ],
        );
      },
    );
  }

  Future<bool> _showRejectedDialog(BuildContext context) async {
    return await showDialog(
      context: context,
      builder: (BuildContext context) {
        return Form(
          key: formKey,
          child: AlertDialog(
            title: const Column(
              children: [
                Text('Konfirmasi Aksi'),
                Divider(),
              ],
            ),
            content: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  "Apakah anda yakin ingin menolak data ini?",
                  style: StyleTheme().styleBlack.copyWith(fontSize: 14),
                ),
                const SizedBox(height: 10),
                const Text("Berikan Ulasan:"),
                TextFormField(
                  controller: ulasan,
                  decoration: InputDecoration(
                    isDense: true,
                    filled: true,
                    fillColor: ColorTheme().whiteColor,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(10),
                    ),
                  ),
                  maxLines: 5,
                  validator: (value) {
                    return value == null || value.isEmpty
                        ? "Ulasan tidak boleh kosong"
                        : null;
                  },
                )
              ],
            ),
            actions: <Widget>[
              TextButton(
                onPressed: () async {
                  setState(() {
                    ulasan.clear();
                  });

                  Navigator.of(context).pop(false);
                },
                child: Text(
                  "Tidak",
                  style: StyleTheme()
                      .stylePrimary
                      .copyWith(fontSize: 14, color: Colors.red),
                ),
              ),
              TextButton(
                onPressed: () async {
                  if (formKey.currentState!.validate()) {
                    Navigator.of(context).pop(true);
                  }
                },
                child: Text(
                  "Ya",
                  style: StyleTheme().stylePrimary.copyWith(fontSize: 14),
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}
