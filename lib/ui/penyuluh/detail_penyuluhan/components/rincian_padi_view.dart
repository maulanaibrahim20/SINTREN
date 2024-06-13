import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/penyuluh/padi_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/kesimpulan_data_padi_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/detail_penyuluhan/components/ringkasan_padi_widget.dart';

class RincianPadiView extends StatefulWidget {
  const RincianPadiView({
    super.key,
    required this.date,
    required this.desaId,
    required this.desaName,
    required this.isRincian,
  });
  final String date;
  final String desaId;
  final String desaName;
  final bool isRincian;

  @override
  State<RincianPadiView> createState() => _RincianPadiViewState();
}

class _RincianPadiViewState extends State<RincianPadiView> {
  final padiC = PadiController();

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
          'Rincian Data Padi',
          style: StyleTheme().styleWhite.copyWith(
                fontSize: 20.sp,
                fontWeight: FontWeight.w500,
              ),
        ),
        backgroundColor: ColorTheme().primaryColor,
      ),
      body: SingleChildScrollView(
        child: Column(
          children: [
            RingkasanPadiWidget(
              date: widget.date,
              desaId: widget.desaId,
              desaName: widget.desaName,
              isRincian: widget.isRincian,
            ),
            Card(
              margin: EdgeInsets.symmetric(
                horizontal: 10.w,
                vertical: 10.h,
              ),
              elevation: 3,
              color: ColorTheme().whiteColor,
              surfaceTintColor: ColorTheme().whiteColor,
              child: ConstrainedBox(
                constraints: const BoxConstraints(),
                child: FutureBuilder(
                  future: Future.wait([
                    padiC.getKesimpulanDataPengairan(
                        widget.date, widget.desaId),
                    padiC.getKesimpulanDataPadi(widget.date, widget.desaId)
                  ]),
                  builder: (context, AsyncSnapshot<List<dynamic>> snapshot) {
                    if (snapshot.connectionState == ConnectionState.waiting) {
                      return const Center(child: CircularProgressIndicator());
                    } else if (snapshot.hasError) {
                      return Center(child: Text('Error: ${snapshot.error}'));
                    } else {
                      final pengairanData =
                          snapshot.data![0] as Map<String, JenisPengairan>;
                      final padiData =
                          snapshot.data![1] as Map<String, JenisPadi>;

                      if (pengairanData.isEmpty && padiData.isEmpty) {
                        return const SizedBox.shrink();
                      } else {
                        return Padding(
                          padding: EdgeInsets.all(8.sp),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              const Divider(),
                              Padding(
                                padding: EdgeInsets.symmetric(
                                    horizontal: 20.w, vertical: 5.h),
                                child: Text(
                                  "Rincian Data Padi",
                                  style: StyleTheme().styleBlack.copyWith(
                                        fontSize: 18.sp,
                                        fontWeight: FontWeight.w500,
                                      ),
                                ),
                              ),
                              const Divider(),
                              ConstrainedBox(
                                constraints: BoxConstraints(
                                  maxHeight:
                                      MediaQuery.of(context).size.height -
                                          150.h,
                                ),
                                child: ListView.builder(
                                  shrinkWrap: true,
                                  physics: const NeverScrollableScrollPhysics(),
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
                                          Text(
                                            'Jenis $jenisPadi',
                                            style: StyleTheme()
                                                .styleBlack
                                                .copyWith(fontSize: 14.sp),
                                          ),
                                          Text(
                                            "${padiDataItem.total} hektar",
                                            style: StyleTheme()
                                                .styleBlack
                                                .copyWith(
                                                  fontSize: 14.sp,
                                                  fontWeight: FontWeight.w500,
                                                ),
                                          ),
                                        ],
                                      ),
                                      children: padiDataItem.jenisLahan.entries
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
                                                    .copyWith(fontSize: 14.sp),
                                              ),
                                              Text(
                                                "${lahanData.total} hektar",
                                                style: StyleTheme()
                                                    .styleBlack
                                                    .copyWith(
                                                      fontSize: 14.sp,
                                                      fontWeight:
                                                          FontWeight.w500,
                                                    ),
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
                                                        .copyWith(
                                                            fontSize: 14.sp),
                                                  ),
                                                  Text(
                                                    "${bantuanData.total} hektar",
                                                    style: StyleTheme()
                                                        .styleBlack
                                                        .copyWith(
                                                          fontSize: 14.sp,
                                                          fontWeight:
                                                              FontWeight.w500,
                                                        ),
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
                                                                fontSize:
                                                                    14.sp),
                                                      ),
                                                      Text(
                                                        "$nilai hektar",
                                                        style: StyleTheme()
                                                            .styleBlack
                                                            .copyWith(
                                                              fontSize: 14.sp,
                                                              fontWeight:
                                                                  FontWeight
                                                                      .w500,
                                                            ),
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
                              const Divider(),
                              if (pengairanData.isNotEmpty) ...[
                                Padding(
                                  padding: EdgeInsets.symmetric(
                                      horizontal: 20.w, vertical: 5.h),
                                  child: Text(
                                    "Rincian Data Pengairan",
                                    style: StyleTheme().styleBlack.copyWith(
                                          fontSize: 18.sp,
                                          fontWeight: FontWeight.w500,
                                        ),
                                  ),
                                ),
                                const Divider(),
                                ConstrainedBox(
                                  constraints: BoxConstraints(
                                    maxHeight:
                                        MediaQuery.of(context).size.height -
                                            150.h,
                                  ),
                                  child: ListView.builder(
                                    shrinkWrap: true,
                                    physics: const NeverScrollableScrollPhysics(),
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
                                              UserController()
                                                  .toCamelCase(jenisPengairan),
                                              style: StyleTheme()
                                                  .styleBlack
                                                  .copyWith(fontSize: 14.sp),
                                            ),
                                            Text(
                                              "${pengairanDataItem.total} hektar",
                                              style: StyleTheme()
                                                  .styleBlack
                                                  .copyWith(
                                                    fontSize: 14.sp,
                                                    fontWeight: FontWeight.w500,
                                                  ),
                                            ),
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
                                                    'Data ${UserController().toCamelCase(entry.key)}',
                                                    style: StyleTheme()
                                                        .styleBlack
                                                        .copyWith(
                                                            fontSize: 14.sp),
                                                  ),
                                                  Text(
                                                    "${entry.value.total} hektar",
                                                    style: StyleTheme()
                                                        .styleBlack
                                                        .copyWith(
                                                          fontSize: 14.sp,
                                                          fontWeight:
                                                              FontWeight.w500,
                                                        ),
                                                  ),
                                                ],
                                              ),
                                            ),
                                        ],
                                      );
                                    },
                                  ),
                                ),
                              ],
                            ],
                          ),
                        );
                      }
                    }
                  },
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
