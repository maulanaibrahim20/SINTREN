import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/admin/admin_desa_view.dart';
import 'package:sintren_mobile/ui/admin/admin_home_view.dart';
import 'package:sintren_mobile/ui/admin/admin_padi_view.dart';
import 'package:sintren_mobile/ui/admin/admin_palawija_view.dart';
import 'package:sintren_mobile/ui/admin/admin_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';

class AdminLandingView extends StatefulWidget {
  const AdminLandingView({super.key});

  @override
  State<AdminLandingView> createState() => _AdminLandingViewState();
}

class _AdminLandingViewState extends State<AdminLandingView> {
  int _tabIndex = 0; // State untuk menyimpan indeks tab

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      resizeToAvoidBottomInset: false,
      bottomNavigationBar: buildBottomNavigationMenu(context),
      body: IndexedStack(
        index: _tabIndex,
        children: const [
          AdminHomeView(),
          AdminPadiView(),
          AdminPalawijaView(),
          AdminDesaView(),
          AdminPenyuluhanView(),
        ],
      ),
    );
  }

  Widget buildBottomNavigationMenu(BuildContext context) {
    return MediaQuery(
      data: MediaQuery.of(context)
          .copyWith(textScaler: const TextScaler.linear(1.0)),
      child: BottomNavigationBar(
        landscapeLayout: BottomNavigationBarLandscapeLayout.spread,
        type: BottomNavigationBarType.fixed,
        showUnselectedLabels: true,
        showSelectedLabels: true,
        elevation: 2,
        onTap: (index) {
          setState(() {
            _tabIndex = index; // Perbarui indeks tab saat tab dipilih
          });
        },
        currentIndex: _tabIndex, // Tentukan indeks yang aktif
        backgroundColor: ColorTheme().primaryColor,
        unselectedItemColor: ColorTheme().whiteColor.withOpacity(0.5),
        selectedItemColor: ColorTheme().whiteColor,
        unselectedLabelStyle: TextStyle(
          color: ColorTheme().whiteColor.withOpacity(0.5),
          fontWeight: FontWeight.w500,
          fontSize: 12,
        ),
        selectedLabelStyle: TextStyle(
          color: ColorTheme().whiteColor,
          fontWeight: FontWeight.w500,
          fontSize: 12,
        ),
        items: [
          BottomNavigationBarItem(
            icon: const Icon(
              Icons.home,
              size: 30.0,
            ),
            label: 'Beranda',
            backgroundColor: ColorTheme().primaryColor,
          ),
          BottomNavigationBarItem(
            icon: const Icon(
              Icons.drafts,
              size: 30.0,
            ),
            label: 'Padi',
            backgroundColor: ColorTheme().primaryColor,
          ),
          BottomNavigationBarItem(
            icon: const Icon(
              Icons.note,
              size: 30.0,
            ),
            label: 'Palawija',
            backgroundColor: ColorTheme().primaryColor,
          ),
          BottomNavigationBarItem(
            icon: const Icon(
              Icons.villa,
              size: 30.0,
            ),
            label: 'Desa',
            backgroundColor: ColorTheme().primaryColor,
          ),
          BottomNavigationBarItem(
            icon: const Icon(
              Icons.account_circle,
              size: 30.0,
            ),
            label: 'Penyuluhan',
            backgroundColor: ColorTheme().primaryColor,
          ),
        ],
      ),
    );
  }
}
