import 'dart:typed_data';
import 'dart:ui' as ui;
import 'package:flutter/material.dart';
import 'package:flutter/rendering.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:qr_flutter/qr_flutter.dart';
import '/utils/utils.dart';
import '/functions/functions.dart';
import '/view/view.dart';

class Qr extends StatefulWidget {
  const Qr({super.key});

  @override
  State<Qr> createState() => _QrState();
}

class _QrState extends State<Qr> {
  late Future _qrHanlder;
  Map<String, dynamic> _qrData = {};
  final GlobalKey _renderObjectKey = GlobalKey();

  @override
  void initState() {
    _qrHanlder = _init();
    super.initState();
  }

  Future<void> _init() async {
    try {
      var d = await QrFunctions.getQrDetails();
      setState(() {
        _qrData = d["body"];
      });
    } catch (e) {
      Snackbar.showSnackBar(context, content: e.toString(), isSuccess: false);
    }
  }

  Future<Uint8List?> _getWidgetImage() async {
    try {
      futureLoading(context);
      await Future.delayed(const Duration(milliseconds: 100));

      RenderRepaintBoundary boundary = _renderObjectKey.currentContext
          ?.findRenderObject() as RenderRepaintBoundary;

      if (boundary.debugNeedsPaint) {
        await Future.delayed(const Duration(milliseconds: 100));
      }

      ui.Image image = await boundary.toImage(pixelRatio: 3.0);
      ByteData? byteData =
          await image.toByteData(format: ui.ImageByteFormat.png);
      Navigator.pop(context);
      return byteData?.buffer.asUint8List();
    } catch (e) {
      Navigator.pop(context);
      return null;
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
          tooltip: "Back",
        ),
        title: const Text("Qr Details"),
        actions: [
          IconButton(
            tooltip: "Download Qr",
            icon: SvgPicture.asset(SvgAssets.download),
            onPressed: () async {
              Uint8List? imageBytes = await _getWidgetImage();
              if (imageBytes != null) {
                await FileUtils.openAsBytes(
                    context, imageBytes, "Member Qr", "png");
              }
            },
          )
        ],
      ),
      body: FutureBuilder(
        future: _qrHanlder,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return futureWaitingLoading();
          } else if (snapshot.hasError) {
            return Center(child: Text("Error: ${snapshot.error}"));
          } else {
            return ListView(
              padding: const EdgeInsets.all(10),
              children: [
                RepaintBoundary(
                  key: _renderObjectKey,
                  child: Container(
                    padding: const EdgeInsets.all(10),
                    decoration: BoxDecoration(
                      color: Colors.white,
                      borderRadius: BorderRadius.circular(10),
                    ),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        QrImageView(
                          data: _qrData["member_id"].toString(),
                          version: QrVersions.auto,
                          size: 200.0,
                          backgroundColor: AppColors.pureWhiteColor,
                        ),
                        const SizedBox(height: 10),
                        Text(
                          "Member Id : ${_qrData["member_id"]}",
                          style: Theme.of(context).textTheme.bodyLarge,
                        ),
                        const SizedBox(height: 10),
                      ],
                    ),
                  ),
                ),
                const SizedBox(height: 10),
                Container(
                  padding: const EdgeInsets.all(10),
                  decoration: BoxDecoration(
                    color: AppColors.pureWhiteColor,
                    borderRadius: BorderRadius.circular(10),
                  ),
                  child: Table(
                    children: [
                      tableData(context, "Member Name", _qrData["member_name"]),
                      tableData(context, "Mobile Number",
                          _qrData["mobile_number"].toString()),
                      tableData(context, "Father Name", _qrData["father_name"])
                    ],
                  ),
                ),
              ],
            );
          }
        },
      ),
    );
  }
}
