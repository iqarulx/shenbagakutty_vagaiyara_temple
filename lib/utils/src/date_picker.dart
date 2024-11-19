import 'package:flutter/material.dart';

Future<DateTime?> datePicker(context) async {
  final DateTime? picked = await showDatePicker(
    context: context,
    initialDate: DateTime.now(),
    firstDate: DateTime(1900, 01, 01),
    lastDate: DateTime(2100, 12, 31),
  );
  return picked;
}

DateTime getMonthFirstDate() {
  DateTime now = DateTime.now();
  return DateTime(now.year, now.month, 1);
}

DateTime getMonthLastDate() {
  DateTime now = DateTime.now();
  return DateTime(now.year, now.month + 1, 1).subtract(const Duration(days: 1));
}
