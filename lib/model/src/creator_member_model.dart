// ignore_for_file: public_member_api_docs, sort_constructors_first
import 'dart:convert';

class CreatorMemberModel {
  String name;
  String id;
  CreatorMemberModel({
    required this.name,
    required this.id,
  });

  CreatorMemberModel copyWith({
    String? name,
    String? id,
  }) {
    return CreatorMemberModel(
      name: name ?? this.name,
      id: id ?? this.id,
    );
  }

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'name': name,
      'id': id,
    };
  }

  factory CreatorMemberModel.fromMap(Map<String, dynamic> map) {
    return CreatorMemberModel(
      name: map['name'] as String,
      id: map['id'] as String,
    );
  }

  String toJson() => json.encode(toMap());

  factory CreatorMemberModel.fromJson(String source) =>
      CreatorMemberModel.fromMap(json.decode(source) as Map<String, dynamic>);

  @override
  String toString() => 'CreatorMemberModel(name: $name, id: $id)';

  @override
  bool operator ==(covariant CreatorMemberModel other) {
    if (identical(this, other)) return true;

    return other.name == name && other.id == id;
  }

  @override
  int get hashCode => name.hashCode ^ id.hashCode;
}
