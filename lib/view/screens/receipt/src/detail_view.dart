import 'package:flutter/material.dart';
import '/l10n/l10n.dart';
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
            AppLocalizations.of(context).receiptDetails,
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
              tableData(context, AppLocalizations.of(context).receiptType,
                  widget.model.receiptTypeName),
              tableData(context, AppLocalizations.of(context).receiptDate,
                  widget.model.receiptDate),
              tableData(context, AppLocalizations.of(context).receiptNumber,
                  widget.model.receiptNumber),
              tableData(context, AppLocalizations.of(context).lastOpenedBy,
                  widget.model.creatorName),
              tableData(context, AppLocalizations.of(context).memberId,
                  widget.model.statusBaseMemberId),
              tableData(context, AppLocalizations.of(context).memberName,
                  widget.model.memberName),
              tableData(context, AppLocalizations.of(context).yearAmount,
                  widget.model.yearAmount),
              tableData(context, AppLocalizations.of(context).poojaiFromDate,
                  widget.model.poojaiFromDate),
              tableData(context, AppLocalizations.of(context).poojaiToDate,
                  widget.model.poojaiToDate),
              tableData(context, AppLocalizations.of(context).poojaiAmount,
                  widget.model.poojaiAmount),
              tableData(context, AppLocalizations.of(context).amount,
                  widget.model.amount),
              tableData(context, AppLocalizations.of(context).decription,
                  widget.model.description),
              tableData(context, AppLocalizations.of(context).functionDate,
                  widget.model.functionDate),
              tableData(
                  context,
                  AppLocalizations.of(context).countForMudiKanikai,
                  widget.model.countForMudikanikai),
              tableData(
                  context,
                  AppLocalizations.of(context).countForKathukuthu,
                  widget.model.countForKadhuKuthu),
            ],
          )
        ],
      ),
    );
  }
}
