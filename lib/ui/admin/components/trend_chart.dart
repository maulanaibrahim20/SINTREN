import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/models/trend_grafik_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class TrendChart {
  final TrendGrafik data;

  TrendChart({required this.data});

  final List<Color> tanamGradientColors = [
    Colors.deepPurple,
    Colors.indigo,
  ];

  final List<Color> panenGradientColors = [
    Colors.amber[900]!,
    Colors.amberAccent,
  ];

  final List<Color> pusoRusakGradientColors = [
    Colors.red[900]!,
    Colors.redAccent[400]!
  ];

  List<double> get tanamData =>
      data.allData.map((item) => item['tanam'] ?? 0).toList();
  List<double> get panenData =>
      data.allData.map((item) => item['panen'] ?? 0).toList();
  List<double> get pusoRusakData =>
      data.allData.map((item) => item['puso/rusak'] ?? 0).toList();
  List<String> get labels => [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'Mei',
        'Jun',
        'Jul',
        'Agt',
        'Sep',
        'Okt',
        'Nov',
        'Des',
      ];

  Widget bottomTitleWidgets(double value, TitleMeta meta) {
    TextStyle style = TextStyle(
      fontSize: 12.sp, // Add .sp for font size
      color: ColorTheme().primaryColor,
    );
    Widget text;
    int monthIndex = value.toInt();
    if (monthIndex >= 0 && monthIndex < labels.length) {
      text = Text(labels[monthIndex], style: style);
    } else {
      text = Text('', style: style);
    }

    return SideTitleWidget(
      axisSide: meta.axisSide,
      child: text,
    );
  }

  Widget leftTitleWidgets(double value, TitleMeta meta) {
    TextStyle style = TextStyle(
      fontWeight: FontWeight.w500,
      fontSize: 12.sp,
      color: ColorTheme().primaryColor,
    );
    String text;
    if (value % interval == 0) {
      text = '${value ~/ 1000}K';
    } else {
      return Container();
    }

    return Text(text, style: style, textAlign: TextAlign.left);
  }

  double interval = 1.0;

  LineChartData mainData() {
    double maxY = [tanamData, panenData, pusoRusakData]
        .expand((i) => i)
        .reduce((a, b) => a > b ? a : b);
    double minY = [tanamData, panenData, pusoRusakData]
        .expand((i) => i)
        .reduce((a, b) => a < b ? a : b);

    // Adjusting maxY and minY to be a multiple of 5000 for better display
    maxY = ((maxY / 5000).ceil() * 5000).toDouble();
    minY = ((minY / 5000).floor() * 5000).toDouble();

    // Calculate interval based on the range and desired number of intervals
    if (maxY > minY) {
      interval = ((maxY - minY) / 5).ceil().toDouble();
    } else {
      interval = 1.0; // Set a default interval if maxY == minY
    }

    return LineChartData(
      backgroundColor: Colors.white,
      lineTouchData: LineTouchData(
        touchTooltipData: LineTouchTooltipData(
          getTooltipItems: (List<LineBarSpot> touchedSpots) {
            return touchedSpots.map((spot) {
              final dataType = spot.barIndex == 0
                  ? 'Tanam'
                  : spot.barIndex == 1
                      ? 'Panen'
                      : 'Puso/Rusak';
              final textStyle = spot.barIndex == 0
                  ? StyleTheme()
                      .styleWhite
                      .copyWith(fontWeight: FontWeight.w500)
                  : spot.barIndex == 1
                      ? StyleTheme()
                          .styleWhite
                          .copyWith(fontWeight: FontWeight.bold)
                      : StyleTheme().styleWhite;
              return LineTooltipItem(
                '$dataType: ${spot.y.toStringAsFixed(2)}',
                textStyle,
              );
            }).toList();
          },
        ),
      ),
      gridData: FlGridData(
        show: true,
        drawVerticalLine: true,
        horizontalInterval: interval,
        verticalInterval: 1,
        getDrawingHorizontalLine: (value) {
          return FlLine(
            color: Colors.grey[200],
            strokeWidth: 1.w, // Add .w for stroke width
          );
        },
        getDrawingVerticalLine: (value) {
          return FlLine(
            color: Colors.grey[200],
            strokeWidth: 1.w, // Add .w for stroke width
          );
        },
      ),
      titlesData: FlTitlesData(
        show: true,
        rightTitles: const AxisTitles(
          sideTitles: SideTitles(showTitles: false),
        ),
        topTitles: const AxisTitles(
          sideTitles: SideTitles(showTitles: false),
        ),
        bottomTitles: AxisTitles(
          sideTitles: SideTitles(
            showTitles: true,
            reservedSize: 30.h, // Add .h for reserved size
            interval: 1,
            getTitlesWidget: bottomTitleWidgets,
          ),
        ),
        leftTitles: AxisTitles(
          sideTitles: SideTitles(
            showTitles: true,
            interval: interval,
            getTitlesWidget: leftTitleWidgets,
            reservedSize: 42.w, // Add .w for reserved size
          ),
        ),
      ),
      borderData: FlBorderData(
        show: true,
        border: Border.all(
          color: Colors.grey,
          width: 0.w, // Add .w for border width
        ),
      ),
      minX: 0,
      maxX: (labels.length - 1).toDouble(),
      minY: minY,
      maxY: maxY,
      lineBarsData: [
        LineChartBarData(
          spots: List.generate(tanamData.length,
              (index) => FlSpot(index.toDouble(), tanamData[index])),
          isCurved: true,
          gradient: LinearGradient(
            colors: tanamGradientColors,
          ),
          barWidth: 5.w, // Add .w for bar width
          isStrokeCapRound: true,
          dotData: const FlDotData(
            show: false,
          ),
          belowBarData: BarAreaData(
            show: true,
            gradient: LinearGradient(
              colors: tanamGradientColors
                  .map((color) => color.withOpacity(0.3))
                  .toList(),
            ),
          ),
        ),
        LineChartBarData(
          spots: List.generate(panenData.length,
              (index) => FlSpot(index.toDouble(), panenData[index])),
          isCurved: true,
          gradient: LinearGradient(
            colors: panenGradientColors,
          ),
          barWidth: 5.w, // Add .w for bar width
          isStrokeCapRound: true,
          dotData: const FlDotData(
            show: false,
          ),
          belowBarData: BarAreaData(
            show: true,
            gradient: LinearGradient(
              colors: panenGradientColors
                  .map((color) => color.withOpacity(0.3))
                  .toList(),
            ),
          ),
        ),
        LineChartBarData(
          spots: List.generate(pusoRusakData.length,
              (index) => FlSpot(index.toDouble(), pusoRusakData[index])),
          isCurved: true,
          gradient: LinearGradient(
            colors: pusoRusakGradientColors,
          ),
          barWidth: 5.w, // Add .w for bar width
          isStrokeCapRound: true,
          dotData: const FlDotData(
            show: false,
          ),
          belowBarData: BarAreaData(
            show: true,
            gradient: LinearGradient(
              colors: pusoRusakGradientColors
                  .map((color) => color.withOpacity(0.3))
                  .toList(),
            ),
          ),
        ),
      ],
    );
  }
}
