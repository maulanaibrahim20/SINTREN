import 'dart:developer';

import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/models/kesimpulan_data_palawija_model.dart';
import 'package:sintren_mobile/models/palawija_model.dart';

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

      log(result.toString());

      return result;
    } catch (e) {
      log("Get all palawija error: $e");
      return [];
    }
  }

  Future<List<PalawijaModel>> getPalawija() async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query('palawija');
      return List<PalawijaModel>.from(
          maps.map((map) => PalawijaModel.fromJson(map)));
    } catch (e) {
      log("Get palawija error: $e");
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
}
