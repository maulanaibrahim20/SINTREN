class HistoriPenyuluhanModel {
  final String date;
  final String id;
  final String name;
  final double nilai;
  final int totalTunggu;

  HistoriPenyuluhanModel({
    required this.date,
    required this.id,
    required this.name,
    required this.nilai,
    required this.totalTunggu,
  });

  factory HistoriPenyuluhanModel.fromJson(Map<String, dynamic> json) {
    return HistoriPenyuluhanModel(
      date: json['month_year'] ?? '',
      id: json['id'] ?? '',
      name: json['name'] ?? '',
      nilai: (json['total_nilai'] as num?)?.toDouble() ?? 0.0,
      totalTunggu: json['total_tunggu'] ?? 0,
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'date': date,
      'id': id,
      'name': name,
      'nilai': nilai,
      "total_tunggu": totalTunggu,
    };
  }
}