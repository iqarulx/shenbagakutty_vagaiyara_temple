import 'dart:io';
import 'package:flutter/material.dart';
import '/view/view.dart';
import '/constants/constants.dart';
import '/services/services.dart';

class ImageFunctions {
  static Future uploadImage(context,
      {required ImageType type, required File file, String? prefix}) async {
    try {
      futureLoading(context);
      var data = await ImageService.uploadImage(
          type: type, file: file, prefix: prefix);
      Navigator.pop(context);
      if (data.isNotEmpty) {
        Snackbar.showSnackBar(context,
            content: "Image Updated Successfully", isSuccess: true);
      }
    } catch (e) {
      Navigator.pop(context);
      Snackbar.showSnackBar(context, content: e.toString(), isSuccess: false);
    }
  }
}
