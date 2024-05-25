class TipeData {
  final Map<String, int> data;

  TipeData({required this.data});
}

class JenisBantuan {
  final Map<String, TipeData> tipeData;
  final int total;

  JenisBantuan({required this.tipeData, required this.total});
}

class JenisLahan {
  final Map<String, JenisBantuan> jenisBantuan;
  final int total;

  JenisLahan({required this.jenisBantuan, required this.total});
}

class JenisPalawija {
  final Map<String, JenisLahan> jenisLahan;
  final int total;

  JenisPalawija({required this.jenisLahan, required this.total});
}
