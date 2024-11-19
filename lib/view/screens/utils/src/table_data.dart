import 'package:flutter/material.dart';
import '/view/view.dart';

TableRow tableData(BuildContext context, String leftData, String rightData) {
  return TableRow(
    children: [
      Padding(
        padding: const EdgeInsets.all(2.0),
        child: Text(
          leftData,
          textAlign: TextAlign.start,
          style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                color: AppColors.greyColor,
              ),
        ),
      ),
      Padding(
        padding: const EdgeInsets.all(2.0),
        child: rightData.isNotEmpty
            ? Text(
                rightData,
                textAlign: TextAlign.end,
                style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                      color: AppColors.blackColor,
                    ),
              )
            : Text(
                "-",
                textAlign: TextAlign.end,
                style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                      color: AppColors.primaryColor,
                    ),
              ),
      ),
    ],
  );
}

TableRow profileData(BuildContext context, String leftData, String rightData) {
  return TableRow(
    children: [
      Padding(
        padding: const EdgeInsets.all(2.0),
        child: Text(
          leftData,
          textAlign: TextAlign.start,
          style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                color: AppColors.greyColor,
              ),
        ),
      ),
      Padding(
        padding: const EdgeInsets.all(2.0),
        child: rightData.isNotEmpty
            ? Text(
                rightData,
                textAlign: TextAlign.end,
                style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                      color: AppColors.blackColor,
                    ),
              )
            : Text(
                "-",
                textAlign: TextAlign.end,
                style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                      color: AppColors.primaryColor,
                    ),
              ),
      ),
    ],
  );
}
