import 'package:flutter/material.dart';
import '/functions/functions.dart';
import '/view/view.dart';

class MandapamModel {
  final String name;
  final String id;

  MandapamModel({required this.name, required this.id});
}

class MandapamSheet extends StatefulWidget {
  const MandapamSheet({super.key});

  @override
  State<MandapamSheet> createState() => _MandapamSheetState();
}

class _MandapamSheetState extends State<MandapamSheet> {
  TextEditingController searchForm = TextEditingController();
  List<MandapamModel> mandapam = [];
  List<MandapamModel> allMandapam = [];
  Future? mandapamHandler;

  void resetSearch() {
    setState(() {
      mandapam = List.from(allMandapam);
    });
  }

  searchSite() {
    List<MandapamModel> filteredList = allMandapam.where((site) {
      return site.name.toLowerCase().contains(searchForm.text.toLowerCase());
    }).toList();
    setState(() {
      mandapam = filteredList;
    });
  }

  @override
  void initState() {
    mandapamHandler = getCountry();
    super.initState();
  }

  getCountry() async {
    try {
      var d = await MandapamFunctions.getMandapams();
      var data = d["body"];
      for (var i = 0; i < data.length; i++) {
        mandapam.add(
            MandapamModel(id: data[i]["mandapam_id"], name: data[i]["name"]));
      }
      allMandapam = mandapam;
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
        future: mandapamHandler,
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
                    hintText: "Search mandapam",
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
                      itemCount: mandapam.length,
                      separatorBuilder: (context, index) {
                        return Divider(
                          color: Colors.grey.shade300,
                        );
                      },
                      itemBuilder: (context, index) {
                        return ListTile(
                          onTap: () {
                            Navigator.pop(context, {
                              "name": mandapam[index].name,
                              "id": mandapam[index].id
                            });
                          },
                          title: Text(
                            mandapam[index].name,
                            style: const TextStyle(color: Colors.black),
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
