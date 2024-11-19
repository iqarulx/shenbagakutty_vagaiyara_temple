import 'package:flutter/material.dart';

import '/constants/constants.dart';
import '/view/view.dart';

futureLoading(context, {LoadingText? textType}) {
  showDialog(
    context: context,
    barrierDismissible: false,
    builder: (context) => WillPopScope(
      onWillPop: () async {
        return false;
      },
      child: Center(
        child: Container(
          padding: const EdgeInsets.all(10),
          decoration: const BoxDecoration(
            color: Colors.white,
            shape: BoxShape.circle,
          ),
          child: CircularProgressIndicator(
            color: AppColors.primaryColor,
          ),
        ),
      ),
    ),
  );
}

futureWaitingLoading() {
  return Center(
    child: CircularProgressIndicator(
      color: AppColors.primaryColor,
    ),
  );
}

futureExpandedLoading() {
  return Expanded(
    child: Center(
      child: CircularProgressIndicator(
        color: AppColors.primaryColor,
      ),
    ),
  );
}
