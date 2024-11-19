// ignore_for_file: unused_field

import 'dart:io';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:intl/intl.dart';
import 'package:shimmer/shimmer.dart';

import '/constants/constants.dart';
import '/functions/functions.dart';
import '/utils/utils.dart';
import '/view/view.dart';

class ChildView extends StatefulWidget {
  final Map<String, TextEditingController> controllers;
  final String id;

  // final TextEditingController? childInitial;
  // final TextEditingController? childName;
  // final TextEditingController? childDob;
  // final TextEditingController? childEducation;
  // final TextEditingController? childRasi;
  // final TextEditingController? childNatchathiram;
  // final TextEditingController? childMobileNo;
  // final TextEditingController? childJob;
  final String? childMarrigeStatus;
  final String? childGender;
  final String? selectedChildRasi;
  final String? selectedChildNatchathiram;
  final String? selectedLifePartnerRasi;
  final String? selectedLifePartnerNatchathiram;
  // final TextEditingController? lifePatnerName;
  // final TextEditingController? lifePatnerEducation;
  // final TextEditingController? lifePatnerDob;
  // final TextEditingController? marriageDate;
  // final TextEditingController? lifePartnerRasi;
  // final TextEditingController? lifePartnerNatchathiram;
  final void Function()? indexRemove;
  final Map<String, dynamic> radioSelections;
  final Function(String, String) onRadioSelectionChanged;

  const ChildView({
    super.key,
    required this.id,
    required this.controllers,
    required this.radioSelections,
    this.selectedChildRasi,
    this.selectedChildNatchathiram,
    this.selectedLifePartnerRasi,
    this.selectedLifePartnerNatchathiram,
    this.childMarrigeStatus,
    this.childGender,
    this.indexRemove,
    required this.onRadioSelectionChanged,
  });

  @override
  State<ChildView> createState() => _ChildViewState();
}

class _ChildViewState extends State<ChildView> {
  _childDobPicker() async {
    final DateTime? picked = await datePicker(context);
    if (picked != null) {
      setState(() {
        widget.controllers["childDob"]!.text =
            DateFormat('dd/MM/yyyy').format(picked);
        _selectedDob = picked;
      });
    }
  }

  _lifePartnerDobPicker() async {
    final DateTime? picked = await datePicker(context);
    if (picked != null) {
      setState(() {
        widget.controllers["lifePartnerDob"]!.text =
            DateFormat('dd/MM/yyyy').format(picked);
        _selectedDob = picked;
      });
    }
  }

  _marrigeDatePicker() async {
    final DateTime? picked = await datePicker(context);
    if (picked != null) {
      setState(() {
        widget.controllers["marriageDate"]!.text =
            DateFormat('dd/MM/yyyy').format(picked);
        _selectedDob = picked;
      });
    }
  }

  _init() {
    widget.controllers["childRasi"]!.text =
        UtilsFunctions.getRasiTamilName(rasi: widget.selectedChildRasi ?? '');
    _selectedChildRasi = widget.selectedChildRasi;

    widget.controllers["childNatchathiram"]!.text =
        UtilsFunctions.getNatchathiramTamilName(
            natchathiram: widget.selectedChildNatchathiram ?? '');
    _selectedChildNatchathiram = widget.selectedChildNatchathiram;

    widget.controllers["lifePartnerRasi"]!.text =
        UtilsFunctions.getRasiTamilName(
            rasi: widget.selectedLifePartnerRasi ?? '');
    _selectedLifePatnerRasi = widget.selectedLifePartnerRasi;
    widget.controllers["lifePartnerNatchathiram"]!.text =
        UtilsFunctions.getNatchathiramTamilName(
            natchathiram: widget.selectedLifePartnerNatchathiram ?? '');

    _selectedLifePatnerNatchathiram = widget.selectedChildNatchathiram;
    _childGender = widget.childGender;
    _childMarrigeStatus = widget.childMarrigeStatus;

    _childGender = widget.radioSelections["${widget.id}_gender"];
    _childMarrigeStatus = widget.radioSelections["${widget.id}_marriageStatus"];
  }

  DateTime? _selectedDob;
  String? _selectedChildRasi;
  String? _selectedChildNatchathiram;
  DateTime? _selectedLifePartnerDob;
  DateTime? _selectedMarriageDate;
  String? _selectedLifePatnerRasi;
  String? _selectedLifePatnerNatchathiram;
  String? _childGender;
  String? _childMarrigeStatus;

  File? _selectedChildPhoto;

  @override
  void initState() {
    _init();
    super.initState();
  }

