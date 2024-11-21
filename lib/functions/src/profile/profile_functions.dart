import '/services/services.dart';

class ProfileFunctions {
  static Future<Map<String, dynamic>> getProfile() async {
    try {
      Map<String, dynamic> result = {};
      var r = await ProfileService.getProfile();
      if (r.isNotEmpty) {
        result = r;
      }
      return result;
    } catch (e) {
      throw e.toString();
    }
  }

  static Future<Map<String, dynamic>> updateProfile(
      {required Map<String, dynamic> query}) async {
    try {
      return await ProfileService.updateProfile(query: query);
    } catch (e) {
      throw e.toString();
    }
  }
}
