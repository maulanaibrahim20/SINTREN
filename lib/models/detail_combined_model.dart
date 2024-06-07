class DetailCombinedModel {
  final String date;
  final String type; // 'padi' or 'palawija'
  final dynamic data; // Instance of DetailPadiModel or DetailPalawijaModel

  DetailCombinedModel({required this.date, required this.type, required this.data});
}