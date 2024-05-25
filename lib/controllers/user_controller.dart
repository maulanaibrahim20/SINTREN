import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:intl/intl.dart';
import 'package:sintren_mobile/helpers/penyuluh_dbhelper.dart';
import 'package:sintren_mobile/models/desa_model.dart';
import 'package:sintren_mobile/models/histori_penyuluhan_model.dart';
import 'package:sintren_mobile/models/luas_wilayah_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/services/padi_service.dart';
import 'package:sintren_mobile/services/palawija_service.dart';
import 'package:sintren_mobile/services/user_service.dart';

class UserController {
  String toCamelCase(String input) {
    if (input.isEmpty) {
      return input;
    }

    List<String> words = input.split(' ');
    List<String> capitalizedWords = words.map((word) {
      if (word.isEmpty) {
        return word;
      }
      return word[0].toUpperCase() + word.substring(1).toLowerCase();
    }).toList();

    return capitalizedWords.join(' ');
  }

  Future<String> login(
      {required String username, required String password}) async {
    EasyLoading.show(status: "Loading...");
    final result = await UserService().login(
      username: username,
      password: password,
    );
    EasyLoading.dismiss();
    if (!result) {
      EasyLoading.showToast("Login gagal");
    }

    await UserService().getDataPenyuluhanDesa();
    await PadiService().getPengairan();
    await PalawijaService().getPalawija();
    await PadiService().getDetailPadiByUser();
    await PalawijaService().getDetailPalawijaByUser();

    final role = await UserLoginModel().getRole();
    return role ?? "";
  }

  Future<void> updateProfil(Map<String, dynamic> newData) async {
    EasyLoading.show(status: "Loading...");
    final id = await UserLoginModel().getUserId();
    final data = {
      "name": newData['name'],
      "email": newData['email'],
      "username": newData['username'],
      "alamat": newData['address'],
      "no_telp": newData['phone']
    };

    final result =
        await UserService().updateProfile(id: id.toString(), data: data);

    if (!result) {
      EasyLoading.showToast("Update Gagal");
    } else {
      await UserService().getUser(id: id.toString());
      EasyLoading.dismiss();
      EasyLoading.showToast("Update Berhasil");
    }
  }

  Future<void> changePassword(
      {required String oldPass,
      required String newPass,
      required String confirmPass}) async {
    EasyLoading.show(status: "Loading...");
    final id = await UserLoginModel().getUserId();
    final data = {
      "current_password": oldPass,
      "new_password": newPass,
      "confirm_password": confirmPass,
    };

    final result =
        await UserService().changePassword(id: id.toString(), data: data);

    if (!result) {
      EasyLoading.showToast("Update Gagal");
    } else {
      EasyLoading.dismiss();
      EasyLoading.showToast("Update Berhasil");
    }
  }

  Future<Map<String, dynamic>> getUser() async {
    EasyLoading.show(status: "Loading");
    final nameT = await UserLoginModel().getName();
    final emailT = await UserLoginModel().getEmail();
    final usernameT = await UserLoginModel().getUsername();
    final addressT = await UserLoginModel().getAddress();
    final phoneT = await UserLoginModel().getPhone();
    EasyLoading.dismiss();
    return {
      'name': nameT,
      'email': emailT,
      'username': usernameT,
      'address': addressT,
      'phone': phoneT,
    };
  }

  Future<List<DesaModel>> getDesa() async {
    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query('desa');

    return List<DesaModel>.from(maps.map((map) => DesaModel.fromJson(map)));
  }

  Future<List<HistoriPenyuluhanModel>> getHistoriPenyuluhan() async {
    final db = await PenyuluhDatabaseHelper().database;
    const String query = '''
    SELECT
        strftime('%Y-%m', date) AS month_year,
        desa_id,
        desa_name,
        SUM(nilai) AS total_nilai
    FROM (
        SELECT date, desa_id, desa_name, nilai FROM detailPadi
        UNION ALL
        SELECT date, desa_id, desa_name, nilai FROM detailPalawija
    ) AS combined_data
    GROUP BY
        month_year,
        desa_id
    ORDER BY
        month_year, desa_id;
  ''';

    final List<Map<String, dynamic>> maps = await db.rawQuery(query);

    return List<HistoriPenyuluhanModel>.from(
        maps.map((map) => HistoriPenyuluhanModel.fromJson(map)));
  }

