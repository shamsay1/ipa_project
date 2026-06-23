import random
import time
from copy import deepcopy
from collections import defaultdict
from TimetableChromosome import TimetableChromosome


class TimetableGA:

    def __init__(self, population_size=30, mutation_rate=0.03,
                 crossover_rate=0.8, elitism_count=2,
                 max_generations=100, time_limit=120):

        self.population_size = population_size
        self.mutation_rate = mutation_rate
        self.crossover_rate = crossover_rate
        self.elitism_count = elitism_count
        self.max_generations = max_generations
        self.time_limit = time_limit

    def evolve(self, timetable):

        start_time = time.time()
        population = []

        for _ in range(self.population_size):
            chrom = TimetableChromosome(timetable)
            chrom.generate_random()
            chrom.calculate_fitness(timetable)
            population.append(chrom)

        # 🔵 Canonical, fixed order of every scheduling "session".
        # Every chromosome labels its genes with these same ids, so a
        # crossover cut point always aligns the same logical lecture
        # across both parents. A session is always swapped as one
        # indivisible unit so shared-group subjects (same group_name)
        # can never be split between different days/rooms by crossover.
        session_ids = timetable.session_id_sequence()

        for generation in range(self.max_generations):

            if time.time() - start_time > self.time_limit:
                break

            population.sort(key=lambda x: x.fitness, reverse=True)
            new_population = population[:self.elitism_count]

            pool = population[:10] if len(population) >= 10 else population

            while len(new_population) < self.population_size:

                p1 = random.choice(pool)
                p2 = random.choice(pool)

                child = TimetableChromosome(timetable)

                if len(session_ids) > 1 and random.random() < self.crossover_rate:
                    # Session-aware crossover: take sessions 1…cp from p1,
                    # sessions cp+1…end from p2. Cutting on session
                    # boundaries guarantees every multi-slot block and every
                    # member of a shared group always come from the same
                    # parent — the child is internally consistent from birth.
                    cp = random.randint(1, len(session_ids) - 1)
                    left_ids  = set(session_ids[:cp])
                    right_ids = set(session_ids[cp:])

                    child.genes = deepcopy(
                        [g for g in p1.genes if g.get("session_id") in left_ids]
                        + [g for g in p2.genes if g.get("session_id") in right_ids]
                    )
                else:
                    child.genes = deepcopy(
                        p1.genes if p1.fitness >= p2.fitness else p2.genes
                    )

                # carry over expected session counts so fitness can
                # validate per-subject targets correctly
                child.expected_blocks = getattr(
                    p1, "expected_blocks", getattr(p2, "expected_blocks", {})
                )

                self.mutate(child, timetable)
                child.calculate_fitness(timetable)

                new_population.append(child)

            population = new_population

        population.sort(key=lambda x: x.fitness, reverse=True)
        return population[0]

    def mutate(self, chromosome, timetable):

        # 🔵 Mutate a whole "session" (one block) at a time instead of
        # one timeslot-gene at a time.  This keeps:
        #   - a 2-hour double block contiguous (both timeslots move together)
        #   - a shared group (subjects with the same group_name) always on
        #     the same day/room/timeslots because every gene in the group
        #     carries the same session_id and is moved as one atomic unit.

        sessions = defaultdict(list)
        for gene in chromosome.genes:
            sessions[gene.get("session_id")].append(gene)

        subject_lookup = timetable.subject_map
        helper = TimetableChromosome(timetable)

        for session_id, genes in sessions.items():

            if session_id is None or random.random() >= self.mutation_rate:
                continue

            genes_by_subject = defaultdict(list)
            for g in genes:
                genes_by_subject[g["subject_id"]].append(g)

            first_subject_id = genes[0]["subject_id"]
            block = len({g["timeslot_id"] for g in genes_by_subject[first_subject_id]})
            if block == 0:
                continue

            subj_list = [
                subject_lookup[sid]
                for sid in genes_by_subject
                if sid in subject_lookup
            ]
            if not subj_list:
                continue

            levels = set()
            for s in subj_list:
                course = timetable.course_map.get(s["course_id"], {})
                levels.add(course.get("course_level", "").lower())

            nta = subj_list[0].get("nta_level")
            is_nta4 = timetable.is_nta4(nta)

            # Diploma and nta-4 stay off weekends; degree can use weekends
            # (8:00–16:00 per FIX 3).
            weekend_blocked = is_nta4 or ("diploma" in levels)

            # 🔵 FIX 5 — teacher weekly-day cap during mutation: figure
            # out which days each involved teacher is already using
            # (from every OTHER session in this chromosome), so we
            # don't push a teacher past MAX_TEACHER_DAYS distinct days.
            session_gene_ids = {id(g) for g in genes}
            current_teacher_days = defaultdict(set)
            for g in chromosome.genes:
                if id(g) in session_gene_ids:
                    continue
                current_teacher_days[g["teacher_id"]].add(g["day_id"])

            teacher_ids = {s["teacher_id"] for s in subj_list}

            def teacher_day_ok(day_id):
                for tid in teacher_ids:
                    tdays = current_teacher_days.get(tid, set())
                    if day_id not in tdays and len(tdays) >= TimetableChromosome.MAX_TEACHER_DAYS:
                        return False
                return True

            candidate_days = list(range(len(timetable.days)))
            random.shuffle(candidate_days)

            new_day = None
            new_slots = None

            for enforce_teacher_cap in (True, False):
                for day_id in candidate_days:
                    if enforce_teacher_cap and not teacher_day_ok(day_id):
                        continue

                    day_name = timetable.days[day_id]["day_name"].lower()

                    if weekend_blocked and day_name in ("saturday", "sunday"):
                        continue

                    valid_slots = []
                    for idx, hour in enumerate(timetable.timeslot_start_hours):
                        if day_name == "friday" and hour == 12:
                            continue
                        ok = True
                        for level in levels:
                            if not helper.allowed_time(level, nta, day_name, hour):
                                ok = False; break
                        if ok:
                            valid_slots.append(idx)

                    valid_slots.sort()

                    for i in range(len(valid_slots)):
                        if i + block > len(valid_slots):
                            break
                        slots = valid_slots[i:i + block]
                        if slots[-1] != slots[0] + (block - 1):
                            continue
                        new_day = day_id
                        new_slots = slots
                        break

                    if new_slots:
                        break
                if new_slots:
                    break

            if not new_slots:
                # no valid time window found — leave this session as-is
                continue

            # 🔵 FIX 4 — pick the new room using the same student-count
            # / capacity-aware candidate list used during generation,
            # instead of a fully random room. Falls back to a fully
            # random room only if no candidate is available at all.
            candidate_rooms = helper._group_candidate_rooms(subj_list)
            if candidate_rooms:
                chosen_room = random.choice(candidate_rooms)
                new_room_idx = next(
                    (ri for ri, r in enumerate(timetable.rooms)
                     if r["id"] == chosen_room["id"]),
                    None
                )
                if new_room_idx is None:
                    new_room_idx = random.randrange(len(timetable.rooms))
            else:
                new_room_idx = random.randrange(len(timetable.rooms))

            ordered_new_slots = sorted(new_slots)

            for sid, glist in genes_by_subject.items():
                glist.sort(key=lambda g: g["timeslot_id"])
                for g, new_ts in zip(glist, ordered_new_slots):
                    g["day_id"] = new_day
                    g["timeslot_id"] = new_ts
                    g["room_id"] = new_room_idx