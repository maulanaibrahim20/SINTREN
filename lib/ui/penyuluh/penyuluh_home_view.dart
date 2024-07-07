import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:percent_indicator/percent_indicator.dart';
import 'package:sintren_mobile/controllers/penyuluh/penyuluh_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/login_view.dart';
import 'package:sintren_mobile/ui/penyuluh/detail_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/penyuluh/form/form_padi_view.dart';
import 'package:sintren_mobile/ui/penyuluh/form/form_palawija_view.dart';
import 'package:sintren_mobile/ui/penyuluh/histori_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/users/change_password_view.dart';
import 'package:sintren_mobile/ui/users/change_profile_view.dart';

class PenyuluhHomeView extends StatefulWidget {
  const PenyuluhHomeView({super.key});

  @override
  State<PenyuluhHomeView> createState() => PenyuluhHomeViewState();
}

class PenyuluhHomeViewState extends State<PenyuluhHomeView> {
  final userC = UserController();
  final penyuluhC = PenyuluhController();
  late Future<List<DesaModel>> desa;

  Future<void> _initializeData() async {
    setState(() {
      desa = penyuluhC.getDesa();
    });
  }

  @override
  void initState() {
    setState(() {});
    super.initState();
    desa = Future.value([]);
    _initializeData();
  }

