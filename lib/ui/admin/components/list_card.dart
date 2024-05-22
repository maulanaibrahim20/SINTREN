import 'package:flutter/material.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class ListCard extends StatelessWidget {
  final String jenisPadi;
  final String jenisIrigasi;
  final String jenisLahan;
  final String jenisData;
  final String value;
  final String tanggal;
  final String bantuan;
  final String desa;
  const ListCard({
    super.key,
    required this.jenisPadi,
    required this.jenisIrigasi,
    required this.tanggal,
    required this.bantuan,
    required this.jenisLahan,
    required this.jenisData,
    required this.value,
    required this.desa,
  });

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(right: 15, left: 15, bottom: 10),
      surfaceTintColor: ColorTheme().whiteColor,
      elevation: 3,
      child: SizedBox(
        height: 120,
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
                padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          jenisPadi,
                          style: StyleTheme().styleBlack.copyWith(
                              fontWeight: FontWeight.w500, fontSize: 16),
                        ),
                        Text(
                          tanggal,
                          style: StyleTheme().styleBlack,
                        ),
                      ],
                    ),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          jenisLahan,
                          style: StyleTheme().styleBlack,
                        ),
                        Text(
                          desa,
                          style: StyleTheme().styleBlack,
                        ),
                      ],
                    ),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          jenisIrigasi,
                          style: StyleTheme().styleBlack,
                        ),
                        Text(
                          bantuan,
                          style: StyleTheme().styleBlack,
                        ),
                      ],
                    ),
                    const Divider(),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          jenisData,
                          style: StyleTheme().styleBlack.copyWith(
                              fontWeight: FontWeight.w500, fontSize: 14),
                        ),
                        Text(
                          value,
                          style: StyleTheme().styleBlack.copyWith(
                              fontWeight: FontWeight.w500, fontSize: 16),
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
  }
}
