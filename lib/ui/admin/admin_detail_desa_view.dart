import 'package:flutter/material.dart';
import 'package:percent_indicator/linear_percent_indicator.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:sintren_mobile/ui/admin/detail_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class AdminDetailDesaView extends StatefulWidget {
  const AdminDetailDesaView({super.key, this.desaId, this.desaName});

  final String? desaId;
  final String? desaName;

  @override
  State<AdminDetailDesaView> createState() => _AdminDetailDesaViewState();
}

class _AdminDetailDesaViewState extends State<AdminDetailDesaView> {
  final adminC = AdminController();
  TextEditingController search = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      appBar: AppBar(
        foregroundColor: ColorTheme().whiteColor,
        title: Text(
          'Detail Desa ${UserController().toCamelCase(widget.desaName!)}',
          style: StyleTheme().styleWhite.copyWith(
                fontSize: 20.sp,
                fontWeight: FontWeight.w500,
              ),
        ),
        backgroundColor: ColorTheme().primaryColor,
      ),
      floatingActionButton: FloatingActionButton(
        shape: const CircleBorder(),
        heroTag: 'sinkron_detail',
        onPressed: () {
          setState(() {});
        },
        backgroundColor: ColorTheme().primaryColor,
        foregroundColor: ColorTheme().whiteColor,
        child: const Icon(
          Icons.refresh_rounded,
        ),
      ),
      body: FutureBuilder<List<dynamic>>(
        future: Future.wait([
          adminC.getHistoriPenyuluhan(isMonthNow: false),
          adminC.getLuasLahanDesa(),
        ]),
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
                          color: Colors.grey,
                        ),
                  ),
                ],
              ),
            );
          } else {
            final historiList =
                (snapshot.data?[0] as List<HistoriPenyuluhanModel>)
                    .where((histori) {
              return histori.desaId == widget.desaId!;
            });
            final luasDesaList = snapshot.data?[1] as List<LuasWilayahModel>;

            double getLuasDesa(String id) {
              for (LuasWilayahModel wilayah in luasDesaList) {
                if (wilayah.id == id) {
                  return wilayah.totalLuasLahan;
                }
              }
              return 0;
            }

            if (historiList.isEmpty) {
              return Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(
                      Icons.assignment,
                      color: Colors.grey,
                      size: 50.sp,
                    ),
                    Text(
                      "Penyuluhan Belum Dilakukan",
                      style: StyleTheme().styleBlack.copyWith(
                            fontSize: 18.sp,
                            fontWeight: FontWeight.w500,
                            color: Colors.grey,
                          ),
                    ),
                  ],
                ),
              );
            }
            return Padding(
              padding: EdgeInsets.symmetric(vertical: 10.h),
              child: ListView.builder(
                padding: EdgeInsets.zero,
                itemCount: historiList.length,
                itemBuilder: (BuildContext context, int index) {
                  var displayList = historiList.toList();
                  HistoriPenyuluhanModel desa = displayList[index];
                  return GestureDetector(
                    onTap: () async {
                      await Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => DetailPenyuluhanView(
                            index: 0,
                            date: desa.date,
                            desaId: desa.desaId,
                            desaName: desa.desaName,
                          ),
                        ),
                      );
                      setState(() {});
                    },
                    child: Card(
                      margin: EdgeInsets.symmetric(
                          horizontal: 15.w, vertical: 10.h),
                      elevation: 3,
                      color: ColorTheme().whiteColor,
                      surfaceTintColor: ColorTheme().whiteColor,
                      child: Column(
                        children: [
                          if (desa.totalTunggu > 0)
                            Align(
                              alignment: Alignment.topRight,
                              child: Container(
                                height: 30.h,
                                width: 250.w,
                                padding: EdgeInsets.symmetric(
                                    vertical: 5.h, horizontal: 20.w),
                                decoration: BoxDecoration(
                                  color: Colors.red,
                                  borderRadius: BorderRadius.only(
                                    topRight: Radius.circular(10.r),
                                    bottomLeft: Radius.circular(10.r),
                                  ),
                                ),
                                child: Text(
                                  "${desa.totalTunggu} Data Belum Diverifikasi",
                                  style: StyleTheme().styleWhite.copyWith(
                                        fontSize: 14.sp,
                                        fontWeight: FontWeight.bold,
                                      ),
                                ),
                              ),
                            ),
                          SizedBox(height: 10.h),
                          Padding(
                            padding: EdgeInsets.symmetric(horizontal: 20.w),
                            child: Row(
                              children: [
                                Container(
                                  height: 50.h,
                                  width: 50.w,
                                  decoration: BoxDecoration(
                                    shape: BoxShape.circle,
                                    gradient: ColorTheme().linearColor,
                                  ),
                                  child: Center(
                                    child: Icon(
                                      Icons.home_rounded,
                                      color: ColorTheme().whiteColor,
                                      size: 30.sp,
                                    ),
                                  ),
                                ),
                                SizedBox(width: 15.w),
                                Column(
                                  crossAxisAlignment: CrossAxisAlignment.start,
                                  children: [
                                    Text(
                                      "Desa ${UserController().toCamelCase(desa.desaName)}",
                                      style: StyleTheme().stylePrimary.copyWith(
                                            fontSize: 20.sp,
                                            fontWeight: FontWeight.bold,
                                          ),
                                    ),
                                    Text(
                                      UserController().convertDate(desa.date),
                                      style: StyleTheme().styleBlack.copyWith(
                                            fontWeight: FontWeight.bold,
                                            color: Colors.grey[700],
                                            fontSize: 14.sp,
                                          ),
                                    ),
                                  ],
                                )
                              ],
                            ),
                          ),
                          SizedBox(height: 10.h),
                          Stack(
                            children: [
                              Divider(thickness: 2.sp, color: Colors.grey),
                              Container(
                                color: ColorTheme().whiteColor,
                                margin: EdgeInsets.only(left: 20.w),
                                padding:
                                    const EdgeInsets.symmetric(horizontal: 8.0),
                                child: Text(
                                  "Progres bulan ini",
                                  style: StyleTheme().styleBlack.copyWith(
                                        color: Colors.black87,
                                      ),
                                ),
                              ),
                            ],
                          ),
                          SizedBox(height: 10.h),
                          LinearPercentIndicator(
                            width: MediaQuery.of(context).size.width - 30.w,
                            animation: true,
                            lineHeight: 30.h,
                            animationDuration: 2000,
                            percent: (desa.nilai / getLuasDesa(desa.desaId)) > 1
                                ? 1
                                : desa.nilai / getLuasDesa(desa.desaId),
                            center: Text(
                              "${((desa.nilai / getLuasDesa(desa.desaId)) * 100).toStringAsFixed(1)}% (${desa.nilai}/${getLuasDesa(desa.desaId)})",
                              style: StyleTheme().styleWhite.copyWith(
                                    fontWeight: FontWeight.w500,
                                    fontSize: 14.sp,
                                  ),
                            ),
                            barRadius: Radius.circular(10.r),
                            linearGradient: ColorTheme().linearColor,
                          ),
                          SizedBox(height: 10.h),
                        ],
                      ),
                    ),
                  );
                },
              ),
            );
          }
        },
      ),
    );
  }
}
