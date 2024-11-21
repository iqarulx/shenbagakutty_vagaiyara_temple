import 'package:flutter/material.dart';

import '/functions/functions.dart';
import '/view/view.dart';

class States extends StatefulWidget {
  final String index;
  const States({super.key, required this.index});

  @override
  State<States> createState() => _StatesState();
}

class _StatesState extends State<States> {
  TextEditingController searchForm = TextEditingController();
  List<String> state = [];
  List<String> allState = [];
  Future? stateHandler;

  void resetSearch() {
    setState(() {
      state = List.from(allState);
    });
  }

  searchSite() {
    List<String> filteredList = allState.where((site) {
      return site.toLowerCase().contains(searchForm.text.toLowerCase());
    }).toList();
    setState(() {
      state = filteredList;
    });
  }

  @override
  void initState() {
    stateHandler = getCountry();
    super.initState();
  }

  getCountry() async {
    try {
      var data = await UtilsFunctions.getState(index: widget.index);
      state = data;
      allState = state;
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
        future: stateHandler,
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
                  FormFields(
                    controller: searchForm,
                    hintText: "Search state",
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
                      itemCount: state.length,
                      separatorBuilder: (context, index) {
                        return Divider(
                          color: Colors.grey.shade300,
                        );
                      },
                      itemBuilder: (context, index) {
                        return ListTile(
                          onTap: () {
                            Navigator.pop(context, state[index]);
                          },
                          title: Text(
                            state[index],
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
