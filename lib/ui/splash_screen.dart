import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class SplashScreen extends StatelessWidget {
  final ValueNotifier<String> statusNotifier;

  const SplashScreen({super.key, required this.statusNotifier});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Container(
        decoration: BoxDecoration(
          gradient: ColorTheme().linearColor,
        ),
        child: Stack(
          children: [
            Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                ClipRect(
                  child: Align(
                    alignment: Alignment.center,
                    heightFactor: 0.5,
                    child: Image.asset(
                      'assets/images/pertanian.png',
                      width: 200,
                      height: 200,
                      fit: BoxFit.fill,
                    ),
                  ),
                ),
                Text(
                  "SINTREN",
                  style: StyleTheme()
                      .styleWhite
                      .copyWith(fontSize: 32, fontWeight: FontWeight.bold),
                ),
              ],
            ),
            Align(
              alignment: Alignment.bottomCenter,
              child: Padding(
                padding: const EdgeInsets.all(20.0),
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    ValueListenableBuilder<String>(
                      valueListenable: statusNotifier,
                      builder: (context, status, child) {
                        return Text(
                          status.startsWith('Error:')
                              ? status
                              : 'Loading: $status',
                          style: StyleTheme().styleWhite.copyWith(
                                color: status.startsWith('Error:')
                                    ? Colors.red
                                    : Colors.white,
                              ),
                          textAlign: TextAlign.center,
                        );
                      },
                    ),
                    const SizedBox(height: 10),
                    LinearProgressIndicator(
                      backgroundColor: Colors.white.withOpacity(0.5),
                      valueColor: const AlwaysStoppedAnimation<Color>(Colors.white),
                    ),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
