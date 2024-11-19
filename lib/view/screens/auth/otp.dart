// ignore_for_file: unused_element

import 'dart:async';
import 'package:flutter/cupertino.dart';

class Otp extends StatefulWidget {
  final String mobileNo;
  final String memberId;
  final String memberLoginId;
  final String password;

  const Otp({
    super.key,
    required this.mobileNo,
    required this.memberId,
    required this.memberLoginId,
    required this.password,
  });

  @override
  State<Otp> createState() => _OtpState();
}

class _OtpState extends State<Otp> {
  TextEditingController otpValue = TextEditingController();
  var otpKey = GlobalKey<FormState>();

  final int _start = 60;
  int _current = 60;

  late Timer _timer;

  void _startTimer() {
    _timer = Timer.periodic(const Duration(seconds: 1), (timer) {
      setState(() {
        if (_current < 1) {
          _timer.cancel();
          // Do something when timer ends
        } else {
          _current--;
        }
      });
    });
  }

  void _resetTimer() {
    setState(() {
      _timer.cancel();
      _current = _start;
    });
  }

  void _sendOtp() {}

  @override
  Widget build(BuildContext context) {
    return const Placeholder();
  }
}
