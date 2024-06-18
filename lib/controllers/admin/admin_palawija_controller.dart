import 'dart:developer';

import 'package:collection/collection.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';
import 'package:sintren_mobile/models/grouped_data_palawija_model.dart';
import 'package:sintren_mobile/models/kesimpulan_data_palawija_model.dart';

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

  Future<List<GroupedDataPalawijaModel>> getAllPenyuluhanPalawija() async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query(
        'detailPalawija',
        orderBy: '''
          COALESCE(updated_at, created_at) DESC
        ''',
      );

      var data = List<DetailPalawijaModel>.from(
          maps.map((map) => DetailPalawijaModel.fromMap(map)));

      var groupedData = groupBy(data, (DetailPalawijaModel palawija) {
        DateTime parsedDate = DateTime.parse(palawija.date);
        return {
          'kecamatanId': palawija.kecamatanId,
          'yearMonth':
              '${parsedDate.year}-${parsedDate.month.toString().padLeft(2, '0')}',
        };
      });

      List<GroupedDataPalawijaModel> result = [];

      groupedData.forEach((key, values) {
        double sumTanam = values
            .where((palawija) => palawija.tipeData == 'tanam')
            .fold(0, (sum, palawija) => sum + palawija.nilai);

        double sumPanen = values
            .where((palawija) => palawija.tipeData == 'panen')
            .fold(0, (sum, palawija) => sum + palawija.nilai);

        double sumPuso = values
            .where((palawija) => palawija.tipeData == 'puso/rusak')
            .fold(0, (sum, palawija) => sum + palawija.nilai);

        double totalNilai = sumPanen + sumTanam - sumPuso;

        result.add(GroupedDataPalawijaModel(
          kecamatanId: key['kecamatanId'] as int,
          yearMonth: key['yearMonth'] as String,
          tanam: sumTanam,
          panen: sumPanen,
          pusoRusak: sumPuso,
          totalNilai: totalNilai,
        ));
      });

      return result;
    } catch (e) {
      log("Get all padi error: $e");
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
