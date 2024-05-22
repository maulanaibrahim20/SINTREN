import 'package:flutter_easyloading/flutter_easyloading.dart';
import 'package:shared_preferences/shared_preferences.dart';

class UserLoginModel {
  static const String _isLoginKey = 'isLogin';
  static const String _userIdKey = 'userId';
  static const String _nameKey = 'name';
  static const String _emailKey = 'email';
  static const String _usernameKey = 'username';
  static const String _roleKey = 'role';
  static const String _kecamatanIdKey = 'kecamatanId';
  static const String _addressKey = 'address';
  static const String _phoneKey = 'phone';

  Future<void> setLogin(bool isLogin) async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setBool(_isLoginKey, isLogin);
  }

  Future<void> setUserId(String userId) async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString(_userIdKey, userId);
  }

  Future<void> setName(String name) async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString(_nameKey, name);
  }

  Future<void> setEmail(String email) async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString(_emailKey, email);
  }

  Future<void> setUsername(String username) async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString(_usernameKey, username);
  }

  Future<void> setRole(String role) async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString(_roleKey, role);
  }

  Future<void> setKecamatanId(String kecamatanId) async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString(_kecamatanIdKey, kecamatanId);
  }

  Future<void> setAddress(String address) async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString(_addressKey, address);
  }

  Future<void> setPhone(String phone) async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    prefs.setString(_phoneKey, phone);
  }

  Future<bool> getLogin() async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getBool(_isLoginKey) ?? false;
  }

  Future<String?> getUserId() async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getString(_userIdKey);
  }

  Future<String?> getName() async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getString(_nameKey);
  }

  Future<String?> getEmail() async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getString(_emailKey);
  }

  Future<String?> getUsername() async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getString(_usernameKey);
  }

  Future<String?> getRole() async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getString(_roleKey);
  }

  Future<String?> getKecamatanId() async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getString(_kecamatanIdKey);
  }

  Future<String?> getAddress() async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getString(_addressKey);
  }

  Future<String?> getPhone() async {
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    return prefs.getString(_phoneKey);
  }

  Future<void> clearPreferences() async {
    EasyLoading.show(status: "Loading...");
    final SharedPreferences prefs = await SharedPreferences.getInstance();
    await prefs.remove(_isLoginKey);
    await prefs.remove(_userIdKey);
    await prefs.remove(_nameKey);
    await prefs.remove(_emailKey);
    await prefs.remove(_usernameKey);
    await prefs.remove(_roleKey);
    await prefs.remove(_kecamatanIdKey);
    await prefs.remove(_addressKey);
    await prefs.remove(_phoneKey);
    EasyLoading.dismiss();
    EasyLoading.showToast("Berhasil Logout");
  }
}
