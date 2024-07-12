import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/admin/admin_padi_controller.dart';
import 'package:sintren_mobile/controllers/admin/admin_palawija_controller.dart';
import 'package:sintren_mobile/models/pie_chart_model.dart';
import 'package:sintren_mobile/ui/components/indicator.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class ProgressChart {
  final List<Color> _colorList = [
    Colors.indigo,
    Colors.amber[900]!,
    Colors.grey,
  ];

  final String? kecamatanId;
  final String? desaId;

  ProgressChart({this.kecamatanId, this.desaId});

  Column indicator(PieChartModel data) {
    final List<String> indicatorNames = ['Panen', 'Tanam', 'Puso Rusak'];

    return Column(
      mainAxisAlignment: MainAxisAlignment.end,
      crossAxisAlignment: CrossAxisAlignment.start,
      children: List.generate(indicatorNames.length, (index) {
        return Column(
          children: [
            Indicator(
              color: _colorList[index % _colorList.length],
              text: indicatorNames[index],
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

  List<PieChartSectionData> showingSections(PieChartModel data) {
    final List<double> values = [
      data.sumPanen,
      data.sumTanam,
      data.sumPusoRusak,
    ];

    double totalValue = values.fold(0, (sum, item) => sum + item);

    return List.generate(values.length, (index) {
      const fontSize = 16.0;
      const radius = 70.0;
      const shadows = [Shadow(color: Colors.black, blurRadius: 2)];
      final percentage = (values[index] / totalValue * 100).toStringAsFixed(1);

      return PieChartSectionData(
        color: _colorList[index % _colorList.length],
        value: values[index],
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

  Future<dynamic> _fetchProgressChartData(String type) async {
    if (type == 'Padi') {
      return await AdminPadiController()
          .getDataProgressPieChart(kecamatanId: kecamatanId, desaId: desaId);
    } else if (type == 'Palawija') {
      return await AdminPalawijaController()
          .getDataProgressPieChart(kecamatanId: kecamatanId, desaId: desaId);
    }
  }

  Widget buildProgressChartTab(String type) {
    return FutureBuilder(
      future: _fetchProgressChartData(type),
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return const Center(child: CircularProgressIndicator());
        } else if (snapshot.hasError) {
          return Center(
            child: Column(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                Icon(
                  Icons.error,
                  color: Colors.grey,
                  size: 50.r,
                ),
                Text(
                  "Internal Server Error : ${snapshot.error}",
                  style: StyleTheme().styleBlack.copyWith(
                      fontSize: 18.sp,
                      fontWeight: FontWeight.w500,
                      color: Colors.grey),
                ),
              ],
            ),
          );
        } else {
          PieChartModel data = snapshot.data;
          if (data.sumPanen == 0 &&
              data.sumTanam == 0 &&
              data.sumPusoRusak == 0) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.task,
                    color: Colors.grey,
                    size: 50.r,
                  ),
                  Text(
                    "Penyuluhan Belum Dilakukan",
                    style: StyleTheme().styleBlack.copyWith(
                        fontSize: 18.sp,
                        fontWeight: FontWeight.w500,
                        color: Colors.grey),
                  ),
                ],
              ),
            );
          }

          return Column(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Text(
                AdminController().getPeriode(),
                style: StyleTheme()
                    .stylePrimary
                    .copyWith(fontSize: 16, fontWeight: FontWeight.bold),
              ),
              SizedBox(height: 20.sp),
              Expanded(
                child: Row(
                  children: <Widget>[
                    Expanded(
                      child: PieChart(PieChartData(
                        borderData: FlBorderData(
                          show: false,
                        ),
                        sectionsSpace: 0,
                        centerSpaceRadius: 40.r,
                        sections: showingSections(data),
                      )),
                    ),
                    indicator(data),
                    SizedBox(
                      width: 28.w,
                    ),
                  ],
                ),
              ),
              SizedBox(height: 20.h),
            ],
          );
        }
      },
    );
  }
}
