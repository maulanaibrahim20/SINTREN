class Penyuluh {
  final String id;
  final String name;
  final String email;
  final String alamat;
  final String noTelp;
  final List<Penugasan> penugasan;

  Penyuluh({
    required this.id,
    required this.name,
    required this.email,
    required this.alamat,
    required this.noTelp,
    required this.penugasan,
  });

  factory Penyuluh.fromJson(Map<String, dynamic> json) {
    var penugasanList = json['penugasan'] as List;
    List<Penugasan> penugasan =
        penugasanList.map((i) => Penugasan.fromJson(i)).toList();

    return Penyuluh(
      id: json['id'],
      name: json['name'],
      email: json['email'],
      alamat: json['alamat'],
      noTelp: json['no_telp'],
      penugasan: penugasan,
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'name': name,
      'email': email,
      'alamat': alamat,
      'no_telp': noTelp,
    };
  }
}

class Penugasan {
  final int id;
  final String desaId;
  final String desaName;

  Penugasan({
    required this.id,
    required this.desaId,
    required this.desaName,
  });

  factory Penugasan.fromJson(Map<String, dynamic> json) {
    return Penugasan(
      id: json['id'],
      desaId: json['desa_id'],
      desaName: json['desa_name'],
    );
  }

  Map<String, dynamic> toMap() {
    return {
      'id': id,
      'desa_id': desaId,
      'desa_name': desaName,
    };
  }
}
