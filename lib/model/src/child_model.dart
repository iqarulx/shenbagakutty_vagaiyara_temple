import 'package:flutter/material.dart';

class ChildModel {
  String? id;
  TextEditingController childNameController;
  TextEditingController childEducationController;
  TextEditingController childJobController;
  TextEditingController childInitialController;
  TextEditingController childDobController;
  TextEditingController childRasiController;
  TextEditingController childNatchathiramController;
  TextEditingController childMobileNumberController;
  TextEditingController lifePartnerNameController;
  TextEditingController lifePatnerEducationController;
  TextEditingController marriageDateController;
  TextEditingController lifePartnerRasiController;
  TextEditingController lifePartnerNatchathiramController;
  TextEditingController lifePartnerDobController;
  String? childGender;
  String? childMarrigeStatus;
  String? selectedChildJob;
  String? selectedChildRasi;
  String? selectedChildNatchathiram;
  String? selectedLifePartnerRasi;
  String? selectedLifePartnerNatchathiram;
  bool profilePhotoEdit;

  ChildModel({
    required this.id,
    required this.childNameController,
    required this.childEducationController,
    required this.childJobController,
    required this.childInitialController,
    required this.childDobController,
    required this.childRasiController,
    required this.childNatchathiramController,
    required this.childMobileNumberController,
    required this.lifePartnerNameController,
    required this.lifePatnerEducationController,
    required this.marriageDateController,
    required this.lifePartnerRasiController,
    required this.lifePartnerNatchathiramController,
    required this.childGender,
    required this.childMarrigeStatus,
    required this.selectedChildJob,
    required this.selectedChildRasi,
    required this.selectedChildNatchathiram,
    required this.selectedLifePartnerRasi,
    required this.selectedLifePartnerNatchathiram,
    required this.lifePartnerDobController,
    required this.profilePhotoEdit,
  });
}
