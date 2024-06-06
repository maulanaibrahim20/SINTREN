class PrediksiModel {
  final String tipeData;
  final List<int> labels;
  final List<double> targets;
  final List<String> detailedPredictions;

  PrediksiModel({
    required this.tipeData,
    required this.labels,
    required this.targets,
    required this.detailedPredictions,
  });

  factory PrediksiModel.fromJson(Map<String, dynamic> json) {
    return PrediksiModel(
      tipeData: json['tipeData'],
      labels: List<int>.from(json['labels']),
      targets: List<double>.from(json['targets']),
      detailedPredictions: List<String>.from(json['detailedPredictions']),
    );
  }
}
