import random
from collections import defaultdict


class TimetableChromosome:

    def __init__(self, timetable):
        self.timetable = timetable
        self.genes = []
        self.fitness = 0.0

    # ================================================================== #
    # CONSTANTS                                                           #
    # ================================================================== #

    STUDENT_THRESHOLD  = 60    # student count above which we want a bigger room
    LARGE_THEORY_CAP   = 80    # min capacity for Theory rooms (degree always; large groups)
    LARGE_LAB_CAP      = 50    # min capacity for Lab rooms when group is large
    MIN_DAILY_SESSIONS = 3     # soft target: no day should have fewer than this
    MAX_TEACHER_DAYS   = 5     # a teacher must not teach on more than this many days

    # ================================================================== #
    # TIME RULES                                                          #
    # ================================================================== #

    def allowed_time(self, level, nta_level, day_name, hour):
        """
        Return True if `hour` (start-of-slot) is within the allowed window
        for the given course level, nta, and day.

        Degree weekday  : 14:00 – 19:00  (rooms cap>=80, 3 consecutive slots)
        Degree weekend  : 08:00 – 16:00
        NTA-4 weekday   : 08:00 – 16:00  (morning only, regardless of level)
        Diploma weekday : 08:00 – 16:00
        Diploma weekend : blocked entirely (set elsewhere via weekend_blocked)
        """
        if day_name in ("saturday", "sunday"):
            if level == "degree":
                return 8 <= hour < 16
            return hour >= 8

        # NTA-4 override — morning only on weekdays
        if self.timetable.is_nta4(nta_level):
            return 8 <= hour < 16

        # 🔵 DEGREE weekday: 14:00 – 19:00
        if level == "degree":
            return 14 <= hour < 19

        # Diploma weekday: 08:00 – 16:00
        if level == "diploma":
            return 8 <= hour < 16

        return True

    # ================================================================== #
    # GROUP HELPERS                                                       #
    # ================================================================== #

    def _group_levels(self, subj_list):
        t = self.timetable
        levels = set()
        for s in subj_list:
            course = t.course_map.get(s["course_id"], {})
            levels.add(course.get("course_level", "").lower())
        return levels

    def _group_allowed(self, levels, nta, day_name, hour):
        for level in levels:
            if not self.allowed_time(level, nta, day_name, hour):
                return False
        return True

    def _is_shared_group(self, subj_list):
        return any(s.get("group_name") for s in subj_list)

    # ------------------------------------------------------------------ #
    # 🔵 DIPLOMA-IN-LARGE-ROOM: does this diploma group's permanent room  #
    # have capacity >= 80? If so they must finish before 14:00 to vacate  #
    # the room for degree sessions.                                       #
    # ------------------------------------------------------------------ #
    def _group_uses_large_room(self, subj_list):
        t = self.timetable
        for s in subj_list:
            perm = t.get_permanent_rooms(s["course_id"], s["nta_level"])
            for room_id in perm:
                if room_id in t.large_room_ids:
                    return True
        return False

    # ------------------------------------------------------------------ #
    # 🔵 DEGREE ROOM CANDIDATE LIST WITH STUDENT-COUNT PRIORITY           #
    #                                                                     #
    # For degree subjects:                                                #
    #   - MUST use a room with capacity >= 80 (large_rooms_sorted)        #
    #   - Among large rooms, prefer those with higher capacity first when  #
    #     the group has more students (priority = student_count desc)      #
    #   - Diploma subjects who SHARE a subject (group_name set) may also   #
    #     land in large rooms during the morning window; non-shared        #
    #     diploma subjects get their permanent/normal rooms as before.     #
    # ------------------------------------------------------------------ #
    def _group_candidate_rooms(self, subj_list,
                                relax_room_preference=False,
                                relax_capacity=False):
        t = self.timetable

        student_count = t.get_group_student_count(subj_list)
        needs_large_room = student_count > self.STUDENT_THRESHOLD

        levels = self._group_levels(subj_list)
        is_degree = ("degree" in levels and
                     not any(t.is_nta4(s.get("nta_level")) for s in subj_list))

        # Is this a shared-subject group that includes diploma students?
        is_shared = self._is_shared_group(subj_list)
        is_diploma_shared = (not is_degree) and ("diploma" in levels) and is_shared

        lab_subject = next(
            (s for s in subj_list
             if s.get("required_lab") and s.get("required_lab") != "Theory"),
            None
        )

        # ---------- LAB SUBJECTS ---------- #
        if lab_subject:
            base = [
                r for r in t.rooms
                if r["type"] == "Lab"
                and r.get("practical_type") == lab_subject["required_lab"]
            ]
            if needs_large_room and not relax_capacity:
                filtered = [r for r in base
                            if (r.get("capacity") or 0) >= self.LARGE_LAB_CAP]
                if filtered:
                    return filtered
            return base

        # ---------- DEGREE THEORY: must use large rooms (cap >= 80) ---------- #
        if is_degree and not relax_capacity:
            # Sort by capacity desc so that NTAs with more students
            # naturally compete for the biggest rooms (the GA's random
            # selection from this pre-sorted list is biased toward the
            # front, but any room in the list is valid).
            large = list(t.large_rooms_sorted)   # already sorted cap desc
            if large:
                # Further bias: if student_count is high, keep only the
                # top half of large rooms (the biggest ones).
                if student_count > 40 and len(large) > 1:
                    large = large[: max(1, len(large) // 2)]
                return large
            # No large room in the system — fallback to any non-lab room
            return [r for r in t.rooms if r["type"] != "Lab"]

        # ---------- DIPLOMA SHARED (shares subject with degree/other courses) #
        # These groups may be allocated to large rooms during the morning      #
        # window so they co-share the same physical space.  We allow large     #
        # rooms for them too so _find_slot can place them before 14:00.        #
        if is_diploma_shared and not relax_capacity:
            # Allow large rooms for shared diploma groups
            perm_rooms = []
            if not relax_room_preference:
                for s in subj_list:
                    for rid in t.get_permanent_rooms(s["course_id"], s["nta_level"]):
                        if rid not in perm_rooms:
                            perm_rooms.append(rid)
            if perm_rooms:
                base = [r for r in t.rooms if r["id"] in perm_rooms]
                # If permanent room is already large, use it; otherwise
                # fall back to system-wide large rooms so they can share.
                has_large = any(r["id"] in t.large_room_ids for r in base)
                if has_large:
                    return base
            # No large permanent room → use system-wide large rooms
            large = list(t.large_rooms_sorted)
            if large:
                return large
            return [r for r in t.rooms if r["type"] != "Lab"]

        # ---------- NORMAL DIPLOMA / OTHER THEORY ---------- #
        permanent_rooms = []
        if not relax_room_preference:
            for s in subj_list:
                for rid in t.get_permanent_rooms(s["course_id"], s["nta_level"]):
                    if rid not in permanent_rooms:
                        permanent_rooms.append(rid)

        if permanent_rooms:
            base = [r for r in t.rooms if r["id"] in permanent_rooms]
        else:
            base = [r for r in t.rooms if r["type"] != "Lab"]

        if needs_large_room and not relax_capacity:
            filtered = [r for r in base
                        if (r.get("capacity") or 0) >= self.LARGE_THEORY_CAP]
            if filtered:
                return filtered
            system_wide_large = [
                r for r in t.rooms
                if r["type"] != "Lab" and (r.get("capacity") or 0) >= self.LARGE_THEORY_CAP
            ]
            if system_wide_large:
                return system_wide_large
            if base:
                return base
            return [r for r in t.rooms if r["type"] != "Lab"]

        return base if base else [r for r in t.rooms if r["type"] != "Lab"]

    # ================================================================== #
    # SLOT SEARCH — one block / one session                              #
    # ================================================================== #

    def _find_slot(self, subj_list, levels, nta, is_nta4, block,
                    teacher_busy, room_busy, nta_busy,
                    used_days_group, nta_daily_count, allowed_weekdays,
                    teacher_days=None,
                    relax_day_reuse=False, relax_room_preference=False,
                    relax_daily_limit=False, relax_capacity=False,
                    relax_time_window=False, relax_teacher_days=False,
                    relax_min_daily=False,
                    exhaustive=False, attempts=400):

        t = self.timetable

        is_degree = "degree" in levels and not is_nta4
        is_shared = self._is_shared_group(subj_list)
        is_diploma_shared = (not is_degree) and ("diploma" in levels) and is_shared

        # ------------------------------------------------------------------
        # 🔵 DIPLOMA-IN-LARGE-ROOM: diploma groups whose permanent room has
        # capacity >= 80 must finish BY 14:00 on weekdays to vacate for
        # degree sessions.  Also applies to diploma-shared groups that will
        # land in large rooms.
        # ------------------------------------------------------------------
        is_diploma_large_room = (
            not is_degree
            and "diploma" in levels
            and not is_nta4
            and (self._group_uses_large_room(subj_list) or is_diploma_shared)
        )

        weekend_blocked = (not relax_time_window) and (
            is_nta4 or ("diploma" in levels)
        )

        def day_is_candidate(day_id, day_name):
            if weekend_blocked and day_name in ("saturday", "sunday"):
                return False
            if (allowed_weekdays is not None
                    and day_name not in ("saturday", "sunday")
                    and day_id not in allowed_weekdays):
                return False
            if not relax_day_reuse and day_id in used_days_group:
                return False
            if not relax_teacher_days and teacher_days is not None:
                for s in subj_list:
                    tdays = teacher_days.get(s["teacher_id"], set())
                    if day_id not in tdays and len(tdays) >= self.MAX_TEACHER_DAYS:
                        return False
            return True

        def attempt_day(day_id):
            day_name = t.days[day_id]["day_name"].lower()
            if not day_is_candidate(day_id, day_name):
                return None

            if relax_time_window:
                valid_slots = [
                    idx for idx, hour in enumerate(t.timeslot_start_hours)
                    if not (day_name == "friday" and hour == 12)
                ]
            else:
                valid_slots = []
                for idx, hour in enumerate(t.timeslot_start_hours):
                    if day_name == "friday" and hour == 12:
                        continue
                    if not self._group_allowed(levels, nta, day_name, hour):
                        continue
                    # 🔵 DIPLOMA-IN-LARGE-ROOM: only slots starting < 14:00
                    if is_diploma_large_room:
                        if hour >= 14:
                            continue
                    valid_slots.append(idx)

            valid_slots.sort()

            for i in range(len(valid_slots)):
                if i + block > len(valid_slots):
                    break
                slots = valid_slots[i:i + block]
                if slots[-1] != slots[0] + (block - 1):
                    continue

                # Diploma-in-large-room: last slot of the block must also < 14
                if is_diploma_large_room and not relax_time_window:
                    last_hour = t.timeslot_start_hours[slots[-1]]
                    if last_hour >= 14:
                        continue

                # ---------- HARD CONFLICT CHECK ---------- #
                conflict = False
                for s in subj_list:
                    for ts in slots:
                        if (s["teacher_id"], day_id, ts) in teacher_busy:
                            conflict = True; break
                        if (s["course_id"], s["nta_level"], day_id, ts) in nta_busy:
                            conflict = True; break
                    if conflict:
                        break
                if conflict:
                    continue

                # ---------- ROOM SELECTION ---------- #
                candidate_rooms = self._group_candidate_rooms(
                    subj_list,
                    relax_room_preference=relax_room_preference,
                    relax_capacity=relax_capacity,
                )
                # 🔵 DEGREE STUDENT-COUNT PRIORITY: keep sort order from
                # large_rooms_sorted (cap desc) but shuffle only within equal-
                # capacity bands to introduce diversity without losing the
                # biggest-room-first bias.
                if is_degree:
                    # Use as-is (already sorted by capacity desc); just
                    # add a small random offset within equal-cap groups.
                    by_cap = defaultdict(list)
                    for r in candidate_rooms:
                        by_cap[(r.get("capacity") or 0)].append(r)
                    ordered_rooms = []
                    for cap in sorted(by_cap.keys(), reverse=True):
                        group = by_cap[cap]
                        random.shuffle(group)
                        ordered_rooms.extend(group)
                    candidate_rooms = ordered_rooms
                else:
                    random.shuffle(candidate_rooms)

                for room in candidate_rooms:
                    room_idx = next(
                        (ri for ri, r in enumerate(t.rooms) if r["id"] == room["id"]),
                        None
                    )
                    if room_idx is None:
                        continue
                    if any((room_idx, day_id, ts) in room_busy for ts in slots):
                        continue

                    # ---------- DAILY LIMIT (soft) ---------- #
                    if not relax_daily_limit:
                        limit_ok = True
                        for s in subj_list:
                            daily_key = (s["course_id"], s["nta_level"], day_id)
                            count = nta_daily_count.get(daily_key, 0) + block
                            # Degree weekday: max 3 consecutive sessions
                            if is_degree and day_name not in ("saturday", "sunday"):
                                if count > 3:
                                    limit_ok = False; break
                            if count > 8:
                                limit_ok = False; break
                        if not limit_ok:
                            continue

                    return day_id, slots, room_idx

            return None

        if exhaustive:
            day_order = list(range(len(t.days)))
            random.shuffle(day_order)
            for day_id in day_order:
                result = attempt_day(day_id)
                if result:
                    return result
            return None

        boosted_days = [] if relax_min_daily else self._boosted_days(subj_list, nta_daily_count)

        for attempt_no in range(attempts):
            if boosted_days and attempt_no % 2 == 0:
                day_id = random.choice(boosted_days)
            else:
                day_id = random.randrange(len(t.days))
            result = attempt_day(day_id)
            if result:
                return result
        return None

    def _boosted_days(self, subj_list, nta_daily_count):
        t = self.timetable
        boosted = []
        for day_id in range(len(t.days)):
            for s in subj_list:
                key = (s["course_id"], s["nta_level"], day_id)
                count = nta_daily_count.get(key, 0)
                if 0 < count < self.MIN_DAILY_SESSIONS:
                    boosted.append(day_id)
                    break
        return boosted

    # ================================================================== #
    # GENERATE                                                            #
    # ================================================================== #

    def generate_random(self):

        self.genes = []
        t = self.timetable

        teacher_busy   = set()
        room_busy      = set()
        nta_busy       = set()
        nta_daily_count = {}
        teacher_days   = defaultdict(set)

        self.expected_blocks = {}

        session_counter = 0

        # ------------------------------------------------------------------ #
        # 🔵 DEGREE GENERATION ORDER: schedule degree NTAs with LARGER         #
        # student counts FIRST so they get first pick of the biggest rooms.   #
        # Build plan then re-sort: degree groups desc by student count,        #
        # all other groups keep their original order after degree groups.      #
        # ------------------------------------------------------------------ #
        full_plan = t.build_session_plan()
        degree_groups = []
        other_groups  = []
        for subj_list, blocks in full_plan:
            levels = self._group_levels(subj_list)
            is_nta4 = t.is_nta4(subj_list[0].get("nta_level"))
            if "degree" in levels and not is_nta4:
                sc = t.get_group_student_count(subj_list)
                degree_groups.append((sc, subj_list, blocks))
            else:
                other_groups.append((subj_list, blocks))
        degree_groups.sort(key=lambda x: x[0], reverse=True)
        ordered_plan = [(sg, bl) for _, sg, bl in degree_groups] + other_groups

        for subj_list, blocks in ordered_plan:

            levels  = self._group_levels(subj_list)
            nta     = subj_list[0].get("nta_level")
            is_nta4 = t.is_nta4(nta)

            for s in subj_list:
                self.expected_blocks[s["id"]] = sum(blocks)

            used_days_group = []

            allowed_weekdays = None
            if "degree" in levels and not is_nta4:
                weekday_ids = [
                    i for i, d in enumerate(t.days)
                    if d["day_name"].lower() not in ["saturday", "sunday"]
                ]
                random.shuffle(weekday_ids)
                allowed_weekdays = weekday_ids[:random.randint(2, 3)]

            for block in blocks:

                session_counter += 1
                session_id = f"s{session_counter}"

                # ---- TIER 1: normal behaviour ---- #
                placement = self._find_slot(
                    subj_list, levels, nta, is_nta4, block,
                    teacher_busy, room_busy, nta_busy,
                    used_days_group, nta_daily_count, allowed_weekdays,
                    teacher_days=teacher_days,
                    attempts=400,
                )

                # ---- TIER 2: exhaustive, relax day-reuse + room-pref ---- #
                if placement is None:
                    placement = self._find_slot(
                        subj_list, levels, nta, is_nta4, block,
                        teacher_busy, room_busy, nta_busy,
                        used_days_group, nta_daily_count,
                        allowed_weekdays=None,
                        teacher_days=teacher_days,
                        relax_day_reuse=True,
                        relax_room_preference=True,
                        exhaustive=True,
                    )

                # ---- TIER 3: also relax daily limits + capacity ---- #
                if placement is None:
                    placement = self._find_slot(
                        subj_list, levels, nta, is_nta4, block,
                        teacher_busy, room_busy, nta_busy,
                        used_days_group, nta_daily_count,
                        allowed_weekdays=None,
                        teacher_days=teacher_days,
                        relax_day_reuse=True,
                        relax_room_preference=True,
                        relax_daily_limit=True,
                        relax_capacity=True,
                        relax_teacher_days=True,
                        relax_min_daily=True,
                        exhaustive=True,
                    )

                # ---- TIER 4 — LAST RESORT: relax time-window too ---- #
                if placement is None:
                    placement = self._find_slot(
                        subj_list, levels, nta, is_nta4, block,
                        teacher_busy, room_busy, nta_busy,
                        used_days_group, nta_daily_count,
                        allowed_weekdays=None,
                        teacher_days=teacher_days,
                        relax_day_reuse=True,
                        relax_room_preference=True,
                        relax_daily_limit=True,
                        relax_capacity=True,
                        relax_time_window=True,
                        relax_teacher_days=True,
                        relax_min_daily=True,
                        exhaustive=True,
                    )

                if placement is None:
                    continue

                day_id, slots, room_idx = placement

                for s in subj_list:
                    for ts in slots:
                        self.genes.append({
                            "subject_id": s["id"],
                            "teacher_id": s["teacher_id"],
                            "course_id":  s["course_id"],
                            "nta_level":  s["nta_level"],
                            "timeslot_id": ts,
                            "room_id":    room_idx,
                            "day_id":     day_id,
                            "session_id": session_id,
                        })

                for s in subj_list:
                    for ts in slots:
                        teacher_busy.add((s["teacher_id"], day_id, ts))
                        room_busy.add((room_idx, day_id, ts))
                        nta_busy.add((s["course_id"], s["nta_level"], day_id, ts))
                    teacher_days[s["teacher_id"]].add(day_id)
                    daily_key = (s["course_id"], s["nta_level"], day_id)
                    nta_daily_count[daily_key] = (
                        nta_daily_count.get(daily_key, 0) + block
                    )

                used_days_group.append(day_id)

        self.enforce_permanent_theory_rooms()

    # ================================================================== #
    # ROOM FIX                                                            #
    # ================================================================== #

    def enforce_permanent_theory_rooms(self):

        t = self.timetable
        subject_lookup = t.subject_map

        slot_occupant = {}
        for gene in self.genes:
            key = (gene["room_id"], gene["day_id"], gene["timeslot_id"])
            slot_occupant.setdefault(key, []).append(id(gene))

        group_rooms = defaultdict(list)
        for gene in self.genes:
            subject = subject_lookup.get(gene["subject_id"])
            key = (t.get_group_key(subject)
                   if subject else (gene["course_id"], gene["nta_level"]))
            group_rooms[key].append(gene)

        for key, genes in group_rooms.items():

            has_lab = any(
                subject_lookup.get(g["subject_id"], {}).get("required_lab")
                and subject_lookup.get(g["subject_id"], {}).get("required_lab") != "Theory"
                for g in genes
            )
            if has_lab:
                continue

            subj_list_for_group = [
                subject_lookup[g["subject_id"]]
                for g in genes
                if g["subject_id"] in subject_lookup
            ]

            if subj_list_for_group:
                levels = self._group_levels(subj_list_for_group)
                # 🔵 Never force degree groups back to a (possibly small)
                # permanent room — they must stay in large rooms.
                if "degree" in levels:
                    continue
                student_count = t.get_group_student_count(subj_list_for_group)
                if student_count > TimetableChromosome.STUDENT_THRESHOLD:
                    continue

            pairs = sorted({(g["course_id"], g["nta_level"]) for g in genes})

            if len(pairs) == 1:
                course_id, nta = pairs[0]
                permanent_rooms = t.get_permanent_rooms(course_id, nta)
                if not permanent_rooms:
                    continue
                preferred_room = permanent_rooms[0]
            else:
                permanent_lists = [
                    set(t.get_permanent_rooms(cid, nta)) for cid, nta in pairs
                ]
                if any(not pl for pl in permanent_lists):
                    continue
                common = set.intersection(*permanent_lists)
                if not common:
                    continue
                first_cid, first_nta = pairs[0]
                ordered = [r for r in t.get_permanent_rooms(first_cid, first_nta)
                           if r in common]
                preferred_room = ordered[0] if ordered else sorted(common)[0]

            room_idx = None
            for i, r in enumerate(t.rooms):
                if r["id"] == preferred_room:
                    room_idx = i
                    break
            if room_idx is None:
                continue

            own_gene_ids = {id(g) for g in genes}

            can_force = True
            for gene in genes:
                if gene["room_id"] == room_idx:
                    continue
                new_key = (room_idx, gene["day_id"], gene["timeslot_id"])
                occupants = slot_occupant.get(new_key, [])
                if any(occ_id not in own_gene_ids for occ_id in occupants):
                    can_force = False
                    break

            if not can_force:
                continue

            for gene in genes:
                old_key = (gene["room_id"], gene["day_id"], gene["timeslot_id"])
                new_key = (room_idx, gene["day_id"], gene["timeslot_id"])
                if old_key != new_key:
                    if old_key in slot_occupant:
                        slot_occupant[old_key] = [
                            x for x in slot_occupant[old_key] if x != id(gene)
                        ]
                        if not slot_occupant[old_key]:
                            del slot_occupant[old_key]
                    slot_occupant.setdefault(new_key, []).append(id(gene))
                gene["room_id"] = room_idx

    # ================================================================== #
    # FITNESS                                                             #
    # ================================================================== #

    def calculate_fitness(self, timetable):

        penalty = 0

        teacher_assign = {}
        room_assign    = {}
        nta_assign     = {}
        subject_count  = {}

        for g in self.genes:
            subject_count[g["subject_id"]] = (
                subject_count.get(g["subject_id"], 0) + 1
            )

        expected = getattr(self, "expected_blocks", None)

        # ---- 1. Session-count hard constraint ---- #
        for s in timetable.subjects:
            target = expected.get(s["id"]) if expected else 3
            if target is None:
                target = sum(timetable.build_blocks(s.get("credit_hour")))
            if subject_count.get(s["id"], 0) != target:
                penalty += 100000

        # ---- 2. Teacher double-booking ---- #
        for g in self.genes:
            key = (g["teacher_id"], g["day_id"], g["timeslot_id"])
            if key in teacher_assign:
                penalty += 100000
            teacher_assign[key] = True

        # ---- 3. Room double-booking ---- #
        for g in self.genes:
            key = (g["room_id"], g["day_id"], g["timeslot_id"])
            if key in room_assign:
                penalty += 100000
            room_assign[key] = True

        # ---- 4. NTA double-booking ---- #
        for g in self.genes:
            key = (g["course_id"], g["nta_level"], g["day_id"], g["timeslot_id"])
            if key in nta_assign:
                penalty += 100000
            nta_assign[key] = True

        # ---- 5. NTA-4 morning window ---- #
        for g in self.genes:
            if timetable.is_nta4(g["nta_level"]):
                day_name = timetable.days[g["day_id"]]["day_name"].lower()
                hour     = timetable.timeslot_start_hours[g["timeslot_id"]]
                if day_name not in ("saturday", "sunday") and not (8 <= hour < 16):
                    penalty += 50000

        # ---- 6. Degree time-window penalty ---- #
        # Weekday: 14:00–19:00; Weekend: 08:00–16:00
        for g in self.genes:
            course = timetable.course_map.get(g["course_id"], {})
            level  = course.get("course_level", "").lower()
            if level == "degree" and not timetable.is_nta4(g["nta_level"]):
                day_name = timetable.days[g["day_id"]]["day_name"].lower()
                hour     = timetable.timeslot_start_hours[g["timeslot_id"]]
                if day_name in ("saturday", "sunday"):
                    if not (8 <= hour < 16):
                        penalty += 30000
                else:
                    if not (14 <= hour < 19):
                        penalty += 50000

        # ---- 7. Degree room-capacity penalty ---- #
        # Every degree (non-lab, non-nta4) gene must be in a room cap >= 80
        for g in self.genes:
            course = timetable.course_map.get(g["course_id"], {})
            level  = course.get("course_level", "").lower()
            if level == "degree" and not timetable.is_nta4(g["nta_level"]):
                room = timetable.rooms[g["room_id"]]
                if room.get("type") != "Lab":
                    if (room.get("capacity") or 0) < self.LARGE_THEORY_CAP:
                        penalty += 40000

        # ---- 8. Diploma-in-large-room: must start before 14:00 weekday ---- #
        for g in self.genes:
            course = timetable.course_map.get(g["course_id"], {})
            level  = course.get("course_level", "").lower()
            if level != "diploma":
                continue
            if timetable.is_nta4(g["nta_level"]):
                continue
            day_name = timetable.days[g["day_id"]]["day_name"].lower()
            if day_name in ("saturday", "sunday"):
                continue
            room = timetable.rooms[g["room_id"]]
            if room["id"] in timetable.large_room_ids:
                hour = timetable.timeslot_start_hours[g["timeslot_id"]]
                if hour >= 14:
                    penalty += 40000

        # ---- 9. Multi-day split: every subject must span >= 2 days ---- #
        # Siku moja ichukuwe vipindi 2, siku nyingine ichukue kilichobaki.
        # If all timeslots of a subject land on the same day → penalty.
        subj_days = defaultdict(set)
        for g in self.genes:
            subj_days[g["subject_id"]].add(g["day_id"])

        for subj_id, days_used in subj_days.items():
            subj = timetable.subject_map.get(subj_id)
            if subj is None:
                continue
            try:
                ch = int(subj.get("credit_hour", 2))
            except (TypeError, ValueError):
                ch = 2
            # Only penalise if credit_hour >= 2 (ch==1 cannot split)
            if ch >= 2 and len(days_used) < 2:
                penalty += 60000

        # ---- 10. No-gap / consecutive sessions penalty ---- #
        daily_slots = defaultdict(set)
        for g in self.genes:
            key = (g["course_id"], g["nta_level"], g["day_id"])
            daily_slots[key].add(g["timeslot_id"])

        for key, slots in daily_slots.items():
            if len(slots) <= 1:
                if len(slots) == 1:
                    penalty += (TimetableChromosome.MIN_DAILY_SESSIONS - 1) * 8000
                continue
            ordered = sorted(slots)
            span = ordered[-1] - ordered[0] + 1
            gaps = span - len(ordered)

            if gaps > 0:
                base_gap_penalty = gaps * 5000
                max_gap = max(
                    ordered[i+1] - ordered[i] - 1
                    for i in range(len(ordered) - 1)
                )
                if max_gap >= 3:
                    base_gap_penalty += max_gap * 15000
                elif max_gap == 2:
                    base_gap_penalty += max_gap * 8000
                elif max_gap == 1:
                    base_gap_penalty += max_gap * 3000
                penalty += base_gap_penalty

            if len(slots) < TimetableChromosome.MIN_DAILY_SESSIONS:
                penalty += (TimetableChromosome.MIN_DAILY_SESSIONS - len(slots)) * 8000

        # ---- 11. Teacher max-days penalty ---- #
        teacher_day_sets = defaultdict(set)
        for g in self.genes:
            teacher_day_sets[g["teacher_id"]].add(g["day_id"])

        for teacher_id, days_used in teacher_day_sets.items():
            if len(days_used) > TimetableChromosome.MAX_TEACHER_DAYS:
                penalty += (
                    (len(days_used) - TimetableChromosome.MAX_TEACHER_DAYS) * 40000
                )

        # ---- 12. Shared-group cohesion: same day + room for all members ---- #
        session_groups = defaultdict(set)
        for g in self.genes:
            sid = g.get("session_id")
            if sid is None:
                continue
            session_groups[sid].add((g["day_id"], g["room_id"]))

        for sid, combos in session_groups.items():
            if len(combos) > 1:
                penalty += 20000 * (len(combos) - 1)

        # ---- 13. Large-group room-capacity penalty ---- #
        session_subject_ids = defaultdict(set)
        for g in self.genes:
            sid = g.get("session_id")
            if sid is None:
                continue
            session_subject_ids[sid].add(g["subject_id"])

        room_by_idx = {i: r for i, r in enumerate(timetable.rooms)}

        for sid, subject_ids in session_subject_ids.items():
            subj_list = [
                timetable.subject_map[s_id]
                for s_id in subject_ids
                if s_id in timetable.subject_map
            ]
            if not subj_list:
                continue
            student_count = timetable.get_group_student_count(subj_list)
            if student_count <= TimetableChromosome.STUDENT_THRESHOLD:
                continue
            is_lab = any(
                s.get("required_lab") and s.get("required_lab") != "Theory"
                for s in subj_list
            )
            min_cap = (
                TimetableChromosome.LARGE_LAB_CAP if is_lab
                else TimetableChromosome.LARGE_THEORY_CAP
            )
            session_room_idxs = {
                g["room_id"] for g in self.genes if g.get("session_id") == sid
            }
            for room_idx in session_room_idxs:
                room = room_by_idx.get(room_idx)
                if room is None:
                    continue
                if (room.get("capacity") or 0) < min_cap:
                    penalty += 15000

        # ---- 14. Degree student-count room-priority penalty ---- #
        # NTAs with more students should prefer larger rooms.
        # Penalise lightly when a high-student degree NTA lands in a
        # smaller large-room while a low-student NTA occupies a bigger one.
        # (Soft nudge — not a hard constraint.)
        degree_session_room = {}   # session_id -> (room cap, student_count)
        for g in self.genes:
            course = timetable.course_map.get(g["course_id"], {})
            level  = course.get("course_level", "").lower()
            if level != "degree":
                continue
            if timetable.is_nta4(g["nta_level"]):
                continue
            sid  = g.get("session_id")
            room = room_by_idx.get(g["room_id"])
            if sid is None or room is None:
                continue
            if sid not in degree_session_room:
                sc = timetable.get_student_count(g["course_id"], g["nta_level"])
                degree_session_room[sid] = (room.get("capacity") or 0, sc)

        if len(degree_session_room) > 1:
            items = list(degree_session_room.values())
            for i in range(len(items)):
                for j in range(i + 1, len(items)):
                    cap_i, sc_i = items[i]
                    cap_j, sc_j = items[j]
                    # If NTA i has more students but a SMALLER room than NTA j
                    if sc_i > sc_j and cap_i < cap_j:
                        penalty += 5000
                    elif sc_j > sc_i and cap_j < cap_i:
                        penalty += 5000

        self.fitness = 1 / (1 + penalty)
        return self.fitness