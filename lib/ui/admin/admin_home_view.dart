import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:percent_indicator/percent_indicator.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/ui/admin/components/home_chart.dart';
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
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      resizeToAvoidBottomInset: false,
      backgroundColor: ColorTheme().bgColor,
      body: Stack(
        children: [
          Container(
            width: MediaQuery.of(context).size.width,
            height: 300,
            decoration: BoxDecoration(
              borderRadius: const BorderRadius.only(
                  bottomLeft: Radius.circular(10),
                  bottomRight: Radius.circular(10)),
              gradient: ColorTheme().linearColor,
            ),
          ),
          Column(
            children: [
              const SizedBox(height: 30),
              Padding(
                padding:
                    const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text(
                      "Kecamatan Indramayu",
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
                              builder: (_) => const ChangePasswordView()));
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
              Card(
                margin: const EdgeInsets.symmetric(horizontal: 15),
                elevation: 3,
                surfaceTintColor: ColorTheme().whiteColor,
                child: Container(
                  height: MediaQuery.of(context).size.height * 0.3,
                  padding:
                      const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                  child: Column(
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
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
                                style: StyleTheme().stylePrimary.copyWith(
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
                        child: LineChart(
                          HomeChart().mainData(),
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              const SizedBox(height: 20),
              Card(
                margin: const EdgeInsets.symmetric(horizontal: 15),
                elevation: 3,
                surfaceTintColor: ColorTheme().whiteColor,
                child: SizedBox(
                  height: 190,
                  width: MediaQuery.of(context).size.width,
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Container(
                        padding: const EdgeInsets.symmetric(
                            horizontal: 10, vertical: 5),
                        height: 30,
                        width: MediaQuery.of(context).size.width,
                        decoration: BoxDecoration(
                          color: ColorTheme().primaryColor,
                          borderRadius: const BorderRadius.only(
                            topLeft: Radius.circular(10),
                            topRight: Radius.circular(10),
                          ),
                        ),
                        child: Text(
                          "Info Desa:",
                          style: StyleTheme().styleWhite.copyWith(
                                fontSize: 14,
                                fontWeight: FontWeight.w500,
                              ),
                        ),
                      ),
                      Row(
                        children: [
                          SizedBox(
                            width: MediaQuery.of(context).size.width * 0.4,
                            height: 160,
                            child: CircularPercentIndicator(
                              radius: 60,
                              lineWidth: 15.0,
                              animation: true,
                              percent: 0.75,
                              center: Text(
                                "75.0%",
                                style: StyleTheme().styleBlack.copyWith(
                                    fontSize: 20, fontWeight: FontWeight.bold),
                              ),
                              circularStrokeCap: CircularStrokeCap.round,
                              // progressColor: ColorTheme().thirdColor,
                              linearGradient: ColorTheme().linearColor,
                              footer: Text(
                                "Wilayah Tersuluh",
                                style: StyleTheme().stylePrimary.copyWith(
                                    fontWeight: FontWeight.bold, fontSize: 14),
                              ),
                            ),
                          ),
                          Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                children: [
                                  const Icon(
                                    Icons.house,
                                    size: 18,
                                  ),
                                  const SizedBox(width: 10),
                                  RichText(
                                    text: TextSpan(
                                      text: "Total Desa: ",
                                      style: StyleTheme().styleBlack,
                                      children: [
                                        TextSpan(
                                          text: "10 Desa",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
                                                    fontWeight: FontWeight.w500,
                                                  ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 5),
                              Row(
                                children: [
                                  const Icon(
                                    Icons.rice_bowl,
                                    size: 18,
                                  ),
                                  const SizedBox(width: 10),
                                  RichText(
                                    text: TextSpan(
                                      text: "Total Luas Lahan: ",
                                      style: StyleTheme().styleBlack,
                                      children: [
                                        TextSpan(
                                          text: "1000 Hektar",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
                                                    fontWeight: FontWeight.w500,
                                                  ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 5),
                              Row(
                                children: [
                                  const Icon(
                                    Icons.rice_bowl,
                                    size: 18,
                                  ),
                                  const SizedBox(width: 10),
                                  RichText(
                                    text: TextSpan(
                                      text: "Luas Lahan Disuluh: ",
                                      style: StyleTheme().styleBlack,
                                      children: [
                                        TextSpan(
                                          text: "700 Hektar",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
                                                    fontWeight: FontWeight.w500,
                                                  ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(height: 5),
                              Row(
                                children: [
                                  const Icon(
                                    Icons.rice_bowl,
                                    size: 18,
                                  ),
                                  const SizedBox(width: 10),
                                  RichText(
                                    text: TextSpan(
                                      text: "Luas Lahan Belum Disuluh: ",
                                      style: StyleTheme().styleBlack,
                                      children: [
                                        TextSpan(
                                          text: "\n300 Hektar",
                                          style:
                                              StyleTheme().styleBlack.copyWith(
                                                    fontWeight: FontWeight.w500,
                                                  ),
                                        ),
                                      ],
                                    ),
                                  ),
                                ],
                              ),
                            ],
                          )
                        ],
                      )
                    ],
                  ),
                ),
              ),
            ],
          )
        ],
      ),
    );
  }
}
