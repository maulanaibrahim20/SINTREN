import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:percent_indicator/linear_percent_indicator.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:sintren_mobile/services/admin/admin_padi_service.dart';
import 'package:sintren_mobile/services/admin/admin_palawija_service.dart';
import 'package:sintren_mobile/services/admin/admin_service.dart';
import 'package:sintren_mobile/ui/admin/detail_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/components/dropdown_button_component.dart';

class AdminPenyuluhanView extends StatefulWidget {
  const AdminPenyuluhanView({super.key});

  @override
  State<AdminPenyuluhanView> createState() => _AdminPenyuluhanViewState();
}

class _AdminPenyuluhanViewState extends State<AdminPenyuluhanView> {
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
                fontSize: 20,
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
        ],
        backgroundColor: ColorTheme().primaryColor,
      ),
      floatingActionButton: FloatingActionButton(
        shape: const CircleBorder(),
        onPressed: () async {
          EasyLoading.show(status: "Sinkronisasi Data");
          await _synchronizeData();
          EasyLoading.dismiss();
        },
        backgroundColor: ColorTheme().primaryColor,
        foregroundColor: ColorTheme().whiteColor,
        child: const Icon(
          Icons.refresh_rounded,
        ),
      ),
      body: FutureBuilder<List<dynamic>>(
        future: Future.wait([
          adminC.getHistoriPenyuluhan(),
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
                  const Icon(
                    Icons.error,
                    color: Colors.grey,
                    size: 50,
                  ),
                  Text(
                    "Internal Server Error",
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
            final luasDesaList = snapshot.data?[1] as List<LuasWilayahModel>;

            int getLuasDesa(String id) {
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
                itemCount: (selectedDesaValue == null)
                    ? historiList.length
                    : historiList
                        .where((desa) => desa.desaId == selectedDesaValue!.id)
                        .length,
                itemBuilder: (BuildContext context, int index) {
                  var displayList = (selectedDesaValue == null)
                      ? historiList
                      : historiList
                          .where((desa) => desa.desaId == selectedDesaValue!.id)
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
                            desaId: desa.desaId,
                            desaName: desa.desaName,
                          ),
                        ),
                      );
                      setState(() {});
                    },
                    child: Card(
                      margin: const EdgeInsets.symmetric(
                          horizontal: 20, vertical: 10),
                      elevation: 3,
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
                                      "Desa ${UserController().toCamelCase(desa.desaName)}",
                                      style: StyleTheme().stylePrimary.copyWith(
                                          fontSize: 20,
                                          fontWeight: FontWeight.bold),
                                    ),
                                    Text(
                                      userC.convertDate(desa.date),
                                      style: StyleTheme().styleBlack.copyWith(
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
                              const Divider(thickness: 2, color: Colors.grey),
                              Container(
                                color: ColorTheme().whiteColor,
                                margin: const EdgeInsets.only(left: 20),
                                padding:
                                    const EdgeInsets.symmetric(horizontal: 8.0),
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
                            width: MediaQuery.of(context).size.width - 40,
                            animation: true,
                            lineHeight: 30,
                            animationDuration: 2000,
                            percent: (desa.nilai / getLuasDesa(desa.desaId)) > 1
                                ? 1
                                : desa.nilai / getLuasDesa(desa.desaId),
                            center: Text(
                              "${((desa.nilai / getLuasDesa(desa.desaId)) * 100).toStringAsFixed(1)}% (${desa.nilai}/${getLuasDesa(desa.desaId)})",
                              style: StyleTheme().styleWhite.copyWith(
                                  fontWeight: FontWeight.w500, fontSize: 14),
                            ),
                            barRadius: const Radius.circular(10),
                            linearGradient: ColorTheme().linearColor,
                          ),
                          Padding(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 10, vertical: 10),
                            child: ElevatedButton.icon(
                              onPressed: () {
                                _showConfirmationDialog(context);
                                // Navigator.push(context,
                                //     MaterialPageRoute(builder: (_) => AddPadiView()));
                              },
                              icon: const Icon(Icons.verified_outlined,
                                  color: Colors.green),
                              label: Text('Verifikasi',
                                  style: StyleTheme().stylePrimary.copyWith(
                                      fontSize: 14, color: Colors.green)),
                              style: ElevatedButton.styleFrom(
                                  surfaceTintColor: ColorTheme().whiteColor,
                                  side: const BorderSide(
                                      color: Colors.green, width: 2),
                                  shape: RoundedRectangleBorder(
                                    borderRadius: BorderRadius.circular(30),
                                  ),
                                  fixedSize: Size(
                                      MediaQuery.of(context).size.width, 30)),
                            ),
                          ),
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

  Future<bool> _showConfirmationDialog(BuildContext context) async {
    return await showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          icon: Icon(
            Icons.warning_rounded,
            size: 80,
            color: Colors.amber,
          ),
          title: const Text("Konfirmasi Aksi"),
          content: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            mainAxisSize: MainAxisSize.min,
            children: [
              const Text("Pastikan periksa data sebelum melakukan verifikasi."),
              SizedBox(height: 5),
              Text(
                "Anda yakin ingin melanjutkan aksi?",
                style: StyleTheme()
                    .styleBlack
                    .copyWith(fontWeight: FontWeight.w500, fontSize: 14),
              )
            ],
          ),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(false); // Kembali dengan nilai false
              },
              child: Text(
                "Batal",
                style: StyleTheme()
                    .stylePrimary
                    .copyWith(color: Colors.red, fontSize: 16),
              ),
            ),
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(true); // Kembali dengan nilai true
              },
              child: Text(
                "Verifikasi",
                style: StyleTheme().stylePrimary.copyWith(fontSize: 16),
              ),
            ),
          ],
        );
      },
    );
  }

  void _filter(BuildContext context) {
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
            actions: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  ElevatedButton(
                    onPressed: () {
                      if (formKeyUP.currentState!.validate()) {
                        setState(() {
                          selectedDesaValue = null;
                        });
                      }
                    },
                    style: ElevatedButton.styleFrom(
                        backgroundColor: ColorTheme().primaryColor,
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(15),
                        ),
                        fixedSize:
                            Size(MediaQuery.of(context).size.width * 0.27, 50)),
                    child: Text("Reset", style: StyleTheme().styleWhite),
                  ),
                  ElevatedButton(
                    onPressed: () {
                      Navigator.of(context).pop();
                    },
                    style: ElevatedButton.styleFrom(
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(15),
                        ),
                        fixedSize:
                            Size(MediaQuery.of(context).size.width * 0.27, 50)),
                    child: Text(
                      'Tutup',
                      style: StyleTheme().styleBlack,
                    ),
                  ),
                ],
              ),
            ],
          ),
        );
      },
    );
  }
}
