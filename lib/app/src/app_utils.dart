import 'package:flutter/material.dart';
import '/services/db/db.dart';
import '/view/view.dart';

class AuthProvider with ChangeNotifier {
  bool _isLoggedIn = false;
  Widget? _homeWidget;

  bool get isLoggedIn => _isLoggedIn;
  Widget? get homeWidget => _homeWidget;

  Future<void> checkLoginStatus() async {
    var isLogin = await Db.checkLogin();
    // var vs = await Versions.checkVersion(); // Version Status

    // if (vs["status"]) {
    if (isLogin) {
      _homeWidget = const Home();
    } else {
      _homeWidget = const Login();
    }
    // } else {
    //   _homeWidget = Update(uD: vs["vd"]);
    // }

    _isLoggedIn = true;
    notifyListeners();
  }
}
