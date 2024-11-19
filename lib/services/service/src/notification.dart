import 'dart:math';
import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:permission_handler/permission_handler.dart';
import '../../../firebase_options.dart';

class NotificationService {
  static final FlutterLocalNotificationsPlugin notification =
      FlutterLocalNotificationsPlugin();
  static late FirebaseMessaging messaging;

  static const AndroidNotificationChannel fbNotificationChannel =
      AndroidNotificationChannel(
    'fb_notification_channel',
    'Firebase Notifications',
    description: 'Channel for Firebase notifications',
    importance: Importance.max,
  );

  static Future<void> initialize() async {
    const AndroidInitializationSettings initializationSettingsAndroid =
        AndroidInitializationSettings('@mipmap/ic_launcher');

    const DarwinInitializationSettings initializationSettingsIOS =
        DarwinInitializationSettings(
      requestAlertPermission: true,
      requestBadgePermission: true,
      requestSoundPermission: true,
    );

    const InitializationSettings initializationSettings =
        InitializationSettings(
      android: initializationSettingsAndroid,
      iOS: initializationSettingsIOS,
    );

    await notification.initialize(initializationSettings);

    // Request permissions for notifications
    await requestPermissions();

    // Create the default download channel
    const AndroidNotificationChannel downloadChannel =
        AndroidNotificationChannel(
      'download_channel',
      'Download Progress',
      description: 'Shows download progress notifications',
      importance: Importance.max,
    );

    await notification
        .resolvePlatformSpecificImplementation<
            AndroidFlutterLocalNotificationsPlugin>()
        ?.createNotificationChannel(downloadChannel);

    // Create the Firebase notification channel
    await notification
        .resolvePlatformSpecificImplementation<
            AndroidFlutterLocalNotificationsPlugin>()
        ?.createNotificationChannel(fbNotificationChannel);

    messaging = FirebaseMessaging.instance;
    FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);

    await messaging.requestPermission(
      alert: true,
      announcement: false,
      badge: true,
      carPlay: false,
      criticalAlert: false,
      provisional: false,
      sound: true,
    );

    await messaging.setForegroundNotificationPresentationOptions(
      alert: true,
      badge: true,
      sound: true,
    );
  }

  static Future<String?> getFCM() async {
    final fcmToken = await FirebaseMessaging.instance.getToken();
    return fcmToken;
  }

  static Future<String?> getAPNSToken() async {
    final aspnToken = await FirebaseMessaging.instance.getAPNSToken();
    return aspnToken;
  }

  static Future<void> showFirebaseNotification(RemoteMessage message) async {
    await notification.show(
      Random().nextInt(10000),
      message.notification?.title ?? 'No Title',
      message.notification?.body ?? 'No Body',
      const NotificationDetails(
        android: AndroidNotificationDetails(
          'fb_notification_channel',
          'Firebase Notifications',
          channelDescription: 'Channel for Firebase notifications',
          importance: Importance.max,
          priority: Priority.high,
          playSound: true,
          enableVibration: true,
        ),
        iOS: DarwinNotificationDetails(
          presentAlert: true,
          presentBadge: true,
          presentSound: true,
        ),
      ),
      payload: message.data['payload'],
    );
  }

  static Future<void> _firebaseMessagingBackgroundHandler(
      RemoteMessage message) async {
    await Firebase.initializeApp(
        options: DefaultFirebaseOptions.currentPlatform);
  }

  static Future requestPermissions() async {
    if (await Permission.notification.isDenied) {
      await Permission.notification.request();
    }
    // iOS-specific request
    await notification
        .resolvePlatformSpecificImplementation<
            IOSFlutterLocalNotificationsPlugin>()
        ?.requestPermissions(
          alert: true,
          badge: true,
          sound: true,
        );
  }

  static final notificationId = Random().nextInt(10000);

  static Future download({required String title, String? payload}) async {
    await notification.show(
      notificationId,
      'Download',
      title,
      const NotificationDetails(
        android: AndroidNotificationDetails(
          'download_channel',
          'Download Progress',
          channelDescription: 'Shows download progress',
          importance: Importance.high,
          priority: Priority.high,
          playSound: true,
          enableVibration: true,
        ),
        iOS: DarwinNotificationDetails(
          presentAlert: true,
          presentBadge: true,
          presentSound: true,
        ),
      ),
      payload: payload,
    );
  }
}
