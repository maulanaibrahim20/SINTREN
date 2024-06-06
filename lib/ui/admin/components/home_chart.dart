import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class HomeChart {
  final List<int> labels;
  final List<double> targets;
  HomeChart({required this.labels, required this.targets});

  final List<Color> gradientColors = [
    ColorTheme().secondaryColor,
    ColorTheme().thirdColor
  ];

  Widget bottomTitleWidgets(double value, TitleMeta meta) {
    TextStyle style = TextStyle(fontSize: 12, color: ColorTheme().primaryColor);
    Widget text;
    int yearIndex = value.toInt();
    if (yearIndex >= 0 && yearIndex < labels.length) {
      text = Text(labels[yearIndex].toString(), style: style);
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
        fontWeight: FontWeight.bold,
        fontSize: 15,
        color: ColorTheme().primaryColor);
    String text;
    if (value % 5000 == 0) {
      text = '${value ~/ 1000}K';
    } else {
      return Container();
    }

    return Text(text, style: style, textAlign: TextAlign.left);
  }

  LineChartData mainData() {
    double maxValue = targets.reduce((a, b) => a > b ? a : b);
    double maxY = ((maxValue / 5000).ceil() * 5000) * 1;

    return LineChartData(
      backgroundColor: Colors.white,
      lineTouchData: LineTouchData(
        touchTooltipData: LineTouchTooltipData(
          getTooltipColor: (LineBarSpot getToolTipColor) {
            return ColorTheme().primaryColor;
          },
          getTooltipItems: (List<LineBarSpot> touchedSpots) {
            return touchedSpots.map((spot) {
              return LineTooltipItem(
                '${spot.y}',
                StyleTheme().styleWhite.copyWith(fontWeight: FontWeight.w500),
              );
            }).toList();
          },
        ),
        touchCallback: (FlTouchEvent event, LineTouchResponse? touchResponse) {
          if (touchResponse == null || touchResponse.lineBarSpots == null) {
            return;
          }
        },
      ),
      gridData: FlGridData(
        show: true,
        drawVerticalLine: true,
        horizontalInterval: 5000,
        verticalInterval: 1,
        getDrawingHorizontalLine: (value) {
          return FlLine(
            color: Colors.grey[200],
            strokeWidth: 1,
          );
        },
        getDrawingVerticalLine: (value) {
          return FlLine(
            color: Colors.grey[200],
            strokeWidth: 1,
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
            reservedSize: 30,
            interval: 1,
            getTitlesWidget: bottomTitleWidgets,
          ),
        ),
        leftTitles: AxisTitles(
          sideTitles: SideTitles(
            showTitles: true,
            interval: 5000,
            getTitlesWidget: leftTitleWidgets,
            reservedSize: 42,
          ),
        ),
      ),
      borderData: FlBorderData(
        show: true,
        border: Border.all(color: Colors.white),
      ),
      minX: 0,
      maxX: labels.length - 1.toDouble(),
      minY: (targets.reduce((a, b) => a < b ? a : b) / 10000).floor() *
          10000.toDouble(),
      maxY: maxY,
      lineBarsData: [
        LineChartBarData(
          spots: List.generate(targets.length,
              (index) => FlSpot(index.toDouble(), targets[index])),
          isCurved: true,
          gradient: LinearGradient(
            colors: gradientColors,
          ),
          barWidth: 5,
          isStrokeCapRound: true,
          dotData: const FlDotData(
            show: false,
          ),
          belowBarData: BarAreaData(
            show: true,
            gradient: LinearGradient(
              colors: gradientColors
                  .map((color) => color.withOpacity(0.3))
                  .toList(),
            ),
          ),
        ),
      ],
    );
  }
}
