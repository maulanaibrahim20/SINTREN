import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:intl/date_symbol_data_local.dart';
import 'package:sintren_mobile/services/padi_service.dart';
import 'package:sintren_mobile/services/palawija_service.dart';
import 'package:sintren_mobile/services/user_service.dart';
import 'package:sintren_mobile/ui/admin/admin_landing_view.dart';
import 'package:sintren_mobile/ui/login_view.dart';
import 'package:sintren_mobile/ui/penyuluh/penyuluh_home_view.dart';
import 'package:sintren_mobile/ui/splash_screen.dart';

class InitializationWrapper extends StatefulWidget {
  final bool? isLogin;
  final String? role;

  const InitializationWrapper({super.key, this.isLogin, this.role});

  @override
  State<InitializationWrapper> createState() => _InitializationWrapperState();
}

class _InitializationWrapperState extends State<InitializationWrapper> {
  final ValueNotifier<String> statusNotifier =
      ValueNotifier<String>('Memulai aplikasi...');
  bool _initializationError = false;

  Future<void> _initializeData() async {
    statusNotifier.value = 'Memulai inisialisasi...';
    await initializeDateFormatting('id_ID', null);

    if (widget.role == "PENYULUH") {
      statusNotifier.value = 'Mendapatkan data pengairan...';
      await PadiService().getPengairan();

      statusNotifier.value = 'Mendapatkan data padi...';
      await PadiService().getPadi();

      statusNotifier.value = 'Mendapatkan data palawija...';
      await PalawijaService().getPalawija();
    }

    statusNotifier.value = 'Mendapatkan data desa...';
    await UserService().getDataPenyuluhanDesa();

    statusNotifier.value = 'Mendapatkan data penyuluhan...';
    await PadiService().getDetailPadiByUser();
    await PalawijaService().getDetailPalawijaByUser();

    statusNotifier.value = 'Selesai inisialisasi';
  }

  Future<void> _initializeDataWithTimeout() async {
    try {
      await _initializeData().timeout(const Duration(minutes: 1));
    } catch (e) {
      setState(() {
        _initializationError = true;
      });
      _showRetryDialog();
    }
  }

  @override
  void initState() {
    super.initState();
    _initializeDataWithTimeout();
  }

  void _showRetryDialog() {
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) {
        return AlertDialog(
          title: const Text('Kesalahan'),
          content: const Text(
              'Proses inisialisasi gagal. Pastikan koneksi internet tersedia'),
          actions: [
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
                setState(() {
                  _initializationError = false;
                });
                _initializeDataWithTimeout();
              },
              child: const Text('Coba Lagi'),
            ),
            TextButton(
              onPressed: () {
                Navigator.of(context).pop();
                SystemNavigator.pop();
              },
              child: const Text('Tutup Aplikasi'),
            ),
          ],
        );
      },
    );
  }

  @override
  Widget build(BuildContext context) {
    if (_initializationError) {
      return SplashScreen(statusNotifier: statusNotifier);
    }

    return FutureBuilder<void>(
      future: _initializeDataWithTimeout(),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return SplashScreen(statusNotifier: statusNotifier);
        } else if (snapshot.hasError) {
          _showRetryDialog();
          return SplashScreen(statusNotifier: statusNotifier);
        } else {
          if (widget.isLogin ?? false) {
            if (widget.role == "PENYULUH") {
              return const PenyuluhHomeView();
            } else if (widget.role == "PERTANIAN" || widget.role == "UPTD") {
              return const AdminLandingView();
            }
          }
          return const LoginView();
        }
      },
    );
  }
}
