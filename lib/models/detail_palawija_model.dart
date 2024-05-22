class DetailPalawijaModel {
  final int id;
  final String userId;
  final String desaId;
  final String desaName;
  final String kecamatanId;
  final String jenisLahan;
  final String jenisPalawija;
  final String jenisBantuan;
  final String tipeData;
  final int nilai;

  DetailPalawijaModel({
    required this.desaName,
    required this.id,
    required this.userId,
    required this.desaId,
    required this.kecamatanId,
    required this.jenisLahan,
    required this.jenisPalawija,
    required this.jenisBantuan,
    required this.tipeData,
    required this.nilai,
  });

  factory DetailPalawijaModel.fromJson(Map<String, dynamic> json) {
    return DetailPalawijaModel(
      id: json['id'],
      userId: json['user_id'],
      desaId: json['desa_id'],
      kecamatanId: json['kecamatan_id'],
      jenisLahan: json['jenis_lahan'],
      jenisBantuan: json['jenis_bantuan'],
      tipeData: json['tipe_data'],
      nilai: json['nilai'],
      desaName: json['desa']['name'],
      jenisPalawija: json['jenis_padi'],
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
      tipeData: json['tipe_data'],
      nilai: json['nilai'],
      desaName: json['desa_name'],
      jenisPalawija: json['jenis_padi'],
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
      'jenis_padi': jenisPalawija,
      'jenis_bantuan': jenisBantuan,
      'tipe_data': tipeData,
      'nilai': nilai
    };
  }
}
