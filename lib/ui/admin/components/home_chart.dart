import 'package:flutter/material.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
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
  List<int> get predictedData =>
      data.map((item) => item.predictedData).toList();
  List<int> get labels => data.map((item) => item.label).toList();

  Widget bottomTitleWidgets(double value, TitleMeta meta) {
    TextStyle style = TextStyle(
      fontSize: 12.sp, // Add .sp for font size
      color: ColorTheme().primaryColor,
    );
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

  late double interval;

  LineChartData mainData() {
    int maxActualData = actualData.reduce((a, b) => a > b ? a : b);
    int minActualData = actualData.reduce((a, b) => a < b ? a : b);

    int maxPredictedData = predictedData.reduce((a, b) => a > b ? a : b);
    int minPredictedData = predictedData.reduce((a, b) => a < b ? a : b);

    double maxY = [maxActualData, maxPredictedData]
        .reduce((a, b) => a > b ? a : b)
        .toDouble();
    double minY = [minActualData, minPredictedData]
        .reduce((a, b) => a < b ? a : b)
        .toDouble();

    // Adjusting maxY and minY to be a multiple of 5000 for better display
    maxY = ((maxY / 5000).ceil() * 5000).toDouble();
    minY = ((minY / 5000).floor() * 5000).toDouble();

    // Calculate interval based on the range and desired number of intervals
    interval = ((maxY - minY) / 5).ceil().toDouble();

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
        border: Border.all(color: Colors.white),
      ),
      minX: 0,
      maxX: (labels.length - 1).toDouble(),
      minY: minY,
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
          barWidth: 5.w, // Add .w for bar width
          isStrokeCapRound: true,
          dotData: const FlDotData(
            show: false,
          ),
          belowBarData: BarAreaData(
            show: false,
          ),
        ),
        LineChartBarData(
          spots: List.generate(
              predictedData.length,
              (index) =>
                  FlSpot(index.toDouble(), predictedData[index].toDouble())),
          isCurved: true,
          gradient: LinearGradient(
            colors: predictedDataGradientColors,
          ),
          barWidth: 5.w, // Add .w for bar width
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
