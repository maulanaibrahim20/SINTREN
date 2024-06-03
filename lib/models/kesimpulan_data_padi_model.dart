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

class JenisPadi {
  final Map<String, JenisLahan> jenisLahan;
  final double total;

  JenisPadi({required this.jenisLahan, required this.total});
}

class PengairanData {
  final Map<String, TipeData> tipeData;
  double total;

  PengairanData({required this.tipeData, required this.total});
}


class JenisPengairan {
  final Map<String, PengairanData> pengairanData;
  double total; 

  JenisPengairan({required this.pengairanData, this.total = 0}); 

}
