import 'dart:io';

import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:open_app_settings/open_app_settings.dart';
import 'package:permission_handler/permission_handler.dart';
import 'package:provider/provider.dart';
import 'package:url_launcher/url_launcher.dart';
import '/services/services.dart';
import '/utils/utils.dart';
import '/view/view.dart';
import '/app/app.dart';
import '/l10n/l10n.dart';

class Settings extends StatefulWidget {
  const Settings({super.key});

  @override
  State<Settings> createState() => _SettingsState();
}

class _SettingsState extends State<Settings> {
  bool _notEnabled = false;
  String appVersion = "";
  String _theme = "";

  @override
  void initState() {
    _init();
    super.initState();
  }

  Future _init() async {
    if (await Permission.notification.status.isDenied) {
      _notEnabled = false;
    } else {
      _notEnabled = true;
    }
    if (Platform.isAndroid) {
      var v = await AppInfo.getVersion();
      var vc = await AppInfo.getVersionCode();

      appVersion = "$v ($vc)";
    } else {
      var v = await AppInfo.getVersion();
      appVersion = v;
    }

    _theme = await Db.getTheme() ?? 'light';

    setState(() {});
  }

  Future _changeNotSetting(bool value) async {
    if (value) {
      var status = await Permission.notification.request();
      _notEnabled = status.isGranted;
    } else {
      await OpenAppSettings.openAppSettings();
    }
    setState(() {});
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(AppLocalizations.of(context).settings),
        leading: IconButton(
          tooltip: AppLocalizations.of(context).back,
          icon:
              const Icon(Icons.arrow_back_ios_new_rounded, color: Colors.white),
          onPressed: () {
            Navigator.pop(context);
          },
        ),
      ),
      body: ListView(
        padding: const EdgeInsets.all(5),
        children: [
          ListTile(
            onTap: () {},
            leading: CircleAvatar(
              backgroundColor: Theme.of(context).primaryColor,
              child: SvgPicture.asset(
                SvgAssets.bellRing,
                height: 20,
                width: 20,
              ),
            ),
            title: Text(
              AppLocalizations.of(context).notification,
              overflow: TextOverflow.ellipsis,
            ),
            subtitle: Text(
              AppLocalizations.of(context).notificationSubtitle,
              style: Theme.of(context).textTheme.bodySmall,
            ),
            trailing: Transform.scale(
              scale: 0.8,
              child: CupertinoSwitch(
                activeColor: Theme.of(context).primaryColor,
                onChanged: (value) {
                  _changeNotSetting(value);
                },
                value: _notEnabled,
              ),
            ),
          ),
          Divider(color: Colors.grey.shade300),
          ListTile(
            onTap: () {
              final themeProvider =
                  Provider.of<ThemeProvider>(context, listen: false);
              final currentTheme = themeProvider.appTheme == AppTheme.appTheme
                  ? "dark"
                  : "light";
              themeProvider.changeTheme(currentTheme);
              _init();
              setState(() {});
            },
            leading: CircleAvatar(
              backgroundColor: Theme.of(context).primaryColor,
              child: SvgPicture.asset(
                SvgAssets.palette,
                height: 20,
                width: 20,
              ),
            ),
            title: Text(
              AppLocalizations.of(context).appTheme,
              overflow: TextOverflow.ellipsis,
            ),
            subtitle: Text(
              _theme,
              style: Theme.of(context).textTheme.bodySmall,
            ),
            trailing: IconButton(
              tooltip: AppLocalizations.of(context).appThemeSubtitle,
              icon: _theme == "light"
                  ? SvgPicture.asset(
                      SvgAssets.moon,
                      height: 25,
                      width: 25,
                    )
                  : SvgPicture.asset(
                      SvgAssets.sun,
                      height: 25,
                      width: 25,
                    ),
              onPressed: () async {
                final themeProvider =
                    Provider.of<ThemeProvider>(context, listen: false);
                final currentTheme = themeProvider.appTheme == AppTheme.appTheme
                    ? "dark"
                    : "light";
                themeProvider.changeTheme(currentTheme);
                _init();
                setState(() {});
              },
            ),
          ),
          Divider(color: Colors.grey.shade300),
          ListTile(
            onTap: () {},
            leading: CircleAvatar(
              backgroundColor: Theme.of(context).primaryColor,
              child: SvgPicture.asset(
                SvgAssets.palette,
                height: 20,
                width: 20,
              ),
            ),
            title: Text(
              AppLocalizations.of(context).language,
              overflow: TextOverflow.ellipsis,
            ),
            subtitle: Text(
              _theme,
              style: Theme.of(context).textTheme.bodySmall,
            ),
            trailing: IconButton(
              tooltip: AppLocalizations.of(context).languageSubtitle,
              icon: SvgPicture.asset(
                SvgAssets.language,
                height: 25,
                width: 25,
              ),
              onPressed: () async {
                var v = await Sheet.showSheet(context,
                    size: 0.4, widget: const Language());
                final localeProvider =
                    Provider.of<LocaleProvider>(context, listen: false);

                if (v != null) {
                  if (v == 1) {
                    localeProvider.setLocale(const Locale('en'));
                  } else if (v == 2) {
                    localeProvider.setLocale(const Locale('ta'));
                  } else if (v == 3) {
                    localeProvider.setLocale(const Locale('te'));
                  } else if (v == 4) {
                    localeProvider.setLocale(const Locale('ka'));
                  } else if (v == 5) {
                    localeProvider.setLocale(const Locale('ml'));
                  }
                }
              },
            ),
          ),
          Divider(color: Colors.grey.shade300),
          ListTile(
            onTap: () async {
              var r = await AppCache.clearCache();
              if (r) {
                Navigator.pop(context);
                Snackbar.showSnackBar(context,
                    isSuccess: true, content: "Cache cleared");
              } else {
                Navigator.pop(context);
                Snackbar.showSnackBar(context,
                    isSuccess: false, content: "Failed to clear cache");
              }
            },
            leading: CircleAvatar(
              backgroundColor: Theme.of(context).primaryColor,
              child: SvgPicture.asset(
                SvgAssets.erase,
                height: 20,
                width: 20,
              ),
            ),
            title: Text(
              AppLocalizations.of(context).cache,
              overflow: TextOverflow.ellipsis,
            ),
            subtitle: Text(
              AppLocalizations.of(context).cacheSubtitle,
              overflow: TextOverflow.ellipsis,
              style: Theme.of(context).textTheme.bodySmall,
            ),
            trailing: IconButton(
              tooltip: AppLocalizations.of(context).clearTempFiles,
              icon: SvgPicture.asset(
                SvgAssets.info,
                height: 30,
                width: 30,
              ),
              onPressed: () async {
                await showDialog(
                        context: context,
                        builder: (context) => const CacheDialog())
                    .then((value) async {
                  if (value != null) {
                    if (value) {
                      var r = await AppCache.clearCache();
                      if (r) {
                        Navigator.pop(context);
                        Snackbar.showSnackBar(context,
                            isSuccess: true, content: "Cache cleared");
                      } else {
                        Navigator.pop(context);
                        Snackbar.showSnackBar(context,
                            isSuccess: false, content: "Failed to clear cache");
                      }
                    }
                  }
                });
              },
            ),
          ),
          Divider(color: Colors.grey.shade300),
          ListTile(
            onTap: () {},
            leading: CircleAvatar(
              backgroundColor: Theme.of(context).primaryColor,
              child: SvgPicture.asset(
                SvgAssets.device,
                height: 20,
                width: 20,
              ),
            ),
            title: Text(
              AppLocalizations.of(context).appVersion,
              overflow: TextOverflow.ellipsis,
            ),
            subtitle: Text(
              appVersion,
              style: Theme.of(context).textTheme.bodySmall,
            ),
            trailing: IconButton(
              tooltip: AppLocalizations.of(context).openFile,
              icon: Platform.isAndroid
                  ? SvgPicture.asset(
                      SvgAssets.playStore,
                      height: 25,
                      width: 25,
                    )
                  : SvgPicture.asset(
                      SvgAssets.appStore,
                      height: 25,
                      width: 25,
                    ),
              onPressed: () async {
                if (Platform.isAndroid) {
                  var url =
                      "https://play.google.com/store/apps/details?id=com.shenbagakutty.vagaiyara&hl=en";
                  await launchUrl(Uri.parse(url));
                } else if (Platform.isIOS) {}
              },
            ),
          ),
        ],
      ),
    );
  }
}
