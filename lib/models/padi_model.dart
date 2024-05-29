class PadiModel {
  final String id;
  final String name;

  PadiModel({required this.id, required this.name});

  factory PadiModel.fromJson(Map<String, dynamic> json) {
    return PadiModel(
      id: json['id'].toString(),
      name: json['name'],
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'name': name,
    };
  }

  @override
  bool operator ==(Object other) {
    if (identical(this, other)) return true;
    if (runtimeType != other.runtimeType) return false;
    final PadiModel otherPadi = other as PadiModel;
    return id == otherPadi.id && name == otherPadi.name;
  }

  @override
  int get hashCode => id.hashCode ^ name.hashCode;
}
