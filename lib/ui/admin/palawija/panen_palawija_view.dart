import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/admin/components/list_card.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';

class PanenPalawijaView extends StatefulWidget {
  const PanenPalawijaView({super.key});

  @override
  State<PanenPalawijaView> createState() => _PanenPalawijaViewState();
}

class _PanenPalawijaViewState extends State<PanenPalawijaView> {
  bool isSearchOpen = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      body: Padding(
        padding: const EdgeInsets.only(top: 10),
        child: ListView(
          children: [
            Column(
              children: List.generate(
                10,
                (index) {
                  return const ListCard(
                      jenisPadi: "Hibrida",
                      jenisIrigasi: "Irigasi Tersier",
                      tanggal: "10/5/2024",
                      bantuan: "Bantuan Pemerintah",
                      jenisLahan: "Lahan Sawah",
                      jenisData: "Panen:",
                      value: "1000",desa: 'Lohbener',);
                },
              ),
            ),
            const SizedBox(height: 90),
          ],
        ),
      ),
    );
  }
}

