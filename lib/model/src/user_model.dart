// ignore_for_file: public_member_api_docs, sort_constructors_first
import 'dart:convert';

class UserModel {
  String mobileNumber;
  String memberId;
  String memberName;
  String profilePhoto;
  bool receiptVolunteer;
  UserModel({
    required this.mobileNumber,
    required this.memberId,
    required this.memberName,
    required this.profilePhoto,
    required this.receiptVolunteer,
  });

  UserModel copyWith({
    String? mobileNumber,
    String? memberId,
    String? memberName,
    String? profilePhoto,
    bool? receiptVolunteer,
  }) {
    return UserModel(
      mobileNumber: mobileNumber ?? this.mobileNumber,
      memberId: memberId ?? this.memberId,
      memberName: memberName ?? this.memberName,
      profilePhoto: profilePhoto ?? this.profilePhoto,
      receiptVolunteer: receiptVolunteer ?? this.receiptVolunteer,
    );
  }

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'mobileNumber': mobileNumber,
      'memberId': memberId,
      'memberName': memberName,
      'profilePhoto': profilePhoto,
      'receiptVolunteer': receiptVolunteer,
    };
  }

  factory UserModel.fromMap(Map<String, dynamic> map) {
    return UserModel(
      mobileNumber: map['mobileNumber'] as String,
      memberId: map['memberId'] as String,
      memberName: map['memberName'] as String,
      profilePhoto: map['profilePhoto'] as String,
      receiptVolunteer: map['receiptVolunteer'] as bool,
    );
  }

  String toJson() => json.encode(toMap());

  factory UserModel.fromJson(String source) =>
      UserModel.fromMap(json.decode(source) as Map<String, dynamic>);

  @override
  String toString() {
    return 'UserModel(mobileNumber: $mobileNumber, memberId: $memberId, memberName: $memberName, profilePhoto: $profilePhoto, receiptVolunteer: $receiptVolunteer)';
  }

  @override
  bool operator ==(covariant UserModel other) {
    if (identical(this, other)) return true;

    return other.mobileNumber == mobileNumber &&
        other.memberId == memberId &&
        other.memberName == memberName &&
        other.profilePhoto == profilePhoto &&
        other.receiptVolunteer == receiptVolunteer;
  }

  @override
  int get hashCode {
    return mobileNumber.hashCode ^
        memberId.hashCode ^
        memberName.hashCode ^
        profilePhoto.hashCode ^
        receiptVolunteer.hashCode;
  }
}
