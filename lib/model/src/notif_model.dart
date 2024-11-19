// ignore_for_file: public_member_api_docs, sort_constructors_first
import 'dart:convert';

class NotifModel {
  String notificationId;
  String title;
  String notificationImage;
  String notificationAudio;
  String description;
  NotifModel({
    required this.notificationId,
    required this.title,
    required this.notificationImage,
    required this.notificationAudio,
    required this.description,
  });

  NotifModel copyWith({
    String? notificationId,
    String? title,
    String? notificationImage,
    String? notificationAudio,
    String? description,
  }) {
    return NotifModel(
      notificationId: notificationId ?? this.notificationId,
      title: title ?? this.title,
      notificationImage: notificationImage ?? this.notificationImage,
      notificationAudio: notificationAudio ?? this.notificationAudio,
      description: description ?? this.description,
    );
  }

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'notificationId': notificationId,
      'title': title,
      'notificationImage': notificationImage,
      'notificationAudio': notificationAudio,
      'description': description,
    };
  }

  factory NotifModel.fromMap(Map<String, dynamic> map) {
    return NotifModel(
      notificationId: map['notificationId'] as String,
      title: map['title'] as String,
      notificationImage: map['notificationImage'] as String,
      notificationAudio: map['notificationAudio'] as String,
      description: map['description'] as String,
    );
  }

  String toJson() => json.encode(toMap());

  factory NotifModel.fromJson(String source) =>
      NotifModel.fromMap(json.decode(source) as Map<String, dynamic>);

  @override
  String toString() {
    return 'NotifModel(notificationId: $notificationId, title: $title, notificationImage: $notificationImage, notificationAudio: $notificationAudio, description: $description)';
  }

  @override
  bool operator ==(covariant NotifModel other) {
    if (identical(this, other)) return true;

    return other.notificationId == notificationId &&
        other.title == title &&
        other.notificationImage == notificationImage &&
        other.notificationAudio == notificationAudio &&
        other.description == description;
  }

  @override
  int get hashCode {
    return notificationId.hashCode ^
        title.hashCode ^
        notificationImage.hashCode ^
        notificationAudio.hashCode ^
        description.hashCode;
  }
}
