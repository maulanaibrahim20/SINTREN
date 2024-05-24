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

class JenisPadi {
  final Map<String, JenisLahan> jenisLahan;
  final int total;

  JenisPadi({required this.jenisLahan, required this.total});
}

class PengairanData {
  final Map<String, TipeData> tipeData;
  int total;

  PengairanData({required this.tipeData, required this.total});
}


class JenisPengairan {
  final Map<String, PengairanData> pengairanData;
  int total; 

  JenisPengairan({required this.pengairanData, this.total = 0}); 

}
