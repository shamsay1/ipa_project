import random
from collections import defaultdict


class TimetableChromosome:

    def __init__(self, timetable):
        self.timetable = timetable
        self.genes = []
        self.fitness = 0.0

    # ================================================================== #
    # TIME RULES                                                          #
    # ================================================================== #

    def allowed_time(self, level, nta_level, day_name, hour):

        if day_name in ["saturday", "sunday"]:
            # 🔵 FIX 3 — DEGREE WEEKEND: 08:00–16:00 only (many sessions
            # allowed here since weekday limit is 3; this is where degree
            # subjects get their bulk of sessions).
            if level == "degree":
                return 8 <= hour < 16
            # All other levels: from 08:00 onward on weekends
            return hour >= 8

        # 🔵 NTA-4 OVERRIDE (weekdays): morning only, regardless of
        # course_level (degree or diploma).
        if self.timetable.is_nta4(nta_level):
            return 8 <= hour < 16

        # 🔵 FIX 3 — DEGREE WEEKDAY: from 14:00, max 3 sessions/day
        # (max enforced in the daily-limit check, not here).
        if level == "degree":
            return hour >= 14

        # DIPLOMA weekday: 08:00–16:00 (unchanged)
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
        """True when the subjects carry a non-empty group_name (shared lecture)."""
        return any(s.get("group_name") for s in subj_list)

    # ------------------------------------------------------------------ #
    # 🔵 FIX 2 — CAPACITY-AWARE ROOM SELECTION FOR SHARED GROUPS         #
    # When several courses share the same lecture (group_name is set):    #
    #   Theory  → prefer rooms with capacity >= THEORY_CAP               #
    #   Practical / Lab → prefer rooms with capacity >= LAB_CAP           #
    # Falls back to any room of the right type if nothing qualifies.      #
    # ------------------------------------------------------------------ #

    THEORY_CAP = 50   # minimum capacity for shared-group Theory rooms
    LAB_CAP    = 50   # minimum capacity for shared-group Lab rooms

    def _group_candidate_rooms(self, subj_list,
                                relax_room_preference=False,
                                relax_capacity=False):
        t = self.timetable
        shared = self._is_shared_group(subj_list)

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
            if shared and not relax_capacity:
                filtered = [r for r in base
                            if (r.get("capacity") or 0) >= self.LAB_CAP]
                if filtered:
                    return filtered
                # fallback: any matching lab regardless of capacity
            return base

        # ---------- THEORY SUBJECTS ---------- #

        # Gather permanent rooms for the group (may span several courses)
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

        if shared and not relax_capacity:
            filtered = [r for r in base
                        if (r.get("capacity") or 0) >= self.THEORY_CAP]
            if filtered:
                return filtered
            # fallback 1: any non-lab room in the permanent set (or all)
            if base:
                return base
            # fallback 2: any non-lab room whatsoever
            return [r for r in t.rooms if r["type"] != "Lab"]

        return base if base else [r for r in t.rooms if r["type"] != "Lab"]

    # ================================================================== #
    # SLOT SEARCH — one block / one session                              #
    # ================================================================== #
    #
    # Relaxation flags (never touch hard conflict rules):
    #   relax_day_reuse   — allow a day the group already uses
    #   relax_room_pref   — ignore permanent-room preference
    #   relax_daily_limit — drop soft daily workload caps
    #   relax_capacity    — ignore room-capacity preference
    #   relax_time_window — ignore degree/diploma/nta4 time window
    #                       (LAST RESORT — ensures nothing is left out)
    #   exhaustive        — iterate every day, not random sampling
    # ================================================================== #

    def _find_slot(self, subj_list, levels, nta, is_nta4, block,
                    teacher_busy, room_busy, nta_busy,
                    used_days_group, nta_daily_count, allowed_weekdays,
                    relax_day_reuse=False, relax_room_preference=False,
                    relax_daily_limit=False, relax_capacity=False,
                    relax_time_window=False,
                    exhaustive=False, attempts=400):

        t = self.timetable

        # When time-window is relaxed we allow any hour on any day;
        # when diploma/nta4 weekends are normally blocked that is also
        # lifted so the subject can land somewhere.
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
                valid_slots = [
                    idx for idx, hour in enumerate(t.timeslot_start_hours)
                    if not (day_name == "friday" and hour == 12)
                    and self._group_allowed(levels, nta, day_name, hour)
                ]
            valid_slots.sort()

            for i in range(len(valid_slots)):
                if i + block > len(valid_slots):
                    break
                slots = valid_slots[i:i + block]
                if slots[-1] != slots[0] + (block - 1):
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
                            # 🔵 FIX 3 — degree weekday max = 3 (unchanged);
                            # degree weekends use the general max (8) so
                            # more sessions can land there.
                            if ("degree" in levels and not is_nta4
                                    and day_name not in ("saturday", "sunday")):
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

        for _ in range(attempts):
            day_id = random.randrange(len(t.days))
            result = attempt_day(day_id)
            if result:
                return result
        return None

    # ================================================================== #
    # GENERATE                                                            #
    # ================================================================== #

    def generate_random(self):

        self.genes = []
        t = self.timetable

        teacher_busy = set()
        room_busy = set()
        nta_busy = set()
        nta_daily_count = {}

        self.expected_blocks = {}

        session_counter = 0

        for subj_list, blocks in t.build_session_plan():

            levels = self._group_levels(subj_list)
            nta = subj_list[0].get("nta_level")
            is_nta4 = t.is_nta4(nta)

            for s in subj_list:
                self.expected_blocks[s["id"]] = sum(blocks)

            used_days_group = []

            allowed_weekdays = None
            if levels == {"degree"} and not is_nta4:
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
                    attempts=400,
                )

                # ---- TIER 2: exhaustive day search, drop day-reuse
                # and room-preference soft rules ---- #
                if placement is None:
                    placement = self._find_slot(
                        subj_list, levels, nta, is_nta4, block,
                        teacher_busy, room_busy, nta_busy,
                        used_days_group, nta_daily_count,
                        allowed_weekdays=None,
                        relax_day_reuse=True,
                        relax_room_preference=True,
                        exhaustive=True,
                    )

                # ---- TIER 3: also drop daily workload limits and
                # capacity preference ---- #
                if placement is None:
                    placement = self._find_slot(
                        subj_list, levels, nta, is_nta4, block,
                        teacher_busy, room_busy, nta_busy,
                        used_days_group, nta_daily_count,
                        allowed_weekdays=None,
                        relax_day_reuse=True,
                        relax_room_preference=True,
                        relax_daily_limit=True,
                        relax_capacity=True,
                        exhaustive=True,
                    )

                # ---- TIER 4 (FIX 1) — LAST RESORT: also drop the
                # time-window rule (degree/diploma/nta4 hour window).
                # Only hard conflicts are still respected.
                # Guarantees every subject is placed unless there is a
                # genuine double-booking impossibility (e.g. a teacher
                # with more subjects than there are hours in the week). ---- #
                if placement is None:
                    placement = self._find_slot(
                        subj_list, levels, nta, is_nta4, block,
                        teacher_busy, room_busy, nta_busy,
                        used_days_group, nta_daily_count,
                        allowed_weekdays=None,
                        relax_day_reuse=True,
                        relax_room_preference=True,
                        relax_daily_limit=True,
                        relax_capacity=True,
                        relax_time_window=True,
                        exhaustive=True,
                    )

                if placement is None:
                    # Genuinely impossible (teacher/room double-booked
                    # every single slot in the timetable) — skip.
                    continue

                day_id, slots, room_idx = placement

                for s in subj_list:
                    for ts in slots:
                        self.genes.append({
                            "subject_id": s["id"],
                            "teacher_id": s["teacher_id"],
                            "course_id": s["course_id"],
                            "nta_level": s["nta_level"],
                            "timeslot_id": ts,
                            "room_id": room_idx,
                            "day_id": day_id,
                            "session_id": session_id,
                        })

                for s in subj_list:
                    for ts in slots:
                        teacher_busy.add((s["teacher_id"], day_id, ts))
                        room_busy.add((room_idx, day_id, ts))
                        nta_busy.add((s["course_id"], s["nta_level"], day_id, ts))

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

        # Build a map of currently occupied (room_idx, day, ts) → gene ids
        # so we can check for conflicts before forcing a room change.
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

            # Never move lab-required subjects out of their lab room
            has_lab = any(
                subject_lookup.get(g["subject_id"], {}).get("required_lab")
                and subject_lookup.get(g["subject_id"], {}).get("required_lab") != "Theory"
                for g in genes
            )
            if has_lab:
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
        room_assign = {}
        nta_assign = {}
        subject_count = {}

        for g in self.genes:
            subject_count[g["subject_id"]] = (
                subject_count.get(g["subject_id"], 0) + 1
            )

        expected = getattr(self, "expected_blocks", None)

        for s in timetable.subjects:
            target = expected.get(s["id"]) if expected else 3
            if target is None:
                target = sum(timetable.build_blocks(s.get("credit_hour")))
            if subject_count.get(s["id"], 0) != target:
                penalty += 100000

        for g in self.genes:
            key = (g["teacher_id"], g["day_id"], g["timeslot_id"])
            if key in teacher_assign:
                penalty += 100000
            teacher_assign[key] = True

        for g in self.genes:
            key = (g["room_id"], g["day_id"], g["timeslot_id"])
            if key in room_assign:
                penalty += 100000
            room_assign[key] = True

        for g in self.genes:
            key = (g["course_id"], g["nta_level"], g["day_id"], g["timeslot_id"])
            if key in nta_assign:
                penalty += 100000
            nta_assign[key] = True

        # ---------------- NTA-4 TIME WINDOW PENALTY ---------------- #
        for g in self.genes:
            if timetable.is_nta4(g["nta_level"]):
                day_name = timetable.days[g["day_id"]]["day_name"].lower()
                hour = timetable.timeslot_start_hours[g["timeslot_id"]]
                if day_name not in ["saturday", "sunday"] and not (8 <= hour < 16):
                    penalty += 50000

        # ---------------- DEGREE TIME WINDOW PENALTY --------------- #
        for g in self.genes:
            course = timetable.course_map.get(g["course_id"], {})
            level = course.get("course_level", "").lower()
            if level == "degree" and not timetable.is_nta4(g["nta_level"]):
                day_name = timetable.days[g["day_id"]]["day_name"].lower()
                hour = timetable.timeslot_start_hours[g["timeslot_id"]]
                if day_name in ("saturday", "sunday"):
                    if not (8 <= hour < 16):
                        penalty += 30000
                else:
                    if hour < 14:
                        penalty += 30000

        # ---------------- NO-GAP / CONSECUTIVE PENALTY ------------- #
        daily_slots = defaultdict(set)
        for g in self.genes:
            key = (g["course_id"], g["nta_level"], g["day_id"])
            daily_slots[key].add(g["timeslot_id"])

        for key, slots in daily_slots.items():
            if len(slots) <= 1:
                continue
            ordered = sorted(slots)
            span = ordered[-1] - ordered[0] + 1
            gaps = span - len(ordered)
            if gaps > 0:
                penalty += gaps * 2000

        # ---------------- SHARED-GROUP COHESION PENALTY ------------ #
        session_groups = defaultdict(set)
        for g in self.genes:
            sid = g.get("session_id")
            if sid is None:
                continue
            session_groups[sid].add((g["day_id"], g["room_id"]))

        for sid, combos in session_groups.items():
            if len(combos) > 1:
                penalty += 20000 * (len(combos) - 1)

        self.fitness = 1 / (1 + penalty)
        return self.fitness