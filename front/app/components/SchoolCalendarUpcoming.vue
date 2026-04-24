<template>
  <div class="px-2">
    <div
      v-for="schoolCalendar in schoolCalendars"
      :key="schoolCalendar.title"
      class="relative rounded-[2rem] dark:border dark:border-white/20 overflow-hidden flex flex-col justify-end my-5"
    >
      <div
        class="absolute inset-0 cover no-repeat"
        :style="`
					background: linear-gradient(
              79deg,
              rgba(0, 0, 0, 0.9) 0%,
              rgba(0, 0, 0, 0) 58.1%
            ),
            linear-gradient(
              0deg,
              color-mix(in srgb, var(${
                schoolCalendar.title == 'No Event'
                  ? '--color-red-900'
                  : '--color-slate-900'
              }), transparent 30%) 0%,
              color-mix(in srgb, var(${
                schoolCalendar.title == 'No Event'
                  ? '--color-red-900'
                  : '--color-slate-900'
              }), transparent 30%) 100%
            ),
            url(${schoolCalendar.image}) lightgray 50% / cover
              no-repeat;
          background-blend-mode: normal, multiply, normal;
				`"
      />

      <div class="relative flex flex-col justify-end h-full gap-8 p-8 lg:p-10">
        <div class="flex items-center gap-4">
          <span class="text-3xl font-semibold text-white">
            {{ schoolCalendar.title }}
          </span>
        </div>

        <!-- <div class="text-white leading-normal" v-html="item.description" /> -->

        <div class="flex flex-col gap-2">
          <div class="font-medium text-slate-300">
            <span
              class="p-1 rounded border-[0.1rem] bg-white/10 border-white/20"
            >
              {{ formatRange(schoolCalendar.start, schoolCalendar.end) }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
const schoolYearStore = useSchoolYearStore()
const dayjs = useDayjs()

const schoolCalendars = computedAsync<SchoolCalendar[]>(async () => {
  const response = await useGetFetch<SchoolCalendar[]>(
    `api/school-calendars?schoolYearId=${schoolYearStore.selectedSchoolYear.id}&upcoming=true`
  )

  if (Array.isArray(response) && response.length) {
    return response as SchoolCalendar[]
  } else {
    console.error('Error fetching school calendars:', response)
    return noData as any
  }
}, [])

const noData = [
  {
    title: 'No Event',
    from: dayjs().format('YYYY-MM-DD'),
    to: dayjs().format('YYYY-MM-DD'),
    image: '/images/school_calendar.webp',
  },
]
</script>
