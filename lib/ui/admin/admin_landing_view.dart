import 'package:awesome_bottom_bar/awesome_bottom_bar.dart';
import 'package:awesome_bottom_bar/widgets/inspired/inspired.dart';
import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/admin/admin_desa_view.dart';
import 'package:sintren_mobile/ui/admin/admin_home_view.dart';
import 'package:sintren_mobile/ui/admin/admin_penugasan_view.dart';
import 'package:sintren_mobile/ui/admin/admin_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';

class AdminLandingView extends StatefulWidget {
  const AdminLandingView({super.key});

  @override
  State<AdminLandingView> createState() => _AdminLandingViewState();
}

class _AdminLandingViewState extends State<AdminLandingView> {
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
        radius: 10,
        height: 40,
        iconSize: 24,
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
          AdminHomeView(key: _pageKeys[0]),
          AdminDesaView(key: _pageKeys[1]),
          AdminPenyuluhanView(key: _pageKeys[2]),
          AdminPenugasanView(key: _pageKeys[3])
        ],
      ),
    );
  }
}
