import 'package:flutter/material.dart';
import 'package:percent_indicator/linear_percent_indicator.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:sintren_mobile/ui/admin/detail_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class AdminDetailDesaView extends StatefulWidget {
  const AdminDetailDesaView({super.key, this.desaId, this.desaName});

  final String? desaId;
  final String? desaName;

  @override
  State<AdminDetailDesaView> createState() => _AdminDetailDesaViewState();
}

class _AdminDetailDesaViewState extends State<AdminDetailDesaView> {
  final adminC = AdminController();
  TextEditingController search = TextEditingController();

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      appBar: AppBar(
        foregroundColor: ColorTheme().whiteColor,
        title: Text(
          'Detail Desa ${UserController().toCamelCase(widget.desaName!)}',
          style: StyleTheme().styleWhite.copyWith(
                fontSize: 20,
                fontWeight: FontWeight.w500,
              ),
        ),
        backgroundColor: ColorTheme().primaryColor,
      ),
      floatingActionButton: FloatingActionButton(
        shape: const CircleBorder(),
        heroTag: 'sinkron_detail',
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
          adminC.getHistoriPenyuluhan(isMonthNow: false),
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
                (snapshot.data?[0] as List<HistoriPenyuluhanModel>)
                    .where((histori) {
              return histori.desaId == widget.desaId!;
            });
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
                      "Penyuluhan Belum Dilakukan",
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
                  var displayList = historiList.toList();
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
                          horizontal: 15, vertical: 10),
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
                                width: 250,
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
                                  "${desa.totalTunggu} Data Belum Diverifikasi",
                                  style: StyleTheme().styleWhite.copyWith(
                                      fontSize: 14,
                                      fontWeight: FontWeight.bold),
                                ),
                              ),
                            ),
                          const SizedBox(height: 10),
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
                                      UserController().convertDate(desa.date),
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
                            width: MediaQuery.of(context).size.width - 30,
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
