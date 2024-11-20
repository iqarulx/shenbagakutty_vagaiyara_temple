import 'dart:ui';
import 'package:cached_network_image/cached_network_image.dart';
import 'package:flutter/cupertino.dart';
import 'package:flutter/gestures.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:iconsax/iconsax.dart';
import 'package:shimmer/shimmer.dart';
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
  late Future _profileHanlder;
  Map<String, dynamic> _profileData = {};
  TabController? _tableTabController;

  @override
  void initState() {
    _tableTabController = TabController(length: 4, vsync: this);

    _profileHanlder = _getProfile();
    super.initState();
  }

  _getProfile() async {
    try {
      var data = await ProfileService.getProfile();
      _profileData = data["body"];
    } catch (e) {
      Snackbar.showSnackBar(context, content: e.toString(), isSuccess: false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: FutureBuilder(
        future: _profileHanlder,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return futureWaitingLoading();
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
          tooltip: "Back",
        ),
        title: const Text("Profile"),
        actions: [
          IconButton(
            icon: const Icon(Iconsax.refresh),
            tooltip: "Refresh",
            onPressed: () {
              _profileHanlder = _getProfile();
              setState(() {});
            },
          ),
          IconButton(
            icon: SvgPicture.asset(SvgAssets.pencil),
            tooltip: "Edit",
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
                      child: CachedNetworkImage(
                        imageUrl: _profileData["member_profile_photos"],
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
                        "Member Id : ${_profileData["status_base_member_id"]}",
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
                labelColor: AppColors.primaryColor,
                indicatorColor: AppColors.primaryColor,
                unselectedLabelColor: AppColors.blackColor,
                tabs: const [
                  Tab(text: "Personal Details"),
                  Tab(text: "Address"),
                  Tab(text: "Member Images"),
                  Tab(text: "Child Details")
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
                            "Birth Date",
                            _profileData["children"][i]
                                ["member_child_birth_date"]),
                        profileData(context, "Gender",
                            _profileData["children"][i]["member_child_gender"]),
                        profileData(
                            context,
                            "Rasi",
                            UtilsFunctions.getRasiTamilName(
                                rasi: _profileData["children"][i]
                                    ["member_child_rasi"])),
                        profileData(
                            context,
                            "Natchathiram",
                            UtilsFunctions.getNatchathiramTamilName(
                                natchathiram: _profileData["children"][i]
                                    ["member_child_natchathiram"])),
                        profileData(
                            context,
                            "Birth Date",
                            _profileData["children"][i]
                                    ["member_child_mobile_number"]
                                .toString()),
                        profileData(
                            context,
                            "Education",
                            _profileData["children"][i]
                                ["member_child_education"]),
                        profileData(
                            context,
                            "Marriage Status",
                            _profileData["children"][i]
                                        ["member_child_marriage_status"] ==
                                    2
                                ? "No"
                                : "Yes"),
                        if (_profileData["children"][i]
                                ["member_child_marriage_status"] ==
                            1)
                          profileData(
                              context,
                              "Partner Name",
                              _profileData["children"][i]
                                  ["member_child_partner_name"]),
                        if (_profileData["children"][i]
                                ["member_child_marriage_status"] ==
                            1)
                          profileData(
                              context,
                              "Partner Education",
                              _profileData["children"][i]
                                  ["member_child_partner_education"]),
                        if (_profileData["children"][i]
                                ["member_child_marriage_status"] ==
                            1)
                          profileData(
                              context,
                              "Partner Birth Date",
                              _profileData["children"][i]
                                  ["member_child_partner_birth_date"]),
                        if (_profileData["children"][i]
                                ["member_child_marriage_status"] ==
                            1)
                          profileData(
                              context,
                              "Marriage Date",
                              _profileData["children"][i]
                                  ["member_child_marriage_date"]),
                        if (_profileData["children"][i]
                                ["member_child_marriage_status"] ==
                            1)
                          profileData(
                              context,
                              "Partner Rasi",
                              UtilsFunctions.getRasiTamilName(
                                  rasi: _profileData["children"][i]
                                      ["member_child_partner_rasi"])),
                        if (_profileData["children"][i]
                                ["member_child_marriage_status"] ==
                            1)
                          profileData(
                              context,
                              "Partner Natchathiram",
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
    return noData();
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
                Text("Profile Photo",
                    style: Theme.of(context).textTheme.bodyLarge),
                const SizedBox(
                  height: 10,
                ),
                ClipRRect(
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
              ],
            ),
            Column(
              children: [
                Text("Wife Photo",
                    style: Theme.of(context).textTheme.bodyLarge),
                const SizedBox(height: 10),
                ClipRRect(
                  borderRadius: BorderRadius.circular(10),
                  child: CachedNetworkImage(
                    imageUrl:
                        _profileData["member_wife_photos"].toString().isNotEmpty
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
            Text("Family Photo", style: Theme.of(context).textTheme.bodyLarge),
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
                    ClipRRect(
                      borderRadius: BorderRadius.circular(10),
                      child: CachedNetworkImage(
                        imageUrl: _profileData["member_family_photos"][i]
                                .toString()
                                .isNotEmpty
                            ? _profileData["member_family_photos"][i].toString()
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
              "Address",
              _profileData["address"].toString(),
            ),
            profileData(
              context,
              "Country",
              _profileData["country"].toString().split(',').last,
            ),
            profileData(
              context,
              "State",
              _profileData["state"].toString(),
            ),
            profileData(
              context,
              "City",
              _profileData["city"].toString(),
            ),
            profileData(
              context,
              "Pincode",
              _profileData["pincode"].toString(),
            ),
            profileData(
              context,
              "Company Name",
              _profileData["company_name"].toString(),
            ),
            profileData(
              context,
              "Remarks",
              _profileData["remarks"].toString(),
            ),
            profileData(
              context,
              "Histroy",
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
              "Member Rasi",
              UtilsFunctions.getRasiTamilName(
                  rasi: _profileData["rasi"].toString()),
            ),
            profileData(
              context,
              "Member Natchathiram",
              UtilsFunctions.getNatchathiramTamilName(
                  natchathiram: _profileData["natchathiram"].toString()),
            ),
            profileData(
              context,
              "Profession",
              _profileData["profession_name"].toString(),
            ),
            profileData(
              context,
              "Wife Name",
              _profileData["wife_name"].toString(),
            ),
            profileData(
              context,
              "Wife Education",
              _profileData["wife_education"].toString(),
            ),
            profileData(
              context,
              "Father ID",
              _profileData["father_id"].toString(),
            ),
            profileData(
              context,
              "Family Order",
              _profileData["family_order"].toString(),
            ),
            profileData(
              context,
              "Aadhaar Number",
              _profileData["aadhaar_number"].toString(),
            ),
            profileData(
              context,
              "Introducer ID",
              _profileData["introducer_id"].toString(),
            ),
            profileData(
              context,
              "Introducer Relationship",
              _profileData["introducer_relationship"].toString(),
            ),
            profileData(
              context,
              "Status",
              _profileData["status_name"].toString(),
            ),
            profileData(
              context,
              "Phone Number",
              _profileData["phone_number"].toString(),
            ),
            profileData(
              context,
              "Mobile Number",
              _profileData["mobile_number"].toString(),
            ),
            profileData(
              context,
              "Date Of Joining",
              _profileData["date_of_joining"].toString(),
            ),
            profileData(
              context,
              "Date of Deletion",
              _profileData["date_of_deletion"].toString(),
            ),
            profileData(
              context,
              "Date of Rejoin",
              _profileData["date_of_rejoin"].toString(),
            ),
          ],
        ),
      ),
    );
  }
}
