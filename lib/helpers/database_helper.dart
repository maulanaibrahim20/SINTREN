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
        lahan_sawah INTEGER,
        lahan_non_sawah INTEGER,
        total_luas_lahan INTEGER
      )
    ''');
    await db.execute('''
      CREATE TABLE pengairan (
        id TEXT PRIMARY KEY,
        name TEXT
      )
    ''');
    await db.execute('''
      CREATE TABLE padi (
        id TEXT PRIMARY KEY,
        name TEXT
      )
    ''');
    await db.execute('''
      CREATE TABLE palawija (
        id TEXT PRIMARY KEY,
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
        id_jenis_padi TEXT,
        padi_name TEXT,
        jenis_bantuan TEXT,
        id_jenis_pengairan TEXT,
        pengairan_name TEXT,
        tipe_data TEXT,
        date TEXT,
        nilai INTEGER
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
        id_jenis_palawija TEXT,
        palawija_name TEXT,
        tipe_data TEXT,
        date TEXT,
        nilai INTEGER
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
