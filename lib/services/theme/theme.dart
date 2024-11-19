import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '/view/view.dart';

class AppTheme {
  static ThemeData appTheme = ThemeData(
    colorScheme: ColorScheme.fromSeed(seedColor: AppColors.colorScheme),
    indicatorColor: AppColors.primaryColor,
    primaryColor: AppColors.primaryColor,
    useMaterial3: true,
    fontFamily: 'Lato',
    appBarTheme: AppBarTheme(
      centerTitle: true,
      backgroundColor: AppColors.primaryColor,
      systemOverlayStyle: const SystemUiOverlayStyle(
        statusBarColor: Colors.transparent,
        statusBarIconBrightness: Brightness.light,
        statusBarBrightness: Brightness.dark,
      ),
      titleTextStyle: TextStyle(
          color: AppColors.whiteColor, fontSize: 20, fontFamily: 'Lato'),
      iconTheme: IconThemeData(color: AppColors.whiteColor),
    ),
    inputDecorationTheme: const InputDecorationTheme(
      filled: true,
      fillColor: Color(0xfff1f5f9),
      border: OutlineInputBorder(
        borderSide: BorderSide.none,
      ),
    ),
    scaffoldBackgroundColor: const Color(0xffEEEEEE),
    dividerColor: Colors.grey.shade300,
  );
}
