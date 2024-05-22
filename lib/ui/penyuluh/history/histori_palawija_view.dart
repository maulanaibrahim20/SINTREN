import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class HistoriPalawijaView extends StatefulWidget {
  const HistoriPalawijaView({super.key});

  @override
  State<HistoriPalawijaView> createState() => HistoriPalawijaViewState();
}

class HistoriPalawijaViewState extends State<HistoriPalawijaView> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      body: ListView(
        children: [
          const SizedBox(height: 10),
          Column(
            children: List.generate(
              10,
              (index) {
                return Column(
                  children: [
                    Card(
                      surfaceTintColor: ColorTheme().whiteColor,
                      margin: const EdgeInsets.only(right: 15, left: 15, bottom: 10),
                      elevation: 3,
                      child: SizedBox(
                        height: 260,
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
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      "Hibrida",
                                      style: StyleTheme().styleBlack.copyWith(
                                          fontWeight: FontWeight.w500,
                                          fontSize: 16),
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Lohbener",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "Tidak Terverifikasi",
                                          style: StyleTheme()
                                              .styleBlack
                                              .copyWith(color: Colors.red),
                                        ),
                                      ],
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Lahan Sawah",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "1/5/2024",
                                          style: StyleTheme().styleBlack,
                                        ),
                                      ],
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Irigasi Tersier",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "Bantuan Pemerintah",
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
                                          "Tanaman Akhir Bulan Lalu:",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "1000",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
                                                    fontWeight: FontWeight.bold,
                                                  ),
                                        ),
                                      ],
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Tanam:",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "100",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
                                                    fontWeight: FontWeight.bold,
                                                  ),
                                        ),
                                      ],
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Panen:",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "100",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
                                                    fontWeight: FontWeight.bold,
                                                  ),
                                        ),
                                      ],
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Puso/Rusak:",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "100",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
                                                    fontWeight: FontWeight.bold,
                                                  ),
                                        ),
                                      ],
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Tanaman Akhir Bulan Ini:",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "900",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
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
                                            onPressed: () {
                                              // Navigator.push(context,
                                              //     MaterialPageRoute(builder: (_) => AddPadiView()));
                                            },
                                            icon: Icon(Icons.edit,
                                                color:
                                                    ColorTheme().primaryColor),
                                            label: Text('Edit',
                                                style: StyleTheme()
                                                    .stylePrimary
                                                    .copyWith(fontSize: 14)),
                                            style: ElevatedButton.styleFrom(
                                              surfaceTintColor:
                                                  ColorTheme().whiteColor,
                                              side: BorderSide(
                                                  color:
                                                      ColorTheme().primaryColor,
                                                  width: 2),
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
                                            onPressed: () {
                                              // Navigator.push(context,
                                              //     MaterialPageRoute(builder: (_) => AddPadiView()));
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
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                    Card(
                      surfaceTintColor: ColorTheme().whiteColor,
                      margin: const EdgeInsets.only(right: 15, left: 15, bottom: 10),
                      elevation: 3,
                      child: SizedBox(
                        height: 260,
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
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      "Hibrida",
                                      style: StyleTheme().styleBlack.copyWith(
                                          fontWeight: FontWeight.w500,
                                          fontSize: 16),
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Lohbener",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "Terverifikasi",
                                          style: StyleTheme()
                                              .styleBlack
                                              .copyWith(color: Colors.green),
                                        ),
                                      ],
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Lahan Sawah",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "1/5/2024",
                                          style: StyleTheme().styleBlack,
                                        ),
                                      ],
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Irigasi Tersier",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "Bantuan Pemerintah",
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
                                          "Tanaman Akhir Bulan Lalu:",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "1000",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
                                                    fontWeight: FontWeight.bold,
                                                  ),
                                        ),
                                      ],
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Tanam:",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "100",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
                                                    fontWeight: FontWeight.bold,
                                                  ),
                                        ),
                                      ],
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Panen:",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "100",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
                                                    fontWeight: FontWeight.bold,
                                                  ),
                                        ),
                                      ],
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Puso/Rusak:",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "100",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
                                                    fontWeight: FontWeight.bold,
                                                  ),
                                        ),
                                      ],
                                    ),
                                    Row(
                                      mainAxisAlignment:
                                          MainAxisAlignment.spaceBetween,
                                      children: [
                                        Text(
                                          "Tanaman Akhir Bulan Ini:",
                                          style: StyleTheme().styleBlack,
                                        ),
                                        Text(
                                          "900",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
                                                    fontWeight: FontWeight.bold,
                                                  ),
                                        ),
                                      ],
                                    ),
                                    const SizedBox(height: 10),
                                    ElevatedButton.icon(
                                      onPressed: () {
                                        // Navigator.push(context,
                                        //     MaterialPageRoute(builder: (_) => AddPadiView()));
                                      },
                                      icon: Icon(Icons.edit_square,
                                          color: ColorTheme().primaryColor),
                                      label: Text('Permintaan Edit',
                                          style: StyleTheme()
                                              .stylePrimary
                                              .copyWith(fontSize: 14)),
                                      style: ElevatedButton.styleFrom(
                                          surfaceTintColor:
                                              ColorTheme().whiteColor,
                                          side: BorderSide(
                                              color: ColorTheme().primaryColor,
                                              width: 2),
                                          shape: RoundedRectangleBorder(
                                            borderRadius:
                                                BorderRadius.circular(30),
                                          ),
                                          fixedSize: Size(
                                              MediaQuery.of(context).size.width,
                                              30)),
                                    )
                                  ],
                                ),
                              ),
                            ),
                          ],
                        ),
                      ),
                    ),
                  ],
                );
              },
            ),
          ),
          const SizedBox(height: 90),
        ],
      ),
    );
  }
}
