class DataItem {
  final int label;
  final int actualData;
  final int predictedData;

  DataItem({
    required this.label,
    required this.actualData,
    required this.predictedData,
  });

  factory DataItem.fromJson(Map<String, dynamic> json) {
    // Check and convert label to int if it's a double
    int label = json['label'] is double ? (json['label'] as double).toInt() : json['label'];
    // Check and convert actualData to int if it's a double
    int actualData = json['actualData'] is double ? (json['actualData'] as double).toInt() : json['actualData'];
    // Check and convert predictedData to int if it's a double
    int predictedData = json['predictedData'] is double ? (json['predictedData'] as double).toInt() : json['predictedData'];

    return DataItem(
      label: label,
      actualData: actualData,
      predictedData: predictedData,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'label': label,
      'actualData': actualData,
      'predictedData': predictedData,
    };
  }
}

class PrediksiModel {
  final List<DataItem> result;
  final double mape;

  PrediksiModel({
    required this.result,
    required this.mape,
  });

  factory PrediksiModel.fromJson(Map<String, dynamic> json) {
    var list = json['result'] as List;
    List<DataItem> resultList = list.map((i) => DataItem.fromJson(i)).toList();

    // Check and convert mape to double if it's an int
    double mape = json['mape'] is int ? (json['mape'] as int).toDouble() : json['mape'];

    return PrediksiModel(
      result: resultList,
      mape: mape,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'result': result.map((item) => item.toJson()).toList(),
      'mape': mape,
    };
  }
}
