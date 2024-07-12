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

  final LinearGradient linearColorWhite = const LinearGradient(
    colors: [Colors.white, Colors.white54],
    begin: Alignment.topCenter,
    end: Alignment.bottomCenter,
  );

  // final Color buttonBorderColor1 = const Color.fromRGBO(133, 118, 255, 1);
  final Color buttonBorderColor1 = const Color.fromARGB(255, 25, 135, 84);
  final LinearGradient buttonColor1 = const LinearGradient(
    // colors: [Color.fromRGBO(133, 118, 255, 1), Color.fromRGBO(28, 22, 120, 1)],
    colors: [Color.fromARGB(255, 3, 185, 100), Color.fromRGBO(25, 135, 84, 1)],
    begin: Alignment.topCenter,
    end: Alignment.bottomCenter,
  );

  // final Color buttonBorderColor2 = const Color.fromRGBO(123, 201, 255, 1);
  final Color buttonBorderColor2 = Colors.grey;
  final LinearGradient buttonColor2 = const LinearGradient(
    // colors: [Color.fromRGBO(123, 201, 255, 1), Color.fromRGBO(28, 22, 120, 1)],
    colors: [Colors.white, Colors.white],
    begin: Alignment.topCenter,
    end: Alignment.bottomCenter,
  );

  final Color linearBorderColor2 = const Color.fromARGB(255, 25, 135, 84);
  final LinearGradient linearColor2 = LinearGradient(
    colors: [Colors.green[200]!, Colors.green[400]!],
    begin: Alignment.topCenter,
    end: Alignment.bottomCenter,
  );

  final LinearGradient progressColor = LinearGradient(
    colors: [
      Colors.greenAccent[700]!,
      Colors.greenAccent[400]!,
      Colors.greenAccent
    ],
    begin: Alignment.bottomRight,
    end: Alignment.topLeft,
  );

  final Color grey = Colors.grey[600]!;
  final LinearGradient linearColorGrey = LinearGradient(
    colors: [Colors.white54, Colors.grey[600]!],
    begin: Alignment.topCenter,
    end: Alignment.bottomCenter,
  );
}
