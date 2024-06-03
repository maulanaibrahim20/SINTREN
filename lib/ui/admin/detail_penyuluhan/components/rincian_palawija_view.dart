import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/penyuluh/palawija_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/kesimpulan_data_palawija_model.dart';
import 'package:sintren_mobile/ui/admin/detail_penyuluhan/components/ringkasan_palawija_widget.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class RincianPalawijaView extends StatefulWidget {
  const RincianPalawijaView(
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
  State<RincianPalawijaView> createState() => _RincianPalawijaViewState();
}

class _RincianPalawijaViewState extends State<RincianPalawijaView> {
  final palawijaC = PalawijaController();
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      appBar: AppBar(
        elevation: 0,
        centerTitle: false,
        foregroundColor: ColorTheme().whiteColor,
        flexibleSpace: Container(
          decoration: BoxDecoration(gradient: ColorTheme().linearColor),
        ),
        title: Text(
          'Rincian Data Palawija',
          style: StyleTheme().styleWhite.copyWith(
                fontSize: 20,
                fontWeight: FontWeight.w500,
              ),
        ),
        backgroundColor: ColorTheme().primaryColor,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            RingkasanPalawijaWidget(
              date: widget.date,
              desaId: widget.desaId,
              desaName: widget.desaName,
              isRincian: widget.isRincian,
            ),
            Card(
              margin: const EdgeInsets.symmetric(horizontal: 10, vertical: 10),
              elevation: 3,
              surfaceTintColor: ColorTheme().whiteColor,
              color: ColorTheme().whiteColor,
              child: ConstrainedBox(
                constraints: BoxConstraints(),
                child: FutureBuilder(
                  future: palawijaC.getKesimpulanDataPalawija(
                      widget.date, widget.desaId),
                  builder: (context, snapshot) {
                    if (snapshot.connectionState == ConnectionState.waiting) {
                      return const Center(child: CircularProgressIndicator());
                    } else if (snapshot.hasError) {
                      return Center(child: Text('Error: ${snapshot.error}'));
                    } else {
                      final palawijaData =
                          snapshot.data as Map<String, JenisPalawija>;

                      if (palawijaData.isEmpty) {
                        return const Center(child: Text('No data available'));
                      } else {
                        return Padding(
                          padding: const EdgeInsets.all(8.0),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Divider(),
                              Padding(
                                padding: const EdgeInsets.symmetric(
                                    horizontal: 20, vertical: 5),
                                child: Text("Rincian Data Palawija",
                                    style: StyleTheme().styleBlack.copyWith(
                                        fontSize: 18,
                                        fontWeight: FontWeight.w500)),
                              ),
                              Divider(),
                              ConstrainedBox(
                                constraints: BoxConstraints(
                                  maxHeight:
                                      MediaQuery.of(context).size.height - 150,
                                ),
                                child: ListView.builder(
                                  shrinkWrap: true,
                                  physics: const NeverScrollableScrollPhysics(),
                                  itemCount: palawijaData.length,
                                  itemBuilder: (context, index) {
                                    String jenisPadi =
                                        palawijaData.keys.elementAt(index);
                                    JenisPalawija palawijaDataItem =
                                        palawijaData[jenisPadi]!;
                                    return ExpansionTile(
                                      title: Row(
                                        mainAxisAlignment:
                                            MainAxisAlignment.spaceBetween,
                                        children: [
                                          Text(
                                            'Jenis $jenisPadi',
                                            style: StyleTheme()
                                                .styleBlack
                                                .copyWith(fontSize: 14),
                                          ),
                                          Text(
                                            "${palawijaDataItem.total} hektar",
                                            style: StyleTheme()
                                                .styleBlack
                                                .copyWith(
                                                    fontSize: 14,
                                                    fontWeight:
                                                        FontWeight.w500),
                                          ),
                                        ],
                                      ),
                                      children: palawijaDataItem
                                          .jenisLahan.entries
                                          .map((lahanEntry) {
                                        final jenisLahan = lahanEntry.key;
                                        final lahanData = lahanEntry.value;
                                        return ExpansionTile(
                                          title: Row(
                                            mainAxisAlignment:
                                                MainAxisAlignment.spaceBetween,
                                            children: [
                                              Text(
                                                'Lahan ${UserController().toCamelCase(jenisLahan)}',
                                                style: StyleTheme()
                                                    .styleBlack
                                                    .copyWith(fontSize: 14),
                                              ),
                                              Text(
                                                lahanData.total.toString() +
                                                    " hektar",
                                                style: StyleTheme()
                                                    .styleBlack
                                                    .copyWith(
                                                        fontSize: 14,
                                                        fontWeight:
                                                            FontWeight.w500),
                                              ),
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
                                                    UserController()
                                                        .toCamelCase(
                                                            jenisBantuan),
                                                    style: StyleTheme()
                                                        .styleBlack
                                                        .copyWith(fontSize: 14),
                                                  ),
                                                  Text(
                                                    "${bantuanData.total} hektar",
                                                    style: StyleTheme()
                                                        .styleBlack
                                                        .copyWith(
                                                            fontSize: 14,
                                                            fontWeight:
                                                                FontWeight
                                                                    .w500),
                                                  ),
                                                ],
                                              ),
                                              children: bantuanData
                                                  .tipeData.entries
                                                  .map((tipeEntry) {
                                                final tipeData = tipeEntry.key;
                                                final nilai = tipeEntry
                                                        .value.data[tipeData] ??
                                                    0;
                                                return ListTile(
                                                  title: Row(
                                                    mainAxisAlignment:
                                                        MainAxisAlignment
                                                            .spaceBetween,
                                                    children: [
                                                      Text(
                                                        'Data ${UserController().toCamelCase(tipeData)}',
                                                        style: StyleTheme()
                                                            .styleBlack
                                                            .copyWith(
                                                                fontSize: 14),
                                                      ),
                                                      Text(
                                                        "$nilai hektar",
                                                        style: StyleTheme()
                                                            .styleBlack
                                                            .copyWith(
                                                                fontSize: 14,
                                                                fontWeight:
                                                                    FontWeight
                                                                        .w500),
                                                      )
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
                            ],
                          ),
                        );
                      }
                    }
                  },
                ),
              ),
            )
          ],
        ),
      ),
    );
  }
}
