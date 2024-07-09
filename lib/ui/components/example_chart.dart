import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/models/trend_grafik_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class ExampleChart extends StatefulWidget {
  final TrendGrafik data;

  ExampleChart({super.key, required this.data});

  final List<Color> tanamGradientColors = [
    const Color.fromRGBO(123, 201, 255, 1),
    const Color.fromRGBO(133, 118, 255, 1)
  ];

  final List<Color> panenGradientColors = [
    Colors.amber[900]!,
    Colors.amberAccent,
  ];

  final List<Color> pusoRusakGradientColors = [
    Colors.red[900]!,
    Colors.redAccent[400]!
  ];

  @override
  State<StatefulWidget> createState() => ExampleChartState();
}

class ExampleChartState extends State<ExampleChart> {
  final double width = 7.w; // Use .w for width
  late List<BarChartGroupData> rawBarGroups;
  late List<BarChartGroupData> showingBarGroups;
  int touchedGroupIndex = -1;
  late int showingTooltip;
  double interval = 1.0;
  late double maxY;
  late double minY;

  List<double> get tanamData =>
      widget.data.allData.map((item) => item['tanam'] ?? 0).toList();
  List<double> get panenData =>
      widget.data.allData.map((item) => item['panen'] ?? 0).toList();
  List<double> get pusoRusakData =>
      widget.data.allData.map((item) => item['puso/rusak'] ?? 0).toList();
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
        'Des'
      ];

  double get maxDataY {
    double maxY = 0;
    for (var i = 0; i < tanamData.length; i++) {
      maxY = maxY < tanamData[i] ? tanamData[i] : maxY;
      maxY = maxY < panenData[i] ? panenData[i] : maxY;
      maxY = maxY < pusoRusakData[i] ? pusoRusakData[i] : maxY;
    }
    return maxY;
  }

  @override
  void initState() {
    showingTooltip = -1;
    super.initState();

    rawBarGroups = List.generate(labels.length, (i) {
      return makeGroupData(i, tanamData[i], panenData[i], pusoRusakData[i]);
    });

    showingBarGroups = rawBarGroups;
    maxY = [tanamData, panenData, pusoRusakData]
        .expand((i) => i)
        .reduce((a, b) => a > b ? a : b);
    minY = 0; // Set minimum value to 0 for better readability

    // Adjusting maxY to be a multiple of 1000 for better display
    maxY = ((maxY / 1000).ceil() * 1000).toDouble();

    // Calculate interval based on the range and desired number of intervals
    if (maxY > minY) {
      interval = ((maxY - minY) / 5).ceil().toDouble();
    } else {
      interval = 1.0; // Set a default interval if maxY == minY
    }
  }

  @override
  Widget build(BuildContext context) {
    return AspectRatio(
      aspectRatio: 1,
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: <Widget>[
          Expanded(
            child: interval <= 1
                ? Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(
                          Icons.task,
                          color: ColorTheme().whiteColor,
                          size: 50.r,
                        ),
                        Text(
                          "Penyuluhan Belum Dilakukan",
                          style: StyleTheme().styleBlack.copyWith(
                              fontSize: 18.sp,
                              fontWeight: FontWeight.w500,
                              color: ColorTheme().whiteColor),
                        ),
                      ],
                    ),
                  )
                : BarChart(
                    BarChartData(
                      minY: minY,
                      maxY: maxY,
                      barTouchData: BarTouchData(
                        enabled: true,
                        touchCallback:
                            (FlTouchEvent event, BarTouchResponse? response) {
                          if (event.isInterestedForInteractions &&
                              response != null &&
                              response.spot != null) {
                            setState(() {
                              final x = response.spot!.touchedBarGroup.x;
                              final isShowing = showingTooltip == x;
                              if (isShowing) {
                                showingTooltip = -1;
                              } else {
                                showingTooltip = x;
                              }
                            });
                          }
                        },
                        touchTooltipData: BarTouchTooltipData(
                          getTooltipColor: (group) => ColorTheme().primaryColor,
                          getTooltipItem: (group, groupIndex, rod, rodIndex) {
                            String month = labels[group.x];
                            String getData(int index) {
                              switch (index) {
                                case 0:
                                  return "Tanam";
                                case 1:
                                  return "Panen";
                                case 2:
                                  return "Puso";
                                default:
                                  return "";
                              }
                            }

                            return BarTooltipItem(
                              '$month\n',
                              TextStyle(
                                color: Colors.white,
                                fontWeight: FontWeight.bold,
                                fontSize: 18.sp, // Use .sp for font size
                              ),
                              children: <TextSpan>[
                                TextSpan(
                                  text: "${getData(rodIndex)}: ${rod.toY}",
                                  style: TextStyle(
                                    color: Colors.yellow,
                                    fontSize: 16.sp, // Use .sp for font size
                                    fontWeight: FontWeight.w500,
                                  ),
                                ),
                              ],
                            );
                          },
                        ),
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
                            getTitlesWidget: bottomTitles,
                            reservedSize: 42.h, // Use .h for height
                          ),
                        ),
                        leftTitles: AxisTitles(
                          sideTitles: SideTitles(
                            showTitles: true,
                            reservedSize: 35.w, // Use .w for width
                            interval: interval, // Use the calculated interval
                            getTitlesWidget: leftTitles,
                          ),
                        ),
                      ),
                      borderData: FlBorderData(
                        show: false,
                      ),
                      barGroups: showingBarGroups,
                      gridData: const FlGridData(show: false),
                    ),
                  ),
          ),
          SizedBox(height: 3.h),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Row(
                children: [
                  Container(
                    width: 10.w,
                    height: 10.h,
                    color: const Color.fromRGBO(123, 201, 255, 1),
                  ),
                  SizedBox(width: 5.w),
                  Text(
                    "Data Tanam",
                    style: TextStyle(
                      color: ColorTheme().whiteColor,
                      fontSize: 12.sp,
                    ),
                  ),
                ],
              ),
              SizedBox(width: 10.w),
              Row(
                children: [
                  Container(
                      width: 10.w, height: 10.h, color: Colors.amber[900]),
                  SizedBox(width: 5.w),
                  Text(
                    "Data Panen",
                    style: TextStyle(
                        color: ColorTheme().whiteColor, fontSize: 12.sp),
                  ),
                ],
              ),
              SizedBox(width: 5.w),
              Row(
                children: [
                  Container(width: 10.w, height: 10.h, color: Colors.red[900]),
                  SizedBox(width: 5.w),
                  Text(
                    "Data Puso/Rusak",
                    style: TextStyle(
                        color: ColorTheme().whiteColor, fontSize: 12.sp),
                  ),
                ],
              ),
            ],
          ),
        ],
      ),
    );
  }

  Widget leftTitles(double value, TitleMeta meta) {
    TextStyle style = TextStyle(
      fontWeight: FontWeight.w500,
      fontSize: 12.sp,
      color: ColorTheme().whiteColor,
    );
    String text;

    // Display titles at regular intervals
    if (value % interval == 0) {
      if (maxY >= 1000) {
        text =
            '${(value / 1000).toStringAsFixed(1)}K'; // Display in thousands with one decimal place
      } else {
        text = '${value.toInt()}'; // Display exact value for smaller numbers
      }
    } else {
      return Container();
    }

    return Text(text, style: style, textAlign: TextAlign.left);
  }

  Widget bottomTitles(double value, TitleMeta meta) {
    TextStyle style = TextStyle(
      fontSize: 12.sp, // Add .sp for font size
      color: ColorTheme().whiteColor,
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

  BarChartGroupData makeGroupData(int x, double y1, double y2, double y3) {
    return BarChartGroupData(
      barsSpace: 1,
      x: x,
      showingTooltipIndicators: showingTooltip == x ? [0, 1, 2] : [],
      barRods: [
        BarChartRodData(
          toY: y1,
          gradient: LinearGradient(colors: widget.tanamGradientColors),
          width: width,
        ),
        BarChartRodData(
          toY: y2,
          gradient: LinearGradient(colors: widget.panenGradientColors),
          width: width,
        ),
        BarChartRodData(
          toY: y3,
          gradient: LinearGradient(colors: widget.pusoRusakGradientColors),
          width: width,
        ),
      ],
    );
  }
}
