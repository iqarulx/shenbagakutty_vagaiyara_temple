import 'dart:async';

import 'package:flutter/foundation.dart';
import 'package:flutter/widgets.dart';
import 'package:flutter_localizations/flutter_localizations.dart';
import 'package:intl/intl.dart' as intl;

import 'app_localizations_en.dart';
import 'app_localizations_ka.dart';
import 'app_localizations_ml.dart';
import 'app_localizations_ta.dart';
import 'app_localizations_te.dart';

/// Callers can lookup localized strings with an instance of AppLocalizations
/// returned by `AppLocalizations.of(context)`.
///
/// Applications need to include `AppLocalizations.delegate()` in their app's
/// `localizationDelegates` list, and the locales they support in the app's
/// `supportedLocales` list. For example:
///
/// ```dart
/// import 'src/app_localizations.dart';
///
/// return MaterialApp(
///   localizationsDelegates: AppLocalizations.localizationsDelegates,
///   supportedLocales: AppLocalizations.supportedLocales,
///   home: MyApplicationHome(),
/// );
/// ```
///
/// ## Update pubspec.yaml
///
/// Please make sure to update your pubspec.yaml to include the following
/// packages:
///
/// ```yaml
/// dependencies:
///   # Internationalization support.
///   flutter_localizations:
///     sdk: flutter
///   intl: any # Use the pinned version from flutter_localizations
///
///   # Rest of dependencies
/// ```
///
/// ## iOS Applications
///
/// iOS applications define key application metadata, including supported
/// locales, in an Info.plist file that is built into the application bundle.
/// To configure the locales supported by your app, you’ll need to edit this
/// file.
///
/// First, open your project’s ios/Runner.xcworkspace Xcode workspace file.
/// Then, in the Project Navigator, open the Info.plist file under the Runner
/// project’s Runner folder.
///
/// Next, select the Information Property List item, select Add Item from the
/// Editor menu, then select Localizations from the pop-up menu.
///
/// Select and expand the newly-created Localizations item then, for each
/// locale your application supports, add a new item and select the locale
/// you wish to add from the pop-up menu in the Value field. This list should
/// be consistent with the languages listed in the AppLocalizations.supportedLocales
/// property.
abstract class AppLocalizations {
  AppLocalizations(String locale) : localeName = intl.Intl.canonicalizedLocale(locale.toString());

  final String localeName;

  static AppLocalizations of(BuildContext context) {
    return Localizations.of<AppLocalizations>(context, AppLocalizations)!;
  }

  static const LocalizationsDelegate<AppLocalizations> delegate = _AppLocalizationsDelegate();

  /// A list of this localizations delegate along with the default localizations
  /// delegates.
  ///
  /// Returns a list of localizations delegates containing this delegate along with
  /// GlobalMaterialLocalizations.delegate, GlobalCupertinoLocalizations.delegate,
  /// and GlobalWidgetsLocalizations.delegate.
  ///
  /// Additional delegates can be added by appending to this list in
  /// MaterialApp. This list does not have to be used at all if a custom list
  /// of delegates is preferred or required.
  static const List<LocalizationsDelegate<dynamic>> localizationsDelegates = <LocalizationsDelegate<dynamic>>[
    delegate,
    GlobalMaterialLocalizations.delegate,
    GlobalCupertinoLocalizations.delegate,
    GlobalWidgetsLocalizations.delegate,
  ];

  /// A list of this localizations delegate's supported locales.
  static const List<Locale> supportedLocales = <Locale>[
    Locale('en'),
    Locale('ka'),
    Locale('ml'),
    Locale('ta'),
    Locale('te')
  ];

  /// No description provided for @projectTitle.
  ///
  /// In en, this message translates to:
  /// **'Shenbagakutty Vagaiyara'**
  String get projectTitle;

  /// No description provided for @apiErrorText.
  ///
  /// In en, this message translates to:
  /// **'Something went wrong! Please try again'**
  String get apiErrorText;

