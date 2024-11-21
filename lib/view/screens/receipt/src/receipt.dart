import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:intl/intl.dart';
import '/utils/utils.dart';
import '/functions/functions.dart';
import '/view/view.dart';
import '/model/model.dart';
import './detail_view.dart';
import './list_option.dart';
import './pdf_view.dart';
import './sheets/sheets.dart';
import './add_receipt.dart';

class Receipt extends StatefulWidget {
  const Receipt({super.key});

  @override
  State<Receipt> createState() => _ReceiptState();
}

class _ReceiptState extends State<Receipt> {
  @override
  void initState() {
    _pageNo.text = "1";
    _pageLimit.text = "10";
    _fromDate.text = DateFormat('yyyy-MM-dd').format(getMonthFirstDate());
    _toDate.text = DateFormat('yyyy-MM-dd').format(getMonthLastDate());
    _receiptHandler = _init();
    _addListeners([
      _member,
      _creatorMember,
      _nonMember,
      _rCategory,
      _rType,
    ]);

    super.initState();
  }

  @override
  void dispose() {
    for (var controller in [
      _member,
      _creatorMember,
      _nonMember,
      _rCategory,
      _rType,
    ]) {
      controller.dispose();
    }

    super.dispose();
  }

  //***************** UI *********************/
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: RefreshIndicator(
        onRefresh: () async {
          _receiptHandler = _init();
          setState(() {});
        },
        child: ListView(
          children: [_filters(context), _decorText(context), _listView()],
        ),
      ),
      appBar: _appbar(context),
    );
  }

  AppBar _appbar(BuildContext context) {
    return AppBar(
      leading: IconButton(
        tooltip: "Back",
        icon: const Icon(Icons.arrow_back_ios_new_rounded),
        onPressed: () {
          Navigator.pop(context);
        },
      ),
      title: const Text("Receipt"),
      actions: [
        IconButton(
          tooltip: "Create Receipt",
          icon: const Icon(Icons.add_rounded, size: 30),
          onPressed: () {
            _createReceipt();
          },
        )
      ],
    );
  }

  FutureBuilder<dynamic> _listView() {
    return FutureBuilder(
      future: _receiptHandler,
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return futureWaitingLoading();
        } else if (snapshot.hasError) {
          return ErrorWidget(snapshot.error!);
        } else {
          return ListView.separated(
            padding: const EdgeInsets.only(bottom: 10, left: 10, right: 10),
            primary: false,
            shrinkWrap: true,
            separatorBuilder: (context, index) {
              return Divider(
                color: Colors.grey.shade300,
              );
            },
            itemCount: rList.length,
            itemBuilder: (context, index) {
              if (rList.isNotEmpty) {
                return ListTile(
                  onTap: () async {
                    var r = await Sheet.showSheet(context,
                        size: 0.2, widget: const ListOption());
                    if (r != null) {
                      if (r == 1) {
                        await Sheet.showSheet(context,
                            size: 0.9, widget: DetailView(model: rList[index]));
                      } else if (r == 2) {
                        Navigator.push(
                          context,
                          CupertinoPageRoute(
                            builder: (context) => PdfView(
                              uri: rList[index].receiptPrintUrl,
                              name: "Receipt - ${rList[index].receiptNumber}",
                            ),
                          ),
                        );
                      }
                    }
                  },
                  tileColor: AppColors.pureWhiteColor,
                  leading: Container(
                    height: 25,
                    width: 25,
                    decoration: BoxDecoration(
                      color: Colors.grey.shade300,
                      shape: BoxShape.circle,
                    ),
                    child: Center(
                      child: Text(
                        "${index + 1}",
                        style: const TextStyle(
                          color: Colors.grey,
                          fontSize: 10,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                    ),
                  ),
                  title: Text(
                    "${rList[index].statusBaseMemberId} - ${rList[index].memberName}",
                    style: Theme.of(context).textTheme.bodyLarge,
                    overflow: TextOverflow.ellipsis,
                  ),
                  subtitle: RichText(
                    text: TextSpan(
                      text: "Amount : ",
                      style: Theme.of(context)
                          .textTheme
                          .bodySmall!
                          .copyWith(color: AppColors.blackColor),
                      children: [
                        TextSpan(
                          text: rList[index].amount,
                          style: Theme.of(context)
                              .textTheme
                              .bodySmall!
                              .copyWith(fontWeight: FontWeight.bold),
                        ),
                      ],
                    ),
                  ),
                  trailing: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text(
                        rList[index].receiptDate,
                        style: Theme.of(context)
                            .textTheme
                            .bodySmall!
                            .copyWith(fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(height: 3),
                      Container(
                        padding: const EdgeInsets.all(5),
                        decoration: BoxDecoration(
                          color: Color(int.parse(rList[index].colorCode)),
                          borderRadius: BorderRadius.circular(10),
                        ),
                        child: Text(rList[index].receiptNumber,
                            style: TextStyle(color: AppColors.pureWhiteColor)),
                      )
                    ],
                  ),
                );
              }
              return noData();
            },
          );
        }
      },
    );
  }

  Column _decorText(BuildContext context) {
    return Column(
      children: [
        Padding(
          padding: const EdgeInsets.only(top: 10, right: 10, left: 10),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(
                "Receipt List",
                style: Theme.of(context).textTheme.titleMedium,
              ),
              if (_totalReceipt != null)
                Text(
                  "Total Records : $_totalReceipt",
                  style: Theme.of(context).textTheme.titleMedium!.copyWith(
                        color: AppColors.primaryColor,
                      ),
                ),
            ],
          ),
        ),
        const SizedBox(height: 5),
        const Padding(
            padding: EdgeInsets.only(left: 10, right: 10), child: Divider()),
        const SizedBox(height: 5),
      ],
    );
  }

  Padding _filters(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(top: 10, left: 10, right: 10),
      child: SingleChildScrollView(
        scrollDirection: Axis.horizontal,
        child: Row(
          children: [
            SizedBox(
              width: 100,
              child: FormFields(
                suffixIcon: const Icon(Icons.arrow_drop_down_rounded),
                controller: _pageNo,
                hintText: "Page No",
                onTap: () async {
                  var value = await Sheet.showSheet(
                    context,
                    size: 0.9,
                    widget: PageNo(
                      total: int.parse(_totalReceipt ?? "0"),
                      pageLimit: int.parse(_pageLimit.text),
                    ),
                  );
                  if (value != null) {
                    _pageNo.text = value;
                    _receiptHandler = _init();
                  }
                },
                readOnly: true,
              ),
            ),
            const SizedBox(width: 5),
            SizedBox(
              width: 100,
              child: FormFields(
                suffixIcon: const Icon(Icons.arrow_drop_down_rounded),
                controller: _pageLimit,
                hintText: "Page Limit",
                onTap: () async {
                  var value = await Sheet.showSheet(
                    context,
                    size: 0.5,
                    widget: const PageLimit(),
                  );
                  if (value != null) {
                    _pageLimit.text = value;
                    _receiptHandler = _init();
                  }
                },
                readOnly: true,
              ),
            ),
            const SizedBox(width: 5),
            SizedBox(
              width: 200,
              child: FormFields(
                suffixIcon: _rCategory.text.isEmpty
                    ? const Icon(Icons.arrow_drop_down_rounded)
                    : IconButton(
                        tooltip: "Clear",
                        onPressed: () {
                          _rCategory.clear();
                          selectedRCategoryId = null;
                          _receiptHandler = _init();
                          setState(() {});
                        },
                        icon: Icon(
                          Iconsax.close_circle,
                          color: AppColors.primaryColor,
                        ),
                      ),
                controller: _rCategory,
                hintText: "Category",
                onTap: () async {
                  var value = await Sheet.showSheet(context,
                      size: 0.5,
                      widget: RCategory(query: rData["receipt_category"]));
                  if (value != null) {
                    _rCategory.text = value["value"];
                    selectedRCategoryId = value["id"];
                    _receiptHandler = _init();
                  }
                },
                readOnly: true,
              ),
            ),
            const SizedBox(width: 5),
            SizedBox(
              width: 200,
              child: FormFields(
                suffixIcon: _rType.text.isEmpty
                    ? const Icon(Icons.arrow_drop_down_rounded)
                    : IconButton(
                        tooltip: "Clear",
                        onPressed: () {
                          _rType.clear();
                          selectedRTypeId = null;
                          _receiptHandler = _init();

                          setState(() {});
                        },
                        icon: Icon(
                          Iconsax.close_circle,
                          color: AppColors.primaryColor,
                        ),
                      ),
                controller: _rType,
                hintText: "Type",
                onTap: () async {
                  if (selectedRCategoryId != null) {
                    var value = await Sheet.showSheet(context,
                        size: 0.9,
                        widget: RTFC(
                          category: selectedRCategoryId ?? '',
                          receiptQuery: rData["receipt_category"],
                        ));
                    if (value != null) {
                      _rType.text = value["value"];
                      selectedRTypeId = value["id"];
                      _receiptHandler = _init();
                    }
                  } else {
                    var value = await Sheet.showSheet(context,
                        size: 0.9, widget: RType(query: rTList));
                    if (value != null) {
                      _rType.text = value["value"];
                      selectedRTypeId = value["id"];
                      _receiptHandler = _init();
                    }
                  }
                },
                readOnly: true,
              ),
            ),
            const SizedBox(width: 5),
            SizedBox(
              width: 200,
              child: FormFields(
                suffixIcon: _member.text.isEmpty
                    ? const Icon(Icons.arrow_drop_down_rounded)
                    : IconButton(
                        tooltip: "Clear",
                        onPressed: () {
                          _member.clear();
                          selectedMemberId = null;
                          _receiptHandler = _init();

                          setState(() {});
                        },
                        icon: Icon(
                          Iconsax.close_circle,
                          color: AppColors.primaryColor,
                        ),
                      ),
                controller: _member,
                hintText: "Member",
                onTap: () async {
                  var value = await Sheet.showSheet(context,
                      size: 0.9,
                      widget: ListingMember(
                        query: mList,
                      ));
                  if (value != null) {
                    _member.text = value["value"];
                    selectedMemberId = value["id"];
                    _receiptHandler = _init();
                  }
                },
                readOnly: true,
              ),
            ),
            const SizedBox(width: 5),
            SizedBox(
              width: 200,
              child: FormFields(
                suffixIcon: _creatorMember.text.isEmpty
                    ? const Icon(Icons.arrow_drop_down_rounded)
                    : IconButton(
                        tooltip: "Clear",
                        onPressed: () {
                          _creatorMember.clear();
                          selectedCreatorMemberId = null;
                          _receiptHandler = _init();

                          setState(() {});
                        },
                        icon: Icon(
                          Iconsax.close_circle,
                          color: AppColors.primaryColor,
                        ),
                      ),
                controller: _creatorMember,
                hintText: "Creator Member",
                onTap: () async {
                  var value = await Sheet.showSheet(context,
                      size: 0.9,
                      widget: CreatorMember(
                        query: cMList,
                      ));
                  if (value != null) {
                    _creatorMember.text = value["value"];
                    selectedCreatorMemberId = value["id"];
                    _receiptHandler = _init();
                  }
                },
                readOnly: true,
              ),
            ),
            const SizedBox(width: 5),
            SizedBox(
              width: 200,
              child: FormFields(
                suffixIcon: _nonMember.text.isEmpty
                    ? const Icon(Icons.arrow_drop_down_rounded)
                    : IconButton(
                        tooltip: "Clear",
                        onPressed: () {
                          _nonMember.clear();
                          selectedNonMemberId = null;
                          _receiptHandler = _init();

                          setState(() {});
                        },
                        icon: Icon(
                          Iconsax.close_circle,
                          color: AppColors.primaryColor,
                        ),
                      ),
                controller: _nonMember,
                hintText: "Non Member",
                onTap: () async {
                  var value = await Sheet.showSheet(context,
                      size: 0.9,
                      widget: ListingMember(
                        query: rData["non_member"],
                      ));
                  if (value != null) {
                    _nonMember.text = value["value"];
                    selectedNonMemberId = value["id"];
                    _receiptHandler = _init();
                  }
                },
                readOnly: true,
              ),
            ),
            const SizedBox(width: 5),
            SizedBox(
              width: 200,
              child: FormFields(
                suffixIcon: const Icon(Iconsax.calendar_1),
                controller: _fromDate,
                hintText: "From Date",
                onTap: () => _fromPicker(),
                readOnly: true,
              ),
            ),
            const SizedBox(width: 5),
            SizedBox(
              width: 200,
              child: FormFields(
                suffixIcon: const Icon(Iconsax.calendar_1),
                controller: _toDate,
                hintText: "To Date",
                onTap: () => _toPicker(),
                readOnly: true,
              ),
            ),
            const SizedBox(width: 5),
            SizedBox(
              width: 200,
              child: FormFields(
                suffixIcon: const Icon(Iconsax.search_normal),
                controller: _search,
                hintText: "Search",
                onChanged: (String v) {
                  _receiptHandler = _init();
                },
              ),
            ),
          ],
        ),
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

  _init() async {
    try {
      setState(() {
        rList.clear();
        rData.clear();
        rTList.clear();
        cMList.clear();
      });
      var data = await ReceiptFunctions.listing(
        pageNo: _pageNo.text,
        pageLimit: _pageLimit.text,
        fromDate: _fromDate.text,
        toDate: _toDate.text,
        rCId: selectedRCategoryId,
        rTId: selectedRTypeId,
        mId: selectedMemberId,
        lMId: selectedMemberId,
        nMName: selectedNonMemberId,
        rC: selectedCreatorMemberId,
        searchText: _search.text,
      );

      if (data.isNotEmpty) {
        rData = data["head"];
        var d = data["body"];
        _totalReceipt = rData["receipt_count"].toString();
        for (var i in rData["receipt_type"]) {
          rTList.add(
            RTypeModel(
              aId: i["automatic_id"].toString(),
              id: i["receipt_type_id"].toString(),
              name: i["receipt_type_name"].toString(),
              inputs: i["inputs"] != null
                  ? (i["inputs"] as List).map((e) => e.toString()).toList()
                  : [],
            ),
          );
        }

        for (var i in rData["listing_member"]) {
          mList.add(
            MemberModel(
              id: i["id"].toString(),
              name: i["name"].toString(),
              mobileNumber: i["mobile_number"].toString(),
            ),
          );
        }
        for (var i in rData["create_member"]) {
          cMList.add(
            CreatorMemberModel(
              id: i["id"].toString(),
              name: i["name"].toString(),
            ),
          );
        }

        for (var i in d) {
          rList.add(
            ReceiptModel(
              creatorName: i["creator_name"].toString(),
              receiptId: i["receipt_id"].toString(),
              receiptDate: i["receipt_date"].toString(),
              receiptNumber: i["receipt_number"].toString(),
              colorCode: i["color_code"].toString(),
              statusBaseMemberId: i["status_base_member_id"].toString(),
              memberName: i["member_name"].toString(),
              yearAmount: i["year_amount"].toString(),
              poojaiFromDate: i["poojai_from_date"].toString(),
              poojaiToDate: i["poojai_to_date"].toString(),
              poojaiAmount: i["poojai_amount"].toString(),
              amount: i["amount"].toString(),
              description: i["description"].toString(),
              functionDate: i["function_date"].toString(),
              countForMudikanikai: i["count_for_mudikanikai"].toString(),
              countForKadhuKuthu: i["count_for_kadhu_kuthu"].toString(),
              funeralTo: i["funeral_to"].toString(),
              receiptTypeId: i["receipt_type_id"].toString(),
              receiptTypeName: i["receipt_type_name"].toString(),
              formName: i["form_name"].toString(),
              receiptPrintUrl: i["receipt_print_url"].toString(),
            ),
          );
        }
      }
      setState(() {});
    } catch (e) {
      Snackbar.showSnackBar(context, content: e.toString(), isSuccess: false);
    }
  }

  _fromPicker() async {
    final DateTime? picked = await datePicker(context);
    if (picked != null) {
      setState(() {
        _fromDate.text = DateFormat('yyyy-MM-dd').format(picked);
        _receiptHandler = _init();
      });
    }
  }

  _toPicker() async {
    final DateTime? picked = await datePicker(context);
    if (picked != null) {
      setState(() {
        _toDate.text = DateFormat('yyyy-MM-dd').format(picked);
        _receiptHandler = _init();
      });
    }
  }

  _createReceipt() async {
    if (rData.isNotEmpty) {
      if (mList.isNotEmpty && rList.isNotEmpty) {
        var r = await Navigator.push(
          context,
          CupertinoPageRoute(
            builder: (context) => AddReceipt(mList: mList, rList: rTList),
          ),
        );
        if (r != null) {
          if (r) {
            _receiptHandler = _init();
          }
        }
      }
    }
  }

  //***************** Variables *********************/
  late Future _receiptHandler;
  Map<String, dynamic> rData = {};
  List<ReceiptModel> rList = [];
  List<RTypeModel> rTList = [];
  List<MemberModel> mList = [];
  List<CreatorMemberModel> cMList = [];

  final TextEditingController _member = TextEditingController();
  final TextEditingController _creatorMember = TextEditingController();
  final TextEditingController _pageNo = TextEditingController();
  final TextEditingController _pageLimit = TextEditingController();
  final TextEditingController _nonMember = TextEditingController();
  final TextEditingController _rCategory = TextEditingController();
  final TextEditingController _rType = TextEditingController();
  final TextEditingController _fromDate = TextEditingController();
  final TextEditingController _toDate = TextEditingController();
  final TextEditingController _search = TextEditingController();

  String? selectedMemberId;
  String? selectedCreatorMemberId;
  String? selectedNonMemberId;
  String? selectedRCategoryId;
  String? selectedRTypeId;
  String? _totalReceipt;
}
