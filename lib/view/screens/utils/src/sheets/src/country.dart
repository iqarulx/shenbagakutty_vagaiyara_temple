import 'package:flutter/material.dart';
import '/functions/functions.dart';
import '/view/view.dart';

class CountryModel {
  final String name;
  final int index;

  CountryModel({required this.name, required this.index});
}

class Country extends StatefulWidget {
  const Country({super.key});

  @override
  State<Country> createState() => _CountryState();
}

class _CountryState extends State<Country> {
  TextEditingController searchForm = TextEditingController();
  List<CountryModel> country = [];
  List<CountryModel> allCountry = [];
  Future? countryHandler;

  void resetSearch() {
    setState(() {
      country = List.from(allCountry);
    });
  }

  searchSite() {
    List<CountryModel> filteredList = allCountry.where((site) {
      return site.name.toLowerCase().contains(searchForm.text.toLowerCase());
    }).toList();
    setState(() {
      country = filteredList;
    });
  }

  @override
  void initState() {
    countryHandler = getCountry();
    super.initState();
  }

  getCountry() async {
    try {
      var data = await UtilsFunctions.getCountry();
      for (var i = 0; i < data.length; i++) {
        country.add(CountryModel(index: i + 1, name: data[i]));
      }
      allCountry = country;
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
        future: countryHandler,
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
                    hintText: "Search country",
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
                      itemCount: country.length,
                      separatorBuilder: (context, index) {
                        return Divider(
                          color: Colors.grey.shade300,
                        );
                      },
                      itemBuilder: (context, index) {
                        return ListTile(
                          onTap: () {
                            Navigator.pop(context, {
                              "name": country[index].name,
                              "index": country[index].index
                            });
                          },
                          title: Text(
                            country[index].name,
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
