import 'dart:developer';

import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/models/kesimpulan_data_palawija_model.dart';
import 'package:sintren_mobile/models/pie_chart_model.dart';
import 'package:sintren_mobile/models/trend_grafik_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';

class AdminPalawijaController {
  Future<List<DetailPalawijaModel>> getDetailPalawijaByDesa(
      String date, String desaId) async {
    try {
      final db = await DatabaseHelper().database;
      final role = await UserLoginModel().getRole();
      String statusFilter = role == 'PERTANIAN' ? "status = 'terima'" : "1=1";

      final List<Map<String, dynamic>> maps = await db.query(
        'detailPalawija',
        where: 'date LIKE ? AND desa_id = ? AND $statusFilter',
        whereArgs: ['%$date%', desaId],
        orderBy: '''
        COALESCE(updated_at, created_at) DESC
      ''',
      );
      return List<DetailPalawijaModel>.from(
          maps.map((map) => DetailPalawijaModel.fromMap(map)));
    } catch (e) {
      log("Get detail palawija by user error: $e");
      return [];
    }
  }

  Future<List<DetailPalawijaModel>> getAllPenyuluhanPalawija() async {
    try {
      final db = await DatabaseHelper().database;
      final role = await UserLoginModel().getRole();
      String statusFilter = role == 'PERTANIAN' ? "status = 'terima'" : "1=1";

      final List<Map<String, dynamic>> maps = await db.query(
        'detailPalawija',
        where: statusFilter,
        orderBy: '''
          date DESC
        ''',
      );

      return List<DetailPalawijaModel>.from(
          maps.map((map) => DetailPalawijaModel.fromMap(map)));
    } catch (e) {
      log("Get all palawija error: $e");
      return [];
    }
  }

  Future<List<Map<String, dynamic>>> getDataPenyuluhanPalawijaTahunIni(
      {String? desaId, String? kecamatanId}) async {
    try {
      final db = await DatabaseHelper().database;
      final DateTime now = DateTime.now();
      final int year = now.year;

      // Membuat daftar kondisi dan argumen
      List<String> conditions = [
        "strftime('%Y', date) = ?",
        "tipe_data = ?",
        "status = ?"
      ];
      List<dynamic> args = [year.toString(), 'panen', 'terima'];

      // Menambahkan kondisi desaId jika tidak null
      if (desaId != null) {
        conditions.add('desa_id = ?');
        args.add(desaId);
      }

      // Menambahkan kondisi kecamatanId jika tidak null
      if (kecamatanId != null) {
        conditions.add('kecamatan_id = ?');
        args.add(kecamatanId);
      }

      // Query database dengan kondisi yang dinamis
      final List<Map<String, dynamic>> maps = await db.query(
        'detailPalawija',
        where: conditions.join(' AND '),
        whereArgs: args,
        orderBy: 'date DESC',
      );

      // Aggregate nilai for palawija with the same id/name
      Map<String, double> aggregatedValues = {};

      for (var map in maps) {
        String palawijaId = map['id_jenis_palawija'];
        double nilai = double.parse(map['nilai'].toString());

        if (aggregatedValues.containsKey(palawijaId)) {
          aggregatedValues[palawijaId] = aggregatedValues[palawijaId]! + nilai;
        } else {
          aggregatedValues[palawijaId] = nilai;
        }
      }

      // Prepare result list with aggregated values
      List<Map<String, dynamic>> result = [];

      aggregatedValues.forEach((palawijaId, nilai) {
        var palawijaInfo = maps.firstWhere(
          (map) => map['id_jenis_palawija'] == palawijaId,
          orElse: () =>
              <String, dynamic>{}, // Return an empty map instead of null
        );

        if (palawijaInfo.isNotEmpty) {
          result.add({
            'id_jenis_palawija': palawijaId,
            'palawija_name': palawijaInfo['palawija_name'],
            'nilai': nilai,
          });
        }
      });

      return result;
    } catch (e) {
      log("Get all palawija error: $e");
      return [];
    }
  }

