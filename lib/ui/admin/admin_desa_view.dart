import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:percent_indicator/percent_indicator.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:sintren_mobile/ui/admin/admin_detail_desa_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class AdminDesaView extends StatefulWidget {
  const AdminDesaView({super.key});

  @override
  State<AdminDesaView> createState() => _AdminDesaViewState();
}

class _AdminDesaViewState extends State<AdminDesaView> {
  TextEditingController search = TextEditingController();
  bool isSearchOpen = false;

  @override
  Widget build(BuildContext context) {
    final adminC = AdminController();

    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      appBar: AppBar(
        elevation: 0,
        flexibleSpace: Container(
          decoration: BoxDecoration(gradient: ColorTheme().linearColor),
        ),
        foregroundColor: ColorTheme().whiteColor,
        title: isSearchOpen
            ? PreferredSize(
                preferredSize: Size.fromHeight(60.h),
                child: TextField(
                  controller: search,
                  decoration: InputDecoration(
                    prefixIcon: const Icon(Icons.search),
                    suffixIcon: IconButton(
                      icon: const Icon(
                        Icons.clear,
                        color: Colors.red,
                      ),
                      onPressed: () {
                        setState(() {
                          isSearchOpen = false;
                        });
                      },
                    ),
                    hintText: 'Cari desa...',
                    filled: true,
                    fillColor: ColorTheme().whiteColor,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(30.r),
                    ),
                    contentPadding: EdgeInsets.symmetric(horizontal: 16.w),
                  ),
                  onChanged: (value) {
                    setState(() {
                      search.text = value;
                    });
                  },
                ),
              )
            : Text(
                'List Desa',
                style: StyleTheme().styleWhite.copyWith(
                      fontSize: 20.sp,
                      fontWeight: FontWeight.w500,
                    ),
              ),
        actions: [
          isSearchOpen
              ? const SizedBox.shrink()
              : IconButton(
                  icon: Icon(
                    Icons.search,
                    color: ColorTheme().whiteColor,
                  ),
                  onPressed: () {
                    setState(
                      () {
                        isSearchOpen = true;
                      },
                    );
                  },
                ),
        ],
        backgroundColor: ColorTheme().primaryColor,
      ),
      floatingActionButton: FloatingActionButton(
        shape: const CircleBorder(),
        heroTag: 'sinkronisasi',
        onPressed: () => setState(() {}),
        backgroundColor: ColorTheme().primaryColor,
        foregroundColor: ColorTheme().whiteColor,
        child: const Icon(
          Icons.refresh_rounded,
        ),
      ),
      body: FutureBuilder<List<dynamic>>(
        future: Future.wait([
          adminC.getHistoriPenyuluhan(isMonthNow: true),
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
                        color: Colors.grey),
                  ),
                ],
              ),
            );
          } else {
            final historiList =
                snapshot.data?[0] as List<HistoriPenyuluhanModel>;
            final desaList = snapshot.data?[1] as List<LuasWilayahModel>;

            double getNilaiByDesaId(String desaId) {
              try {
                return historiList
                    .firstWhere((element) => element.desaId == desaId)
                    .nilai;
              } catch (e) {
                return 0;
              }
            }

            if (desaList.isEmpty) {
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
                      "Data Kosong",
                      style: StyleTheme().styleBlack.copyWith(
                          fontSize: 18.sp,
                          fontWeight: FontWeight.w500,
                          color: Colors.grey),
                    ),
                  ],
                ),
              );
            }
            return Padding(
              padding: EdgeInsets.symmetric(vertical: 10.h),
              child: ListView.builder(
                padding: EdgeInsets.zero,
                itemCount: (search.text.isEmpty)
                    ? desaList.length
                    : desaList
                        .where((desa) => desa.name
                            .toLowerCase()
                            .contains(search.text.toLowerCase()))
                        .length,
                itemBuilder: (BuildContext context, int index) {
                  var displayList = (search.text.isEmpty)
                      ? desaList
                      : desaList
                          .where((desa) => desa.name
                              .toLowerCase()
                              .contains(search.text.toLowerCase()))
                          .toList();
                  LuasWilayahModel desa = displayList[index];
                  return GestureDetector(
                    onTap: () async {
                      await Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => AdminDetailDesaView(
                            desaId: desa.id,
                            desaName: desa.name,
                          ),
                        ),
                      );
                      setState(() {});
                    },
                    child: Card(
                      margin: EdgeInsets.symmetric(
                          horizontal: 20.w, vertical: 10.h),
                      elevation: 3,
                      surfaceTintColor: ColorTheme().whiteColor,
                      color: ColorTheme().whiteColor,
                      child: Column(
                        children: [
                          SizedBox(height: 20.h),
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
                                Text(
                                  "Desa ${UserController().toCamelCase(desa.name)}",
                                  style: StyleTheme().stylePrimary.copyWith(
                                      fontSize: 20.sp,
                                      fontWeight: FontWeight.bold),
                                )
                              ],
                            ),
                          ),
                          SizedBox(height: 10.h),
                          Stack(
                            children: [
                              Divider(thickness: 2.h, color: Colors.grey),
                              Container(
                                color: ColorTheme().whiteColor,
                                margin: EdgeInsets.only(left: 20.w),
                                padding:
                                    EdgeInsets.symmetric(horizontal: 8.0.w),
                                child: Text(
                                  "Progres bulan ini",
                                  style: StyleTheme()
                                      .styleBlack
                                      .copyWith(color: Colors.black87),
                                ),
                              ),
                            ],
                          ),
                          SizedBox(height: 10.h),
                          LinearPercentIndicator(
                            width: MediaQuery.of(context).size.width - 40.w,
                            animation: true,
                            lineHeight: 30.h,
                            animationDuration: 2000,
                            percent: (getNilaiByDesaId(desa.id) /
                                        desa.totalLuasLahan) >
                                    1
                                ? 1
                                : getNilaiByDesaId(desa.id) /
                                    desa.totalLuasLahan,
                            center: Text(
                              "${((getNilaiByDesaId(desa.id) / desa.totalLuasLahan) * 100).toStringAsFixed(1)}% (${getNilaiByDesaId(desa.id).toStringAsFixed(1)}/${desa.totalLuasLahan.toStringAsFixed(1)})",
                              style: StyleTheme().styleWhite.copyWith(
                                  fontWeight: FontWeight.w500, fontSize: 14.sp),
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
