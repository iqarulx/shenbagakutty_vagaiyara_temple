import 'package:flutter/material.dart';
import 'package:flutter_svg/svg.dart';
import 'package:syncfusion_flutter_pdfviewer/pdfviewer.dart';
import '/l10n/l10n.dart';
import '/view/view.dart';
import '/utils/utils.dart';

class PdfView extends StatefulWidget {
  final String uri, name;
  const PdfView({super.key, required this.uri, required this.name});

  @override
  State<PdfView> createState() => _PdfViewState();
}

class _PdfViewState extends State<PdfView> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(AppLocalizations.of(context).receiptPdf),
        leading: IconButton(
          tooltip: AppLocalizations.of(context).back,
          icon: const Icon(Icons.arrow_back_ios_new_rounded),
          onPressed: () {
            Navigator.pop(context);
          },
        ),
        actions: [
          IconButton(
            tooltip: AppLocalizations.of(context).downloadPdf,
            icon: SvgPicture.asset(SvgAssets.download),
            onPressed: () async {
              await FileUtils.openFile(context, widget.uri, widget.name, 'pdf');
            },
          )
        ],
      ),
      body: SfPdfViewer.network(widget.uri),
    );
  }
}
