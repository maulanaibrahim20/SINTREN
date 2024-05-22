class DetailPadiModel {
  final int id;
  final String userId;
  final String desaId;
  final String desaName;
  final String kecamatanId;
  final String jenisLahan;
  final String jenisPadi;
  final String jenisBantuan;
  final String idJenisPengairan;
  final String pengairanName;
  final String tipeData;
  final int nilai;

  DetailPadiModel({
    required this.desaName,
    required this.pengairanName,
    required this.id,
    required this.userId,
    required this.desaId,
    required this.kecamatanId,
    required this.jenisLahan,
    required this.jenisPadi,
    required this.jenisBantuan,
    required this.idJenisPengairan,
    required this.tipeData,
    required this.nilai,
  });

  factory DetailPadiModel.fromJson(Map<String, dynamic> json) {
    return DetailPadiModel(
      id: json['id'],
      userId: json['user_id'],
      desaId: json['desa_id'],
      kecamatanId: json['kecamatan_id'],
      jenisLahan: json['jenis_lahan'],
      jenisBantuan: json['jenis_bantuan'],
      idJenisPengairan: json['id_jenis_pengairan'].toString(),
      tipeData: json['tipe_data'],
      nilai: json['nilai'],
      desaName: json['desa']['name'],
      pengairanName: json['pengairan'] == null ? '' : json['pengairan']['name'],
      jenisPadi: json['jenis_padi'],
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
      idJenisPengairan: json['id_jenis_pengairan'].toString(),
      tipeData: json['tipe_data'],
      nilai: json['nilai'],
      desaName: json['desa_name'],
      pengairanName: json['pengairan_name'],
      jenisPadi: json['jenis_padi'],
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
      'jenis_padi': jenisPadi,
      'jenis_bantuan': jenisBantuan,
      'id_jenis_pengairan': idJenisPengairan,
      'pengairan_name': pengairanName,
      'tipe_data': tipeData,
      'nilai': nilai
    };
  }
}
