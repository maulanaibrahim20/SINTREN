class DesaModel {
  final String id;
  final String name;

  DesaModel({
    required this.id,
    required this.name,
  });

  factory DesaModel.fromJson(Map<String, dynamic> json) {
    return DesaModel(
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
    final DesaModel otherDesa = other as DesaModel;
    return id == otherDesa.id && name == otherDesa.name;
  }

  @override
  int get hashCode => id.hashCode ^ name.hashCode;
}
