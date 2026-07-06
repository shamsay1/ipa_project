"""
TimetableRepair.py  (v2 - DATA-DRIVEN, HAKUNA MUUNGANISHO WA DATABASE)
=======================================================================

MUHIMU: Toleo hili HALIUNGANISHI na MySQL moja kwa moja (hakuna pymysql,
hakuna socket). Linafanya kazi KWA MUUNDO ULE ULE wa GA ya kwanza
(Timetable.py / TimetableChromosome.py):

    Laravel -> inasoma DB -> inaandika JSON file -> inaita python ->
    python inasoma JSON, inachakata KWENYE MEMORY -> inaandika JSON matokeo
    kwenye stdout -> Laravel inasoma matokeo na kuandika DB yenyewe.

Hii inaondoa kabisa tatizo la "WinError 10106" / antivirus / winsock,
kwa sababu Python haiwahi kufungua socket kuelekea MySQL - kazi hiyo
inabaki kwa Laravel (PDO), kama ilivyokuwa kwenye GA ya kwanza.

INPUT (JSON file - angalia repair_main.py):
{
  "timetables": [ {id, day_id, subject_id, timeslot_id, room_id,
                    teacher_id, group_name}, ... ],
  "subjects":   [ {id, course_id, nta_level, credit_hour,
                    required_lab, group_name}, ... ],
  "courses":    [ {id, course_level}, ... ],
  "days":       [ {id, day_name}, ... ],
  "timeslots":  [ {id, start_time, end_time}, ... ],
  "rooms":      [ {id, type, practical_type, capacity}, ... ]
}

OUTPUT: report ya kawaida (moved / unresolved / changed_rows / summary /
diagnostics) - changed_rows ina {id, day_id, timeslot_id, room_id} tu,
tayari kwa Laravel kuandika kwenye jedwali la `timetables` kwa
`DB::table('timetables')->where('id', $id)->update([...])`.

Masharti mawili yanayorekebishwa hayajabadilika:
  1) Mwalimu asizidi siku 5 kwa wiki.
  2) Diploma (isipokuwa NTA-4) isiwe na kipindi kinachoanza saa >= 18:00;
     hukuruhusiwa kutumia wikendi kama njia ya kutoroka muda huo.
"""

import random
from collections import defaultdict


LATE_HOUR_THRESHOLD = 18
MAX_TEACHER_DAYS = 5
WEEKEND_DAY_NAMES = ("saturday", "sunday")


