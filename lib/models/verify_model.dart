class VerifyModel {
  final int id;
  final String date;
  final String desaId;
  final String kecamatanId;
  final String userId;
  final String isVerify;

  VerifyModel({
    required this.id,
    required this.date,
    required this.desaId,
    required this.kecamatanId,
    required this.userId,
    required this.isVerify,
  });

  factory VerifyModel.fromJson(Map<String, dynamic> json) {
    return VerifyModel(
      id: json['id'],
      date: json['date'],
      desaId: json['desa_id'],
      kecamatanId: json['kecamatan_id'].toString(),
      userId: json['user_id'],
      isVerify: json['isVerify'],
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'date': date,
      'desa_id': desaId,
      'kecamatan_id': kecamatanId,
      'user_id': userId,
      'isVerify': isVerify,
    };
  }
}
