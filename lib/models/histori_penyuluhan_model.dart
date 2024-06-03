class HistoriPenyuluhanModel {
  final String date;
  final String desaId;
  final String desaName;
  final double nilai;

  HistoriPenyuluhanModel({
    required this.date,
    required this.desaId,
    required this.desaName,
    required this.nilai,
  });

  factory HistoriPenyuluhanModel.fromJson(Map<String, dynamic> json) {
    return HistoriPenyuluhanModel(
      date: json['month_year'],
      desaId: json['desa_id'],
      desaName: json['desa_name'],
      nilai: (json['total_nilai'] as num).toDouble(),
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'date': date,
      'desa_id': desaId,
      'desa_name': desaName,
      'nilai': nilai,
    };
  }
}
