import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/detail_penyuluhan/detail_padi_view.dart';
import 'package:sintren_mobile/ui/penyuluh/detail_penyuluhan/detail_palawija_view.dart';
import 'package:sintren_mobile/ui/penyuluh/histori_penyuluhan_view.dart';

class DetailPenyuluhanView extends StatefulWidget {
  const DetailPenyuluhanView(
      {super.key,
      required this.index,
      required this.date,
      required this.desaId,
      this.desaName = "", required this.isVerify});

  final int index;
  final String date;
  final String desaId;
  final String desaName;
  final bool isVerify;

  @override
  State<DetailPenyuluhanView> createState() => _DetailPenyuluhanViewState();
}

class _DetailPenyuluhanViewState extends State<DetailPenyuluhanView>
    with SingleTickerProviderStateMixin {
  final List<Tab> tabs = [
    const Tab(text: 'Padi'),
    const Tab(text: 'Palawija'),
  ];

  late TabController _tabController =
      TabController(length: tabs.length, vsync: this);

  @override
  void initState() {
    setState(() {});
    super.initState();
    _tabController = TabController(
      length: tabs.length,
      vsync: this,
      initialIndex: widget.index,
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        automaticallyImplyLeading: false,
        leading: IconButton(
          icon: Icon(
            Icons.arrow_back,
            color: ColorTheme().whiteColor,
          ),
          onPressed: () {
            Navigator.pushAndRemoveUntil(
              context,
              MaterialPageRoute(builder: (_) => const HistoriPenyuluhanView()),
              (route) => false,
            );
          },
        ),
        elevation: 0,
        centerTitle: false,
        foregroundColor: ColorTheme().whiteColor,
        flexibleSpace: Container(
          decoration: BoxDecoration(gradient: ColorTheme().linearColor),
        ),
        title: Text(
          'Histori Penyuluhan',
          style: StyleTheme().styleWhite.copyWith(
                fontSize: 20,
                fontWeight: FontWeight.w500,
              ),
        ),
        actions: [
          IconButton(
            icon: Icon(
              Icons.refresh_rounded,
              color: ColorTheme().whiteColor,
            ),
            onPressed: () {
              setState(
                () {},
              );
            },
          ),
        ],
        backgroundColor: ColorTheme().primaryColor,
      ),
      body: Container(
        color: ColorTheme().bgColor,
        child: Column(
          children: [
            Container(
              color: ColorTheme().primaryColor,
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
                children: [
                  DetailPadiView(
                    date: widget.date,
                    desaId: widget.desaId,
                    desaName: widget.desaName,
                    isVerify: widget.isVerify,
                  ),
                  DetailPalawijaView(
                    date: widget.date,
                    desaId: widget.desaId,
                    desaName: widget.desaName,
                    isVerify: widget.isVerify,
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
}
