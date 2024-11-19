import 'package:flutter/material.dart';

class PageNo extends StatefulWidget {
  final int total, pageLimit;
  const PageNo({super.key, required this.total, required this.pageLimit});
  @override
  State<PageNo> createState() => _PageNoState();
}

class _PageNoState extends State<PageNo> {
  List<String> pageNo = [];

  @override
  void initState() {
    _init();
    super.initState();
  }

  _init() {
    int totalPages = (widget.total / widget.pageLimit).ceil();
    setState(() {
      pageNo = List.generate(totalPages, (index) => (index + 1).toString());
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
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text(
              "Select Page No",
              style: Theme.of(context).textTheme.bodyLarge,
            ),
            const SizedBox(height: 5),
            const Divider(),
            const SizedBox(height: 5),
            Flexible(
              child: ListView.separated(
                primary: false,
                shrinkWrap: true,
                itemCount: pageNo.length,
                separatorBuilder: (context, index) {
                  return Divider(
                    color: Colors.grey.shade300,
                  );
                },
                itemBuilder: (context, index) {
                  return ListTile(
                    onTap: () {
                      Navigator.pop(context, pageNo[index]);
                    },
                    title: Text(
                      pageNo[index],
                      style: Theme.of(context).textTheme.bodyLarge,
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
