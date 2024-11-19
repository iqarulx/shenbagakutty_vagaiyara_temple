// ignore_for_file: public_member_api_docs, sort_constructors_first
import 'dart:convert';

class ReceiptModel {
  String creatorName;
  String receiptId;
  String receiptDate;
  String receiptNumber;
  String colorCode;
  String statusBaseMemberId;
  String memberName;
  String yearAmount;
  String poojaiFromDate;
  String poojaiToDate;
  String poojaiAmount;
  String amount;
  String description;
  String functionDate;
  String countForMudikanikai;
  String countForKadhuKuthu;
  String funeralTo;
  String receiptTypeId;
  String receiptTypeName;
  String formName;
  String receiptPrintUrl;
  ReceiptModel({
    required this.creatorName,
    required this.receiptId,
    required this.receiptDate,
    required this.receiptNumber,
    required this.colorCode,
    required this.statusBaseMemberId,
    required this.memberName,
    required this.yearAmount,
    required this.poojaiFromDate,
    required this.poojaiToDate,
    required this.poojaiAmount,
    required this.amount,
    required this.description,
    required this.functionDate,
    required this.countForMudikanikai,
    required this.countForKadhuKuthu,
    required this.funeralTo,
    required this.receiptTypeId,
    required this.receiptTypeName,
    required this.formName,
    required this.receiptPrintUrl,
  });

  ReceiptModel copyWith({
    String? creatorName,
    String? receiptId,
    String? receiptDate,
    String? receiptNumber,
    String? colorCode,
    String? statusBaseMemberId,
    String? memberName,
    String? yearAmount,
    String? poojaiFromDate,
    String? poojaiToDate,
    String? poojaiAmount,
    String? amount,
    String? description,
    String? functionDate,
    String? countForMudikanikai,
    String? countForKadhuKuthu,
    String? funeralTo,
    String? receiptTypeId,
    String? receiptTypeName,
    String? formName,
    String? receiptPrintUrl,
  }) {
    return ReceiptModel(
      creatorName: creatorName ?? this.creatorName,
      receiptId: receiptId ?? this.receiptId,
      receiptDate: receiptDate ?? this.receiptDate,
      receiptNumber: receiptNumber ?? this.receiptNumber,
      colorCode: colorCode ?? this.colorCode,
      statusBaseMemberId: statusBaseMemberId ?? this.statusBaseMemberId,
      memberName: memberName ?? this.memberName,
      yearAmount: yearAmount ?? this.yearAmount,
      poojaiFromDate: poojaiFromDate ?? this.poojaiFromDate,
      poojaiToDate: poojaiToDate ?? this.poojaiToDate,
      poojaiAmount: poojaiAmount ?? this.poojaiAmount,
      amount: amount ?? this.amount,
      description: description ?? this.description,
      functionDate: functionDate ?? this.functionDate,
      countForMudikanikai: countForMudikanikai ?? this.countForMudikanikai,
      countForKadhuKuthu: countForKadhuKuthu ?? this.countForKadhuKuthu,
      funeralTo: funeralTo ?? this.funeralTo,
      receiptTypeId: receiptTypeId ?? this.receiptTypeId,
      receiptTypeName: receiptTypeName ?? this.receiptTypeName,
      formName: formName ?? this.formName,
      receiptPrintUrl: receiptPrintUrl ?? this.receiptPrintUrl,
    );
  }

  Map<String, dynamic> toMap() {
    return <String, dynamic>{
      'creatorName': creatorName,
      'receiptId': receiptId,
      'receiptDate': receiptDate,
      'receiptNumber': receiptNumber,
      'colorCode': colorCode,
      'statusBaseMemberId': statusBaseMemberId,
      'memberName': memberName,
      'yearAmount': yearAmount,
      'poojaiFromDate': poojaiFromDate,
      'poojaiToDate': poojaiToDate,
      'poojaiAmount': poojaiAmount,
      'amount': amount,
      'description': description,
      'functionDate': functionDate,
      'countForMudikanikai': countForMudikanikai,
      'countForKadhuKuthu': countForKadhuKuthu,
      'funeralTo': funeralTo,
      'receiptTypeId': receiptTypeId,
      'receiptTypeName': receiptTypeName,
      'formName': formName,
      'receiptPrintUrl': receiptPrintUrl,
    };
  }

