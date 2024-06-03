import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/penyuluh/palawija_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/detail_penyuluhan/components/ringkasan_palawija_widget.dart';
import 'package:sintren_mobile/ui/penyuluh/form/form_palawija_view.dart';

class DetailPalawijaView extends StatefulWidget {
  const DetailPalawijaView(
      {super.key,
      required this.date,
      required this.desaId,
      required this.desaName,
      required this.isVerify});

  final String date;
  final String desaId;
  final String desaName;
  final bool isVerify;

  @override
  State<DetailPalawijaView> createState() => _DetailPalawijaViewState();
}

class _DetailPalawijaViewState extends State<DetailPalawijaView> {
  final palawijaC = PalawijaController();
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
          RingkasanPalawijaWidget(
            date: widget.date,
            desaId: widget.desaId,
            desaName: widget.desaName,
            isVerify: widget.isVerify,
            isRincian: false,
          ),
          Expanded(
            child: FutureBuilder<List<DetailPalawijaModel>>(
              future:
                  palawijaC.getDetailPalawijaByUser(widget.date, widget.desaId),
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
                      DetailPalawijaModel data = itemList[index];
                      return GestureDetector(
                        onTap: () {
                          Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (_) => FormPalawijaView(
                                  detail: data,
                                  onCreate: false,
                                ),
                              ));
                        },
                        child: Card(
                          surfaceTintColor: ColorTheme().whiteColor,
                          margin: const EdgeInsets.only(
                              right: 10, left: 10, bottom: 15),
                          elevation: 3,
                          child: SizedBox(
                            height: widget.isVerify ? 130 : 180,
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
                                          UserController()
                                              .toCamelCase(data.palawijaName),
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
                                              "Lahan ${UserController().toCamelCase(data.jenisLahan)}",
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
                                        widget.isVerify
                                            ? const SizedBox.shrink()
                                            : Row(
                                                children: [
                                                  Expanded(
                                                    flex: 1,
                                                    child: ElevatedButton.icon(
                                                      onPressed: () async {
                                                        await Navigator.push(
                                                            context,
                                                            MaterialPageRoute(
                                                                builder: (_) =>
                                                                    FormPalawijaView(
                                                                      detail:
                                                                          data,
                                                                      onCreate:
                                                                          false,
                                                                    )));
                                                        setState(() {});
                                                      },
                                                      icon: Icon(Icons.edit,
                                                          color: ColorTheme()
                                                              .primaryColor),
                                                      label: Text('Edit',
                                                          style: StyleTheme()
                                                              .stylePrimary
                                                              .copyWith(
                                                                  fontSize:
                                                                      14)),
                                                      style: ElevatedButton
                                                          .styleFrom(
                                                        surfaceTintColor:
                                                            ColorTheme()
                                                                .whiteColor,
                                                        side: BorderSide(
                                                            color: ColorTheme()
                                                                .primaryColor,
                                                            width: 2),
                                                        shape:
                                                            RoundedRectangleBorder(
                                                          borderRadius:
                                                              BorderRadius
                                                                  .circular(30),
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
                                                        if (shouldDelete ==
                                                            true) {
                                                          await palawijaC
                                                              .deleteDetailById(
                                                                  data.id);
                                                          setState(() {});
                                                        }
                                                      },
                                                      icon: const Icon(
                                                          Icons.delete,
                                                          color: Colors.red),
                                                      label: Text('Hapus',
                                                          style: StyleTheme()
                                                              .stylePrimary
                                                              .copyWith(
                                                                  fontSize: 14,
                                                                  color: Colors
                                                                      .red)),
                                                      style: ElevatedButton
                                                          .styleFrom(
                                                        surfaceTintColor:
                                                            ColorTheme()
                                                                .whiteColor,
                                                        side: const BorderSide(
                                                            color: Colors.red,
                                                            width: 2),
                                                        shape:
                                                            RoundedRectangleBorder(
                                                          borderRadius:
                                                              BorderRadius
                                                                  .circular(30),
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
