class DetailPalawijaModel {
  final int id;
  final String userId;
  final String desaId;
  final String desaName;
  final String kecamatanId;
  final String jenisLahan;
  final String jenisBantuan;
  final String idJenisPalawija;
  final String palawijaName;
  final String date;
  final String tipeData;
  final double nilai;
  final String status;
  final String catatan;

  DetailPalawijaModel({
    required this.desaName,
    required this.palawijaName,
    required this.id,
    required this.userId,
    required this.desaId,
    required this.kecamatanId,
    required this.jenisLahan,
    required this.jenisBantuan,
    required this.idJenisPalawija,
    required this.tipeData,
    required this.nilai,
    required this.date,
    required this.status,
    required this.catatan,
  });

  factory DetailPalawijaModel.fromJson(Map<String, dynamic> json) {
    return DetailPalawijaModel(
      id: json['id'],
      userId: json['user_id'].toString(),
      desaId: json['desa_id'].toString(),
      kecamatanId: json['kecamatan_id'].toString(),
      jenisLahan: json['jenis_lahan'],
      jenisBantuan: json['jenis_bantuan'],
      idJenisPalawija: json['id_jenis_palawija'],
      tipeData: json['tipe_data'],
      nilai: double.parse(json['nilai'].toString()),
      // nilai: json['nilai'],
      desaName: json['desa']['name'],
      palawijaName: json['palawija'] == null ? '' : json['palawija']['name'],
      date: json['date'], status: json['verify']['status'],
      catatan: json['verify']['catatan'],
    );
  }

  factory DetailPalawijaModel.fromMap(Map<String, dynamic> json) {
    return DetailPalawijaModel(
      id: json['id'],
      userId: json['user_id'],
      desaId: json['desa_id'],
      kecamatanId: json['kecamatan_id'],
      jenisLahan: json['jenis_lahan'],
      jenisBantuan: json['jenis_bantuan'],
      idJenisPalawija: json['id_jenis_palawija'],
      tipeData: json['tipe_data'],
      nilai: json['nilai'],
      desaName: json['desa_name'],
      palawijaName: json['palawija_name'],
      date: json['date'],
      status: json['status'],
      catatan: json['catatan'],
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'user_id': userId,
      'desa_id': desaId,
      'desa_name': desaName,
      'kecamatan_id': kecamatanId,
      'jenis_lahan': jenisLahan,
      'jenis_bantuan': jenisBantuan,
      'id_jenis_palawija': idJenisPalawija,
      'palawija_name': palawijaName,
      'tipe_data': tipeData,
      'nilai': nilai,
      'date': date,
      'status': status,
      'catatan': catatan,
    };
  }
}
