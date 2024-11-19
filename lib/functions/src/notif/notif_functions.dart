import 'package:shenbagakutty_vagaiyara/services/services.dart';

class NotifFunctions {
  static Future<Map<String, dynamic>> getNotif() async {
    try {
      Map<String, dynamic> result = {};
      var r = await NotifService.getNotif();
      if (r.isNotEmpty) {
        result = r;
      }
      return result;
    } catch (e) {
      throw e.toString();
    }
  }
}