  Future<List<LuasWilayahModel>> getLuasLahanDesa() async {
    final db = await PenyuluhDatabaseHelper().database;
    final List<Map<String, dynamic>> maps = await db.query('desa');

    return List<LuasWilayahModel>.from(
        maps.map((map) => LuasWilayahModel.fromMap(map)));
  }

  String convertDate(String date) {
    DateTime parsedDate = DateTime.parse('$date-01');

    String formattedDate = DateFormat('MMMM yyyy', 'id_ID').format(parsedDate);

    return formattedDate;
  }

  Future<List<HistoriPenyuluhanModel>> getHistoriPenyuluhanBulanIni() async {
    final db = await PenyuluhDatabaseHelper().database;
    final DateTime now = DateTime.now();
    final String currentMonthYear =
        '${now.year}-${now.month.toString().padLeft(2, '0')}';
    final DateTime lastMonthDate = DateTime(now.year, now.month - 1, now.day);
    final String lastMonthYear =
        '${lastMonthDate.year}-${lastMonthDate.month.toString().padLeft(2, '0')}';

    const String queryCurrentMonth = '''
    SELECT
        strftime('%Y-%m', date) AS month_year,
        desa_id,
        desa_name,
        SUM(nilai) AS total_nilai
    FROM (
        SELECT date, desa_id, desa_name, nilai FROM detailPadi
        UNION ALL
        SELECT date, desa_id, desa_name, nilai FROM detailPalawija
    ) AS combined_data
    WHERE strftime('%Y-%m', date) = ?
    GROUP BY
        month_year,
        desa_id
    ORDER BY
        month_year, desa_id;
  ''';

    const String queryLastMonth = '''
    SELECT
        strftime('%Y-%m', date) AS month_year,
        desa_id,
        desa_name,
        SUM(nilai) AS total_nilai
    FROM (
        SELECT date, desa_id, desa_name, nilai FROM detailPadi
        UNION ALL
        SELECT date, desa_id, desa_name, nilai FROM detailPalawija
    ) AS combined_data
    WHERE strftime('%Y-%m', date) = ?
    GROUP BY
        month_year,
        desa_id
    ORDER BY
        month_year, desa_id;
  ''';

    final List<Map<String, dynamic>> mapsCurrentMonth =
        await db.rawQuery(queryCurrentMonth, [currentMonthYear]);

    // Mengidentifikasi desa yang sudah ada di bulan ini
    final Set desaIdsCurrentMonth =
        mapsCurrentMonth.map((map) => map['desa_id']).toSet();

    // Mengambil data bulan lalu
    final List<Map<String, dynamic>> mapsLastMonth =
        await db.rawQuery(queryLastMonth, [lastMonthYear]);

    // Filter data bulan lalu untuk desa yang tidak ada di bulan ini
    final List<Map<String, dynamic>> mapsFilteredLastMonth = mapsLastMonth
        .where((map) => !desaIdsCurrentMonth.contains(map['desa_id']))
        .toList();

    // Menggabungkan data bulan ini dengan data bulan lalu yang difilter
    final List<Map<String, dynamic>> combinedMaps = [
      ...mapsCurrentMonth,
      ...mapsFilteredLastMonth
    ];

    return List<HistoriPenyuluhanModel>.from(
        combinedMaps.map((map) => HistoriPenyuluhanModel.fromJson(map)));
  }

  Future<void> synchronizeData() async {
    await UserService().getDataPenyuluhanDesa();
    await PadiService().getPengairan();
    await PalawijaService().getPalawija();
    await PadiService().getDetailPadiByUser();
    await PalawijaService().getDetailPalawijaByUser();
  }
}
