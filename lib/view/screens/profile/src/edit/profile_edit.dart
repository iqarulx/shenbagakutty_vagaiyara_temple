// ignore_for_file: unused_field

import 'dart:io';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:iconsax/iconsax.dart';
import 'package:shimmer/shimmer.dart';
import '/constants/constants.dart';
import '/functions/functions.dart';
import '/services/services.dart';
import '/utils/utils.dart';
import '/view/view.dart';

class ProfileEdit extends StatefulWidget {
  final Map<String, dynamic> profileData; // Profile Data
  const ProfileEdit({super.key, required this.profileData});

  @override
  State<ProfileEdit> createState() => _ProfileEditState();
}

class _ProfileEditState extends State<ProfileEdit>
    with TickerProviderStateMixin {
  final TextEditingController _memberName = TextEditingController();
  final TextEditingController _initial = TextEditingController();
  final TextEditingController _rasi = TextEditingController();
  String? _selectedRasi;
  final TextEditingController _natchathiram = TextEditingController();
  String? _selectedNatchathiram;
  final TextEditingController _profession = TextEditingController();
  String? _selectedProfessionId;
  final TextEditingController _wifeName = TextEditingController();
  final TextEditingController _wifeEducation = TextEditingController();
  final TextEditingController _wifeRasi = TextEditingController();
  String? _selectedWifeRasi;
  final TextEditingController _wifeNatchathiram = TextEditingController();
  String? _selectedWifeNatchathiram;
  final TextEditingController _fatherId = TextEditingController();
  final TextEditingController _fatherName = TextEditingController();
  final TextEditingController _familyOrder = TextEditingController();
  final TextEditingController _introducerId = TextEditingController();
  final TextEditingController _introducerRelationship = TextEditingController();
  final TextEditingController _status = TextEditingController();
  final TextEditingController _phoneNumber = TextEditingController();
  final TextEditingController _mobileNumber = TextEditingController();
  final TextEditingController _adhaarNumber = TextEditingController();
  final TextEditingController _country = TextEditingController();
  final TextEditingController _state = TextEditingController();
  final TextEditingController _city = TextEditingController();
  final TextEditingController _address = TextEditingController();
  final TextEditingController _pincode = TextEditingController();
  final TextEditingController _companyName = TextEditingController();
  final TextEditingController _remarks = TextEditingController();
  final TextEditingController _history = TextEditingController();

  String? _selectedCountryIndex;
  String _doj = ""; // Date of joining
  String _dod = ""; // Date of deletion
  String _dor = ""; // Date of rejoin
  String? _profilePhoto;
  String? _wifePhoto;
  List<String>? _familyPhoto;

  final List<ChildView> _childList = [];

  File? _selectedProfilePhoto;
  File? _selectedWifePhoto;
  File? _selectedFamilyPhoto1;
  File? _selectedFamilyPhoto2;
  File? _selectedFamilyPhoto3;

  @override
  void initState() {
    _tabController = TabController(length: 4, vsync: this);
    _tabController?.addListener(() {
      if (_tabController?.indexIsChanging == false) {
        setState(() {});
      }
    });

    _init();

    _addListeners([
      _memberName,
      _initial,
      _rasi,
      _natchathiram,
      _wifeName,
      _profession,
      _wifeEducation,
      _wifeRasi,
      _wifeNatchathiram,
      _country,
      _state,
      _city
    ]);
    super.initState();
  }

  @override
  void dispose() {
    for (var controller in [
      _memberName,
      _initial,
      _rasi,
      _natchathiram,
      _profession,
      _wifeName,
      _wifeEducation,
      _wifeRasi,
      _wifeNatchathiram,
      _country,
      _state,
      _city
    ]) {
      controller.dispose();
    }

    super.dispose();
  }

  void _addListeners(List<TextEditingController> controllers) {
    for (var controller in controllers) {
      controller.addListener(() {
        setState(() {});
      });
    }
  }

  _init() async {
    var data = widget.profileData;
    _memberName.text = data["initial"].toString();
    _initial.text = data["name"].toString();
    _rasi.text = UtilsFunctions.getRasiTamilName(rasi: data["rasi"].toString());
    _selectedRasi = data["rasi"].toString();
    _natchathiram.text = UtilsFunctions.getNatchathiramTamilName(
        natchathiram: data["natchathiram"].toString());
    _selectedNatchathiram = data["natchathiram"].toString();
    _wifeName.text = data["wife_name"].toString();
    _wifeEducation.text = data["wife_education"].toString();
    _profession.text = data["profession_name"].toString();
    _selectedProfessionId = data["profession_id"].toString();
    _wifeRasi.text =
        UtilsFunctions.getRasiTamilName(rasi: data["wife_rasi"].toString());
    _selectedWifeRasi = data["wife_rasi"].toString();
    _wifeNatchathiram.text = UtilsFunctions.getNatchathiramTamilName(
        natchathiram: data["wife_natchathiram"].toString());
    _selectedWifeNatchathiram = data["wife_natchathiram"].toString();
    _fatherId.text = data["father_id"].toString();
    _fatherName.text = data["father_name"].toString();
    _familyOrder.text = data["family_order"].toString();
    _introducerId.text = data["introducer_id"].toString();
    _introducerRelationship.text = data["introducer_relationship"].toString();
    _status.text = data["status_name"].toString();
    _phoneNumber.text = data["phone_number"].toString();
    _mobileNumber.text = data["mobile_number"].toString();
    _adhaarNumber.text = data["aadhaar_number"].toString();
    _doj = data["date_of_joining"].toString();
    _dod = data["date_of_deletion"].toString();
    _dor = data["date_of_rejoin"].toString();
    _country.text = data["country"].toString().split(',').last;
    _selectedCountryIndex = data["country_index"].toString();
    _state.text = data["state"].toString();
    _city.text = data["city"].toString();
    _address.text = data["address"].toString();
    _pincode.text = data["pincode"].toString();
    _companyName.text = data["company_name"].toString();
    _remarks.text = data["remarks"].toString();
    _profilePhoto = data["member_profile_photos"].isNotEmpty
        ? data["member_profile_photos"]
        : null;
    _wifePhoto = data["member_wife_photos"].isNotEmpty
        ? data["member_wife_photos"]
        : null;
    _familyPhoto = data["member_family_photos"].isNotEmpty
        ? (data["member_family_photos"] as List)
            .map((e) => e.toString())
            .toList()
        : null;

    for (var d = 0; d < data["children"].length; d++) {
      var i = data["children"][d];
      var controllerMap = {
        "childName":
            TextEditingController(text: i["member_child_name"].toString()),
        "childEducation":
            TextEditingController(text: i["member_child_education"].toString()),
        "childJob":
            TextEditingController(text: i["member_child_job"].toString()),
        "childInitial":
            TextEditingController(text: i["member_child_initial"].toString()),
        "childDob": TextEditingController(
            text: i["member_child_birth_date"].toString()),
        "childRasi": TextEditingController(),
        "childNatchathiram": TextEditingController(),
        "childMobileNumber": TextEditingController(
            text: i["member_child_mobile_number"].toString()),
        "lifePartnerName": TextEditingController(
            text: i["member_child_partner_name"].toString()),
        "lifePatnerEducation": TextEditingController(
            text: i["member_child_partner_education"].toString()),
        "lifePatnerDob": TextEditingController(
            text: i["member_child_partner_birth_date"].toString()),
        "marriageDate": TextEditingController(
            text: i["member_child_marriage_date"].toString()),
        "lifePartnerRasi": TextEditingController(),
        "lifePartnerNatchathiram": TextEditingController(),
      };
      _childControllers.add(controllerMap);
      String id = i["member_child_id"];
      radioSelections["${id}_gender"] = i["member_child_gender"].toString();
      radioSelections["${id}_marriageStatus"] =
          i["member_child_marriage_status"].toString();

      _childList.add(
        ChildView(
          id: id,
          controllers: controllerMap,
          childMarrigeStatus: i["member_child_marriage_status"].toString(),
          childGender: i["member_child_gender"].toString(),
          selectedChildRasi: i["member_child_rasi"].toString(),
          selectedChildNatchathiram: i["member_child_natchathiram"].toString(),
          selectedLifePartnerRasi: i["member_child_partner_rasi"].toString(),
          selectedLifePartnerNatchathiram:
              i["member_child_partner_natchathiram"].toString(),
          radioSelections: radioSelections,
          indexRemove: () {
            setState(() {
              // _childControllers.remove(controllerMap);
              // _childList.removeWhere((child) => child == controllerMap);
            });
          },
          onRadioSelectionChanged: (key, value) {
            radioSelections[key] = value;
            setState(() {});
          },
        ),
      );
    }
  }

  TabController? _tabController;
  final List<Map<String, TextEditingController>> _childControllers = [];
  final Map<String, dynamic> radioSelections = {};

  _saveProfile() async {
    try {
      futureLoading(context);

      var memberId = await Db.getData(type: UserData.memberId);
      var memberName = await Db.getData(type: UserData.memberName);
      Map<String, dynamic> updateData = {};

      if (_tabController?.index == 0) {
        updateData = {
          "type": "personal",
          "update_member_id": memberId,
          "date_of_birth": "",
          "rasi": _selectedRasi,
          "natchathiram": _selectedNatchathiram,
          "profession_id": _selectedProfessionId,
          "wife_name": _wifeName.text,
          "wife_education": _wifeEducation.text,
          "wife_date_of_birth": "",
          "wife_rasi": _selectedWifeRasi,
          "wife_natchathiram": _selectedWifeNatchathiram,
          "marriage_date": "",
          "phone_number": _phoneNumber.text,
          "mobile_number": _mobileNumber.text,
          "aadhaar_number": _adhaarNumber.text,
          "creator": memberId,
          "creator_name": memberName,
        };
      } else if (_tabController?.index == 1) {
        updateData = {
          "type": "address",
          "update_member_id": memberId,
          "address": _address.text,
          "city": _city.text,
          "pincode": _pincode.text,
          "state": _state.text,
          "country": _country.text,
          "company_name": _companyName.text,
          "remarks": _remarks.text,
          "member_history": _history.text,
          "creator": memberId,
          "creator_name": memberName,
        };
      } else if (_tabController?.index == 3) {
        updateData = {
          "type": "child",
          "update_member_id": memberId,
          "children": [],
          "creator": memberId,
          "creator_name": memberName,
        };
        List<Map<String, dynamic>> childList = [];

        for (var i = 0; i < _childControllers.length; i++) {
          var childDetails = {
            "child1_id": _childList[i].id,
            "child1_initial": _childControllers[i]["childInitial"]?.text,
            "child1_name": _childControllers[i]["childName"]?.text,
            "child1_gender": radioSelections["${_childList[i].id}_gender"],
            "child1_dob": _childControllers[i]["childDob"]?.text,
            "child1_rasi": UtilsFunctions.getRasiEnglishName(
                rasi: _childControllers[i]["childRasi"]?.text ?? ''),
            "child1_natchathiram": UtilsFunctions.getEnglishNatchathiramName(
                natchathiram:
                    _childControllers[i]["childNatchathiram"]?.text ?? ''),
            "child1_education": _childControllers[i]["childEducation"]?.text,
            "child1_job": "",
            "child1_marital_status":
                radioSelections["${_childList[i].id}_marriageStatus"],
            "child1_mobile_number":
                _childControllers[i]["childMobileNumber"]?.text,
            "child1_photo": ""
          };
          if (radioSelections["${_childList[i].id}_gender"] == 1) {
            childDetails["child1_wife_name"] =
                _childControllers[i]["lifePartnerName"]?.text;
            childDetails["child1_wife_education"] =
                _childControllers[i]["lifePatnerEducation"]?.text;
            childDetails["child1_wife_rasi"] =
                UtilsFunctions.getRasiEnglishName(
                    rasi: _childControllers[i]["lifePartnerRasi"]?.text ?? '');
            childDetails["child1_wife_natchathiram"] =
                UtilsFunctions.getEnglishNatchathiramName(
              natchathiram:
                  _childControllers[i]["lifePartnerNatchathiram"]?.text ?? '',
            );
            childDetails["child1_husband_name"] = "";
            childDetails["child1_husband_education"] = "";
            childDetails["child1_husband_rasi"] = "";
            childDetails["child1_husband_natchathiram"] = "";
          } else {
            childDetails["child1_wife_name"] = "";
            childDetails["child1_wife_education"] = "";
            childDetails["child1_wife_rasi"] = "";
            childDetails["child1_wife_natchathiram"] = "";
            childDetails["child1_husband_name"] =
                _childControllers[i]["lifePartnerName"]?.text;
            childDetails["child1_husband_education"] =
                _childControllers[i]["lifePatnerEducation"]?.text;
            childDetails["child1_husband_rasi"] =
                UtilsFunctions.getRasiEnglishName(
                    rasi: _childControllers[i]["lifePartnerRasi"]?.text ?? '');
            childDetails["child1_husband_natchathiram"] =
                UtilsFunctions.getEnglishNatchathiramName(
              natchathiram:
                  _childControllers[i]["lifePartnerNatchathiram"]?.text ?? '',
            );
          }

          childList.add(childDetails);
        }

        updateData["children"] = childList;
      }

      await ProfileFunctions.updateProfile(query: updateData).then((value) {
        Navigator.pop(context);
        Snackbar.showSnackBar(context,
            content: "Updated Successfully", isSuccess: true);
      });
    } catch (e) {
      Navigator.pop(context);
      Snackbar.showSnackBar(context, content: e.toString(), isSuccess: false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: _appbar(context),
      floatingActionButton: _floatingButton(),
      body: TabBarView(
        controller: _tabController,
        children: [
          _profileDetails(context),
          _addressForm(),
          _memberImages(),
          _childView(),
        ],
      ),
    );
  }

  ListView _childView() {
    return ListView(
      padding: const EdgeInsets.all(10),
      children: [for (var i in _childList) i],
    );
  }

  ListView _memberImages() {
    return ListView(
      padding: const EdgeInsets.all(10),
      children: [
        Container(
          padding: const EdgeInsets.all(10),
          decoration: BoxDecoration(
            borderRadius: BorderRadius.circular(10),
            color: AppColors.pureWhiteColor,
          ),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              const SizedBox(height: 10),
              Text(
                "Profile Photo",
                style: Theme.of(context).textTheme.bodyLarge,
              ),
              const SizedBox(height: 10),
              Stack(
                children: [
                  ClipOval(
                    child: _selectedProfilePhoto != null
                        ? Image.file(
                            _selectedProfilePhoto!,
                            width: 150,
                            height: 150,
                          )
                        : CachedNetworkImage(
                            imageUrl: _profilePhoto ?? emptyProfilePhoto,
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
                              _selectedProfilePhoto = pr;
                              await ImageFunctions.uploadImage(context,
                                  type: ImageType.profile, file: pr);

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
              const SizedBox(height: 10),
              const Divider(),
              const SizedBox(height: 10),
              const SizedBox(height: 10),
              Text(
                "Wife Photo",
                style: Theme.of(context).textTheme.bodyLarge,
              ),
              const SizedBox(height: 10),
              Stack(children: [
                ClipOval(
                  child: _selectedWifePhoto != null
                      ? Image.file(
                          _selectedWifePhoto!,
                          width: 150,
                          height: 150,
                        )
                      : CachedNetworkImage(
                          imageUrl: _wifePhoto ?? emptyProfilePhoto,
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
                            _selectedWifePhoto = pr;
                            await ImageFunctions.uploadImage(context,
                                file: pr, type: ImageType.wife);
                            setState(() {});
                          }
                        }
                      },
                      color: Colors.white,
                    ),
                  ),
                )
              ]),
              const SizedBox(height: 10),
              const Divider(),
              const SizedBox(height: 10),
              const SizedBox(height: 10),
              Text(
                "Family Photo",
                style: Theme.of(context).textTheme.bodyLarge,
              ),
              const SizedBox(height: 10),
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Stack(
                    children: [
                      ClipOval(
                        child: _selectedFamilyPhoto1 != null
                            ? Image.file(
                                _selectedFamilyPhoto1!,
                                width: 150,
                                height: 150,
                              )
                            : CachedNetworkImage(
                                imageUrl: _familyPhoto?[0] ?? emptyProfilePhoto,
                                placeholder: (context, url) =>
                                    Shimmer.fromColors(
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
                                  _selectedFamilyPhoto1 = pr;

                                  await ImageFunctions.uploadImage(
                                    context,
                                    file: pr,
                                    type: ImageType.family,
                                    prefix: "1",
                                  );
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
                  Stack(
                    children: [
                      ClipOval(
                        child: _selectedFamilyPhoto2 != null
                            ? Image.file(
                                _selectedFamilyPhoto2!,
                                width: 150,
                                height: 150,
                              )
                            : CachedNetworkImage(
                                imageUrl: _familyPhoto?[1] ?? emptyProfilePhoto,
                                placeholder: (context, url) =>
                                    Shimmer.fromColors(
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
                                  _selectedFamilyPhoto2 = pr;
                                  await ImageFunctions.uploadImage(
                                    context,
                                    file: pr,
                                    type: ImageType.family,
                                    prefix: "2",
                                  );
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
                ],
              ),
              const SizedBox(height: 10),
              Stack(
                children: [
                  ClipOval(
                    child: _selectedFamilyPhoto3 != null
                        ? Image.file(
                            _selectedFamilyPhoto3!,
                            width: 150,
                            height: 150,
                          )
                        : CachedNetworkImage(
                            imageUrl: _familyPhoto?[2] ?? emptyProfilePhoto,
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
                              _selectedFamilyPhoto3 = pr;
                              await ImageFunctions.uploadImage(
                                context,
                                file: pr,
                                type: ImageType.family,
                                prefix: "3",
                              );
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
              const SizedBox(height: 10),
              const Divider(),
              const SizedBox(height: 10),
            ],
          ),
        )
      ],
    );
  }

  ListView _addressForm() {
    return ListView(
      padding: const EdgeInsets.all(10),
      children: [
        FormFields(
          suffixIcon: _country.text.isEmpty
              ? const Icon(Icons.arrow_drop_down_rounded)
              : IconButton(
                  tooltip: "Clear",
                  onPressed: () {
                    _country.clear();
                    _selectedCountryIndex = null;
                    setState(() {});
                  },
                  icon: Icon(
                    Iconsax.close_circle,
                    color: AppColors.primaryColor,
                  ),
                ),
          controller: _country,
          label: "Country (*)",
          hintText: "Select",
          onTap: () async {
            var value = await Sheet.showSheet(context,
                size: 0.9, widget: const Country());
            if (value != null) {
              _country.text = value["name"];
              _selectedCountryIndex = value["index"].toString();
            }
          },
          readOnly: true,
        ),
        const SizedBox(height: 10),
        FormFields(
          suffixIcon: _state.text.isEmpty
              ? const Icon(Icons.arrow_drop_down_rounded)
              : IconButton(
                  tooltip: "Clear",
                  onPressed: () {
                    _state.clear();
                    setState(() {});
                  },
                  icon: Icon(
                    Iconsax.close_circle,
                    color: AppColors.primaryColor,
                  ),
                ),
          controller: _state,
          label: "State (*)",
          hintText: "Select",
          onTap: () async {
            if (_selectedCountryIndex != null) {
              var value = await Sheet.showSheet(context,
                  size: 0.9,
                  widget: States(
                    index: _selectedCountryIndex ?? '',
                  ));
              if (value != null) {
                _state.text = value;
              }
            }
          },
          readOnly: true,
        ),
        const SizedBox(height: 10),
        Row(
          children: [
            Expanded(
              child: FormFields(
                suffixIcon: _city.text.isEmpty
                    ? const Icon(Icons.arrow_drop_down_rounded)
                    : IconButton(
                        tooltip: "Clear",
                        onPressed: () {
                          _city.clear();
                          setState(() {});
                        },
                        icon: Icon(
                          Iconsax.close_circle,
                          color: AppColors.primaryColor,
                        ),
                      ),
                controller: _city,
                label: "City (*)",
                hintText: "Select",
                onTap: () async {
                  if (_selectedCountryIndex != null && _state.text.isNotEmpty) {
                    var value = await Sheet.showSheet(context,
                        size: 0.9, widget: const City());
                    if (value != null) {
                      _city.text = value;
                    }
                  }
                },
                readOnly: true,
              ),
            ),
            const SizedBox(
              width: 10,
            ),
            Expanded(
              child: FormFields(
                controller: _pincode,
                label: "Pincode",
              ),
            )
          ],
        ),
        const SizedBox(height: 10),
        FormFields(
          controller: _address,
          label: "Address",
          maxLines: 3,
        ),
        const SizedBox(height: 10),
        FormFields(
          controller: _companyName,
          label: "Company Name",
        ),
        const SizedBox(height: 10),
        FormFields(
          controller: _remarks,
          label: "Remarks",
          maxLines: 3,
        ),
        const SizedBox(height: 10),
        FormFields(
          controller: _history,
          label: "History",
          maxLines: 3,
        ),
      ],
    );
  }

  ListView _profileDetails(BuildContext context) {
    return ListView(
      padding: const EdgeInsets.all(10),
      children: [
        Row(
          children: [
            Expanded(
              child: FormFields(
                controller: _initial,
                enabled: false,
                label: "Initial",
              ),
            ),
            const SizedBox(
              width: 10,
            ),
            Expanded(
              child: FormFields(
                controller: _memberName,
                enabled: false,
                label: "Member Name",
              ),
            ),
          ],
        ),
        const SizedBox(height: 10),
        Row(
          children: [
            Expanded(
              child: FormFields(
                suffixIcon: _rasi.text.isEmpty
                    ? const Icon(Icons.arrow_drop_down_rounded)
                    : IconButton(
                        tooltip: "Clear",
                        onPressed: () {
                          _rasi.clear();
                          _selectedRasi = null;
                          _natchathiram.clear();
                          setState(() {});
                        },
                        icon: Icon(
                          Iconsax.close_circle,
                          color: AppColors.primaryColor,
                        ),
                      ),
                controller: _rasi,
                label: "Rasi",
                hintText: "Select",
                onTap: () async {
                  var value = await Sheet.showSheet(context,
                      size: 0.9, widget: const Rasi());
                  if (value != null) {
                    _rasi.text = value["value"];
                    _selectedRasi = value["id"];
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
              width: 5,
            ),
            Expanded(
              child: FormFields(
                suffixIcon: _natchathiram.text.isEmpty
                    ? const Icon(Icons.arrow_drop_down_rounded)
                    : IconButton(
                        tooltip: "Clear",
                        onPressed: () {
                          _selectedNatchathiram = null;
                          _natchathiram.clear();
                          setState(() {});
                        },
                        icon: Icon(
                          Iconsax.close_circle,
                          color: AppColors.primaryColor,
                        ),
                      ),
                controller: _natchathiram,
                label: "Natchathiram",
                hintText: "Select",
                onTap: () async {
                  if (_rasi.text.isNotEmpty) {
                    var value = await Sheet.showSheet(context,
                        size: 0.7, widget: Natchathiram(rasi: _rasi.text));
                    if (value != null) {
                      _natchathiram.text = value["value"];
                      _selectedNatchathiram = value["id"];
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
        FormFields(
          suffixIcon: _profession.text.isEmpty
              ? const Icon(Icons.arrow_drop_down_rounded)
              : IconButton(
                  tooltip: "Clear",
                  onPressed: () {
                    _profession.clear();
                    _selectedProfessionId = null;
                    setState(() {});
                  },
                  icon: Icon(
                    Iconsax.close_circle,
                    color: AppColors.primaryColor,
                  ),
                ),
          controller: _profession,
          label: "Profession",
          hintText: "Select",
          onTap: () async {
            var value = await Sheet.showSheet(context,
                size: 0.9,
                widget: Profession(profData: widget.profileData["profession"]));
            if (value != null) {
              _profession.text = value["code"];
              _selectedProfessionId = value["id"];
            }
          },
          readOnly: true,
        ),
        const SizedBox(height: 10),
        Row(
          children: [
            Expanded(
              child: FormFields(
                controller: _wifeName,
                label: "Wife Name",
              ),
            ),
            const SizedBox(
              width: 10,
            ),
            Expanded(
              child: FormFields(
                controller: _wifeEducation,
                label: "Wife Education",
              ),
            ),
          ],
        ),
        const SizedBox(height: 10),
        Row(
          children: [
            Expanded(
              child: FormFields(
                controller: _phoneNumber,
                label: "Phone Number",
              ),
            ),
            const SizedBox(
              width: 10,
            ),
            Expanded(
              child: FormFields(
                controller: _mobileNumber,
                label: "Mobile Number",
              ),
            ),
          ],
        ),
        const SizedBox(height: 10),
        FormFields(
          controller: _profession,
          label: "Adhaar Number",
        ),
        const SizedBox(height: 10),
        Row(
          children: [
            Expanded(
              child: FormFields(
                suffixIcon: _wifeRasi.text.isEmpty
                    ? const Icon(Icons.arrow_drop_down_rounded)
                    : IconButton(
                        tooltip: "Clear",
                        onPressed: () {
                          _wifeRasi.clear();
                          _selectedWifeRasi = null;
                          _wifeNatchathiram.clear();
                          setState(() {});
                        },
                        icon: Icon(
                          Iconsax.close_circle,
                          color: AppColors.primaryColor,
                        ),
                      ),
                controller: _wifeRasi,
                label: "Wife Rasi",
                hintText: "Select",
                onTap: () async {
                  var value = await Sheet.showSheet(context,
                      size: 0.9, widget: const Rasi());
                  if (value != null) {
                    _wifeRasi.text = value["value"];
                    _selectedWifeRasi = value["id"];
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
              width: 5,
            ),
            Expanded(
              child: FormFields(
                suffixIcon: _wifeNatchathiram.text.isEmpty
                    ? const Icon(Icons.arrow_drop_down_rounded)
                    : IconButton(
                        tooltip: "Clear",
                        onPressed: () {
                          _wifeNatchathiram.clear();
                          _selectedWifeNatchathiram = null;
                          setState(() {});
                        },
                        icon: Icon(
                          Iconsax.close_circle,
                          color: AppColors.primaryColor,
                        ),
                      ),
                controller: _wifeNatchathiram,
                label: "Wife Natchathiram",
                hintText: "Select",
                onTap: () async {
                  if (_wifeRasi.text.isNotEmpty) {
                    var value = await Sheet.showSheet(context,
                        size: 0.7,
                        widget: Natchathiram(rasi: _selectedWifeRasi ?? ''));
                    if (value != null) {
                      _wifeNatchathiram.text = value["value"];
                      _selectedWifeNatchathiram = value["id"];
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
        Row(
          children: [
            Expanded(
              child: FormFields(
                controller: _fatherId,
                enabled: false,
                label: "Father Id",
              ),
            ),
            const SizedBox(
              width: 10,
            ),
            Expanded(
              child: FormFields(
                controller: _familyOrder,
                enabled: false,
                label: "Family Order",
              ),
            ),
          ],
        ),
        const SizedBox(height: 10),
        Row(
          children: [
            Expanded(
              child: FormFields(
                controller: _fatherName,
                enabled: false,
                label: "Father Name",
              ),
            ),
            const SizedBox(
              width: 10,
            ),
            Expanded(
              child: FormFields(
                controller: _status,
                enabled: false,
                label: "Status",
              ),
            ),
          ],
        ),
        const SizedBox(height: 10),
        Row(
          children: [
            Expanded(
              child: FormFields(
                controller: _introducerId,
                enabled: false,
                label: "Introducer Id",
              ),
            ),
            const SizedBox(
              width: 10,
            ),
            Expanded(
              child: FormFields(
                controller: _introducerRelationship,
                enabled: false,
                label: "Introducer Relationship",
              ),
            ),
          ],
        ),
        const SizedBox(height: 10),
        Container(
          padding: const EdgeInsets.all(10),
          decoration: BoxDecoration(
            color: AppColors.pureWhiteColor,
            borderRadius: BorderRadius.circular(10),
          ),
          child: Table(children: [
            tableData(context, "Date Of Joining", _doj),
            tableData(context, "Date of Deletion", _dod),
            tableData(context, "Date of Rejoin", _dor)
          ]),
        )
      ],
    );
  }

  FloatingActionButton? _floatingButton() {
    if (_tabController?.index == 3) {
      return FloatingActionButton(
        heroTag: null,
        foregroundColor: AppColors.whiteColor,
        backgroundColor: AppColors.primaryColor,
        shape: const CircleBorder(),
        onPressed: () {
          // _childList.add(const ChildView());
          // setState(() {});
        },
        child: const Icon(Icons.add_rounded),
      );
    } else {
      return null;
    }
  }

  AppBar _appbar(BuildContext context) {
    return AppBar(
      leading: IconButton(
        icon: const Icon(Icons.arrow_back_ios_new_rounded),
        onPressed: () {
          Navigator.pop(context);
        },
        tooltip: "Back",
      ),
      title: const Text("Edit Profile"),
      actions: [
        IconButton(
          icon: SvgPicture.asset(SvgAssets.tick),
          onPressed: () {
            _saveProfile();
          },
          tooltip: "Save",
        )
      ],
      bottom: TabBar(
        tabAlignment: TabAlignment.start,
        dragStartBehavior: DragStartBehavior.start,
        labelColor: AppColors.whiteColor,
        indicatorColor: AppColors.whiteColor,
        unselectedLabelColor: Colors.white70,
        controller: _tabController,
        isScrollable: true,
        tabs: const [
          Tab(text: "Personal Details"),
          Tab(text: "Address"),
          Tab(text: "Member Images"),
          Tab(text: "Child Details"),
        ],
      ),
    );
  }
}
