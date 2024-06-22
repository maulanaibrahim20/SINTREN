import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class SplashScreen extends StatefulWidget {
  final ValueNotifier<String> statusNotifier;

  const SplashScreen({super.key, required this.statusNotifier});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
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
                    heightFactor: 1,
                    child: Image.asset(
                      'assets/images/pertanian.png',
                      width: 170.w,
                      height: 200.h,
                      fit: BoxFit.fill,
                    ),
                  ),
                ),
                Text(
                  "SINTREN",
                  style: StyleTheme()
                      .styleWhite
                      .copyWith(fontSize: 32.sp, fontWeight: FontWeight.bold),
                ),
              ],
            ),
            Align(
              alignment: Alignment.bottomCenter,
              child: Padding(
                padding: EdgeInsets.all(20.0.r),
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    ValueListenableBuilder<String>(
                      valueListenable: widget.statusNotifier,
                      builder: (context, status, child) {
                        bool isError = status.startsWith('Error:');
                        return Column(
                          children: [
                            Text(
                              isError ? status : 'Loading: $status',
                              style: StyleTheme().styleWhite.copyWith(
                                    color: isError ? Colors.red : Colors.white,
                                    fontSize: 14.sp,
                                  ),
                              textAlign: TextAlign.center,
                            ),
                            SizedBox(height: 10.h),
                            if (isError)
                              const SizedBox.shrink()
                            else
                              LinearProgressIndicator(
                                backgroundColor: Colors.white.withOpacity(0.5),
                                valueColor: const AlwaysStoppedAnimation<Color>(
                                    Colors.white),
                              ),
                          ],
                        );
                      },
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
