// ignore_for_file: public_member_api_docs, sort_constructors_first
import 'dart:convert';

class MemberModel {
  String name;
  String id;
  String mobileNumber;
  MemberModel({
    required this.name,
    required this.id,
    required this.mobileNumber,
  });

  MemberModel copyWith({
    String? name,
    String? id,
    String? mobileNumber,
  }) {
    return MemberModel(
      name: name ?? this.name,
      id: id ?? this.id,
      mobileNumber: mobileNumber ?? this.mobileNumber,
    );
  }

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'name': name,
      'id': id,
      'mobileNumber': mobileNumber,
    };
  }

  factory MemberModel.fromMap(Map<String, dynamic> map) {
    return MemberModel(
      name: map['name'] as String,
      id: map['id'] as String,
      mobileNumber: map['mobileNumber'] as String,
    );
  }

  String toJson() => json.encode(toMap());

  factory MemberModel.fromJson(String source) =>
      MemberModel.fromMap(json.decode(source) as Map<String, dynamic>);

  @override
  String toString() =>
      'MemberModel(name: $name, id: $id, mobileNumber: $mobileNumber)';

  @override
  bool operator ==(covariant MemberModel other) {
    if (identical(this, other)) return true;

    return other.name == name &&
        other.id == id &&
        other.mobileNumber == mobileNumber;
  }

  @override
  int get hashCode => name.hashCode ^ id.hashCode ^ mobileNumber.hashCode;
}
