<template>
  <div
    class="w-full overflow-hidden"
  >
    <div class="mx-auto flex max-w-7xl flex-col items-center gap-10">
      <UiBlurReveal :duration="0.5">
        <div class="mx-auto flex max-w-3xl flex-col items-center gap-3">
          <h3
            class="text-center text-2xl font-bold leading-tight text-slate-900 dark:text-surface-0"
          >
            Stay Connected
          </h3>
          <p
            class="text-center leading-6 text-slate-600 dark:text-surface-300"
          >
            Find important updates, browse galleries, and check upcoming events.
          </p>
        </div>
      </UiBlurReveal>

      <div
        class="mx-auto grid w-full max-w-5xl grid-cols-1 gap-4 md:grid-cols-2"
      >
        <template v-for="(quickLink, index) in quickLinks" :key="quickLink.id">
          <button
            v-motion
            type="button"
            class="group flex min-h-56 flex-col justify-between rounded-xl border border-slate-200 bg-white p-6 text-left transition-colors hover:border-indigo-200 hover:bg-indigo-50 dark:border-surface-800 dark:bg-surface-950 dark:hover:border-indigo-500/40 dark:hover:bg-indigo-500/10"
            :initial="{
              opacity: 0,
              y: 100,
              rotateZ: index % 2 === 0 ? -15 : 15,
            }"
            :visible="{
              opacity: 1,
              y: 0,
              rotateZ: 0,
              transition: {
                type: 'spring',
                stiffness: 100,
                damping: 15,
                delay: 300 + index * 150,
              },
            }"
            @click="goToPage(quickLink.path)"
          >
            <div>
              <span
                class="mb-5 flex h-12 w-12 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 dark:border-surface-700 dark:bg-surface-900"
              >
                <Icon :name="quickLink.icon" class="h-7 w-7" />
              </span>
              <h4 class="text-xl font-bold text-slate-900 dark:text-white">
                {{ quickLink.title }}
              </h4>
              <p class="mt-3 max-w-md text-sm leading-6 text-slate-600 dark:text-surface-300">
                {{ quickLink.description }}
              </p>
            </div>
            <span
              class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-indigo-700 group-hover:text-indigo-900 dark:text-indigo-200"
            >
              Explore
              <Icon name="solar:round-arrow-right-linear" class="h-4 w-4" />
            </span>
          </button>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
const router = useRouter()

const quickLinks = ref([
  // {
  //   id: ulid(),
  //   icon: 'solar:document-text-linear',
  //   title: 'School Updates',
  //   description:
  //     'Keep up-to-date with everything happening on campus. Find news articles, updates, and stories here.',
  //   path: '/news',
  // },
  {
    id: ulid(),
    icon: 'solar:gallery-linear',
    title: 'School Moments',
    description:
      'See our students in action! Check out galleries from academic, sports, and extracurricular events.',
    path: '/galleries',
  },
{
id: ulid(),
icon: 'solar:calendar-date-linear',
title: 'School Calendar',
description:
'Stay organized with our official school calendar. Find important dates, holidays, exam schedules, and events.',
path: '/school-calendar',
},
{
id: ulid(),
icon: 'solar:document-text-linear',
title: 'School Updates',
description:
'Read the latest school news, announcements, stories, and upcoming events in one place.',
path: '/school-updates',
},
])

function goToPage(path) {
  router.push({ path })
}
</script>
