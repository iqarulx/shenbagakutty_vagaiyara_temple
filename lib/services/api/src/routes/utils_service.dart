import 'dart:convert';
import 'package:http/http.dart' as http;
import '/services/services.dart';
import '/constants/constants.dart';

class UtilsService {
  static final String _apiUrl = Config.apiUrl;
  static const String _route = "profile.php";
  static const String _routeCity = "country.php";

  static Future<Map<String, dynamic>> createProfession(
      {required String pName}) async {
    try {
      var memberId = await Db.getData(type: UserData.memberId);
      var memberName = await Db.getData(type: UserData.memberName);

      final queryParameters = {
        "profession_name": pName,
        "creator": memberId,
        "creator_name": memberName
      };
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

  static Future<Map<String, dynamic>> createCity(
      {required String cName}) async {
    try {
      var memberId = await Db.getData(type: UserData.memberId);
      var memberName = await Db.getData(type: UserData.memberName);

      final queryParameters = {
        "add_city_name": cName,
        "creator": memberId,
        "creator_name": memberName
      };
      final uri = Uri.parse("$_apiUrl/$_routeCity");
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
}