  Future<Map<String, JenisPalawija>> getKesimpulanDataPalawija(
      String date, String desaId) async {
    try {
      List<DetailPalawijaModel> groupedData =
          await getDetailPalawijaByDesa(date, desaId);

      var groupedByAll =
          <String, Map<String, Map<String, Map<String, double>>>>{};

      for (var row in groupedData) {
        groupedByAll
            .putIfAbsent(row.palawijaName, () => {})
            .putIfAbsent(row.jenisLahan, () => {})
            .putIfAbsent(row.jenisBantuan, () => {})
            .update(row.tipeData, (value) => value + row.nilai,
                ifAbsent: () => row.nilai);
      }

      var result = <String, JenisPalawija>{};

      groupedByAll.forEach((jenisPalawija, lahanMap) {
        var lahanData = <String, JenisLahan>{};
        double totalJenisPalawija = 0;

        lahanMap.forEach((jenisLahan, bantuanMap) {
          var bantuanData = <String, JenisBantuan>{};
          double totalJenisLahan = 0;

          bantuanMap.forEach((jenisBantuan, tipeDataMap) {
            var tipeDataEntries = <String, TipeData>{};
            double totalJenisBantuan = 0;

            tipeDataMap.forEach((tipeData, nilai) {
              tipeDataEntries[tipeData] = TipeData(data: {tipeData: nilai});
              totalJenisBantuan += nilai;
            });

            bantuanData[jenisBantuan] = JenisBantuan(
                tipeData: tipeDataEntries, total: totalJenisBantuan);
            totalJenisLahan += totalJenisBantuan;
          });

          lahanData[jenisLahan] =
              JenisLahan(jenisBantuan: bantuanData, total: totalJenisLahan);
          totalJenisPalawija += totalJenisLahan;
        });

        result[jenisPalawija] =
            JenisPalawija(jenisLahan: lahanData, total: totalJenisPalawija);
      });

      return result;
    } catch (e) {
      log("Get kesimpulan data palawija error: $e");
      return {};
    }
  }

