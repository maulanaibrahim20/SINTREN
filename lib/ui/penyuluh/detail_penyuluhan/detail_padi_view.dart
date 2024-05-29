import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/penyuluh/padi_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/kesimpulan_data_padi_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/form/form_padi_view.dart';

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
  final padiC = PadiController();
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
      body: Column(
        children: [
          Card(
            margin: const EdgeInsets.symmetric(horizontal: 10, vertical: 10),
            elevation: 3,
            surfaceTintColor: ColorTheme().whiteColor,
            color: ColorTheme().whiteColor,
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                const SizedBox(height: 20),
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 20),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Row(
                        children: [
                          Container(
                            height: 50,
                            width: 50,
                            decoration: BoxDecoration(
                              shape: BoxShape.circle,
                              gradient: ColorTheme().linearColor,
                            ),
                            child: Center(
                              child: Icon(
                                Icons.home_rounded,
                                color: ColorTheme().whiteColor,
                                size: 30,
                              ),
                            ),
                          ),
                          const SizedBox(width: 15),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text(
                                "Desa ${UserController().toCamelCase(widget.desaName)}",
                                style: StyleTheme().stylePrimary.copyWith(
                                    fontSize: 20, fontWeight: FontWeight.bold),
                              ),
                              Text(
                                UserController().convertDate(widget.date),
                                style: StyleTheme().styleBlack.copyWith(
                                    fontWeight: FontWeight.bold,
                                    color: Colors.grey[700],
                                    fontSize: 14),
                              ),
                            ],
                          ),
                        ],
                      ),
                      Container(
                        height: 40,
                        width: 40,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          gradient: ColorTheme().linearColor,
                        ),
                        child: Center(
                          child: Icon(
                            Icons.add,
                            color: ColorTheme().whiteColor,
                            size: 25,
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 10),
                const Divider(thickness: 2),
                Visibility(
                  visible: isOpen,
                  child: ConstrainedBox(
                    constraints: BoxConstraints(
                      maxHeight: MediaQuery.of(context).size.height -
                          400, // Sesuaikan batas tinggi sesuai kebutuhan Anda
                    ),
                    child: SingleChildScrollView(
                      child: FutureBuilder(
                        future: Future.wait([
                          padiC.getKesimpulanDataPengairan(
                              widget.date, widget.desaId),
                          padiC.getKesimpulanDataPadi(
                              widget.date, widget.desaId)
                        ]),
                        builder:
                            (context, AsyncSnapshot<List<dynamic>> snapshot) {
                          if (snapshot.connectionState ==
                              ConnectionState.waiting) {
                            return const Center(
                                child: CircularProgressIndicator());
                          } else if (snapshot.hasError) {
                            return Center(
                                child: Text('Error: ${snapshot.error}'));
                          } else {
                            final pengairanData = snapshot.data![0]
                                as Map<String, JenisPengairan>;
                            final padiData =
                                snapshot.data![1] as Map<String, JenisPadi>;

                            if (pengairanData.isEmpty && padiData.isEmpty) {
                              return const Center(
                                  child: Text('No data available'));
                            } else {
                              return Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Padding(
                                    padding: const EdgeInsets.only(
                                        left: 20, bottom: 5),
                                    child: Text("Data Padi",
                                        style: StyleTheme().styleBlack.copyWith(
                                            fontSize: 18,
                                            fontWeight: FontWeight.w500)),
                                  ),
                                  ConstrainedBox(
                                    constraints: BoxConstraints(
                                      maxHeight:
                                          MediaQuery.of(context).size.height -
                                              150,
                                    ),
                                    child: ListView.builder(
                                      shrinkWrap: true,
                                      physics:
                                          const NeverScrollableScrollPhysics(),
                                      itemCount: padiData.length,
                                      itemBuilder: (context, index) {
                                        String jenisPadi =
                                            padiData.keys.elementAt(index);
                                        JenisPadi padiDataItem =
                                            padiData[jenisPadi]!;
                                        return ExpansionTile(
                                          title: Row(
                                            mainAxisAlignment:
                                                MainAxisAlignment.spaceBetween,
                                            children: [
                                              Text('Jenis: $jenisPadi'),
                                              Text(padiDataItem.total
                                                  .toString()),
                                            ],
                                          ),
                                          children: padiDataItem
                                              .jenisLahan.entries
                                              .map((lahanEntry) {
                                            final jenisLahan = lahanEntry.key;
                                            final lahanData = lahanEntry.value;
                                            return ExpansionTile(
                                              title: Row(
                                                mainAxisAlignment:
                                                    MainAxisAlignment
                                                        .spaceBetween,
                                                children: [
                                                  Text(
                                                      'Lahan: ${UserController().toCamelCase(jenisLahan)}'),
                                                  Text(lahanData.total
                                                      .toString()),
                                                ],
                                              ),
                                              children: lahanData
                                                  .jenisBantuan.entries
                                                  .map((bantuanEntry) {
                                                final jenisBantuan =
                                                    bantuanEntry.key;
                                                final bantuanData =
                                                    bantuanEntry.value;
                                                return ExpansionTile(
                                                  title: Row(
                                                    mainAxisAlignment:
                                                        MainAxisAlignment
                                                            .spaceBetween,
                                                    children: [
                                                      Text(
                                                          'Bantuan: ${UserController().toCamelCase(jenisBantuan)}'),
                                                      Text(bantuanData.total
                                                          .toString()),
                                                    ],
                                                  ),
                                                  children: bantuanData
                                                      .tipeData.entries
                                                      .map((tipeEntry) {
                                                    final tipeData =
                                                        tipeEntry.key;
                                                    final nilai = tipeEntry
                                                            .value
                                                            .data[tipeData] ??
                                                        0;
                                                    return ListTile(
                                                      title: Row(
                                                        mainAxisAlignment:
                                                            MainAxisAlignment
                                                                .spaceBetween,
                                                        children: [
                                                          Text(
                                                              'Data ${UserController().toCamelCase(tipeData)}'),
                                                          Text(nilai.toString())
                                                        ],
                                                      ),
                                                    );
                                                  }).toList(),
                                                );
                                              }).toList(),
                                            );
                                          }).toList(),
                                        );
                                      },
                                    ),
                                  ),
                                  Padding(
                                    padding: const EdgeInsets.only(
                                        left: 20, bottom: 5, top: 5),
                                    child: Text("Data Pengairan",
                                        style: StyleTheme().styleBlack.copyWith(
                                            fontSize: 18,
                                            fontWeight: FontWeight.w500)),
                                  ),
                                  ConstrainedBox(
                                    constraints: BoxConstraints(
                                      maxHeight:
                                          MediaQuery.of(context).size.height -
                                              150,
                                    ),
                                    child: ListView.builder(
                                      shrinkWrap: true,
                                      physics:
                                          const NeverScrollableScrollPhysics(),
                                      itemCount: pengairanData.length,
                                      itemBuilder: (context, index) {
                                        String jenisPengairan =
                                            pengairanData.keys.elementAt(index);
                                        JenisPengairan pengairanDataItem =
                                            pengairanData[jenisPengairan]!;
                                        return ExpansionTile(
                                          title: Row(
                                            mainAxisAlignment:
                                                MainAxisAlignment.spaceBetween,
                                            children: [
                                              Text(
                                                  'Jenis: ${UserController().toCamelCase(jenisPengairan)}'),
                                              Text(pengairanDataItem.total
                                                  .toString()),
                                            ],
                                          ),
                                          children: [
                                            for (var entry in pengairanDataItem
                                                .pengairanData.entries)
                                              ListTile(
                                                title: Row(
                                                  mainAxisAlignment:
                                                      MainAxisAlignment
                                                          .spaceBetween,
                                                  children: [
                                                    Text(
                                                        'Data ${UserController().toCamelCase(entry.key)}'),
                                                    Text(entry.value.total
                                                        .toString()),
                                                  ],
                                                ),
                                              ),
                                          ],
                                        );
                                      },
                                    ),
                                  ),
                                ],
                              );
                            }
                          }
                        },
                      ),
                    ),
                  ),
                ),
                GestureDetector(
                  onTap: () {
                    setState(() {
                      isOpen = !isOpen;
                    });
                  },
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(
                        "Detail Data Penyuluhan",
                        style: StyleTheme().stylePrimary.copyWith(
                            fontSize: 16, fontWeight: FontWeight.w500),
                      ),
                      Icon(
                        !isOpen ? Icons.arrow_drop_down : Icons.arrow_drop_up,
                        color: ColorTheme().primaryColor,
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 10),
              ],
            ),
          ),
          Expanded(
            child: FutureBuilder<List<DetailPadiModel>>(
              future: padiC.getDetailPadiByUser(widget.date, widget.desaId),
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
                    padding: EdgeInsets.zero,
                    itemCount: itemList.length,
                    itemBuilder: (BuildContext context, int index) {
                      DetailPadiModel data = itemList[index];
                      return GestureDetector(
                        onTap: () {
                          Navigator.push(
                            context,
                            MaterialPageRoute(
                              builder: (_) => FormPadiView(
                                detail: data,
                                onCreate: false,
                              ),
                            ),
                          ).then((value) => setState(() {}));
                        },
                        child: Card(
                          surfaceTintColor: ColorTheme().whiteColor,
                          margin: const EdgeInsets.only(
                              right: 10, left: 10, bottom: 15),
                          elevation: 3,
                          child: SizedBox(
                            height: 180,
                            width: MediaQuery.of(context).size.width,
                            child: Row(
                              children: [
                                Container(
                                  width: 10,
                                  decoration: BoxDecoration(
                                      color: ColorTheme().primaryColor,
                                      borderRadius: const BorderRadius.only(
                                          bottomLeft: Radius.circular(10),
                                          topLeft: Radius.circular(10))),
                                ),
                                Expanded(
                                  child: Padding(
                                    padding: const EdgeInsets.symmetric(
                                        horizontal: 20, vertical: 10),
                                    child: Column(
                                      crossAxisAlignment:
                                          CrossAxisAlignment.start,
                                      children: [
                                        Text(
                                          data.padiName,
                                          style: StyleTheme()
                                              .styleBlack
                                              .copyWith(
                                                  fontWeight: FontWeight.w500,
                                                  fontSize: 16),
                                        ),
                                        Row(
                                          mainAxisAlignment:
                                              MainAxisAlignment.spaceBetween,
                                          children: [
                                            Text(
                                              UserController().toCamelCase(
                                                  data.jenisBantuan),
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
                                              UserController().toCamelCase(
                                                  data.pengairanName),
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
                                              UserController()
                                                  .toCamelCase(data.tipeData),
                                              style: StyleTheme()
                                                  .styleBlack
                                                  .copyWith(
                                                      fontSize: 14,
                                                      fontWeight:
                                                          FontWeight.w500),
                                            ),
                                            Text(
                                              data.nilai.toString(),
                                              style: StyleTheme()
                                                  .styleBlack
                                                  .copyWith(
                                                    fontSize: 14,
                                                    fontWeight: FontWeight.bold,
                                                  ),
                                            ),
                                          ],
                                        ),
                                        const SizedBox(height: 10),
                                        Row(
                                          children: [
                                            Expanded(
                                              flex: 1,
                                              child: ElevatedButton.icon(
                                                onPressed: () async {
                                                  await Navigator.push(
                                                    context,
                                                    MaterialPageRoute(
                                                      builder: (_) =>
                                                          FormPadiView(
                                                        detail: data,
                                                        onCreate: false,
                                                      ),
                                                    ),
                                                  ).then((value) =>
                                                      setState(() {}));
                                                },
                                                icon: Icon(Icons.edit,
                                                    color: ColorTheme()
                                                        .primaryColor),
                                                label: Text('Edit',
                                                    style: StyleTheme()
                                                        .stylePrimary
                                                        .copyWith(
                                                            fontSize: 14)),
                                                style: ElevatedButton.styleFrom(
                                                  surfaceTintColor:
                                                      ColorTheme().whiteColor,
                                                  side: BorderSide(
                                                      color: ColorTheme()
                                                          .primaryColor,
                                                      width: 2),
                                                  shape: RoundedRectangleBorder(
                                                    borderRadius:
                                                        BorderRadius.circular(
                                                            30),
                                                  ),
                                                ),
                                              ),
                                            ),
                                            const SizedBox(width: 10),
                                            Expanded(
                                              flex: 1,
                                              child: ElevatedButton.icon(
                                                onPressed: () async {
                                                  bool? shouldDelete =
                                                      await _showDeleteConfirmationDialog(
                                                          context);
                                                  if (shouldDelete == true) {
                                                    await padiC
                                                        .deleteDetailById(
                                                            data.id);
                                                    setState(() {});
                                                  }
                                                },
                                                icon: const Icon(Icons.delete,
                                                    color: Colors.red),
                                                label: Text('Hapus',
                                                    style: StyleTheme()
                                                        .stylePrimary
                                                        .copyWith(
                                                            fontSize: 14,
                                                            color: Colors.red)),
                                                style: ElevatedButton.styleFrom(
                                                  surfaceTintColor:
                                                      ColorTheme().whiteColor,
                                                  side: const BorderSide(
                                                      color: Colors.red,
                                                      width: 2),
                                                  shape: RoundedRectangleBorder(
                                                    borderRadius:
                                                        BorderRadius.circular(
                                                            30),
                                                  ),
                                                ),
                                              ),
                                            ),
                                          ],
                                        )
                                      ],
                                    ),
                                  ),
                                ),
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
          ),
        ],
      ),
    );
  }

  Future<bool> _showDeleteConfirmationDialog(BuildContext context) async {
    return await showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text("Konfirmasi Aksi"),
          content: const Text("Anda yakin ingin menghapus data ini?"),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(false); // Kembali dengan nilai false
              },
              child: const Text("Cancel"),
            ),
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(true); // Kembali dengan nilai true
              },
              child: const Text("Delete"),
            ),
          ],
        );
      },
    );
  }
}
