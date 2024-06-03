class LuasWilayahModel {
  final String id;
  final String name;
  final String kecamatanId;
  final double luasLahanSawah;
  final double luasLahanNonSawah;
  final double totalLuasLahan;

  LuasWilayahModel({
    required this.luasLahanSawah,
    required this.luasLahanNonSawah,
    required this.totalLuasLahan,
    required this.kecamatanId,
    required this.id,
    required this.name,
  });

  factory LuasWilayahModel.fromJson(Map<String, dynamic> json) {
    double parseDouble(dynamic value) {
      if (value is int) {
        return value.toDouble();
      } else if (value is double) {
        return value;
      } else if (value is String) {
        return double.tryParse(value) ?? 0.0;
      } else {
        throw ArgumentError('Cannot convert $value to double');
      }
    }

    double luasLahanSawah = parseDouble(json['lahan_sawah']);
    double luasLahanNonSawah = parseDouble(json['lahan_non_sawah']);

    return LuasWilayahModel(
      id: json['desa_id'],
      name: json['desa']['name'],
      kecamatanId: json['kecamatan_id'].toString(),
      luasLahanNonSawah: luasLahanNonSawah,
      luasLahanSawah: luasLahanSawah,
      totalLuasLahan: luasLahanSawah + luasLahanNonSawah,
    );
  }

  factory LuasWilayahModel.fromMap(Map<String, dynamic> map) {
    return LuasWilayahModel(
      id: map['id'],
      name: map['name'],
      kecamatanId: map['kecamatan_id'],
      luasLahanNonSawah: map['lahan_non_sawah'],
      luasLahanSawah: map['lahan_sawah'],
      totalLuasLahan: map['total_luas_lahan'],
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'name': name,
      'kecamatan_id': kecamatanId,
      'lahan_sawah': luasLahanSawah,
      'lahan_non_sawah': luasLahanNonSawah,
      'total_luas_lahan': totalLuasLahan,
    };
  }
}
