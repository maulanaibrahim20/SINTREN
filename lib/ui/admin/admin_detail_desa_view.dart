import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class AdminDetailDesaView extends StatefulWidget {
  const AdminDetailDesaView({super.key});

  @override
  State<AdminDetailDesaView> createState() => _AdminDetailDesaViewState();
}

class _AdminDetailDesaViewState extends State<AdminDetailDesaView> {
  bool isSearchOpen = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      appBar: AppBar(
        foregroundColor: ColorTheme().whiteColor,
        title: isSearchOpen
            ? PreferredSize(
                preferredSize: const Size.fromHeight(60.0),
                child: TextField(
                  decoration: InputDecoration(
                    prefixIcon: const Icon(Icons.search),
                    suffixIcon: IconButton(
                      icon: const Icon(
                        Icons.clear,
                        color: Colors.red,
                      ),
                      onPressed: () {
                        setState(() {
                          isSearchOpen = false;
                        });
                      },
                    ),
                    hintText: 'Cari berdasarkan desa/tanggal...',
                    filled: true,
                    fillColor: ColorTheme().whiteColor,
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(30.0),
                    ),
                    contentPadding:
                        const EdgeInsets.symmetric(horizontal: 16.0),
                  ),
                  // onChanged: controller.updateSearchText,
                ),
              )
            : Text(
                'Detail Desa',
                style: StyleTheme().styleWhite.copyWith(
                      fontSize: 20,
                      fontWeight: FontWeight.w500,
                    ),
              ),
        actions: [
          isSearchOpen
              ? const SizedBox.shrink()
              : IconButton(
                  icon: Icon(
                    Icons.search,
                    color: ColorTheme().whiteColor,
                  ),
                  onPressed: () {
                    setState(
                      () {
                        isSearchOpen = true;
                      },
                    );
                  },
                ),
        ],
        backgroundColor: ColorTheme().primaryColor,
      ),
      floatingActionButton: FloatingActionButton(
        heroTag: 'filter_detail_desa',
        onPressed: () {},
        backgroundColor: ColorTheme().primaryColor,
        foregroundColor: ColorTheme().whiteColor,
        child: const Icon(
          Icons.filter_list,
        ),
      ),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Card(
            margin: const EdgeInsets.symmetric(horizontal: 15, vertical: 10),
            surfaceTintColor: ColorTheme().whiteColor,
            elevation: 3,
            child: Column(
              children: [
                Container(
                  width: MediaQuery.of(context).size.width,
                  height: 30,
                  decoration: BoxDecoration(
                    color: ColorTheme().primaryColor,
                    borderRadius: const BorderRadius.only(
                      topLeft: Radius.circular(10),
                      topRight: Radius.circular(10),
                    ),
                  ),
                  child: Padding(
                    padding:
                        const EdgeInsets.symmetric(horizontal: 15, vertical: 5),
                    child: Text(
                      "Desa Lohbener",
                      style: StyleTheme()
                          .styleWhite
                          .copyWith(fontSize: 14, fontWeight: FontWeight.w500),
                    ),
                  ),
                ),
                SizedBox(
                  height: 150,
                  child: Column(
                    children: [
                      Row(
                        mainAxisAlignment: MainAxisAlignment.start,
                        children: [
                          Padding(
                            padding: const EdgeInsets.symmetric(
                                horizontal: 15, vertical: 15),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.center,
                              children: [
                                Text(
                                  "Progres penyuluhan \nbulan ini :",
                                  style: StyleTheme().styleBlack,
                                ),
                                RichText(
                                  text: TextSpan(
                                    text: "90",
                                    style: StyleTheme().stylePrimary.copyWith(
                                        fontSize: 64,
                                        fontWeight: FontWeight.w500),
                                    children: [
                                      TextSpan(
                                        text: "%",
                                        style: StyleTheme()
                                            .stylePrimary
                                            .copyWith(fontSize: 32),
                                      ),
                                    ],
                                  ),
                                ),
                              ],
                            ),
                          ),
                          Container(
                            height: 130,
                            width: 3,
                            decoration: BoxDecoration(
                                borderRadius: BorderRadius.circular(10),
                                color: Colors.grey),
                          ),
                          const SizedBox(
                            width: 20,
                          ),
                          Expanded(
                            child: Padding(
                              padding:
                                  const EdgeInsets.symmetric(horizontal: 10),
                              child: Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceEvenly,
                                children: [
                                  Row(
                                    mainAxisAlignment:
                                        MainAxisAlignment.spaceBetween,
                                    children: [
                                      RichText(
                                        text: TextSpan(
                                          text: "Penyuluh: ",
                                          style: StyleTheme().styleBlack,
                                          children: [
                                            TextSpan(
                                              text: "\nSammir Emrich",
                                              style: StyleTheme()
                                                  .styleBlack
                                                  .copyWith(
                                                      fontWeight:
                                                          FontWeight.w500),
                                            ),
                                          ],
                                        ),
                                      ),
                                      IconButton(
                                        onPressed: () {},
                                        icon: Icon(
                                          Icons.remove_red_eye_rounded,
                                          color: ColorTheme().primaryColor,
                                        ),
                                      ),
                                    ],
                                  ),
                                  Row(
                                    mainAxisAlignment:
                                        MainAxisAlignment.spaceBetween,
                                    children: [
                                      RichText(
                                        text: TextSpan(
                                          text: "Total Luas Lahan: ",
                                          style: StyleTheme().styleBlack,
                                          children: [
                                            TextSpan(
                                              text: "\n100 hektar",
                                              style: StyleTheme()
                                                  .styleBlack
                                                  .copyWith(
                                                      fontWeight:
                                                          FontWeight.w500),
                                            ),
                                          ],
                                        ),
                                      ),
                                      IconButton(
                                        onPressed: () {},
                                        icon: Icon(
                                          Icons.remove_red_eye_rounded,
                                          color: ColorTheme().primaryColor,
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                            ),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 5),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 20),
            child: Container(
              padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
              width: MediaQuery.of(context).size.width * 0.7,
              height: 30,
              decoration: BoxDecoration(
                color: ColorTheme().primaryColor,
                borderRadius: const BorderRadius.only(
                  topLeft: Radius.circular(10),
                  bottomLeft: Radius.circular(10),
                  topRight: Radius.circular(50),
                ),
              ),
              child: Text(
                "Laporan Penyuluhan",
                style: StyleTheme()
                    .styleWhite
                    .copyWith(fontSize: 14, fontWeight: FontWeight.w500),
              ),
            ),
          ),
          const SizedBox(height: 10),
          Expanded(
            child: ListView(
              children: [
                Column(
                  children: List.generate(
                    10,
                    (index) {
                      return Card(
                        margin:
                            const EdgeInsets.only(right: 15, left: 15, bottom: 10),
                        surfaceTintColor: ColorTheme().whiteColor,
                        elevation: 3,
                        child: SizedBox(
                          height: 180,
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
                                    crossAxisAlignment:
                                        CrossAxisAlignment.start,
                                    children: [
                                      Row(
                                        mainAxisAlignment:
                                            MainAxisAlignment.spaceBetween,
                                        children: [
                                          Text(
                                            "Hibrida",
                                            style: StyleTheme()
                                                .styleBlack
                                                .copyWith(
                                                    fontWeight: FontWeight.w500,
                                                    fontSize: 16),
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
                                            style: StyleTheme()
                                                .styleBlack
                                                .copyWith(
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
                                            style: StyleTheme()
                                                .styleBlack
                                                .copyWith(
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
                                            style: StyleTheme()
                                                .styleBlack
                                                .copyWith(
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
                                            style: StyleTheme()
                                                .styleBlack
                                                .copyWith(
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
                                            style: StyleTheme()
                                                .styleBlack
                                                .copyWith(
                                                  fontWeight: FontWeight.bold,
                                                ),
                                          ),
                                        ],
                                      ),
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
          ),
        ],
      ),
    );
  }
}
