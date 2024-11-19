import 'package:flutter/material.dart';

import '/view/view.dart';

class NatchathiramModel {
  final String rasi;
  final String tamilName;
  final String englishName;

  NatchathiramModel(
      {required this.rasi, required this.tamilName, required this.englishName});
}

class Natchathiram extends StatefulWidget {
  final String rasi;

  const Natchathiram({super.key, required this.rasi});

  @override
  State<Natchathiram> createState() => _NatchathiramState();
}

class _NatchathiramState extends State<Natchathiram> {
  TextEditingController searchForm = TextEditingController();

  List<NatchathiramModel> natchathiramList = [
    NatchathiramModel(
        rasi: "Aries", englishName: "Aswinini", tamilName: "அசுவினி"),
    NatchathiramModel(rasi: "Aries", englishName: "Barani", tamilName: "பரணி"),
    NatchathiramModel(
        rasi: "Aries", englishName: "Krithikai", tamilName: "கிருத்திகை"),
    //
    NatchathiramModel(
        rasi: "Taurus", englishName: "Krithikai", tamilName: "கிருத்திகை"),
    NatchathiramModel(
        rasi: "Taurus", englishName: "Rohini", tamilName: "ரோகிணி"),
    NatchathiramModel(
        rasi: "Taurus",
        englishName: "Mirukasheerisham",
        tamilName: "மிருகசிரீஷம்"),
    //
    NatchathiramModel(
        rasi: "Gemini",
        englishName: "Mirukasheerisham",
        tamilName: "மிருகசிரீஷம்"),
    NatchathiramModel(
        rasi: "Gemini", englishName: "Tiruvadhirai", tamilName: "திருவாதிரை"),
    NatchathiramModel(
        rasi: "Gemini", englishName: "Punarpoosam", tamilName: "புனர்பூசம்"),
    //
    NatchathiramModel(
        rasi: "Cancer", englishName: "Punarpoosam", tamilName: "புனர்பூசம்"),
    NatchathiramModel(
        rasi: "Cancer", englishName: "Poosam", tamilName: "பூசம்"),
    NatchathiramModel(
        rasi: "Cancer", englishName: "Ayilyam", tamilName: "ஆயில்யம்"),
    //
    NatchathiramModel(rasi: "Leo", englishName: "Makam", tamilName: "மகம்"),
    NatchathiramModel(rasi: "Leo", englishName: "Pooram", tamilName: "பூரம்"),
    NatchathiramModel(
        rasi: "Leo", englishName: "Uthiram", tamilName: "உத்திரம்"),
    //
    NatchathiramModel(
        rasi: "Virgo", englishName: "Uthiram", tamilName: "உத்திரம்"),
    NatchathiramModel(
        rasi: "Virgo", englishName: "Astham", tamilName: "அஸ்தம்"),
    NatchathiramModel(
        rasi: "Virgo", englishName: "Chithirai", tamilName: "சித்திரை"),
    //
    NatchathiramModel(
        rasi: "Libra", englishName: "Chithirai", tamilName: "சித்திரை"),
    NatchathiramModel(
        rasi: "Libra", englishName: "Swathi", tamilName: "சுவாதி"),
    NatchathiramModel(
        rasi: "Libra", englishName: "Vishaakam", tamilName: "விசாகம்"),
    //
    NatchathiramModel(
        rasi: "Scorpio", englishName: "Vishaakam", tamilName: "விசாகம்"),
    NatchathiramModel(
        rasi: "Scorpio", englishName: "Anushyam", tamilName: "அனுஷம்"),
    NatchathiramModel(
        rasi: "Scorpio", englishName: "Keattai", tamilName: "கேட்டை"),
    //
    NatchathiramModel(
        rasi: "Sagittarius", englishName: "Moolam", tamilName: "முலம்"),
    NatchathiramModel(
        rasi: "Sagittarius", englishName: "Pooradam", tamilName: "பூராடம்"),
    NatchathiramModel(
        rasi: "Sagittarius",
        englishName: "Uthiraadam",
        tamilName: "உத்திராடம்"),
    //
    NatchathiramModel(
        rasi: "Capricorn", englishName: "Uthiraadam", tamilName: "உத்திராடம்"),
    NatchathiramModel(
        rasi: "Capricorn", englishName: "Thiruvonam", tamilName: "திருவோணம்"),
    NatchathiramModel(
        rasi: "Capricorn", englishName: "Avittam", tamilName: "அவிட்டம்"),
    //
    NatchathiramModel(
        rasi: "Aquarius", englishName: "Avittam", tamilName: "அவிட்டம்"),
    NatchathiramModel(
        rasi: "Aquarius", englishName: "Sathayam", tamilName: "சதயம்"),
    NatchathiramModel(
        rasi: "Aquarius", englishName: "Poorattathi", tamilName: "பூரட்டாதி"),
    //
    NatchathiramModel(
        rasi: "Pisces", englishName: "Poorattathi", tamilName: "பூரட்டாதி"),
    NatchathiramModel(
        rasi: "Pisces", englishName: "Uthiraadathi", tamilName: "உத்திரட்டாதி"),
    NatchathiramModel(
        rasi: "Pisces", englishName: "Revathi", tamilName: "ரேவதி"),
  ];
  List<NatchathiramModel> natchathirams = [];
  List<NatchathiramModel> allNatchathirams = [];

  @override
  void initState() {
    _init();
    super.initState();
  }

  _init() {
    var list = natchathiramList.where((element) {
      return element.rasi == widget.rasi;
    }).toList();

    natchathirams = list;
    allNatchathirams = natchathirams;

    setState(() {});
  }

  void resetSearch() {
    setState(() {
      natchathirams = List.from(allNatchathirams);
    });
  }

  searchSite() {
    List<NatchathiramModel> filteredList = allNatchathirams.where((site) {
      return site.tamilName
              .toLowerCase()
              .contains(searchForm.text.toLowerCase()) ||
          site.englishName
              .toLowerCase()
              .contains(searchForm.text.toLowerCase());
    }).toList();
    setState(() {
      natchathirams = filteredList;
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
              hintText: "Search natchathiram",
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
                itemCount: natchathirams.length,
                separatorBuilder: (context, index) {
                  return Divider(
                    color: Colors.grey.shade300,
                  );
                },
                itemBuilder: (context, index) {
                  return ListTile(
                    onTap: () {
                      Navigator.pop(context, {
                        "id": natchathirams[index].englishName,
                        "value": natchathirams[index].tamilName
                      });
                    },
                    title: Text(
                      natchathirams[index].tamilName,
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
