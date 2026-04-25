<template>
  <div class="grid gap-3 md:grid-cols-2">
    <article
      v-for="schoolCalendar in schoolCalendars"
      :key="schoolCalendar.id"
      class="rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-blue-200 hover:bg-blue-50/50 dark:border-white/10 dark:bg-slate-950/50 dark:hover:border-blue-300/40 dark:hover:bg-blue-400/10"
    >
      <div class="mb-3 flex items-start justify-between gap-3">
        <div class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-xs font-bold text-blue-800 ring-1 ring-slate-200 dark:bg-white/10 dark:text-blue-200 dark:ring-white/10">
          <Icon name="solar:calendar-linear" class="size-3.5" />
          {{ formatRange(schoolCalendar.start, schoolCalendar.end) }}
        </div>
      </div>
      <h3 class="text-lg font-black leading-6 text-slate-950 dark:text-white">
        {{ schoolCalendar.title }}
      </h3>
<AppSafeHtml
class="mt-3 line-clamp-5 text-sm leading-6 text-slate-600 dark:text-slate-300"
 :html="htmlTransformer(schoolCalendar.description)"
/>
    </article>

    <div
      v-if="!schoolCalendars.length"
      class="rounded-xl border border-dashed border-slate-300 p-6 text-sm text-slate-500 dark:border-white/15 dark:text-slate-400 md:col-span-2"
    >
      No calendar events available for the selected school year.
    </div>
  </div>
</template>

<script setup lang="ts">
const schoolYearStore = useSchoolYearStore()

const schoolCalendars = computedAsync<SchoolCalendar[]>(async () => {
  const response = await useGetFetch<SchoolCalendar[]>(
    `api/school-calendars?schoolYearId=${schoolYearStore.selectedSchoolYear.id}`
  )

  if (Array.isArray(response)) {
    return response as SchoolCalendar[]
  }

  return []
}, [])

function htmlTransformer(htmlString: string) {
  let updatedHtmlString = htmlString.replace(
    /<ul>/g,
    '<ul style="list-style-type: circle !important;">'
  )
  updatedHtmlString = updatedHtmlString.replace(
    /<br>/g,
    '<div class="mb-1">&nbsp;</div>'
  )

  return updatedHtmlString
}
</script>

<style scoped>
ul {
  list-style-type: circle !important;
}
</style>
