import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:percent_indicator/percent_indicator.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
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
import 'package:sintren_mobile/ui/penyuluh/components/dropdown_button_component.dart';
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
  int selectedDariTahun = DateTime.now().year - 5;
  int selectedSampaiTahun = DateTime.now().year - 1;

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
            height: 250,
            decoration: BoxDecoration(
              borderRadius: const BorderRadius.only(
                  bottomLeft: Radius.circular(10),
                  bottomRight: Radius.circular(10)),
              gradient: ColorTheme().linearColor,
            ),
          ),
          Column(
            children: [
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
                        _customAppBar(context),
                        const SizedBox(height: 10),
                        _trendLineChart(context),
                        const SizedBox(height: 15),
                        _progresPenyuluhan(context),
                      ],
                    );
                  }
                },
              ),
              const SizedBox(height: 10),
              _listVerify(),
              const SizedBox(height: 20),
            ],
          ),
        ],
      ),
    );
  }

  Expanded _listVerify() {
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
                width: MediaQuery.of(context).size.width,
                height: 50,
                margin: const EdgeInsets.symmetric(horizontal: 15, vertical: 5),
                padding: const EdgeInsets.symmetric(horizontal: 15),
                decoration: BoxDecoration(
                  color: Colors.green,
                  borderRadius: BorderRadius.circular(10),
                ),
                child: Center(
                  child: Row(
                    children: [
                      Icon(
                        Icons.verified_outlined,
                        color: ColorTheme().whiteColor,
                      ),
                      const SizedBox(width: 10),
                      Text(
                        'Semua Data Sudah Diverifikasi',
                        style: StyleTheme().styleWhite.copyWith(
                            fontSize: 14, fontWeight: FontWeight.bold),
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
                    width: MediaQuery.of(context).size.width,
                    height: 50,
                    margin:
                        const EdgeInsets.symmetric(horizontal: 15, vertical: 5),
                    padding: const EdgeInsets.symmetric(horizontal: 15),
                    decoration: BoxDecoration(
                      color: ColorTheme().primaryColor,
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Center(
                        child: Row(
                      children: [
                        Icon(
                          Icons.error_outline,
                          color: ColorTheme().whiteColor,
                        ),
                        const SizedBox(width: 10),
                        Expanded(
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                '${snapshot.data!.length} Data Menunggu Diverifikasi',
                                style: StyleTheme().styleWhite.copyWith(
                                    fontSize: 14, fontWeight: FontWeight.bold),
                              ),
                              Icon(
                                Icons.arrow_right_rounded,
                                size: 35,
                                color: ColorTheme().whiteColor,
                              )
                            ],
                          ),
                        ),
                      ],
                    )),
                  ),
                ),
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
                        margin: const EdgeInsets.symmetric(
                            vertical: 5, horizontal: 15),
                        elevation: 3,
                        child: ConstrainedBox(
                          constraints: const BoxConstraints(),
                          child: Padding(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 20, vertical: 10),
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
                                          fontSize: 16),
                                    ),
                                    Container(
                                      margin: const EdgeInsets.symmetric(
                                          vertical: 3),
                                      padding: const EdgeInsets.symmetric(
                                          horizontal: 8, vertical: 3),
                                      decoration: BoxDecoration(
                                        color: Colors.amber,
                                        borderRadius: BorderRadius.circular(5),
                                      ),
                                      child: Text(
                                        "Membutuhkan Verifikasi",
                                        style: StyleTheme().styleWhite.copyWith(
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
                                      UserController().toCamelCase(item.type ==
                                              "padi"
                                          ? dataPadi?.jenisBantuan ?? ""
                                          : dataPalawija?.jenisBantuan ?? ""),
                                      style: StyleTheme().styleBlack,
                                    ),
                                    Text(
                                      item.type == "padi"
                                          ? dataPadi?.date ?? ""
                                          : dataPalawija?.date ?? "",
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
                                          fontSize: 14,
                                          fontWeight: FontWeight.w500),
                                    ),
                                    Text(
                                      "${item.type == "padi" ? dataPadi?.nilai : dataPalawija?.nilai} hektar",
                                      style: StyleTheme().styleBlack.copyWith(
                                            fontSize: 14,
                                            fontWeight: FontWeight.bold,
                                          ),
                                    ),
                                  ],
                                ),
                                const Divider(),
                                const SizedBox(height: 5),
                                Row(
                                  children: [
                                    Expanded(
                                      flex: 1,
                                      child: ElevatedButton.icon(
                                        onPressed: () async {
                                          bool? shouldVerify =
                                              await _showVerifyDialog(context);
                                          if (shouldVerify == true) {
                                            await AdminController().verify(
                                              dataId: item.type == "padi"
                                                  ? dataPadi!.id.toString()
                                                  : dataPalawija!.id.toString(),
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
                                                    fontSize: 14,
                                                    color: Colors.green)),
                                        style: ElevatedButton.styleFrom(
                                          surfaceTintColor:
                                              ColorTheme().whiteColor,
                                          side: const BorderSide(
                                              color: Colors.green, width: 2),
                                          shape: RoundedRectangleBorder(
                                            borderRadius:
                                                BorderRadius.circular(30),
                                          ),
                                        ),
                                      ),
                                    ),
                                    const SizedBox(width: 10),
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
                                                  : dataPalawija!.id.toString(),
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
                                                    fontSize: 14,
                                                    color: Colors.red)),
                                        style: ElevatedButton.styleFrom(
                                          surfaceTintColor:
                                              ColorTheme().whiteColor,
                                          side: const BorderSide(
                                              color: Colors.red, width: 2),
                                          shape: RoundedRectangleBorder(
                                            borderRadius:
                                                BorderRadius.circular(30),
                                          ),
                                        ),
                                      ),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 5),
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
      margin: const EdgeInsets.symmetric(horizontal: 15),
      elevation: 5,
      surfaceTintColor: ColorTheme().whiteColor,
      color: ColorTheme().whiteColor,
      child: Column(
        children: [
          const SizedBox(height: 20),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 20),
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
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(
                      kecamatan == ""
                          ? "Kabupaten Indramayu"
                          : "Kecamatan ${UserController().toCamelCase(kecamatan ?? "")}",
                      style: StyleTheme()
                          .stylePrimary
                          .copyWith(fontSize: 20, fontWeight: FontWeight.bold),
                    ),
                    Text(
                      userC.dateNow(),
                      style: StyleTheme().styleBlack.copyWith(
                          fontWeight: FontWeight.bold,
                          color: Colors.grey[700],
                          fontSize: 14),
                    ),
                  ],
                ),
                Expanded(
                  child: Align(
                    alignment: Alignment.centerRight,
                    child: Container(
                      decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          gradient: ColorTheme().linearColor),
                      child: IconButton(
                        icon: const Icon(Icons.refresh),
                        color: ColorTheme().whiteColor, // Icon color
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
                )
              ],
            ),
          ),
          const SizedBox(height: 10),
          Stack(
            children: [
              const Divider(thickness: 2, color: Colors.grey),
              Container(
                color: ColorTheme().whiteColor,
                margin: const EdgeInsets.only(left: 20),
                padding: const EdgeInsets.symmetric(horizontal: 8.0),
                child: Text(
                  "Progres bulan ini",
                  style:
                      StyleTheme().styleBlack.copyWith(color: Colors.black87),
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
              "${presentasePenyuluhan!.toStringAsFixed(1)}% (${penyuluhanBulanIni!.toStringAsFixed(1)}/${totalLuasLahanKecamatan!.toStringAsFixed(1)})",
              style: StyleTheme()
                  .styleWhite
                  .copyWith(fontWeight: FontWeight.w500, fontSize: 14),
            ),
            barRadius: const Radius.circular(10),
            linearGradient: ColorTheme().linearColor,
          ),
          const SizedBox(height: 10),
        ],
      ),
    );
  }

  Card _trendLineChart(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 15),
      elevation: 3,
      surfaceTintColor: ColorTheme().whiteColor,
      child: Container(
        height: MediaQuery.of(context).size.height * 0.3,
        padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
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
                    const Icon(
                      Icons.error,
                      color: Colors.grey,
                      size: 50,
                    ),
                    Text(
                      "Internal Server Error : ${snapshot.error}",
                      style: StyleTheme().styleBlack.copyWith(
                          fontSize: 18,
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
                    margin: const EdgeInsets.only(top: 10),
                    width: MediaQuery.of(context).size.width,
                    height: 207,
                    child: Column(
                      children: [
                        SizedBox(
                          height: 180,
                          child: LineChart(
                            HomeChart(data: result).mainData(),
                          ),
                        ),
                        const SizedBox(height: 5),
                        Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 16.0),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              Row(
                                children: [
                                  Container(
                                    width: 10,
                                    height: 10,
                                    color: ColorTheme().secondaryColor,
                                  ),
                                  const SizedBox(width: 5),
                                  Text(
                                    "Data Aktual",
                                    style: TextStyle(
                                      color: ColorTheme().primaryColor,
                                    ),
                                  ),
                                ],
                              ),
                              const SizedBox(width: 10),
                              Row(
                                children: [
                                  Container(
                                      width: 10,
                                      height: 10,
                                      color: Colors.amber[900]),
                                  const SizedBox(width: 5),
                                  Text(
                                    "Data Prediksi",
                                    style: TextStyle(
                                        color: ColorTheme().primaryColor),
                                  ),
                                ],
                              )
                            ],
                          ),
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
        final formKeyUP = GlobalKey<FormState>();
        return Form(
          key: formKeyUP,
          child: AlertDialog(
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
                DropdownButtonComponent(
                  icon: Icons.dataset,
                  label: 'Sampai Tahun',
                  selectedItem: selectedSampaiTahun,
                  items: tahun.map(
                    (value) {
                      return DropdownMenuItem<int>(
                        value: value,
                        child: Text(
                            UserController().toCamelCase(value.toString())),
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
                    setState(() {
                      selectedSampaiTahun = newValue!;
                    });
                  },
                  onSaved: (newValue) {
                    setState(() {
                      selectedSampaiTahun = newValue!;
                    });
                  },
                ),
              ],
            ),
            actions: <Widget>[
              TextButton(
                onPressed: () {
                  Navigator.of(context)
                      .pop(false); // Kembali dengan nilai false
                },
                child: Text(
                  "Tutup",
                  style: StyleTheme()
                      .stylePrimary
                      .copyWith(color: Colors.red, fontSize: 16),
                ),
              ),
              TextButton(
                onPressed: () {
                  if (formKeyUP.currentState!.validate()) {
                    setState(() {
                      selectedDariTahun = DateTime.now().year - 5;
                      selectedSampaiTahun = DateTime.now().year;
                    });
                  }
                },
                child: Text(
                  "Reset",
                  style: StyleTheme().stylePrimary.copyWith(fontSize: 16),
                ),
              ),
            ],
          ),
        );
      },
    );
  }

  Padding _customAppBar(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
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
            style: StyleTheme().styleBlack.copyWith(fontSize: 14),
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
                    .copyWith(fontSize: 14, color: Colors.red),
              ),
            ),
            TextButton(
              onPressed: () async {
                Navigator.of(context).pop(true);
              },
              child: Text(
                "Ya",
                style: StyleTheme().stylePrimary.copyWith(fontSize: 14),
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
                  style: StyleTheme().styleBlack.copyWith(fontSize: 14),
                ),
                const SizedBox(height: 10),
                const Text("Berikan Ulasan:"),
                TextFormField(
                  controller: ulasan,
                  decoration: InputDecoration(
                    isDense: true,
                    filled: true,
                    fillColor: ColorTheme().whiteColor,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(10),
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
                      .copyWith(fontSize: 14, color: Colors.red),
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
                  style: StyleTheme().stylePrimary.copyWith(fontSize: 14),
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}
