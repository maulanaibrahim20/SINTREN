import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:sintren_mobile/models/prediksi_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class HomeChart {
  final List<DataItem> data;

  HomeChart({required this.data});

  final List<Color> actualDataGradientColors = [
    ColorTheme().secondaryColor,
    ColorTheme().thirdColor,
  ];

  final List<Color> predictedDataGradientColors = [
    Colors.amber[900]!, // You can choose any other color to differentiate
    Colors.amberAccent,
  ];

  List<int> get actualData => data.map((item) => item.actualData).toList();
  List<double> get predictedData =>
      data.map((item) => item.predictedData).toList();
  List<int> get labels => data.map((item) => item.label).toList();

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
      color: ColorTheme().primaryColor,
    );
    String text;
    if (value % 5000 == 0) {
      text = '${value ~/ 1000}K';
    } else {
      return Container();
    }

    return Text(text, style: style, textAlign: TextAlign.left);
  }

  LineChartData mainData() {
    double maxValue = actualData.reduce((a, b) => a > b ? a : b).toDouble();
    double maxY = ((maxValue / 5000).ceil() * 5000).toDouble();

    return LineChartData(
      backgroundColor: Colors.white,
      lineTouchData: LineTouchData(
        touchTooltipData: LineTouchTooltipData(
          getTooltipItems: (List<LineBarSpot> touchedSpots) {
            return touchedSpots.map((spot) {
              final isActualData = spot.barIndex == 0;
              final dataType = isActualData ? 'Actual Data' : 'Predicted Data';
              final textStyle = isActualData
                  ? StyleTheme()
                      .styleWhite
                      .copyWith(fontWeight: FontWeight.w500)
                  : StyleTheme()
                      .styleWhite
                      .copyWith(fontWeight: FontWeight.bold);
              return LineTooltipItem(
                '$dataType: ${spot.y}',
                textStyle,
              );
            }).toList();
          },
        ),
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
      maxX: (labels.length - 1).toDouble(),
      minY: (actualData.reduce((a, b) => a < b ? a : b) / 10000).floor() *
          10000.toDouble(),
      maxY: maxY,
      lineBarsData: [
        LineChartBarData(
          spots: List.generate(
              actualData.length,
              (index) =>
                  FlSpot(index.toDouble(), actualData[index].toDouble())),
          isCurved: true,
          gradient: LinearGradient(
            colors: actualDataGradientColors,
          ),
          barWidth: 5,
          isStrokeCapRound: true,
          dotData: const FlDotData(
            show: false,
          ),
          belowBarData: BarAreaData(
            show: false,
          ),
        ),
        LineChartBarData(
          spots: List.generate(predictedData.length,
              (index) => FlSpot(index.toDouble(), predictedData[index])),
          isCurved: true,
          gradient: LinearGradient(
            colors: predictedDataGradientColors,
          ),
          barWidth: 5,
          isStrokeCapRound: true,
          dotData: const FlDotData(
            show: false,
          ),
          belowBarData: BarAreaData(
            show: false,
          ),
        ),
      ],
    );
  }
}
