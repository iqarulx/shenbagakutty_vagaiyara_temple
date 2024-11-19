import 'dart:convert';
import 'package:http/http.dart' as http;
import '/constants/constants.dart';
import '../config.dart';

class MandapamService {
  static final String _apiUrl = Config.apiUrl;
  static const String _route = "mandapam.php";
  static const String _routeBooking = "mandapam_booking.php";

  static Future<Map<String, dynamic>> checkMandapamAvailable(
      {required String month,
      required String year,
      required int lastDate,
      required String mandapamId}) async {
    try {
      final queryParameters = {
        "mandapam_booking_month": month,
        "mandapam_booking_year": year,
        "month_last_date": lastDate,
        "booking_mandapam_id": mandapamId
      };

      final uri = Uri.parse("$_apiUrl/$_routeBooking");
      print(json.encode(queryParameters));

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

  static Future<Map<String, dynamic>> getMandapams() async {
    try {
      final queryParameters = {"search_text": ""};
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
}
