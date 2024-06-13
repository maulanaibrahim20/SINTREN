import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/penyuluh_model.dart';
import 'package:sintren_mobile/ui/admin/detail_penugasan_view.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';

class AdminPenugasanView extends StatefulWidget {
  const AdminPenugasanView({super.key});

  @override
  State<AdminPenugasanView> createState() => _AdminPenugasanViewState();
}

class _AdminPenugasanViewState extends State<AdminPenugasanView> {
  final adminC = AdminController();

  @override
  void initState() {
    super.initState();
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
          'Penugasan Penyuluh',
          style: StyleTheme().styleWhite.copyWith(
                fontSize: 20,
                fontWeight: FontWeight.w500,
              ),
        ),
        backgroundColor: ColorTheme().primaryColor,
      ),
      body: FutureBuilder<List<Penyuluh>>(
        future: adminC.getPenyuluh(),
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return const Center(child: CircularProgressIndicator());
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
            return const Center(child: Text('Data Penyuluh Kosong'));
          } else {
            List<Penyuluh> penyuluhList = snapshot.data!;
            return ListView.builder(
              padding: const EdgeInsets.only(top: 10),
              itemCount: penyuluhList.length,
              itemBuilder: (context, index) {
                Penyuluh penyuluh = penyuluhList[index];
                return GestureDetector(
                  onTap: () async {
                    await Navigator.push(
                      context,
                      MaterialPageRoute(
                        builder: (_) => DetailPenugasanView(
                          id: penyuluh.id,
                        ),
                      ),
                    );
                    setState(() {});
                  },
                  child: Card(
                    margin: const EdgeInsets.symmetric(
                        horizontal: 20, vertical: 10),
                    elevation: 3,
                    surfaceTintColor: ColorTheme().whiteColor,
                    color: ColorTheme().whiteColor,
                    child: Column(
                      children: [
                        const SizedBox(height: 10),
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
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceEvenly,
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
                );
              },
            );
          }
        },
      ),
    );
  }
}
