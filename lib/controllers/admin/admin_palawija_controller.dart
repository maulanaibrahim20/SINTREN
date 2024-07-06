import 'dart:developer';

import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/models/kesimpulan_data_palawija_model.dart';
import 'package:sintren_mobile/models/trend_grafik_model.dart';

class AdminPalawijaController {
  Future<List<DetailPalawijaModel>> getDetailPalawijaByDesa(
      String date, String desaId) async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query(
        'detailPalawija',
        where: 'date LIKE ? AND desa_id = ?',
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
      final List<Map<String, dynamic>> maps = await db.query(
        'detailPalawija',
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

  Future<List<Map<String, dynamic>>> getDataPenyuluhanPalawijaTahunIni() async {
    try {
      final db = await DatabaseHelper().database;
      final DateTime now = DateTime.now();
      final int year = now.year;

      final List<Map<String, dynamic>> maps = await db.query(
        'detailPalawija',
        orderBy: 'date DESC',
      );

      // Filter data for current year and type 'panen'
      List<Map<String, dynamic>> filteredData = maps.where((map) {
        DateTime dataDate = DateTime.parse(map['date']);
        return dataDate.year == year && map['tipe_data'] == 'panen';
      }).toList();

      // Aggregate nilai for palawija with the same id/name
      Map<String, double> aggregatedValues = {};

      for (var map in filteredData) {
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
        var palawijaInfo = filteredData.firstWhere(
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

  Future<TrendGrafik> getDataGrafikPenyuluhanPalawija(String year) async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query(
        'detailPalawija',
        where: 'date LIKE ?',
        whereArgs: ['%$year%'],
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
