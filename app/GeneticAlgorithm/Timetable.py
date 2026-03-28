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
