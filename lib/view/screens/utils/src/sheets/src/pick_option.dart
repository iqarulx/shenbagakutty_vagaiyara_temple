import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';

class PickOption extends StatefulWidget {
  const PickOption({super.key});

  @override
  State<PickOption> createState() => _PickOptionState();
}

class _PickOptionState extends State<PickOption> {
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
                "Camera",
                style: TextStyle(color: Colors.black),
              ),
              leading: const Icon(Iconsax.camera),
            ),
            ListTile(
              onTap: () {
                Navigator.pop(context, 2);
              },
              title: const Text(
                "Gallery",
                style: TextStyle(color: Colors.black),
              ),
              leading: const Icon(Iconsax.gallery),
            )
          ],
        ),
      ),
    );
  }
}
