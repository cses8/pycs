<template>
  <div
    class="bg-surface-0 dark:bg-surface-950 px-6 py-20 md:px-12 lg:px-20 flex lg:flex-row flex-col gap-12 justify-center items-center"
  >
    <UiBlurReveal :duration="0.5">
      <div class="flex-1">
        <h2 class="text-5xl leading-tight font-bold flex flex-col mb-4">
          <span class="text-surface-900 dark:text-surface-0">Our Academic</span>
          <span class="text-blue-600">Program</span>
        </h2>
        <p
          class="text-lg leading-normal text-surface-600 dark:text-surface-400 mb-8 max-w-xl"
        >
          Offering a complete educational pathway, these programs guide learners
          from foundational readiness and play-based discovery in Preschool,
          through holistic development and core competencies in Primary School &
          Middle School. Junior High School builds academic excellence and
          character, leading into specialized Senior High tracks like STEM, ABM,
          and HUMSS, preparing students for higher education and future careers.
        </p>
      </div>
    </UiBlurReveal>
    <div class="flex-1 flex flex-col">
      <template
        v-for="(academicProgram, index) in academicPrograms"
        :key="academicProgram.id"
      >
        <div
          class="flex gap-6"
          v-motion
          :initial="{ opacity: 0, y: 50, rotateX: -30 }"
          :visible="{
            // Changed from :visible-once
            opacity: 1,
            y: 0,
            rotateX: 0,
            transition: {
              type: 'spring',
              stiffness: 150,
              damping: 20,
              mass: 1,
              delay: 200 + index * 100,
            },
          }"
        >
          <div class="flex flex-col items-center">
            <div
              class="w-14 h-14 flex items-center justify-center rounded-xl border bg-white/90 border-surface-200 dark:border-surface-700"
            >
              <Icon
                :name="academicProgram.icon"
                class="text-blue-600"
                size="2rem"
              />
            </div>
            <div class="w-px flex-1 bg-surface-200 dark:bg-surface-700" />
          </div>
          <div>
            <h5
              class="text-lg leading-tight font-semibold mb-2 text-surface-900 dark:text-surface-200"
            >
              {{ academicProgram.title }}
            </h5>
            <p
              class="leading-normal text-md text-surface-600 dark:text-surface-400 max-w-lg"
            >
              {{ academicProgram.description }}
            </p>
          </div>
        </div>

        <div
          v-if="index + 1 < academicPrograms.length"
          class="flex flex-col items-center w-14 h-14"
        >
          <div class="w-px flex-1 bg-surface-200 dark:bg-surface-700" />
          <div
            class="w-3 h-3 border border-surface-200 rounded-sm dark:border-surface-700"
          />
          <div class="w-px flex-1 bg-surface-200 dark:bg-surface-700" />
        </div>
      </template>
    </div>
  </div>
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
