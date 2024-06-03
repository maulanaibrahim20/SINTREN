class TipeData {
  final Map<String, double> data;

  TipeData({required this.data});
}

class JenisBantuan {
  final Map<String, TipeData> tipeData;
  final double total;

  JenisBantuan({required this.tipeData, required this.total});
}

class JenisLahan {
  final Map<String, JenisBantuan> jenisBantuan;
  final double total;

  JenisLahan({required this.jenisBantuan, required this.total});
}

class JenisPalawija {
  final Map<String, JenisLahan> jenisLahan;
  final double total;

  JenisPalawija({required this.jenisLahan, required this.total});
}
