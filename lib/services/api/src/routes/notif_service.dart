import 'dart:convert';
import 'package:http/http.dart' as http;
import '/services/services.dart';
import '/constants/constants.dart';

class NotifService {
  static final String _apiUrl = Config.apiUrl;
  static const String _route = "notification.php";

  static Future<Map<String, dynamic>> getNotif() async {
    try {
      var memberId = await Db.getData(type: UserData.memberId);
      final queryParameters = {"notification_view_member_id": memberId};
      print(json.encode(queryParameters));
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
