import 'package:audioplayers/audioplayers.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:iconsax/iconsax.dart';
import '/l10n/l10n.dart';
import '/model/model.dart';
import '/functions/functions.dart';
import '/view/view.dart';

class Notif extends StatefulWidget {
  const Notif({super.key});

  @override
  State<Notif> createState() => _NotifState();
}

class _NotifState extends State<Notif> {
  late Future _notifHandler;
  final List<NotifModel> _nList = [];

  @override
  void initState() {
    _notifHandler = _getNotif();
    super.initState();
  }

  _getNotif() async {
    try {
      var d = await NotifFunctions.getNotif();
      var data = d["body"];
      for (var i in data) {
        _nList.add(
          NotifModel(
            notificationId: i["notification_id"],
            title: i["title"],
            description: i["description"],
            notificationImage: i["notification_image"],
            notificationAudio: i["notification_audio"],
          ),
        );
      }
    } catch (e) {
      Snackbar.showSnackBar(context, content: e.toString(), isSuccess: false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon:
              const Icon(Icons.arrow_back_ios_new_rounded, color: Colors.white),
          onPressed: () {
            Navigator.pop(context);
          },
          tooltip: AppLocalizations.of(context).back,
        ),
        title: Text(AppLocalizations.of(context).notification),
      ),
      body: FutureBuilder(
        future: _notifHandler,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return futureWaitingLoading(context);
          } else if (snapshot.hasError) {
            return Center(child: Text("Error: ${snapshot.error}"));
          } else {
            if (_nList.isNotEmpty) {
              return ListView.separated(
                padding: const EdgeInsets.all(5),
                primary: false,
                shrinkWrap: true,
                itemCount: _nList.length,
                itemBuilder: (context, index) {
                  var d = _nList;

                  return ListTile(
                    onTap: () async {
                      if (d[index].notificationAudio.isNotEmpty) {
                        final player = AudioPlayer();
                        await player.play(
                            UrlSource(d[index].notificationAudio.toString()));
                      }
                    },
                    leading: d[index].notificationImage.isNotEmpty
                        ? CircleAvatar(
                            backgroundImage: NetworkImage(
                                d[index].notificationImage.toString()),
                          )
                        : CircleAvatar(
                            backgroundColor: Theme.of(context).primaryColor,
                            child: SvgPicture.asset(SvgAssets.bellRing),
                          ),
                    title: Text(
                      d[index].title,
                      overflow: TextOverflow.ellipsis,
                    ),
                    subtitle: Text(
                      d[index].description,
                      overflow: TextOverflow.ellipsis,
                    ),
                    trailing: d[index].notificationAudio.isNotEmpty
                        ? IconButton(
                            tooltip: AppLocalizations.of(context).openFile,
                            icon: const Icon(Iconsax.music),
                            onPressed: () async {
                              final player = AudioPlayer();
                              await player.play(UrlSource(
                                  d[index].notificationAudio.toString()));
                            },
                          )
                        : null,
                  );
                },
                separatorBuilder: (context, index) {
                  return Divider(
                    color: Colors.grey.shade300,
                  );
                },
              );
            }
            return noData(context);
          }
        },
      ),
    );
  }
}
