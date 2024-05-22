import 'package:flutter/material.dart';

class ColorTheme {
  final Color primaryColor = Colors.indigo[900]!;
  final Color secondaryColor = Colors.indigoAccent;
  final Color thirdColor = Colors.lightBlueAccent;
  final Color forthColor = Colors.blue[800]!;
  final Color whiteColor = Colors.white;
  final Color blackColor = Colors.black;
  final Color bgColor = Colors.grey[200]!;

  final LinearGradient linearColor = LinearGradient(
    colors: [Colors.blue[800]!, Colors.indigo[900]!],
    begin: Alignment.topCenter,
    end: Alignment.bottomCenter,
   );
}
