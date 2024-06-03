import 'package:path/path.dart';
import 'package:sqflite/sqflite.dart';

class DatabaseHelper {
  static final DatabaseHelper _instance = DatabaseHelper._internal();
  static Database? _database;

  factory DatabaseHelper() {
    return _instance;
  }

  DatabaseHelper._internal();

  Future<Database> get database async {
    if (_database != null) return _database!;
    _database = await _initDatabase();
    return _database!;
  }

  Future<Database> _initDatabase() async {
    String path = join(await getDatabasesPath(), 'sintren.db');
    return await openDatabase(
      path,
      version: 1,
      onCreate: _onCreate,
    );
  }

  Future<void> _onCreate(Database db, int version) async {
    await db.execute('''
      CREATE TABLE desa (
        id TEXT PRIMARY KEY,
        name TEXT,
        kecamatan_id TEXT,
        lahan_sawah REAL,
        lahan_non_sawah REAL,
        total_luas_lahan REAL
      )
    ''');
    await db.execute('''
      CREATE TABLE pengairan (
        id INTEGER PRIMARY KEY,
        name TEXT
      )
    ''');
    await db.execute('''
      CREATE TABLE padi (
        id INTEGER PRIMARY KEY,
        name TEXT
      )
    ''');
    await db.execute('''
      CREATE TABLE palawija (
        id INTEGER PRIMARY KEY,
        name TEXT
      )
    ''');
    await db.execute('''
      CREATE TABLE detailPadi (
        id INTEGER PRIMARY KEY,
        user_id TEXT,
        desa_id TEXT,
        desa_name TEXT,
        kecamatan_id TEXT,
        jenis_lahan TEXT,
        id_jenis_padi INTEGER,
        padi_name TEXT,
        jenis_bantuan TEXT,
        id_jenis_pengairan INTEGER,
        pengairan_name TEXT,
        tipe_data TEXT,
        date TEXT,
        nilai REAL
      )
    ''');
    await db.execute('''
      CREATE TABLE detailPalawija (
        id INTEGER PRIMARY KEY,
        user_id TEXT,
        desa_id TEXT,
        desa_name TEXT,
        kecamatan_id TEXT,
        jenis_lahan TEXT,
        jenis_bantuan TEXT,
        id_jenis_palawija INTEGER,
        palawija_name TEXT,
        tipe_data TEXT,
        date TEXT,
        nilai REAL
      )
    ''');
    await db.execute('''
      CREATE TABLE verify (
        id INTEGER PRIMARY KEY,
        date TEXT,
        desa_id TEXT,
        kecamatan_id TEXT,
        user_id TEXT,
        isVerify TEXT
      )
    ''');
  }
}
