import '../../../../constants/constants.dart';
import '/services/services.dart';

class ReceiptFunctions {
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
      Map<String, dynamic> result = {};
      var r = await ReceiptService.listing(
          searchText: searchText,
          fromDate: fromDate,
          toDate: toDate,
          rCId: rCId,
          rTId: rTId,
          mId: mId,
          lMId: lMId,
          rC: rC,
          nMName: nMName,
          pageNo: pageNo,
          pageLimit: pageLimit);

      if (r.isNotEmpty) {
        result = r;
      }
      return result;
    } catch (e) {
      throw e.toString();
    }
  }

  static Future<Map<String, dynamic>> saveReceipt(
      {required Map<String, dynamic> query, required ReceiptType type}) async {
    try {
      Map<String, dynamic> result = {};
      var r = await ReceiptService.saveReceipt(query: query, type: type);

      if (r.isNotEmpty) {
        result = r;
      }
      return result;
    } catch (e) {
      throw e.toString();
    }
  }
}
