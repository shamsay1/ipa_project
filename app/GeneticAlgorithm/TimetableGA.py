import random
import time
from copy import deepcopy
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

        for generation in range(self.max_generations):

            if time.time() - start_time > self.time_limit:
                break

            population.sort(key=lambda x: x.fitness, reverse=True)
            new_population = population[:self.elitism_count]

            while len(new_population) < self.population_size:

                p1 = random.choice(population[:10])
                p2 = random.choice(population[:10])

                cp = random.randint(1, len(p1.genes) - 1)

                child = TimetableChromosome(timetable)
                child.genes = deepcopy(p1.genes[:cp] + p2.genes[cp:])
                self.mutate(child, timetable)
                child.calculate_fitness(timetable)

                new_population.append(child)

            population = new_population

        return population[0]

    def mutate(self, chromosome, timetable):

        for gene in chromosome.genes:
            if random.random() < self.mutation_rate:
                gene["day_id"] = random.randrange(len(timetable.days))
                gene["timeslot_id"] = random.randrange(len(timetable.timeslots))
                gene["room_id"] = random.randrange(len(timetable.rooms))
