import 'package:flutter/material.dart';
import 'package:shenbagakutty_vagaiyara/model/model.dart';
import '/view/view.dart';

class ListingMember extends StatefulWidget {
  final List<MemberModel> query;
  const ListingMember({super.key, required this.query});
  @override
  State<ListingMember> createState() => _ListingMemberState();
}

class _ListingMemberState extends State<ListingMember> {
  TextEditingController searchForm = TextEditingController();
  List<MemberModel> data = [];
  List<MemberModel> allData = [];
  Future? dataHandler;

  void resetSearch() {
    setState(() {
      data = List.from(allData);
    });
  }

  searchSite() {
    List<MemberModel> filteredList = allData.where((site) {
      return site.mobileNumber.contains(searchForm.text.toLowerCase()) ||
          site.name.contains(searchForm.text.toLowerCase()) ||
          site.name.contains(searchForm.text.toUpperCase());
    }).toList();
    setState(() {
      data = filteredList;
    });
  }

  @override
  void initState() {
    dataHandler = get();
    super.initState();
  }

  get() async {
    try {
      for (var i in widget.query) {
        data.add(
          MemberModel(
            id: i.id.toString(),
            name: i.name.toString(),
            mobileNumber: i.mobileNumber.toString(),
          ),
        );
      }
      allData = data;
      setState(() {});
    } catch (e) {
      Snackbar.showSnackBar(context, content: e.toString(), isSuccess: false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: const BorderRadius.only(
        topLeft: Radius.circular(30),
        topRight: Radius.circular(30),
      ),
      child: FutureBuilder(
        future: dataHandler,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return futureWaitingLoading();
          } else if (snapshot.hasError) {
            return ErrorWidget(snapshot.error!);
          } else {
            return Padding(
              padding: const EdgeInsets.only(left: 15, bottom: 15, right: 15),
              child: Column(
                children: [
                  FormFields(
                    controller: searchForm,
                    hintText: "Search by name, mobile no",
                    onChanged: (value) {
                      searchSite();
                    },
                    suffixIcon: searchForm.text.isNotEmpty
                        ? TextButton(
                            onPressed: () {
                              searchForm.clear();
                              resetSearch();
                            },
                            child: Text(
                              "Clear",
                              style: TextStyle(
                                color: AppColors.greyColor,
                              ),
                            ),
                          )
                        : null,
                  ),
                  Flexible(
                    child: ListView.separated(
                      primary: false,
                      shrinkWrap: true,
                      itemCount: data.length,
                      separatorBuilder: (context, index) {
                        return Divider(
                          color: Colors.grey.shade300,
                        );
                      },
                      itemBuilder: (context, index) {
                        return ListTile(
                          onTap: () {
                            Navigator.pop(context, {
                              "id": data[index].id,
                              "value": data[index].name
                            });
                          },
                          title: Text(
                            data[index].name,
                            style: Theme.of(context).textTheme.bodyLarge,
                          ),
                          subtitle: Text(
                            data[index].mobileNumber.isNotEmpty
                                ? data[index].mobileNumber
                                : "-",
                            style: Theme.of(context).textTheme.bodySmall,
                          ),
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
                        );
                      },
                    ),
                  )
                ],
              ),
            );
          }
        },
      ),
    );
  }
}
