import '/services/services.dart';

class MandapamFunctions {
  static Future<Map<String, dynamic>> getMandapams() async {
    try {
      Map<String, dynamic> result = {};
      var r = await MandapamService.getMandapams();
      if (r.isNotEmpty) {
        result = r;
      }
      return result;
    } catch (e) {
      throw e.toString();
    }
  }

  static Future<Map<String, dynamic>> checkAvailablity(
      {required String month,
      required String year,
      required int lastDate,
      required String mandapamId}) async {
    try {
      Map<String, dynamic> result = {};
      var r = await MandapamService.checkMandapamAvailable(
          mandapamId: mandapamId, month: month, year: year, lastDate: lastDate);
      if (r.isNotEmpty) {
        result = r;
      }
      return result;
    } catch (e) {
      throw e.toString();
    }
  }
}
