import '/services/services.dart';
import '/model/model.dart';

class AuthFunctions {
  static Future<UserModel?> checkLogin(
      {required String memberLoginId, required String password}) async {
    try {
      var result = await AuthService.checkLogin(
          memberLoginId: memberLoginId, password: password);
      if (result.isNotEmpty) {
        var data = result["head"];

        return UserModel(
          memberId: data["member_id"].toString(),
          mobileNumber: data["mobile_number"].toString(),
          memberName: data["name"].toString(),
          profilePhoto: data["profile_photo"].toString(),
          receiptVolunteer: data["receipt_volunteer"],
        );
      }
      return null;
    } catch (e) {
      throw e.toString();
    }
  }
}
