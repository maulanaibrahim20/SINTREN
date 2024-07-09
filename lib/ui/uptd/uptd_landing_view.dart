import 'package:awesome_bottom_bar/awesome_bottom_bar.dart';
import 'package:awesome_bottom_bar/widgets/inspired/inspired.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/ui/uptd/uptd_desa_view.dart';
import 'package:sintren_mobile/ui/uptd/uptd_home_view.dart';
import 'package:sintren_mobile/ui/uptd/uptd_penugasan_view.dart';
import 'package:sintren_mobile/ui/uptd/uptd_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class UptdLandingView extends StatefulWidget {
  const UptdLandingView({super.key, required this.kecamatan});

  final String kecamatan;

  @override
  State<UptdLandingView> createState() => _UptdLandingViewState();
}

class _UptdLandingViewState extends State<UptdLandingView> {
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
            title: 'Desa',
          ),
          TabItem(
            icon: Icons.task_rounded,
            title: 'Penyuluhan',
          ),
          TabItem(
            icon: Icons.work_outlined,
            title: 'Penugasan',
          ),
        ],
        radius: 10.r,
        height: 40.h,
        iconSize: 22.h,
        titleStyle: StyleTheme().styleWhite.copyWith(fontSize: 12.sp),
        backgroundColor: ColorTheme().primaryColor,
        color: ColorTheme().whiteColor,
        colorSelected: ColorTheme().primaryColor,
        indexSelected: _tabIndex,
        onTap: (int index) {
          setState(() {
            _tabIndex = index;
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
          UptdHomeView(key: _pageKeys[0], kecamatan: widget.kecamatan),
          UptdDesaView(key: _pageKeys[1]),
          UptdPenyuluhanView(key: _pageKeys[2]),
          UptdPenugasanView(key: _pageKeys[3])
        ],
      ),
    );
  }
}
