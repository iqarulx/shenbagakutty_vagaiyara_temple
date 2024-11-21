import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import '/l10n/l10n.dart';

class ListOption extends StatefulWidget {
  const ListOption({super.key});

  @override
  State<ListOption> createState() => _ListOptionState();
}

class _ListOptionState extends State<ListOption> {
  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: const BorderRadius.only(
        topLeft: Radius.circular(30),
        topRight: Radius.circular(30),
      ),
      child: Padding(
        padding: const EdgeInsets.only(left: 15, bottom: 15, right: 15),
        child: ListView(
          primary: false,
          shrinkWrap: true,
          children: [
            ListTile(
              onTap: () {
                Navigator.pop(context, 1);
              },
              title: Text(
                AppLocalizations.of(context).viewDetail,
                style: const TextStyle(color: Colors.black),
              ),
              leading: const Icon(Icons.open_in_new_rounded),
            ),
            ListTile(
              onTap: () {
                Navigator.pop(context, 2);
              },
              title: Text(
                AppLocalizations.of(context).print,
                style: const TextStyle(color: Colors.black),
              ),
              leading: const Icon(Iconsax.printer),
            )
          ],
        ),
      ),
    );
  }
}
