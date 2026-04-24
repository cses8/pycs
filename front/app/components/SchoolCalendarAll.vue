<template>
  <div class="w-full items-center justify-center md:px-8">
    <UiTracingBeam class="px-6">
      <div class="relative mx-auto max-w-2xl pt-4 antialiased">
        <div
          v-for="(schoolCalendar, index) in schoolCalendars"
          :key="`content-${index}`"
          class="mb-[8rem]"
        >
          <div
            class="mb-4 w-fit rounded-full bg-black px-2 text-sm text-white dark:bg-white dark:text-black"
          >
            {{ formatRange(schoolCalendar.start, schoolCalendar.end) }}
          </div>

          <p :class="['mb-4 text-xl']">
            {{ schoolCalendar.title }}
          </p>

          <div class="prose prose-sm dark:prose-invert text-sm">
            <Image
              v-if="schoolCalendar.image"
              :src="schoolCalendar.image"
              alt="blog thumbnail"
              class="mb-10 object-cover"
              width="200"
              preview
            />
            <div>
              <div
                class="font-light text-slate-700 dark:text-slate-300"
                v-html="htmlTransformer(schoolCalendar.description)"
              />
            </div>
          </div>
        </div>
      </div>
    </UiTracingBeam>
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
  } else {
    console.error('Error fetching school calendars:', response)
    return []
  }
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
