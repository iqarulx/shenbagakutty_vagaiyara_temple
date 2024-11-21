// ignore_for_file: unused_field

import 'dart:developer';
import 'dart:io';

import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:intl/intl.dart';
import 'package:shimmer/shimmer.dart';
import '/constants/constants.dart';
import '/functions/functions.dart';
import '/services/services.dart';
import '/utils/utils.dart';
import '/view/view.dart';
import '/model/model.dart';

class ProfileEdit extends StatefulWidget {
  final Map<String, dynamic> profileData; // Profile Data
  const ProfileEdit({super.key, required this.profileData});

  @override
  State<ProfileEdit> createState() => _ProfileEditState();
}

class _ProfileEditState extends State<ProfileEdit>
    with TickerProviderStateMixin {
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

  //***************** UI *********************/
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: _appbar(context),
      floatingActionButton: _floatingButton(),
      bottomNavigationBar: bottomAppbar(context),
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
      children: [
        for (var i = 0; i < childList.length; i++)
          Column(
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
                          style: Theme.of(context)
                              .textTheme
                              .bodyLarge!
                              .copyWith(
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
                          onPressed: () async {
                            var r = await showDialog(
                              context: context,
                              builder: (context) {
                                return const CDialog(
                                  title: "Remove",
                                  content: "Are you sure want to remove child?",
                                );
                              },
                            );
                            if (r != null) {
                              if (r) {
                                childList.removeAt(i);
                                setState(() {});
                              }
                            }
                          },
                        )
                      ],
                    ),
                    const SizedBox(height: 10),
                    Stack(
                      children: [
                        ClipOval(
                          child: selectedChildPhoto[i] != null
                              ? Image.file(
                                  selectedChildPhoto[i]!,
                                  width: 150,
                                  height: 150,
                                )
                              : InkWell(
                                  onTap: () {},
                                  child: CachedNetworkImage(
                                    imageUrl: emptyProfilePhoto,
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
                        ),
                        if (childList[i].profilePhotoEdit)
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
                                      var v = await ImageFunctions.uploadImage(
                                        context,
                                        type: ImageType.child,
                                        file: pr,
                                        prefix: i.toString(),
                                      );
                                      if (v) {
                                        selectedChildPhoto[i] = pr;
                                      }
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
                            controller: childList[i].childInitialController,
                            label: "Intial",
                          ),
                        ),
                        const SizedBox(
                          width: 10,
                        ),
                        Expanded(
                          flex: 3,
                          child: FormFields(
                            controller: childList[i].childNameController,
                            label: "Name",
                          ),
                        )
                      ],
                    ),
                    const SizedBox(height: 10),
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          "Gender",
                          style: Theme.of(context)
                              .textTheme
                              .bodyMedium!
                              .copyWith(
                                  color: Colors.black,
                                  fontWeight: FontWeight.w300),
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
                                groupValue: childList[i].childGender,
                                activeColor: AppColors.primaryColor,
                                onChanged: (v) {
                                  childList[i].childGender = v;
                                  setState(() {});
                                },
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
                                groupValue: childList[i].childGender,
                                onChanged: (v) {
                                  childList[i].childGender = v;
                                  setState(() {});
                                },
                                contentPadding: EdgeInsets.zero,
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                    Row(
                      children: [
                        Expanded(
                          child: FormFields(
                            controller: childList[i].childDobController,
                            label: "Date of Birth",
                            hintText: "dd-mm-yyyy",
                            onTap: () =>
                                _dobPicker(childList[i].childDobController),
                            readOnly: true,
                          ),
                        ),
                        const SizedBox(
                          width: 10,
                        ),
                        Expanded(
                          child: FormFields(
                            controller:
                                childList[i].childMobileNumberController,
                            label: "Mobile No",
                            keyType: TextInputType.number,
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                    Row(
                      children: [
                        Expanded(
                          child: FormFields(
                            suffixIcon: childList[i]
                                    .childRasiController
                                    .text
                                    .isEmpty
                                ? const Icon(Icons.arrow_drop_down_rounded)
                                : IconButton(
                                    tooltip: "Clear",
                                    onPressed: () {
                                      childList[i].childRasiController.clear();
                                      childList[i].selectedChildRasi = null;
                                      childList[i]
                                          .childNatchathiramController
                                          .clear();
                                      setState(() {});
                                    },
                                    icon: Icon(
                                      Iconsax.close_circle,
                                      color: AppColors.primaryColor,
                                    ),
                                  ),
                            controller: childList[i].childRasiController,
                            label: "Rasi",
                            hintText: "Select",
                            onTap: () async {
                              var value = await Sheet.showSheet(context,
                                  size: 0.9, widget: const Rasi());
                              if (value != null) {
                                childList[i].childRasiController.text =
                                    value["value"];
                                childList[i].selectedChildRasi = value["id"];
                                setState(() {});
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
                            suffixIcon: childList[i]
                                    .childNatchathiramController
                                    .text
                                    .isEmpty
                                ? const Icon(Icons.arrow_drop_down_rounded)
                                : IconButton(
                                    tooltip: "Clear",
                                    onPressed: () {
                                      childList[i]
                                          .childNatchathiramController
                                          .clear();
                                      childList[i].selectedChildNatchathiram =
                                          null;
                                      setState(() {});
                                    },
                                    icon: Icon(
                                      Iconsax.close_circle,
                                      color: AppColors.primaryColor,
                                    ),
                                  ),
                            controller:
                                childList[i].childNatchathiramController,
                            label: "Natchathiram",
                            hintText: "Select",
                            onTap: () async {
                              if (childList[i]
                                  .childRasiController
                                  .text
                                  .isNotEmpty) {
                                var value = await Sheet.showSheet(context,
                                    size: 0.7,
                                    widget: Natchathiram(
                                        rasi: childList[i].selectedChildRasi ??
                                            ''));
                                if (value != null) {
                                  childList[i]
                                      .childNatchathiramController
                                      .text = value["value"];
                                  childList[i].selectedChildNatchathiram =
                                      value["id"];
                                  setState(() {});
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
                            controller: childList[i].childEducationController,
                            label: "Education",
                          ),
                        ),
                        const SizedBox(
                          width: 10,
                        ),
                        Expanded(
                            child: FormFields(
                          suffixIcon:
                              childList[i].childJobController.text.isEmpty
                                  ? const Icon(Icons.arrow_drop_down_rounded)
                                  : IconButton(
                                      tooltip: "Clear",
                                      onPressed: () {
                                        childList[i].childJobController.clear();
                                        childList[i].selectedChildJob = null;

                                        setState(() {});
                                      },
                                      icon: Icon(
                                        Iconsax.close_circle,
                                        color: AppColors.primaryColor,
                                      ),
                                    ),
                          controller: childList[i].childJobController,
                          label: "Job",
                          hintText: "Select",
                          onTap: () async {
                            var value = await Sheet.showSheet(context,
                                size: 0.9,
                                widget: Profession(
                                    profData:
                                        widget.profileData["profession"]));
                            if (value != null) {
                              childList[i].childJobController.text =
                                  value["code"];
                              childList[i].selectedChildJob = value["id"];
                              setState(() {});
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
                        )),
                      ],
                    ),
                    const SizedBox(height: 10),
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        Text(
                          "Marrige Status",
                          style: Theme.of(context)
                              .textTheme
                              .bodyMedium!
                              .copyWith(
                                  color: Colors.black,
                                  fontWeight: FontWeight.w300),
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
                                groupValue: childList[i].childMarrigeStatus,
                                activeColor: AppColors.primaryColor,
                                onChanged: (v) {
                                  childList[i].childMarrigeStatus = v;
                                  setState(() {});
                                },
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
                                groupValue: childList[i].childMarrigeStatus,
                                onChanged: (v) {
                                  childList[i].childMarrigeStatus = v;
                                  setState(() {});
                                },
                              ),
                            ),
                          ],
                        ),
                      ],
                    ),
                    const SizedBox(height: 10),
                    if (childList[i].childMarrigeStatus != null &&
                        childList[i].childMarrigeStatus == "1")
                      Column(
                        children: [
                          const Divider(),
                          const SizedBox(height: 5),
                          Text(
                            "Life Partner Details",
                            style: Theme.of(context)
                                .textTheme
                                .bodyLarge!
                                .copyWith(
                                    color: AppColors.blackColor,
                                    fontWeight: FontWeight.bold),
                          ),
                          const SizedBox(height: 10),
                          Row(
                            children: [
                              Expanded(
                                child: FormFields(
                                  controller:
                                      childList[i].lifePartnerNameController,
                                  label: "Name",
                                ),
                              ),
                              const SizedBox(
                                width: 10,
                              ),
                              Expanded(
                                child: FormFields(
                                  controller: childList[i]
                                      .lifePatnerEducationController,
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
                                  controller:
                                      childList[i].lifePartnerDobController,
                                  label: "Date of Birth",
                                  hintText: "dd-mm-yyyy",
                                  onTap: () => _dobPicker(
                                      childList[i].lifePartnerDobController),
                                  readOnly: true,
                                ),
                              ),
                              const SizedBox(
                                width: 10,
                              ),
                              Expanded(
                                child: FormFields(
                                  controller:
                                      childList[i].marriageDateController,
                                  label: "Marriage Date",
                                  hintText: "dd-mm-yyyy",
                                  onTap: () => _dobPicker(
                                      childList[i].marriageDateController),
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
                                  suffixIcon: childList[i]
                                          .lifePartnerRasiController
                                          .text
                                          .isEmpty
                                      ? const Icon(
                                          Icons.arrow_drop_down_rounded)
                                      : IconButton(
                                          tooltip: "Clear",
                                          onPressed: () {
                                            childList[i]
                                                .lifePartnerRasiController
                                                .clear();
                                            childList[i]
                                                .selectedLifePartnerRasi = null;
                                            childList[i]
                                                .lifePartnerNatchathiramController
                                                .clear();
                                            setState(() {});
                                          },
                                          icon: Icon(
                                            Iconsax.close_circle,
                                            color: AppColors.primaryColor,
                                          ),
                                        ),
                                  controller:
                                      childList[i].lifePartnerRasiController,
                                  label: "Rasi",
                                  hintText: "Select",
                                  onTap: () async {
                                    var value = await Sheet.showSheet(context,
                                        size: 0.9, widget: const Rasi());
                                    if (value != null) {
                                      childList[i]
                                          .lifePartnerRasiController
                                          .text = value["value"];
                                      childList[i].selectedLifePartnerRasi =
                                          value["id"];
                                      setState(() {});
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
                                  suffixIcon: childList[i]
                                          .lifePartnerNatchathiramController
                                          .text
                                          .isEmpty
                                      ? const Icon(
                                          Icons.arrow_drop_down_rounded)
                                      : IconButton(
                                          tooltip: "Clear",
                                          onPressed: () {
                                            childList[i]
                                                .lifePartnerNatchathiramController
                                                .clear();
                                            childList[i]
                                                    .selectedLifePartnerNatchathiram =
                                                null;
                                            setState(() {});
                                          },
                                          icon: Icon(
                                            Iconsax.close_circle,
                                            color: AppColors.primaryColor,
                                          ),
                                        ),
                                  controller: childList[i]
                                      .lifePartnerNatchathiramController,
                                  label: "Natchathiram",
                                  hintText: "Select",
                                  onTap: () async {
                                    if (childList[i]
                                        .lifePartnerRasiController
                                        .text
                                        .isNotEmpty) {
                                      var value = await Sheet.showSheet(context,
                                          size: 0.7,
                                          widget: Natchathiram(
                                              rasi: childList[i]
                                                      .selectedChildRasi ??
                                                  ''));
                                      if (value != null) {
                                        log(value.toString());
                                        childList[i]
                                            .lifePartnerNatchathiramController
                                            .text = value["value"];
                                        childList[i]
                                                .selectedLifePartnerNatchathiram =
                                            value["id"];

                                        setState(() {});
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
          )
      ],
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
                        : InkWell(
                            onTap: () {
                              if (_profilePhoto != null) {
                                Navigator.push(
                                  context,
                                  CupertinoPageRoute(builder: (context) {
                                    return Preview(
                                        uri: _profilePhoto.toString());
                                  }),
                                );
                              }
                            },
                            child: CachedNetworkImage(
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
                              var v = await ImageFunctions.uploadImage(context,
                                  type: ImageType.profile, file: pr);
                              if (v) {
                                _selectedProfilePhoto = pr;
                              }
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
                      : InkWell(
                          onTap: () {
                            if (_wifePhoto != null) {
                              Navigator.push(
                                context,
                                CupertinoPageRoute(builder: (context) {
                                  return Preview(uri: _wifePhoto.toString());
                                }),
                              );
                            }
                          },
                          child: CachedNetworkImage(
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
                            var v = await ImageFunctions.uploadImage(context,
                                file: pr, type: ImageType.wife);
                            if (v) {
                              _selectedWifePhoto = pr;
                            }
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
                            : InkWell(
                                onTap: () {
                                  if (_familyPhoto != null &&
                                      _familyPhoto!.isNotEmpty) {
                                    Navigator.push(
                                      context,
                                      CupertinoPageRoute(builder: (context) {
                                        return Preview(
                                            uri: _familyPhoto![0].toString());
                                      }),
                                    );
                                  }
                                },
                                child: CachedNetworkImage(
                                  imageUrl: _familyPhoto != null &&
                                          _familyPhoto!.isNotEmpty
                                      ? (_familyPhoto!.isNotEmpty
                                          ? _familyPhoto![0]
                                          : emptyProfilePhoto)
                                      : emptyProfilePhoto,
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
                                  var v = await ImageFunctions.uploadImage(
                                    context,
                                    file: pr,
                                    type: ImageType.family,
                                    prefix: "1",
                                  );
                                  if (v) {
                                    _selectedFamilyPhoto1 = pr;
                                  }
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
                            : InkWell(
                                onTap: () {
                                  if (_familyPhoto != null &&
                                      _familyPhoto!.isNotEmpty) {
                                    if (_familyPhoto!.length > 1) {
                                      Navigator.push(
                                        context,
                                        CupertinoPageRoute(builder: (context) {
                                          return Preview(
                                              uri: _familyPhoto![1].toString());
                                        }),
                                      );
                                    }
                                  }
                                },
                                child: CachedNetworkImage(
                                  imageUrl: _familyPhoto != null &&
                                          _familyPhoto!.isNotEmpty
                                      ? (_familyPhoto!.length > 1
                                          ? _familyPhoto![1]
                                          : emptyProfilePhoto)
                                      : emptyProfilePhoto,
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
                                  var v = await ImageFunctions.uploadImage(
                                    context,
                                    file: pr,
                                    type: ImageType.family,
                                    prefix: "2",
                                  );
                                  if (v) {
                                    _selectedFamilyPhoto2 = pr;
                                  }
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
                        : InkWell(
                            onTap: () {
                              if (_familyPhoto != null &&
                                  _familyPhoto!.isNotEmpty) {
                                if (_familyPhoto!.length > 2) {
                                  Navigator.push(
                                    context,
                                    CupertinoPageRoute(builder: (context) {
                                      return Preview(
                                          uri: _familyPhoto![2].toString());
                                    }),
                                  );
                                }
                              }
                            },
                            child: CachedNetworkImage(
                              imageUrl: _familyPhoto != null &&
                                      _familyPhoto!.isNotEmpty
                                  ? (_familyPhoto!.length > 2
                                      ? _familyPhoto![2]
                                      : emptyProfilePhoto)
                                  : emptyProfilePhoto,
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
                              var v = await ImageFunctions.uploadImage(
                                context,
                                file: pr,
                                type: ImageType.family,
                                prefix: "3",
                              );
                              if (v) {
                                _selectedFamilyPhoto3 = pr;
                              }
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
                keyType: TextInputType.number,
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
          _addNewChild();
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
          Navigator.pop(context, true);
        },
        tooltip: "Back",
      ),
      title: const Text("Edit Profile"),
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

  BottomAppBar bottomAppbar(BuildContext context) {
    return BottomAppBar(
      color: Colors.white,
      surfaceTintColor: Colors.white,
      child: ElevatedButton(
        style: ButtonStyle(
          backgroundColor: MaterialStateProperty.all(AppColors.primaryColor),
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
              "Submit",
              style: Theme.of(context)
                  .textTheme
                  .bodyLarge!
                  .copyWith(color: AppColors.pureWhiteColor),
            ),
          ],
        ),
        onPressed: () async {
          _saveProfile();
        },
      ),
    );
  }
  //***************** End of UI *********************/

  //***************** Utils *********************/

  void _addListeners(List<TextEditingController> controllers) {
    for (var controller in controllers) {
      controller.addListener(() {
        setState(() {});
      });
    }
  }

  _dobPicker(TextEditingController c) async {
    final DateTime? picked = await datePicker(context);
    if (picked != null) {
      setState(() {
        c.text = DateFormat('yyyy-MM-dd').format(picked);
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
      selectedChildPhoto.add(null);

      childList.add(
        ChildModel(
          id: i["member_child_id"].toString(),
          profilePhotoEdit: true,
          childNameController:
              TextEditingController(text: i["member_child_name"].toString()),
          childEducationController: TextEditingController(
              text: i["member_child_education"].toString()),
          childJobController: TextEditingController(
              text: i["member_child_job_name"].toString()),
          childInitialController:
              TextEditingController(text: i["member_child_initial"].toString()),
          childDobController: TextEditingController(
              text: i["member_child_birth_date"].toString()),
          childRasiController: TextEditingController(
              text: UtilsFunctions.getRasiTamilName(
                  rasi: i["member_child_rasi"].toString())),
          childNatchathiramController: TextEditingController(
              text: UtilsFunctions.getNatchathiramTamilName(
                  natchathiram: i["member_child_natchathiram"].toString())),
          childMobileNumberController: TextEditingController(
              text: i["member_child_mobile_number"].toString()),
          lifePartnerNameController: TextEditingController(
              text: i["member_child_partner_name"].toString()),
          lifePatnerEducationController: TextEditingController(
              text: i["member_child_partner_education"].toString()),
          marriageDateController: TextEditingController(
              text: i["member_child_marriage_date"].toString()),
          lifePartnerRasiController: TextEditingController(
              text: UtilsFunctions.getRasiTamilName(
                  rasi: i["member_child_partner_rasi"].toString())),
          lifePartnerNatchathiramController: TextEditingController(
              text: UtilsFunctions.getNatchathiramTamilName(
                  natchathiram:
                      i["member_child_partner_natchathiram"].toString())),
          lifePartnerDobController: TextEditingController(
              text: i["member_child_partner_birth_date"].toString()),
          childGender: i["member_child_gender"].toString(),
          childMarrigeStatus: i["member_child_marriage_status"].toString(),
          selectedChildJob: i["member_child_job"].toString(),
          selectedChildRasi: i["member_child_rasi"].toString(),
          selectedChildNatchathiram: i["member_child_natchathiram"].toString(),
          selectedLifePartnerRasi: i["member_child_partner_rasi"].toString(),
          selectedLifePartnerNatchathiram:
              i["member_child_partner_natchathiram"].toString(),
        ),
      );
    }
  }

  _addNewChild() {
    selectedChildPhoto.add(null);
    childList.add(
      ChildModel(
        id: null,
        profilePhotoEdit: false,
        childNameController: TextEditingController(),
        childEducationController: TextEditingController(),
        childJobController: TextEditingController(),
        childInitialController: TextEditingController(),
        childDobController: TextEditingController(),
        childRasiController: TextEditingController(),
        childNatchathiramController: TextEditingController(),
        childMobileNumberController: TextEditingController(),
        lifePartnerNameController: TextEditingController(),
        lifePatnerEducationController: TextEditingController(),
        marriageDateController: TextEditingController(),
        lifePartnerRasiController: TextEditingController(),
        lifePartnerNatchathiramController: TextEditingController(),
        lifePartnerDobController: TextEditingController(),
        childGender: null,
        selectedChildJob: null,
        childMarrigeStatus: null,
        selectedChildRasi: null,
        selectedChildNatchathiram: null,
        selectedLifePartnerRasi: null,
        selectedLifePartnerNatchathiram: null,
      ),
    );
    setState(() {});
  }

  //***************** Submit *********************/
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

        var childMapDetails = [];

        for (var i = 0; i < childList.length; i++) {
          var childDetails = {
            "member_child${i}_id": childList[i].id ?? "",
            "member_child${i}_initial":
                childList[i].childInitialController.text,
            "member_child${i}_name": childList[i].childNameController.text,
            "member_child${i}_gender": childList[i].childGender ?? "",
            "member_child${i}_birth_date": childList[i].childDobController.text,
            "member_child${i}_rasi": childList[i].selectedChildRasi ?? "",
            "member_child${i}_natchathiram":
                childList[i].selectedChildNatchathiram ?? "",
            "member_child${i}_education":
                childList[i].childEducationController.text,
            "member_child${i}_job": childList[i].selectedChildJob,
            "member_child${i}_marriage_status":
                childList[i].childMarrigeStatus ?? "",
            "member_child${i}_mobile_number":
                childList[i].childMobileNumberController.text,
            "member_child${i}_photo": "",
          };

          childDetails["member_child${i}_partner_name"] =
              childList[i].lifePartnerNameController.text;
          childDetails["member_child${i}_partner_education"] =
              childList[i].lifePatnerEducationController.text;
          childDetails["member_child${i}_partner_birth_date"] =
              childList[i].lifePartnerDobController.text;
          childDetails["member_child${i}_marriage_date"] =
              childList[i].marriageDateController.text;
          childDetails["member_child${i}_partner_rasi"] =
              childList[i].selectedLifePartnerRasi ?? "";
          childDetails["member_child${i}_partner_natchathiram"] =
              childList[i].selectedLifePartnerNatchathiram ?? "";

          childMapDetails.add(childDetails);
        }

        updateData["children"] = childMapDetails;
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

  //************** Variables ****************/
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

  File? _selectedProfilePhoto;
  File? _selectedWifePhoto;
  File? _selectedFamilyPhoto1;
  File? _selectedFamilyPhoto2;
  File? _selectedFamilyPhoto3;

  List<ChildModel> childList = [];
  List<File?> selectedChildPhoto = [];
  TabController? _tabController;
}
