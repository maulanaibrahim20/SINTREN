import 'package:flutter/material.dart';
import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/penyuluh_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class DetailPenugasanView extends StatefulWidget {
  const DetailPenugasanView({super.key, required this.id});

  final String id;

  @override
  State<DetailPenugasanView> createState() => _AdminPenugasanViewState();
}

class _AdminPenugasanViewState extends State<DetailPenugasanView> {
  final adminC = AdminController();
  late Future<List<Penyuluh>> _futurePenyuluh;

  @override
  void initState() {
    super.initState();
    _futurePenyuluh = adminC.getPenyuluh();
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
        title: Text(
          'Detail Penugasan',
          style: StyleTheme().styleWhite.copyWith(
                fontSize: 20,
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
              // _filter(context);
            },
          ),
          IconButton(
              onPressed: () async {
                EasyLoading.show(status: "Sinkronisasi Data");
                // await _synchronizeData();
                EasyLoading.dismiss();
              },
              icon: Icon(
                Icons.refresh_rounded,
                color: ColorTheme().whiteColor,
              ))
        ],
        backgroundColor: ColorTheme().primaryColor,
      ),
      body: FutureBuilder<List<Penyuluh>>(
        future: _futurePenyuluh,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: const CircularProgressIndicator());
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
                        fontSize: 18,
                        fontWeight: FontWeight.w500,
                        color: Colors.grey),
                  ),
                ],
              ),
            );
          } else if (!snapshot.hasData || snapshot.data!.isEmpty) {
            return const Center(child: Text('Data Penugasan Kosong'));
          } else {
            Penyuluh? penyuluh;
            try {
              penyuluh = snapshot.data!.firstWhere((p) => p.id == widget.id);
            } catch (e) {
              penyuluh = null;
            }

            if (penyuluh == null) {
              return const Center(child: Text('Belum Dilakukan Penugasan'));
            }
            return Column(
              children: [
                Card(
                  margin:
                      const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                  elevation: 3,
                  surfaceTintColor: ColorTheme().whiteColor,
                  color: ColorTheme().whiteColor,
                  child: Column(
                    children: [
                      SizedBox(height: 10),
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
                                  Icons.person,
                                  color: ColorTheme().whiteColor,
                                  size: 30,
                                ),
                              ),
                            ),
                            const SizedBox(width: 15),
                            Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                              children: [
                                Text(
                                  UserController().toCamelCase(penyuluh.name),
                                  style: StyleTheme().stylePrimary.copyWith(
                                      fontSize: 20,
                                      fontWeight: FontWeight.bold),
                                ),
                                Text(
                                  "Email: ${penyuluh.email}",
                                  style: StyleTheme().styleBlack.copyWith(
                                      fontWeight: FontWeight.bold,
                                      color: Colors.grey[700],
                                      fontSize: 14),
                                ),
                                Text(
                                  "No. Telp: ${penyuluh.noTelp}",
                                  style: StyleTheme().styleBlack.copyWith(
                                      fontWeight: FontWeight.bold,
                                      color: Colors.grey[700],
                                      fontSize: 14),
                                ),
                                Text(
                                  "Alamat: ${penyuluh.alamat}",
                                  style: StyleTheme().styleBlack.copyWith(
                                      fontWeight: FontWeight.bold,
                                      color: Colors.grey[700],
                                      fontSize: 14),
                                ),
                              ],
                            )
                          ],
                        ),
                      ),
                      const SizedBox(height: 10),
                    ],
                  ),
                ),
                Expanded(
                  child: ListView.builder(
                    padding: const EdgeInsets.only(top: 10),
                    itemCount: penyuluh.penugasan.length,
                    itemBuilder: (context, index) {
                      Penugasan penugasan = penyuluh!.penugasan[index];
                      return ListTile(
                        title: Text(penugasan.desaName),
                        subtitle: Text('Desa ID: ${penugasan.desaId}'),
                      );
                    },
                  ),
                ),
              ],
            );
          }
        },
      ),
    );
  }
}
