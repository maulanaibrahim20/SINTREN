import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:percent_indicator/percent_indicator.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/admin/admin_padi_controller.dart';
import 'package:sintren_mobile/controllers/admin/admin_palawija_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/detail_combined_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/ui/uptd/uptd_verify_view.dart';
import 'package:sintren_mobile/ui/components/palawija_chart.dart';
import 'package:sintren_mobile/ui/components/progress_chart.dart';
import 'package:sintren_mobile/ui/components/trend_chart.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/login_view.dart';
import 'package:sintren_mobile/ui/components/dropdown_button_component.dart';
import 'package:sintren_mobile/ui/users/change_password_view.dart';
import 'package:sintren_mobile/ui/users/change_profile_view.dart';

class UptdHomeView extends StatefulWidget {
  const UptdHomeView({super.key});

  @override
  State<UptdHomeView> createState() => _UptdHomeViewState();
}

class _UptdHomeViewState extends State<UptdHomeView>
    with TickerProviderStateMixin {
  final userC = UserController();
  final adminC = AdminController();
  TextEditingController ulasan = TextEditingController();
  final formKey = GlobalKey<FormState>();
  String? kecamatan;
  double? presentasePenyuluhan;
  double? penyuluhanBulanIni;
  double? totalLuasLahan;
  final statusNotifier = ValueNotifier<String>('Memulai sinkronisasi data...');
  late int selectedTahun;
  String? kecamatanId;
  int currentYear = DateTime.now().year;
  late List<int> years;
  late TabController _tabController;
  late TabController _tabProgressController;
  final List<String> _tabs = ['Padi', 'Palawija'];

  Future<void> _initializedData() async {
    years = List.generate(currentYear - 2009, (index) => 2010 + index);
    kecamatan = await UserLoginModel().getKecamatanName();
    penyuluhanBulanIni = await adminC.getTotalNilaiPenyuluhanBulanIni();
    totalLuasLahan = await adminC.getTotalLuasLahan();
    presentasePenyuluhan =
        (penyuluhanBulanIni! / totalLuasLahan!) * 100;
    kecamatanId = await UserLoginModel().getKecamatanId();
  }

  Future<dynamic> _fetchChartData(String type) async {
    if (type == 'Padi') {
      return await AdminPadiController().getDataGrafikPenyuluhanPadi(
          selectedTahun.toString(),
          kecamatanId: kecamatanId);
    } else if (type == 'Palawija') {
      return await AdminPalawijaController().getDataGrafikPenyuluhanPalawija(
          selectedTahun.toString(),
          kecamatanId: kecamatanId);
    }
  }

  @override
  void dispose() {
    _tabController.dispose();
    _tabProgressController.dispose();
    super.dispose();
  }

  @override
  void initState() {
    _tabController = TabController(length: _tabs.length, vsync: this);
    _tabProgressController = TabController(length: _tabs.length, vsync: this);
    selectedTahun = DateTime.now().year;
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      resizeToAvoidBottomInset: false,
      backgroundColor: ColorTheme().bgColor,
      floatingActionButton: FloatingActionButton(
        shape: const CircleBorder(),
        heroTag: 'sinkronisasi_home',
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
        backgroundColor: ColorTheme().primaryColor,
        foregroundColor: ColorTheme().whiteColor,
        child: const Icon(
          Icons.refresh_rounded,
        ),
      ),
      body: Stack(
        children: [
          Container(
            width: MediaQuery.of(context).size.width,
            height: 300.h,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.only(
                bottomLeft: const Radius.circular(10).r,
                bottomRight: const Radius.circular(10).r,
              ),
              gradient: ColorTheme().linearColor,
            ),
          ),
          FutureBuilder(
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
                    Expanded(
                      child: ListView(
                        padding: EdgeInsets.zero,
                        children: [
                          _trendLineChart(context),
                          SizedBox(height: 10.h),
                          _notifVerify(),
                          SizedBox(height: 10.h),
                          _progresPenyuluhan(context),
                          SizedBox(height: 10.h),
                          PalawijaChart().chart(kecamatanId: kecamatanId),
                          SizedBox(height: 80.h),
                        ],
                      ),
                    ),
                  ],
                );
              }
            },
          ),
        ],
      ),
    );
  }

  FutureBuilder _notifVerify() {
    return FutureBuilder<List<DetailCombinedModel>>(
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
          return GestureDetector(
            onTap: () {
              Navigator.push(context,
                  MaterialPageRoute(builder: (_) => const UptdVerifyView()));
            },
            child: Container(
              width: MediaQuery.of(context).size.width.w,
              height: 50.h,
              margin: EdgeInsets.symmetric(horizontal: 15.w, vertical: 5.h),
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
                              fontSize: 14.sp, fontWeight: FontWeight.bold),
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
          );
        }
      },
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
              "${presentasePenyuluhan!.toStringAsFixed(1)}% (${penyuluhanBulanIni!.toStringAsFixed(1)}/${totalLuasLahan!.toStringAsFixed(1)})",
              style: StyleTheme()
                  .styleWhite
                  .copyWith(fontWeight: FontWeight.w500, fontSize: 14.sp),
            ),
            barRadius: Radius.circular(10.r),
            linearGradient: ColorTheme().progressColor,
          ),
          SizedBox(height: 10.h),
          if (presentasePenyuluhan! > 0) ...[
            TabBar(
              controller: _tabProgressController,
              tabs: _tabs.map((String tab) {
                return Tab(text: tab);
              }).toList(),
              labelColor: ColorTheme().primaryColor,
              unselectedLabelColor: Colors.grey,
              indicatorColor: ColorTheme().primaryColor,
              indicatorWeight: 2.0.r,
              indicatorSize: TabBarIndicatorSize.tab,
            ),
            Container(
              height: 280.h,
              padding: EdgeInsets.symmetric(horizontal: 10.w, vertical: 10.h),
              child: TabBarView(
                controller: _tabProgressController,
                children: [
                  // Tab untuk data Padi
                  ProgressChart(kecamatanId: kecamatanId)
                      .buildProgressChartTab('Padi'),
                  // Tab untuk data Palawija
                  ProgressChart(kecamatanId: kecamatanId)
                      .buildProgressChartTab('Palawija'),
                ],
              ),
            ),
          ],
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
      child: Column(
        children: [
          TabBar(
            controller: _tabController,
            tabs: _tabs.map((String tab) {
              return Tab(text: tab);
            }).toList(),
            labelColor: ColorTheme().primaryColor,
            unselectedLabelColor: Colors.grey,
            indicatorColor: ColorTheme().primaryColor,
            indicatorWeight: 2.0.r,
            indicatorSize: TabBarIndicatorSize.tab,
          ),
          Container(
            height: 280.h,
            padding: EdgeInsets.symmetric(horizontal: 10.w, vertical: 10.h),
            child: TabBarView(
              controller: _tabController,
              children: [
                // Tab untuk data Padi
                _buildChartTab(context, 'Padi'),
                // Tab untuk data Palawija
                _buildChartTab(context, 'Palawija'),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildChartTab(BuildContext context, String type) {
    return FutureBuilder(
      future: _fetchChartData(type),
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
          final data = snapshot.data;
          return Column(
            children: [
              Padding(
                padding: EdgeInsets.symmetric(horizontal: 10.w),
                child: Row(
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
                          "Trend $type", // Judul dinamis sesuai dengan jenis data
                          style: StyleTheme().stylePrimary.copyWith(
                                fontSize: 20.sp,
                                fontWeight: FontWeight.w500,
                              ),
                        ),
                      ],
                    ),
                    GestureDetector(
                      onTap: () {
                        _showDialogFilterTrend(context, years);
                      },
                      child: Icon(
                        Icons.filter_list,
                        color: ColorTheme().primaryColor,
                      ),
                    ),
                  ],
                ),
              ),
              Container(
                margin: EdgeInsets.only(top: 20.h),
                width: MediaQuery.of(context).size.width,
                height: 207.h,
                child: Column(
                  children: [
                    SizedBox(
                      height: 180.h,
                      width: 330.w,
                      child: LineChart(
                        TrendChart(data: data!).mainData(),
                      ),
                    ),
                    SizedBox(height: 3.h),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Row(
                          children: [
                            Container(
                              width: 10.w,
                              height: 10.h,
                              color: ColorTheme().secondaryColor,
                            ),
                            SizedBox(width: 5.w),
                            Text(
                              "Data Tanam",
                              style: TextStyle(
                                color: Colors.indigo,
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
                              "Data Panen",
                              style: TextStyle(
                                  color: ColorTheme().primaryColor,
                                  fontSize: 12.sp),
                            ),
                          ],
                        ),
                        SizedBox(width: 5.w),
                        Row(
                          children: [
                            Container(
                                width: 10.w,
                                height: 10.h,
                                color: Colors.red[900]),
                            SizedBox(width: 5.w),
                            Text(
                              "Data Puso/Rusak",
                              style: TextStyle(
                                  color: ColorTheme().primaryColor,
                                  fontSize: 12.sp),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ],
          );
        }
      },
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
          content: DropdownButtonComponent(
            icon: Icons.dataset,
            label: 'Tahun',
            selectedItem: selectedTahun,
            items: tahun.map(
              (value) {
                return DropdownMenuItem<int>(
                  value: value,
                  child: Text(value.toString()),
                );
              },
            ).toList(),
            hint: 'Pilih Tahun',
            validator: (value) =>
                value == null ? 'Pilih tahun terlebih dahulu' : null,
            onChanged: (newValue) {
              setState(() {
                selectedTahun = newValue!;
              });
            },
            onSaved: (newValue) {
              setState(() {
                selectedTahun = newValue!;
              });
            },
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
                  selectedTahun = DateTime.now().year;
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
}
