import 'dart:developer';

import 'package:collection/collection.dart';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';
import 'package:sintren_mobile/models/grouped_data_padi_model.dart';

class AdminPadiController {
  Future<List<DetailPadiModel>> getDetailPadiByDesa(
      String date, String desaId) async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query(
        'detailPadi',
        where: 'date LIKE ? AND desa_id = ?',
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

  Future<List<GroupedDataPadiModel>> getAllPenyuluhanPadi() async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query(
        'detailPadi',
        orderBy: '''
          COALESCE(updated_at, created_at) DESC
        ''',
      );

      var data = List<DetailPadiModel>.from(
          maps.map((map) => DetailPadiModel.fromMap(map)));

      var groupedData = groupBy(data, (DetailPadiModel padi) {
        log(padi.date);
        DateTime parsedDate = DateTime.parse(padi.date);
        return {
          'kecamatanId': padi.kecamatanId,
          'yearMonth':
              '${parsedDate.year}-${parsedDate.month.toString().padLeft(2, '0')}',
        };
      });

      List<GroupedDataPadiModel> result = [];

      groupedData.forEach((key, values) {
        double sumTanam = values
            .where((padi) => padi.tipeData == 'tanam')
            .fold(0, (sum, padi) => sum + padi.nilai);

        double sumPanen = values
            .where((padi) => padi.tipeData == 'panen')
            .fold(0, (sum, padi) => sum + padi.nilai);

        double sumPuso = values
            .where((padi) => padi.tipeData == 'puso/rusak')
            .fold(0, (sum, padi) => sum + padi.nilai);

        double totalNilai = sumPanen + sumTanam - sumPuso;

        result.add(GroupedDataPadiModel(
          kecamatanId: key['kecamatanId'].toString(),
          yearMonth: key['yearMonth'].toString(),
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

  getKesimpulanDataPengairan(String date, String desaId) {}

  getKesimpulanDataPadi(String date, String desaId) {}
}
