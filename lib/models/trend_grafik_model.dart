class TrendGrafik {
  final Map<String, double> januari;
  final Map<String, double> februari;
  final Map<String, double> maret;
  final Map<String, double> april;
  final Map<String, double> mei;
  final Map<String, double> juni;
  final Map<String, double> juli;
  final Map<String, double> agustus;
  final Map<String, double> september;
  final Map<String, double> oktober;
  final Map<String, double> november;
  final Map<String, double> desember;

  TrendGrafik({
    required this.januari,
    required this.februari,
    required this.maret,
    required this.april,
    required this.mei,
    required this.juni,
    required this.juli,
    required this.agustus,
    required this.september,
    required this.oktober,
    required this.november,
    required this.desember,
  });

  factory TrendGrafik.fromMap(Map<String, Map<String, double>> map) {
    return TrendGrafik(
      januari: map['Jan'] ?? {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
      februari: map['Feb'] ?? {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
      maret: map['Mar'] ?? {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
      april: map['Apr'] ?? {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
      mei: map['Mei'] ?? {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
      juni: map['Jun'] ?? {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
      juli: map['Jul'] ?? {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
      agustus: map['Agt'] ?? {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
      september: map['Sep'] ?? {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
      oktober: map['Okt'] ?? {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
      november: map['Nov'] ?? {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
      desember: map['Des'] ?? {'tanam': 0, 'panen': 0, 'puso/rusak': 0},
    );
  }

  Map<String, Map<String, double>> toMap() {
    return {
      'Jan': januari,
      'Feb': februari,
      'Mar': maret,
      'Apr': april,
      'Mei': mei,
      'Jun': juni,
      'Jul': juli,
      'Agt': agustus,
      'Sep': september,
      'Okt': oktober,
      'Nov': november,
      'Des': desember,
    };
  }

  List<Map<String, double>> get allData => [
        januari,
        februari,
        maret,
        april,
        mei,
        juni,
        juli,
        agustus,
        september,
        oktober,
        november,
        desember,
      ];
}
