import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/admin/admin_palawija_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/ui/dinas/detail_penyuluhan/components/rincian_palawija_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

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
      margin: EdgeInsets.symmetric(horizontal: 10.w, vertical: 10.h),
      elevation: 3,
      child: Container(
        decoration: BoxDecoration(
            gradient: ColorTheme().linearColor2,
            borderRadius: BorderRadius.circular(15),
            border: Border.all(color: ColorTheme().linearBorderColor2)),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            SizedBox(height: 20.h),
            Padding(
              padding: EdgeInsets.symmetric(horizontal: 20.w),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Row(
                    children: [
                      Container(
                        height: 50.h,
                        width: 50.w,
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          border: Border.all(color: ColorTheme().whiteColor),
                          gradient: ColorTheme().linearColorWhite,
                        ),
                        child: Center(
                          child: Icon(
                            Icons.home_rounded,
                            color: ColorTheme().linearBorderColor2,
                            size: 30.sp,
                          ),
                        ),
                      ),
                      SizedBox(width: 15.w),
                      Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            "Desa ${UserController().toCamelCase(widget.desaName)}",
                            style: StyleTheme().styleWhite.copyWith(
                                fontSize: 20.sp, fontWeight: FontWeight.bold),
                          ),
                          Text(
                            UserController().convertDate(widget.date),
                            style: StyleTheme().styleWhite.copyWith(
                                fontWeight: FontWeight.bold, fontSize: 14.sp),
                          ),
                        ],
                      ),
                    ],
                  ),
                ],
              ),
            ),
            SizedBox(height: 10.h),
            Divider(thickness: 2.h),
            Padding(
              padding: EdgeInsets.symmetric(horizontal: 20.w),
              child: FutureBuilder(
                future: AdminPalawijaController()
                    .getDetailPalawijaByDesa(widget.date, widget.desaId),
                builder: ((context, snapshot) {
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
                          Text(
                            snapshot.error.toString(),
                            style: TextStyle(
                              fontSize: 14.sp,
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
                          padding: EdgeInsets.all(8.sp),
                          child: Text(
                            "Data Kosong",
                            style: StyleTheme().styleWhite.copyWith(
                                color: Colors.grey,
                                fontSize: 18.sp,
                                fontWeight: FontWeight.w500),
                          ),
                        ),
                      );
                    }

                    return Column(
                      children: [
                        Text(
                          "Ringkasan penyuluhan bulan ini",
                          style: StyleTheme().styleWhite.copyWith(
                              fontSize: 14.sp, fontWeight: FontWeight.bold),
                        ),
                        Divider(thickness: 2.h),
                        Column(
                          mainAxisSize: MainAxisSize.min,
                          children: totalValues.entries.map((entry) {
                            return Padding(
                              padding: EdgeInsets.symmetric(
                                  horizontal: 10.w, vertical: 5.h),
                              child: Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    "${UserController().toCamelCase(entry.key)}:",
                                    style: StyleTheme()
                                        .styleWhite
                                        .copyWith(fontSize: 14.sp),
                                  ),
                                  Text(
                                    '${entry.value} hektar',
                                    style: StyleTheme().styleWhite.copyWith(
                                        fontSize: 14.sp,
                                        fontWeight: FontWeight.w500),
                                  ),
                                ],
                              ),
                            );
                          }).toList(),
                        ),
                        Divider(thickness: 2.h),
                      ],
                    );
                  }
                }),
              ),
            ),
            Divider(thickness: 2.h),
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
                            isRincian: true,
                          ),
                        ),
                      );
                    },
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Text(
                          "Lihat Selengkapnya",
                          style: StyleTheme().styleWhite.copyWith(
                              fontSize: 16.sp, fontWeight: FontWeight.w500),
                        ),
                        Icon(
                          Icons.arrow_right_outlined,
                          color: ColorTheme().whiteColor,
                        ),
                      ],
                    ),
                  ),
            SizedBox(height: 10.h),
          ],
        ),
      ),
    );
  }
}
