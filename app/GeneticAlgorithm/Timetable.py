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

    def get_permanent_rooms(self, course_id, nta_level):
        return self.course_room_map.get((course_id, nta_level), [])

    # ---------------------------------------------------------------
    # Helper: is this subject's nta_level the special "nta-4" cohort?
    # nta-4 must always be scheduled in the morning (08:00 - 16:00)
    # regardless of the course_level (degree/diploma).
    # ---------------------------------------------------------------
    @staticmethod
    def is_nta4(nta_level):
        if not nta_level:
            return False
        return str(nta_level).strip().lower().replace(" ", "") in ("nta-4", "nta4", "nta_4")

    # ---------------------------------------------------------------
    # Build the session "blocks" pattern for a subject based on its
    # credit_hour. The rule (as used across this institution):
    #   - One DOUBLE block (2 consecutive timeslots) on one day
    #   - Plus single-slot block(s) on other day(s) to make up the
    #     remaining credit hours.
    #
    # credit_hour 2  -> [2, 1]   (double one day, single another day)
    # credit_hour 1  -> [1]
    # credit_hour 3  -> [2, 1, 1]
    # credit_hour 4  -> [2, 2]
    # Anything else falls back to a sane default of [2, 1].
    # ---------------------------------------------------------------
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

        if ch == 2:
            return [2, 1]

        blocks = []
        remaining = ch
        # use as many double blocks as possible, then top up with a
        # single extra block on a different day to avoid gaps within
        # the same day (mirrors the credit_hour == 2 pattern).
        while remaining >= 2:
            blocks.append(2)
            remaining -= 2
        if remaining == 1:
            blocks.append(1)
        # ensure there is always at least one extra single session on
        # a separate day, matching institutional pattern.
        if len(blocks) == 1:
            blocks.append(1)
        return blocks

    # ---------------------------------------------------------------
    # 🔵 FIX 1 — SHARED-LECTURE GROUPING
    #
    # Some rows in `subjects` represent the SAME physical lecture
    # taught jointly to students from several different courses/
    # programmes. They are stored as separate rows because each course
    # needs its own record, but they share the same non-empty
    # `group_name` and the same `nta_level` — that is the signal.
    # They must be timetabled as ONE event: same day, same room, same
    # timeslot(s), just attended by more than one course at once.
    #
    # Subjects without a group_name (the normal case) are left
    # completely untouched — one subject = one independent scheduling
    # unit, exactly as before.
    # ---------------------------------------------------------------
    @staticmethod
    def get_group_key(subject):
        gname = subject.get("group_name")
        if gname and str(gname).strip():
            nta = str(subject.get("nta_level") or "").strip().lower()
            return ("shared", str(gname).strip().lower(), nta)
        return ("solo", subject["id"])

    def build_scheduling_groups(self):
        """
        Returns a list of subject-lists in deterministic order. Each
        sub-list is either:
          - several subjects that share the same group_name + nta_level
            (one joint lecture, several courses), or
          - a single subject (the normal, non-shared case).
        """
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
        """
        Deterministic plan of every scheduling "session" that needs a
        day / timeslot / room: one entry per (scheduling-group, block).
        Both TimetableChromosome (generation) and TimetableGA
        (crossover / mutation) rely on this SAME order so that a given
        session id (s1, s2, …) always refers to the same logical
        lecture-block no matter which chromosome is looking at it.

        Returns: list of (subject_list, blocks) tuples.
        """
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
        """Flat, ordered list of every session id in the canonical plan."""
        ids = []
        counter = 0
        for _subj_list, blocks in self.build_session_plan():
            for _block in blocks:
                counter += 1
                ids.append(f"s{counter}")
        return ids