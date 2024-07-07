import 'dart:developer';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/penyuluh/penyuluh_controller.dart';
import 'package:sintren_mobile/ui/dinas/dinas_landing_view.dart';
import 'package:sintren_mobile/ui/uptd/uptd_landing_view.dart';
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
  bool isDialogShown = false;

  Future<void> _initializeDataWithTimeout() async {
    try {
      if (widget.role == "PENYULUH") {
        await PenyuluhController()
            .synchronizeData(statusNotifier)
            .timeout(const Duration(minutes: 5));
      } else if (widget.role == "PERTANIAN" || widget.role == "UPTD") {
        await AdminController()
            .synchronizeData(statusNotifier)
            .timeout(const Duration(minutes: 5));
      }
    } catch (e) {
      log("error initialized: $e");
      setState(() {
        _initializationError = true;
      });
      if (!isDialogShown) {
        isDialogShown = true;
        _showRetryDialog();
      }
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
                  isDialogShown = false;
                  _initializationError = false;
                });
                _initializeDataWithTimeout();
              },
              child: const Text('Coba Lagi'),
            ),
            TextButton(
              onPressed: () {
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
          return SplashScreen(statusNotifier: statusNotifier);
        } else {
          if (widget.isLogin ?? false) {
            if (widget.role == "PENYULUH") {
              return const PenyuluhHomeView();
            } else if (widget.role == "UPTD") {
              return const UptdLandingView();
            } else if (widget.role == "PERTANIAN") {
              return const DinasLandingView();
            }
          }
          return const LoginView();
        }
      },
    );
  }
}
