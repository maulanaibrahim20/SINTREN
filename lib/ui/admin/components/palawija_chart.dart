import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/ui/admin/components/indicator.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';

class PalawijaChart {
  final List<Color> _colorList = [
    Colors.green,
    Colors.blue,
    Colors.indigo,
    Colors.purple,
    Colors.green.shade900,
    Colors.blue.shade900,
    Colors.indigo.shade900,
    Colors.purple.shade900,
    Colors.green.shade500,
    Colors.blue.shade500,
    Colors.indigo.shade500,
    Colors.purple.shade500,
    Colors.green.shade300,
    Colors.blue.shade300,
    Colors.indigo.shade300,
    Colors.purple.shade300,
  ];

  Column indicator(List<Map<String, dynamic>> list) {
    return Column(
      mainAxisAlignment: MainAxisAlignment.end,
      crossAxisAlignment: CrossAxisAlignment.start,
      children: List.generate(list.length, (index) {
        return Column(
          children: [
            Indicator(
              color: _colorList[index % _colorList.length],
              text: list[index]['palawija_name'],
              isSquare: true,
            ),
            SizedBox(
              height: 4.h,
            ),
          ],
        );
      }),
    );
  }

  List<PieChartSectionData> showingSections(List<Map<String, dynamic>> list) {
    double totalValue = list.fold(0, (sum, item) => sum + item['nilai']);

    return List.generate(list.length, (index) {
      const fontSize = 16.0;
      const radius = 60.0;
      const shadows = [Shadow(color: Colors.black, blurRadius: 2)];
      final percentage =
          (list[index]['nilai'] / totalValue * 100).toStringAsFixed(1);

      return PieChartSectionData(
        color: _colorList[index % _colorList.length],
        value: list[index]['nilai'],
        title: '$percentage%',
        radius: radius,
        titleStyle: TextStyle(
          fontSize: fontSize,
          fontWeight: FontWeight.bold,
          color: ColorTheme().whiteColor,
          shadows: shadows,
        ),
        borderSide: BorderSide(color: ColorTheme().whiteColor),
      );
    });
  }
}
