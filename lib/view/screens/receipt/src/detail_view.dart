import 'package:flutter/material.dart';
import '/model/model.dart';
import '/view/view.dart';

class DetailView extends StatefulWidget {
  final ReceiptModel model;
  const DetailView({super.key, required this.model});

  @override
  State<DetailView> createState() => _DetailViewState();
}

class _DetailViewState extends State<DetailView> {
  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: const BorderRadius.only(
        topLeft: Radius.circular(30),
        topRight: Radius.circular(30),
      ),
      child: ListView(
        padding: const EdgeInsets.all(10),
        children: [
          Text(
            "Receipt Details",
            style: Theme.of(context)
                .textTheme
                .bodyLarge!
                .copyWith(fontWeight: FontWeight.bold),
          ),
          const SizedBox(height: 10),
          const Divider(),
          const SizedBox(height: 10),
          Table(
            children: [
              tableData(context, "Receipt Type", widget.model.receiptTypeName),
              tableData(context, "Receipt Date", widget.model.receiptDate),
              tableData(context, "Receipt Number", widget.model.receiptNumber),
              tableData(context, "Last Opened By", widget.model.creatorName),
              tableData(context, "Member Id", widget.model.statusBaseMemberId),
              tableData(context, "Member Name", widget.model.memberName),
              tableData(context, "Year Amount", widget.model.yearAmount),
              tableData(
                  context, "Pooja From Date", widget.model.poojaiFromDate),
              tableData(context, "Pooja To Date", widget.model.poojaiToDate),
              tableData(context, "Poojai Amount", widget.model.poojaiAmount),
              tableData(context, "Amount", widget.model.amount),
              tableData(context, "Description", widget.model.description),
              tableData(context, "Function Date", widget.model.functionDate),
              tableData(context, "Count For Mudikanikai",
                  widget.model.countForMudikanikai),
              tableData(context, "Count For Kadhu Kuthu",
                  widget.model.countForKadhuKuthu),
            ],
          )
        ],
      ),
    );
  }
}
