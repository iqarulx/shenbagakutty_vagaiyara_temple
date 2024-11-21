import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';

class Language extends StatefulWidget {
  const Language({super.key});

  @override
  State<Language> createState() => _LanguageState();
}

class _LanguageState extends State<Language> {
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
              title: const Text(
                "English",
                style: TextStyle(color: Colors.black),
              ),
              leading: const Icon(Iconsax.language_circle),
            ),
            ListTile(
              onTap: () {
                Navigator.pop(context, 2);
              },
              title: const Text(
                "Tamil",
                style: TextStyle(color: Colors.black),
              ),
              leading: const Icon(Iconsax.language_circle),
            ),
            ListTile(
              onTap: () {
                Navigator.pop(context, 3);
              },
              title: const Text(
                "Telugu",
                style: TextStyle(color: Colors.black),
              ),
              leading: const Icon(Iconsax.language_circle),
            ),
            ListTile(
              onTap: () {
                Navigator.pop(context, 4);
              },
              title: const Text(
                "Kannada",
                style: TextStyle(color: Colors.black),
              ),
              leading: const Icon(Iconsax.language_circle),
            ),
            ListTile(
              onTap: () {
                Navigator.pop(context, 5);
              },
              title: const Text(
                "Malayalam",
                style: TextStyle(color: Colors.black),
              ),
              leading: const Icon(Iconsax.language_circle),
            ),
          ],
        ),
      ),
    );
  }
}
