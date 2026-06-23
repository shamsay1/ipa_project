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

        # 🔵 FIX 5 — DEGREE WEEKDAY: window is now 14:00–18:00 only
        # (never reaches night hours like 20:00). Max 3 sessions/day
        # is still enforced separately in the daily-limit check.
        if level == "degree":
            return 14 <= hour < 18

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
    # 🔵 FIX 4 — STUDENT-COUNT-AWARE ROOM SELECTION                       #
    #
    # Rule (applies to EVERY subject/group, shared or solo):
    #   1. Work out how many students attend this scheduling group via
    #      timetable.get_group_student_count(subj_list):
    #        - solo subject  -> students of its own course+nta_level
    #        - shared group  -> sum of students across every distinct
    #          course+nta_level pair attending the joint lecture
    #          (e.g. NTA-5 of course A + NTA-5 of course B + ...)
    #   2. If that total is GREATER THAN 60:
    #        - Theory subject -> must use a room with capacity >= 80
    #        - Practical/Lab subject -> must use a Lab room with
    #          capacity >= 50
    #   3. If the total is 60 or below -> use the normal room pool,
    #      no inflated capacity requirement.
    #
    # As before, this never overrides a HARD requirement (a lab
    # subject always needs a room of the matching practical_type); it
    # only narrows/widens the *candidate* room list, and always falls
    # back gracefully if no room meets the preferred capacity.
    # ------------------------------------------------------------------ #

    STUDENT_THRESHOLD = 60   # student-count cut-off that triggers a bigger room
    LARGE_THEORY_CAP   = 80  # min capacity for Theory rooms once threshold is passed
    LARGE_LAB_CAP      = 50  # min capacity for Lab rooms once threshold is passed

    # 🔵 FIX 5 — DAILY-LOAD / TEACHER-WORKLOAD CONSTANTS
    #
    # MIN_DAILY_SESSIONS: once an nta (course + nta_level) has ANY
    # session on a given day, that day should end up with at least
    # this many sessions (no lone single-period days). Enforced as a
    # strong soft rule: generation is biased to top up days that
    # already have 1-2 sessions before opening a brand-new day, and
    # calculate_fitness penalizes any day that still falls short. A
    # subject with credit_hour 1 cannot reach this alone — in that
    # case the penalty simply nudges other subjects to land on the
    # same day to make up the difference.
    #
    # MAX_TEACHER_DAYS: a teacher's subjects must not be spread across
    # more than this many distinct days in the week — i.e. every
    # teacher keeps at least (len(days) - MAX_TEACHER_DAYS) full days
    # free. Enforced as a hard-ish rule during generation/mutation,
    # relaxed only in the final "last resort" tiers, and penalized in
    # calculate_fitness if it still ends up violated.
    MIN_DAILY_SESSIONS = 3
    MAX_TEACHER_DAYS = 5

    def _boosted_days(self, subj_list, nta_daily_count):
        """
        Days that already have 1..(MIN_DAILY_SESSIONS-1) sessions for
        this nta — placing another block here helps reach the minimum
        instead of opening a fresh, under-filled day.
        """
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

    def _group_candidate_rooms(self, subj_list,
                                relax_room_preference=False,
                                relax_capacity=False):
        t = self.timetable

        student_count = t.get_group_student_count(subj_list)
        needs_large_room = student_count > self.STUDENT_THRESHOLD

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
                # No matching lab meets the capacity — there is no
                # bigger lab to fall back to (lab type must match), so
                # use whatever matching lab exists regardless of size.
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

        if needs_large_room and not relax_capacity:
            # First preference: a permanent room that is ALSO big
            # enough.
            filtered = [r for r in base
                        if (r.get("capacity") or 0) >= self.LARGE_THEORY_CAP]
            if filtered:
                return filtered
            # Next: any non-lab room in the whole system big enough —
            # the capacity requirement matters more than sticking to
            # the permanent room once the group is genuinely large.
            system_wide_large = [
                r for r in t.rooms
                if r["type"] != "Lab" and (r.get("capacity") or 0) >= self.LARGE_THEORY_CAP
            ]
            if system_wide_large:
                return system_wide_large
            # Nothing in the whole system is big enough — fall back to
            # the permanent/base room set, then any non-lab room.
            if base:
                return base
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
                    teacher_days=None,
                    relax_day_reuse=False, relax_room_preference=False,
                    relax_daily_limit=False, relax_capacity=False,
                    relax_time_window=False, relax_teacher_days=False,
                    relax_min_daily=False,
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
            # 🔵 FIX 5 — teacher weekly-day cap: don't open a brand-new
            # day for a teacher who is already spread across
            # MAX_TEACHER_DAYS distinct days.
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

        # 🔵 FIX 5 — bias random attempts toward days that already
        # have 1-2 sessions for this nta, so they get topped up to the
        # minimum instead of leaving them stuck below it. Still falls
        # through to fully random attempts the rest of the time so new
        # days can still be opened when needed.
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
        teacher_days = defaultdict(set)

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
                    teacher_days=teacher_days,
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
                        teacher_days=teacher_days,
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
                        teacher_days=teacher_days,
                        relax_day_reuse=True,
                        relax_room_preference=True,
                        relax_daily_limit=True,
                        relax_capacity=True,
                        relax_teacher_days=True,
                        relax_min_daily=True,
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

            # 🔵 FIX 4 — if this group needs a "large room" (student
            # count > threshold), do NOT force it back into a small
            # permanent room; leave the GA-selected large-capacity
            # room as-is.
            subj_list_for_group = [
                subject_lookup[g["subject_id"]]
                for g in genes
                if g["subject_id"] in subject_lookup
            ]
            if subj_list_for_group:
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
                if len(slots) == 1:
                    # 🔵 FIX 5 — a day with just 1 session for this nta
                    # falls short of MIN_DAILY_SESSIONS.
                    penalty += (TimetableChromosome.MIN_DAILY_SESSIONS - 1) * 8000
                continue
            ordered = sorted(slots)
            span = ordered[-1] - ordered[0] + 1
            gaps = span - len(ordered)
            if gaps > 0:
                penalty += gaps * 2000
            # 🔵 FIX 5 — still short of the minimum even though it's
            # more than one session that day (e.g. 2 sessions).
            if len(slots) < TimetableChromosome.MIN_DAILY_SESSIONS:
                penalty += (TimetableChromosome.MIN_DAILY_SESSIONS - len(slots)) * 8000

        # ---------------- FIX 5 — TEACHER MAX-DAYS PENALTY ---------- #
        teacher_day_sets = defaultdict(set)
        for g in self.genes:
            teacher_day_sets[g["teacher_id"]].add(g["day_id"])

        for teacher_id, days_used in teacher_day_sets.items():
            if len(days_used) > TimetableChromosome.MAX_TEACHER_DAYS:
                penalty += (
                    (len(days_used) - TimetableChromosome.MAX_TEACHER_DAYS) * 40000
                )

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

        # ---------------- FIX 4 — LARGE-GROUP ROOM-CAPACITY PENALTY - #
        # If a session's student count exceeds the threshold, every
        # room used for that session should meet the large-room
        # capacity (Theory >= 80, Lab >= 50). This nudges the GA away
        # from squeezing a big group into an undersized room, even
        # though _group_candidate_rooms already steers placement that
        # way during generation/mutation.
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

        self.fitness = 1 / (1 + penalty)
        return self.fitness