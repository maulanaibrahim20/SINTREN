import 'dart:developer';

import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/detail_palawija_model.dart';

class AdminPalawijaController {
    Future<List<DetailPalawijaModel>> getDetailPalawijaByDesa(
      String date, String desaId) async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query(
        'detailPalawija',
        where: 'date LIKE ? AND desa_id = ?',
        whereArgs: ['%$date%', desaId],
        orderBy: 'date DESC',
      );
      return List<DetailPalawijaModel>.from(
          maps.map((map) => DetailPalawijaModel.fromMap(map)));
    } catch (e) {
      log("Get detail palawija by user error: $e");
      return [];
    }
  }

}