  /// No description provided for @logoutTitle.
  ///
  /// In en, this message translates to:
  /// **'Logout'**
  String get logoutTitle;

  /// No description provided for @logoutSubTitle.
  ///
  /// In en, this message translates to:
  /// **'Are you sure want to logout?'**
  String get logoutSubTitle;

  /// No description provided for @logoutSuccessMsg.
  ///
  /// In en, this message translates to:
  /// **'Logout Successfully'**
  String get logoutSuccessMsg;

  /// No description provided for @back.
  ///
  /// In en, this message translates to:
  /// **'Back'**
  String get back;

  /// No description provided for @clear.
  ///
  /// In en, this message translates to:
  /// **'Clear'**
  String get clear;

  /// No description provided for @select.
  ///
  /// In en, this message translates to:
  /// **'Select'**
  String get select;

  /// No description provided for @date.
  ///
  /// In en, this message translates to:
  /// **'Date'**
  String get date;

  /// No description provided for @edit.
  ///
  /// In en, this message translates to:
  /// **'Edit'**
  String get edit;

  /// No description provided for @contact.
  ///
  /// In en, this message translates to:
  /// **'Contact'**
  String get contact;

  /// No description provided for @contactInformation.
  ///
  /// In en, this message translates to:
  /// **'Contact Information'**
  String get contactInformation;

  /// No description provided for @mobile.
  ///
  /// In en, this message translates to:
  /// **'Mobile : {text}'**
  String mobile(Object text);

  /// No description provided for @email.
  ///
  /// In en, this message translates to:
  /// **'Email : {text}'**
  String email(Object text);

  /// No description provided for @addressInformation.
  ///
  /// In en, this message translates to:
  /// **'Address Information'**
  String get addressInformation;

  /// No description provided for @addressTitle1.
  ///
  /// In en, this message translates to:
  /// **'Arulmighu Hariharaputhra Ayyanar Temple'**
  String get addressTitle1;

  /// No description provided for @addressSubtitle1.
  ///
  /// In en, this message translates to:
  /// **'Kaliappa Nagar, Sivakasi, Tamil Nadu 626123'**
  String get addressSubtitle1;

  /// No description provided for @addressTitle2.
  ///
  /// In en, this message translates to:
  /// **'Arulmighu Shenbaga Vinayagar Temple'**
  String get addressTitle2;

  /// No description provided for @addressSubtitle2.
  ///
  /// In en, this message translates to:
  /// **'Sivakasi - Kalugumalai Rd, Kaliappa Nagar, Sivakasi, Tamil Nadu 626123'**
  String get addressSubtitle2;

  /// No description provided for @downloads.
  ///
  /// In en, this message translates to:
  /// **'Downloads'**
  String get downloads;

  /// No description provided for @deleteDownloads.
  ///
  /// In en, this message translates to:
  /// **'Delete Downloads'**
  String get deleteDownloads;

  /// No description provided for @deleteDownloadsTitle.
  ///
  /// In en, this message translates to:
  /// **'Delete'**
  String get deleteDownloadsTitle;

  /// No description provided for @deleteDownloadsSubtitle.
  ///
  /// In en, this message translates to:
  /// **'Are you sure want to delete all downloads?'**
  String get deleteDownloadsSubtitle;

  /// No description provided for @deleteDownloadsSuccessMsg.
  ///
  /// In en, this message translates to:
  /// **'Downloads deleted'**
  String get deleteDownloadsSuccessMsg;

  /// No description provided for @noFileName.
  ///
  /// In en, this message translates to:
  /// **'No file name'**
  String get noFileName;

  /// No description provided for @openFile.
  ///
  /// In en, this message translates to:
  /// **'Open File'**
  String get openFile;

  /// No description provided for @dashboard.
  ///
  /// In en, this message translates to:
  /// **'Dashboard'**
  String get dashboard;

  /// No description provided for @notification.
  ///
  /// In en, this message translates to:
  /// **'Notification'**
  String get notification;