  factory ReceiptModel.fromMap(Map<String, dynamic> map) {
    return ReceiptModel(
      creatorName: map['creatorName'] as String,
      receiptId: map['receiptId'] as String,
      receiptDate: map['receiptDate'] as String,
      receiptNumber: map['receiptNumber'] as String,
      colorCode: map['colorCode'] as String,
      statusBaseMemberId: map['statusBaseMemberId'] as String,
      memberName: map['memberName'] as String,
      yearAmount: map['yearAmount'] as String,
      poojaiFromDate: map['poojaiFromDate'] as String,
      poojaiToDate: map['poojaiToDate'] as String,
      poojaiAmount: map['poojaiAmount'] as String,
      amount: map['amount'] as String,
      description: map['description'] as String,
      functionDate: map['functionDate'] as String,
      countForMudikanikai: map['countForMudikanikai'] as String,
      countForKadhuKuthu: map['countForKadhuKuthu'] as String,
      funeralTo: map['funeralTo'] as String,
      receiptTypeId: map['receiptTypeId'] as String,
      receiptTypeName: map['receiptTypeName'] as String,
      formName: map['formName'] as String,
      receiptPrintUrl: map['receiptPrintUrl'] as String,
    );
  }

  String toJson() => json.encode(toMap());

  factory ReceiptModel.fromJson(String source) =>
      ReceiptModel.fromMap(json.decode(source) as Map<String, dynamic>);

  @override
  String toString() {
    return 'ReceiptModel(creatorName: $creatorName, receiptId: $receiptId, receiptDate: $receiptDate, receiptNumber: $receiptNumber, colorCode: $colorCode, statusBaseMemberId: $statusBaseMemberId, memberName: $memberName, yearAmount: $yearAmount, poojaiFromDate: $poojaiFromDate, poojaiToDate: $poojaiToDate, poojaiAmount: $poojaiAmount, amount: $amount, description: $description, functionDate: $functionDate, countForMudikanikai: $countForMudikanikai, countForKadhuKuthu: $countForKadhuKuthu, funeralTo: $funeralTo, receiptTypeId: $receiptTypeId, receiptTypeName: $receiptTypeName, formName: $formName, receiptPrintUrl: $receiptPrintUrl)';
  }

  @override
  bool operator ==(covariant ReceiptModel other) {
    if (identical(this, other)) return true;

    return other.creatorName == creatorName &&
        other.receiptId == receiptId &&
        other.receiptDate == receiptDate &&
        other.receiptNumber == receiptNumber &&
        other.colorCode == colorCode &&
        other.statusBaseMemberId == statusBaseMemberId &&
        other.memberName == memberName &&
        other.yearAmount == yearAmount &&
        other.poojaiFromDate == poojaiFromDate &&
        other.poojaiToDate == poojaiToDate &&
        other.poojaiAmount == poojaiAmount &&
        other.amount == amount &&
        other.description == description &&
        other.functionDate == functionDate &&
        other.countForMudikanikai == countForMudikanikai &&
        other.countForKadhuKuthu == countForKadhuKuthu &&
        other.funeralTo == funeralTo &&
        other.receiptTypeId == receiptTypeId &&
        other.receiptTypeName == receiptTypeName &&
        other.formName == formName &&
        other.receiptPrintUrl == receiptPrintUrl;
  }

  @override
  int get hashCode {
    return creatorName.hashCode ^
        receiptId.hashCode ^
        receiptDate.hashCode ^
        receiptNumber.hashCode ^
        colorCode.hashCode ^
        statusBaseMemberId.hashCode ^
        memberName.hashCode ^
        yearAmount.hashCode ^
        poojaiFromDate.hashCode ^
        poojaiToDate.hashCode ^
        poojaiAmount.hashCode ^
        amount.hashCode ^
        description.hashCode ^
        functionDate.hashCode ^
        countForMudikanikai.hashCode ^
        countForKadhuKuthu.hashCode ^
        funeralTo.hashCode ^
        receiptTypeId.hashCode ^
        receiptTypeName.hashCode ^
        formName.hashCode ^
        receiptPrintUrl.hashCode;
  }
}
