import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/admin/admin_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/penyuluh_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/components/dropdown_button_component.dart';

class DetailPenugasanView extends StatefulWidget {
  const DetailPenugasanView({super.key, required this.id});

  final String id;

  @override
  State<DetailPenugasanView> createState() => _AdminPenugasanViewState();
}

class _AdminPenugasanViewState extends State<DetailPenugasanView> {
  final adminC = AdminController();
  late List<DesaModel> desaList;
  late DesaModel? selectedDesaValue;

  Future<void> _initializeData() async {
    desaList = await adminC.getDesa();
    selectedDesaValue = null;
    setState(() {});
  }

  @override
  void initState() {
    _initializeData();
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
          'Detail Penugasan',
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
                Container(
                  margin: const EdgeInsets.symmetric(horizontal: 20),
                  width: MediaQuery.of(context).size.width,
                  height: 50,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(10),
                    gradient: ColorTheme().linearColor,
                  ),
                  child: ElevatedButton.icon(
                    onPressed: () async {
                      bool? shouldAdd = await _showAddPenugasanDialog(context);
                      if (shouldAdd == true) {
                        final data = {
                          'user_id': penyuluh!.id,
                          'desa_id': selectedDesaValue!.id,
                          'desa_name': selectedDesaValue!.name,
                        };
                        await adminC.addPenugasan(data);
                        setState(() {});
                      }
                    },
                    icon: Icon(Icons.add_box, color: ColorTheme().whiteColor),
                    label: Text(
                      'Tambah Penugasan',
                      style: TextStyle(
                        fontWeight: FontWeight.bold,
                        fontSize: 14,
                        color: ColorTheme().whiteColor,
                      ),
                    ),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.transparent,
                      side: BorderSide(
                          color: ColorTheme().primaryColor, width: 2),
                      shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(10),
                      ),
                    ),
                  ),
                ),
                Expanded(
                  child: penyuluh.penugasan.isNotEmpty
                      ? ListView.builder(
                          padding: const EdgeInsets.only(top: 10),
                          itemCount: penyuluh.penugasan.length,
                          itemBuilder: (context, index) {
                            Penugasan penugasan = penyuluh!.penugasan[index];
                            return Card(
                              elevation: 3,
                              margin: const EdgeInsets.symmetric(
                                  horizontal: 20, vertical: 5),
                              surfaceTintColor: ColorTheme().whiteColor,
                              child: ListTile(
                                leading: Icon(
                                  Icons.villa,
                                  color: ColorTheme().primaryColor,
                                  size: 30,
                                ),
                                title: Text(penugasan.desaName,
                                    style: StyleTheme().stylePrimary.copyWith(
                                        fontSize: 16,
                                        fontWeight: FontWeight.bold)),
                                subtitle: Text(
                                  penugasan.desaId,
                                  style: StyleTheme().styleBlack,
                                ),
                                trailing: IconButton(
                                    onPressed: () async {
                                      bool? shouldDelete =
                                          await _showDeleteConfirmationDialog(
                                              context);
                                      if (shouldDelete == true) {
                                        await adminC
                                            .deletePenugasan(penugasan.id);
                                        setState(() {});
                                      }
                                    },
                                    icon: const Icon(
                                      Icons.remove_circle,
                                      color: Colors.red,
                                    )),
                              ),
                            );
                          },
                        )
                      : Center(
                          child: Column(
                            mainAxisAlignment: MainAxisAlignment.center,
                            children: [
                              const Icon(
                                Icons.assignment,
                                color: Colors.grey,
                                size: 50,
                              ),
                              Text(
                                "Belum Ada Penugasan",
                                style: StyleTheme().styleBlack.copyWith(
                                    fontSize: 18,
                                    fontWeight: FontWeight.w500,
                                    color: Colors.grey),
                              ),
                            ],
                          ),
                        ),
                ),
              ],
            );
          }
        },
      ),
    );
  }

  Future<bool> _showAddPenugasanDialog(BuildContext context) async {
    return await showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text("Tambah Penugasan"),
          content: DropdownButtonComponent(
            icon: Icons.villa,
            label: 'Desa',
            selectedItem: selectedDesaValue,
            items: desaList.map((desa) {
              return DropdownMenuItem<DesaModel>(
                value: desa,
                child: Text(UserController().toCamelCase(desa.name)),
              );
            }).toList(),
            hint: 'Pilih Desa',
            validator: (value) =>
                value == null ? 'Pilih desa terlebih dahulu' : null,
            onChanged: (newValue) {
              setState(() {
                selectedDesaValue = newValue;
              });
            },
            onSaved: (newValue) {
              setState(() {
                selectedDesaValue = newValue!;
              });
            },
          ),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(false); // Kembali dengan nilai false
              },
              child: Text("Batal",
                  style: StyleTheme()
                      .stylePrimary
                      .copyWith(fontSize: 14, color: Colors.grey)),
            ),
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(true); // Kembali dengan nilai true
              },
              child: Text(
                "Tambah",
                style: StyleTheme()
                    .stylePrimary
                    .copyWith(fontSize: 14, color: ColorTheme().primaryColor),
              ),
            ),
          ],
        );
      },
    );
  }

  Future<bool> _showDeleteConfirmationDialog(BuildContext context) async {
    return await showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text("Konfirmasi Aksi"),
          content: const Text("Anda yakin ingin menghapus data ini?"),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(false); // Kembali dengan nilai false
              },
              child: Text("Cancel",
                  style: StyleTheme()
                      .stylePrimary
                      .copyWith(fontSize: 14, color: Colors.grey)),
            ),
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(true); // Kembali dengan nilai true
              },
              child: Text(
                "Delete",
                style: StyleTheme()
                    .stylePrimary
                    .copyWith(fontSize: 14, color: Colors.red),
              ),
            ),
          ],
        );
      },
    );
  }
}
