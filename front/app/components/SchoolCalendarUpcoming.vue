<!-- eslint-disable vue/no-v-html -->
<template>
  <div class="space-y-3">
    <article
      v-for="schoolCalendar in schoolCalendars"
      :key="schoolCalendar.id ?? schoolCalendar.title"
      class="overflow-hidden rounded-xl border border-slate-200 bg-slate-50 shadow-sm dark:border-white/10 dark:bg-slate-950/50"
    >
      <img
        :src="resolveCalendarImage(schoolCalendar)"
        :alt="schoolCalendar.title"
        class="h-32 w-full object-cover"
        @error="replaceWithFallbackImage"
      >
      <div class="space-y-3 p-4">
        <div class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-xs font-bold text-blue-800 dark:bg-blue-400/15 dark:text-blue-200">
          <Icon name="solar:clock-circle-linear" class="size-3.5" />
          {{ formatRange(schoolCalendar.start, schoolCalendar.end) }}
        </div>
        <h3 class="text-base font-black leading-5 text-slate-950 dark:text-white">
          {{ schoolCalendar.title }}
        </h3>
<AppSafeHtml
v-if="schoolCalendar.description"
class="line-clamp-4 text-sm leading-6 text-slate-600 dark:text-slate-300"
 :html="schoolCalendar.description"
/>
      </div>
    </article>
  </div>
</template>

<script setup lang="ts">
const schoolYearStore = useSchoolYearStore()
const dayjs = useDayjs()
const fallbackImage = '/images/school_calendar.webp'

const schoolCalendars = computedAsync<SchoolCalendar[]>(async () => {
  const response = await useGetFetch<SchoolCalendar[]>(
    `api/school-calendars?schoolYearId=${schoolYearStore.selectedSchoolYear.id}&upcoming=true`
  )

  if (Array.isArray(response) && response.length) {
    return response as SchoolCalendar[]
  }

  return [
    {
      id: 0,
      school_year_id: Number.isFinite(Number(schoolYearStore.selectedSchoolYear?.id))
        ? Number(schoolYearStore.selectedSchoolYear.id)
        : null,
      title: 'No upcoming events',
      description: 'There are no scheduled upcoming events for the selected school year yet.',
      start: dayjs().format('YYYY-MM-DD'),
      end: dayjs().format('YYYY-MM-DD'),
      image: fallbackImage,
    },
  ]
}, [])

function resolveCalendarImage(schoolCalendar: SchoolCalendar) {
  const image = schoolCalendar.image?.trim()

  if (!image) {
    return fallbackImage
  }

  if (image.startsWith('http') || image.startsWith('/images/')) {
    return image
  }

  if (image.startsWith('/storage/')) {
    return apiUrl(image)
  }

  return image
}

function replaceWithFallbackImage(event: Event) {
  const image = event.target as HTMLImageElement
  image.src = fallbackImage
}
</script>
