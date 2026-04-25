<template>
  <div class="p-4">
    <div class="flex flex-col gap-4">
      <template v-for="(item, index) in routes" :key="item.id">
        <NuxtLink
          class="w-full h-full p-4 rounded-border"
          :class="
            route.path == item.path
              ? 'outline outline-offset-2 outline-blue-600'
              : ''
          "
          :style="`
						background: linear-gradient(
								180deg,
								rgba(0, 0, 0, 0.4) 0%,
								#000000 100%
							),
							url('/images/banner${index + 1}.webp');
					`"
          :to="item.path"
        >
          <span class="text-white font-medium mb-4">
            {{ item.title }}
          </span>
        </NuxtLink>
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()

const routes = ref([
  {
    id: ulid(),
    icon: 'solar:smile-circle-linear', // Icon left blank as requested
    title: 'Preschool',
    path: '/academics/curriculum/pre-school',
    description:
      'Fostering readiness for Grade 1, the Preschool program develops foundational skills in young learners through play, exploration, and discovery. Within a nurturing environment, it covers numeracy, literacy, science, arts, and languages, cultivating communication, creativity, self-help skills, and early critical thinking abilities.',
  },
  {
    id: ulid(),
    icon: 'solar:book-linear', // Icon left blank as requested
    title: 'Primary School & Middle School',
    path: '/academics/curriculum/primary-school-middle-school',
    description:
      'Experience an education nurturing God-given potential while developing critical thinking, communication, collaboration, and creativity. The integrated curriculum blends core academics, arts, practical skills, technology, and languages, fostering character formation, cultural appreciation, and lifelong learning for holistic student development and readiness for future challenges.',
  },
  {
    id: ulid(),
    icon: 'solar:rocket-linear', // Icon left blank as requested
    title: 'Junior High School',
    path: '/academics/curriculum/junior-high-school',
    description:
      'Building on elementary foundations, the Junior High School program fosters academic excellence and character formation aligned with the national K-12 curriculum. It employs the Understanding by Design framework and offers specialized tracks, ensuring comprehensive preparation in core subjects and readiness for Senior High School challenges.',
  },
  {
    id: ulid(),
    icon: 'solar:square-academic-cap-linear', // Icon left blank as requested
    title: 'Senior High School',
    path: '/academics/curriculum/senior-high-school',
    description:
      'Providing specialized pathways aligned with the K-12 curriculum, the Senior High School program allows students to choose tracks like STEM, ABM, HUMSS, GAS, or TVL based on aptitude and interest. This prepares graduates for tertiary education, employment, or entrepreneurship through targeted core, applied, and specialized subjects.',
  },
])
</script>