  void _updateGender(String? value) {
    setState(() {
      _childGender = value;
      widget.onRadioSelectionChanged("${widget.id}_gender", value!);
    });
  }

  void _updateMarriageStatus(String? value) {
    setState(() {
      _childMarrigeStatus = value;
      widget.onRadioSelectionChanged("${widget.id}_marriageStatus", value!);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Container(
          padding: const EdgeInsets.all(10),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(10),
            color: AppColors.pureWhiteColor,
          ),
          child: Column(
            children: [
              const SizedBox(height: 5),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    "Child Details",
                    style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                        color: AppColors.blackColor,
                        fontWeight: FontWeight.bold),
                  ),
                  IconButton(
                    padding: EdgeInsets.zero,
                    constraints: const BoxConstraints(),
                    style: const ButtonStyle(
                      tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                    ),
                    tooltip: "Remove Child",
                    icon: const Icon(
                      Iconsax.trash,
                      color: Colors.red,
                    ),
                    onPressed: () {
                      if (widget.indexRemove != null) {
                        widget.indexRemove!();
                      }
                    },
                  )
                ],
              ),
              const SizedBox(height: 10),
              Stack(
                children: [
                  ClipOval(
                    child: _selectedChildPhoto != null
                        ? Image.file(
                            _selectedChildPhoto!,
                            width: 150,
                            height: 150,
                          )
                        : CachedNetworkImage(
                            imageUrl: emptyProfilePhoto,
                            placeholder: (context, url) => Shimmer.fromColors(
                              baseColor: Colors.grey.shade300,
                              highlightColor: Colors.grey.shade100,
                              child: Container(
                                width: 150,
                                height: 150,
                                color: Colors.white,
                              ),
                            ),
                            fit: BoxFit.contain,
                            width: 150,
                            height: 150,
                            errorWidget: (context, url, error) =>
                                const Icon(Icons.error),
                          ),
                  ),
                  Positioned(
                    bottom: 0,
                    right: 0,
                    child: Container(
                      decoration: BoxDecoration(
                        color: AppColors.primaryColor,
                        shape: BoxShape.circle,
                      ),
                      child: IconButton(
                        tooltip: "Edit",
                        icon: const Icon(Icons.edit_rounded),
                        onPressed: () async {
                          var v = await Sheet.showSheet(context,
                              size: 0.2, widget: const PickOption());
                          if (v != null) {
                            var pr = await PickImage.pickImage(v);
                            if (pr != null) {
                              _selectedChildPhoto = pr;
                              setState(() {});
                            }
                          }
                        },
                        color: Colors.white,
                      ),
                    ),
                  )
                ],
              ),
              const SizedBox(
                height: 10,
              ),
              Row(
                children: [
                  Expanded(
                    flex: 1,
                    child: FormFields(
                      controller: widget.controllers["childInitial"]!,
                      label: "Intial",
                    ),
                  ),
                  const SizedBox(
                    width: 10,
                  ),
                  Expanded(
                    flex: 3,
                    child: FormFields(
                      controller: widget.controllers["childName"]!,
                      label: "Name",
                    ),
                  )
                ],
              ),
              const SizedBox(
                height: 10,
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "Gender",
                    style: Theme.of(context).textTheme.bodyMedium!.copyWith(
                        color: Colors.black, fontWeight: FontWeight.w300),
                  ),
                  Row(
                    children: [
                      Expanded(
                        child: RadioListTile<String>(
                          title: Text(
                            'Male',
                            style: Theme.of(context).textTheme.bodySmall,
                          ),
                          value: 'Male',
                          groupValue: _childGender,
                          activeColor: AppColors.primaryColor,
                          onChanged: _updateGender,
                          contentPadding: EdgeInsets.zero,
                        ),
                      ),
                      Expanded(
                        child: RadioListTile<String>(
                          title: Text(
                            'Female',
                            style: Theme.of(context).textTheme.bodySmall,
                          ),
                          value: 'Female',
                          activeColor: AppColors.primaryColor,
                          groupValue: _childGender,
                          onChanged: _updateGender,
                          contentPadding: EdgeInsets.zero,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
              const SizedBox(
                height: 10,
              ),
              Row(
                children: [
                  Expanded(
                    child: FormFields(
                      controller: widget.controllers["childDob"]!,
                      label: "Date of Birth",
                      hintText: "dd-mm-yyyy",
                      onTap: () => _childDobPicker(),
                      readOnly: true,
                    ),
                  ),
                  const SizedBox(
                    width: 10,
                  ),
                  Expanded(
                    child: FormFields(
                      controller: widget.controllers["childMobileNumber"]!,
                      label: "Mobile No",
                    ),
                  ),
                ],
              ),
              const SizedBox(
                height: 10,
              ),
              Row(
                children: [
                  Expanded(
                    child: FormFields(
                      suffixIcon: widget.controllers["childRasi"]!.text.isEmpty
                          ? const Icon(Icons.arrow_drop_down_rounded)
                          : IconButton(
                              tooltip: "Clear",
                              onPressed: () {
                                widget.controllers["childRasi"]!.clear();
                                _selectedChildRasi = null;
                                widget.controllers["childNatchathiram"]!
                                    .clear();
                                setState(() {});
                              },
                              icon: Icon(
                                Iconsax.close_circle,
                                color: AppColors.primaryColor,
                              ),
                            ),
                      controller: widget.controllers["childRasi"]!,
                      label: "Rasi",
                      hintText: "Select",
                      onTap: () async {
                        var value = await Sheet.showSheet(context,
                            size: 0.9, widget: const Rasi());
                        if (value != null) {
                          widget.controllers["childRasi"]!.text =
                              value["value"];
                          _selectedChildRasi = value["id"];
                        }
                      },
                      readOnly: true,
                      // valid: (input) {
                      //   if (input != null) {
                      //     if (input.isEmpty) {
                      //       return 'Select site';
                      //     }
                      //   }
                      //   return null;
                      // },
                    ),
                  ),
                  const SizedBox(
                    width: 10,
                  ),
                  Expanded(
                    child: FormFields(
                      suffixIcon:
                          widget.controllers["childNatchathiram"]!.text.isEmpty
                              ? const Icon(Icons.arrow_drop_down_rounded)
                              : IconButton(
                                  tooltip: "Clear",
                                  onPressed: () {
                                    widget.controllers["childNatchathiram"]!
                                        .clear();
                                    _selectedChildNatchathiram = null;
                                    setState(() {});
                                  },
                                  icon: Icon(
                                    Iconsax.close_circle,
                                    color: AppColors.primaryColor,
                                  ),
                                ),
                      controller: widget.controllers["childNatchathiram"]!,
                      label: "Natchathiram",
                      hintText: "Select",
                      onTap: () async {
                        if (widget.controllers["childRasi"]!.text.isNotEmpty) {
                          var value = await Sheet.showSheet(context,
                              size: 0.7,
                              widget: Natchathiram(
                                  rasi: widget.controllers["childRasi"]!.text));
                          if (value != null) {
                            widget.controllers["childNatchathiram"]!.text =
                                value["value"];
                            _selectedChildNatchathiram = value["id"];
                          }
                        }
                      },
                      // valid: (input) {
                      //   if (input != null) {
                      //     if (input.isEmpty) {
                      //       return 'Select';
                      //     }
                      //   }
                      //   return null;
                      // },
                      readOnly: true,
                    ),
                  ),
                ],
              ),
              const SizedBox(
                height: 10,
              ),
              Row(
                children: [
                  Expanded(
                    child: FormFields(
                      controller: widget.controllers["childEducation"]!,
                      label: "Education",
                    ),
                  ),
                  const SizedBox(
                    width: 10,
                  ),
                  Expanded(
                    child: FormFields(
                      controller: widget.controllers["childJob"]!,
                      label: "Job",
                    ),
                  ),
                ],
              ),
              const SizedBox(
                height: 10,
              ),
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "Marrige Status",
                    style: Theme.of(context).textTheme.bodyMedium!.copyWith(
                        color: Colors.black, fontWeight: FontWeight.w300),
                  ),
                  Row(
                    children: [
                      Expanded(
                        child: RadioListTile<String>(
                          title: Text(
                            'Yes',
                            style: Theme.of(context).textTheme.bodySmall,
                          ),
                          value: '1',
                          groupValue: _childMarrigeStatus,
                          activeColor: AppColors.primaryColor,
                          onChanged: _updateMarriageStatus,
                          contentPadding: EdgeInsets.zero,
                        ),
                      ),
                      Expanded(
                        child: RadioListTile<String>(
                          title: Text(
                            'No',
                            style: Theme.of(context).textTheme.bodySmall,
                          ),
                          value: '2',
                          contentPadding: EdgeInsets.zero,
                          activeColor: AppColors.primaryColor,
                          groupValue: _childMarrigeStatus,
                          onChanged: _updateMarriageStatus,
                        ),
                      ),
                    ],
                  ),
                ],
              ),
              const SizedBox(height: 10),
              if (_childMarrigeStatus != null && _childMarrigeStatus == "1")
                Column(
                  children: [
                    const Divider(),
                    const SizedBox(height: 5),
                    Text(
                      "Life Partner Details",
                      style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                          color: AppColors.blackColor,
                          fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 10),
                    Row(
                      children: [
                        Expanded(
                          child: FormFields(
                            controller: widget.controllers["lifePartnerName"]!,
                            label: "Name",
                          ),
                        ),
                        const SizedBox(
                          width: 10,
                        ),
                        Expanded(
                          child: FormFields(
                            controller:
                                widget.controllers["lifePatnerEducation"]!,
                            label: "Education",
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                    Row(
                      children: [
                        Expanded(
                          child: FormFields(
                            controller: widget.controllers["lifePatnerDob"]!,
                            label: "Date of Birth",
                            hintText: "dd-mm-yyyy",
                            onTap: () => _lifePartnerDobPicker(),
                            readOnly: true,
                          ),
                        ),
                        const SizedBox(
                          width: 10,
                        ),
                        Expanded(
                          child: FormFields(
                            controller: widget.controllers["marriageDate"]!,
                            label: "Marriage Date",
                            hintText: "dd-mm-yyyy",
                            onTap: () => _marrigeDatePicker(),
                            readOnly: true,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                    Row(
                      children: [
                        Expanded(
                          child: FormFields(
                            suffixIcon: widget.controllers["lifePartnerRasi"]!
                                    .text.isEmpty
                                ? const Icon(Icons.arrow_drop_down_rounded)
                                : IconButton(
                                    tooltip: "Clear",
                                    onPressed: () {
                                      widget.controllers["lifePartnerRasi"]!
                                          .clear();
                                      _selectedLifePatnerRasi = null;
                                      widget.controllers[
                                              "lifePartnerNatchathiram"]!
                                          .clear();
                                      setState(() {});
                                    },
                                    icon: Icon(
                                      Iconsax.close_circle,
                                      color: AppColors.primaryColor,
                                    ),
                                  ),
                            controller: widget.controllers["lifePartnerRasi"]!,
                            label: "Rasi",
                            hintText: "Select",
                            onTap: () async {
                              var value = await Sheet.showSheet(context,
                                  size: 0.9, widget: const Rasi());
                              if (value != null) {
                                widget.controllers["lifePartnerRasi"]!.text =
                                    value["value"];
                                _selectedLifePatnerRasi = value["id"];
                              }
                            },
                            readOnly: true,
                            // valid: (input) {
                            //   if (input != null) {
                            //     if (input.isEmpty) {
                            //       return 'Select site';
                            //     }
                            //   }
                            //   return null;
                            // },
                          ),
                        ),
                        const SizedBox(
                          width: 10,
                        ),
                        Expanded(
                          child: FormFields(
                            suffixIcon: widget
                                    .controllers["lifePartnerNatchathiram"]!
                                    .text
                                    .isEmpty
                                ? const Icon(Icons.arrow_drop_down_rounded)
                                : IconButton(
                                    tooltip: "Clear",
                                    onPressed: () {
                                      widget.controllers[
                                              "lifePartnerNatchathiram"]!
                                          .clear();
                                      _selectedLifePatnerNatchathiram = null;
                                      setState(() {});
                                    },
                                    icon: Icon(
                                      Iconsax.close_circle,
                                      color: AppColors.primaryColor,
                                    ),
                                  ),
                            controller:
                                widget.controllers["lifePartnerNatchathiram"]!,
                            label: "Natchathiram",
                            hintText: "Select",
                            onTap: () async {
                              if (widget.controllers["lifePartnerRasi"]!.text
                                  .isNotEmpty) {
                                var value = await Sheet.showSheet(context,
                                    size: 0.7,
                                    widget: Natchathiram(
                                        rasi: widget
                                            .controllers["lifePartnerRasi"]!
                                            .text));
                                if (value != null) {
                                  widget.controllers["lifePartnerNatchathiram"]!
                                      .text = value["value"];
                                  _selectedLifePatnerNatchathiram =
                                      value["code"];
                                }
                              }
                            },
                            // valid: (input) {
                            //   if (input != null) {
                            //     if (input.isEmpty) {
                            //       return 'Select';
                            //     }
                            //   }
                            //   return null;
                            // },
                            readOnly: true,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                  ],
                ),
              const Divider(),
            ],
          ),
        ),
        const SizedBox(height: 10)
      ],
    );
  }
}
