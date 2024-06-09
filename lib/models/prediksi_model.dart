class DataItem {
  final int label;
  final int actualData;
  final double predictedData;

  DataItem({
    required this.label,
    required this.actualData,
    required this.predictedData,
  });

  factory DataItem.fromJson(Map<String, dynamic> json) {
    return DataItem(
      label: json['label'],
      actualData: json['actualData'],
      predictedData: json['predictedData'],
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

    return PrediksiModel(
      result: resultList,
      mape: json['mape'],
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'result': result.map((item) => item.toJson()).toList(),
      'mape': mape,
    };
  }
}