  /// No description provided for @templePhotos.
  ///
  /// In en, this message translates to:
  /// **'Temple Photos'**
  String get templePhotos;

  /// No description provided for @mandapam.
  ///
  /// In en, this message translates to:
  /// **'Mandapam'**
  String get mandapam;

  /// No description provided for @booked.
  ///
  /// In en, this message translates to:
  /// **'Booked'**
  String get booked;

  /// No description provided for @today.
  ///
  /// In en, this message translates to:
  /// **'Today'**
  String get today;

  /// No description provided for @failedToLoadImage.
  ///
  /// In en, this message translates to:
  /// **'Failed to load image'**
  String get failedToLoadImage;

  /// No description provided for @childDetails.
  ///
  /// In en, this message translates to:
  /// **'Child Details'**
  String get childDetails;

  /// No description provided for @removeChild.
  ///
  /// In en, this message translates to:
  /// **'Remove Child'**
  String get removeChild;

  /// No description provided for @removeChildTitle.
  ///
  /// In en, this message translates to:
  /// **'Remove'**
  String get removeChildTitle;

  /// No description provided for @removeChildBody.
  ///
  /// In en, this message translates to:
  /// **'Are you sure want to remove child?'**
  String get removeChildBody;

  /// No description provided for @initial.
  ///
  /// In en, this message translates to:
  /// **'Intial'**
  String get initial;

  /// No description provided for @name.
  ///
  /// In en, this message translates to:
  /// **'Name'**
  String get name;

  /// No description provided for @gender.
  ///
  /// In en, this message translates to:
  /// **'Gender'**
  String get gender;

  /// No description provided for @male.
  ///
  /// In en, this message translates to:
  /// **'Male'**
  String get male;

  /// No description provided for @female.
  ///
  /// In en, this message translates to:
  /// **'Female'**
  String get female;

  /// No description provided for @dateOfBirth.
  ///
  /// In en, this message translates to:
  /// **'Date of Birth'**
  String get dateOfBirth;

  /// No description provided for @mobileNumber.
  ///
  /// In en, this message translates to:
  /// **'Mobile Number'**
  String get mobileNumber;

  /// No description provided for @rasi.
  ///
  /// In en, this message translates to:
  /// **'Rasi'**
  String get rasi;

  /// No description provided for @natchathiram.
  ///
  /// In en, this message translates to:
  /// **'Natchathiram'**
  String get natchathiram;

  /// No description provided for @education.
  ///
  /// In en, this message translates to:
  /// **'Education'**
  String get education;

  /// No description provided for @job.
  ///
  /// In en, this message translates to:
  /// **'Job'**
  String get job;

  /// No description provided for @marriageStatus.
  ///
  /// In en, this message translates to:
  /// **'Marrige Status'**
  String get marriageStatus;

  /// No description provided for @yes.
  ///
  /// In en, this message translates to:
  /// **'Yes'**
  String get yes;

  /// No description provided for @no.
  ///
  /// In en, this message translates to:
  /// **'No'**
  String get no;

  /// No description provided for @cancel.
  ///
  /// In en, this message translates to:
  /// **'Cancel'**
  String get cancel;

  /// No description provided for @confirm.
  ///
  /// In en, this message translates to:
  /// **'Confirm'**
  String get confirm;

  /// No description provided for @lifePartnerDetails.
  ///
  /// In en, this message translates to:
  /// **'Life Partner Details'**
  String get lifePartnerDetails;

  /// No description provided for @marriageDate.
  ///
  /// In en, this message translates to:
  /// **'Marriage Date'**
  String get marriageDate;

  /// No description provided for @profilePhoto.
  ///
  /// In en, this message translates to:
  /// **'Profile Photo'**
  String get profilePhoto;

  /// No description provided for @wifePhoto.
  ///
  /// In en, this message translates to:
  /// **'Wife Photo'**
  String get wifePhoto;

