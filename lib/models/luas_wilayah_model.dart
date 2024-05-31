class LuasWilayahModel {
  final String id;
  final String name;
  final int luasLahanSawah;
  final int luasLahanNonSawah;
  final int totalLuasLahan;

  LuasWilayahModel({
    required this.luasLahanSawah,
    required this.luasLahanNonSawah,
    required this.totalLuasLahan,
    required this.id,
    required this.name,
  });

  factory LuasWilayahModel.fromJson(Map<String, dynamic> json) {
    return LuasWilayahModel(
      id: json['id'],
      name: json['name'],
      luasLahanNonSawah:
          int.parse(json['luas_lahan_wilayah']['lahan_non_sawah']),
      luasLahanSawah: int.parse(json['luas_lahan_wilayah']['lahan_sawah']),
      totalLuasLahan: int.parse(json['luas_lahan_wilayah']['lahan_sawah']) +
          int.parse(json['luas_lahan_wilayah']['lahan_non_sawah']),
    );
  }

  factory LuasWilayahModel.fromMap(Map<String, dynamic> map) {
    return LuasWilayahModel(
      id: map['id'],
      name: map['name'],
      luasLahanNonSawah: map['lahan_non_sawah'],
      luasLahanSawah: map['lahan_sawah'],
      totalLuasLahan: map['total_luas_lahan'],
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'name': name,
      'lahan_sawah': luasLahanSawah,
      'lahan_non_sawah': luasLahanNonSawah,
      'total_luas_lahan': totalLuasLahan,
    };
  }
}
