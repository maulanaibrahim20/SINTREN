import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/admin/admin_padi_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/ui/admin/detail_penyuluhan/components/ringkasan_padi_widget.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class DetailPadiView extends StatefulWidget {
  const DetailPadiView(
      {super.key,
      required this.date,
      required this.desaId,
      this.desaName = ""});
  final String date;
  final String desaId;
  final String desaName;

  @override
  State<DetailPadiView> createState() => DetailPadiViewState();
}

class DetailPadiViewState extends State<DetailPadiView> {
  final padiC = AdminPadiController();
  TextEditingController ulasan = TextEditingController();
  final formKey = GlobalKey<FormState>();
  bool isOpen = false;

  @override
  void initState() {
    setState(() {});
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      body: ListView(
        children: [
          RingkasanPadiWidget(
            date: widget.date,
            desaId: widget.desaId,
            desaName: widget.desaName,
            isRincian: false,
          ),
          FutureBuilder<List<DetailPadiModel>>(
            future: padiC.getDetailPadiByDesa(widget.date, widget.desaId),
            builder: (context, snapshot) {
              if (snapshot.connectionState == ConnectionState.waiting) {
                return const Center(child: CircularProgressIndicator());
              } else if (snapshot.hasError) {
                return Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      const Icon(
                        Icons.error,
                        color: Colors.grey,
                        size: 50,
                      ),
                      Text(
                        "Internal Server Error",
                        style: StyleTheme().styleBlack.copyWith(
                            fontSize: 18,
                            fontWeight: FontWeight.w500,
                            color: Colors.grey),
                      ),
                      Text(
                        snapshot.error.toString(),
                        style: const TextStyle(
                          fontSize: 14,
                          color: Colors.red,
                        ),
                        textAlign: TextAlign.center,
                      ),
                    ],
                  ),
                );
              } else {
                final itemList = snapshot.data ?? [];
                if (itemList.isEmpty) {
                  return Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        const Icon(
                          Icons.receipt_long,
                          size: 40,
                          color: Colors.grey,
                        ),
                        Text(
                          "Data Kosong",
                          style: StyleTheme().styleBlack.copyWith(
                              color: Colors.grey,
                              fontSize: 18,
                              fontWeight: FontWeight.w500),
                        )
                      ],
                    ),
                  );
                }
                return ListView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  padding: EdgeInsets.zero,
                  itemCount: itemList.length,
                  itemBuilder: (BuildContext context, int index) {
                    DetailPadiModel data = itemList[index];
                    return Card(
                      surfaceTintColor: ColorTheme().whiteColor,
                      margin: const EdgeInsets.symmetric(
                          vertical: 5, horizontal: 10),
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
                                    data.padiName,
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
                                      color: data.status == "terima"
                                          ? Colors.green
                                          : data.status == "tolak"
                                              ? Colors.red
                                              : Colors.amber,
                                      borderRadius: BorderRadius.circular(5),
                                    ),
                                    child: Text(
                                      data.status == "terima"
                                          ? "Terverifikasi"
                                          : data.status == "tolak"
                                              ? "Data Ditolak"
                                              : "Membutuhkan Verifikasi",
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
                                    UserController()
                                        .toCamelCase(data.jenisBantuan),
                                    style: StyleTheme().styleBlack,
                                  ),
                                  Text(
                                    data.date,
                                    style: StyleTheme().styleBlack,
                                  ),
                                ],
                              ),
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    'Lahan ${UserController().toCamelCase(data.jenisLahan)}',
                                    style: StyleTheme().styleBlack,
                                  ),
                                  Text(
                                    UserController()
                                        .toCamelCase(data.pengairanName),
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
                                    UserController().toCamelCase(data.tipeData),
                                    style: StyleTheme().styleBlack.copyWith(
                                        fontSize: 14,
                                        fontWeight: FontWeight.w500),
                                  ),
                                  Text(
                                    "${data.nilai} hektar",
                                    style: StyleTheme().styleBlack.copyWith(
                                          fontSize: 14,
                                          fontWeight: FontWeight.bold,
                                        ),
                                  ),
                                ],
                              ),
                              const Divider(),
                              if (data.status == "tunggu") ...[
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
                                              dataId: data.id.toString(),
                                              map: {
                                                "status": "terima",
                                                "catatan": "oke"
                                              },
                                              isPalawija: false,
                                            );
                                            setState(() {});
                                          }
                                        },
                                        icon: const Icon(
                                            Icons.verified_outlined,
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
                                              await _showRejectedDialog(
                                                  context);
                                          if (shouldReject == true) {
                                            await AdminController().verify(
                                              dataId: data.id.toString(),
                                              map: {
                                                "status": "tolak",
                                                "catatan": ulasan.text
                                              },
                                              isPalawija: false,
                                            );
                                            setState(() {
                                              ulasan.clear();
                                            });
                                          }
                                        },
                                        icon: const Icon(
                                            Icons.dangerous_outlined,
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
                                )
                              ],
                              const SizedBox(height: 5),
                            ],
                          ),
                        ),
                      ),
                    );
                  },
                );
              }
            },
          ),
          const SizedBox(height: 10),
        ],
      ),
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
