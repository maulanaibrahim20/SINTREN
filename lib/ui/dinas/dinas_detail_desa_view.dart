import 'package:flutter/material.dart';
import 'package:percent_indicator/linear_percent_indicator.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/admin/admin_padi_controller.dart';
import 'package:sintren_mobile/controllers/admin/admin_palawija_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:sintren_mobile/ui/components/example_chart.dart';
import 'package:sintren_mobile/ui/components/palawija_chart.dart';
import 'package:sintren_mobile/ui/components/progress_chart.dart';
import 'package:sintren_mobile/ui/dinas/detail_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/dropdown_button_component.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class DinasDetailDesaView extends StatefulWidget {
  const DinasDetailDesaView({super.key, this.desaId, this.desaName});

  final String? desaId;
  final String? desaName;

  @override
  State<DinasDetailDesaView> createState() => _DinasDetailDesaViewState();
}

class _DinasDetailDesaViewState extends State<DinasDetailDesaView>
    with TickerProviderStateMixin {
  final adminC = AdminController();
  TextEditingController search = TextEditingController();
  late int selectedTahun;
  int currentYear = DateTime.now().year;
  late List<int> years;
  late TabController _tabController;
  late TabController _tabProgressController;
  final List<String> _tabs = ['Padi', 'Palawija'];

  Future<dynamic> _fetchChartData(String type) async {
    if (type == 'Padi') {
      return await AdminPadiController().getDataGrafikPenyuluhanPadi(
          selectedTahun.toString(),
          desaId: widget.desaId);
    } else if (type == 'Palawija') {
      return await AdminPalawijaController().getDataGrafikPenyuluhanPalawija(
          selectedTahun.toString(),
          desaId: widget.desaId);
    }
  }

  @override
  void initState() {
    _tabController = TabController(length: _tabs.length, vsync: this);
    _tabProgressController = TabController(length: _tabs.length, vsync: this);
    selectedTahun = DateTime.now().year;
    years = List.generate(currentYear - 2009, (index) => 2010 + index);
    super.initState();
  }

  Card _trendLineChart(BuildContext context) {
    return Card(
      margin: EdgeInsets.symmetric(horizontal: 10.w),
      elevation: 3,
      color: ColorTheme().whiteColor,
      surfaceTintColor: ColorTheme().whiteColor,
      child: Container(
        decoration: BoxDecoration(
            gradient: ColorTheme().linearColor2,
            borderRadius: BorderRadius.circular(10)),
        child: Column(
          children: [
            TabBar(
              controller: _tabController,
              tabs: _tabs.map((String tab) {
                return Tab(text: tab);
              }).toList(),
              labelColor: ColorTheme().whiteColor,
              unselectedLabelColor: ColorTheme().whiteColor,
              indicatorColor: ColorTheme().whiteColor,
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
                          color: ColorTheme().whiteColor,
                          size: 30.r,
                        ),
                        SizedBox(width: 10.w),
                        Text(
                          "Grafik Pertanian $type", // Judul dinamis sesuai dengan jenis data
                          style: StyleTheme().styleWhite.copyWith(
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
                        color: ColorTheme().whiteColor,
                      ),
                    ),
                  ],
                ),
              ),
              Container(
                  margin: EdgeInsets.only(top: 20.h),
                  width: MediaQuery.of(context).size.width,
                  height: 207.h,
                  child: ExampleChart(data: data)),
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

  Card _progresPenyuluhan(BuildContext context) {
    return Card(
      margin: EdgeInsets.symmetric(horizontal: 15.w),
      elevation: 5.r,
      surfaceTintColor: ColorTheme().whiteColor,
      color: ColorTheme().whiteColor,
      child: Column(
        children: [
          SizedBox(height: 10.h),
          Text(
            "Grafik Penyuluhan Bulan Ini", // Judul dinamis sesuai dengan jenis data
            style: StyleTheme().stylePrimary.copyWith(
                  fontSize: 18.sp,
                  fontWeight: FontWeight.w500,
                ),
          ),
          Divider(thickness: 2.h),
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
                ProgressChart(desaId: widget.desaId)
                    .buildProgressChartTab('Padi'),
                // Tab untuk data Palawija
                ProgressChart(desaId: widget.desaId)
                    .buildProgressChartTab('Palawija'),
              ],
            ),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      appBar: AppBar(
        foregroundColor: ColorTheme().whiteColor,
        flexibleSpace: Container(
          decoration: BoxDecoration(gradient: ColorTheme().linearColor),
        ),
        title: Text(
          'Detail Desa ${UserController().toCamelCase(widget.desaName!)}',
          style: StyleTheme().styleWhite.copyWith(
                fontSize: 20.sp,
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
                  Icon(
                    Icons.error,
                    color: Colors.grey,
                    size: 50.sp,
                  ),
                  Text(
                    "Internal Server Error",
                    style: StyleTheme().styleBlack.copyWith(
                          fontSize: 18.sp,
                          fontWeight: FontWeight.w500,
                          color: Colors.grey,
                        ),
                  ),
                ],
              ),
            );
          } else {
            final historiList =
                (snapshot.data?[0] as List<HistoriPenyuluhanModel>)
                    .where((histori) {
              return histori.id == widget.desaId!;
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
                    Icon(
                      Icons.assignment,
                      color: Colors.grey,
                      size: 50.sp,
                    ),
                    Text(
                      "Penyuluhan Belum Dilakukan",
                      style: StyleTheme().styleBlack.copyWith(
                            fontSize: 18.sp,
                            fontWeight: FontWeight.w500,
                            color: Colors.grey,
                          ),
                    ),
                  ],
                ),
              );
            }
            return SingleChildScrollView(
              child: Padding(
                padding: EdgeInsets.symmetric(vertical: 10.h),
                child: Column(
                  children: [
                    _trendLineChart(context),
                    SizedBox(height: 10.h),
                    _progresPenyuluhan(context),
                    SizedBox(height: 10.h),
                    PalawijaChart().chart(desaId: widget.desaId),
                    SizedBox(height: 5.h),
                    ListView.builder(
                      physics: const NeverScrollableScrollPhysics(),
                      shrinkWrap: true,
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
                                  desaId: desa.id,
                                  desaName: desa.name,
                                ),
                              ),
                            );
                            setState(() {});
                          },
                          child: Card(
                            margin: EdgeInsets.symmetric(
                                horizontal: 15.w, vertical: 10.h),
                            elevation: 3,
                            color: ColorTheme().whiteColor,
                            surfaceTintColor: ColorTheme().whiteColor,
                            child: Column(
                              children: [
                                if (desa.totalTunggu > 0)
                                  Align(
                                    alignment: Alignment.topRight,
                                    child: Container(
                                      height: 30.h,
                                      width: 250.w,
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
                                        "${desa.totalTunggu} Data Belum Diverifikasi",
                                        style: StyleTheme().styleWhite.copyWith(
                                              fontSize: 14.sp,
                                              fontWeight: FontWeight.bold,
                                            ),
                                      ),
                                    ),
                                  ),
                                SizedBox(height: 10.h),
                                Padding(
                                  padding:
                                      EdgeInsets.symmetric(horizontal: 20.w),
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
                                        crossAxisAlignment:
                                            CrossAxisAlignment.start,
                                        children: [
                                          Text(
                                            "Desa ${UserController().toCamelCase(desa.name)}",
                                            style: StyleTheme()
                                                .stylePrimary
                                                .copyWith(
                                                  fontSize: 20.sp,
                                                  fontWeight: FontWeight.bold,
                                                ),
                                          ),
                                          Text(
                                            UserController()
                                                .convertDate(desa.date),
                                            style: StyleTheme()
                                                .styleBlack
                                                .copyWith(
                                                  fontWeight: FontWeight.bold,
                                                  color: Colors.grey[700],
                                                  fontSize: 14.sp,
                                                ),
                                          ),
                                        ],
                                      )
                                    ],
                                  ),
                                ),
                                SizedBox(height: 10.h),
                                Stack(
                                  children: [
                                    Divider(
                                        thickness: 2.sp, color: Colors.grey),
                                    Container(
                                      color: ColorTheme().whiteColor,
                                      margin: EdgeInsets.only(left: 20.w),
                                      padding: const EdgeInsets.symmetric(
                                          horizontal: 8.0),
                                      child: Text(
                                        "Progres bulan ini",
                                        style: StyleTheme().styleBlack.copyWith(
                                              color: Colors.black87,
                                            ),
                                      ),
                                    ),
                                  ],
                                ),
                                SizedBox(height: 10.h),
                                LinearPercentIndicator(
                                  width:
                                      MediaQuery.of(context).size.width - 30.w,
                                  animation: true,
                                  lineHeight: 30.h,
                                  animationDuration: 2000,
                                  percent:
                                      (desa.nilai / getLuasDesa(desa.id)) > 1
                                          ? 1
                                          : desa.nilai / getLuasDesa(desa.id),
                                  center: Text(
                                    "${((desa.nilai / getLuasDesa(desa.id)) * 100).toStringAsFixed(1)}% (${desa.nilai}/${getLuasDesa(desa.id)})",
                                    style: StyleTheme().styleWhite.copyWith(
                                          fontWeight: FontWeight.w500,
                                          fontSize: 14.sp,
                                        ),
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
                    ),
                  ],
                ),
              ),
            );
          }
        },
      ),
    );
  }
}
