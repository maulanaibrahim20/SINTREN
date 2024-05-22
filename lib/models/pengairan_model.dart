class PengairanModel {
  final String id;
  final String name;

  PengairanModel({required this.id, required this.name});

  factory PengairanModel.fromJson(Map<String, dynamic> json) {
    return PengairanModel(
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
    final PengairanModel otherPengairan = other as PengairanModel;
    return id == otherPengairan.id && name == otherPengairan.name;
  }

  @override
  int get hashCode => id.hashCode ^ name.hashCode;
}
