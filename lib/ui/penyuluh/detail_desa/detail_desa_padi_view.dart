import 'package:flutter/material.dart';
import 'package:percent_indicator/percent_indicator.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class DetailDesaPadiView extends StatefulWidget {
  const DetailDesaPadiView({super.key});

  @override
  State<DetailDesaPadiView> createState() => _DetailDesaPadiViewState();
}

class _DetailDesaPadiViewState extends State<DetailDesaPadiView> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      body: ListView(
        children: [
          const SizedBox(height: 10),
          Column(
            children: List.generate(
              10,
              (index) {
                return Card(
                  surfaceTintColor: ColorTheme().whiteColor,
                  margin: const EdgeInsets.only(right: 15, left: 15, bottom: 10),
                  elevation: 3,
                  child: SizedBox(
                    height: 240,
                    width: MediaQuery.of(context).size.width,
                    child: Row(
                      children: [
                        Container(
                          width: 10,
                          decoration: BoxDecoration(
                              color: ColorTheme().primaryColor,
                              borderRadius: const BorderRadius.only(
                                  bottomLeft: Radius.circular(10),
                                  topLeft: Radius.circular(10))),
                        ),
                        Expanded(
                          child: Padding(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 20, vertical: 10),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  children: [
                                    Container(
                                      height: 40,
                                      width: 40,
                                      decoration: BoxDecoration(
                                        shape: BoxShape.circle,
                                        gradient: ColorTheme().linearColor,
                                      ),
                                      child: Center(
                                        child: Icon(
                                          Icons.date_range_rounded,
                                          color: ColorTheme().whiteColor,
                                          size: 20,
                                        ),
                                      ),
                                    ),
                                    const SizedBox(width: 10),
                                    Text(
                                      "Mei 2024",
                                      style: StyleTheme().stylePrimary.copyWith(
                                          fontSize: 16,
                                          fontWeight: FontWeight.bold),
                                    )
                                  ],
                                ),
                                const Divider(),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      "Tanaman Akhir Bulan Lalu:",
                                      style: StyleTheme().styleBlack,
                                    ),
                                    Text(
                                      "1000",
                                      style: StyleTheme().styleBlack.copyWith(
                                            fontWeight: FontWeight.bold,
                                          ),
                                    ),
                                  ],
                                ),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      "Tanam:",
                                      style: StyleTheme().styleBlack,
                                    ),
                                    Text(
                                      "100",
                                      style: StyleTheme().styleBlack.copyWith(
                                            fontWeight: FontWeight.bold,
                                          ),
                                    ),
                                  ],
                                ),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      "Panen:",
                                      style: StyleTheme().styleBlack,
                                    ),
                                    Text(
                                      "100",
                                      style: StyleTheme().styleBlack.copyWith(
                                            fontWeight: FontWeight.bold,
                                          ),
                                    ),
                                  ],
                                ),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      "Puso/Rusak:",
                                      style: StyleTheme().styleBlack,
                                    ),
                                    Text(
                                      "100",
                                      style: StyleTheme().styleBlack.copyWith(
                                            fontWeight: FontWeight.bold,
                                          ),
                                    ),
                                  ],
                                ),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      "Tanaman Akhir Bulan Ini:",
                                      style: StyleTheme().styleBlack,
                                    ),
                                    Text(
                                      "900",
                                      style: StyleTheme().styleBlack.copyWith(
                                            fontWeight: FontWeight.bold,
                                          ),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 10),
                                Stack(
                                  children: [
                                    const Divider(thickness: 2, color: Colors.grey),
                                    Container(
                                      color: ColorTheme().whiteColor,
                                      margin: const EdgeInsets.only(left: 20),
                                      padding:
                                          const EdgeInsets.symmetric(horizontal: 8.0),
                                      child: Text(
                                        "Progres penyuluhan",
                                        style: StyleTheme()
                                            .styleBlack
                                            .copyWith(color: Colors.black87),
                                      ),
                                    ),
                                  ],
                                ),
                                const SizedBox(height: 10),
                                LinearPercentIndicator(
                                  width: MediaQuery.of(context).size.width - 80,
                                  animation: true,
                                  lineHeight: 30,
                                  animationDuration: 2000,
                                  percent: 0.9,
                                  center: Text(
                                    "90.0%",
                                    style: StyleTheme().styleWhite.copyWith(
                                        fontWeight: FontWeight.w500,
                                        fontSize: 14),
                                  ),
                                  padding: EdgeInsets.zero,
                                  barRadius: const Radius.circular(10),
                                  linearGradient: ColorTheme().linearColor,
                                ),
                                const SizedBox(height: 10),
                              ],
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              },
            ),
          ),
          const SizedBox(height: 90),
        ],
      ),
    );
  }
}
