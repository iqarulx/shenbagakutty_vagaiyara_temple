import 'package:flutter/material.dart';
import 'package:iconsax/iconsax.dart';
import 'package:intl/intl.dart';
import 'package:syncfusion_flutter_calendar/calendar.dart';
import '/l10n/l10n.dart';
import '/utils/utils.dart';
import '/view/view.dart';
import '/functions/functions.dart';

class Mandapam extends StatefulWidget {
  const Mandapam({super.key});

  @override
  State<Mandapam> createState() => _MandapamState();
}

class _MandapamState extends State<Mandapam> {
  @override
  void initState() {
    _customDate.text = DateFormat('dd-MM-yyyy').format(DateTime.now());
    _selectedCustomDate = DateTime.now();
    _mandapamHandler = _init();
    _mandapam.addListener(() {
      setState(() {});
    });
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Padding(
        padding: const EdgeInsets.all(8.0),
        child: Column(
          children: [
            _fields(context),
            const SizedBox(height: 10),
            _legend(),
            _calendarView(),
          ],
        ),
      ),
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(Icons.arrow_back_ios_new_rounded),
          onPressed: () {
            Navigator.pop(context);
          },
          tooltip: AppLocalizations.of(context).back,
        ),
        title: Text(AppLocalizations.of(context).mandapam),
      ),
    );
  }

  Row _fields(BuildContext context) {
    return Row(
      children: [
        Expanded(
          child: FormFields(
            suffixIcon: _mandapam.text.isEmpty
                ? const Icon(Icons.arrow_drop_down_rounded)
                : IconButton(
                    tooltip: AppLocalizations.of(context).clear,
                    onPressed: () {
                      _mandapam.clear();
                      _selectedMandapamId = null;
                      _mandapamHandler = _init();
                      setState(() {});
                    },
                    icon: Icon(
                      Iconsax.close_circle,
                      color: Theme.of(context).primaryColor,
                    ),
                  ),
            controller: _mandapam,
            label: AppLocalizations.of(context).mandapam,
            hintText: AppLocalizations.of(context).select,
            onTap: () async {
              var value = await Sheet.showSheet(context,
                  size: 0.9, widget: const MandapamSheet());
              if (value != null) {
                _mandapam.text = value["name"];
                _selectedMandapamId = value["id"];
                _mandapamHandler = _init();
              }
            },
            readOnly: true,
          ),
        ),
        const SizedBox(width: 10),
        Expanded(
          child: FormFields(
            controller: _customDate,
            label: AppLocalizations.of(context).date,
            hintText: "dd-mm-yyyy",
            onTap: () => _customDatePicker(),
            readOnly: true,
          ),
        ),
      ],
    );
  }

  FutureBuilder<void> _calendarView() {
    return FutureBuilder(
      future: _mandapamHandler,
      builder: (context, snapshot) {
        if (snapshot.connectionState == ConnectionState.waiting) {
          return futureExpandedLoading(context);
        } else if (snapshot.hasError) {
          return ErrorWidget(snapshot.error!);
        } else {
          return SfCalendar(
            controller: _calendarController,
            todayHighlightColor: Colors.red.withOpacity(0.7),
            backgroundColor: AppColors.pureWhiteColor,
            headerStyle: CalendarHeaderStyle(
              backgroundColor: AppColors.pureWhiteColor,
              textAlign: TextAlign.start,
              textStyle: const TextStyle(fontWeight: FontWeight.bold),
            ),
            view: CalendarView.month,
            monthCellBuilder: (BuildContext context, MonthCellDetails details) {
              final bool isBooked = bookedDate.any((date) =>
                  date.year == details.date.year &&
                  date.month == details.date.month &&
                  date.day == details.date.day);

              final bool isToday = details.date.year == DateTime.now().year &&
                  details.date.month == DateTime.now().month &&
                  details.date.day == DateTime.now().day;

              return Container(
                margin: const EdgeInsets.all(4),
                alignment: Alignment.center,
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  color: isToday
                      ? Colors.red.withOpacity(0.7) // Highlight today's date
                      : isBooked
                          ? Theme.of(context).primaryColor
                          : null,
                  border: isBooked
                      ? Border.all(color: Theme.of(context).primaryColor)
                      : Border.all(color: Colors.transparent),
                ),
                child: Text(
                  details.date.day.toString(),
                  style: TextStyle(
                    color: isToday
                        ? AppColors
                            .pureWhiteColor // Text color for today's date
                        : isBooked
                            ? AppColors.pureWhiteColor
                            : AppColors.blackColor,
                  ),
                ),
              );
            },
            monthViewSettings: const MonthViewSettings(
              appointmentDisplayMode: MonthAppointmentDisplayMode.indicator,
            ),
          );
        }
      },
    );
  }

  Container _legend() {
    return Container(
      padding: const EdgeInsets.only(top: 10),
      decoration: BoxDecoration(color: AppColors.pureWhiteColor),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Row(
            children: [
              Icon(
                Icons.circle,
                size: 10,
                color: Theme.of(context).primaryColor,
              ),
              const SizedBox(width: 5),
              Text(AppLocalizations.of(context).booked),
            ],
          ),
          const SizedBox(width: 10),
          Row(
            children: [
              Icon(
                Icons.circle,
                size: 10,
                color: Colors.red.withOpacity(0.7),
              ),
              const SizedBox(width: 5),
              Text(AppLocalizations.of(context).today),
            ],
          )
        ],
      ),
    );
  }

  _customDatePicker() async {
    final DateTime? picked = await datePicker(context);
    if (picked != null) {
      _customDate.text = DateFormat('dd-MM-yyyy').format(picked);
      _selectedCustomDate = picked;
      _mandapamHandler = _init();
      setState(() {});
      _calendarController.displayDate = picked;
    }
  }

  Future<void> _init() async {
    try {
      var d = await MandapamFunctions.checkAvailablity(
        year: _selectedCustomDate?.year.toString() ?? '',
        month: _selectedCustomDate?.month.toString() ?? '',
        mandapamId: _selectedMandapamId ?? '',
        lastDate: monthLastDate(_selectedCustomDate?.month ?? 1),
      );
      if (d.isNotEmpty) {
        var data = d["head"]["booking_dates"];
        setState(() {
          bookingDates = (data as List).map((e) => e.toString()).toList();
          bookedDate = bookingDates
              .map((day) => DateTime(
                    _selectedCustomDate?.year ?? DateTime.now().year,
                    _selectedCustomDate?.month ?? DateTime.now().month,
                    int.tryParse(day) ?? 1,
                  ))
              .toList();
        });
      }
    } catch (e) {
      Snackbar.showSnackBar(context, content: e.toString(), isSuccess: false);
    }
  }

  final TextEditingController _customDate = TextEditingController();
  final TextEditingController _mandapam = TextEditingController();
  String? _selectedMandapamId;
  DateTime? _selectedCustomDate;
  List<String> bookingDates = [];
  List<DateTime> bookedDate = [];
  final CalendarController _calendarController = CalendarController();
  late Future<void> _mandapamHandler;
}

class BookingDataSource extends CalendarDataSource {
  BookingDataSource(List<DateTime> bookedDates, context) {
    appointments = bookedDates
        .map(
          (date) => Appointment(
            startTime: date,
            endTime: date,
            subject: AppLocalizations.of(context).booked,
            color: Colors.blue,
          ),
        )
        .toList();
  }
}
