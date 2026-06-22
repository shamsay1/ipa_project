import json
import sys
import os
import traceback

sys.path.append(os.path.dirname(os.path.abspath(__file__)))


def main():
    try:
        if len(sys.argv) < 2:
            print(json.dumps({"error": "Usage: python main.py <data_file>"}))
            sys.exit(1)

        with open(sys.argv[1], "r", encoding="utf-8") as f:
            data = json.load(f)

        from Timetable import Timetable
        from TimetableGA import TimetableGA

        timetable = Timetable(
            data["subjects"],
            data["teachers"],
            data["rooms"],
            data["timeslots"],
            data["days"],
            data["courses"],
            data.get("course_rooms", [])
        )

        ga = TimetableGA(
            population_size=30,
            mutation_rate=0.03,
            crossover_rate=0.8,
            elitism_count=2,
            max_generations=100,
            time_limit=120
        )

        best = ga.evolve(timetable)

        result = []
        for gene in best.genes:
            result.append({
                "subject_id": gene["subject_id"],
                "teacher_id": gene["teacher_id"],
                "course_id": gene["course_id"],
                "nta_level": gene["nta_level"],
                "timeslot_id": timetable.timeslots[gene["timeslot_id"]]["id"],
                "room_id": timetable.rooms[gene["room_id"]]["id"],
                "day_id": timetable.days[gene["day_id"]]["id"]
            })

        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({"error": str(e), "traceback": traceback.format_exc()}))
        sys.exit(1)


if __name__ == "__main__":
    main()