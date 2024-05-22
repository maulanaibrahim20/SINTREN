class PalawijaModel {
  final String id;
  final String name;

  PalawijaModel({required this.id, required this.name});

  factory PalawijaModel.fromJson(Map<String, dynamic> json) {
    return PalawijaModel(
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
    final PalawijaModel otherPalawija = other as PalawijaModel;
    return id == otherPalawija.id && name == otherPalawija.name;
  }

  @override
  int get hashCode => id.hashCode ^ name.hashCode;
}
