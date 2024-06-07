import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:percent_indicator/percent_indicator.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/prediksi_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/admin/admin_service.dart';
import 'package:sintren_mobile/ui/admin/components/home_chart.dart';
import 'package:sintren_mobile/ui/admin/components/home_verify_widget.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/login_view.dart';
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
  String? kecamatan;
  double? presentasePenyuluhan;
  double? penyuluhanBulanIni;
  double? totalLuasLahanKecamatan;
  Future<void> _initializedData() async {
    kecamatan = await UserLoginModel().getKecamatanName();
    penyuluhanBulanIni = await adminC.getTotalNilaiPenyuluhanBulanIni();
    totalLuasLahanKecamatan = await adminC.getTotalLuasLahanKecamatan();
    presentasePenyuluhan =
        (penyuluhanBulanIni! / totalLuasLahanKecamatan!) * 100;
  }

  @override
  void initState() {
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
            height: 90,
            decoration: BoxDecoration(
              borderRadius: const BorderRadius.only(
                  bottomLeft: Radius.circular(10),
                  bottomRight: Radius.circular(10)),
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
                    const SizedBox(height: 20),
                    Padding(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 20, vertical: 10),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text(
                            kecamatan == ""
                                ? "Dinas Pertanian Indramayu"
                                : "Kecamatan ${UserController().toCamelCase(kecamatan ?? "")}",
                            style: StyleTheme().styleWhite.copyWith(
                                  fontSize: 20,
                                  fontWeight: FontWeight.w500,
                                ),
                          ),
                          PopupMenuButton<String>(
                            surfaceTintColor: ColorTheme().whiteColor,
                            icon: Icon(
                              Icons.account_circle,
                              size: 30,
                              color: ColorTheme().whiteColor,
                            ),
                            onSelected: (String value) {
                              if (value == "1") {
                                Navigator.of(context).push(MaterialPageRoute(
                                    builder: (_) => const ChangeProfileView()));
                              } else if (value == "2") {
                                Navigator.of(context).push(MaterialPageRoute(
                                    builder: (_) =>
                                        const ChangePasswordView()));
                              } else {
                                userC.logout().then((value) {
                                  Navigator.pushAndRemoveUntil(
                                      context,
                                      MaterialPageRoute(
                                          builder: (_) => const LoginView()),
                                      (route) => false);
                                  EasyLoading.showToast("Berhasil Logout");
                                });
                              }
                            },
                            itemBuilder: (BuildContext context) =>
                                <PopupMenuEntry<String>>[
                              const PopupMenuItem<String>(
                                value: '1',
                                child: Row(
                                  children: [
                                    Icon(Icons.person),
                                    SizedBox(width: 5),
                                    Text('Edit Profil'),
                                  ],
                                ),
                              ),
                              const PopupMenuItem<String>(
                                value: '2',
                                child: Row(
                                  children: [
                                    Icon(Icons.lock),
                                    SizedBox(width: 5),
                                    Text('Ubah Password'),
                                  ],
                                ),
                              ),
                              const PopupMenuItem<String>(
                                value: '3',
                                child: Row(
                                  children: [
                                    Icon(
                                      Icons.logout,
                                      color: Colors.red,
                                    ),
                                    SizedBox(width: 5),
                                    Text(
                                      'Logout',
                                      style: TextStyle(
                                        color: Colors.red,
                                      ),
                                    ),
                                  ],
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 10),
                    Expanded(
                      child: ListView(
                        shrinkWrap: true,
                        padding: EdgeInsets.zero,
                        children: [
                          Card(
                            margin: const EdgeInsets.symmetric(horizontal: 15),
                            elevation: 3,
                            surfaceTintColor: ColorTheme().whiteColor,
                            child: Container(
                              height: MediaQuery.of(context).size.height * 0.3,
                              padding: const EdgeInsets.symmetric(
                                  horizontal: 20, vertical: 10),
                              child: Column(
                                children: [
                                  Row(
                                    mainAxisAlignment:
                                        MainAxisAlignment.spaceBetween,
                                    children: [
                                      Row(
                                        children: [
                                          Icon(
                                            Icons.stacked_line_chart,
                                            color: ColorTheme().primaryColor,
                                            size: 30,
                                          ),
                                          const SizedBox(width: 10),
                                          Text(
                                            "Trend Pertanian",
                                            style: StyleTheme()
                                                .stylePrimary
                                                .copyWith(
                                                  fontSize: 20,
                                                  fontWeight: FontWeight.w500,
                                                ),
                                          ),
                                        ],
                                      ),
                                      GestureDetector(
                                        onTap: () {
                                          // MainPopup().filterTrend(context);
                                        },
                                        child: Icon(
                                          Icons.filter_list,
                                          color: ColorTheme().primaryColor,
                                        ),
                                      ),
                                    ],
                                  ),
                                  Container(
                                    margin: const EdgeInsets.only(top: 10),
                                    width: MediaQuery.of(context).size.width,
                                    height: 200,
                                    child: FutureBuilder<PrediksiModel>(
                                        future:
                                            AdminService().getPrediksiPadi(),
                                        builder: (context, snapshot) {
                                          if (snapshot.connectionState ==
                                              ConnectionState.waiting) {
                                            return const Center(
                                                child:
                                                    CircularProgressIndicator());
                                          } else if (snapshot.hasError) {
                                            return Center(
                                              child: Column(
                                                mainAxisAlignment:
                                                    MainAxisAlignment.center,
                                                children: [
                                                  const Icon(
                                                    Icons.error,
                                                    color: Colors.grey,
                                                    size: 50,
                                                  ),
                                                  Text(
                                                    "Internal Server Error",
                                                    style: StyleTheme()
                                                        .styleBlack
                                                        .copyWith(
                                                            fontSize: 18,
                                                            fontWeight:
                                                                FontWeight.w500,
                                                            color: Colors.grey),
                                                  ),
                                                ],
                                              ),
                                            );
                                          } else {
                                            final prediksiList = snapshot.data;
                                            return LineChart(
                                              HomeChart(
                                                      labels:
                                                          prediksiList!.labels,
                                                      targets:
                                                          prediksiList.targets)
                                                  .mainData(),
                                            );
                                          }
                                        }),
                                  ),
                                ],
                              ),
                            ),
                          ),
                          const SizedBox(height: 15),
                          Card(
                            margin: const EdgeInsets.symmetric(horizontal: 15),
                            elevation: 5,
                            surfaceTintColor: ColorTheme().whiteColor,
                            color: ColorTheme().whiteColor,
                            child: Column(
                              children: [
                                const SizedBox(height: 20),
                                Padding(
                                  padding: const EdgeInsets.symmetric(
                                      horizontal: 20),
                                  child: Row(
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
                                        crossAxisAlignment:
                                            CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            kecamatan == ""
                                                ? "Kabupaten Indramayu"
                                                : "Kecamatan ${UserController().toCamelCase(kecamatan ?? "")}",
                                            style: StyleTheme()
                                                .stylePrimary
                                                .copyWith(
                                                    fontSize: 20,
                                                    fontWeight:
                                                        FontWeight.bold),
                                          ),
                                          Text(
                                            userC.dateNow(),
                                            style: StyleTheme()
                                                .styleBlack
                                                .copyWith(
                                                    fontWeight: FontWeight.bold,
                                                    color: Colors.grey[700],
                                                    fontSize: 14),
                                          ),
                                        ],
                                      )
                                    ],
                                  ),
                                ),
                                const SizedBox(height: 10),
                                Stack(
                                  children: [
                                    const Divider(
                                        thickness: 2, color: Colors.grey),
                                    Container(
                                      color: ColorTheme().whiteColor,
                                      margin: const EdgeInsets.only(left: 20),
                                      padding: const EdgeInsets.symmetric(
                                          horizontal: 8.0),
                                      child: Text(
                                        "Progres bulan ini",
                                        style: StyleTheme()
                                            .styleBlack
                                            .copyWith(color: Colors.black87),
                                      ),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 10),
                                LinearPercentIndicator(
                                  width: MediaQuery.of(context).size.width - 30,
                                  animation: true,
                                  lineHeight: 30,
                                  animationDuration: 2000,
                                  percent: (presentasePenyuluhan! / 100) > 1
                                      ? 1
                                      : (presentasePenyuluhan! / 100),
                                  center: Text(
                                    "${presentasePenyuluhan!.toStringAsFixed(1)}% ($penyuluhanBulanIni/$totalLuasLahanKecamatan)",
                                    style: StyleTheme().styleWhite.copyWith(
                                        fontWeight: FontWeight.w500,
                                        fontSize: 14),
                                  ),
                                  barRadius: const Radius.circular(10),
                                  linearGradient: ColorTheme().linearColor,
                                ),
                                const SizedBox(height: 10),
                              ],
                            ),
                          ),
                          const SizedBox(height: 10),
                          const HomeVerifyWidget(),
                          const SizedBox(height: 20),
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
}
