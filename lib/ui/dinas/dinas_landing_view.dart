import 'package:awesome_bottom_bar/awesome_bottom_bar.dart';
import 'package:awesome_bottom_bar/widgets/inspired/inspired.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/dinas/dinas_desa_view.dart';
import 'package:sintren_mobile/ui/dinas/dinas_home_view.dart';
import 'package:sintren_mobile/ui/dinas/dinas_kecamatan_view.dart';
import 'package:sintren_mobile/ui/dinas/dinas_landing_penyuluhan_view.dart';

class DinasLandingView extends StatefulWidget {
  const DinasLandingView({super.key});

  @override
  State<DinasLandingView> createState() => _DinasLandingViewState();
}

class _DinasLandingViewState extends State<DinasLandingView> {
  int _tabIndex = 0;

  // Generate keys to ensure widget rebuilding
  final List<UniqueKey> _pageKeys = [
    UniqueKey(),
    UniqueKey(),
    UniqueKey(),
    UniqueKey(),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      resizeToAvoidBottomInset: false,
      bottomNavigationBar: BottomBarInspiredInside(
        items: const [
          TabItem(
            icon: Icons.home_rounded,
            title: 'Beranda',
          ),
          TabItem(
            icon: Icons.villa_rounded,
            title: 'Kecamatan',
          ),
          TabItem(
            icon: Icons.villa_rounded,
            title: 'Desa',
          ),
          TabItem(
            icon: Icons.task_rounded,
            title: 'Penyuluhan',
          ),
        ],
        radius: 10.r,
        height: 42.h,
        iconSize: 22.h,
        titleStyle: StyleTheme().styleWhite.copyWith(fontSize: 12.sp),
        backgroundColor: ColorTheme().primaryColor,
        color: ColorTheme().whiteColor,
        colorSelected: ColorTheme().primaryColor,
        indexSelected: _tabIndex,
        onTap: (int index) {
          setState(() {
            _tabIndex = index;
            // Generate a new key for the selected page
            _pageKeys[index] = UniqueKey();
          });
        },
        chipStyle:
            ChipStyle(convexBridge: true, background: ColorTheme().whiteColor),
        itemStyle: ItemStyle.circle,
        animated: false,
      ),
      body: IndexedStack(
        index: _tabIndex,
        children: [
          DinasHomeView(key: _pageKeys[0]),
          DinasKecamatanView(key: _pageKeys[1]),
          DinasDesaView(key: _pageKeys[2]),
          DinasLandingPenyuluhanView(
            key: _pageKeys[3],
          ),
        ],
      ),
    );
  }
}
