import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/admin/padi/panen_padi_view.dart';
import 'package:sintren_mobile/ui/admin/padi/puso_padi_view.dart';
import 'package:sintren_mobile/ui/admin/padi/tanam_padi_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class AdminPalawijaView extends StatefulWidget {
  const AdminPalawijaView({super.key});

  @override
  State<AdminPalawijaView> createState() => _AdminPalawijaViewState();
}

class _AdminPalawijaViewState extends State<AdminPalawijaView>
    with SingleTickerProviderStateMixin {
  final List<Tab> tabs = [
    const Tab(text: 'Panen'),
    const Tab(text: 'Tanam'),
    const Tab(text: 'Puso'),
  ];

  bool isSearchOpen = false;

  late TabController _tabController =
      TabController(length: tabs.length, vsync: this);

  @override
  void initState() {
    super.initState();
    _tabController = TabController(length: tabs.length, vsync: this);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        elevation: 0,
        centerTitle: false,
        foregroundColor: ColorTheme().whiteColor,
        automaticallyImplyLeading: isSearchOpen ? false : true,
        flexibleSpace: Container(
          decoration: BoxDecoration(gradient: ColorTheme().linearColor),
        ),
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
                'Laporan Palawija',
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
        heroTag: 'filter_palawija',
        onPressed: () {},
        backgroundColor: ColorTheme().primaryColor,
        foregroundColor: ColorTheme().whiteColor,
        child: const Icon
        (
          Icons.filter_list,
        ),
      ),
      body: Container(
        color: ColorTheme().bgColor, // Warna latar belakang body
        child: Column(
          children: [
            Container(
              color: ColorTheme().primaryColor, // Warna latar belakang TabBar
              child: TabBar(
                controller: _tabController,
                tabs: tabs,
                labelColor: ColorTheme().whiteColor,
                labelStyle: StyleTheme()
                    .stylePrimary
                    .copyWith(fontSize: 16, fontWeight: FontWeight.bold),
                unselectedLabelColor: Colors.grey,
                indicatorColor: ColorTheme().whiteColor,
                indicatorWeight: 2.0,
                indicatorSize: TabBarIndicatorSize.tab,
              ),
            ),
            Expanded(
              child: TabBarView(
                controller: _tabController,
                children: const [
                  PanenPadiView(),
                  TanamPadiView(),
                  PusoPadiView()
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
}