  /// No description provided for @familyPhoto.
  ///
  /// In en, this message translates to:
  /// **'Family Photo'**
  String get familyPhoto;

  /// No description provided for @country.
  ///
  /// In en, this message translates to:
  /// **'Country'**
  String get country;

  /// No description provided for @state.
  ///
  /// In en, this message translates to:
  /// **'State'**
  String get state;

  /// No description provided for @city.
  ///
  /// In en, this message translates to:
  /// **'City'**
  String get city;

  /// No description provided for @pincode.
  ///
  /// In en, this message translates to:
  /// **'Pincode'**
  String get pincode;

  /// No description provided for @address.
  ///
  /// In en, this message translates to:
  /// **'Address'**
  String get address;

  /// No description provided for @companyName.
  ///
  /// In en, this message translates to:
  /// **'Company Name'**
  String get companyName;

  /// No description provided for @remarks.
  ///
  /// In en, this message translates to:
  /// **'Remarks'**
  String get remarks;

  /// No description provided for @history.
  ///
  /// In en, this message translates to:
  /// **'History'**
  String get history;

  /// No description provided for @memberName.
  ///
  /// In en, this message translates to:
  /// **'Member Name'**
  String get memberName;

  /// No description provided for @profession.
  ///
  /// In en, this message translates to:
  /// **'Profession'**
  String get profession;

  /// No description provided for @wifeName.
  ///
  /// In en, this message translates to:
  /// **'Wife Name'**
  String get wifeName;

  /// No description provided for @wifeEducation.
  ///
  /// In en, this message translates to:
  /// **'Wife Education'**
  String get wifeEducation;

  /// No description provided for @phoneNumber.
  ///
  /// In en, this message translates to:
  /// **'Phone Number'**
  String get phoneNumber;

  /// No description provided for @adhaarNumber.
  ///
  /// In en, this message translates to:
  /// **'Adhaar Number'**
  String get adhaarNumber;

  /// No description provided for @wifeRasi.
  ///
  /// In en, this message translates to:
  /// **'Wife Rasi'**
  String get wifeRasi;

  /// No description provided for @wifeNatchathiram.
  ///
  /// In en, this message translates to:
  /// **'Wife Natchathiram'**
  String get wifeNatchathiram;

  /// No description provided for @fatherId.
  ///
  /// In en, this message translates to:
  /// **'Father Id'**
  String get fatherId;

  /// No description provided for @familyOrder.
  ///
  /// In en, this message translates to:
  /// **'Family Order'**
  String get familyOrder;

  /// No description provided for @fatherName.
  ///
  /// In en, this message translates to:
  /// **'Father Name'**
  String get fatherName;

  /// No description provided for @status.
  ///
  /// In en, this message translates to:
  /// **'Status'**
  String get status;

  /// No description provided for @introducerId.
  ///
  /// In en, this message translates to:
  /// **'Introducer Id'**
  String get introducerId;

  /// No description provided for @introducerRelationship.
  ///
  /// In en, this message translates to:
  /// **'Introducer Relationship'**
  String get introducerRelationship;

  /// No description provided for @dateOfJoining.
  ///
  /// In en, this message translates to:
  /// **'Date Of Joining'**
  String get dateOfJoining;

  /// No description provided for @dateOfDeletion.
  ///
  /// In en, this message translates to:
  /// **'Date of Deletion'**
  String get dateOfDeletion;

  /// No description provided for @dateOfRejoin.
  ///
  /// In en, this message translates to:
  /// **'Date of Rejoin'**
  String get dateOfRejoin;

  /// No description provided for @editProfile.
  ///
  /// In en, this message translates to:
  /// **'Edit Profile'**
  String get editProfile;

  /// No description provided for @personalDetails.
  ///
  /// In en, this message translates to:
  /// **'Personal Details'**
  String get personalDetails;

  /// No description provided for @memberImages.
  ///
  /// In en, this message translates to:
  /// **'Member Images'**
  String get memberImages;

