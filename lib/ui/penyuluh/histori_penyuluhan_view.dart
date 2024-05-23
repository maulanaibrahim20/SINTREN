import 'package:flutter/material.dart';
import 'package:percent_indicator/linear_percent_indicator.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class HistoriPenyuluhanView extends StatefulWidget {
  const HistoriPenyuluhanView({super.key});

  @override
  State<HistoriPenyuluhanView> createState() => _HistoriPenyuluhanViewState();
}

class _HistoriPenyuluhanViewState extends State<HistoriPenyuluhanView> {
  final userC = UserController();
  late Future<List<dynamic>> combinedFuture;

  Future<void> _initializeData() async {
    combinedFuture = Future.wait([
      userC.getHistory(),
      userC.getLuasLahanDesa(),
    ]);
  }

  @override
  void initState() {
    _initializeData();
    super.initState();
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
          'Histori Penyuluhan',
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
              setState(
                () {},
              );
            },
          ),
        ],
        backgroundColor: ColorTheme().primaryColor,
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {},
        backgroundColor: ColorTheme().primaryColor,
        foregroundColor: ColorTheme().whiteColor,
        child: const Icon(
          Icons.filter_list,
        ),
      ),
      body: FutureBuilder<List<dynamic>>(
        future: combinedFuture,
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
                itemCount: historiList.length,
                itemBuilder: (BuildContext context, int index) {
                  HistoriPenyuluhanModel desa = historiList[index];
                  return GestureDetector(
                    onTap: () {
                      // Navigator.push(
                      //     context,
                      //     MaterialPageRoute(
                      //         builder: (_) => const DetailDesaView()));
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
                                          fontWeight: FontWeight.w500,
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
                            percent: desa.nilai / getLuasDesa(desa.desaId),
                            center: Text(
                              "${(desa.nilai / getLuasDesa(desa.desaId)) * 100}%",
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
