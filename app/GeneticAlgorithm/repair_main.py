"""
repair_main.py  (v2 - MUUNDO SAWA NA main.py, HAKUNA DB CONNECTION)
====================================================================

Kama main.py, script hii:
  - Inapokea NJIA ya JSON file kama argument ya kwanza (imeandikwa na
    Laravel, kama file_put_contents inavyofanya kwenye generateTimetable())
  - Inasoma JSON hiyo
  - Inachakata KWENYE MEMORY (hakuna muunganisho wa database)
  - Inaandika matokeo (JSON) kwenye stdout

Laravel ndiyo itakayosoma DB na kuandika DB - Python haiwahi kuigusa.

Matumizi:
    python repair_main.py <data_file.json>

data_file.json inatakiwa iwe na:
{
  "timetables": [...],
  "subjects": [...],
  "courses": [...],
  "days": [...],
  "timeslots": [...],
  "rooms": [...]
}
"""

import json
import sys
import os
import traceback

sys.path.append(os.path.dirname(os.path.abspath(__file__)))


def main():
    try:
        if len(sys.argv) < 2:
            print(json.dumps({"error": "Usage: python repair_main.py <data_file.json>"}))
            sys.exit(1)

        with open(sys.argv[1], "r", encoding="utf-8") as f:
            data = json.load(f)

        from TimetableRepair import RepairEngine

        engine = RepairEngine(data)
        engine.load()
        report = engine.run()

        output = {
            "summary": report["summary"],
            "moved_count": len(report["moved"]),
            "unresolved_count": len(report["unresolved"]),
            "moved": report["moved"],
            "unresolved": report["unresolved"],
            "changed_rows": report["changed_rows"],
            "diagnostics": report["diagnostics"],
        }
        print(json.dumps(output, default=str))

    except Exception as e:
        print(json.dumps({"error": str(e), "traceback": traceback.format_exc()}))
        sys.exit(1)


if __name__ == "__main__":
    main()