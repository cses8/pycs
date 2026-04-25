<template>
  <section
    class="mx-auto grid w-full max-w-7xl gap-8 rounded-xl border border-slate-200 bg-white p-6 md:p-8 lg:grid-cols-[20rem_minmax(0,1fr)] dark:border-surface-800 dark:bg-surface-900"
  >
    <div class="flex flex-col justify-start">
      <span
        class="mb-4 inline-flex w-fit rounded-full bg-indigo-50 px-3 py-1 text-sm font-semibold text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-200"
      >
        Academic pathways
      </span>
      <h2 class="mb-4 flex flex-col text-3xl font-bold leading-tight md:text-4xl">
        <span class="text-slate-900 dark:text-surface-0">Our Academic</span>
        <span class="text-indigo-700 dark:text-indigo-200">Program</span>
      </h2>
      <p
        class="max-w-3xl text-base leading-7 text-slate-600 dark:text-surface-300"
      >
        Offering a complete educational pathway, these programs guide learners
        from foundational readiness and play-based discovery in Preschool,
        through holistic development and core competencies in Primary School &
        Middle School. Junior High School builds academic excellence and
        character, leading into specialized Senior High tracks like STEM, ABM,
        and HUMSS, preparing students for higher education and future careers.
      </p>
    </div>

    <div class="flex flex-col">
      <NuxtLink
        v-for="(academicProgram, index) in academicPrograms"
        :key="academicProgram.id"
        :to="academicProgram.path"
        class="group grid grid-cols-[3.5rem_minmax(0,1fr)] gap-4 rounded-lg px-2 py-1 transition-colors hover:bg-indigo-50 dark:hover:bg-indigo-500/10"
        v-motion
        :initial="{ opacity: 0, y: 24 }"
        :visible="{
          opacity: 1,
          y: 0,
          transition: {
            type: 'spring',
            stiffness: 160,
            damping: 22,
            mass: 1,
            delay: 120 + index * 80,
          },
        }"
      >
        <span class="flex flex-col items-center">
          <span
            class="flex h-12 w-12 items-center justify-center rounded-lg border border-slate-200 bg-white dark:border-surface-700 dark:bg-surface-950"
          >
            <Icon
              :name="academicProgram.icon"
              class="text-indigo-700 dark:text-indigo-200"
              size="1.6rem"
            />
          </span>
          <span
            v-if="index + 1 < academicPrograms.length"
            class="my-2 h-full min-h-14 w-px bg-slate-200 dark:bg-surface-700"
          />
        </span>
        <span class="min-w-0 pb-8">
          <span
            class="block text-lg font-semibold leading-tight text-slate-950 group-hover:text-indigo-800 dark:text-surface-0 dark:group-hover:text-indigo-200"
          >
            {{ academicProgram.title }}
          </span>
          <span
            class="mt-2 block max-w-3xl text-sm leading-6 text-slate-600 md:text-base md:leading-7 dark:text-surface-300"
          >
            {{ academicProgram.description }}
          </span>
        </span>
      </NuxtLink>
    </div>
  </section>
</template>

<script setup lang="ts">
const academicPrograms = ref([
  {
    id: ulid(),
    icon: 'healthicons:child-care-outline', // Icon left blank as requested
    title: 'Preschool',
    path: '/academics/curriculum/pre-school',
    description:
      'Fostering readiness for Grade 1, the Preschool program develops foundational skills in young learners through play, exploration, and discovery. Within a nurturing environment, it covers numeracy, literacy, science, arts, and languages, cultivating communication, creativity, self-help skills, and early critical thinking abilities.',
  },
  {
    id: ulid(),
    icon: 'mdi:book-open-variant-outline', // Icon left blank as requested
    title: 'Primary School & Middle School',
    path: '/academics/curriculum/primary-school-middle-school',
    description:
      'Experience an education nurturing God-given potential while developing critical thinking, communication, collaboration, and creativity. The integrated curriculum blends core academics, arts, practical skills, technology, and languages, fostering character formation, cultural appreciation, and lifelong learning for holistic student development and readiness for future challenges.',
  },
  {
    id: ulid(),
    icon: 'mdi:rocket-launch-outline', // Icon left blank as requested
    title: 'Junior High School',
    path: '/academics/curriculum/junior-high-school',
    description:
      'Building on elementary foundations, the Junior High School program fosters academic excellence and character formation aligned with the national K-12 curriculum. It employs the Understanding by Design framework and offers specialized tracks, ensuring comprehensive preparation in core subjects and readiness for Senior High School challenges.',
  },
  {
    id: ulid(),
    icon: 'ph:graduation-cap-bold', // Icon left blank as requested
    title: 'Senior High School',
    path: '/academics/curriculum/senior-high-school',
    description:
      'Providing specialized pathways aligned with the K-12 curriculum, the Senior High School program allows students to choose tracks like STEM, ABM, HUMSS, GAS, or TVL based on aptitude and interest. This prepares graduates for tertiary education, employment, or entrepreneurship through targeted core, applied, and specialized subjects.',
  },
])
</script>
