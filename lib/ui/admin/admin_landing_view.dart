import 'package:awesome_bottom_bar/awesome_bottom_bar.dart';
import 'package:awesome_bottom_bar/widgets/inspired/inspired.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/ui/admin/admin_desa_view.dart';
import 'package:sintren_mobile/ui/admin/admin_home_view.dart';
import 'package:sintren_mobile/ui/admin/admin_penugasan_view.dart';
import 'package:sintren_mobile/ui/admin/admin_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/admin/dinas_landing_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class AdminLandingView extends StatefulWidget {
  const AdminLandingView({super.key});

  @override
  State<AdminLandingView> createState() => _AdminLandingViewState();
}

class _AdminLandingViewState extends State<AdminLandingView> {
  int _tabIndex = 0;
  late bool isDinas;

  // Generate keys to ensure widget rebuilding
  final List<UniqueKey> _pageKeys = [
    UniqueKey(),
    UniqueKey(),
    UniqueKey(),
    UniqueKey(),
  ];

  Future<void> _initializedData() async {
    String? role = await UserLoginModel().getRole();
    if (role == "PERTANIAN") {
      isDinas = true;
    } else {
      isDinas = false;
    }
  }

  @override
  Widget build(BuildContext context) {
    return FutureBuilder(
        future: _initializedData(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return const Center(child: Text('Error loading data'));
          } else {
            return Scaffold(
              resizeToAvoidBottomInset: false,
              bottomNavigationBar: BottomBarInspiredInside(
                items: [
                  const TabItem(
                    icon: Icons.home_rounded,
                    title: 'Beranda',
                  ),
                  const TabItem(
                    icon: Icons.villa_rounded,
                    title: 'Desa',
                  ),
                  const TabItem(
                    icon: Icons.task_rounded,
                    title: 'Penyuluhan',
                  ),
                  if (!isDinas) ...[
                    const TabItem(
                      icon: Icons.work_outlined,
                      title: 'Penugasan',
                    ),
                  ]
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
                chipStyle: ChipStyle(
                    convexBridge: true, background: ColorTheme().whiteColor),
                itemStyle: ItemStyle.circle,
                animated: false,
              ),
              body: IndexedStack(
                index: _tabIndex,
                children: [
                  AdminHomeView(key: _pageKeys[0]),
                  AdminDesaView(key: _pageKeys[1]),
                  if (isDinas) ...[
                    DinasLandingPenyuluhanView(
                      key: _pageKeys[2],
                    ),
                  ] else ...[
                    AdminPenyuluhanView(key: _pageKeys[2]),
                    AdminPenugasanView(key: _pageKeys[3])
                  ],
                ],
              ),
            );
          }
        });
  }
}
