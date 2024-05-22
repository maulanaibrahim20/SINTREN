import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';

class StyleTheme {
  final TextStyle stylePrimary =
      TextStyle(color: ColorTheme().primaryColor, fontSize: 12);
  final TextStyle styleWhite =
      TextStyle(color: ColorTheme().whiteColor, fontSize: 12);
  final TextStyle styleBlack = const TextStyle(color: Colors.black, fontSize: 12);
}
