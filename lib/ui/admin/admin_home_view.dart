import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:percent_indicator/percent_indicator.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/admin/admin_padi_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/detail_combined_model.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/models/prediksi_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/admin/admin_service.dart';
import 'package:sintren_mobile/ui/admin/admin_verify_view.dart';
import 'package:sintren_mobile/ui/admin/components/home_chart.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/login_view.dart';
import 'package:sintren_mobile/ui/components/dropdown_button_component.dart';
import 'package:sintren_mobile/ui/users/change_password_view.dart';
import 'package:sintren_mobile/ui/users/change_profile_view.dart';

class AdminHomeView extends StatefulWidget {
  const AdminHomeView({super.key});

  @override
  State<AdminHomeView> createState() => _AdminHomeViewState();
}

class _AdminHomeViewState extends State<AdminHomeView> {
  final userC = UserController();
  final adminC = AdminController();
  TextEditingController ulasan = TextEditingController();
  final formKey = GlobalKey<FormState>();
  String? kecamatan;
  double? presentasePenyuluhan;
  double? penyuluhanBulanIni;
  double? totalLuasLahanKecamatan;
  final statusNotifier = ValueNotifier<String>('Memulai sinkronisasi data...');
  late int selectedSampaiTahun;
  late int selectedDariTahun;
  
  Future<void> _initializedData() async {
    kecamatan = await UserLoginModel().getKecamatanName();
    penyuluhanBulanIni = await adminC.getTotalNilaiPenyuluhanBulanIni();
    totalLuasLahanKecamatan = await adminC.getTotalLuasLahanKecamatan();
    presentasePenyuluhan =
        (penyuluhanBulanIni! / totalLuasLahanKecamatan!) * 100;
  }

