import 'package:flutter/material.dart';
import 'package:flutter_screenutil/flutter_screenutil.dart';
import 'package:sintren_mobile/controllers/penyuluh/padi_controller.dart';
import 'package:sintren_mobile/controllers/penyuluh/palawija_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/padi_model.dart';
import 'package:sintren_mobile/models/palawija_model.dart';
import 'package:sintren_mobile/models/pengairan_model.dart';
import 'package:sintren_mobile/ui/admin/detail_penyuluhan_dinas/penyuluhan_padi_view.dart';
import 'package:sintren_mobile/ui/admin/detail_penyuluhan_dinas/penyuluhan_palawija_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/dropdown_button_component.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class DinasLandingPenyuluhanView extends StatefulWidget {
  const DinasLandingPenyuluhanView({super.key});

  @override
  State<DinasLandingPenyuluhanView> createState() =>
      _DinasLandingPenyuluhanViewState();
}

class _DinasLandingPenyuluhanViewState extends State<DinasLandingPenyuluhanView>
    with SingleTickerProviderStateMixin {
  final List<Tab> tabs = [
    const Tab(text: 'Padi'),
    const Tab(text: 'Palawija'),
  ];

  final padiC = PadiController();
  final palawijaC = PalawijaController();
  TextEditingController searchC = TextEditingController();
  late List<PengairanModel> pengiranList;
  late List<PadiModel> padiList;
  late List<PalawijaModel> palawijaList;
  late String selectedJenisLahanValue;
  late String selectedBantuanValue;
  late PengairanModel? selectedJenisPengairanValue;
  late PadiModel? selectedJenisPadiValue;
  late PalawijaModel? selectedJenisPalawijaValue;
  late String selectedTipeDataValue;
  bool _isLoading = true;
  bool _isSearching = false;

  late TabController _tabController =
      TabController(length: tabs.length, vsync: this);

  @override
  void initState() {
    setState(() {});
    super.initState();
    _initializeData();
    _tabController = TabController(
      length: tabs.length,
      vsync: this,
      initialIndex: 0,
    );
  }

  Future<void> _initializeData() async {
    pengiranList = await padiC.getPengairan();
    padiList = await padiC.getPadi();
    palawijaList = await palawijaC.getPalawija();
    setState(() {
      selectedJenisLahanValue = '';
      selectedBantuanValue = '';
      selectedJenisPengairanValue = null;
      selectedJenisPadiValue = null;
      selectedJenisPalawijaValue = null;
      selectedTipeDataValue = '';

      _isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        centerTitle: false,
        foregroundColor: ColorTheme().whiteColor,
        flexibleSpace: Container(
          decoration: BoxDecoration(gradient: ColorTheme().linearColor),
        ),
        title: _isSearching
            ? PreferredSize(
                preferredSize: Size.fromHeight(60.h),
                child: TextField(
                  controller: searchC,
                  decoration: InputDecoration(
                    prefixIcon: const Icon(Icons.search),
                    suffixIcon: IconButton(
                      icon: const Icon(
                        Icons.clear,
                        color: Colors.red,
                      ),
                      onPressed: () {
                        setState(() {
                          _isSearching = false;
                        });
                      },
                    ),
                    hintText: 'Cari desa/kecamatan...',
                    filled: true,
                    fillColor: ColorTheme().whiteColor,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(30.r),
                    ),
                    contentPadding: EdgeInsets.symmetric(horizontal: 16.w),
                  ),
                  onChanged: (value) {
                    setState(() {
                      searchC.text = value;
                    });
                  },
                ),
              )
            : Text(
                'Data Penyuluhan',
                style: StyleTheme().styleWhite.copyWith(
                      fontSize: 20.sp,
                      fontWeight: FontWeight.w500,
                    ),
              ),
        actions: [
          if (!_isSearching) ...[
            IconButton(
              onPressed: () {
                setState(() {
                  _isSearching = true;
                });
              },
              icon: Icon(
                Icons.search,
                color: ColorTheme().whiteColor,
                size: 24.sp,
              ),
            ),
            IconButton(
              icon: Icon(
                Icons.filter_list,
                color: ColorTheme().whiteColor,
                size: 24.sp,
              ),
              onPressed: () {
                _showDialogFilter(context);
              },
            ),
          ]
        ],
        backgroundColor: ColorTheme().primaryColor,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : Container(
              color: ColorTheme().bgColor,
              child: Column(
                children: [
                  Container(
                    color: ColorTheme().primaryColor,
                    child: TabBar(
                      controller: _tabController,
                      tabs: tabs,
                      labelColor: ColorTheme().whiteColor,
                      labelStyle: StyleTheme().stylePrimary.copyWith(
                          fontSize: 16.sp, fontWeight: FontWeight.bold),
                      unselectedLabelColor: Colors.grey,
                      indicatorColor: ColorTheme().whiteColor,
                      indicatorWeight: 2.0.w,
                      indicatorSize: TabBarIndicatorSize.tab,
                    ),
                  ),
                  Expanded(
                    child: TabBarView(
                      controller: _tabController,
                      children: [
                        PenyuluhanPadiView(
                          jenisLahan: selectedJenisLahanValue == ""
                              ? null
                              : selectedJenisLahanValue,
                          jenisBantuan: selectedBantuanValue == ""
                              ? null
                              : selectedBantuanValue,
                          jenisPadi: selectedJenisPadiValue?.name,
                          jenisPengairan: selectedJenisPengairanValue?.name,
                          jenisData: selectedTipeDataValue == ""
                              ? null
                              : selectedTipeDataValue,
                          search: searchC.text == "" ? null : searchC.text,
                        ),
                        PenyuluhanPalawijaView(
                          jenisLahan: selectedJenisLahanValue == ""
                              ? null
                              : selectedJenisLahanValue,
                          jenisBantuan: selectedBantuanValue == ""
                              ? null
                              : selectedBantuanValue,
                          jenisPalawija: selectedJenisPalawijaValue?.name,
                          jenisData: selectedTipeDataValue == ""
                              ? null
                              : selectedTipeDataValue,
                          search: searchC.text == "" ? null : searchC.text,
                        )
                      ],
                    ),
                  ),
                ],
              ),
            ),
    );
  }

  @override
  void dispose() {
    _tabController.dispose();
    super.dispose();
  }

  void _showDialogFilter(BuildContext context) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          surfaceTintColor: ColorTheme().whiteColor,
          title: const Column(
            children: [
              Text('Filter Data'),
              Divider(),
            ],
          ),
          content: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              DropdownButtonComponent(
                icon: Icons.date_range,
                label: "Jenis Lahan",
                selectedItem: selectedJenisLahanValue.isEmpty
                    ? null
                    : selectedJenisLahanValue,
                items: padiC.jenisLahan.map(
                  (value) {
                    return DropdownMenuItem<String>(
                      value: value,
                      child: Text(UserController().toCamelCase(value)),
                    );
                  },
                ).toList(),
                hint: "Pilih Jenis Lahan",
                validator: (value) {
                  return null;
                },
                onChanged: (newValue) {
                  setState(() {
                    selectedJenisLahanValue = newValue!;
                  });
                },
                onSaved: (newValue) {
                  setState(() {
                    selectedJenisLahanValue = newValue!;
                  });
                },
              ),
              SizedBox(height: 10.h),
              if (_tabController.index == 0) ...[
                DropdownButtonComponent(
                  icon: Icons.water,
                  label: 'Pengairan',
                  selectedItem: selectedJenisPengairanValue,
                  items: pengiranList.map((pengiran) {
                    return DropdownMenuItem<PengairanModel>(
                      value: pengiran,
                      child: Text(UserController().toCamelCase(pengiran.name)),
                    );
                  }).toList(),
                  hint: 'Pilih Pengiran',
                  validator: (value) {
                    return null;
                  },
                  onChanged: (newValue) {
                    setState(() {
                      selectedJenisPengairanValue = newValue!;
                    });
                  },
                  onSaved: (newValue) {
                    setState(() {
                      selectedJenisPengairanValue = newValue!;
                    });
                  },
                ),
                SizedBox(height: 10.h),
              ],
              if (_tabController.index == 1) ...[
                DropdownButtonComponent(
                  icon: Icons.date_range,
                  label: "Jenis Palawija",
                  selectedItem: selectedJenisPalawijaValue,
                  items: palawijaList.map((palawija) {
                    return DropdownMenuItem<PalawijaModel>(
                      value: palawija,
                      child: Text(UserController().toCamelCase(palawija.name)),
                    );
                  }).toList(),
                  hint: "Pilih Jenis Palawija",
                  validator: (value) {
                    return null;
                  },
                  onChanged: (newValue) {
                    setState(() {
                      selectedJenisPalawijaValue = newValue!;
                    });
                  },
                  onSaved: (newValue) {
                    setState(() {
                      selectedJenisPalawijaValue = newValue!;
                    });
                  },
                ),
              ] else ...[
                DropdownButtonComponent(
                  icon: Icons.date_range,
                  label: "Jenis Padi",
                  selectedItem: selectedJenisPadiValue,
                  items: padiList.map((padi) {
                    return DropdownMenuItem<PadiModel>(
                      value: padi,
                      child: Text(UserController().toCamelCase(padi.name)),
                    );
                  }).toList(),
                  hint: "Pilih Jenis Padi",
                  validator: (value) {
                    return null;
                  },
                  onChanged: (newValue) {
                    setState(() {
                      selectedJenisPadiValue = newValue!;
                    });
                  },
                  onSaved: (newValue) {
                    setState(() {
                      selectedJenisPadiValue = newValue!;
                    });
                  },
                ),
              ],
              SizedBox(height: 10.h),
              DropdownButtonComponent(
                icon: Icons.date_range,
                label: "Jenis Bantuan",
                selectedItem:
                    selectedBantuanValue.isEmpty ? null : selectedBantuanValue,
                items: padiC.bantuan.map(
                  (value) {
                    return DropdownMenuItem<String>(
                      value: value,
                      child: Text(UserController().toCamelCase(value)),
                    );
                  },
                ).toList(),
                hint: "Pilih Jenis Bantuan",
                validator: (value) {
                  return null;
                },
                onChanged: (newValue) {
                  setState(() {
                    selectedBantuanValue = newValue!;
                  });
                },
                onSaved: (newValue) {
                  setState(() {
                    selectedBantuanValue = newValue!;
                  });
                },
              ),
              SizedBox(height: 10.h),
              DropdownButtonComponent(
                icon: Icons.type_specimen,
                label: "Jenis Data",
                selectedItem: selectedTipeDataValue.isEmpty
                    ? null
                    : selectedTipeDataValue,
                items: padiC.tipeData.map(
                  (value) {
                    return DropdownMenuItem<String>(
                      value: value,
                      child: Text(UserController().toCamelCase(value)),
                    );
                  },
                ).toList(),
                hint: "Pilih Jenis Data",
                validator: (value) {
                  return null;
                },
                onChanged: (newValue) {
                  setState(() {
                    selectedTipeDataValue = newValue!;
                  });
                },
                onSaved: (newValue) {
                  setState(() {
                    selectedTipeDataValue = newValue!;
                  });
                },
              ),
              SizedBox(height: 10.h),
            ],
          ),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.pop(context);
              },
              child: Text(
                "Tutup",
                style: StyleTheme().stylePrimary.copyWith(
                    color: Colors.red,
                    fontSize: 16.sp), // Using screen_util for fontSize
              ),
            ),
            TextButton(
              onPressed: () {
                setState(() {
                  selectedJenisLahanValue = '';
                  selectedBantuanValue = '';
                  selectedJenisPengairanValue = null;
                  selectedJenisPadiValue = null;
                  selectedJenisPalawijaValue = null;
                  selectedTipeDataValue = '';
                });
              },
              child: Text(
                "Reset",
                style: StyleTheme().stylePrimary.copyWith(
                    fontSize: 16.sp), // Using screen_util for fontSize
              ),
            ),
          ],
        );
      },
    );
  }
}