class RepairEngine:

    def __init__(self, data):
        """
        data: dict iliyosomwa moja kwa moja kutoka kwenye JSON file
              (angalia muundo hapo juu).
        """
        self.raw = data

        self.days = {}
        self.day_ids_sorted = []
        self.timeslots = {}
        self.timeslot_ids_sorted = []
        self.rooms = {}

        self.subject_map = {}
        self.course_level_map = {}

        self.rows = []
        self.units = {}

        self.teacher_busy = set()
        self.room_busy = set()
        self.nta_busy = set()
        self.teacher_days = defaultdict(set)

    def load(self):
        for d in self.raw.get("days", []):
            self.days[int(d["id"])] = str(d["day_name"]).strip().lower()
        self.day_ids_sorted = sorted(self.days.keys())

        for ts in self.raw.get("timeslots", []):
            self.timeslots[int(ts["id"])] = {
                "start_hour": self._to_hour(ts["start_time"]),
                "end_hour": self._to_hour(ts["end_time"]),
            }
        self.timeslot_ids_sorted = sorted(
            self.timeslots.keys(),
            key=lambda tid: self.timeslots[tid]["start_hour"]
        )

        for r in self.raw.get("rooms", []):
            self.rooms[int(r["id"])] = {
                "type": r.get("type"),
                "practical_type": r.get("practical_type"),
                "capacity": r.get("capacity") or 0,
            }

        for s in self.raw.get("subjects", []):
            self.subject_map[int(s["id"])] = {
                "course_id": s.get("course_id"),
                "nta_level": s.get("nta_level"),
                "credit_hour": s.get("credit_hour"),
                "required_lab": s.get("required_lab"),
                "group_name": s.get("group_name"),
            }

        for c in self.raw.get("courses", []):
            self.course_level_map[int(c["id"])] = str(c.get("course_level") or "").strip().lower()

        self.rows = []
        for t in self.raw.get("timetables", []):
            subj = self.subject_map.get(int(t["subject_id"]), {})
            course_id = subj.get("course_id")
            row = {
                "id": t["id"],
                "day_id": int(t["day_id"]),
                "subject_id": int(t["subject_id"]),
                "timeslot_id": int(t["timeslot_id"]),
                "room_id": int(t["room_id"]),
                "teacher_id": t.get("teacher_id"),
                "group_name": subj.get("group_name") or t.get("group_name"),
                "course_id": course_id,
                "nta_level": subj.get("nta_level"),
                "required_lab": subj.get("required_lab"),
                "course_level": self.course_level_map.get(course_id, ""),
            }
            self.rows.append(row)

        self._build_units()
        self._build_busy_maps()

    @staticmethod
    def _to_hour(t):
        if t is None:
            return 0
        if hasattr(t, "seconds"):
            return t.seconds // 3600
        return int(str(t).split(":")[0])

    @staticmethod
    def is_nta4(nta_level):
        if not nta_level:
            return False
        return str(nta_level).strip().lower().replace(" ", "") in ("nta-4", "nta4", "nta_4")

    def _build_units(self):
        self.units = {}
        for row in self.rows:
            gname = row.get("group_name")
            if gname and str(gname).strip():
                key = ("shared", row["day_id"], str(gname).strip().lower())
            else:
                key = ("solo", row["day_id"], row["subject_id"], row["teacher_id"])

            unit = self.units.setdefault(key, {
                "key": key,
                "day_id": row["day_id"],
                "rows": [],
                "room_id": row["room_id"],
                "teacher_ids": set(),
                "subject_ids": set(),
                "nta_pairs": set(),
                "course_levels": set(),
                "is_nta4": False,
                "has_lab": False,
            })
            unit["rows"].append(row)
            unit["teacher_ids"].add(row["teacher_id"])
            unit["subject_ids"].add(row["subject_id"])
            unit["nta_pairs"].add((row["course_id"], row["nta_level"]))
            unit["course_levels"].add(row["course_level"])
            if self.is_nta4(row["nta_level"]):
                unit["is_nta4"] = True
            if row.get("required_lab") and row["required_lab"] != "Theory":
                unit["has_lab"] = True

        for unit in self.units.values():
            unit["timeslot_ids"] = sorted({r["timeslot_id"] for r in unit["rows"]})
            unit["block_len"] = len(unit["timeslot_ids"])

    def _build_busy_maps(self):
        self.teacher_busy = set()
        self.room_busy = set()
        self.nta_busy = set()
        self.teacher_days = defaultdict(set)

        for row in self.rows:
            self.teacher_busy.add((row["teacher_id"], row["day_id"], row["timeslot_id"]))
            self.room_busy.add((row["room_id"], row["day_id"], row["timeslot_id"]))
            self.nta_busy.add((row["course_id"], row["nta_level"], row["day_id"], row["timeslot_id"]))

        for unit in self.units.values():
            for tid in unit["teacher_ids"]:
                self.teacher_days[tid].add(unit["day_id"])

    def _allowed_time(self, course_levels, is_nta4, day_name, hour, allow_weekend_override=False):
        if day_name in WEEKEND_DAY_NAMES:
            if allow_weekend_override:
                return 8 <= hour < LATE_HOUR_THRESHOLD
            for level in course_levels:
                if level == "degree":
                    if not (8 <= hour < 16):
                        return False
                else:
                    if not (hour >= 8):
                        return False
            return True

        if is_nta4:
            return 8 <= hour < 16

        for level in course_levels:
            if level == "degree":
                if not (14 <= hour < 19):
                    return False
            elif level == "diploma":
                if not (8 <= hour < 16):
                    return False
        return True

    def _room_candidates(self, unit):
        room_id = unit["room_id"]
        candidates = [room_id]
        room_info = self.rooms.get(room_id, {})
        if unit["has_lab"]:
            ptype = room_info.get("practical_type")
            extra = [rid for rid, info in self.rooms.items()
                     if info.get("type") == "Lab" and info.get("practical_type") == ptype
                     and rid != room_id]
        else:
            min_cap = room_info.get("capacity", 0)
            extra = [rid for rid, info in self.rooms.items()
                     if info.get("type") != "Lab" and rid != room_id
                     and info.get("capacity", 0) >= min_cap]
        random.shuffle(extra)
        candidates.extend(extra)
        return candidates

    def _try_place(self, unit, day_id, block_len, forbid_hours=None,
                    allow_weekend_override=False, extra_teacher_cap_check=None):
        day_name = self.days[day_id]
        forbid_hours = forbid_hours or set()

        valid_slot_ids = []
        for tid in self.timeslot_ids_sorted:
            hour = self.timeslots[tid]["start_hour"]
            if hour in forbid_hours:
                continue
            if not self._allowed_time(unit["course_levels"], unit["is_nta4"],
                                       day_name, hour,
                                       allow_weekend_override=allow_weekend_override):
                continue
            valid_slot_ids.append(tid)
        valid_slot_ids.sort()

        for i in range(len(valid_slot_ids)):
            if i + block_len > len(valid_slot_ids):
                break
            block = valid_slot_ids[i:i + block_len]
            if block[-1] - block[0] != block_len - 1:
                continue

            conflict = False
            for tid_person in unit["teacher_ids"]:
                for ts in block:
                    if (tid_person, day_id, ts) in self.teacher_busy:
                        conflict = True
                        break
                if conflict:
                    break
            if conflict:
                continue

            for (course_id, nta_level) in unit["nta_pairs"]:
                for ts in block:
                    if (course_id, nta_level, day_id, ts) in self.nta_busy:
                        conflict = True
                        break
                if conflict:
                    break
            if conflict:
                continue

            if extra_teacher_cap_check:
                if not extra_teacher_cap_check(day_id, unit["teacher_ids"]):
                    continue

            for room_id in self._room_candidates(unit):
                if any((room_id, day_id, ts) in self.room_busy for ts in block):
                    continue
                return block, room_id

        return None

    def _release_unit(self, unit):
        for row in unit["rows"]:
            self.teacher_busy.discard((row["teacher_id"], unit["day_id"], row["timeslot_id"]))
            self.room_busy.discard((row["room_id"], unit["day_id"], row["timeslot_id"]))
            self.nta_busy.discard((row["course_id"], row["nta_level"], unit["day_id"], row["timeslot_id"]))

    def _occupy_unit(self, unit, day_id, new_timeslots, new_room_id):
        rows_by_subject = defaultdict(list)
        for row in unit["rows"]:
            rows_by_subject[row["subject_id"]].append(row)

        for subject_id, rlist in rows_by_subject.items():
            rlist.sort(key=lambda r: r["timeslot_id"])
            for row, new_ts in zip(rlist, new_timeslots):
                row["_new_day_id"] = day_id
                row["_new_timeslot_id"] = new_ts
                row["_new_room_id"] = new_room_id
                self.teacher_busy.add((row["teacher_id"], day_id, new_ts))
                self.room_busy.add((new_room_id, day_id, new_ts))
                self.nta_busy.add((row["course_id"], row["nta_level"], day_id, new_ts))

        unit["day_id"] = day_id
        unit["room_id"] = new_room_id
        unit["timeslot_ids"] = list(new_timeslots)
        for tid_person in unit["teacher_ids"]:
            self.teacher_days[tid_person].add(day_id)

    def fix_teacher_days(self, moved_log, unresolved_log):
        teacher_day_block_count = defaultdict(lambda: defaultdict(int))
        for unit in self.units.values():
            for tid in unit["teacher_ids"]:
                teacher_day_block_count[tid][unit["day_id"]] += 1

        def cap_check(day_id, teacher_ids):
            for tid in teacher_ids:
                if day_id in self.teacher_days[tid]:
                    continue
                if len(self.teacher_days[tid]) >= MAX_TEACHER_DAYS:
                    return False
            return True

        for teacher_id, days_used in list(self.teacher_days.items()):
            if len(days_used) <= MAX_TEACHER_DAYS:
                continue

            day_counts = teacher_day_block_count[teacher_id]
            kept_days = sorted(days_used, key=lambda d: day_counts.get(d, 0), reverse=True)[:MAX_TEACHER_DAYS]
            excess_days = [d for d in days_used if d not in kept_days]

            excess_units = [
                u for u in self.units.values()
                if teacher_id in u["teacher_ids"] and u["day_id"] in excess_days
            ]
            excess_units.sort(key=lambda u: u["block_len"])

            for unit in excess_units:
                if len(self.teacher_days[teacher_id]) <= MAX_TEACHER_DAYS:
                    break

                old_day = unit["day_id"]
                candidate_days = list(kept_days)
                random.shuffle(candidate_days)
                fallback_days = [d for d in self.day_ids_sorted
                                  if d not in candidate_days and d != old_day]
                random.shuffle(fallback_days)

                self._release_unit(unit)
                placement = None
                tried_day = None
                for day_id in candidate_days + fallback_days:
                    if day_id == old_day:
                        continue
                    result = self._try_place(unit, day_id, unit["block_len"],
                                              extra_teacher_cap_check=cap_check)
                    if result:
                        placement = result
                        tried_day = day_id
                        break

                if placement is None:
                    self._occupy_unit(unit, old_day, unit["timeslot_ids"], unit["room_id"])
                    unresolved_log.append({
                        "reason": "teacher_over_5_days",
                        "teacher_id": teacher_id,
                        "subject_ids": list(unit["subject_ids"]),
                        "old_day_id": old_day,
                    })
                    continue

                new_slots, new_room_id = placement
                self._occupy_unit(unit, tried_day, new_slots, new_room_id)
                moved_log.append({
                    "reason": "teacher_over_5_days",
                    "teacher_id": teacher_id,
                    "subject_ids": list(unit["subject_ids"]),
                    "old_day_id": old_day,
                    "new_day_id": tried_day,
                    "new_timeslot_ids": new_slots,
                    "new_room_id": new_room_id,
                })

    def fix_diploma_late_slot(self, moved_log, unresolved_log):
        late_hour_ids = {
            tid for tid, info in self.timeslots.items()
            if info["start_hour"] >= LATE_HOUR_THRESHOLD
        }
        if not late_hour_ids:
            return

        offending_units = []
        for unit in self.units.values():
            if unit["is_nta4"]:
                continue
            if "diploma" not in unit["course_levels"]:
                continue
            if any(ts in late_hour_ids for ts in unit["timeslot_ids"]):
                offending_units.append(unit)

        def cap_check(day_id, teacher_ids):
            for tid in teacher_ids:
                if day_id in self.teacher_days[tid]:
                    continue
                if len(self.teacher_days[tid]) >= MAX_TEACHER_DAYS:
                    return False
            return True

        for unit in offending_units:
            old_day = unit["day_id"]
            self._release_unit(unit)

            placement = None
            tried_day = None

            weekday_ids = [d for d in self.day_ids_sorted
                           if self.days[d] not in WEEKEND_DAY_NAMES]
            random.shuffle(weekday_ids)
            for day_id in weekday_ids:
                result = self._try_place(unit, day_id, unit["block_len"],
                                          extra_teacher_cap_check=cap_check)
                if result:
                    placement, tried_day = result, day_id
                    break

            if placement is None:
                for day_id in weekday_ids:
                    result = self._try_place(
                        unit, day_id, unit["block_len"],
                        forbid_hours=set(range(LATE_HOUR_THRESHOLD, 24)),
                        extra_teacher_cap_check=cap_check,
                    )
                    if result:
                        placement, tried_day = result, day_id
                        break

            if placement is None:
                weekend_ids = [d for d in self.day_ids_sorted
                               if self.days[d] in WEEKEND_DAY_NAMES]
                random.shuffle(weekend_ids)
                for day_id in weekend_ids:
                    result = self._try_place(
                        unit, day_id, unit["block_len"],
                        allow_weekend_override=True,
                        extra_teacher_cap_check=cap_check,
                    )
                    if result:
                        placement, tried_day = result, day_id
                        break

            if placement is None:
                self._occupy_unit(unit, old_day, unit["timeslot_ids"], unit["room_id"])
                unresolved_log.append({
                    "reason": "diploma_late_slot",
                    "subject_ids": list(unit["subject_ids"]),
                    "old_day_id": old_day,
                })
                continue

            new_slots, new_room_id = placement
            self._occupy_unit(unit, tried_day, new_slots, new_room_id)
            moved_log.append({
                "reason": "diploma_late_slot",
                "subject_ids": list(unit["subject_ids"]),
                "old_day_id": old_day,
                "new_day_id": tried_day,
                "new_timeslot_ids": new_slots,
                "new_room_id": new_room_id,
            })

    def run(self):
        moved_log = []
        unresolved_log = []

        self.fix_diploma_late_slot(moved_log, unresolved_log)
        self.fix_teacher_days(moved_log, unresolved_log)

        changed_rows = [
            {
                "id": r["id"],
                "day_id": r["_new_day_id"],
                "timeslot_id": r["_new_timeslot_id"],
                "room_id": r["_new_room_id"],
            }
            for r in self.rows if "_new_day_id" in r
        ]

        summary = (
            f"Vipindi vilivyohamishwa: {len(moved_log)} | "
            f"Vipindi visivyoweza kutatuliwa kiotomatiki: {len(unresolved_log)} | "
            f"Rows za timetables zitakazobadilika: {len(changed_rows)}"
        )

        diploma_late_found = sum(1 for m in moved_log if m.get("reason") == "diploma_late_slot") + \
            sum(1 for u in unresolved_log if u.get("reason") == "diploma_late_slot")
        teacher_issue_found = sum(1 for m in moved_log if m.get("reason") == "teacher_over_5_days") + \
            sum(1 for u in unresolved_log if u.get("reason") == "teacher_over_5_days")

        diagnostics = {
            "rows_loaded": len(self.rows),
            "units_built": len(self.units),
            "distinct_teachers": len(self.teacher_days),
            "teacher_over_5_days_found": teacher_issue_found,
            "diploma_late_slot_found": diploma_late_found,
        }

        return {
            "moved": moved_log,
            "unresolved": unresolved_log,
            "changed_rows": changed_rows,
            "summary": summary,
            "diagnostics": diagnostics,
        }