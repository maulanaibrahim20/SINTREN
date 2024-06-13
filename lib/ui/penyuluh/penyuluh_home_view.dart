import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
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
            height: MediaQuery.of(context).size.height * 0.35,
            decoration: BoxDecoration(
              gradient: ColorTheme().linearColor,
              borderRadius: const BorderRadius.vertical(
                bottom: Radius.elliptical(200, 30),
              ),
            ),
          ),
          Column(
            mainAxisAlignment: MainAxisAlignment.start,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              SizedBox(
                height: 300,
                child: Column(
                  children: [
                    const SizedBox(height: 30),
                    Padding(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 10, vertical: 10),
                      child: Container(
                        alignment: Alignment.centerRight,
                        child: PopupMenuButton<String>(
                          padding: EdgeInsets.zero,
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
                      ),
                    ),
                    ClipRect(
                      child: Align(
                        alignment: Alignment.center,
                        heightFactor: 0.5,
                        child: Image.asset(
                          'assets/images/pertanian.png',
                          width: 200,
                          height: 200,
                          fit: BoxFit.fill,
                        ),
                      ),
                    ),
                    Text(
                      "SINTREN",
                      style: StyleTheme()
                          .styleWhite
                          .copyWith(fontSize: 32, fontWeight: FontWeight.bold),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 20),
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 10),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Expanded(
                      flex: 1,
                      child: Container(
                        height: 50,
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(10),
                          gradient: ColorTheme().linearColor,
                        ),
                        child: ElevatedButton.icon(
                          onPressed: () {
                            desa.then((value) {
                              if (value.isEmpty) {
                                EasyLoading.showToast(
                                    "Belum Dilakukan Penyuluhan");
                              } else {
                                Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                        builder: (_) => const FormPadiView(
                                              onCreate: true,
                                            )));
                              }
                            });
                          },
                          icon: Icon(Icons.add, color: ColorTheme().whiteColor),
                          label: Text(
                            'Padi',
                            style: StyleTheme().styleWhite.copyWith(
                                  fontSize: 14,
                                  fontWeight: FontWeight.bold,
                                ),
                          ),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.transparent,
                            side: BorderSide(
                                color: ColorTheme().primaryColor, width: 2),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(10),
                            ),
                          ),
                        ),
                      ),
                    ),
                    const SizedBox(width: 10),
                    Expanded(
                      child: Container(
                        height: 50,
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(10),
                          gradient: ColorTheme().linearColor,
                        ),
                        child: ElevatedButton.icon(
                          onPressed: () {
                            Navigator.push(
                                context,
                                MaterialPageRoute(
                                    builder: (_) => const FormPalawijaView(
                                          onCreate: true,
                                        )));
                          },
                          icon: Icon(Icons.add, color: ColorTheme().whiteColor),
                          label: Text(
                            'Palawija',
                            style: StyleTheme().styleWhite.copyWith(
                                  fontSize: 14,
                                  fontWeight: FontWeight.bold,
                                ),
                          ),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.transparent,
                            side: BorderSide(
                                color: ColorTheme().primaryColor, width: 2),
                            shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(10),
                            ),
                          ),
                        ),
                      ),
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 10),
              Container(
                margin: const EdgeInsets.symmetric(horizontal: 10),
                width: MediaQuery.of(context).size.width,
                height: 50,
                decoration: BoxDecoration(
                  borderRadius: BorderRadius.circular(10),
                  gradient: ColorTheme().linearColor,
                ),
                child: ElevatedButton.icon(
                  onPressed: () {
                    Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (_) => const HistoriPenyuluhanView()));
                  },
                  icon: Icon(Icons.history, color: ColorTheme().whiteColor),
                  label: Text(
                    'Histori Penyuluhan',
                    style: TextStyle(
                      fontWeight: FontWeight.bold,
                      fontSize: 14,
                      color: ColorTheme().whiteColor,
                    ),
                  ),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: Colors.transparent,
                    side:
                        BorderSide(color: ColorTheme().primaryColor, width: 2),
                    shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(10),
                    ),
                  ),
                ),
              ),
              const SizedBox(height: 10),
              Padding(
                padding: const EdgeInsets.symmetric(horizontal: 15),
                child: Text("List Desa",
                    style: StyleTheme()
                        .stylePrimary
                        .copyWith(fontWeight: FontWeight.bold, fontSize: 18)),
              ),
              Expanded(
                child: FutureBuilder<List<dynamic>>(
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
                                  fontSize: 18,
                                  fontWeight: FontWeight.w500,
                                  color: Colors.grey),
                            ),
                          ],
                        ),
                      );
                    } else {
                      final historiList =
                          snapshot.data?[0] as List<HistoriPenyuluhanModel>;
                      final luasDesaList =
                          snapshot.data?[1] as List<LuasWilayahModel>;

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
                                    fontSize: 18,
                                    fontWeight: FontWeight.w500,
                                    color: Colors.grey),
                              ),
                            ],
                          ),
                        );
                      }
                      return Padding(
                        padding: const EdgeInsets.symmetric(vertical: 10),
                        child: ListView.builder(
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
                                      desaId: desa.desaId,
                                      desaName: desa.desaName,
                                    ),
                                  ),
                                ).then((value) => setState(() {}));
                              },
                              child: Card(
                                margin: const EdgeInsets.symmetric(
                                    horizontal: 10, vertical: 10),
                                elevation: 3,
                                surfaceTintColor: ColorTheme().whiteColor,
                                color: ColorTheme().whiteColor,
                                child: Column(
                                  children: [
                                    if (desa.totalTunggu > 0)
                                      Align(
                                        alignment: Alignment.topRight,
                                        child: Container(
                                          height: 30,
                                          width: 150,
                                          padding: const EdgeInsets.symmetric(
                                              vertical: 5, horizontal: 20),
                                          decoration: const BoxDecoration(
                                            color: Colors.red,
                                            borderRadius: BorderRadius.only(
                                              topRight: Radius.circular(10),
                                              bottomLeft: Radius.circular(10),
                                            ),
                                          ),
                                          child: Text(
                                            "${desa.totalTunggu} Data Ditolak",
                                            style: StyleTheme()
                                                .styleWhite
                                                .copyWith(
                                                    fontSize: 14,
                                                    fontWeight:
                                                        FontWeight.bold),
                                          ),
                                        ),
                                      ),
                                    const SizedBox(height: 10),
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
                                              gradient:
                                                  ColorTheme().linearColor,
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
                                                "Desa ${UserController().toCamelCase(desa.desaName)}",
                                                style: StyleTheme()
                                                    .stylePrimary
                                                    .copyWith(
                                                        fontSize: 20,
                                                        fontWeight:
                                                            FontWeight.bold),
                                              ),
                                              Text(
                                                userC.convertDate(desa.date),
                                                style: StyleTheme()
                                                    .styleBlack
                                                    .copyWith(
                                                        fontWeight:
                                                            FontWeight.bold,
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
                                          margin:
                                              const EdgeInsets.only(left: 20),
                                          padding: const EdgeInsets.symmetric(
                                              horizontal: 8.0),
                                          child: Text(
                                            "Progres bulan ini",
                                            style: StyleTheme()
                                                .styleBlack
                                                .copyWith(
                                                    color: Colors.black87),
                                          ),
                                        ),
                                      ],
                                    ),
                                    const SizedBox(height: 10),
                                    LinearPercentIndicator(
                                      width: MediaQuery.of(context).size.width -
                                          30,
                                      animation: true,
                                      lineHeight: 30,
                                      animationDuration: 2000,
                                      percent: (desa.nilai /
                                                  getLuasDesa(desa.desaId)) >
                                              1
                                          ? 1
                                          : desa.nilai /
                                              getLuasDesa(desa.desaId),
                                      center: Text(
                                        "${((desa.nilai / getLuasDesa(desa.desaId)) * 100).toStringAsFixed(1)}% (${desa.nilai}/${getLuasDesa(desa.desaId)})",
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
                            );
                          },
                        ),
                      );
                    }
                  },
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
