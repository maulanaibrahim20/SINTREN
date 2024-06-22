import 'dart:developer';
import 'package:sintren_mobile/helpers/database_helper.dart';
import 'package:sintren_mobile/models/detail_padi_model.dart';

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

  Future<List<DetailPadiModel>> getAllPenyuluhanPadi() async {
    try {
      final db = await DatabaseHelper().database;
      final List<Map<String, dynamic>> maps = await db.query(
        'detailPadi',
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

  getKesimpulanDataPengairan(String date, String desaId) {}

  getKesimpulanDataPadi(String date, String desaId) {}
}
