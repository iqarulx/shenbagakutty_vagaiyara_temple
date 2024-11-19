import 'package:flutter/material.dart';
import '/view/view.dart';

class RasiModel {
  final String tamilName;
  final String englishName;

  RasiModel({required this.tamilName, required this.englishName});
}

class Rasi extends StatefulWidget {
  const Rasi({super.key});

  @override
  State<Rasi> createState() => _RasiState();
}

class _RasiState extends State<Rasi> {
  TextEditingController searchForm = TextEditingController();
  List<RasiModel> rasi = [
    RasiModel(englishName: "Aries", tamilName: "மேஷம்"),
    RasiModel(englishName: "Taurus", tamilName: "ரிஷபம்"),
    RasiModel(englishName: "Gemini", tamilName: "மிதுனம்"),
    RasiModel(englishName: "Cancer", tamilName: "கடகம்"),
    RasiModel(englishName: "Leo", tamilName: "சிம்மம்"),
    RasiModel(englishName: "Virgo", tamilName: "கன்னி"),
    RasiModel(englishName: "Libra", tamilName: "துலாம்"),
    RasiModel(englishName: "Scorpio", tamilName: "விருச்சிகம்"),
    RasiModel(englishName: "Sagittarius", tamilName: "தனுசு"),
    RasiModel(englishName: "Capricorn", tamilName: "மகரம்"),
    RasiModel(englishName: "Aquarius", tamilName: "கும்பம்"),
    RasiModel(englishName: "Pisces", tamilName: "மீனம்"),
  ];
  List<RasiModel> allRasi = [
    RasiModel(englishName: "Aries", tamilName: "மேஷம்"),
    RasiModel(englishName: "Taurus", tamilName: "ரிஷபம்"),
    RasiModel(englishName: "Gemini", tamilName: "மிதுனம்"),
    RasiModel(englishName: "Cancer", tamilName: "கடகம்"),
    RasiModel(englishName: "Leo", tamilName: "சிம்மம்"),
    RasiModel(englishName: "Virgo", tamilName: "கன்னி"),
    RasiModel(englishName: "Libra", tamilName: "துலாம்"),
    RasiModel(englishName: "Scorpio", tamilName: "விருச்சிகம்"),
    RasiModel(englishName: "Sagittarius", tamilName: "தனுசு"),
    RasiModel(englishName: "Capricorn", tamilName: "மகரம்"),
    RasiModel(englishName: "Aquarius", tamilName: "கும்பம்"),
    RasiModel(englishName: "Pisces", tamilName: "மீனம்"),
  ];
  Future? rasiHandler;

  void resetSearch() {
    setState(() {
      rasi = List.from(allRasi);
    });
  }

  searchSite() {
    List<RasiModel> filteredList = allRasi.where((site) {
      return site.englishName
              .toLowerCase()
              .contains(searchForm.text.toLowerCase()) ||
          site.tamilName.toLowerCase().contains(searchForm.text.toLowerCase());
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
            FormFields(
              controller: searchForm,
              hintText: "Search rasi",
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
                        "id": rasi[index].englishName,
                        "value": rasi[index].tamilName
                      });
                    },
                    title: Text(
                      rasi[index].tamilName,
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
