import 'dart:convert';
import 'dart:developer';

import 'package:firebase_core/firebase_core.dart';
import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:sintren_mobile/main.dart';
import 'package:sintren_mobile/models/user_login_model.dart';
import 'package:sintren_mobile/ui/penyuluh/histori_penyuluhan_view.dart';
import 'package:sintren_mobile/ui/uptd/uptd_verify_view.dart';

class FirebaseService {
  final _firebaseMessaging = FirebaseMessaging.instance;
  static const AndroidNotificationChannel _channel = AndroidNotificationChannel(
    'verify_notifications_channel',
    'Verify Notifications',
    description: 'This channel is used for important notifications.',
    importance: Importance.high,
    playSound: true,
  );

  final _localNotifications = FlutterLocalNotificationsPlugin();

  Future<void> initNotifications() async {
    await _firebaseMessaging.requestPermission();
    final fCMToken = await _firebaseMessaging.getToken();
    log('Token: $fCMToken');

    await _requestPermission();
    await _subscribeToTopic();
    await initPushNotifications();
    await initLocalNotifications();
  }

  @pragma('vm:entry-point')
  static Future<void> _firebaseMessagingBackgroundHandler(
      RemoteMessage message) async {
    await Firebase.initializeApp();

    log("Handling a background message: ${message.messageId}");
    if (message.notification != null) {
      log('Message also contained a notification: ${message.notification!.title}');
      log('Message also contained a notification: ${message.notification!.body}');
    }
  }

  Future<void> _requestPermission() async {
    FirebaseMessaging messaging = FirebaseMessaging.instance;

    NotificationSettings settings = await messaging.requestPermission(
      alert: true,
      badge: true,
      sound: true,
    );

    if (settings.authorizationStatus == AuthorizationStatus.authorized) {
      log('User granted permission');
    } else if (settings.authorizationStatus ==
        AuthorizationStatus.provisional) {
      log('User granted provisional permission');
    } else {
      log('User declined or has not accepted permission');
    }
  }

  Future<void> initPushNotifications() async {
    FirebaseMessaging.instance.setForegroundNotificationPresentationOptions(
        alert: true, badge: true, sound: true);

    FirebaseMessaging.instance.getInitialMessage().then((message) {
      if (message != null) {
        _handleMessage(message);
      }
    });

    FirebaseMessaging.onMessageOpenedApp.listen(_handleMessage);
    FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);

    FirebaseMessaging.onMessage.listen((message) {
      final notification = message.notification;
      if (notification == null) return;
      _localNotifications.show(
        notification.hashCode,
        notification.title,
        notification.body,
        NotificationDetails(
          android: AndroidNotificationDetails(
            _channel.id,
            _channel.name,
            channelDescription: _channel.description,
            icon: '@drawable/ic_launcher',
          ),
        ),
        payload: jsonEncode(message.toMap()),
      );
    });
  }

  Future<void> initLocalNotifications() async {
    const android = AndroidInitializationSettings('@drawable/ic_launcher');
    const settings = InitializationSettings(android: android);
    await _localNotifications.initialize(settings,
        onDidReceiveNotificationResponse:
            (NotificationResponse notificationResponse) {
      final String? payload = notificationResponse.payload;
      if (payload != null) {
        final message = RemoteMessage.fromMap(jsonDecode(payload));
        _handleMessage(message);
      }
    });
    final platform = _localNotifications.resolvePlatformSpecificImplementation<
        AndroidFlutterLocalNotificationsPlugin>();
    await platform?.createNotificationChannel(_channel);
  }

  Future<void> _handleMessage(RemoteMessage? message) async {
    String? role = await UserLoginModel().getRole();

    if (message == null) return;

    if (role == "UPTD") {
      navigatorKey.currentState?.pushNamed(UptdVerifyView.route);
    }

    if (role == "PENYULUH") {
      navigatorKey.currentState?.pushNamed(HistoriPenyuluhanView.route);
    }
  }

  Future<void> _subscribeToTopic() async {
    String? role = await UserLoginModel().getRole();
    String? id = await UserLoginModel().getUserId();

    final prefs = await SharedPreferences.getInstance();
    final topic = prefs.getString('subscribed_topic') ?? "";

    if (role == "UPTD") {
      id = await UserLoginModel().getKecamatanId();
    }

    if (id != null) {
      log('Subscribing to topic: $id');
      if (topic == "") {
        await prefs.setString('subscribed_topic', id);
      } else {
        await FirebaseMessaging.instance.unsubscribeFromTopic(topic);
        await prefs.setString('subscribed_topic', id);
      }
      await FirebaseMessaging.instance.subscribeToTopic(id);
    } else {
      log('User ID or Kecamatan ID is null, subscription not applied');
    }
  }
}
