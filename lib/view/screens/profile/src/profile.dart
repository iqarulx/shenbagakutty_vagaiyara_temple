import 'dart:ui';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:iconsax/iconsax.dart';
import 'package:shimmer/shimmer.dart';
import '/l10n/l10n.dart';
import '/functions/functions.dart';
import '/services/services.dart';
import '/view/view.dart';
import '/constants/constants.dart';

class Profile extends StatefulWidget {
  const Profile({super.key});

  @override
  State<Profile> createState() => _ProfileState();
}

class _ProfileState extends State<Profile> with TickerProviderStateMixin {
  @override
  void initState() {
    _tableTabController = TabController(length: 4, vsync: this);
    _profileHanlder = _getProfile();
    super.initState();
  }

  //***************** Tils *********************/

  _getProfile() async {
    try {
      var data = await ProfileService.getProfile();
      _profileData = data["body"];
    } catch (e) {
      Snackbar.showSnackBar(context, content: e.toString(), isSuccess: false);
    }
  }

  //***************** UI *********************/
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: FutureBuilder(
        future: _profileHanlder,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return futureWaitingLoading(context);
          } else if (snapshot.hasError) {
            return ErrorWidget(snapshot.error!);
          } else {
            return _buildProfile();
          }
        },
      ),
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded),
          onPressed: () {
            Navigator.pop(context);
          },
          tooltip: AppLocalizations.of(context).back,
        ),
        title: Text(AppLocalizations.of(context).profile),
        actions: [
          IconButton(
            icon: const Icon(Iconsax.refresh),
            tooltip: AppLocalizations.of(context).refresh,
            onPressed: () {
              _profileHanlder = _getProfile();
              setState(() {});
            },
          ),
          IconButton(
            icon: SvgPicture.asset(SvgAssets.pencil),
            tooltip: AppLocalizations.of(context).edit,
            onPressed: () async {
              var r = await Navigator.push(
                context,
                CupertinoPageRoute(
                  builder: (context) => ProfileEdit(profileData: _profileData),
                ),
              );
              if (r != null) {
                if (r) {
                  _profileHanlder = _getProfile();
                  setState(() {});
                }
              }
            },
          ),
        ],
      ),
    );
  }

  Widget _buildProfile() {
    return Padding(
      padding: const EdgeInsets.all(10),
      child: Container(
        decoration: BoxDecoration(
          color: AppColors.pureWhiteColor,
          borderRadius: BorderRadius.circular(10),
        ),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.start,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Stack(
              clipBehavior: Clip.none,
              children: [
                Container(
                  height: 180,
                  decoration: BoxDecoration(
                    borderRadius: BorderRadius.circular(10),
                    image: const DecorationImage(
                      fit: BoxFit.cover,
                      image: AssetImage(ImageAssets.profileBack),
                    ),
                  ),
                ),
                ClipRRect(
                  borderRadius: BorderRadius.circular(10),
                  child: BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: 1, sigmaY: 1),
                    child: Container(
                      height: 180,
                      color: Colors.black.withOpacity(0.2),
                    ),
                  ),
                ),
                Positioned(
                  top: 130,
                  left: 10,
                  child: Container(
                    padding: const EdgeInsets.all(3),
                    decoration: BoxDecoration(
                      shape: BoxShape.circle,
                      color: AppColors.pureWhiteColor,
                    ),
                    child: ClipOval(
                      child: InkWell(
                        onTap: () {
                          if (_profileData["member_profile_photos"]
                              .toString()
                              .isNotEmpty) {
                            Navigator.push(
                              context,
                              CupertinoPageRoute(builder: (context) {
                                return Preview(
                                    uri: _profileData["member_profile_photos"]
                                        .toString());
                              }),
                            );
                          }
                        },
                        child: CachedNetworkImage(
                          imageUrl: _profileData["member_profile_photos"]
                                  .toString()
                                  .isNotEmpty
                              ? _profileData["member_profile_photos"].toString()
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
                          fit: BoxFit.cover,
                          width: 70,
                          height: 70,
                          errorWidget: (context, url, error) =>
                              const Icon(Icons.error),
                        ),
                      ),
                    ),
                  ),
                ),
              ],
            ),
            const SizedBox(height: 40),
            Padding(
              padding: const EdgeInsets.only(left: 15, right: 10),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    "${_profileData["initial"]} ${_profileData["name"]}",
                    style: Theme.of(context).textTheme.titleLarge!.copyWith(
                          fontWeight: FontWeight.bold,
                        ),
                  ),
                  const SizedBox(height: 5),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(
                        "${AppLocalizations.of(context).memberId} : ${_profileData["status_base_member_id"]}",
                        style: Theme.of(context).textTheme.bodyLarge,
                      ),
                      Row(
                        children: [
                          const Icon(
                            Iconsax.location5,
                            size: 18,
                          ),
                          const SizedBox(
                            width: 3,
                          ),
                          Text(
                            "${_profileData["city"]}",
                            style: Theme.of(context).textTheme.bodyLarge,
                          ),
                        ],
                      ),
                    ],
                  ),
                ],
              ),
            ),
            const SizedBox(height: 10),
            PreferredSize(
              preferredSize: const Size(double.infinity, 50),
              child: TabBar(
                tabAlignment: TabAlignment.start,
                dragStartBehavior: DragStartBehavior.start,
                controller: _tableTabController,
                isScrollable: true,
                labelColor: Theme.of(context).primaryColor,
                indicatorColor: Theme.of(context).primaryColor,
                unselectedLabelColor: AppColors.blackColor,
                tabs: [
                  Tab(text: AppLocalizations.of(context).personalDetails),
                  Tab(text: AppLocalizations.of(context).address),
                  Tab(text: AppLocalizations.of(context).memberImages),
                  Tab(text: AppLocalizations.of(context).childDetails),
                ],
              ),
            ),
            Expanded(
              child: TabBarView(
                physics: const NeverScrollableScrollPhysics(),
                controller: _tableTabController,
                children: [
                  _buildProfileDetails(),
                  _buildAddressDetails(),
                  _buildMemberImages(),
                  _buildChildDetails(),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildChildDetails() {
    if (_profileData["children"].isNotEmpty) {
      return Padding(
        padding: const EdgeInsets.all(8.0),
        child: SingleChildScrollView(
          child: Column(
            children: [
              for (var i = 0; i < _profileData["children"].length; i++)
                Column(
                  children: [
                    ClipOval(
                      child: CachedNetworkImage(
                        imageUrl: _profileData["children"][i]
                                ["member_child_profile_photo"] ??
                            emptyProfilePhoto,
                        placeholder: (context, url) => Shimmer.fromColors(
                          baseColor: Colors.grey.shade300,
                          highlightColor: Colors.grey.shade100,
                          child: Container(
                            width: 80,
                            height: 80,
                            color: Colors.white,
                          ),
                        ),
                        fit: BoxFit.cover,
                        width: 80,
                        height: 80,
                        errorWidget: (context, url, error) =>
                            const Icon(Icons.error),
                      ),
                    ),
                    const SizedBox(height: 10),
                    Text(
                      "${_profileData["children"][i]["member_child_initial"]} ${_profileData["children"][i]["member_child_name"]}",
                      style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                            fontWeight: FontWeight.bold,
                          ),
                    ),
                    const SizedBox(
                      height: 10,
                    ),
                    Table(
                      children: [
                        profileData(
                            context,
                            AppLocalizations.of(context).gender,
                            _profileData["children"][i]
                                ["member_child_birth_date"]),
                        profileData(
                            context,
                            AppLocalizations.of(context).gender,
                            _profileData["children"][i]["member_child_gender"]),
                        profileData(
                            context,
                            AppLocalizations.of(context).rasi,
                            UtilsFunctions.getRasiTamilName(
                                rasi: _profileData["children"][i]
                                    ["member_child_rasi"])),
                        profileData(
                            context,
                            AppLocalizations.of(context).natchathiram,
                            UtilsFunctions.getNatchathiramTamilName(
                                natchathiram: _profileData["children"][i]
                                    ["member_child_natchathiram"])),
                        profileData(
                            context,
                            AppLocalizations.of(context).dateOfBirth,
                            _profileData["children"][i]
                                    ["member_child_birth_date"]
                                .toString()),
                        profileData(
                            context,
                            AppLocalizations.of(context).education,
                            _profileData["children"][i]
                                ["member_child_education"]),
                        profileData(
                            context,
                            AppLocalizations.of(context).job,
                            _profileData["children"][i]["member_child_job_name"]
                                .toString()),
                        profileData(
                            context,
                            AppLocalizations.of(context).marriageStatus,
                            _profileData["children"][i]
                                        ["member_child_marriage_status"] ==
                                    2
                                ? AppLocalizations.of(context).no
                                : AppLocalizations.of(context).yes),
                        if (_profileData["children"][i]
                                ["member_child_marriage_status"] ==
                            1)
                          profileData(
                              context,
                              AppLocalizations.of(context).partnerName,
                              _profileData["children"][i]
                                  ["member_child_partner_name"]),
                        if (_profileData["children"][i]
                                ["member_child_marriage_status"] ==
                            1)
                          profileData(
                              context,
                              AppLocalizations.of(context).partnerEducation,
                              _profileData["children"][i]
                                  ["member_child_partner_education"]),
                        if (_profileData["children"][i]
                                ["member_child_marriage_status"] ==
                            1)
                          profileData(
                              context,
                              AppLocalizations.of(context).partnerBirthDate,
                              _profileData["children"][i]
                                  ["member_child_partner_birth_date"]),
                        if (_profileData["children"][i]
                                ["member_child_marriage_status"] ==
                            1)
                          profileData(
                              context,
                              AppLocalizations.of(context).marriageDate,
                              _profileData["children"][i]
                                  ["member_child_marriage_date"]),
                        if (_profileData["children"][i]
                                ["member_child_marriage_status"] ==
                            1)
                          profileData(
                              context,
                              AppLocalizations.of(context).partnerRasi,
                              UtilsFunctions.getRasiTamilName(
                                  rasi: _profileData["children"][i]
                                      ["member_child_partner_rasi"])),
                        if (_profileData["children"][i]
                                ["member_child_marriage_status"] ==
                            1)
                          profileData(
                              context,
                              AppLocalizations.of(context).partnerNatchathiram,
                              UtilsFunctions.getNatchathiramTamilName(
                                  natchathiram: _profileData["children"][i]
                                      ["member_child_partner_natchathiram"])),
                      ],
                    ),
                    const Divider()
                  ],
                )
            ],
          ),
        ),
      );
    }
    return noData(context);
  }

  Widget _buildMemberImages() {
    return Column(
      mainAxisAlignment: MainAxisAlignment.start,
      children: [
        const SizedBox(
          height: 10,
        ),
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceEvenly,
          children: [
            Column(
              children: [
                Text(AppLocalizations.of(context).profilePhoto,
                    style: Theme.of(context).textTheme.bodyLarge),
                const SizedBox(
                  height: 10,
                ),
                InkWell(
                  onTap: () {
                    if (_profileData["member_profile_photos"]
                        .toString()
                        .isNotEmpty) {
                      Navigator.push(
                        context,
                        CupertinoPageRoute(builder: (context) {
                          return Preview(
                              uri: _profileData["member_profile_photos"]
                                  .toString());
                        }),
                      );
                    }
                  },
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(10),
                    child: CachedNetworkImage(
                      imageUrl: _profileData["member_profile_photos"]
                              .toString()
                              .isNotEmpty
                          ? _profileData["member_profile_photos"].toString()
                          : emptyProfilePhoto,
                      placeholder: (context, url) => Shimmer.fromColors(
                        baseColor: Colors.grey.shade300,
                        highlightColor: Colors.grey.shade100,
                        child: Container(
                          width: 200,
                          height: 200,
                          color: Colors.white,
                        ),
                      ),
                      fit: BoxFit.cover,
                      width: 100,
                      height: 100,
                      errorWidget: (context, url, error) =>
                          const Icon(Icons.error),
                    ),
                  ),
                ),
              ],
            ),
            Column(
              children: [
                Text(AppLocalizations.of(context).wifePhoto,
                    style: Theme.of(context).textTheme.bodyLarge),
                const SizedBox(height: 10),
                InkWell(
                  onTap: () {
                    if (_profileData["member_wife_photos"]
                        .toString()
                        .isNotEmpty) {
                      Navigator.push(
                        context,
                        CupertinoPageRoute(builder: (context) {
                          return Preview(
                              uri: _profileData["member_wife_photos"]
                                  .toString());
                        }),
                      );
                    }
                  },
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(10),
                    child: CachedNetworkImage(
                      imageUrl: _profileData["member_wife_photos"]
                              .toString()
                              .isNotEmpty
                          ? _profileData["member_wife_photos"].toString()
                          : emptyProfilePhoto,
                      placeholder: (context, url) => Shimmer.fromColors(
                        baseColor: Colors.grey.shade300,
                        highlightColor: Colors.grey.shade100,
                        child: Container(
                          width: 200,
                          height: 200,
                          color: Colors.white,
                        ),
                      ),
                      fit: BoxFit.cover,
                      width: 100,
                      height: 100,
                      errorWidget: (context, url, error) =>
                          const Icon(Icons.error),
                    ),
                  ),
                ),
              ],
            )
          ],
        ),
        const SizedBox(
          height: 20,
        ),
        Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text(AppLocalizations.of(context).familyPhoto,
                style: Theme.of(context).textTheme.bodyLarge),
            const SizedBox(
              height: 10,
            ),
            if (_profileData["member_family_photos"].isNotEmpty)
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceEvenly,
                children: [
                  for (var i = 0;
                      i < _profileData["member_family_photos"].length;
                      i++)
                    InkWell(
                      onTap: () {
                        if (_profileData["member_family_photos"][i]
                            .toString()
                            .isNotEmpty) {
                          Navigator.push(
                            context,
                            CupertinoPageRoute(builder: (context) {
                              return Preview(
                                  uri: _profileData["member_family_photos"][i]
                                      .toString());
                            }),
                          );
                        }
                      },
                      child: ClipRRect(
                        borderRadius: BorderRadius.circular(10),
                        child: CachedNetworkImage(
                          imageUrl: _profileData["member_family_photos"][i]
                                  .toString()
                                  .isNotEmpty
                              ? _profileData["member_family_photos"][i]
                                  .toString()
                              : emptyProfilePhoto,
                          placeholder: (context, url) => Shimmer.fromColors(
                            baseColor: Colors.grey.shade300,
                            highlightColor: Colors.grey.shade100,
                            child: Container(
                              width: 200,
                              height: 200,
                              color: Colors.white,
                            ),
                          ),
                          fit: BoxFit.cover,
                          width: 100,
                          height: 100,
                          errorWidget: (context, url, error) =>
                              const Icon(Icons.error),
                        ),
                      ),
                    ),
                ],
              )
            else
              Text(
                "No family images found",
                style: Theme.of(context).textTheme.bodyLarge!.copyWith(
                      color: AppColors.greyColor,
                    ),
              )
          ],
        )
      ],
    );
  }

  Widget _buildAddressDetails() {
    return Padding(
      padding: const EdgeInsets.all(8.0),
      child: SingleChildScrollView(
        child: Table(
          children: [
            profileData(
              context,
              AppLocalizations.of(context).address,
              _profileData["address"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).country,
              _profileData["country"].toString().split(',').last,
            ),
            profileData(
              context,
              AppLocalizations.of(context).state,
              _profileData["state"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).city,
              _profileData["city"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).pincode,
              _profileData["pincode"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).companyName,
              _profileData["company_name"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).remarks,
              _profileData["remarks"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).history,
              _profileData["member_history"].toString(),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildProfileDetails() {
    return Padding(
      padding: const EdgeInsets.all(8.0),
      child: SingleChildScrollView(
        child: Table(
          children: [
            profileData(
              context,
              AppLocalizations.of(context).rasi,
              UtilsFunctions.getRasiTamilName(
                  rasi: _profileData["rasi"].toString()),
            ),
            profileData(
              context,
              AppLocalizations.of(context).natchathiram,
              UtilsFunctions.getNatchathiramTamilName(
                  natchathiram: _profileData["natchathiram"].toString()),
            ),
            profileData(
              context,
              AppLocalizations.of(context).profession,
              _profileData["profession_name"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).wifeName,
              _profileData["wife_name"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).wifeEducation,
              _profileData["wife_education"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).fatherId,
              _profileData["father_id"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).familyOrder,
              _profileData["family_order"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).adhaarNumber,
              _profileData["aadhaar_number"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).introducerId,
              _profileData["introducer_id"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).introducerRelationship,
              _profileData["introducer_relationship"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).status,
              _profileData["status_name"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).phoneNumber,
              _profileData["phone_number"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).mobileNumber,
              _profileData["mobile_number"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).dateOfJoining,
              _profileData["date_of_joining"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).dateOfDeletion,
              _profileData["date_of_deletion"].toString(),
            ),
            profileData(
              context,
              AppLocalizations.of(context).dateOfJoining,
              _profileData["date_of_rejoin"].toString(),
            ),
          ],
        ),
      ),
    );
  }
  //***************** End of UI *********************/

  //***************** Variables *********************/
  late Future _profileHanlder;
  Map<String, dynamic> _profileData = {};
  TabController? _tableTabController;
}
