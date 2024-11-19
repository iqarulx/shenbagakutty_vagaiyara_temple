import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import '/view/view.dart';

class ProfessionModel {
  final String profId;
  final String profName;

  ProfessionModel({required this.profId, required this.profName});
}

class Profession extends StatefulWidget {
  final List profData;
  const Profession({super.key, required this.profData});

  @override
  State<Profession> createState() => _ProfessionState();
}

class _ProfessionState extends State<Profession> {
  TextEditingController searchForm = TextEditingController();
  List<ProfessionModel> rasi = [];
  List<ProfessionModel> allRasi = [];
  Future? rasiHandler;

  @override
  void initState() {
    _init();
    super.initState();
  }

  _init() {
    for (var i in widget.profData) {
      rasi.add(
          ProfessionModel(profId: i["profession_id"], profName: i["name"]));
    }
    allRasi = rasi;
    setState(() {});
  }

  void resetSearch() {
    setState(() {
      rasi = List.from(allRasi);
    });
  }

  searchSite() {
    List<ProfessionModel> filteredList = allRasi.where((site) {
      return site.profName
          .toLowerCase()
          .contains(searchForm.text.toLowerCase());
    }).toList();
    setState(() {
      rasi = filteredList;
    });
  }

  @override
  Widget build(BuildContext context) {
    return ClipRRect(
      borderRadius: const BorderRadius.only(
        topLeft: Radius.circular(30),
        topRight: Radius.circular(30),
      ),
      child: Padding(
        padding: const EdgeInsets.only(left: 15, bottom: 15, right: 15),
        child: Column(
          children: [
            Row(
              children: [
                Expanded(
                  child: FormFields(
                    controller: searchForm,
                    hintText: "Search profession",
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
                        size: 0.8, widget: const ProfessionAdd());
                  },
                )
              ],
            ),
            Flexible(
              child: ListView.separated(
                primary: false,
                shrinkWrap: true,
                itemCount: rasi.length,
                separatorBuilder: (context, index) {
                  return Divider(
                    color: Colors.grey.shade300,
                  );
                },
                itemBuilder: (context, index) {
                  return ListTile(
                    onTap: () {
                      Navigator.pop(context, {
                        "code": rasi[index].profName,
                        "id": rasi[index].profId
                      });
                    },
                    title: Text(
                      rasi[index].profName,
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
      ),
    );
  }
}
