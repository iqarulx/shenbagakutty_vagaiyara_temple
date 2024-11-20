import 'dart:convert';
import 'dart:developer';
import '/constants/constants.dart';
import '/services/services.dart';
import 'package:http/http.dart' as http;

class ProfileService {
  static final String _apiUrl = Config.apiUrl;
  static const String _route = "profile.php";
  static const String _routeUpdate = "member.php";

  static Future<Map<String, dynamic>> getProfile() async {
    try {
      var userId = await Db.getData(type: UserData.memberId);
      final queryParameters = {"login_member_id": userId};
      final uri = Uri.parse("$_apiUrl/$_route");
      final response = await http.post(uri, body: json.encode(queryParameters));
      if (response.statusCode == 200) {
        if (response.body.isNotEmpty) {
          var d = response.body; // Data
          var r = jsonDecode(d); // Result
          if (r["head"]["code"] == 200) {
            return r;
          } else {
            throw r["head"]["msg"];
          }
        } else {
          throw apiErrorText;
        }
      } else {
        throw apiErrorText;
      }
    } catch (error) {
      throw error.toString();
    }
  }

  static Future updateProfile({required Map<String, dynamic> query}) async {
    try {
      final queryParameters = query;
      final uri = Uri.parse("$_apiUrl/$_routeUpdate");

      final response = await http.post(uri, body: json.encode(queryParameters));
      log(uri.toString());
      log(json.encode(queryParameters));

      if (response.statusCode == 200) {
        if (response.body.isNotEmpty) {
          var d = response.body; // Data
          var r = jsonDecode(d); // Result
          if (r["head"]["code"] == 200) {
            return r;
          } else {
            throw r["head"]["msg"];
          }
        } else {
          throw apiErrorText;
        }
      } else {
        throw apiErrorText;
      }
    } catch (error) {
      throw error.toString();
    }
  }
}
