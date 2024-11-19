import 'package:shenbagakutty_vagaiyara/services/services.dart';

class QrFunctions {
  static Future<Map<String, dynamic>> getQrDetails() async {
    try {
      Map<String, dynamic> result = {};
      var r = await QrService.getQrDetails();
      if (r.isNotEmpty) {
        result = r;
      }
      return result;
    } catch (e) {
      throw e.toString();
    }
  }
}
