import 'dart:io';
import 'dart:convert';
import '/constants/constants.dart';
import '/services/services.dart';
import 'package:http/http.dart' as http;
import 'package:path/path.dart' as path;

class ImageService {
  static final String _apiUrl = Config.apiUrl;
  static const String _route = "image_api.php";

  static Future<Map<String, dynamic>> uploadImage(
      {required ImageType type, required File file, String? prefix}) async {
    try {
      var userId = await Db.getData(type: UserData.memberId);
      List<int> bytes = file.readAsBytesSync();
      var image = base64Encode(bytes);
      final queryParameters = {
        "update_member_id": userId,
        "image_type": path.extension(file.path).replaceAll('.', ''),
        "image_upload_type": type.name,
        "prefix": prefix,
        "image_url": image,
      };
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
