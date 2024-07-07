import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/admin/admin_padi_controller.dart';
import 'package:sintren_mobile/controllers/admin/admin_palawija_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/ui/components/palawija_chart.dart';
import 'package:sintren_mobile/ui/components/progress_chart.dart';
import 'package:sintren_mobile/ui/components/trend_chart.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/dropdown_button_component.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/ui/dinas/dinas_desa_view.dart';

class DinasDetailKecamatanView extends StatefulWidget {
  const DinasDetailKecamatanView(
      {super.key, this.kecamatanId, this.kecamatanName});

  final String? kecamatanId;
  final String? kecamatanName;

  @override
  State<DinasDetailKecamatanView> createState() =>
      _DinasDetailKecamatanViewState();
}

class _DinasDetailKecamatanViewState extends State<DinasDetailKecamatanView>
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
          kecamatanId: widget.kecamatanId);
    } else if (type == 'Palawija') {
      return await AdminPalawijaController().getDataGrafikPenyuluhanPalawija(
          selectedTahun.toString(),
          kecamatanId: widget.kecamatanId);
    }
  }

  @override
  void initState() {
    _tabController = TabController(length: _tabs.length, vsync: this);
    _tabProgressController = TabController(length: _tabs.length, vsync: this);
    selectedTahun = DateTime.now().year;
    super.initState();
  }

  Card _trendLineChart(BuildContext context) {
    return Card(
      margin: EdgeInsets.symmetric(horizontal: 15.w),
      elevation: 3,
      color: ColorTheme().whiteColor,
      surfaceTintColor: ColorTheme().whiteColor,
      child: Column(
        children: [
          TabBar(
            controller: _tabController,
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
                          color: ColorTheme().primaryColor,
                          size: 30.r,
                        ),
                        SizedBox(width: 10.w),
                        Text(
                          "Trend $type", // Judul dinamis sesuai dengan jenis data
                          style: StyleTheme().stylePrimary.copyWith(
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
                        color: ColorTheme().primaryColor,
                      ),
                    ),
                  ],
                ),
              ),
              Container(
                margin: EdgeInsets.only(top: 20.h),
                width: MediaQuery.of(context).size.width,
                height: 207.h,
                child: Column(
                  children: [
                    SizedBox(
                      height: 180.h,
                      width: 330.w,
                      child: LineChart(
                        TrendChart(data: data!).mainData(),
                      ),
                    ),
                    SizedBox(height: 3.h),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Row(
                          children: [
                            Container(
                              width: 10.w,
                              height: 10.h,
                              color: ColorTheme().secondaryColor,
                            ),
                            SizedBox(width: 5.w),
                            Text(
                              "Data Tanam",
                              style: TextStyle(
                                color: Colors.indigo,
                                fontSize: 12.sp,
                              ),
                            ),
                          ],
                        ),
                        SizedBox(width: 10.w),
                        Row(
                          children: [
                            Container(
                                width: 10.w,
                                height: 10.h,
                                color: Colors.amber[900]),
                            SizedBox(width: 5.w),
                            Text(
                              "Data Panen",
                              style: TextStyle(
                                  color: ColorTheme().primaryColor,
                                  fontSize: 12.sp),
                            ),
                          ],
                        ),
                        SizedBox(width: 5.w),
                        Row(
                          children: [
                            Container(
                                width: 10.w,
                                height: 10.h,
                                color: Colors.red[900]),
                            SizedBox(width: 5.w),
                            Text(
                              "Data Puso/Rusak",
                              style: TextStyle(
                                  color: ColorTheme().primaryColor,
                                  fontSize: 12.sp),
                            ),
                          ],
                        ),
                      ],
                    ),
                  ],
                ),
              ),
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
                ProgressChart(kecamatanId: widget.kecamatanId)
                    .buildProgressChartTab('Padi'),
                ProgressChart(kecamatanId: widget.kecamatanId)
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
        title: Text(
          'Detail Kecamatan ${UserController().toCamelCase(widget.kecamatanName!)}',
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
      body: FutureBuilder<List<HistoriPenyuluhanModel>>(
        future:
            adminC.getHistoriPenyuluhan(isMonthNow: false, isKecamatan: true),
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
            final historiList = (snapshot.data as List<HistoriPenyuluhanModel>)
                .where((histori) {
              return histori.id == widget.kecamatanId!;
            });

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
                    InkWell(
                      onTap: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                            builder: (_) => DinasDesaView(
                              kecamatanId: widget.kecamatanId,
                            ),
                          ),
                        );
                      },
                      child: Container(
                        margin: EdgeInsets.symmetric(horizontal: 5.w),
                        width: MediaQuery.of(context).size.width,
                        height: 50.h,
                        decoration: BoxDecoration(
                          borderRadius: BorderRadius.circular(10.r),
                          gradient: ColorTheme().linearColor,
                          border: Border.all(
                            color: ColorTheme().grey,
                          ),
                        ),
                        child: Padding(
                          padding: EdgeInsets.symmetric(horizontal: 20.w),
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Row(
                                mainAxisAlignment: MainAxisAlignment.center,
                                children: [
                                  Icon(Icons.list,
                                      color: ColorTheme().whiteColor),
                                  SizedBox(width: 10.w),
                                  Text(
                                    'List Desa Kecamatan ${widget.kecamatanName}',
                                    style: TextStyle(
                                      fontWeight: FontWeight.bold,
                                      fontSize: 14.sp,
                                      color: ColorTheme().whiteColor,
                                    ),
                                  )
                                ],
                              ),
                              Icon(Icons.arrow_right,
                                  color: ColorTheme().whiteColor),
                            ],
                          ),
                        ),
                      ),
                    ),
                    SizedBox(height: 10.h),
                    _progresPenyuluhan(context),
                    SizedBox(height: 10.h),
                    PalawijaChart().chart(kecamatanId: widget.kecamatanId),
                    SizedBox(height: 5.h),
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