  /// No description provided for @submit.
  ///
  /// In en, this message translates to:
  /// **'Submit'**
  String get submit;

  /// No description provided for @updateSuccessMsg.
  ///
  /// In en, this message translates to:
  /// **'Updated Successfully'**
  String get updateSuccessMsg;

  /// No description provided for @profile.
  ///
  /// In en, this message translates to:
  /// **'Profile'**
  String get profile;

  /// No description provided for @refresh.
  ///
  /// In en, this message translates to:
  /// **'Refresh'**
  String get refresh;

  /// No description provided for @memberId.
  ///
  /// In en, this message translates to:
  /// **'Member Id'**
  String get memberId;

  /// No description provided for @qrDetails.
  ///
  /// In en, this message translates to:
  /// **'Qr Details'**
  String get qrDetails;

  /// No description provided for @downloadQr.
  ///
  /// In en, this message translates to:
  /// **'Download Qr'**
  String get downloadQr;

  /// No description provided for @settings.
  ///
  /// In en, this message translates to:
  /// **'Settings'**
  String get settings;

  /// No description provided for @notificationSubtitle.
  ///
  /// In en, this message translates to:
  /// **'Push notifications while downloading file'**
  String get notificationSubtitle;

  /// No description provided for @appTheme.
  ///
  /// In en, this message translates to:
  /// **'App Theme'**
  String get appTheme;

  /// No description provided for @appThemeSubtitle.
  ///
  /// In en, this message translates to:
  /// **'Change Theme'**
  String get appThemeSubtitle;

  /// No description provided for @cache.
  ///
  /// In en, this message translates to:
  /// **'Cache'**
  String get cache;

  /// No description provided for @cacheSubtitle.
  ///
  /// In en, this message translates to:
  /// **'Clear app temporary files'**
  String get cacheSubtitle;

  /// No description provided for @clearTempFiles.
  ///
  /// In en, this message translates to:
  /// **'Clear Temp Files'**
  String get clearTempFiles;

  /// No description provided for @appVersion.
  ///
  /// In en, this message translates to:
  /// **'App Version'**
  String get appVersion;

  /// No description provided for @viewQrDetails.
  ///
  /// In en, this message translates to:
  /// **'View Qr Details'**
  String get viewQrDetails;

  /// No description provided for @menu.
  ///
  /// In en, this message translates to:
  /// **'Menu'**
  String get menu;

  /// No description provided for @receipt.
  ///
  /// In en, this message translates to:
  /// **'Receipt'**
  String get receipt;

  /// No description provided for @noRecords.
  ///
  /// In en, this message translates to:
  /// **'No records'**
  String get noRecords;

  /// No description provided for @noRecordsSubtitle.
  ///
  /// In en, this message translates to:
  /// **'No data available to show'**
  String get noRecordsSubtitle;

  /// No description provided for @language.
  ///
  /// In en, this message translates to:
  /// **'Language'**
  String get language;

  /// No description provided for @languageSubtitle.
  ///
  /// In en, this message translates to:
  /// **'Change Language'**
  String get languageSubtitle;

  /// No description provided for @receiptDate.
  ///
  /// In en, this message translates to:
  /// **'Receipt Date'**
  String get receiptDate;

  /// No description provided for @receiptType.
  ///
  /// In en, this message translates to:
  /// **'Receipt Type'**
  String get receiptType;

  /// No description provided for @type.
  ///
  /// In en, this message translates to:
  /// **'Type'**
  String get type;

  /// No description provided for @selectReceiptType.
  ///
  /// In en, this message translates to:
  /// **'Select Receipt Type'**
  String get selectReceiptType;

  /// No description provided for @member.
  ///
  /// In en, this message translates to:
  /// **'Member'**
  String get member;

  /// No description provided for @selectMember.
  ///
  /// In en, this message translates to:
  /// **'Select Member'**
  String get selectMember;

