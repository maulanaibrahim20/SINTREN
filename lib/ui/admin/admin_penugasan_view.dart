import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/penyuluh_model.dart';
import 'package:sintren_mobile/ui/admin/detail_penugasan_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class AdminPenugasanView extends StatefulWidget {
  const AdminPenugasanView({super.key});

  @override
  State<AdminPenugasanView> createState() => _AdminPenugasanViewState();
}

class _AdminPenugasanViewState extends State<AdminPenugasanView> {
  final adminC = AdminController();

  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      appBar: AppBar(
        elevation: 0.w,
        centerTitle: false,
        foregroundColor: ColorTheme().whiteColor,
        flexibleSpace: Container(
          decoration: BoxDecoration(gradient: ColorTheme().linearColor),
        ),
        title: Text(
          'Penugasan Penyuluh',
          style: StyleTheme().styleWhite.copyWith(
                fontSize: 20.sp,
                fontWeight: FontWeight.w500,
              ),
        ),
        backgroundColor: ColorTheme().primaryColor,
      ),
      body: 
      FutureBuilder<List<Penyuluh>>(
        future: adminC.getPenyuluh(),
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
                    "Data Penugasan Kosong",
                    style: StyleTheme().styleBlack.copyWith(
                        fontSize: 18.sp,
                        fontWeight: FontWeight.w500,
                        color: Colors.grey),
                  ),
                ],
              ),
            );
          } else {
            List<Penyuluh> penyuluhList = snapshot.data!;
            return ListView.builder(
              padding: EdgeInsets.only(top: 10.h),
              itemCount: penyuluhList.length,
              itemBuilder: (context, index) {
                Penyuluh penyuluh = penyuluhList[index];
                return GestureDetector(
                  onTap: () async {
                    await Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => DetailPenugasanView(
                          id: penyuluh.id,
                        ),
                      ),
                    );
                    setState(() {});
                  },
                  child: Card(
                    margin:
                        EdgeInsets.symmetric(horizontal: 20.w, vertical: 10.h),
                    elevation: 3.r,
                    shadowColor: ColorTheme().whiteColor,
                    surfaceTintColor: ColorTheme().whiteColor,
                    color: ColorTheme().whiteColor,
                    child: Column(
                      children: [
                        SizedBox(height: 10.h),
                        Padding(
                          padding: EdgeInsets.symmetric(horizontal: 20.w),
                          child: Row(
                            children: [
                              Container(
                                height: 50.h,
                                width: 50.w,
                                decoration: BoxDecoration(
                                  shape: BoxShape.circle,
                                  gradient: ColorTheme().linearColor,
                                ),
                                child: Center(
                                  child: Icon(
                                    Icons.person,
                                    color: ColorTheme().whiteColor,
                                    size: 30.w,
                                  ),
                                ),
                              ),
                              SizedBox(width: 15.w),
                              Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceEvenly,
                                children: [
                                  Text(
                                    UserController().toCamelCase(penyuluh.name),
                                    style: StyleTheme().stylePrimary.copyWith(
                                        fontSize: 20.sp,
                                        fontWeight: FontWeight.bold),
                                  ),
                                  Text(
                                    "Email: ${penyuluh.email}",
                                    style: StyleTheme().styleBlack.copyWith(
                                        fontWeight: FontWeight.bold,
                                        color: Colors.grey[700],
                                        fontSize: 14.sp),
                                  ),
                                  Text(
                                    "No. Telp: ${penyuluh.noTelp}",
                                    style: StyleTheme().styleBlack.copyWith(
                                        fontWeight: FontWeight.bold,
                                        color: Colors.grey[700],
                                        fontSize: 14.sp),
                                  ),
                                  Text(
                                    "Alamat: ${penyuluh.alamat}",
                                    style: StyleTheme().styleBlack.copyWith(
                                        fontWeight: FontWeight.bold,
                                        color: Colors.grey[700],
                                        fontSize: 14.sp),
                                  ),
                                ],
                              )
                            ],
                          ),
                        ),
                        SizedBox(height: 10.h),
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
