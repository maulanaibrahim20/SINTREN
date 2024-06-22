import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/admin/admin_padi_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
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
      body: FutureBuilder<List<DetailPadiModel>>(
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
            List<DetailPadiModel> padiList = snapshot.data!;
            return ListView.builder(
              padding: EdgeInsets.only(top: 10.h),
              itemCount: padiList.length,
              itemBuilder: (context, index) {
                DetailPadiModel data = padiList[index];
                return Card(
                  surfaceTintColor: ColorTheme().whiteColor,
                  color: ColorTheme().whiteColor,
                  margin: EdgeInsets.symmetric(vertical: 5.h, horizontal: 10.w),
                  elevation: 3,
                  child: ConstrainedBox(
                    constraints: const BoxConstraints(),
                    child: Padding(
                      padding: EdgeInsets.symmetric(
                          horizontal: 20.w, vertical: 10.h),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            data.padiName,
                            style: StyleTheme().styleBlack.copyWith(
                                fontWeight: FontWeight.w500, fontSize: 16.sp),
                          ),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                UserController()
                                    .toCamelCase(data.kecamatanName),
                                style: StyleTheme().styleBlack,
                              ),
                              Text(
                                UserController().toCamelCase(data.desaName),
                                style: StyleTheme().styleBlack,
                              ),
                            ],
                          ),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                UserController().toCamelCase(data.jenisBantuan),
                                style: StyleTheme().styleBlack,
                              ),
                              Text(
                                UserController().normalizeDate(data.date),
                                style: StyleTheme().styleBlack,
                              ),
                            ],
                          ),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                'Lahan ${UserController().toCamelCase(data.jenisLahan)}',
                                style: StyleTheme().styleBlack,
                              ),
                              Text(
                                UserController()
                                    .toCamelCase(data.pengairanName),
                                style: StyleTheme().styleBlack,
                              ),
                            ],
                          ),
                          const Divider(),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text(
                                UserController().toCamelCase(data.tipeData),
                                style: StyleTheme().styleBlack.copyWith(
                                    fontSize: 14.sp,
                                    fontWeight: FontWeight.w500),
                              ),
                              Text(
                                "${data.nilai} hektar",
                                style: StyleTheme().styleBlack.copyWith(
                                      fontSize: 14.sp,
                                      fontWeight: FontWeight.bold,
                                    ),
                              ),
                            ],
                          ),
                          const Divider(),
                          SizedBox(height: 5.h),
                        ],
                      ),
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
