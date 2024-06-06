import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/penyuluh/palawija_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/detail_penyuluhan/components/rincian_palawija_view.dart';
import 'package:sintren_mobile/ui/penyuluh/form/form_palawija_view.dart';

class RingkasanPalawijaWidget extends StatefulWidget {
  const RingkasanPalawijaWidget(
      {super.key,
      required this.date,
      required this.desaId,
      required this.desaName,
      required this.isRincian});
  final String date;
  final String desaId;
  final String desaName;
  final bool isRincian;

  @override
  State<RingkasanPalawijaWidget> createState() =>
      _RingkasanPalawijaWidgetState();
}

class _RingkasanPalawijaWidgetState extends State<RingkasanPalawijaWidget> {
  @override
  Widget build(BuildContext context) {
    return Card(
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
                widget.isRincian
                    ? const SizedBox.shrink()
                    : Container(
                        height: 40,
                        width: 40,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          gradient: ColorTheme().linearColor,
                        ),
                        child: Center(
                          child: GestureDetector(
                            onTap: () {
                              DesaModel desa = DesaModel(
                                  id: widget.desaId, name: widget.desaName);
                              Navigator.push(
                                  context,
                                  MaterialPageRoute(
                                      builder: (_) => FormPalawijaView(
                                            desa: desa,
                                            onCreate: true,
                                            date: "${widget.date}-01",
                                          )));
                            },
                            child: Icon(
                              Icons.add,
                              color: ColorTheme().whiteColor,
                              size: 25,
                            ),
                          ),
                        ),
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
              future: PalawijaController()
                  .getDetailPalawijaByUser(widget.date, widget.desaId),
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
                  Map<String, double> totalValues = {
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
                      child: Padding(
                        padding: const EdgeInsets.all(8.0),
                        child: Text(
                          "Data Kosong",
                          style: StyleTheme().styleBlack.copyWith(
                              color: Colors.grey,
                              fontSize: 18,
                              fontWeight: FontWeight.w500),
                        ),
                      ),
                    );
                  }

                  double total = totalValues['panen']! +
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
                              mainAxisAlignment: MainAxisAlignment.spaceBetween,
                              children: [
                                Text(
                                  "${UserController().toCamelCase(entry.key)}:",
                                  style: StyleTheme()
                                      .styleBlack
                                      .copyWith(fontSize: 14),
                                ),
                                Text(
                                  '${entry.value} hektar',
                                  style: StyleTheme().styleBlack.copyWith(
                                      fontSize: 14,
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
                          mainAxisAlignment: MainAxisAlignment.spaceBetween,
                          children: [
                            Text(
                              'Total Tanaman Bulan Ini:',
                              style: StyleTheme()
                                  .styleBlack
                                  .copyWith(fontSize: 14),
                            ),
                            Text(
                              '${total < 0 ? 0 : total} hektar',
                              style: StyleTheme().styleBlack.copyWith(
                                  fontSize: 14, fontWeight: FontWeight.w500),
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
          widget.isRincian
              ? const SizedBox.shrink()
              : GestureDetector(
                  onTap: () {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => RincianPalawijaView(
                            date: widget.date,
                            desaId: widget.desaId,
                            desaName: widget.desaName,
                            isRincian: true),
                      ),
                    );
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
                        Icons.arrow_right_outlined,
                        color: ColorTheme().primaryColor,
                      ),
                    ],
                  ),
                ),
          const SizedBox(height: 10),
        ],
      ),
    );
  }
}
