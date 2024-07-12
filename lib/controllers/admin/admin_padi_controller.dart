import 'dart:developer';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/pie_chart_model.dart';
import 'package:sintren_mobile/models/trend_grafik_model.dart';
import 'package:sintren_mobile/models/user_login_model.dart';

class AdminPadiController {
  Future<List<DetailPadiModel>> getDetailPadiByDesa(
      String date, String desaId) async {
    try {
      final db = await DatabaseHelper().database;
      final role = await UserLoginModel().getRole();

      String statusFilter = role == 'PERTANIAN' ? "status = 'terima'" : "1=1";

      final List<Map<String, dynamic>> maps = await db.query(
        'detailPadi',
        where: 'date LIKE ? AND desa_id = ? AND $statusFilter',
        whereArgs: ['%$date%', desaId],
        orderBy: '''
        COALESCE(updated_at, created_at) DESC
      ''',
      );

      return List<DetailPadiModel>.from(
          maps.map((map) => DetailPadiModel.fromMap(map)));
    } catch (e) {
      log("Get detail padi by user error: $e");
      return [];
    }
  }

  Future<List<DetailPadiModel>> getAllPenyuluhanPadi() async {
    try {
      final db = await DatabaseHelper().database;
      final role = await UserLoginModel().getRole();
      String statusFilter = role == 'PERTANIAN' ? "status = 'terima'" : "1=1";

      final List<Map<String, dynamic>> maps = await db.query(
        'detailPadi',
        where: statusFilter,
        orderBy: '''
          date DESC
        ''',
      );

      return List<DetailPadiModel>.from(
          maps.map((map) => DetailPadiModel.fromMap(map)));
    } catch (e) {
      log("Get all padi error: $e");
      return [];
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
        FROM detailPadi
        WHERE ${conditions.join(' AND ')}
    ''', args);

      if (maps.isNotEmpty) {
        return PieChartModel.fromMap(maps.first);
      } else {
        return null;
      }
    } catch (e) {
      log("Get all padi error: $e");
      return null;
    }
  }

  Future<TrendGrafik> getDataGrafikPenyuluhanPadi(String year,
      {String? desaId, String? kecamatanId}) async {
    try {
      final db = await DatabaseHelper().database;

      // Membuat daftar kondisi dan argumen
      List<String> conditions = ['date LIKE ?', 'status = ?'];
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
        'detailPadi',
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
}