  @override
  Widget build(BuildContext context) {
    final statusNotifier =
        ValueNotifier<String>('Memulai sinkronisasi data...');

    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      floatingActionButton: FloatingActionButton(
        shape: const CircleBorder(),
        onPressed: () async {
          EasyLoading.show(status: statusNotifier.value);

          statusNotifier.addListener(() {
            EasyLoading.show(status: statusNotifier.value);
          });

          try {
            await penyuluhC.synchronizeData(statusNotifier);
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
            height: 320.h,
            decoration: BoxDecoration(
              gradient: ColorTheme().linearColor,
              borderRadius: BorderRadius.vertical(
                bottom: Radius.elliptical(200.w, 30.h),
              ),
            ),
          ),
          Padding(
            padding: EdgeInsets.symmetric(horizontal: 10.w),
            child: Column(
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _customAppBar(context),
                SizedBox(height: 40.h),
                _buttonAddData(context),
                SizedBox(height: 10.h),
                _buttonHistory(context),
                SizedBox(height: 15.h),
                Padding(
                  padding: EdgeInsets.only(left: 5.w),
                  child: Text("List Desa",
                      style: StyleTheme().stylePrimary.copyWith(
                          fontWeight: FontWeight.bold, fontSize: 18.sp)),
                ),
                SizedBox(height: 10.h),
                Expanded(child: _buildListPenyuluhan()),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildListPenyuluhan() {
    return FutureBuilder<List<dynamic>>(
      future: Future.wait([
        penyuluhC.getHistoriPenyuluhanBulanIni(),
        penyuluhC.getLuasLahanDesa(),
      ]),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        } else if (snapshot.hasError) {
          return Center(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Icon(
                  Icons.error,
                  color: Colors.grey,
                  size: 50,
                ),
                Text(
                  "Internal Server Error: ${snapshot.error}",
                  style: StyleTheme().styleBlack.copyWith(
                      fontSize: 18.sp,
                      fontWeight: FontWeight.w500,
                      color: Colors.grey),
                ),
              ],
            ),
          );
        } else {
          final historiList = snapshot.data?[0] as List<HistoriPenyuluhanModel>;
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
                  const Icon(
                    Icons.assignment,
                    color: Colors.grey,
                    size: 50,
                  ),
                  Text(
                    "Tugas Belum Diberikan",
                    style: StyleTheme().styleBlack.copyWith(
                        fontSize: 18.sp,
                        fontWeight: FontWeight.w500,
                        color: Colors.grey),
                  ),
                ],
              ),
            );
          }
          return ListView.builder(
            shrinkWrap: true,
            padding: EdgeInsets.zero,
            itemCount: historiList.length,
            itemBuilder: (BuildContext context, int index) {
              HistoriPenyuluhanModel desa = historiList[index];
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
                  ).then((value) => setState(() {}));
                },
                child: Card(
                  margin: EdgeInsets.symmetric(horizontal: 5.w, vertical: 5.h),
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
                            width: 150.w,
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
                              "${desa.totalTunggu} Data Ditolak",
                              style: StyleTheme().styleWhite.copyWith(
                                  fontSize: 14.sp, fontWeight: FontWeight.bold),
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
                        width: MediaQuery.of(context).size.width - 30.w,
                        animation: true,
                        lineHeight: 30.h,
                        animationDuration: 2000,
                        percent: (desa.nilai / getLuasDesa(desa.id)) > 1
                            ? 1
                            : desa.nilai / getLuasDesa(desa.id),
                        center: Text(
                          "${((desa.nilai / getLuasDesa(desa.id)) * 100).toStringAsFixed(1)}% (${desa.nilai}/${getLuasDesa(desa.id)})",
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
          );
        }
      },
    );
  }

  InkWell _buttonHistory(BuildContext context) {
    return InkWell(
      onTap: () {
        Navigator.push(
          context,
          MaterialPageRoute(builder: (_) => const HistoriPenyuluhanView()),
        );
      },
      child: Container(
        margin: EdgeInsets.symmetric(horizontal: 5.w),
        width: MediaQuery.of(context).size.width,
        height: 50.h,
        decoration: BoxDecoration(
          borderRadius: BorderRadius.circular(10.r),
          gradient: ColorTheme().linearColorGrey,
          border: Border.all(
            color: ColorTheme().grey,
          ),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.history, color: ColorTheme().primaryColor),
            SizedBox(width: 10.w),
            Text(
              'Histori Penyuluhan',
              style: TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 14.sp,
                color: ColorTheme().primaryColor,
              ),
            )
          ],
        ),
      ),
    );
  }

  Padding _buttonAddData(BuildContext context) {
    return Padding(
      padding: EdgeInsets.symmetric(horizontal: 5.w),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Expanded(
            flex: 1,
            child: InkWell(
              onTap: () {
                desa.then((value) {
                  if (value.isEmpty) {
                    EasyLoading.showToast("Belum Dilakukan Penyuluhan");
                  } else {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => const FormPadiView(
                          onCreate: true,
                        ),
                      ),
                    );
                  }
                });
              },
              child: Container(
                height: 50.h,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(10.r),
                  gradient: ColorTheme().linearColorGreen,
                  border: Border.all(
                    color: ColorTheme().green,
                  ),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.add, color: ColorTheme().whiteColor),
                    SizedBox(width: 10.w),
                    Text(
                      'Padi',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 14.sp,
                        color: ColorTheme().whiteColor,
                      ),
                    )
                  ],
                ),
              ),
            ),
          ),
          SizedBox(width: 10.w),
          Expanded(
            flex: 1,
            child: InkWell(
              onTap: () {
                desa.then((value) {
                  if (value.isEmpty) {
                    EasyLoading.showToast("Belum Dilakukan Penyuluhan");
                  } else {
                    Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => const FormPalawijaView(
                          onCreate: true,
                        ),
                      ),
                    );
                  }
                });
              },
              child: Container(
                height: 50.h,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(10.r),
                  gradient: ColorTheme().linearColorGreen,
                  border: Border.all(
                    color: ColorTheme().green,
                  ),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(Icons.add, color: ColorTheme().whiteColor),
                    SizedBox(width: 10.w),
                    Text(
                      'Palawija',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 14.sp,
                        color: ColorTheme().whiteColor,
                      ),
                    )
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  SizedBox _customAppBar(BuildContext context) {
    return SizedBox(
      height: 300.h,
      child: Column(
        children: [
          SizedBox(height: 30.h),
          Padding(
            padding: EdgeInsets.symmetric(horizontal: 5.w, vertical: 10.h),
            child: Container(
              alignment: Alignment.centerRight,
              child: PopupMenuButton<String>(
                padding: EdgeInsets.zero,
                surfaceTintColor: ColorTheme().whiteColor,
                icon: Icon(
                  Icons.account_circle,
                  size: 30.sp,
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
                        const Icon(Icons.person),
                        SizedBox(width: 5.w),
                        const Text('Edit Profil'),
                      ],
                    ),
                  ),
                  PopupMenuItem<String>(
                    value: '2',
                    child: Row(
                      children: [
                        const Icon(Icons.lock),
                        SizedBox(width: 5.w),
                        const Text('Ubah Password'),
                      ],
                    ),
                  ),
                  PopupMenuItem<String>(
                    value: '3',
                    child: Row(
                      children: [
                        const Icon(
                          Icons.logout,
                          color: Colors.red,
                        ),
                        SizedBox(width: 5.w),
                        const Text(
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
            ),
          ),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              ClipRect(
                child: Align(
                  alignment: Alignment.center,
                  heightFactor: 1,
                  child: Image.asset(
                    'assets/images/pertanian.png',
                    width: 100.w,
                    height: 110.h,
                    fit: BoxFit.fill,
                  ),
                ),
              ),
              SizedBox(width: 20.w),
              ClipRect(
                child: Align(
                  alignment: Alignment.center,
                  heightFactor: 1,
                  child: Image.asset(
                    'assets/images/logo-polindra.png',
                    width: 100.w,
                    height: 100.h,
                    fit: BoxFit.fill,
                  ),
                ),
              ),
            ],
          ),
          SizedBox(height: 20.h),
          Text(
            "SINTREN",
            style: StyleTheme()
                .styleWhite
                .copyWith(fontSize: 32.sp, fontWeight: FontWeight.bold),
          ),
        ],
      ),
    );
  }
}
