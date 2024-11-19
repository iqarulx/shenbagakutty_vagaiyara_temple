import 'package:flutter/material.dart';

class PageLimit extends StatefulWidget {
  const PageLimit({super.key});
  @override
  State<PageLimit> createState() => _PageLimitState();
}

class _PageLimitState extends State<PageLimit> {
  List<String> pageLimit = ["25", "50", "75", "100"];

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
              "Select Page Limit",
              style: Theme.of(context).textTheme.bodyLarge,
            ),
            const SizedBox(height: 5),
            const Divider(),
            const SizedBox(height: 5),
            Flexible(
              child: ListView.separated(
                primary: false,
                shrinkWrap: true,
                itemCount: pageLimit.length,
                separatorBuilder: (context, index) {
                  return Divider(
                    color: Colors.grey.shade300,
                  );
                },
                itemBuilder: (context, index) {
                  return ListTile(
                    onTap: () {
                      Navigator.pop(context, pageLimit[index]);
                    },
                    title: Text(
                      pageLimit[index],
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
