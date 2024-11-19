// ignore_for_file: public_member_api_docs, sort_constructors_first
import 'package:flutter/foundation.dart';

class RTypeModel {
  String aId;
  String id;
  String name;
  List<String> inputs;
  RTypeModel({
    required this.aId,
    required this.id,
    required this.name,
    required this.inputs,
  });

  RTypeModel copyWith({
    String? aId,
    String? id,
    String? name,
    List<String>? inputs,
  }) {
    return RTypeModel(
      aId: aId ?? this.aId,
      id: id ?? this.id,
      name: name ?? this.name,
      inputs: inputs ?? this.inputs,
    );
  }

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'aId': aId,
      'id': id,
      'name': name,
      'inputs': inputs,
    };
  }

  @override
  String toString() {
    return 'RTypeModel(aId: $aId, id: $id, name: $name, inputs: $inputs)';
  }

  @override
  bool operator ==(covariant RTypeModel other) {
    if (identical(this, other)) return true;

    return other.aId == aId &&
        other.id == id &&
        other.name == name &&
        listEquals(other.inputs, inputs);
  }

  @override
  int get hashCode {
    return aId.hashCode ^ id.hashCode ^ name.hashCode ^ inputs.hashCode;
  }
}
