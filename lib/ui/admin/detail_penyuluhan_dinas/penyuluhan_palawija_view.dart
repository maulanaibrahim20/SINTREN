import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/admin/admin_palawija_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class PenyuluhanPalawijaView extends StatefulWidget {
  const PenyuluhanPalawijaView(
      {super.key,
      this.jenisLahan,
      this.jenisBantuan,
      this.jenisPalawija,
      this.jenisData,
      this.search});

  final String? jenisLahan;
  final String? jenisBantuan;
  final String? jenisPalawija;
  final String? jenisData;
  final String? search;

  @override
  State<PenyuluhanPalawijaView> createState() => _PenyuluhanPalawijaViewState();
}

class _PenyuluhanPalawijaViewState extends State<PenyuluhanPalawijaView> {
  AdminPalawijaController palawijaC = AdminPalawijaController();

  List<DetailPalawijaModel> filterPalawijaList({
    required List<DetailPalawijaModel> palawijaList,
    String? jenisLahan,
    String? jenisPalawija,
    String? jenisBantuan,
    String? jenisData,
    String? search,
  }) {
    log(search.toString());
    return palawijaList.where((palawija) {
      final matchJenisLahan =
          jenisLahan == null || palawija.jenisLahan == jenisLahan;
      final matchJenisPalawija =
          jenisPalawija == null || palawija.palawijaName == jenisPalawija;
      final matchJenisBantuan =
          jenisBantuan == null || palawija.jenisBantuan == jenisBantuan;
      final matchJenisData =
          jenisData == null || palawija.tipeData == jenisData;
      final matchDesa = search == null ||
          palawija.desaName.toLowerCase().contains(search.toLowerCase());
      final matchKecamatan = search == null ||
          palawija.kecamatanName.toLowerCase().contains(search.toLowerCase());

      return matchJenisLahan &&
          matchJenisPalawija &&
          matchJenisBantuan &&
          matchJenisData &&
          (matchDesa ||
          matchKecamatan);
    }).toList();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      body: FutureBuilder<List<DetailPalawijaModel>>(
        future: palawijaC.getAllPenyuluhanPalawija(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.error,
                    color: Colors.grey,
                    size: 50.sp,
                  ),
                  Text(
                    "Internal Server Error",
                    style: StyleTheme().styleBlack.copyWith(
                        fontSize: 18.sp,
                        fontWeight: FontWeight.w500,
                        color: Colors.grey),
                  ),
                ],
              ),
            );
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.person,
                    color: Colors.grey,
                    size: 50.sp,
                  ),
                  Text(
                    "Data Penyuluhan Kosong",
                    style: StyleTheme().styleBlack.copyWith(
                        fontSize: 18.sp,
                        fontWeight: FontWeight.w500,
                        color: Colors.grey),
                  ),
                ],
              ),
            );
          } else {
            List<DetailPalawijaModel> palawijaList = filterPalawijaList(
              palawijaList: snapshot.data!,
              jenisLahan: widget.jenisLahan,
              jenisPalawija: widget.jenisPalawija,
              jenisBantuan: widget.jenisBantuan,
              jenisData: widget.jenisData,
              search: widget.search,
            );
            return ListView.builder(
              padding: EdgeInsets.only(top: 10.h),
              itemCount: palawijaList.length,
              itemBuilder: (context, index) {
                DetailPalawijaModel data = palawijaList[index];
                return Card(
                  surfaceTintColor: ColorTheme().whiteColor,
                  color: ColorTheme().whiteColor,
                  margin: EdgeInsets.symmetric(vertical: 5.h, horizontal: 10.w),
                  elevation: 3,
                  child: ConstrainedBox(
                    constraints: const BoxConstraints(),
                    child: Padding(
                      padding: EdgeInsets.symmetric(
                          horizontal: 20.w, vertical: 10.h),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            data.palawijaName,
                            style: StyleTheme().styleBlack.copyWith(
                                fontWeight: FontWeight.w500, fontSize: 16.sp),
                          ),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                UserController()
                                    .toCamelCase(data.kecamatanName),
                                style: StyleTheme().styleBlack,
                              ),
                              Text(
                                UserController().toCamelCase(data.desaName),
                                style: StyleTheme().styleBlack,
                              ),
                            ],
                          ),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                UserController().toCamelCase(data.jenisBantuan),
                                style: StyleTheme().styleBlack,
                              ),
                              Text(
                                UserController().normalizeDate(data.date),
                                style: StyleTheme().styleBlack,
                              ),
                            ],
                          ),
                          Text(
                            'Lahan ${UserController().toCamelCase(data.jenisLahan)}',
                            style: StyleTheme().styleBlack,
                          ),
                          const Divider(),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                UserController().toCamelCase(data.tipeData),
                                style: StyleTheme().styleBlack.copyWith(
                                    fontSize: 14.sp,
                                    fontWeight: FontWeight.w500),
                              ),
                              Text(
                                "${data.nilai} hektar",
                                style: StyleTheme().styleBlack.copyWith(
                                      fontSize: 14.sp,
                                      fontWeight: FontWeight.bold,
                                    ),
                              ),
                            ],
                          ),
                          const Divider(),
                          SizedBox(height: 5.h),
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
    );
  }
}
