import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';

class StyleTheme {
  final TextStyle stylePrimary =
      TextStyle(color: ColorTheme().primaryColor, fontSize: 12.sp);
  final TextStyle styleWhite =
      TextStyle(color: ColorTheme().whiteColor, fontSize: 12.sp);
  final TextStyle styleBlack = TextStyle(color: Colors.black, fontSize: 12.sp);
}
