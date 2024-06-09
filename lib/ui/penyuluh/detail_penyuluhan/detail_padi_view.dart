import 'package:flutter/material.dart';
import 'package:sintren_mobile/controllers/penyuluh/padi_controller.dart';
import 'package:sintren_mobile/controllers/user_controller.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/ui/components/color_theme.dart';
import 'package:sintren_mobile/ui/components/style_theme.dart';
import 'package:sintren_mobile/ui/penyuluh/components/dropdown_button_component.dart';
import 'package:sintren_mobile/ui/penyuluh/detail_penyuluhan/components/ringkasan_padi_widget.dart';
import 'package:sintren_mobile/ui/penyuluh/form/form_padi_view.dart';

class DetailPadiView extends StatefulWidget {
  const DetailPadiView({
    super.key,
    required this.date,
    required this.desaId,
    this.desaName = "",
  });
  final String date;
  final String desaId;
  final String desaName;

  @override
  State<DetailPadiView> createState() => _DetailPadiViewState();
}

class _DetailPadiViewState extends State<DetailPadiView> {
  final padiC = PadiController();
  bool isOpen = false;
  late List<String> status = ['terima', 'tolak', 'tunggu'];
  late String selectedStatus;

  @override
  void initState() {
    selectedStatus = "";
    setState(() {});
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: ColorTheme().bgColor,
      floatingActionButton: FloatingActionButton(
        shape: const CircleBorder(),
        onPressed: () async {
          _filter(context);
        },
        backgroundColor: ColorTheme().primaryColor,
        foregroundColor: ColorTheme().whiteColor,
        child: const Icon(
          Icons.filter_list,
        ),
      ),
      body: ListView(
        children: [
          RingkasanPadiWidget(
            date: widget.date,
            desaId: widget.desaId,
            desaName: widget.desaName,
            isRincian: false,
          ),
          FutureBuilder<List<DetailPadiModel>>(
            future: padiC.getDetailPadiByUser(widget.date, widget.desaId),
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
                      Text(
                        snapshot.error.toString(),
                        style: const TextStyle(
                          fontSize: 14,
                          color: Colors.red,
                        ),
                        textAlign: TextAlign.center,
                      ),
                    ],
                  ),
                );
              } else {
                final itemList = snapshot.data ?? [];
                if (itemList.isEmpty) {
                  return const SizedBox.shrink();
                }
                return ListView.builder(
                  shrinkWrap: true,
                  physics: const NeverScrollableScrollPhysics(),
                  padding: EdgeInsets.zero,
                  itemCount: (selectedStatus.isEmpty)
                      ? itemList.length
                      : itemList
                          .where((data) => data.status == selectedStatus)
                          .length,
                  itemBuilder: (BuildContext context, int index) {
                    var displayList = (selectedStatus.isEmpty)
                        ? itemList
                        : itemList
                            .where((data) => data.status == selectedStatus)
                            .toList();
                    DetailPadiModel data = displayList[index];
                    return Card(
                      surfaceTintColor: ColorTheme().whiteColor,
                      margin: const EdgeInsets.only(
                          right: 10, left: 10, bottom: 15),
                      elevation: 3,
                      child: ConstrainedBox(
                        constraints: const BoxConstraints(),
                        child: Padding(
                          padding: const EdgeInsets.symmetric(
                              horizontal: 20, vertical: 10),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    data.padiName,
                                    style: StyleTheme().styleBlack.copyWith(
                                        fontWeight: FontWeight.w500,
                                        fontSize: 16),
                                  ),
                                  Container(
                                    margin:
                                        const EdgeInsets.symmetric(vertical: 3),
                                    padding: const EdgeInsets.symmetric(
                                        horizontal: 8, vertical: 3),
                                    decoration: BoxDecoration(
                                      color: data.status == "terima"
                                          ? Colors.green
                                          : data.status == "tolak"
                                              ? Colors.red
                                              : Colors.grey,
                                      borderRadius: BorderRadius.circular(5),
                                    ),
                                    child: Text(
                                      data.status == "terima"
                                          ? "Terverifikasi"
                                          : data.status == "tolak"
                                              ? "Data Ditolak"
                                              : "Menunggu Verifikasi",
                                      style: StyleTheme().styleWhite.copyWith(
                                            fontWeight: FontWeight.w500,
                                          ),
                                    ),
                                  )
                                ],
                              ),
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    UserController()
                                        .toCamelCase(data.jenisBantuan),
                                    style: StyleTheme().styleBlack,
                                  ),
                                  Text(
                                    data.date,
                                    style: StyleTheme().styleBlack,
                                  ),
                                ],
                              ),
                              Row(
                                mainAxisAlignment:
                                    MainAxisAlignment.spaceBetween,
                                children: [
                                  Text(
                                    'Lahan ${UserController().toCamelCase(data.jenisLahan)}',
                                    style: StyleTheme().styleBlack,
                                  ),
                                  Text(
                                    UserController()
                                        .toCamelCase(data.pengairanName),
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
                                    UserController().toCamelCase(data.tipeData),
                                    style: StyleTheme().styleBlack.copyWith(
                                        fontSize: 14,
                                        fontWeight: FontWeight.w500),
                                  ),
                                  Text(
                                    data.nilai.toString(),
                                    style: StyleTheme().styleBlack.copyWith(
                                          fontSize: 14,
                                          fontWeight: FontWeight.bold,
                                        ),
                                  ),
                                ],
                              ),
                              const Divider(),
                              if (data.status == "tolak") ...[
                                ElevatedButton.icon(
                                  onPressed: () async {
                                    String? shouldUlasan =
                                        await _showUlasanDialog(context, data);
                                    if (shouldUlasan == "edit") {
                                      await Navigator.push(
                                        // ignore: use_build_context_synchronously
                                        context,
                                        MaterialPageRoute(
                                          builder: (_) => FormPadiView(
                                            detail: data,
                                            onCreate: false,
                                          ),
                                        ),
                                      ).then((value) {
                                        setState(() {});
                                      });
                                    } else if (shouldUlasan == "delete") {
                                      bool? shouldDelete =
                                          await _showDeleteConfirmationDialog(
                                              // ignore: use_build_context_synchronously
                                              context);
                                      if (shouldDelete == true) {
                                        await padiC.deleteDetailById(data.id);
                                        setState(() {});
                                      }
                                    }
                                  },
                                  icon: Icon(Icons.remove_red_eye,
                                      color: ColorTheme().primaryColor),
                                  label: Text('Lihat Ulasan',
                                      style: StyleTheme()
                                          .stylePrimary
                                          .copyWith(fontSize: 14)),
                                  style: ElevatedButton.styleFrom(
                                    fixedSize: Size.fromWidth(
                                        MediaQuery.of(context).size.width),
                                    surfaceTintColor: ColorTheme().whiteColor,
                                    side: BorderSide(
                                        color: ColorTheme().primaryColor,
                                        width: 2),
                                    shape: RoundedRectangleBorder(
                                      borderRadius: BorderRadius.circular(30),
                                    ),
                                  ),
                                ),
                                const SizedBox(height: 10),
                              ],
                              data.status == "terima" || data.status == "tolak"
                                  ? const SizedBox.shrink()
                                  : Row(
                                      children: [
                                        Expanded(
                                          flex: 1,
                                          child: ElevatedButton.icon(
                                            onPressed: () async {
                                              await Navigator.push(
                                                context,
                                                MaterialPageRoute(
                                                  builder: (_) => FormPadiView(
                                                    detail: data,
                                                    onCreate: false,
                                                  ),
                                                ),
                                              ).then(
                                                  (value) => setState(() {}));
                                            },
                                            icon: Icon(Icons.edit,
                                                color:
                                                    ColorTheme().primaryColor),
                                            label: Text('Edit',
                                                style: StyleTheme()
                                                    .stylePrimary
                                                    .copyWith(fontSize: 14)),
                                            style: ElevatedButton.styleFrom(
                                              surfaceTintColor:
                                                  ColorTheme().whiteColor,
                                              side: BorderSide(
                                                  color:
                                                      ColorTheme().primaryColor,
                                                  width: 2),
                                              shape: RoundedRectangleBorder(
                                                borderRadius:
                                                    BorderRadius.circular(30),
                                              ),
                                            ),
                                          ),
                                        ),
                                        const SizedBox(width: 10),
                                        Expanded(
                                          flex: 1,
                                          child: ElevatedButton.icon(
                                            onPressed: () async {
                                              bool? shouldDelete =
                                                  await _showDeleteConfirmationDialog(
                                                      context);
                                              if (shouldDelete == true) {
                                                await padiC
                                                    .deleteDetailById(data.id);
                                                setState(() {});
                                              }
                                            },
                                            icon: const Icon(Icons.delete,
                                                color: Colors.red),
                                            label: Text('Hapus',
                                                style: StyleTheme()
                                                    .stylePrimary
                                                    .copyWith(
                                                        fontSize: 14,
                                                        color: Colors.red)),
                                            style: ElevatedButton.styleFrom(
                                              surfaceTintColor:
                                                  ColorTheme().whiteColor,
                                              side: const BorderSide(
                                                  color: Colors.red, width: 2),
                                              shape: RoundedRectangleBorder(
                                                borderRadius:
                                                    BorderRadius.circular(30),
                                              ),
                                            ),
                                          ),
                                        ),
                                      ],
                                    )
                            ],
                          ),
                        ),
                      ),
                    );
                  },
                );
              }
            },
          ),
        ],
      ),
    );
  }

  void _filter(BuildContext context) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          surfaceTintColor: ColorTheme().whiteColor,
          title: const Column(
            children: [
              Text('Filter Desa'),
              Divider(),
            ],
          ),
          content: DropdownButtonComponent(
            icon: Icons.dataset,
            label: 'Status',
            selectedItem: selectedStatus.isEmpty ? null : selectedStatus,
            items: status.map(
              (value) {
                return DropdownMenuItem<String>(
                  value: value,
                  child: Text(UserController().toCamelCase(value)),
                );
              },
            ).toList(),
            hint: 'Pilih Status',
            validator: (value) =>
                value == null ? 'Pilih status terlebih dahulu' : null,
            onChanged: (newValue) {
              setState(() {
                selectedStatus = newValue!;
              });
            },
            onSaved: (newValue) {
              setState(() {
                selectedStatus = newValue!;
              });
            },
          ),
          actions: <Widget>[
            TextButton(
              onPressed: () {
                Navigator.of(context).pop(false); // Kembali dengan nilai false
              },
              child: Text(
                "Tutup",
                style: StyleTheme()
                    .stylePrimary
                    .copyWith(color: Colors.red, fontSize: 16),
              ),
            ),
            TextButton(
              onPressed: () {
                setState(() {
                  selectedStatus = "";
                });
              },
              child: Text(
                "Reset",
                style: StyleTheme().stylePrimary.copyWith(fontSize: 16),
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

  Future<String> _showUlasanDialog(
      BuildContext context, DetailPadiModel data) async {
    return await showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Column(
            children: [
              Text('Ulasan'),
              Divider(),
            ],
          ),
          content: Text(
            data.catatan,
            style: StyleTheme().styleBlack.copyWith(fontSize: 14),
          ),
          actions: <Widget>[
            TextButton(
              onPressed: () async {
                Navigator.of(context).pop("tutup");
              },
              child: Text(
                "Tutup",
                style: StyleTheme()
                    .stylePrimary
                    .copyWith(fontSize: 14, color: Colors.grey),
              ),
            ),
            TextButton(
              onPressed: () {
                Navigator.of(context).pop("edit");
              },
              child: Text(
                "Edit",
                style: StyleTheme().stylePrimary.copyWith(fontSize: 14),
              ),
            ),
            TextButton(
              onPressed: () {
                Navigator.of(context).pop("delete");
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
