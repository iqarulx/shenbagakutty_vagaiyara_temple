import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '/constants/constants.dart';
import '/services/services.dart';
import '/view/view.dart';
import 'app_utils.dart';

class App extends StatelessWidget {
  const App({super.key});

  @override
  Widget build(BuildContext context) {
    return ChangeNotifierProvider(
      create: (context) => AuthProvider()..checkLoginStatus(),
      child: Consumer<AuthProvider>(
        builder: (context, authProvider, child) {
          return MaterialApp(
            debugShowCheckedModeBanner: false,
            title: projectTitle,
            theme: AppTheme.appTheme,
            home: authProvider.isLoggedIn
                ? authProvider.homeWidget ?? Container()
                : futureWaitingLoading(),
          );
        },
      ),
    );
  }
}
