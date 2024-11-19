import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';

import '/constants/constants.dart';
import '/services/services.dart';
import '/view/view.dart';

class Appbar extends StatefulWidget {
  final String title;
  const Appbar({super.key, required this.title});

  @override
  State<Appbar> createState() => _AppbarState();
}

class _AppbarState extends State<Appbar> {
  late Future _appbarHandler;
  String? memberProfilePhoto;
  String memberName = "";

  @override
  void initState() {
    _appbarHandler = _init();
    super.initState();
  }

  _init() async {
    memberName = await Db.getData(type: UserData.memberName) ?? '';
    memberProfilePhoto = await Db.getData(type: UserData.profilePhoto);
    setState(() {});
  }

  @override
  Widget build(BuildContext context) {
    return PreferredSize(
      preferredSize: const Size(double.infinity, 50),
      child: AppBar(
        centerTitle: true,
        leading: FutureBuilder(
          future: _appbarHandler,
          builder: (context, snapshot) {
            if (snapshot.connectionState == ConnectionState.waiting) {
              return CircleAvatar(
                child: futureWaitingLoading(),
              );
            } else {
              return CircleAvatar(
                backgroundImage: NetworkImage(memberProfilePhoto ?? ''),
              );
            }
          },
        ),
        title: Text(widget.title),
        actions: [
          IconButton(
            tooltip: "Back",
            icon: const Icon(Iconsax.menu),
            onPressed: () {},
          )
        ],
      ),
    );
  }
}
