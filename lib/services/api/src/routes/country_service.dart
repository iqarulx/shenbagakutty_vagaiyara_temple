import 'dart:convert';
import 'package:http/http.dart' as http;
import '/services/services.dart';
import '/constants/constants.dart';

class CountryService {
  static final String _apiUrl = Config.apiUrl;
  static const String _route = "country.php";

  static Future<Map<String, dynamic>> getCountry() async {
    try {
      final queryParameters = {"country_listing": ""};
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

  static Future<Map<String, dynamic>> getStates({required String index}) async {
    try {
      final queryParameters = {"filter_country_id": index};
      final uri = Uri.parse("$_apiUrl/$_route");
      final response = await http.post(uri, body: jsonEncode(queryParameters));
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

  static Future<Map<String, dynamic>> getCity() async {
    try {
      final queryParameters = {"city_listing": ""};
      final uri = Uri.parse("$_apiUrl/$_route");
      final response = await http.post(uri, body: jsonEncode(queryParameters));
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