  /// No description provided for @amount.
  ///
  /// In en, this message translates to:
  /// **'Amount'**
  String get amount;

  /// No description provided for @emterAmount.
  ///
  /// In en, this message translates to:
  /// **'Enter amount'**
  String get emterAmount;

  /// No description provided for @funeralTo.
  ///
  /// In en, this message translates to:
  /// **'Funeral To'**
  String get funeralTo;

  /// No description provided for @enterFuneralTo.
  ///
  /// In en, this message translates to:
  /// **'Enter the funeral to'**
  String get enterFuneralTo;

  /// No description provided for @nonMemberName.
  ///
  /// In en, this message translates to:
  /// **'Non-Member Name'**
  String get nonMemberName;

  /// No description provided for @nonMemberError.
  ///
  /// In en, this message translates to:
  /// **'Enter the name or select member'**
  String get nonMemberError;

  /// No description provided for @functionDate.
  ///
  /// In en, this message translates to:
  /// **'Function Date'**
  String get functionDate;

  /// No description provided for @selectDate.
  ///
  /// In en, this message translates to:
  /// **'Select Date'**
  String get selectDate;

  /// No description provided for @countForMudiKanikai.
  ///
  /// In en, this message translates to:
  /// **'Count For MudiKanikai'**
  String get countForMudiKanikai;

  /// No description provided for @countError.
  ///
  /// In en, this message translates to:
  /// **'Enter the count'**
  String get countError;

  /// No description provided for @countForKathukuthu.
  ///
  /// In en, this message translates to:
  /// **'Count For Kadhu Kuthu'**
  String get countForKathukuthu;

  /// No description provided for @decription.
  ///
  /// In en, this message translates to:
  /// **'Description'**
  String get decription;

  /// No description provided for @enterDescription.
  ///
  /// In en, this message translates to:
  /// **'Enter the description'**
  String get enterDescription;

  /// No description provided for @yearAmount.
  ///
  /// In en, this message translates to:
  /// **'Year Amount'**
  String get yearAmount;

  /// No description provided for @enterYearAmount.
  ///
  /// In en, this message translates to:
  /// **'Enter the year amount'**
  String get enterYearAmount;

  /// No description provided for @poojaiFromDate.
  ///
  /// In en, this message translates to:
  /// **'Poojai From Date'**
  String get poojaiFromDate;

  /// No description provided for @poojaiFromDateError.
  ///
  /// In en, this message translates to:
  /// **'Enter the Poojai From Date'**
  String get poojaiFromDateError;

  /// No description provided for @poojaiToDate.
  ///
  /// In en, this message translates to:
  /// **'Poojai To Date'**
  String get poojaiToDate;

  /// No description provided for @poojaiToDateError.
  ///
  /// In en, this message translates to:
  /// **'Enter the Poojai To Date'**
  String get poojaiToDateError;

  /// No description provided for @poojaiAmount.
  ///
  /// In en, this message translates to:
  /// **'Poojai Amount'**
  String get poojaiAmount;

  /// No description provided for @poojaiAmountError.
  ///
  /// In en, this message translates to:
  /// **'Enter the Poojai Amount'**
  String get poojaiAmountError;

  /// No description provided for @addReceipt.
  ///
  /// In en, this message translates to:
  /// **'Add Receipt'**
  String get addReceipt;

  /// No description provided for @receiptSuccessMsg.
  ///
  /// In en, this message translates to:
  /// **'Receipt created successfully'**
  String get receiptSuccessMsg;

  /// No description provided for @receiptDetails.
  ///
  /// In en, this message translates to:
  /// **'Receipt Details'**
  String get receiptDetails;

  /// No description provided for @receiptNumber.
  ///
  /// In en, this message translates to:
  /// **'Receipt Number'**
  String get receiptNumber;

  /// No description provided for @lastOpenedBy.
  ///
  /// In en, this message translates to:
  /// **'Last Opened By'**
  String get lastOpenedBy;