  @override
  void initState() {
    AdminPadiController().getAllPenyuluhanPadi();
    selectedSampaiTahun = DateTime.now().year - 3;
    selectedDariTahun = selectedSampaiTahun - 5;
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      resizeToAvoidBottomInset: false,
      backgroundColor: ColorTheme().bgColor,
      body: Stack(
        children: [
          Container(
            width: MediaQuery.of(context).size.width,
            height: 250.h,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.only(
                bottomLeft: const Radius.circular(10).r,
                bottomRight: const Radius.circular(10).r,
              ),
              gradient: ColorTheme().linearColor,
            ),
          ),
          LayoutBuilder(
            builder: (context, constraints) {
              double appBarHeight = 30.h + 10.h;
              double trendChartHeight = 280.h + 10.h;
              double progressHeight = 20.h + 50.h + 10.h + 10.h + 10.h;
              return FutureBuilder(
                future: _initializedData(),
                builder: (context, snapshot) {
                  if (snapshot.connectionState == ConnectionState.waiting) {
                    return const Center(child: CircularProgressIndicator());
                  } else if (snapshot.hasError) {
                    return const Center(child: Text('Error loading data'));
                  } else {
                    return Column(
                      children: [
                        SizedBox(height: 30.h),
                        _customAppBar(context),
                        _trendLineChart(context),
                        SizedBox(height: 15.h),
                        _progresPenyuluhan(context),
                        SizedBox(height: 10.h),
                        _listVerify(
                            isShow: constraints.maxHeight -
                                    (appBarHeight +
                                        trendChartHeight +
                                        progressHeight +
                                        10.h) >=
                                390.h),
                        SizedBox(height: 20.h),
                      ],
                    );
                  }
                },
              );
            },
          ),
        ],
      ),
    );
  }

  Expanded _listVerify({required bool isShow}) {
    return Expanded(
      child: FutureBuilder<List<DetailCombinedModel>>(
        future: AdminController().getDetailCombinedByStatus(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return const Center(
              child: Text('Error Data Tidak Ditemukan'),
            );
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return Align(
              alignment: Alignment.topCenter,
              child: Container(
                width: MediaQuery.of(context).size.width.w,
                height: 50.h,
                margin: EdgeInsets.symmetric(horizontal: 15.w, vertical: 5.h),
                padding: EdgeInsets.symmetric(horizontal: 15.w),
                decoration: BoxDecoration(
                  color: Colors.green,
                  borderRadius: BorderRadius.circular(10.r),
                ),
                child: Center(
                  child: Row(
                    children: [
                      Icon(
                        Icons.verified_outlined,
                        color: ColorTheme().whiteColor,
                      ),
                      SizedBox(width: 10.w),
                      Text(
                        'Semua Data Sudah Diverifikasi',
                        style: StyleTheme().styleWhite.copyWith(
                            fontSize: 14.sp, fontWeight: FontWeight.bold),
                      ),
                    ],
                  ),
                ),
              ),
            );
          } else {
            return Column(
              children: [
                GestureDetector(
                  onTap: () {
                    Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (_) => const AdminVerifyView()));
                  },
                  child: Container(
                    width: MediaQuery.of(context).size.width.w,
                    height: isShow ? 50.h : 100.h,
                    margin:
                        EdgeInsets.symmetric(horizontal: 15.w, vertical: 5.h),
                    padding: EdgeInsets.symmetric(horizontal: 15.w),
                    decoration: BoxDecoration(
                      color: ColorTheme().primaryColor,
                      borderRadius: BorderRadius.circular(10.r),
                    ),
                    child: Center(
                        child: Row(
                      children: [
                        Icon(
                          Icons.error_outline,
                          color: ColorTheme().whiteColor,
                        ),
                        SizedBox(width: 10.w),
                        Expanded(
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                '${snapshot.data!.length} Data Menunggu Diverifikasi',
                                style: StyleTheme().styleWhite.copyWith(
                                    fontSize: 14.sp,
                                    fontWeight: FontWeight.bold),
                              ),
                              Icon(
                                Icons.arrow_right_rounded,
                                size: 35.sp,
                                color: ColorTheme().whiteColor,
                              )
                            ],
                          ),
                        ),
                      ],
                    )),
                  ),
                ),
                if (isShow)
                  Expanded(
                    child: ListView.builder(
                      padding: EdgeInsets.zero,
                      shrinkWrap: true,
                      itemCount: snapshot.data!.length,
                      itemBuilder: (context, index) {
                        var item = snapshot.data![index];
                        DetailPadiModel? dataPadi;
                        DetailPalawijaModel? dataPalawija;
                        if (item.type == "padi") {
                          dataPadi = item.data;
                        }
                        if (item.type == "palawija") {
                          dataPalawija = item.data;
                        }
                        return Card(
                          surfaceTintColor: ColorTheme().whiteColor,
                          margin: EdgeInsets.symmetric(
                              vertical: 5.h, horizontal: 15.w),
                          elevation: 3,
                          color: ColorTheme().whiteColor,
                          child: ConstrainedBox(
                            constraints: const BoxConstraints(),
                            child: Padding(
                              padding: EdgeInsets.symmetric(
                                  horizontal: 20.w, vertical: 10.h),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Row(
                                    mainAxisAlignment:
                                        MainAxisAlignment.spaceBetween,
                                    children: [
                                      Text(
                                        item.type == "padi"
                                            ? dataPadi?.padiName ?? ""
                                            : dataPalawija?.palawijaName ?? "",
                                        style: StyleTheme().styleBlack.copyWith(
                                            fontWeight: FontWeight.w500,
                                            fontSize: 16.sp),
                                      ),
                                      Container(
                                        margin:
                                            EdgeInsets.symmetric(vertical: 3.h),
                                        padding: EdgeInsets.symmetric(
                                            horizontal: 8.w, vertical: 3.h),
                                        decoration: BoxDecoration(
                                          color: Colors.amber,
                                          borderRadius:
                                              BorderRadius.circular(5.r),
                                        ),
                                        child: Text(
                                          "Membutuhkan Verifikasi",
                                          style:
                                              StyleTheme().styleWhite.copyWith(
                                                    fontWeight: FontWeight.w500,
                                                  ),
                                        ),
                                      )
                                    ],
                                  ),
                                  Row(
                                    mainAxisAlignment:
                                        MainAxisAlignment.spaceBetween,
                                    children: [
                                      Text(
                                        UserController().toCamelCase(
                                            item.type == "padi"
                                                ? dataPadi?.jenisBantuan ?? ""
                                                : dataPalawija?.jenisBantuan ??
                                                    ""),
                                        style: StyleTheme().styleBlack,
                                      ),
                                      Text(
                                        item.type == "padi"
                                            ? UserController().normalizeDate(
                                                dataPadi?.date ?? "")
                                            : UserController().normalizeDate(
                                                dataPalawija?.date ?? ""),
                                        style: StyleTheme().styleBlack,
                                      ),
                                    ],
                                  ),
                                  Row(
                                    mainAxisAlignment:
                                        MainAxisAlignment.spaceBetween,
                                    children: [
                                      Text(
                                        'Lahan ${UserController().toCamelCase(item.type == "padi" ? dataPadi?.jenisLahan ?? "" : dataPalawija?.jenisLahan ?? "")}',
                                        style: StyleTheme().styleBlack,
                                      ),
                                      Text(
                                        item.type == "padi"
                                            ? UserController().toCamelCase(
                                                dataPadi?.pengairanName ?? "")
                                            : "",
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
                                        UserController().toCamelCase(
                                            item.type == "padi"
                                                ? dataPadi?.tipeData ?? ""
                                                : dataPalawija?.tipeData ?? ""),
                                        style: StyleTheme().styleBlack.copyWith(
                                            fontSize: 14.sp,
                                            fontWeight: FontWeight.w500),
                                      ),
                                      Text(
                                        "${item.type == "padi" ? dataPadi?.nilai : dataPalawija?.nilai} hektar",
                                        style: StyleTheme().styleBlack.copyWith(
                                              fontSize: 14.sp,
                                              fontWeight: FontWeight.bold,
                                            ),
                                      ),
                                    ],
                                  ),
                                  const Divider(),
                                  SizedBox(height: 5.h),
                                  Row(
                                    children: [
                                      Expanded(
                                        flex: 1,
                                        child: ElevatedButton.icon(
                                          onPressed: () async {
                                            bool? shouldVerify =
                                                await _showVerifyDialog(
                                                    context);
                                            if (shouldVerify == true) {
                                              await AdminController().verify(
                                                dataId: item.type == "padi"
                                                    ? dataPadi!.id.toString()
                                                    : dataPalawija!.id
                                                        .toString(),
                                                map: {
                                                  "status": "terima",
                                                  "catatan": "oke"
                                                },
                                                isPalawija: item.type == "padi"
                                                    ? false
                                                    : true,
                                              );
                                              setState(() {});
                                            }
                                          },
                                          icon: const Icon(
                                              Icons.verified_outlined,
                                              color: Colors.green),
                                          label: Text('Verifikasi',
                                              style: StyleTheme()
                                                  .stylePrimary
                                                  .copyWith(
                                                      fontSize: 14.sp,
                                                      color: Colors.green)),
                                          style: ElevatedButton.styleFrom(
                                            surfaceTintColor:
                                                ColorTheme().whiteColor,
                                            side: BorderSide(
                                                color: Colors.green,
                                                width: 2.w),
                                            shape: RoundedRectangleBorder(
                                              borderRadius:
                                                  BorderRadius.circular(30.r),
                                            ),
                                          ),
                                        ),
                                      ),
                                      SizedBox(width: 10.w),
                                      Expanded(
                                        flex: 1,
                                        child: ElevatedButton.icon(
                                          onPressed: () async {
                                            bool? shouldReject =
                                                await _showRejectedDialog(
                                                    context);
                                            if (shouldReject == true) {
                                              await AdminController().verify(
                                                dataId: item.type == "padi"
                                                    ? dataPadi!.id.toString()
                                                    : dataPalawija!.id
                                                        .toString(),
                                                map: {
                                                  "status": "tolak",
                                                  "catatan": ulasan.text
                                                },
                                                isPalawija: item.type == "padi"
                                                    ? false
                                                    : true,
                                              );
                                              setState(() {});
                                            }
                                          },
                                          icon: const Icon(
                                              Icons.dangerous_outlined,
                                              color: Colors.red),
                                          label: Text('Tolak',
                                              style: StyleTheme()
                                                  .stylePrimary
                                                  .copyWith(
                                                      fontSize: 14.sp,
                                                      color: Colors.red)),
                                          style: ElevatedButton.styleFrom(
                                            surfaceTintColor:
                                                ColorTheme().whiteColor,
                                            side: BorderSide(
                                                color: Colors.red, width: 2.w),
                                            shape: RoundedRectangleBorder(
                                              borderRadius:
                                                  BorderRadius.circular(30.r),
                                            ),
                                          ),
                                        ),
                                      ),
                                    ],
                                  ),
                                  SizedBox(height: 5.h),
                                ],
                              ),
                            ),
                          ),
                        );
                      },
                    ),
                  ),
              ],
            );
          }
        },
      ),
    );
  }

  Card _progresPenyuluhan(BuildContext context) {
    return Card(
      margin: EdgeInsets.symmetric(horizontal: 15.w),
      elevation: 5.r,
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
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      kecamatan == ""
                          ? "Kabupaten Indramayu"
                          : "Kecamatan ${UserController().toCamelCase(kecamatan ?? "")}",
                      style: StyleTheme().stylePrimary.copyWith(
                          fontSize: 20.sp, fontWeight: FontWeight.bold),
                    ),
                    Text(
                      userC.dateNow(),
                      style: StyleTheme().styleBlack.copyWith(
                          fontWeight: FontWeight.bold,
                          color: Colors.grey[700],
                          fontSize: 14.sp),
                    ),
                  ],
                ),
                Expanded(
                  child: Align(
                    alignment: Alignment.centerRight,
                    child: Container(
                      height: 50.h,
                      width: 50.w,
                      decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          gradient: ColorTheme().linearColor),
                      child: IconButton(
                        icon: Icon(Icons.refresh, size: 24.sp),
                        color: ColorTheme().whiteColor,
                        onPressed: () async {
                          EasyLoading.show(status: statusNotifier.value);

                          statusNotifier.addListener(() {
                            EasyLoading.show(status: statusNotifier.value);
                          });

                          try {
                            await adminC.synchronizeData(statusNotifier);
                          } finally {
                            EasyLoading.dismiss();
                          }
                          setState(() {});
                        },
                      ),
                    ),
                  ),
                ),
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
                  style:
                      StyleTheme().styleBlack.copyWith(color: Colors.black87),
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
            percent: (presentasePenyuluhan! / 100) > 1
                ? 1
                : (presentasePenyuluhan! / 100),
            center: Text(
              "${presentasePenyuluhan!.toStringAsFixed(1)}% (${penyuluhanBulanIni!.toStringAsFixed(1)}/${totalLuasLahanKecamatan!.toStringAsFixed(1)})",
              style: StyleTheme()
                  .styleWhite
                  .copyWith(fontWeight: FontWeight.w500, fontSize: 14.sp),
            ),
            barRadius: Radius.circular(10.r),
            linearGradient: ColorTheme().linearColor,
          ),
          SizedBox(height: 10.h),
        ],
      ),
    );
  }

  Card _trendLineChart(BuildContext context) {
    return Card(
      margin: EdgeInsets.symmetric(horizontal: 15.w),
      elevation: 3,
      color: ColorTheme().whiteColor,
      surfaceTintColor: ColorTheme().whiteColor,
      child: Container(
        height: 280.h,
        padding: EdgeInsets.symmetric(horizontal: 20.w, vertical: 10.h),
        child: FutureBuilder<PrediksiModel>(
          future: AdminService().getPrediksiPadi(),
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
                      size: 50.r,
                    ),
                    Text(
                      "Internal Server Error : ${snapshot.error}",
                      style: StyleTheme().styleBlack.copyWith(
                          fontSize: 18.sp,
                          fontWeight: FontWeight.w500,
                          color: Colors.grey),
                    ),
                  ],
                ),
              );
            } else {
              final prediksi = snapshot.data;
              List<DataItem> result =
                  selectedDariTahun == 0 || selectedSampaiTahun == 0
                      ? prediksi!.result
                      : prediksi!.result
                          .where((item) =>
                              item.label >= selectedDariTahun &&
                              item.label <= selectedSampaiTahun)
                          .toList();
              List<int> labels =
                  prediksi.result.map((item) => item.label).toList();
              return Column(
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Row(
                        children: [
                          Icon(
                            Icons.multiline_chart_rounded,
                            color: ColorTheme().primaryColor,
                            size: 30.r,
                          ),
                          SizedBox(width: 10.w),
                          Text(
                            "Trend Pertanian",
                            style: StyleTheme().stylePrimary.copyWith(
                                  fontSize: 20.sp,
                                  fontWeight: FontWeight.w500,
                                ),
                          ),
                        ],
                      ),
                      GestureDetector(
                        onTap: () {
                          _showDialogFilterTrend(context, labels);
                        },
                        child: Icon(
                          Icons.filter_list,
                          color: ColorTheme().primaryColor,
                        ),
                      ),
                    ],
                  ),
                  Container(
                    margin: EdgeInsets.only(top: 20.h),
                    width: MediaQuery.of(context).size.width,
                    height: 207.h,
                    child: Column(
                      children: [
                        SizedBox(
                          height: 180.h,
                          child: LineChart(
                            HomeChart(data: result).mainData(),
                          ),
                        ),
                        SizedBox(height: 3.h),
                        Row(
                          mainAxisAlignment: MainAxisAlignment.center,
                          children: [
                            Expanded(
                                child: GestureDetector(
                              onTap: () {
                                _showMapeDialog(context);
                              },
                              child: Row(
                                children: [
                                  Icon(Icons.error_outline, size: 20.r),
                                  SizedBox(width: 5.w),
                                  Text(
                                    "Mape: ${prediksi.mape}",
                                    style: TextStyle(fontSize: 12.sp),
                                  ),
                                ],
                              ),
                            )),
                            Row(
                              children: [
                                Container(
                                  width: 10.w,
                                  height: 10.h,
                                  color: ColorTheme().secondaryColor,
                                ),
                                SizedBox(width: 5.w),
                                Text(
                                  "Data Aktual",
                                  style: TextStyle(
                                    color: ColorTheme().primaryColor,
                                    fontSize: 12.sp,
                                  ),
                                ),
                              ],
                            ),
                            SizedBox(width: 10.w),
                            Row(
                              children: [
                                Container(
                                    width: 10.w,
                                    height: 10.h,
                                    color: Colors.amber[900]),
                                SizedBox(width: 5.w),
                                Text(
                                  "Data Prediksi",
                                  style: TextStyle(
                                      color: ColorTheme().primaryColor,
                                      fontSize: 12.sp),
                                ),
                              ],
                            )
                          ],
                        ),
                      ],
                    ),
                  ),
                ],
              );
            }
          },
        ),
      ),
    );
  }

  void _showDialogFilterTrend(BuildContext context, List<int> tahun) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          surfaceTintColor: ColorTheme().whiteColor,
          title: const Column(
            children: [
              Text('Filter Trend'),
              Divider(),
            ],
          ),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              DropdownButtonComponent(
                icon: Icons.dataset,
                label: 'Dari Tahun',
                selectedItem: selectedDariTahun,
                items: tahun.map(
                  (value) {
                    return DropdownMenuItem<int>(
                      value: value,
                      child: Text(value.toString()),
                    );
                  },
                ).toList(),
                hint: 'Pilih Dari Tahun',
                validator: (value) =>
                    value == null ? 'Pilih tahun terlebih dahulu' : null,
                onChanged: (newValue) {
                  setState(() {
                    selectedDariTahun = newValue!;
                  });
                },
                onSaved: (newValue) {
                  setState(() {
                    selectedDariTahun = newValue!;
                  });
                },
              ),
              SizedBox(height: 10.h), // Using screen_util for height
              DropdownButtonComponent(
                icon: Icons.dataset,
                label: 'Sampai Tahun',
                selectedItem: selectedSampaiTahun,
                items: tahun.map(
                  (value) {
                    return DropdownMenuItem<int>(
                      value: value,
                      child:
                          Text(UserController().toCamelCase(value.toString())),
                    );
                  },
                ).toList(),
                hint: 'Pilih Sampai Tahun',
                validator: (value) {
                  if (value == null) {
                    return 'Pilih tahun terlebih dahulu';
                  }
                  if (selectedDariTahun != 0 && selectedSampaiTahun != 0) {
                    final int dari = selectedDariTahun;
                    final int sampai = selectedSampaiTahun;
                    if (dari > sampai) {
                      return 'Dari Tahun tidak boleh lebih besar daripada Sampai Tahun';
                    }
                  }
                  return null;
                },
                onChanged: (newValue) {
                  if (selectedDariTahun != 0 && selectedSampaiTahun != 0) {
                    final int dari = selectedDariTahun;
                    if (dari < newValue!) {
                      setState(() {
                        selectedSampaiTahun = newValue;
                      });
                    }
                  }
                },
                onSaved: (newValue) {
                  if (selectedDariTahun != 0 && selectedSampaiTahun != 0) {
                    final int dari = selectedDariTahun;
                    if (dari < newValue!) {
                      setState(() {
                        selectedSampaiTahun = newValue;
                      });
                    }
                  }
                },
              ),
            ],
          ),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.pop(context);
              },
              child: Text(
                "Tutup",
                style: StyleTheme().stylePrimary.copyWith(
                    color: Colors.red,
                    fontSize: 16.sp), // Using screen_util for fontSize
              ),
            ),
            TextButton(
              onPressed: () {
                setState(() {
                  selectedDariTahun = DateTime.now().year - 5;
                  selectedSampaiTahun = DateTime.now().year;
                });
              },
              child: Text(
                "Reset",
                style: StyleTheme().stylePrimary.copyWith(
                    fontSize: 16.sp), // Using screen_util for fontSize
              ),
            ),
          ],
        );
      },
    );
  }

  Padding _customAppBar(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 20.w, vertical: 10.h),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            kecamatan == ""
                ? "Dinas Pertanian Indramayu"
                : "Kecamatan ${UserController().toCamelCase(kecamatan ?? "")}",
            style: StyleTheme().styleWhite.copyWith(
                  fontSize: 20.sp,
                  fontWeight: FontWeight.w500,
                ),
          ),
          PopupMenuButton<String>(
            surfaceTintColor: ColorTheme().whiteColor,
            icon: Icon(
              Icons.account_circle,
              size: 30.r,
              color: ColorTheme().whiteColor,
            ),
            onSelected: (String value) {
              if (value == "1") {
                Navigator.of(context).push(MaterialPageRoute(
                    builder: (_) => const ChangeProfileView()));
              } else if (value == "2") {
                Navigator.of(context).push(MaterialPageRoute(
                    builder: (_) => const ChangePasswordView()));
              } else {
                userC.logout().then((value) {
                  Navigator.pushAndRemoveUntil(
                      context,
                      MaterialPageRoute(builder: (_) => const LoginView()),
                      (route) => false);
                  EasyLoading.showToast("Berhasil Logout");
                });
              }
            },
            itemBuilder: (BuildContext context) => <PopupMenuEntry<String>>[
              PopupMenuItem<String>(
                value: '1',
                child: Row(
                  children: [
                    Icon(Icons.person, size: 24.r), // Example size adjustment
                    SizedBox(width: 5.w),
                    Text('Edit Profil',
                        style: TextStyle(
                            fontSize: 16.sp)), // Example text size adjustment
                  ],
                ),
              ),
              PopupMenuItem<String>(
                value: '2',
                child: Row(
                  children: [
                    Icon(Icons.lock, size: 24.r), // Example size adjustment
                    SizedBox(width: 5.w),
                    Text('Ubah Password',
                        style: TextStyle(
                            fontSize: 16.sp)), // Example text size adjustment
                  ],
                ),
              ),
              PopupMenuItem<String>(
                value: '3',
                child: Row(
                  children: [
                    Icon(
                      Icons.logout,
                      size: 24.r, // Example size adjustment
                      color: Colors.red,
                    ),
                    SizedBox(width: 5.w),
                    Text(
                      'Logout',
                      style: TextStyle(
                        color: Colors.red,
                        fontSize: 16.sp, // Example text size adjustment
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  Future<bool> _showVerifyDialog(BuildContext context) async {
    return await showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Column(
            children: [
              Text('Konfirmasi Aksi'),
              Divider(),
            ],
          ),
          content: Text(
            "Apakah anda yakin ingin memverifikasi data ini?",
            style: StyleTheme().styleBlack.copyWith(fontSize: 14.sp),
          ),
          actions: <Widget>[
            TextButton(
              onPressed: () async {
                Navigator.of(context).pop(false);
              },
              child: Text(
                "Tidak",
                style: StyleTheme()
                    .stylePrimary
                    .copyWith(fontSize: 14.sp, color: Colors.red),
              ),
            ),
            TextButton(
              onPressed: () async {
                Navigator.of(context).pop(true);
              },
              child: Text(
                "Ya",
                style: StyleTheme().stylePrimary.copyWith(fontSize: 14.sp),
              ),
            ),
          ],
        );
      },
    );
  }

  Future<bool> _showRejectedDialog(BuildContext context) async {
    return await showDialog(
      context: context,
      builder: (BuildContext context) {
        return Form(
          key: formKey,
          child: AlertDialog(
            title: const Column(
              children: [
                Text('Konfirmasi Aksi'),
                Divider(),
              ],
            ),
            content: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  "Apakah anda yakin ingin menolak data ini?",
                  style: StyleTheme().styleBlack.copyWith(fontSize: 14.sp),
                ),
                SizedBox(height: 10.h),
                const Text("Berikan Ulasan:"),
                TextFormField(
                  controller: ulasan,
                  decoration: InputDecoration(
                    isDense: true,
                    filled: true,
                    fillColor: ColorTheme().whiteColor,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(10.r),
                    ),
                  ),
                  maxLines: 5,
                  validator: (value) {
                    return value == null || value.isEmpty
                        ? "Ulasan tidak boleh kosong"
                        : null;
                  },
                )
              ],
            ),
            actions: <Widget>[
              TextButton(
                onPressed: () async {
                  setState(() {
                    ulasan.clear();
                  });

                  Navigator.of(context).pop(false);
                },
                child: Text(
                  "Tidak",
                  style: StyleTheme()
                      .stylePrimary
                      .copyWith(fontSize: 14.sp, color: Colors.red),
                ),
              ),
              TextButton(
                onPressed: () async {
                  if (formKey.currentState!.validate()) {
                    Navigator.of(context).pop(true);
                  }
                },
                child: Text(
                  "Ya",
                  style: StyleTheme().stylePrimary.copyWith(fontSize: 14.sp),
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  _showMapeDialog(BuildContext context) {
    final List<Map<String, String>> mapeData = [
      {'mape': '< 10%', 'akurasi': 'Sangat Baik'},
      {'mape': '10-20%', 'akurasi': 'Baik'},
      {'mape': '20-50%', 'akurasi': 'Layak/Memadai'},
      {'mape': '> 50%', 'akurasi': 'Sangat Buruk'},
    ];
    showDialog(
      context: context,
      builder: ((context) {
        return AlertDialog(
          content: DataTable(
            columns: const <DataColumn>[
              DataColumn(
                label: Text(
                  'MAPE (%)',
                  style: TextStyle(fontStyle: FontStyle.italic),
                ),
              ),
              DataColumn(
                label: Text(
                  'Akurasi',
                  style: TextStyle(fontStyle: FontStyle.italic),
                ),
              ),
            ],
            rows: mapeData.map((data) {
              return DataRow(
                cells: <DataCell>[
                  DataCell(Text(data['mape']!)),
                  DataCell(Text(data['akurasi']!)),
                ],
              );
            }).toList(),
          ),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.pop(context);
              },
              child: Text(
                "Tutup",
                style: StyleTheme()
                    .stylePrimary
                    .copyWith(color: Colors.red, fontSize: 16.sp),
              ),
            ),
          ],
        );
      }),
    );
  }
}
