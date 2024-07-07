import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/admin/admin_palawija_controller.dart';
import 'package:sintren_mobile/ui/components/indicator.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

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

  Column _indicator(List<Map<String, dynamic>> list) {
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

  List<PieChartSectionData> _showingSections(List<Map<String, dynamic>> list) {
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

  Widget chart({String? kecamatanId, String? desaId}) {
    return FutureBuilder(
        future: AdminPalawijaController().getDataPenyuluhanPalawijaTahunIni(
            kecamatanId: kecamatanId, desaId: desaId),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
          } else if (snapshot.hasError) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(
                    Icons.error,
                    color: Colors.grey,
                    size: 50,
                  ),
                  Text(
                    "Internal Server Error",
                    style: StyleTheme().styleBlack.copyWith(
                        fontSize: 18.sp,
                        fontWeight: FontWeight.w500,
                        color: Colors.grey),
                  ),
                ],
              ),
            );
          } else {
            final dataList = snapshot.data as List<Map<String, dynamic>>;
            if (dataList.isEmpty) {
              return const SizedBox.shrink();
            }
            return SizedBox(
              width: double.infinity,
              height: 270.h,
              child: Card(
                elevation: 3,
                margin: EdgeInsets.symmetric(horizontal: 15.w),
                color: ColorTheme().whiteColor,
                surfaceTintColor: ColorTheme().whiteColor,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Padding(
                      padding: EdgeInsets.symmetric(
                          horizontal: 30.w, vertical: 10.h),
                      child: Column(
                        children: [
                          Text(
                            "Data Panen Palawija Tahun Ini",
                            style: StyleTheme().stylePrimary.copyWith(
                                  fontSize: 18.sp,
                                  fontWeight: FontWeight.bold,
                                ),
                          ),
                          const Divider(),
                        ],
                      ),
                    ),
                    Expanded(
                      child: Row(
                        children: <Widget>[
                          SizedBox(
                            height: 18.h,
                          ),
                          Expanded(
                            child: PieChart(PieChartData(
                              borderData: FlBorderData(
                                show: false,
                              ),
                              sectionsSpace: 0,
                              centerSpaceRadius: 40.r,
                              sections: _showingSections(dataList),
                            )),
                          ),
                          _indicator(dataList),
                          SizedBox(
                            width: 28.w,
                          ),
                        ],
                      ),
                    ),
                    SizedBox(height: 20.h),
                  ],
                ),
              ),
            );
          }
        });
  }
}
