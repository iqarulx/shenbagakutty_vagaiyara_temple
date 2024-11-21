import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:intl/intl.dart';
import '/l10n/l10n.dart';
import '/services/db/db.dart';
import '/constants/constants.dart';
import '/functions/functions.dart';
import '/utils/utils.dart';
import '/view/view.dart';
import '/model/model.dart';
import 'sheets/sheets.dart';
import './pdf_view.dart';

class AddReceipt extends StatefulWidget {
  final List<MemberModel> mList;
  final List<RTypeModel> rList;

  const AddReceipt({super.key, required this.mList, required this.rList});

  @override
  State<AddReceipt> createState() => _AddReceiptState();
}

class _AddReceiptState extends State<AddReceipt> {
  @override
  void initState() {
    _rDate.text = DateFormat('yyyy-MM-dd').format(DateTime.now());
    _rType.addListener(() {
      setState(() {});
    });
    _member.addListener(() {
      setState(() {});
    });
    super.initState();
  }

  //***************** UI *********************/

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: _appbar(context),
      body: _form(context),
      bottomNavigationBar: bottomAppbar(context),
    );
  }

  Form _form(BuildContext context) {
    return Form(
      key: formKey,
      child: ListView(
        padding: const EdgeInsets.all(10),
        children: [
          Row(
            children: [
              Expanded(
                child: FormFields(
                  suffixIcon: const Icon(Iconsax.calendar_1),
                  controller: _rDate,
                  label: AppLocalizations.of(context).receiptDate,
                  hintText: "yyyy-MM-dd",
                  onTap: () => _rDatePicker(),
                  readOnly: true,
                ),
              ),
              const SizedBox(width: 10),
              Expanded(
                child: FormFields(
                  label: "${AppLocalizations.of(context).receiptType} (*)",
                  suffixIcon: _rType.text.isEmpty
                      ? const Icon(Icons.arrow_drop_down_rounded)
                      : IconButton(
                          tooltip: AppLocalizations.of(context).clear,
                          onPressed: () {
                            _rType.clear();
                            selectedRTypeId = null;
                            visibleFeilds.clear();
                            setState(() {});
                          },
                          icon: Icon(
                            Iconsax.close_circle,
                            color: Theme.of(context).primaryColor,
                          ),
                        ),
                  controller: _rType,
                  hintText: AppLocalizations.of(context).receiptType,
                  onTap: () async {
                    var value = await Sheet.showSheet(context,
                        size: 0.9, widget: RType(query: widget.rList));
                    if (value != null) {
                      _rType.text = value["value"];
                      visibleFeilds = List<String>.from(value["inputs"]);
                      selectedRTypeId = value["id"];

                      if (selectedRTypeId ==
                          "4d5463774e5449774d6a4d774e5455304e4442664d44453d") {
                        var val = await ReceiptFunctions.getNewThalakattuForm();
                        var v = val["head"];
                        _poojaiAmount.text = v["poojai_amount"].toString();
                        _poojaiFromDate.text = v["poojai_from_date"].toString();
                        _poojaiToDate.text = v["poojai_to_date"].toString();
                        _yearAmount.text = v["year_amount"].toString();
                      }

                      setState(() {});
                    }
                  },
                  readOnly: true,
                  valid: (input) {
                    if (input != null) {
                      if (input.isEmpty) {
                        return AppLocalizations.of(context).selectReceiptType;
                      }
                    }
                    return null;
                  },
                ),
              ),
            ],
          ),
          if (visibleFeilds.isNotEmpty)
            Column(
              children: [
                if (visibleFeilds.contains('Member'))
                  Padding(
                    padding: const EdgeInsets.only(top: 10),
                    child: FormFields(
                      label: "${AppLocalizations.of(context).member} (*)",
                      suffixIcon: _member.text.isEmpty
                          ? const Icon(Icons.arrow_drop_down_rounded)
                          : IconButton(
                              tooltip: AppLocalizations.of(context).clear,
                              onPressed: () {
                                _member.clear();
                                selectedMemberId = null;
                                setState(() {});
                              },
                              icon: Icon(
                                Iconsax.close_circle,
                                color: Theme.of(context).primaryColor,
                              ),
                            ),
                      controller: _member,
                      onTap: () async {
                        var value = await Sheet.showSheet(context,
                            size: 0.9,
                            widget: ListingMember(query: widget.mList));
                        if (value != null) {
                          _member.text = value["value"];
                          selectedMemberId = value["id"];
                        }
                      },
                      readOnly: true,
                      valid: (input) {
                        if (input != null) {
                          if (input.isEmpty) {
                            return AppLocalizations.of(context).selectMember;
                          }
                        }
                        return null;
                      },
                    ),
                  ),
                if (visibleFeilds.contains('Amount'))
                  Padding(
                    padding: const EdgeInsets.only(top: 10),
                    child: FormFields(
                      controller: _amount,
                      keyType: TextInputType.number,
                      label: "${AppLocalizations.of(context).amount} (*)",
                      valid: (input) {
                        if (input != null) {
                          if (input.isEmpty) {
                            return AppLocalizations.of(context).emterAmount;
                          }
                        }
                        return null;
                      },
                    ),
                  ),
                if (visibleFeilds.contains('Funeral To'))
                  Padding(
                    padding: const EdgeInsets.only(top: 10),
                    child: FormFields(
                      controller: _funeralTo,
                      label: AppLocalizations.of(context).funeralTo,
                      valid: (input) {
                        if (input != null) {
                          if (input.isEmpty) {
                            return AppLocalizations.of(context).enterFuneralTo;
                          }
                        }
                        return null;
                      },
                    ),
                  ),
                if (visibleFeilds.contains('Name'))
                  Padding(
                    padding: const EdgeInsets.only(top: 10),
                    child: FormFields(
                      controller: _nMemberName,
                      label: AppLocalizations.of(context).nonMemberName,
                      enabled: selectedMemberId == null,
                      valid: (input) {
                        if (_member.text.isNotEmpty) {
                          return null;
                        }
                        if (input != null) {
                          if (input.isEmpty) {
                            return AppLocalizations.of(context).nonMemberError;
                          }
                        }
                        return null;
                      },
                    ),
                  ),
                if (visibleFeilds.contains('Function Date'))
                  Padding(
                    padding: const EdgeInsets.only(top: 10),
                    child: FormFields(
                      suffixIcon: const Icon(Iconsax.calendar_1),
                      controller: _functionDate,
                      label: AppLocalizations.of(context).functionDate,
                      hintText: "yyyy-MM-dd",
                      onTap: () => _fDatePicker(),
                      readOnly: true,
                      valid: (input) {
                        if (input != null) {
                          if (input.isEmpty) {
                            return AppLocalizations.of(context).selectDate;
                          }
                        }
                        return null;
                      },
                    ),
                  ),
                if (visibleFeilds.contains('Count For MudiKanikai'))
                  Padding(
                    padding: const EdgeInsets.only(top: 10),
                    child: FormFields(
                      controller: _countForMudiKanikai,
                      keyType: TextInputType.number,
                      label: AppLocalizations.of(context).countForMudiKanikai,
                      valid: (input) {
                        if (input != null) {
                          if (input.isEmpty) {
                            return AppLocalizations.of(context).countError;
                          }
                        }
                        return null;
                      },
                    ),
                  ),
                if (visibleFeilds.contains('Count For Kadhu Kuthu'))
                  Padding(
                    padding: const EdgeInsets.only(top: 10),
                    child: FormFields(
                      controller: _countForKadhuKuthu,
                      keyType: TextInputType.number,
                      label: AppLocalizations.of(context).countForKathukuthu,
                      valid: (input) {
                        if (input != null) {
                          if (input.isEmpty) {
                            return AppLocalizations.of(context).countError;
                          }
                        }
                        return null;
                      },
                    ),
                  ),
                if (visibleFeilds.contains('Description'))
                  Padding(
                    padding: const EdgeInsets.only(top: 10),
                    child: FormFields(
                      controller: _description,
                      label: AppLocalizations.of(context).decription,
                      maxLines: 2,
                      valid: (input) {
                        if (input != null) {
                          if (input.isEmpty) {
                            return AppLocalizations.of(context)
                                .enterDescription;
                          }
                        }
                        return null;
                      },
                    ),
                  ),
                if (visibleFeilds.contains('Year Amount'))
                  Padding(
                    padding: const EdgeInsets.only(top: 10),
                    child: FormFields(
                      controller: _yearAmount,
                      label: AppLocalizations.of(context).yearAmount,
                      enabled: false,
                      valid: (input) {
                        if (input != null) {
                          if (input.isEmpty) {
                            return AppLocalizations.of(context).enterYearAmount;
                          }
                        }
                        return null;
                      },
                    ),
                  ),
                if (visibleFeilds.contains('Poojai From Date'))
                  Padding(
                    padding: const EdgeInsets.only(top: 10),
                    child: FormFields(
                      controller: _poojaiFromDate,
                      label: AppLocalizations.of(context).poojaiFromDate,
                      enabled: false,
                      valid: (input) {
                        if (input != null) {
                          if (input.isEmpty) {
                            return AppLocalizations.of(context)
                                .poojaiFromDateError;
                          }
                        }
                        return null;
                      },
                    ),
                  ),
                if (visibleFeilds.contains('Poojai To Date'))
                  Padding(
                    padding: const EdgeInsets.only(top: 10),
                    child: FormFields(
                      enabled: false,
                      controller: _poojaiToDate,
                      label: AppLocalizations.of(context).poojaiToDate,
                      valid: (input) {
                        if (input != null) {
                          if (input.isEmpty) {
                            return AppLocalizations.of(context)
                                .poojaiToDateError;
                          }
                        }
                        return null;
                      },
                    ),
                  ),
                if (visibleFeilds.contains('Poojai Amount'))
                  Padding(
                    padding: const EdgeInsets.only(top: 10),
                    child: FormFields(
                      enabled: false,
                      controller: _poojaiAmount,
                      label: AppLocalizations.of(context).poojaiAmount,
                      valid: (input) {
                        if (input != null) {
                          if (input.isEmpty) {
                            return AppLocalizations.of(context)
                                .poojaiAmountError;
                          }
                        }
                        return null;
                      },
                    ),
                  ),
              ],
            ),
        ],
      ),
    );
  }

  AppBar _appbar(BuildContext context) {
    return AppBar(
      leading: IconButton(
        tooltip: AppLocalizations.of(context).back,
        icon: const Icon(Icons.arrow_back_ios_new_rounded),
        onPressed: () {
          Navigator.pop(context);
        },
      ),
      title: Text(AppLocalizations.of(context).addReceipt),
    );
  }

  BottomAppBar bottomAppbar(BuildContext context) {
    return BottomAppBar(
      color: Colors.white,
      surfaceTintColor: Colors.white,
      child: ElevatedButton(
        style: ButtonStyle(
          backgroundColor:
              MaterialStateProperty.all(Theme.of(context).primaryColor),
          tapTargetSize: MaterialTapTargetSize.shrinkWrap,
          minimumSize: MaterialStateProperty.all(const Size(80, 30)),
          padding: MaterialStateProperty.all(EdgeInsets.zero),
          shape: MaterialStateProperty.all(
            RoundedRectangleBorder(
              borderRadius: BorderRadius.circular(8),
            ),
          ),
        ),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Iconsax.tick_circle, color: AppColors.pureWhiteColor),
            const SizedBox(width: 10),
            Text(
              AppLocalizations.of(context).submit,
              style: Theme.of(context)
                  .textTheme
                  .bodyLarge!
                  .copyWith(color: AppColors.pureWhiteColor),
            ),
          ],
        ),
        onPressed: () async {
          if (formKey.currentState!.validate()) {
            _submitForm();
          }
        },
      ),
    );
  }
  //***************** End of UI *********************/

  //***************** Submit *********************/
  _submitForm() async {
    try {
      futureLoading(context);
      var result = {};
      var creatorId = await Db.getData(type: UserData.memberId);

      if (_rType.text == "General Donation") {
        var map = {
          "edit_id": "",
          "receipt_date": _rDate.text,
          "receipt_type_id": selectedRTypeId,
          "member_id": selectedMemberId,
          "amount": _amount.text,
          "creator": creatorId,
        };

        result = await ReceiptFunctions.saveReceipt(
            query: map, type: ReceiptType.generalDonation);
      } else if (_rType.text == "Personal Savings") {
        var map = {
          "edit_id": "",
          "receipt_date": _rDate.text,
          "receipt_type_id": selectedRTypeId,
          "member_id": selectedMemberId,
          "amount": _amount.text,
          "creator": creatorId,
        };

        result = await ReceiptFunctions.saveReceipt(
            query: map, type: ReceiptType.personalSavings);
      } else if (_rType.text == "Nandavanam") {
        var map = {
          "edit_id": "",
          "receipt_date": _rDate.text,
          "receipt_type_id": selectedRTypeId,
          "member_id": selectedMemberId,
          "amount": _amount.text,
          "non_member_name": _nMemberName.text,
          "funeral_to": _funeralTo.text,
          "creator": creatorId,
        };

        result = await ReceiptFunctions.saveReceipt(
            query: map, type: ReceiptType.nandavanam);
      } else if (_rType.text == "Mudi Kaanikai") {
        var map = {
          "edit_id": "",
          "receipt_date": _rDate.text,
          "receipt_type_id": selectedRTypeId,
          "member_id": selectedMemberId,
          "amount": _amount.text,
          "function_date": _functionDate.text,
          "count_for_kadhu_kuthu": _countForKadhuKuthu.text,
          "count_for_mudikanikai": _countForMudiKanikai.text,
          "creator": creatorId,
        };

        result = await ReceiptFunctions.saveReceipt(
            query: map, type: ReceiptType.mudiKaanikai);
      } else if (_rType.text == "Pooja Donation") {
        var map = {
          "edit_id": "",
          "receipt_date": _rDate.text,
          "receipt_type_id": selectedRTypeId,
          "member_id": selectedMemberId,
          "amount": _amount.text,
          "creator": creatorId,
        };

        result = await ReceiptFunctions.saveReceipt(
            query: map, type: ReceiptType.poojaDonation);
      } else if (_rType.text == "Gold/Silver/Dollar") {
        var map = {
          "edit_id": "",
          "receipt_date": _rDate.text,
          "receipt_type_id": selectedRTypeId,
          "member_id": selectedMemberId,
          "amount": _amount.text,
          "description": _description.text,
          "creator": creatorId,
        };

        result = await ReceiptFunctions.saveReceipt(
            query: map, type: ReceiptType.goldSilverDollar);
      } else if (_rType.text == "New Member Registration") {
        var map = {
          "edit_id": "",
          "receipt_date": _rDate.text,
          "receipt_type_id": selectedRTypeId,
          "member_id": selectedMemberId,
          "amount": _amount.text,
          "creator": creatorId,
        };

        result = await ReceiptFunctions.saveReceipt(
            query: map, type: ReceiptType.newMemberRegistration);
      } else if (_rType.text == "Old Thalakattu") {
        var map = {
          "edit_id": "",
          "receipt_date": _rDate.text,
          "receipt_type_id": selectedRTypeId,
          "member_id": selectedMemberId,
          "amount": _amount.text,
          "description": _description.text,
          "creator": creatorId,
        };

        result = await ReceiptFunctions.saveReceipt(
            query: map, type: ReceiptType.oldThalakattu);
      } else if (_rType.text == "New Thalakattu") {
        var map = {
          "edit_id": "",
          "receipt_date": _rDate.text,
          "receipt_type_id": selectedRTypeId,
          "member_id": selectedMemberId,
          "amount": _amount.text,
          "year_amount": _yearAmount.text,
          "poojai_from_date": _poojaiFromDate.text,
          "poojai_to_date": _poojaiToDate.text,
          "poojai_amount": _poojaiAmount.text,
          "creator": creatorId,
        };

        result = await ReceiptFunctions.saveReceipt(
            query: map, type: ReceiptType.newThalakattu);
      }
      Navigator.pop(context);
      Snackbar.showSnackBar(context,
          content: "Receipt created successfully", isSuccess: true);
      Navigator.pop(context, true);
      Navigator.push(context, CupertinoPageRoute(builder: (context) {
        return PdfView(
            uri: result["head"]["print_url"], name: "Receipt Preview");
      }));
    } catch (e) {
      Navigator.pop(context);
      Snackbar.showSnackBar(context, content: e.toString(), isSuccess: false);
    }
  }

  //***************** Utils *********************/
  _rDatePicker() async {
    final DateTime? picked = await datePicker(context);
    if (picked != null) {
      setState(() {
        _rDate.text = DateFormat('yyyy-MM-dd').format(picked);
      });
    }
    return null;
  }

  _fDatePicker() async {
    final DateTime? picked = await datePicker(context);
    if (picked != null) {
      setState(() {
        _functionDate.text = DateFormat('yyyy-MM-dd').format(picked);
      });
    }
  }

  //***************** Variables *********************/
  var formKey = GlobalKey<FormState>();

  final TextEditingController _rDate = TextEditingController();
  final TextEditingController _rType = TextEditingController();
  String? selectedRTypeId;
  final TextEditingController _member = TextEditingController();
  String? selectedMemberId;
  final TextEditingController _amount = TextEditingController();
  final TextEditingController _funeralTo = TextEditingController();
  final TextEditingController _nMemberName = TextEditingController();
  final TextEditingController _functionDate = TextEditingController();
  final TextEditingController _countForMudiKanikai = TextEditingController();
  final TextEditingController _countForKadhuKuthu = TextEditingController();
  final TextEditingController _description = TextEditingController();
  final TextEditingController _yearAmount = TextEditingController();
  final TextEditingController _poojaiFromDate = TextEditingController();
  final TextEditingController _poojaiToDate = TextEditingController();
  final TextEditingController _poojaiAmount = TextEditingController();

  List<String> visibleFeilds = [];
}
