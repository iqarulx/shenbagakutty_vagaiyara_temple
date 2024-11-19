class Validation {
  Validation._internal();

  static final Validation _instance = Validation._internal();

  factory Validation() {
    return _instance;
  }

  static String? commonValidation(
      {required bool isMandatory,
      required String? input,
      required String label}) {
    if (isMandatory && input != null && input.isEmpty) {
      return "Please enter $label";
    } else {
      return null;
    }
  }

  static String? dropdownValidation(
      {required bool isMandatory,
      required String? input,
      required String label}) {
    if (isMandatory && input != null && input.isEmpty) {
      return "Please select $label";
    } else {
      return null;
    }
  }
}