  /// No description provided for @viewDetail.
  ///
  /// In en, this message translates to:
  /// **'View Detail'**
  String get viewDetail;

  /// No description provided for @print.
  ///
  /// In en, this message translates to:
  /// **'Print'**
  String get print;

  /// No description provided for @receiptPdf.
  ///
  /// In en, this message translates to:
  /// **'Receipt Pdf'**
  String get receiptPdf;

  /// No description provided for @downloadPdf.
  ///
  /// In en, this message translates to:
  /// **'Download Pdf'**
  String get downloadPdf;

  /// No description provided for @createReceipt.
  ///
  /// In en, this message translates to:
  /// **'Create Receipt'**
  String get createReceipt;

  /// No description provided for @receiptList.
  ///
  /// In en, this message translates to:
  /// **'Receipt List'**
  String get receiptList;

  /// No description provided for @totalRecords.
  ///
  /// In en, this message translates to:
  /// **'Total Records'**
  String get totalRecords;

  /// No description provided for @pageNo.
  ///
  /// In en, this message translates to:
  /// **'Page No'**
  String get pageNo;

  /// No description provided for @pageLimit.
  ///
  /// In en, this message translates to:
  /// **'Page Limit'**
  String get pageLimit;

  /// No description provided for @category.
  ///
  /// In en, this message translates to:
  /// **'Category'**
  String get category;

  /// No description provided for @creatorMember.
  ///
  /// In en, this message translates to:
  /// **'Creator Member'**
  String get creatorMember;

  /// No description provided for @nonMember.
  ///
  /// In en, this message translates to:
  /// **'Non Member'**
  String get nonMember;

  /// No description provided for @fromDate.
  ///
  /// In en, this message translates to:
  /// **'From Date'**
  String get fromDate;

  /// No description provided for @toDate.
  ///
  /// In en, this message translates to:
  /// **'To Date'**
  String get toDate;

  /// No description provided for @search.
  ///
  /// In en, this message translates to:
  /// **'Search'**
  String get search;

  /// No description provided for @partnerName.
  ///
  /// In en, this message translates to:
  /// **'Partner Name'**
  String get partnerName;

  /// No description provided for @partnerEducation.
  ///
  /// In en, this message translates to:
  /// **'Partner Education'**
  String get partnerEducation;

  /// No description provided for @partnerBirthDate.
  ///
  /// In en, this message translates to:
  /// **'Partner Birth Date'**
  String get partnerBirthDate;

  /// No description provided for @partnerRasi.
  ///
  /// In en, this message translates to:
  /// **'Partner Rasi'**
  String get partnerRasi;

  /// No description provided for @partnerNatchathiram.
  ///
  /// In en, this message translates to:
  /// **'Partner Natchathiram'**
  String get partnerNatchathiram;
}

class _AppLocalizationsDelegate extends LocalizationsDelegate<AppLocalizations> {
  const _AppLocalizationsDelegate();

  @override
  Future<AppLocalizations> load(Locale locale) {
    return SynchronousFuture<AppLocalizations>(lookupAppLocalizations(locale));
  }

  @override
  bool isSupported(Locale locale) => <String>['en', 'ka', 'ml', 'ta', 'te'].contains(locale.languageCode);

  @override
  bool shouldReload(_AppLocalizationsDelegate old) => false;
}

AppLocalizations lookupAppLocalizations(Locale locale) {


  // Lookup logic when only language code is specified.
  switch (locale.languageCode) {
    case 'en': return AppLocalizationsEn();
    case 'ka': return AppLocalizationsKa();
    case 'ml': return AppLocalizationsMl();
    case 'ta': return AppLocalizationsTa();
    case 'te': return AppLocalizationsTe();
  }

  throw FlutterError(
    'AppLocalizations.delegate failed to load unsupported locale "$locale". This is likely '
    'an issue with the localizations generation tool. Please file an issue '
    'on GitHub with a reproducible sample app and the gen-l10n configuration '
    'that was used.'
  );
}
