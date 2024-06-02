import 'dart:developer';

import 'package:flutter/material.dart';
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
                preferredSize: const Size.fromHeight(60.0),
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
                      borderRadius: BorderRadius.circular(30.0),
                    ),
                    contentPadding:
                        const EdgeInsets.symmetric(horizontal: 16.0),
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
                      fontSize: 20,
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
        onPressed: () {
          setState(() {});
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
            final desaList = snapshot.data?[1] as List<LuasWilayahModel>;

            int getNilaiByDesaId(String desaId) {
              try {
                return historiList
                    .firstWhere((element) => element.desaId == desaId)
                    .nilai;
              } catch (e) {
                log('Desa with id $desaId not found');
                return 0;
              }
            }

            if (desaList.isEmpty) {
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
                      "Data Kosong",
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
                                Text(
                                  "Desa ${UserController().toCamelCase(desa.name)}",
                                  style: StyleTheme().stylePrimary.copyWith(
                                      fontSize: 20,
                                      fontWeight: FontWeight.bold),
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
                            percent: (getNilaiByDesaId(desa.id) /
                                        desa.totalLuasLahan) >
                                    1
                                ? 1
                                : getNilaiByDesaId(desa.id) /
                                    desa.totalLuasLahan,
                            center: Text(
                              "${((getNilaiByDesaId(desa.id) / desa.totalLuasLahan) * 100).toStringAsFixed(1)}% (${getNilaiByDesaId(desa.id)}/${desa.totalLuasLahan})",
                              style: StyleTheme().styleWhite.copyWith(
                                  fontWeight: FontWeight.w500, fontSize: 14),
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
    );
  }
}
