import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/controllers/penyuluh/penyuluh_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/services/penyuluh/padi_service.dart';
import 'package:sintren_mobile/services/penyuluh/palawija_service.dart';
import 'package:sintren_mobile/services/penyuluh/penyuluh_service.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/components/dropdown_button_component.dart';
import 'package:sintren_mobile/ui/penyuluh/detail_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/penyuluh/penyuluh_home_view.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';

class HistoriPenyuluhanView extends StatefulWidget {
  const HistoriPenyuluhanView({super.key, this.isHome, this.desa});

  final bool? isHome;
  final String? desa;

  @override
  State<HistoriPenyuluhanView> createState() => _HistoriPenyuluhanViewState();
}

class _HistoriPenyuluhanViewState extends State<HistoriPenyuluhanView> {
  final userC = UserController();
  final penyuluhC = PenyuluhController();
  late List<DesaModel> desaList;
  late DesaModel? selectedDesaValue;

  Future<void> _synchronizeData() async {
    await PenyuluhService().getDataPenyuluhanDesa();
    await PadiService().getDetailPadiByUser();
    await PalawijaService().getDetailPalawijaByUser();
    setState(() {});
  }

  @override
  void initState() {
    _initializeData();
    super.initState();
  }

  Future<void> _initializeData() async {
    desaList = await PenyuluhController().getDesa();
    selectedDesaValue = null;
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      appBar: AppBar(
        elevation: 0,
        centerTitle: false,
        foregroundColor: ColorTheme().whiteColor,
        flexibleSpace: Container(
          decoration: BoxDecoration(gradient: ColorTheme().linearColor),
        ),
        automaticallyImplyLeading: false,
        leading: IconButton(
          icon: Icon(
            Icons.arrow_back,
            color: ColorTheme().whiteColor,
          ),
          onPressed: () {
            Navigator.pushAndRemoveUntil(
              context,
              MaterialPageRoute(builder: (_) => const PenyuluhHomeView()),
              (route) => false,
            );
          },
        ),
        title: Text(
          'Histori Penyuluhan',
          style: StyleTheme().styleWhite.copyWith(
                fontSize: 20.sp,
                fontWeight: FontWeight.w500,
              ),
        ),
        actions: [
          IconButton(
            icon: Icon(
              Icons.filter_list,
              color: ColorTheme().whiteColor,
            ),
            onPressed: () {
              _filter(context);
            },
          ),
        ],
        backgroundColor: ColorTheme().primaryColor,
      ),
      floatingActionButton: FloatingActionButton(
        shape: const CircleBorder(),
        onPressed: () async {
          EasyLoading.show(status: "Sinkronisasi Data");
          await _synchronizeData();
          EasyLoading.dismiss();
        },
        backgroundColor: ColorTheme().primaryColor,
        foregroundColor: ColorTheme().whiteColor,
        child: const Icon(
          Icons.refresh_rounded,
        ),
      ),
      body: FutureBuilder(
        future: penyuluhC.getHistoriPenyuluhan(isHome: widget.isHome ?? false,desa: widget.desa),
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
            final historiList =
                snapshot.data as List<HistoriPenyuluhanModel>;

            if (historiList.isEmpty) {
              return Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(
                      Icons.assignment,
                      color: Colors.grey,
                      size: 50,
                    ),
                    Text(
                      "Tugas Belum Diberikan",
                      style: StyleTheme().styleBlack.copyWith(
                          fontSize: 18.sp,
                          fontWeight: FontWeight.w500,
                          color: Colors.grey),
                    ),
                  ],
                ),
              );
            }
            return Padding(
              padding: EdgeInsets.symmetric(vertical: 10.h),
              child: ListView.builder(
                padding: EdgeInsets.zero,
                itemCount: (selectedDesaValue == null)
                    ? historiList.length
                    : historiList
                        .where((desa) => desa.id == selectedDesaValue!.id)
                        .length,
                itemBuilder: (BuildContext context, int index) {
                  var displayList = (selectedDesaValue == null)
                      ? historiList
                      : historiList
                          .where((desa) => desa.id == selectedDesaValue!.id)
                          .toList();
                  HistoriPenyuluhanModel desa = displayList[index];
                  return GestureDetector(
                    onTap: () async {
                      await Navigator.push(
                        context,
                        MaterialPageRoute(
                          builder: (_) => DetailPenyuluhanView(
                            index: 0,
                            date: desa.date,
                            desaId: desa.id,
                            desaName: desa.name,
                          ),
                        ),
                      );
                      setState(() {});
                    },
                    child: Card(
                      margin:
                          EdgeInsets.symmetric(horizontal: 20.w, vertical: 8.h),
                      elevation: 3,
                      surfaceTintColor: ColorTheme().whiteColor,
                      color: ColorTheme().whiteColor,
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          if (desa.totalTunggu > 0) ...[
                            Align(
                              alignment: Alignment.topRight,
                              child: Container(
                                height: 30.h,
                                width: 150.w,
                                padding: EdgeInsets.symmetric(
                                    vertical: 5.h, horizontal: 20.w),
                                decoration: const BoxDecoration(
                                  color: Colors.red,
                                  borderRadius: BorderRadius.only(
                                    topRight: Radius.circular(10),
                                    bottomLeft: Radius.circular(10),
                                  ),
                                ),
                                child: Text(
                                  "${desa.totalTunggu} Data Ditolak",
                                  style: StyleTheme().styleWhite.copyWith(
                                      fontSize: 14.sp,
                                      fontWeight: FontWeight.bold),
                                ),
                              ),
                            ),
                          ] else ...[
                            SizedBox(height: 20.h),
                          ],
                          Padding(
                            padding: EdgeInsets.symmetric(horizontal: 20.w),
                            child: Row(
                              mainAxisSize: MainAxisSize.max,
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
                                      Icons.home_rounded,
                                      color: ColorTheme().whiteColor,
                                      size: 30.sp,
                                    ),
                                  ),
                                ),
                                SizedBox(width: 15.w),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      Text(
                                        "Desa ${UserController().toCamelCase(desa.name)}",
                                        style: StyleTheme()
                                            .stylePrimary
                                            .copyWith(
                                                fontSize: 20.sp,
                                                fontWeight: FontWeight.bold),
                                      ),
                                      Text(
                                        userC.convertDate(desa.date),
                                        style: StyleTheme().styleBlack.copyWith(
                                            fontWeight: FontWeight.bold,
                                            color: Colors.grey[700],
                                            fontSize: 14.sp),
                                      ),
                                    ],
                                  ),
                                ),
                                Icon(
                                  Icons.arrow_right,
                                  color: ColorTheme().primaryColor,
                                  size: 40.sp,
                                )
                              ],
                            ),
                          ),
                          SizedBox(height: 20.h),
                        ],
                      ),
                    ),
                  );
                },
              ),
            );
          }
        },
      ),
    );
  }

  void _filter(BuildContext context) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          surfaceTintColor: ColorTheme().whiteColor,
          title: const Column(
            children: [
              Text('Filter Desa'),
              Divider(),
            ],
          ),
          content: DropdownButtonComponent(
            icon: Icons.villa,
            label: 'Desa',
            selectedItem: selectedDesaValue,
            items: desaList.map((desa) {
              return DropdownMenuItem<DesaModel>(
                value: desa,
                child: Text(UserController().toCamelCase(desa.name)),
              );
            }).toList(),
            hint: 'Pilih Desa',
            validator: (value) =>
                value == null ? 'Pilih desa terlebih dahulu' : null,
            onChanged: (newValue) {
              setState(() {
                selectedDesaValue = newValue;
              });
            },
            onSaved: (newValue) {
              setState(() {
                selectedDesaValue = newValue!;
              });
            },
          ),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.pop(context);
              },
              child: Text(
                "Tutup",
                style: StyleTheme()
                    .stylePrimary
                    .copyWith(color: Colors.red, fontSize: 16.sp),
              ),
            ),
            TextButton(
              onPressed: () {
                setState(() {
                  selectedDesaValue = null;
                });
              },
              child: Text(
                "Reset",
                style: StyleTheme().stylePrimary.copyWith(fontSize: 16.sp),
              ),
            ),
          ],
        );
      },
    );
  }
}
