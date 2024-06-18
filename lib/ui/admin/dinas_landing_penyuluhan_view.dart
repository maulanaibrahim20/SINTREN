import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/ui/admin/detail_penyuluhan_dinas/penyuluhan_padi_view.dart';
import 'package:sintren_mobile/ui/admin/detail_penyuluhan_dinas/penyuluhan_palawija_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class DinasLandingPenyuluhanView extends StatefulWidget {
  const DinasLandingPenyuluhanView({super.key});

  @override
  State<DinasLandingPenyuluhanView> createState() =>
      _DinasLandingPenyuluhanViewState();
}

class _DinasLandingPenyuluhanViewState extends State<DinasLandingPenyuluhanView>
    with SingleTickerProviderStateMixin {
  final List<Tab> tabs = [
    const Tab(text: 'Padi'),
    const Tab(text: 'Palawija'),
  ];

  late TabController _tabController =
      TabController(length: tabs.length, vsync: this);

  @override
  void initState() {
    setState(() {});
    super.initState();
    _tabController = TabController(
      length: tabs.length,
      vsync: this,
      initialIndex: 0,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        centerTitle: false,
        foregroundColor: ColorTheme().whiteColor,
        flexibleSpace: Container(
          decoration: BoxDecoration(gradient: ColorTheme().linearColor),
        ),
        title: Text(
          'Data Penyuluhan',
          style: StyleTheme().styleWhite.copyWith(
                fontSize: 20.sp,
                fontWeight: FontWeight.w500,
              ),
        ),
        actions: [
          IconButton(
            icon: Icon(
              Icons.refresh_rounded,
              color: ColorTheme().whiteColor,
              size: 24.sp,
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
      body: Container(
        color: ColorTheme().bgColor,
        child: Column(
          children: [
            Container(
              color: ColorTheme().primaryColor,
              child: TabBar(
                controller: _tabController,
                tabs: tabs,
                labelColor: ColorTheme().whiteColor,
                labelStyle: StyleTheme()
                    .stylePrimary
                    .copyWith(fontSize: 16.sp, fontWeight: FontWeight.bold),
                unselectedLabelColor: Colors.grey,
                indicatorColor: ColorTheme().whiteColor,
                indicatorWeight: 2.0.w,
                indicatorSize: TabBarIndicatorSize.tab,
              ),
            ),
            Expanded(
              child: TabBarView(
                controller: _tabController,
                children: const [
                  PenyuluhanPadiView(),
                  PenyuluhanPalawijaView()
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }
}
