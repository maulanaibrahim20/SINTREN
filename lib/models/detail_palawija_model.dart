import 'dart:developer';

import 'package:intl/intl.dart';

class DetailPalawijaModel {
  final int id;
  final String userId;
  final String desaId;
  final String desaName;
  final String kecamatanId;
  final String kecamatanName;
  final String jenisLahan;
  final String idJenisPalawija;
  final String palawijaName;
  final String jenisBantuan;
  final String date;
  final String tipeData;
  final double nilai;
  final String status;
  final String catatan;
  final String createdAt;
  final String updatedAt;

  DetailPalawijaModel({
    required this.desaName,
    required this.id,
    required this.userId,
    required this.desaId,
    required this.kecamatanId,
    required this.kecamatanName,
    required this.jenisLahan,
    required this.idJenisPalawija,
    required this.palawijaName,
    required this.jenisBantuan,
    required this.tipeData,
    required this.nilai,
    required this.date,
    required this.status,
    required this.catatan,
    required this.createdAt,
    required this.updatedAt,
  });

  factory DetailPalawijaModel.fromJson(Map<String, dynamic> json) {
    String dateConvert(String date) {
      if (date.isEmpty) {
        return "";
      }
      DateTime rawDate = DateTime.parse(date);
      return DateFormat('yyyy-MM-dd HH:mm:ss').format(rawDate);
    }

    try {
      return DetailPalawijaModel(
        id: json['id'] ?? 0,
        userId: json['user_id']?.toString() ?? '',
        desaId: json['desa_id']?.toString() ?? '',
        kecamatanId: json['kecamatan_id']?.toString() ?? '',
        kecamatanName: json['kecamatan_name'] ?? '',
        jenisLahan: json['jenis_lahan'] ?? '',
        jenisBantuan: json['jenis_bantuan'] ?? '',
        tipeData: json['tipe_data'] ?? '',
        nilai: json['nilai'] != null
            ? double.parse(json['nilai'].toString())
            : 0.0,
        desaName: json['desa_name'] ?? '',
        idJenisPalawija: json['id_jenis_palawija']?.toString() ?? '',
        palawijaName: json['palawija_name'] ?? '',
        date: json['date'] ?? '',
        status: json['status'] ?? '',
        catatan: json['catatan'] ?? '',
        createdAt:
            json['created_at'] != null ? dateConvert(json['created_at']) : '',
        updatedAt:
            json['updated_at'] != null ? dateConvert(json['updated_at']) : '',
      );
    } catch (e) {
      log('Error parsing JSON to DetailPalawijaModel: $e');
      return DetailPalawijaModel(
        id: 0,
        userId: '',
        desaId: '',
        kecamatanId: '',
        kecamatanName: '',
        jenisLahan: '',
        jenisBantuan: '',
        tipeData: '',
        nilai: 0.0,
        desaName: '',
        idJenisPalawija: '',
        palawijaName: '',
        date: '',
        status: '',
        catatan: '',
        createdAt: '',
        updatedAt: '',
      );
    }
  }

  factory DetailPalawijaModel.fromMap(Map<String, dynamic> json) {
    return DetailPalawijaModel(
      id: json['id'],
      userId: json['user_id'],
      desaId: json['desa_id'],
      kecamatanId: json['kecamatan_id'],
      kecamatanName: json['kecamatan_name'],
      jenisLahan: json['jenis_lahan'],
      jenisBantuan: json['jenis_bantuan'],
      tipeData: json['tipe_data'],
      nilai: json['nilai'],
      desaName: json['desa_name'],
      idJenisPalawija: json['id_jenis_palawija'],
      palawijaName: json['palawija_name'],
      date: json['date'],
      status: json['status'],
      catatan: json['catatan'],
      createdAt: json['created_at'],
      updatedAt: json['updated_at'],
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'user_id': userId,
      'desa_id': desaId,
      'desa_name': desaName,
      'kecamatan_id': kecamatanId,
      'kecamatan_name': kecamatanName,
      'jenis_lahan': jenisLahan,
      'id_jenis_palawija': idJenisPalawija,
      'palawija_name': palawijaName,
      'jenis_bantuan': jenisBantuan,
      'tipe_data': tipeData,
      'nilai': nilai,
      'date': date,
      'status': status,
      'catatan': catatan,
      'created_at': createdAt,
      'updated_at': updatedAt,
    };
  }
}
