import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/admin/components/list_card.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';

class PusoPalawijaView extends StatefulWidget {
  const PusoPalawijaView({super.key});

  @override
  State<PusoPalawijaView> createState() => _PusoPalawijaViewState();
}

class _PusoPalawijaViewState extends State<PusoPalawijaView> {
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
                      jenisData: "Puso:",
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
