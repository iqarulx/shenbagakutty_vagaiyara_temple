import 'package:shared_preferences/shared_preferences.dart';
import '/constants/constants.dart';
import '/model/model.dart';

class Db {
  Db._internal();

  static final Db _instance = Db._internal();

  factory Db() {
    return _instance;
  }

  static Future<SharedPreferences> connect() async {
    return await SharedPreferences.getInstance();
  }

  static Future<bool> checkLogin() async {
    var cn = await connect();
    bool? r = cn.getBool('login');
    return r ?? false;
  }

  static Future setLogin({required UserModel model}) async {
    var cn = await connect();
    cn.setString('member_id', model.memberId);
    cn.setString('member_name', model.memberName);
    cn.setString('mobile_number', model.mobileNumber);
    cn.setString('profile_photo', model.profilePhoto);
    cn.setBool('receipt_volunteer', model.receiptVolunteer);
    cn.setBool('login', true);
  }

  static Future<String?> getData({required UserData type}) async {
    var cn = await connect();
    if (type == UserData.memberId) {
      return cn.getString('member_id');
    } else if (type == UserData.memberName) {
      return cn.getString('member_name');
    } else if (type == UserData.mobileNumber) {
      return cn.getString('mobile_number');
    } else if (type == UserData.profilePhoto) {
      return "profile_photo";
    }
    return null;
  }

  static Future<bool?> getRV() async {
    var cn = await connect();
    return cn.getBool('receipt_volunteer');
  }

  static Future<bool> clearDb() async {
    var cn = await connect();
    return cn.clear();
  }
}
