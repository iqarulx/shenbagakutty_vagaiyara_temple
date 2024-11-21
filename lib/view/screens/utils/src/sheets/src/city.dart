import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import '/functions/functions.dart';
import '/view/view.dart';

class City extends StatefulWidget {
  const City({super.key});

  @override
  State<City> createState() => _CityState();
}

class _CityState extends State<City> {
  TextEditingController searchForm = TextEditingController();
  List<String> city = [];
  List<String> allCity = [];
  Future? cityHandler;

  void resetSearch() {
    setState(() {
      city = List.from(allCity);
    });
  }

  searchSite() {
    List<String> filteredList = allCity.where((site) {
      return site.toLowerCase().contains(searchForm.text.toLowerCase());
    }).toList();
    setState(() {
      city = filteredList;
    });
  }

  @override
  void initState() {
    cityHandler = getCountry();
    super.initState();
  }

  getCountry() async {
    try {
      var data = await UtilsFunctions.getCity();
      city = data;
      allCity = city;
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
        future: cityHandler,
        builder: (context, snapshot) {
          if (snapshot.connectionState == ConnectionState.waiting) {
            return futureWaitingLoading(context);
          } else if (snapshot.hasError) {
            return ErrorWidget(snapshot.error!);
          } else {
            return Padding(
              padding: const EdgeInsets.only(left: 15, bottom: 15, right: 15),
              child: Column(
                children: [
                  Row(
                    children: [
                      Expanded(
                        child: FormFields(
                          controller: searchForm,
                          hintText: "Search city",
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
                      ),
                      IconButton(
                        tooltip: "Create New",
                        icon: const Icon(
                          Iconsax.add_circle,
                          size: 30,
                        ),
                        onPressed: () async {
                          await Sheet.showSheet(context,
                              size: 0.8, widget: const CityAdd());
                        },
                      )
                    ],
                  ),
                  Flexible(
                    child: ListView.separated(
                      primary: false,
                      shrinkWrap: true,
                      itemCount: city.length,
                      separatorBuilder: (context, index) {
                        return Divider(
                          color: Colors.grey.shade300,
                        );
                      },
                      itemBuilder: (context, index) {
                        return ListTile(
                          onTap: () {
                            Navigator.pop(context, city[index]);
                          },
                          title: Text(
                            city[index],
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
