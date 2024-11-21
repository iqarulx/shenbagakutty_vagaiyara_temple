import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;
import '/services/services.dart';
import '/constants/constants.dart';

class AuthService {
  static final String _apiUrl = Config.apiUrl;
  static const String _route = "auth.php";

  static Future<Map<String, dynamic>> checkLogin(
      {required String memberLoginId,
      required String password,
      String? mobileNumber,
      String? memberId}) async {
    try {
      final queryParameters = {
        'member_login_id': memberLoginId,
        'password': password,
        'verify_otp_number': null,
        'mobile_number': mobileNumber,
        'member_id': memberId,
        'fcm_id': Platform.isAndroid
            ? await NotificationService.getFCM()
            : await NotificationService.getAPNSToken(),
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
}
