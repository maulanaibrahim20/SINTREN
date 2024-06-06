class DetailPadiModel {
  final int id;
  final String userId;
  final String desaId;
  final String desaName;
  final String kecamatanId;
  final String jenisLahan;
  final String idJenisPadi;
  final String padiName;
  final String jenisBantuan;
  final String idJenisPengairan;
  final String pengairanName;
  final String date;
  final String tipeData;
  final double nilai;
  final String status;
  final String catatan;

  DetailPadiModel({
    required this.desaName,
    required this.pengairanName,
    required this.id,
    required this.userId,
    required this.desaId,
    required this.kecamatanId,
    required this.jenisLahan,
    required this.idJenisPadi,
    required this.padiName,
    required this.jenisBantuan,
    required this.idJenisPengairan,
    required this.tipeData,
    required this.nilai,
    required this.date,
    required this.status,
    required this.catatan,
  });

  factory DetailPadiModel.fromJson(Map<String, dynamic> json) {
    return DetailPadiModel(
      id: json['id'],
      userId: json['user_id'].toString(),
      desaId: json['desa_id'].toString(),
      kecamatanId: json['kecamatan_id'].toString(),
      jenisLahan: json['jenis_lahan'],
      jenisBantuan: json['jenis_bantuan'],
      tipeData: json['tipe_data'],
      nilai: double.parse(json['nilai'].toString()),
      desaName: json['desa']['name'],
      idJenisPengairan: json['id_jenis_pengairan']?.toString() ?? "",
      pengairanName: json['pengairan'] == null ? '' : json['pengairan']['name'],
      idJenisPadi: json['id_jenis_padi'].toString(),
      padiName: json['padi'] == null ? '' : json['padi']['name'],
      date: json['date'],
      status: json['verify']['status'],
      catatan: json['verify']['catatan'] ?? "",
    );
  }

  factory DetailPadiModel.fromMap(Map<String, dynamic> json) {
    return DetailPadiModel(
      id: json['id'],
      userId: json['user_id'],
      desaId: json['desa_id'],
      kecamatanId: json['kecamatan_id'],
      jenisLahan: json['jenis_lahan'],
      jenisBantuan: json['jenis_bantuan'],
      tipeData: json['tipe_data'],
      nilai: json['nilai'],
      desaName: json['desa_name'],
      idJenisPengairan: json['id_jenis_pengairan'],
      pengairanName: json['pengairan_name'],
      idJenisPadi: json['id_jenis_padi'],
      padiName: json['padi_name'],
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
      'id_jenis_padi': idJenisPadi,
      'padi_name': padiName,
      'jenis_bantuan': jenisBantuan,
      'id_jenis_pengairan': idJenisPengairan,
      'pengairan_name': pengairanName,
      'tipe_data': tipeData,
      'nilai': nilai,
      'date': date,
      'status': status,
      'catatan': catatan,
    };
  }
}
