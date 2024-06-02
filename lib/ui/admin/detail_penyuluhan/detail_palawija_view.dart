import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/admin/admin_palawija_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class DetailPalawijaView extends StatefulWidget {
  const DetailPalawijaView(
      {super.key,
      required this.date,
      required this.desaId,
      required this.desaName});

  final String date;
  final String desaId;
  final String desaName;

  @override
  State<DetailPalawijaView> createState() => DetailPalawijaViewState();
}

class DetailPalawijaViewState extends State<DetailPalawijaView> {
  final palawijaC = AdminPalawijaController();
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
                    ],
                  ),
                ),
                const SizedBox(height: 10),
                Stack(
                  children: [
                    const Divider(thickness: 2),
                    Container(
                      color: ColorTheme().whiteColor,
                      margin: const EdgeInsets.only(left: 20),
                      padding: const EdgeInsets.symmetric(horizontal: 8.0),
                      child: Text(
                        "Ringkasan penyuluhan bulan ini",
                        style: StyleTheme()
                            .styleBlack
                            .copyWith(color: Colors.black87, fontSize: 14),
                      ),
                    ),
                  ],
                ),
                const SizedBox(height: 5),
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 20),
                  child: FutureBuilder(
                    future:
                        palawijaC.getDetailPalawijaByDesa(widget.date, widget.desaId),
                    builder: ((context, snapshot) {
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
                        Map<String, int> totalValues = {
                          'panen': 0,
                          'tanam': 0,
                          'puso/rusak': 0,
                        };

                        for (var item in itemList) {
                          if (totalValues.containsKey(item.tipeData)) {
                            totalValues[item.tipeData] =
                                totalValues[item.tipeData]! + item.nilai;
                          } else {
                            totalValues[item.tipeData] = item.nilai;
                          }
                        }

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

                        int total = totalValues['panen']! +
                            totalValues['tanam']! -
                            totalValues['puso/rusak']!;

                        return Column(
                          children: [
                            Column(
                              mainAxisSize: MainAxisSize.min,
                              children: totalValues.entries.map((entry) {
                                return Padding(
                                  padding: const EdgeInsets.symmetric(
                                      horizontal: 10, vertical: 5),
                                  child: Row(
                                    mainAxisAlignment:
                                        MainAxisAlignment.spaceBetween,
                                    children: [
                                      Text(
                                        "${UserController().toCamelCase(entry.key)}:",
                                        style: StyleTheme()
                                            .styleBlack
                                            .copyWith(fontSize: 16),
                                      ),
                                      Text(
                                        '${entry.value} hektar',
                                        style: StyleTheme().styleBlack.copyWith(
                                            fontSize: 16,
                                            fontWeight: FontWeight.w500),
                                      ),
                                    ],
                                  ),
                                );
                              }).toList(),
                            ),
                            const Divider(),
                            Padding(
                              padding: const EdgeInsets.symmetric(
                                  horizontal: 10, vertical: 5),
                              child: Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    'Total Tanaman Bulan Ini:',
                                    style: StyleTheme()
                                        .styleBlack
                                        .copyWith(fontSize: 16),
                                  ),
                                  Text(
                                    '$total hektar',
                                    style: StyleTheme().styleBlack.copyWith(
                                        fontSize: 16,
                                        fontWeight: FontWeight.w500),
                                  ),
                                ],
                              ),
                            )
                          ],
                        );
                      }
                    }),
                  ),
                ),
                const Divider(thickness: 2),
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
                        "Lihat Selengkapnya",
                        style: StyleTheme().stylePrimary.copyWith(
                            fontSize: 16, fontWeight: FontWeight.w500),
                      ),
                      Icon(
                        !isOpen
                            ? Icons.arrow_right_outlined
                            : Icons.arrow_drop_up,
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
            child: FutureBuilder<List<DetailPalawijaModel>>(
              future:
                  palawijaC.getDetailPalawijaByDesa(widget.date, widget.desaId),
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
                      return Card(
                        surfaceTintColor: ColorTheme().whiteColor,
                        margin: const EdgeInsets.only(
                            right: 10, left: 10, bottom: 15),
                        elevation: 3,
                        child: SizedBox(
                          height: 130,
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
                                        style: StyleTheme().styleBlack.copyWith(
                                            fontWeight: FontWeight.w500,
                                            fontSize: 16),
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
                                    ],
                                  ),
                                ),
                              ),
                            ],
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
}
