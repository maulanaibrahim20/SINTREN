import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/admin/admin_palawija_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/ui/dinas/detail_penyuluhan/components/ringkasan_palawija_widget.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/components/dropdown_button_component.dart';

class DetailPalawijaView extends StatefulWidget {
  const DetailPalawijaView(
      {super.key,
      required this.date,
      required this.desaId,
      required this.desaName});

  final String date;
  final String desaId;
  final String desaName;

  @override
  State<DetailPalawijaView> createState() => DetailPalawijaViewState();
}

class DetailPalawijaViewState extends State<DetailPalawijaView> {
  final palawijaC = AdminPalawijaController();
  TextEditingController ulasan = TextEditingController();
  final formKey = GlobalKey<FormState>();
  bool isOpen = false;
  late List<String> status = ['terima', 'tolak', 'tunggu'];
  late String selectedStatus;

  @override
  void initState() {
    selectedStatus = "";
    setState(() {});
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      floatingActionButton: FloatingActionButton(
        shape: const CircleBorder(),
        onPressed: () async {
          _filter(context);
        },
        backgroundColor: ColorTheme().primaryColor,
        foregroundColor: ColorTheme().whiteColor,
        child: const Icon(
          Icons.filter_list,
        ),
      ),
      body: ListView(
        children: [
          RingkasanPalawijaWidget(
            date: widget.date,
            desaId: widget.desaId,
            desaName: widget.desaName,
            isRincian: false,
          ),
          FutureBuilder<List<DetailPalawijaModel>>(
            future:
                palawijaC.getDetailPalawijaByDesa(widget.date, widget.desaId),
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
                        size: 50.w,
                      ),
                      Text(
                        "Internal Server Error",
                        style: StyleTheme().styleBlack.copyWith(
                            fontSize: 18.sp,
                            fontWeight: FontWeight.w500,
                            color: Colors.grey),
                      ),
                      Text(
                        snapshot.error.toString(),
                        style: TextStyle(
                          fontSize: 14.sp,
                          color: Colors.red,
                        ),
                        textAlign: TextAlign.center,
                      ),
                    ],
                  ),
                );
              } else {
                final itemList = snapshot.data ?? [];
                if (itemList.isEmpty) {
                  return const SizedBox.shrink();
                }
                return ListView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  padding: EdgeInsets.zero,
                  itemCount: (selectedStatus.isEmpty)
                      ? itemList.length
                      : itemList
                          .where((data) => data.status == selectedStatus)
                          .length,
                  itemBuilder: (BuildContext context, int index) {
                    var displayList = (selectedStatus.isEmpty)
                        ? itemList
                        : itemList
                            .where((data) => data.status == selectedStatus)
                            .toList();
                    DetailPalawijaModel data = displayList[index];
                    return Card(
                      surfaceTintColor: ColorTheme().whiteColor,
                      color: ColorTheme().whiteColor,
                      margin:
                          EdgeInsets.symmetric(vertical: 5.h, horizontal: 10.w),
                      elevation: 3,
                      child: ConstrainedBox(
                        constraints: const BoxConstraints(),
                        child: Padding(
                          padding: EdgeInsets.symmetric(
                              horizontal: 20.w, vertical: 10.h),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    data.palawijaName,
                                    style: StyleTheme().styleBlack.copyWith(
                                        fontWeight: FontWeight.w500,
                                        fontSize: 16.sp),
                                  ),
                                  Container(
                                    margin: EdgeInsets.symmetric(vertical: 3.h),
                                    padding: EdgeInsets.symmetric(
                                        horizontal: 8.w, vertical: 3.h),
                                    decoration: BoxDecoration(
                                      color: data.status == "terima"
                                          ? Colors.green
                                          : data.status == "tolak"
                                              ? Colors.red
                                              : Colors.amber,
                                      borderRadius: BorderRadius.circular(5.r),
                                    ),
                                    child: Text(
                                      data.status == "terima"
                                          ? "Terverifikasi"
                                          : data.status == "tolak"
                                              ? "Data Ditolak"
                                              : "Membutuhkan Verifikasi",
                                      style: StyleTheme().styleWhite.copyWith(
                                            fontWeight: FontWeight.w500,
                                          ),
                                    ),
                                  )
                                ],
                              ),
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    UserController()
                                        .toCamelCase(data.jenisBantuan),
                                    style: StyleTheme().styleBlack,
                                  ),
                                  Text(
                                    UserController().normalizeDate(data.date),
                                    style: StyleTheme().styleBlack,
                                  ),
                                ],
                              ),
                              Text(
                                'Lahan ${UserController().toCamelCase(data.jenisLahan)}',
                                style: StyleTheme().styleBlack,
                              ),
                              const Divider(),
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
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
                              if (data.status == "tunggu") ...[
                                SizedBox(height: 5.h),
                                Row(
                                  children: [
                                    Expanded(
                                      flex: 1,
                                      child: ElevatedButton.icon(
                                        onPressed: () async {
                                          bool? shouldVerify =
                                              await _showVerifyDialog(context);
                                          if (shouldVerify == true) {
                                            await AdminController().verify(
                                              dataId: data.id.toString(),
                                              map: {
                                                "status": "terima",
                                                "catatan": "oke"
                                              },
                                              isPalawija: true,
                                            );
                                            setState(() {});
                                          }
                                        },
                                        icon: const Icon(
                                            Icons.verified_outlined,
                                            color: Colors.green),
                                        label: Text('Verifikasi',
                                            style: StyleTheme()
                                                .stylePrimary
                                                .copyWith(
                                                    fontSize: 14.sp,
                                                    color: Colors.green)),
                                        style: ElevatedButton.styleFrom(
                                          surfaceTintColor:
                                              ColorTheme().whiteColor,
                                          side: BorderSide(
                                              color: Colors.green, width: 2.w),
                                          shape: RoundedRectangleBorder(
                                            borderRadius:
                                                BorderRadius.circular(30.r),
                                          ),
                                        ),
                                      ),
                                    ),
                                    SizedBox(width: 10.w),
                                    Expanded(
                                      flex: 1,
                                      child: ElevatedButton.icon(
                                        onPressed: () async {
                                          bool? shouldReject =
                                              await _showRejectedDialog(
                                                  context);
                                          if (shouldReject == true) {
                                            await AdminController().verify(
                                              dataId: data.id.toString(),
                                              map: {
                                                "status": "tolak",
                                                "catatan": ulasan.text
                                              },
                                              isPalawija: true,
                                            );
                                            setState(() {
                                              ulasan.clear();
                                            });
                                          }
                                        },
                                        icon: const Icon(
                                            Icons.dangerous_outlined,
                                            color: Colors.red),
                                        label: Text('Tolak',
                                            style: StyleTheme()
                                                .stylePrimary
                                                .copyWith(
                                                    fontSize: 14.sp,
                                                    color: Colors.red)),
                                        style: ElevatedButton.styleFrom(
                                          surfaceTintColor:
                                              ColorTheme().whiteColor,
                                          side: BorderSide(
                                              color: Colors.red, width: 2.w),
                                          shape: RoundedRectangleBorder(
                                            borderRadius:
                                                BorderRadius.circular(30.r),
                                          ),
                                        ),
                                      ),
                                    ),
                                  ],
                                )
                              ],
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
          SizedBox(height: 10.h),
        ],
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
            icon: Icons.dataset,
            label: 'Status',
            selectedItem: selectedStatus.isEmpty ? null : selectedStatus,
            items: status.map(
              (value) {
                return DropdownMenuItem<String>(
                  value: value,
                  child: Text(UserController().toCamelCase(value)),
                );
              },
            ).toList(),
            hint: 'Pilih Status',
            validator: (value) =>
                value == null ? 'Pilih status terlebih dahulu' : null,
            onChanged: (newValue) {
              setState(() {
                selectedStatus = newValue!;
              });
            },
            onSaved: (newValue) {
              setState(() {
                selectedStatus = newValue!;
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
                  selectedStatus = "";
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

  Future<bool> _showVerifyDialog(BuildContext context) async {
    return await showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Column(
            children: [
              Text('Konfirmasi Aksi'),
              Divider(),
            ],
          ),
          content: Text(
            "Apakah anda yakin ingin memverifikasi data ini?",
            style: StyleTheme().styleBlack.copyWith(fontSize: 14.sp),
          ),
          actions: <Widget>[
            TextButton(
              onPressed: () async {
                Navigator.of(context).pop(false);
              },
              child: Text(
                "Tidak",
                style: StyleTheme()
                    .stylePrimary
                    .copyWith(fontSize: 14.sp, color: Colors.red),
              ),
            ),
            TextButton(
              onPressed: () async {
                Navigator.of(context).pop(true);
              },
              child: Text(
                "Ya",
                style: StyleTheme().stylePrimary.copyWith(fontSize: 14.sp),
              ),
            ),
          ],
        );
      },
    );
  }

  Future<bool> _showRejectedDialog(BuildContext context) async {
    return await showDialog(
      context: context,
      builder: (BuildContext context) {
        return Form(
          key: formKey,
          child: AlertDialog(
            title: const Column(
              children: [
                Text('Konfirmasi Aksi'),
                Divider(),
              ],
            ),
            content: Column(
              mainAxisSize: MainAxisSize.min,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  "Apakah anda yakin ingin menolak data ini?",
                  style: StyleTheme().styleBlack.copyWith(fontSize: 14.sp),
                ),
                SizedBox(height: 10.h),
                const Text("Berikan Ulasan:"),
                TextFormField(
                  controller: ulasan,
                  decoration: InputDecoration(
                    isDense: true,
                    filled: true,
                    fillColor: ColorTheme().whiteColor,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(10.r),
                    ),
                  ),
                  maxLines: 5,
                  validator: (value) {
                    return value == null || value.isEmpty
                        ? "Ulasan tidak boleh kosong"
                        : null;
                  },
                )
              ],
            ),
            actions: <Widget>[
              TextButton(
                onPressed: () async {
                  setState(() {
                    ulasan.clear();
                  });
                  Navigator.of(context).pop(false);
                },
                child: Text(
                  "Tidak",
                  style: StyleTheme()
                      .stylePrimary
                      .copyWith(fontSize: 14.sp, color: Colors.red),
                ),
              ),
              TextButton(
                onPressed: () async {
                  if (formKey.currentState!.validate()) {
                    Navigator.of(context).pop(true);
                  }
                },
                child: Text(
                  "Ya",
                  style: StyleTheme().stylePrimary.copyWith(fontSize: 14.sp),
                ),
              ),
            ],
          ),
        );
      },
    );
  }
}
