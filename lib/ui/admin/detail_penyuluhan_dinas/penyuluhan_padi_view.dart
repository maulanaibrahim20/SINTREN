import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/admin/admin_padi_controller.dart';
import 'package:sintren_mobile/models/grouped_data_padi_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class PenyuluhanPadiView extends StatefulWidget {
  const PenyuluhanPadiView({super.key});

  @override
  State<PenyuluhanPadiView> createState() => _PenyuluhanPadiViewState();
}

class _PenyuluhanPadiViewState extends State<PenyuluhanPadiView> {
  AdminPadiController padiC = AdminPadiController();
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      body: FutureBuilder<List<GroupedDataPadiModel>>(
        future: padiC.getAllPenyuluhanPadi(),
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
                    size: 50.sp,
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
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.person,
                    color: Colors.grey,
                    size: 50.sp,
                  ),
                  Text(
                    "Data Penyuluhan Kosong",
                    style: StyleTheme().styleBlack.copyWith(
                        fontSize: 18.sp,
                        fontWeight: FontWeight.w500,
                        color: Colors.grey),
                  ),
                ],
              ),
            );
          } else {
            List<GroupedDataPadiModel> padiList = snapshot.data!;
            return ListView.builder(
              padding: EdgeInsets.only(top: 10.h),
              itemCount: padiList.length,
              itemBuilder: (context, index) {
                GroupedDataPadiModel data = padiList[index];
                return Card(
                  surfaceTintColor: ColorTheme().whiteColor,
                  margin:
                      const EdgeInsets.only(right: 15, left: 15, bottom: 10),
                  elevation: 3,
                  child: SizedBox(
                    height: 210,
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
                                Text(
                                  "Hibrida",
                                  style: StyleTheme().styleBlack.copyWith(
                                      fontWeight: FontWeight.w500,
                                      fontSize: 16),
                                ),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      "Lohbener",
                                      style: StyleTheme().styleBlack,
                                    ),
                                    Text(
                                      "Tidak Terverifikasi",
                                      style: StyleTheme()
                                          .styleBlack
                                          .copyWith(color: Colors.red),
                                    ),
                                  ],
                                ),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      "Lahan Sawah",
                                      style: StyleTheme().styleBlack,
                                    ),
                                    Text(
                                      "1/5/2024",
                                      style: StyleTheme().styleBlack,
                                    ),
                                  ],
                                ),
                                Row(
                                  mainAxisAlignment:
                                      MainAxisAlignment.spaceBetween,
                                  children: [
                                    Text(
                                      "Irigasi Tersier",
                                      style: StyleTheme().styleBlack,
                                    ),
                                    Text(
                                      "Bantuan Pemerintah",
                                      style: StyleTheme().styleBlack,
                                    ),
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
                              ],
                            ),
                          ),
                        ),
                      ],
                    ),
                  ),
                );
              },
            );
          }
        },
      ),
    );
  }
}
