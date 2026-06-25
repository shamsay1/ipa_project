from datetime import datetime
from collections import defaultdict


class Timetable:

    def __init__(self, subjects, teachers, rooms, timeslots, days, courses, course_rooms=None):

        self.subjects = subjects
        self.teachers = teachers
        self.rooms = rooms
        self.timeslots = timeslots
        self.days = days
        self.courses = courses
        self.course_rooms = course_rooms or []

        self.course_map = {c["id"]: c for c in courses}
        self.subject_map = {s["id"]: s for s in subjects}

        self.timeslot_start_hours = []
        for t in timeslots:
            st = datetime.strptime(t["start_time"], "%H:%M:%S")
            self.timeslot_start_hours.append(st.hour)

        self.course_room_map = defaultdict(list)
        for r in self.course_rooms:
            key = (r["course_id"], r["nta_level"])
            self.course_room_map[key].append(r["room_id"])

        self.student_count_map = defaultdict(int)
        for r in self.course_rooms:
            key = (r["course_id"], self._norm_nta(r.get("nta_level")))
            self.student_count_map[key] += (r.get("total_students") or 0)

        # Pre-build a set of room IDs that have capacity >= 80 (large rooms)
        self.large_room_ids = {
            r["id"] for r in self.rooms
            if r.get("type") != "Lab" and (r.get("capacity") or 0) >= 80
        }

        # ------------------------------------------------------------------ #
        # 🔵 DEGREE ROOM PRIORITY LIST                                        #
        # Large rooms (cap >= 80) sorted by capacity descending so that       #
        # degree NTAs with MORE students get the biggest rooms first.         #
        # Built once here; used by _group_candidate_rooms() in Chromosome.    #
        # ------------------------------------------------------------------ #
        self.large_rooms_sorted = sorted(
            [r for r in self.rooms
             if r.get("type") != "Lab" and (r.get("capacity") or 0) >= 80],
            key=lambda r: (r.get("capacity") or 0),
            reverse=True,
        )

    @staticmethod
    def _norm_nta(nta_level):
        return str(nta_level or "").strip().lower()

    def get_permanent_rooms(self, course_id, nta_level):
        return self.course_room_map.get((course_id, nta_level), [])

    def get_student_count(self, course_id, nta_level):
        return self.student_count_map.get((course_id, self._norm_nta(nta_level)), 0)

    def get_group_student_count(self, subj_list):
        pairs = set()
        for s in subj_list:
            pairs.add((s["course_id"], self._norm_nta(s.get("nta_level"))))
        total = 0
        for course_id, nta_level in pairs:
            total += self.student_count_map.get((course_id, nta_level), 0)
        return total

    @staticmethod
    def is_nta4(nta_level):
        if not nta_level:
            return False
        return str(nta_level).strip().lower().replace(" ", "") in ("nta-4", "nta4", "nta_4")

    # ------------------------------------------------------------------ #
    # 🔵 NEW: build_blocks — ENFORCE "2 then rest on other days" rule     #
    # Rule: siku moja ichukuwe vipindi 2 vinavyofuata (double block),     #
    # siku nyingine ichukuwe kilichobaki. KAMWE vipindi vyote             #
    # visomeshwe siku moja.                                               #
    #                                                                     #
    # credit_hour 1  -> [1]          (cannot split, one single session)   #
    # credit_hour 2  -> [2]          one double block, done               #
    # credit_hour 3  -> [2, 1]       double + single on diff day          #
    # credit_hour 4  -> [2, 2]       two doubles on two diff days         #
    # credit_hour 5  -> [2, 2, 1]                                         #
    # credit_hour 6  -> [2, 2, 2]                                         #
    # Anything <=0   -> [2, 1]  (safe default)                            #
    #                                                                     #
    # The key guarantee: len(blocks) >= 2 for ch >= 2, so the subject    #
    # MUST span at least two different days. For ch==2 we use [2] because #
    # a single 2-slot block on one day is the minimum unit; to force two  #
    # days we need at least ch==3. If you want even ch==2 to split,       #
    # change [2] to [1, 1] below.                                         #
    # ------------------------------------------------------------------ #
    @staticmethod
    def build_blocks(credit_hour):
        try:
            ch = int(credit_hour)
        except (TypeError, ValueError):
            ch = 2

        if ch <= 0:
            return [2, 1]

        if ch == 1:
            return [1]

        # ch >= 2: fill with double blocks first, then a single if remainder
        blocks = []
        remaining = ch
        while remaining >= 2:
            blocks.append(2)
            remaining -= 2
        if remaining == 1:
            blocks.append(1)

        # Guarantee at least two blocks (two different days) for ch >= 2
        # If only one block resulted (e.g. ch==2 → [2]), add a solo block
        # on another day so the subject spans two days as required.
        if len(blocks) == 1 and ch >= 2:
            blocks.append(1)

        return blocks

    @staticmethod
    def get_group_key(subject):
        gname = subject.get("group_name")
        if gname and str(gname).strip():
            nta = str(subject.get("nta_level") or "").strip().lower()
            return ("shared", str(gname).strip().lower(), nta)
        return ("solo", subject["id"])

    def build_scheduling_groups(self):
        groups = {}
        order = []
        for subject in self.subjects:
            key = self.get_group_key(subject)
            if key not in groups:
                groups[key] = []
                order.append(key)
            groups[key].append(subject)
        return [groups[k] for k in order]

    def build_session_plan(self):
        plan = []
        for subj_list in self.build_scheduling_groups():
            credit_hours = []
            for s in subj_list:
                try:
                    credit_hours.append(int(s.get("credit_hour")))
                except (TypeError, ValueError):
                    continue
            base = max(credit_hours) if credit_hours else subj_list[0].get("credit_hour")
            blocks = self.build_blocks(base)
            plan.append((subj_list, blocks))
        return plan

    def session_id_sequence(self):
        ids = []
        counter = 0
        for _subj_list, blocks in self.build_session_plan():
            for _block in blocks:
                counter += 1
                ids.append(f"s{counter}")
        return ids