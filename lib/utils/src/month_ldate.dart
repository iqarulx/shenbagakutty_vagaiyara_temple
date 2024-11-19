int monthLastDate(int month) {
  int year = DateTime.now().year;
  int? monthNumber = month;
  DateTime firstDayNextMonth = DateTime(year, monthNumber + 1, 1);
  DateTime lastDayOfMonth = firstDayNextMonth.subtract(const Duration(days: 1));
  return lastDayOfMonth.day;
}
