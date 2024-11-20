import 'dart:convert';
import 'package:http/http.dart' as http;
import '/services/services.dart';
import '/constants/constants.dart';

class ReceiptService {
  static final String _apiUrl = Config.apiUrl;
  static const String _route = "receipt.php";
  // static const String _routeType = "receipt_type.php";
  static const String _generalDonation =
      "receipt_form/general_donation/save.php";
  static const String _goldSilverDollar =
      "receipt_form/gold_silver_dollar/save.php";
  static const String _mudiKaanikai = "receipt_form/mudi_kaanikai/save.php";
  static const String _nandavanam = "receipt_form/nandavanam/save.php";
  static const String _newMemberRegistration =
      "receipt_form/new_member_registration/save.php";
  static const String _oldThalakattu = "receipt_form/old_thalakattu/save.php";
  static const String _newThalakattu = "receipt_form/new_thalakattu/save.php";
  static const String _personalSavings =
      "receipt_form/personal_savings/save.php";
  static const String _poojaDonation = "receipt_form/pooja_donation/save.php";

  static Future<Map<String, dynamic>> saveReceipt(
      {required Map<String, dynamic> query, required ReceiptType type}) async {
    try {
      final queryParameters = query;
      final Uri? uri;
      if (type == ReceiptType.generalDonation) {
        uri = Uri.parse("$_apiUrl/$_generalDonation");
      } else if (type == ReceiptType.goldSilverDollar) {
        uri = Uri.parse("$_apiUrl/$_goldSilverDollar");
      } else if (type == ReceiptType.mudiKaanikai) {
        uri = Uri.parse("$_apiUrl/$_mudiKaanikai");
      } else if (type == ReceiptType.nandavanam) {
        uri = Uri.parse("$_apiUrl/$_nandavanam");
      } else if (type == ReceiptType.newMemberRegistration) {
        uri = Uri.parse("$_apiUrl/$_newMemberRegistration");
      } else if (type == ReceiptType.newThalakattu) {
        uri = Uri.parse("$_apiUrl/$_newThalakattu");
      } else if (type == ReceiptType.oldThalakattu) {
        uri = Uri.parse("$_apiUrl/$_oldThalakattu");
      } else if (type == ReceiptType.personalSavings) {
        uri = Uri.parse("$_apiUrl/$_personalSavings");
      } else if (type == ReceiptType.poojaDonation) {
        uri = Uri.parse("$_apiUrl/$_poojaDonation");
      } else {
        uri = null;
      }
      if (uri != null) {
        final response =
            await http.post(uri, body: json.encode(queryParameters));
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
      } else {
        throw apiErrorText;
      }
    } catch (error) {
      throw error.toString();
    }
  }

  static Future<Map<String, dynamic>> listing(
      {String? searchText,
      String? fromDate,
      String? toDate,
      String? rCId,
      String? rTId,
      String? mId,
      String? lMId,
      String? rC,
      String? nMName,
      required String pageNo,
      required String pageLimit}) async {
    try {
      final queryParameters = {
        "search_text": searchText ?? '',
        "from_date": fromDate ?? '',
        "to_date": toDate ?? '',
        "receipt_category_id": rCId ?? '',
        "receipt_type_id": rTId ?? '',
        "member_id": mId ?? '',
        "login_member_id": lMId ?? '',
        "receipt_creator": rC ?? '',
        "non_member_name": nMName ?? '',
        "page_number": pageNo,
        "page_limit": pageLimit
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
