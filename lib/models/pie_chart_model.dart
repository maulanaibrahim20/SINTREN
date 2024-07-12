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
    double parseToDouble(dynamic value) {
      if (value is int) {
        return value.toDouble();
      } else if (value is double) {
        return value;
      } else {
        return 0.0;
      }
    }

    return PieChartModel(
      sumPanen: parseToDouble(json['sum_panen']),
      sumTanam: parseToDouble(json['sum_tanam']),
      sumPusoRusak: parseToDouble(json['sum_puso_rusak']),
    );
  }
}
