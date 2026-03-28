import random
from collections import defaultdict

class TimetableChromosome:

    def __init__(self, timetable):
        self.timetable = timetable
        self.genes = []
        self.fitness = 0.0

    # ---------------- TIME RULES ---------------- #

    def allowed_time(self, level, day_name, hour):

        # WEEKEND
        if day_name in ["saturday", "sunday"]:
            return hour >= 8

        # DEGREE → evening only
        if level == "degree":
            return hour >= 16

        # DIPLOMA → daytime
        if level == "diploma":
            return 8 <= hour < 16

        return True


    # ---------------- GENERATE ---------------- #

    def generate_random(self):

        self.genes = []
        t = self.timetable

        teacher_busy = set()
        room_busy = set()
        nta_busy = set()
        nta_daily_count = {}

        for subject in t.subjects:

            subject_id = subject["id"]
            teacher_id = subject["teacher_id"]
            course_id = subject["course_id"]
            nta = subject["nta_level"]

            course = t.course_map.get(course_id, {})
            level = course.get("course_level", "").lower()

            permanent_rooms = t.get_permanent_rooms(course_id, nta)

            blocks = [2,1]  # total vipindi = 3
            used_days = []

            # 🔵 DEGREE: choose only 2–3 weekdays
            if level == "degree":
                weekday_ids = [
                    i for i, d in enumerate(t.days)
                    if d["day_name"].lower() not in ["saturday", "sunday"]
                ]
                random.shuffle(weekday_ids)
                allowed_weekdays = weekday_ids[:random.randint(2,3)]
            else:
                allowed_weekdays = None

            for block in blocks:

                scheduled = False

                for _ in range(400):

                    day_id = random.randrange(len(t.days))
                    day_name = t.days[day_id]["day_name"].lower()

                    # ---------------- DIPLOMA RULE ---------------- #
                    if level == "diploma":
                        # 🚫 avoid weekend kabisa
                        if day_name in ["saturday","sunday"]:
                            continue

                    # ---------------- DEGREE RULE ---------------- #
                    if level == "degree":

                        # weekday lazima iwe selected 2–3 days
                        if day_name not in ["saturday","sunday"] and day_id not in allowed_weekdays:
                            continue

                    # avoid kutumia siku moja mara nyingi
                    if day_id in used_days:
                        continue

                    # ---------- SLOT FILTER ---------- #

                    valid_slots = []

                    for idx, hour in enumerate(t.timeslot_start_hours):

                        if day_name == "friday" and hour == 12:
                            continue

                        if not self.allowed_time(level, day_name, hour):
                            continue

                        valid_slots.append(idx)

                    valid_slots.sort()

                    for i in range(len(valid_slots)):

                        if i + block > len(valid_slots):
                            break

                        slots = valid_slots[i:i+block]

                        if slots[-1] != slots[0] + (block - 1):
                            continue

                        # ---------- CONFLICT CHECK ---------- #

                        conflict = False

                        for ts in slots:

                            if (teacher_id, day_id, ts) in teacher_busy:
                                conflict = True
                                break

                            if (course_id, nta, day_id, ts) in nta_busy:
                                conflict = True
                                break

                        if conflict:
                            continue

                        # ---------- ROOM SELECTION ---------- #

                        if subject.get("required_lab") != "Theory":

                            candidate_rooms = [
                                r for r in t.rooms
                                if r["type"] == "Lab"
                                and r.get("practical_type") == subject.get("required_lab")
                            ]

                        else:

                            if permanent_rooms:
                                candidate_rooms = [
                                    r for r in t.rooms
                                    if r["id"] in permanent_rooms
                                ]
                            else:
                                candidate_rooms = [
                                    r for r in t.rooms
                                    if r["type"] != "Lab"
                                ]

                        random.shuffle(candidate_rooms)

                        for room in candidate_rooms:

                            room_idx = next(i for i, r in enumerate(t.rooms)
                                            if r["id"] == room["id"])

                            room_conflict = False

                            for ts in slots:
                                if (room_idx, day_id, ts) in room_busy:
                                    room_conflict = True
                                    break

                            if room_conflict:
                                continue

                            # ---------- DAILY LIMIT ---------- #

                            daily_key = (course_id, nta, day_id)
                            current_count = nta_daily_count.get(daily_key, 0)
                            count = current_count + block

                            # DEGREE weekday max 3
                            if level == "degree" and day_name not in ["saturday","sunday"]:
                                if count > 3:
                                    continue

                            # 🚫 NO LIMIT for weekend (degree)
                            # diploma already restricted above

                            if count > 8:
                                continue

                            # ---------- SAVE ---------- #

                            for ts in slots:

                                self.genes.append({
                                    "subject_id": subject_id,
                                    "teacher_id": teacher_id,
                                    "course_id": course_id,
                                    "nta_level": nta,
                                    "timeslot_id": ts,
                                    "room_id": room_idx,
                                    "day_id": day_id
                                })

                                teacher_busy.add((teacher_id, day_id, ts))
                                room_busy.add((room_idx, day_id, ts))
                                nta_busy.add((course_id, nta, day_id, ts))

                            nta_daily_count[daily_key] = count
                            used_days.append(day_id)

                            scheduled = True
                            break

                        if scheduled:
                            break

                    if scheduled:
                        break

        # enforce room
        self.enforce_permanent_theory_rooms()


    # ---------------- ROOM FIX ---------------- #

    def enforce_permanent_theory_rooms(self):

        t = self.timetable
        group_rooms = defaultdict(list)

        for gene in self.genes:
            key = (gene["course_id"], gene["nta_level"])
            group_rooms[key].append(gene)

        for key, genes in group_rooms.items():

            course_id, nta = key
            permanent_rooms = t.get_permanent_rooms(course_id, nta)

            if not permanent_rooms:
                continue

            preferred_room = permanent_rooms[0]

            room_idx = None
            for i, r in enumerate(t.rooms):
                if r["id"] == preferred_room:
                    room_idx = i
                    break

            if room_idx is None:
                continue

            for g in genes:
                g["room_id"] = room_idx


    # ---------------- FITNESS ---------------- #

    def calculate_fitness(self, timetable):

        penalty = 0

        teacher_assign = {}
        room_assign = {}
        nta_assign = {}

        subject_count = {}

        for g in self.genes:
            subject_id = g["subject_id"]
            subject_count[subject_id] = subject_count.get(subject_id,0) + 1

        for s in timetable.subjects:
            if subject_count.get(s["id"],0) != 3:
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

        self.fitness = 1 / (1 + penalty)
        return self.fitness