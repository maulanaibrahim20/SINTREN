import 'package:flutter/material.dart';
import 'package:percent_indicator/percent_indicator.dart';
import 'package:sintren_mobile/ui/admin/admin_detail_desa_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class AdminDesaView extends StatefulWidget {
  const AdminDesaView({super.key});

  @override
  State<AdminDesaView> createState() => _AdminDesaViewState();
}

class _AdminDesaViewState extends State<AdminDesaView> {
  bool isSearchOpen = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      appBar: AppBar(
        elevation: 0,
        flexibleSpace: Container(
          decoration: BoxDecoration(gradient: ColorTheme().linearColor),
        ),
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
                    hintText: 'Cari desa...',
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
                'List Desa',
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
        heroTag: 'filter_desa',
        onPressed: () {},
        backgroundColor: ColorTheme().primaryColor,
        foregroundColor: ColorTheme().whiteColor,
        child: const Icon(
          Icons.filter_list,
        ),
      ),
      body: ListView(
        children: [
          const SizedBox(height: 10),
          Column(
            children: List.generate(
              10,
              (index) {
                return GestureDetector(
                  onTap: () {
                    Navigator.push(
                        context,
                        MaterialPageRoute(
                            builder: (_) => const AdminDetailDesaView()));
                  },
                  child: Card(
                    margin: const EdgeInsets.symmetric(horizontal: 15, vertical: 5),
                    elevation: 3,
                    surfaceTintColor: ColorTheme().whiteColor,
                    color: ColorTheme().whiteColor,
                    child: Column(
                      children: [
                        const SizedBox(height: 20),
                        Padding(
                          padding: const EdgeInsets.symmetric(horizontal: 20),
                          child: Row(
                            children: [
                              Container(
                                height: 50,
                                width: 50,
                                decoration: BoxDecoration(
                                  shape: BoxShape.circle,
                                  gradient: ColorTheme().linearColor,
                                ),
                                child: Center(
                                  child: Icon(
                                    Icons.home_rounded,
                                    color: ColorTheme().whiteColor,
                                    size: 30,
                                  ),
                                ),
                              ),
                              const SizedBox(width: 15),
                              Column(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  Text(
                                    "Desa Lohbener",
                                    style: StyleTheme().stylePrimary.copyWith(
                                        fontSize: 20,
                                        fontWeight: FontWeight.bold),
                                  ),
                                  Text(
                                    maxLines: 1,
                                    "Luas Lahan: 1000 Hektar",
                                    style: StyleTheme()
                                        .styleBlack
                                        .copyWith(color: Colors.grey[700]),
                                  )
                                ],
                              )
                            ],
                          ),
                        ),
                        const SizedBox(height: 10),
                        Stack(
                          children: [
                            const Divider(thickness: 2, color: Colors.grey),
                            Container(
                              color: ColorTheme().whiteColor,
                              margin: const EdgeInsets.only(left: 20),
                              padding: const EdgeInsets.symmetric(horizontal: 8.0),
                              child: Text(
                                "Progres bulan ini",
                                style: StyleTheme()
                                    .styleBlack
                                    .copyWith(color: Colors.black87),
                              ),
                            ),
                          ],
                        ),
                        const SizedBox(height: 10),
                        LinearPercentIndicator(
                          width: MediaQuery.of(context).size.width - 30,
                          animation: true,
                          lineHeight: 30,
                          animationDuration: 2000,
                          percent: 0.9,
                          center: Text(
                            "90.0%",
                            style: StyleTheme().styleWhite.copyWith(
                                fontWeight: FontWeight.w500, fontSize: 14),
                          ),
                          barRadius: const Radius.circular(10),
                          linearGradient: ColorTheme().linearColor,
                        ),
                        const SizedBox(height: 10),
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
