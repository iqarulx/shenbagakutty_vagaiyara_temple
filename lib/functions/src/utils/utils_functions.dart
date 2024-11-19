import '/services/services.dart';

class UtilsFunctions {
  static Future createProfession({required String pName}) async {
    try {
      await UtilsService.createProfession(pName: pName);
    } catch (e) {
      throw e.toString();
    }
  }

  static Future createCity({required String cName}) async {
    try {
      await UtilsService.createCity(cName: cName);
    } catch (e) {
      throw e.toString();
    }
  }

  static Future<List<String>> getCountry() async {
    try {
      List<String> countryList = [];
      var data = await CountryService.getCountry();
      if (data.isNotEmpty) {
        var d = data["body"];
        for (var i in d) {
          countryList.add(i.toString());
        }
      }
      return countryList;
    } catch (e) {
      throw e.toString();
    }
  }

  static Future<List<String>> getState({required String index}) async {
    try {
      List<String> stateList = [];
      var data = await CountryService.getStates(index: index);
      if (data.isNotEmpty) {
        var d = data["body"];
        for (var i in d) {
          stateList.add(i.toString());
        }
      }
      return stateList;
    } catch (e) {
      throw e.toString();
    }
  }

  static Future<List<String>> getCity() async {
    try {
      List<String> cityList = [];
      var data = await CountryService.getCity();
      if (data.isNotEmpty) {
        var d = data["body"];
        for (var i in d) {
          cityList.add(i.toString());
        }
      }
      return cityList;
    } catch (e) {
      throw e.toString();
    }
  }

  static String getRasiTamilName({required String rasi}) {
    var rasiList = {
      "Aries": "மேஷம்",
      "Taurus": "ரிஷபம்",
      "Gemini": "மிதுனம்",
      "Cancer": "கடகம்",
      "Leo": "சிம்மம்",
      "Virgo": "கன்னி",
      "Libra": "துலாம்",
      "Scorpio": "விருச்சிகம்",
      "Sagittarius": "தனுசு",
      "Capricorn": "மகரம்",
      "Aquarius": "கும்பம்",
      "Pisces": "மீனம்"
    };
    return rasiList[rasi] ?? '';
  }

  static String getRasiEnglishName({required String rasi}) {
    var rasiList = {
      "மேஷம்": "Aries",
      "ரிஷபம்": "Taurus",
      "மிதுனம்": "Gemini",
      "கடகம்": "Cancer",
      "சிம்மம்": "Leo",
      "கன்னி": "Virgo",
      "துலாம்": "Libra",
      "விருச்சிகம்": "Scorpio",
      "தனுசு": "Sagittarius",
      "மகரம்": "Capricorn",
      "கும்பம்": "Aquarius",
      "மீனம்": "Pisces"
    };

    return rasiList[rasi] ?? '';
  }

  static String getNatchathiramTamilName({required String natchathiram}) {
    var natchathiramList = {
      "Aswinini": "அசுவினி",
      "Barani": "பரணி",
      "Krithikai": "கிருத்திகை",
      "Rohini": "ரோகிணி",
      "Mirukasheerisham": "மிருகசிரீஷம்",
      "Tiruvadhirai": "திருவாதிரை",
      "Punarpoosam": "புனர்பூசம்",
      "Poosam": "பூசம்",
      "Ayilyam": "ஆயில்யம்",
      "Makam": "மகம்",
      "Pooram": "பூரம்",
      "Uthiram": "உத்திரம்",
      "Astham": "அஸ்தம்",
      "Chithirai": "சித்திரை",
      "Swathi": "சுவாதி",
      "Vishaakam": "விசாகம்",
      "Anushyam": "அனுஷம்",
      "Keattai": "கேட்டை",
      "Moolam": "முலம்",
      "Pooradam": "பூராடம்",
      "Uthiraadam": "உத்திராடம்",
      "Thiruvonam": "திருவோணம்",
      "Avittam": "அவிட்டம்",
      "Sathayam": "சதயம்",
      "Poorattathi": "பூரட்டாதி",
      "Uthiraadathi": "உத்திரட்டாதி",
      "Revathi": "ரேவதி"
    };
    return natchathiramList[natchathiram] ?? '';
  }

  static String getEnglishNatchathiramName({required String natchathiram}) {
    var natchathiramList = {
      "அசுவினி": "Aswinini",
      "பரணி": "Barani",
      "கிருத்திகை": "Krithikai",
      "ரோகிணி": "Rohini",
      "மிருகசிரீஷம்": "Mirukasheerisham",
      "திருவாதிரை": "Tiruvadhirai",
      "புனர்பூசம்": "Punarpoosam",
      "பூசம்": "Poosam",
      "ஆயில்யம்": "Ayilyam",
      "மகம்": "Makam",
      "பூரம்": "Pooram",
      "உத்திரம்": "Uthiram",
      "அஸ்தம்": "Astham",
      "சித்திரை": "Chithirai",
      "சுவாதி": "Swathi",
      "விசாகம்": "Vishaakam",
      "அனுஷம்": "Anushyam",
      "கேட்டை": "Keattai",
      "முலம்": "Moolam",
      "பூராடம்": "Pooradam",
      "உத்திராடம்": "Uthiraadam",
      "திருவோணம்": "Thiruvonam",
      "அவிட்டம்": "Avittam",
      "சதயம்": "Sathayam",
      "பூரட்டாதி": "Poorattathi",
      "உத்திரட்டாதி": "Uthiraadathi",
      "ரேவதி": "Revathi"
    };
    return natchathiramList[natchathiram] ?? '';
  }
}
