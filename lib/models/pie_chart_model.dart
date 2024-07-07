class PieChartModel {
  final double sumPanen;
  final double sumTanam;
  final double sumPusoRusak;

  PieChartModel({
    required this.sumPanen,
    required this.sumTanam,
    required this.sumPusoRusak,
  });

  factory PieChartModel.fromMap(Map<String, dynamic> json) {
    return PieChartModel(
      sumPanen: json['sum_panen'] ?? 0.0,
      sumTanam: json['sum_tanam'] ?? 0.0,
      sumPusoRusak: json['sum_puso_rusak'] ?? 0.0,
    );
  }
}
