import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:percent_indicator/linear_percent_indicator.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:sintren_mobile/services/admin/admin_padi_service.dart';
import 'package:sintren_mobile/services/admin/admin_palawija_service.dart';
import 'package:sintren_mobile/services/admin/admin_service.dart';
import 'package:sintren_mobile/ui/uptd/detail_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/components/dropdown_button_component.dart';

class UptdPenyuluhanView extends StatefulWidget {
  const UptdPenyuluhanView({super.key});

  @override
  State<UptdPenyuluhanView> createState() => _UptdPenyuluhanViewState();
}

class _UptdPenyuluhanViewState extends State<UptdPenyuluhanView> {
  final userC = UserController();
  final adminC = AdminController();
  late List<DesaModel> desaList;
  late DesaModel? selectedDesaValue;

  Future<void> _synchronizeData() async {
    await AdminService().getDataPenyuluhanDesa();
    await AdminPadiService().getDetailPadiByKecamatan();
    await AdminPalawijaService().getDetailPalawijaByKecamatan();
    setState(() {});
  }

  @override
  void initState() {
    _initializeData();
    super.initState();
  }

  void refresh() {
    setState(() {});
  }

  Future<void> _initializeData() async {
    desaList = await adminC.getDesa();
    selectedDesaValue = null;
  }

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
          'Histori Penyuluhan Bulan Ini',
          style: StyleTheme().styleWhite.copyWith(
                fontSize: 20.sp,
                fontWeight: FontWeight.w500,
              ),
        ),
        actions: [
          IconButton(
            icon: Icon(
              Icons.filter_list,
              color: ColorTheme().whiteColor,
            ),
            onPressed: () {
              _filter(context);
            },
          ),
          IconButton(
              onPressed: () async {
                EasyLoading.show(status: "Sinkronisasi Data");
                await _synchronizeData();
                EasyLoading.dismiss();
              },
              icon: Icon(
                Icons.refresh_rounded,
                color: ColorTheme().whiteColor,
              ))
        ],
        backgroundColor: ColorTheme().primaryColor,
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
                itemCount: (selectedDesaValue == null)
                    ? historiList.length
                    : historiList
                        .where((desa) => desa.id == selectedDesaValue!.id)
                        .length,
                itemBuilder: (BuildContext context, int index) {
                  var displayList = (selectedDesaValue == null)
                      ? historiList
                      : historiList
                          .where((desa) => desa.id == selectedDesaValue!.id)
                          .toList();
                  HistoriPenyuluhanModel desa = displayList[index];

                  return GestureDetector(
                    onTap: () async {
                      await Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => DetailPenyuluhanView(
                            index: 0,
                            date: desa.date,
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
                                      fontWeight: FontWeight.bold),
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
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceEvenly,
                                  children: [
                                    Text(
                                      "Desa ${UserController().toCamelCase(desa.name)}",
                                      style: StyleTheme().stylePrimary.copyWith(
                                          fontSize: 20.sp,
                                          fontWeight: FontWeight.bold),
                                    ),
                                    Text(
                                      userC.convertDate(desa.date),
                                      style: StyleTheme().styleBlack.copyWith(
                                          fontWeight: FontWeight.bold,
                                          color: Colors.grey[700],
                                          fontSize: 14.sp),
                                    ),
                                  ],
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
                                padding: EdgeInsets.symmetric(horizontal: 8.w),
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
                            percent: (desa.nilai / getLuasDesa(desa.id)) > 1
                                ? 1
                                : desa.nilai / getLuasDesa(desa.id),
                            center: Text(
                              "${((desa.nilai / getLuasDesa(desa.id)) * 100).toStringAsFixed(1)}% (${desa.nilai.toStringAsFixed(1)}/${getLuasDesa(desa.id).toStringAsFixed(1)})",
                              style: StyleTheme().styleWhite.copyWith(
                                  fontWeight: FontWeight.w500, fontSize: 14.sp),
                            ),
                            barRadius: Radius.circular(10.r),
                            linearGradient: ColorTheme().progressColor,
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

  void _filter(BuildContext context) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          surfaceTintColor: ColorTheme().whiteColor,
          title: const Column(
            children: [
              Text('Filter Desa'),
              Divider(),
            ],
          ),
          content: DropdownButtonComponent(
            icon: Icons.villa,
            label: 'Desa',
            selectedItem: selectedDesaValue,
            items: desaList.map((desa) {
              return DropdownMenuItem<DesaModel>(
                value: desa,
                child: Text(UserController().toCamelCase(desa.name)),
              );
            }).toList(),
            hint: 'Pilih Desa',
            validator: (value) =>
                value == null ? 'Pilih desa terlebih dahulu' : null,
            onChanged: (newValue) {
              setState(() {
                selectedDesaValue = newValue;
              });
            },
            onSaved: (newValue) {
              setState(() {
                selectedDesaValue = newValue!;
              });
            },
          ),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(false);
              },
              child: Text(
                "Tutup",
                style: StyleTheme()
                    .stylePrimary
                    .copyWith(color: Colors.red, fontSize: 16.sp),
              ),
            ),
            TextButton(
              onPressed: () {
                setState(() {
                  selectedDesaValue = null;
                });
              },
              child: Text(
                "Reset",
                style: StyleTheme().stylePrimary.copyWith(fontSize: 16.sp),
              ),
            ),
          ],
        );
      },
    );
  }
}
