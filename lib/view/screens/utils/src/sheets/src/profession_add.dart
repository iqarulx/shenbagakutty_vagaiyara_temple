import 'package:flutter/material.dart';
import '/functions/functions.dart';
import '/view/view.dart';

class ProfessionAdd extends StatefulWidget {
  const ProfessionAdd({super.key});

  @override
  State<ProfessionAdd> createState() => _ProfessionAddState();
}

class _ProfessionAddState extends State<ProfessionAdd> {
  TextEditingController searchForm = TextEditingController();
  var formKey = GlobalKey<FormState>();

  _createProfession() async {
    try {
      futureLoading(context);
      await UtilsFunctions.createProfession(pName: searchForm.text)
          .then((value) {
        Navigator.pop(context);
        Snackbar.showSnackBar(context,
            content: "New profession created", isSuccess: true);
      });
    } catch (e) {
      Navigator.pop(context);
      Snackbar.showSnackBar(context, content: e.toString(), isSuccess: false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: const BorderRadius.only(
        topLeft: Radius.circular(30),
        topRight: Radius.circular(30),
      ),
      child: Scaffold(
        backgroundColor: AppColors.pureWhiteColor,
        body: Padding(
          padding: const EdgeInsets.only(left: 15, bottom: 15, right: 15),
          child: Form(
            key: formKey,
            child: Column(
              children: [
                Text(
                  "Create new profession",
                  style: Theme.of(context).textTheme.bodyLarge,
                ),
                const SizedBox(height: 5),
                const Divider(),
                const SizedBox(height: 5),
                FormFields(
                  controller: searchForm,
                  label: "Profession Name",
                  hintText: "Profession Name",
                  valid: (input) {
                    if (input != null) {
                      if (input.isEmpty) {
                        return 'Enter profession name';
                      }
                    }
                    return null;
                  },
                ),
                const SizedBox(height: 30),
                ElevatedButton(
                  style: ButtonStyle(
                    backgroundColor: MaterialStateProperty.all(
                        Theme.of(context).primaryColor),
                    tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                    minimumSize: MaterialStateProperty.all(const Size(100, 40)),
                    padding: MaterialStateProperty.all(EdgeInsets.zero),
                    shape: MaterialStateProperty.all(
                      RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(8),
                      ),
                    ),
                  ),
                  child: Text("Create",
                      style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                            color: AppColors.pureWhiteColor,
                          )),
                  onPressed: () async {
                    if (formKey.currentState!.validate()) {
                      _createProfession();
                    }
                  },
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }
}