  Future<TrendGrafik> getDataGrafikPenyuluhanPalawija(String year,
      {String? desaId, String? kecamatanId}) async {
    try {
      final db = await DatabaseHelper().database;

      // Membuat daftar kondisi dan argumen
      List<String> conditions = ['date LIKE ?', "status = ?"];
      List<dynamic> args = ['%$year%', 'terima'];

      // Menambahkan kondisi desaId jika tidak null
      if (desaId != null) {
        conditions.add('desa_id = ?');
        args.add(desaId);
      }

      // Menambahkan kondisi kecamatanId jika tidak null
      if (kecamatanId != null) {
        conditions.add('kecamatan_id = ?');
        args.add(kecamatanId);
      }

      // Membuat query dengan kondisi yang dinamis
      final List<Map<String, dynamic>> maps = await db.query(
        'detailPalawija',
        where: conditions.join(' AND '),
        whereArgs: args,
        orderBy: 'date DESC',
      );

      Map<String, Map<String, double>> result = {
        'Jan': {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        'Feb': {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        'Mar': {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        'Apr': {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        'Mei': {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        'Jun': {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        'Jul': {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        'Agt': {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        'Sep': {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        'Okt': {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        'Nov': {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        'Des': {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
      };

      for (var map in maps) {
        // Mendapatkan bulan dari date
        DateTime date = DateTime.parse(map['date']);
        String month = getMonthString(date.month);

        // Mendapatkan tipe data dan nilai
        String tipeData = map['tipe_data'];
        double nilai = map['nilai'];

        // Pastikan map untuk bulan dan tipe data tidak null sebelum menambahkan nilai
        if (result[month] != null) {
          if (result[month]![tipeData] != null) {
            result[month]![tipeData] = result[month]![tipeData]! + nilai;
          } else {
            result[month]![tipeData] = nilai;
          }
        } else {
          result[month] = {tipeData: nilai};
        }
      }

      return TrendGrafik.fromMap(result);
    } catch (e) {
      log("Get all padi error: $e");
      return TrendGrafik(
        januari: {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        februari: {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        maret: {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        april: {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        mei: {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        juni: {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        juli: {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        agustus: {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        september: {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        oktober: {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        november: {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
        desember: {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
      );
    }
  }

  String getMonthString(int month) {
    switch (month) {
      case 1:
        return 'Jan';
      case 2:
        return 'Feb';
      case 3:
        return 'Mar';
      case 4:
        return 'Apr';
      case 5:
        return 'Mei';
      case 6:
        return 'Jun';
      case 7:
        return 'Jul';
      case 8:
        return 'Agt';
      case 9:
        return 'Sep';
      case 10:
        return 'Okt';
      case 11:
        return 'Nov';
      case 12:
        return 'Des';
      default:
        return '';
    }
  }

   Future<PieChartModel?> getDataProgressPieChart(
      {String? desaId, String? kecamatanId}) async {
    try {
      final db = await DatabaseHelper().database;
      final now = DateTime.now();
      final currentMonth = now.month;
      final currentYear = now.year;

      // Mengidentifikasi periode MT1 dan MT2
      List<String> mt1Months = ['10', '11', '12', '01', '02', '03'];
      List<String> mt2Months = ['04', '05', '06', '07', '08', '09'];

      List<String> selectedMonths = [];
      String selectedYearCondition = '';

      if (mt1Months.contains(currentMonth.toString().padLeft(2, '0'))) {
        selectedMonths = mt1Months;
        // Menentukan kondisi tahun untuk MT1
        selectedYearCondition = '''
      (strftime('%m', date) IN (?, ?, ?) AND strftime('%Y', date) = ?) OR
      (strftime('%m', date) IN (?, ?, ?) AND strftime('%Y', date) = ?)
      ''';
      } else if (mt2Months.contains(currentMonth.toString().padLeft(2, '0'))) {
        selectedMonths = mt2Months;
        // Menentukan kondisi tahun untuk MT2
        selectedYearCondition = '''
      strftime('%m', date) IN (${List.filled(mt2Months.length, '?').join(', ')}) AND strftime('%Y', date) = ?
      ''';
      }

      List<String> conditions = [selectedYearCondition, "status = ?"];

      List<dynamic> args = [];
      if (selectedMonths == mt1Months) {
        args.addAll([
          '10',
          '11',
          '12',
          (currentYear - 1).toString(),
          '01',
          '02',
          '03',
          currentYear.toString()
        ]);
      } else if (selectedMonths == mt2Months) {
        args.addAll(selectedMonths);
        args.add(currentYear.toString());
      }
      args.add("terima");

      if (desaId != null) {
        conditions.add('desa_id = ?');
        args.add(desaId);
      }

      if (kecamatanId != null) {
        conditions.add('kecamatan_id = ?');
        args.add(kecamatanId);
      }

      final List<Map<String, dynamic>> maps = await db.rawQuery('''
        SELECT 
          SUM(CASE WHEN tipe_data = 'panen' THEN nilai ELSE 0 END) as sum_panen,
          SUM(CASE WHEN tipe_data = 'tanam' THEN nilai ELSE 0 END) as sum_tanam,
          SUM(CASE WHEN tipe_data = 'puso/rusak' THEN nilai ELSE 0 END) as sum_puso_rusak
        FROM detailPalawija
        WHERE ${conditions.join(' AND ')}
    ''', args);

      if (maps.isNotEmpty) {
        return PieChartModel.fromMap(maps.first);
      } else {
        return null;
      }
    } catch (e) {
      log("Get all palawija error: $e");
      return null;
    }
  }

}
