import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:url_launcher/url_launcher.dart';
import 'package:url_launcher/url_launcher_string.dart';
import '/view/view.dart';

class Contact extends StatefulWidget {
  const Contact({super.key});

  @override
  State<Contact> createState() => _ContactState();
}

class _ContactState extends State<Contact> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          tooltip: "Back",
          icon: const Icon(Icons.arrow_back_ios_new_rounded),
          onPressed: () {
            Navigator.pop(context);
          },
        ),
        title: const Text("Contact"),
      ),
      body: ListView(
        padding: const EdgeInsets.all(10),
        children: [
          Container(
            height: 160,
            decoration: BoxDecoration(
              borderRadius: BorderRadius.circular(10),
              image: const DecorationImage(
                fit: BoxFit.cover,
                image: AssetImage(ImageAssets.profileBack),
              ),
            ),
          ),
          const SizedBox(height: 10),
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(10),
            ),
            child: Column(
              children: [
                Text(
                  "Contact Information",
                  style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                        fontWeight: FontWeight.bold,
                      ),
                ),
                const SizedBox(height: 5),
                const Divider(),
                const SizedBox(height: 5),
                TextButton(
                  onPressed: () {
                    launchUrlString("tel:+919442722278");
                  },
                  child: Text("Mobile : +91 94427 22278",
                      style: Theme.of(context).textTheme.bodyLarge),
                ),
                TextButton(
                  onPressed: () {
                    launchUrlString("tel:+917305092278");
                  },
                  child: Text("Mobile : +91 73050 92278",
                      style: Theme.of(context).textTheme.bodyLarge),
                ),
                TextButton(
                  onPressed: () {
                    launchUrlString(
                        "mailto:hariharaputhraayyanar@gmail.com?subject=&body=");
                  },
                  child: Text("Email : hariharaputhraayyanar@gmail.com",
                      style: Theme.of(context).textTheme.bodyLarge),
                ),
              ],
            ),
          ),
          const SizedBox(height: 10),
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(10),
            ),
            child: Column(
              children: [
                Text(
                  "Address Information",
                  style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                        fontWeight: FontWeight.bold,
                      ),
                ),
                const SizedBox(height: 5),
                const Divider(),
                const SizedBox(height: 5),
                Text("Arulmighu Hariharaputhra Ayyanar Temple",
                    style: Theme.of(context).textTheme.bodyLarge),
                TextButton.icon(
                  icon: SvgPicture.asset(
                    SvgAssets.location,
                    height: 20,
                    width: 20,
                  ),
                  onPressed: () async {
                    var url =
                        "https://maps.google.com/maps?ll=9.446587,77.7955&z=18&t=m&hl=en&gl=IN&mapclient=embed&cid=11028767044078909208";
                    await launchUrl(Uri.parse(url));
                  },
                  label: Text(
                    "Kaliappa Nagar, Sivakasi, Tamil Nadu 626123",
                    style: Theme.of(context).textTheme.bodySmall!.copyWith(
                          color: AppColors.greyColor,
                        ),
                    textAlign: TextAlign.center,
                  ),
                ),
                const SizedBox(height: 5),
                Text("Arulmighu Shenbaga Vinayagar Temple",
                    style: Theme.of(context).textTheme.bodyLarge),
                TextButton.icon(
                  icon: SvgPicture.asset(
                    SvgAssets.location,
                    height: 20,
                    width: 20,
                  ),
                  onPressed: () async {
                    var url =
                        "https://maps.google.com/maps?ll=9.443163,77.792938&z=15&t=m&hl=en&gl=IN&mapclient=embed&cid=199997829008236116";
                    await launchUrl(Uri.parse(url));
                  },
                  label: Text(
                    "Sivakasi - Kalugumalai Rd, Kaliappa Nagar, Sivakasi, Tamil Nadu 626123",
                    style: Theme.of(context).textTheme.bodySmall!.copyWith(
                          color: AppColors.greyColor,
                        ),
                    textAlign: TextAlign.center,
                  ),
                ),
              ],
            ),
          ),
          const SizedBox(height: 10),
          Container(
            padding: const EdgeInsets.all(10),
            decoration: BoxDecoration(
              color: Colors.white,
              borderRadius: BorderRadius.circular(10),
            ),
            child: Column(
              children: [
                Text(
                  "Copyright © 2023, SHENBAGAKUTTY VAGAIYARA THAYATHIGAL SANGAM",
                  style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                        fontWeight: FontWeight.bold,
                      ),
                  textAlign: TextAlign.center,
                ),
                TextButton.icon(
                  icon: SvgPicture.asset(
                    SvgAssets.earthLock,
                    height: 20,
                    width: 20,
                  ),
                  onPressed: () async {
                    var url =
                        "https://sridemoapps.in/mahendran2022/temple/privacypolicy.php";
                    await launchUrl(Uri.parse(url));
                  },
                  label: Text(
                    "Privacy Policy",
                    style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                          color: AppColors.greyColor,
                        ),
                    textAlign: TextAlign.center,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
