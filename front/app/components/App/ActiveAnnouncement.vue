<template>
  <LazyUiAnimatedTestimonials
    v-if="announcements.length"
    :testimonials="announcements"
    autoplay
    :duration="5000"
  />
  <div
    v-else
    class="mx-auto w-full max-w-md rounded-xl border border-white/15 bg-white/10 p-6 text-left backdrop-blur"
  >
    <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-lg bg-white/10 text-white">
      <Icon name="lucide:megaphone" class="h-6 w-6" />
    </div>
    <h2 class="text-xl font-bold text-white">Announcements</h2>
    <p class="mt-2 text-sm leading-6 text-slate-200">
      Active school announcements will appear here when published.
    </p>
  </div>
</template>

<script setup lang="ts">
const dayjs = useDayjs()

onMounted(async () => {
  await fetchActiveAnnouncement()
})

const announcementApis = ref<Announcement[]>([])

const announcements = computed(() => {
  const formatAnnouncement = announcementApis.value.map(item => {
    return {
      name: item.title,
      designation: formatDisplayDateRange(item.start, item.end),
      quote: item.description,
      image: `${apiUrl(`/storage/announcements/${item.id}/${item.id}.webp`)}`,
    }
  })
  console.log(formatAnnouncement, 'formatAnnouncement')
  return formatAnnouncement
})

async function fetchActiveAnnouncement() {
  const response = await useGetFetch<Announcement[]>('api/announcements/active')

  if (Array.isArray(response)) {
    announcementApis.value = response
  }
}

function formatDisplayDateRange(startStr: string, endStr: string) {
  const start = dayjs(startStr)
  const end = dayjs(endStr)

  // --- Input Validation (Optional but Recommended) ---
  if (!start.isValid() || !end.isValid()) {
    console.error('Invalid date string provided:', { startStr, endStr })
    return 'Invalid Date Range'
  }
  if (start.isAfter(end)) {
    console.error('Start date cannot be after end date:', { startStr, endStr })
    return 'Invalid Date Range (Start > End)'
  }

  // --- Logic Implementation ---

  // Check if the end timestamp is *exactly* at the start of its day (00:00:00)
  // If true, the range effectively ends *before* the end day begins for display purposes.
  const isEndExactlyStartOfDay = end.isSame(end.startOf('day'))

  // --- Formatting based on rules ---

  // Rule for cases where end time is exactly 00:00:00 (Cases 2 & 4)
  // Effectively, only the duration *up to* the end day is considered.
  if (isEndExactlyStartOfDay) {
    return start.format('MMMM DD, YYYY') // e.g., March 01, 2025 or December 31, 2025
  }

  // --- Handle ranges where the end day is included (end time > 00:00:00) ---

  const isSameDay = start.isSame(end, 'day')
  const isSameMonth = start.isSame(end, 'month')
  const isSameYear = start.isSame(end, 'year')

  // If start and end fall on the same calendar day
  if (isSameDay) {
    return start.format('MMMM DD, YYYY') // e.g., March 01, 2025
  }
  // If start and end are on different days
  else {
    if (isSameYear) {
      if (isSameMonth) {
        // Case 1: Different day, same month, same year
        // Format: Month DayStart - DayEnd, Year
        // e.g., March 01 - 02, 2025
        return `${start.format('MMMM DD')} - ${end.format(
          'DD'
        )}, ${start.format('YYYY')}`
      } else {
        // Case 3: Different day, different month, same year
        // Format: MonthStart DayStart - MonthEnd DayEnd, Year
        // e.g., March 31 - April 01, 2025
        return `${start.format('MMMM DD')} - ${end.format(
          'MMMM DD'
        )}, ${start.format('YYYY')}`
      }
    } else {
      // Case 5: Different day, different month, different year
      // Format: MonthStart DayStart, YearStart - MonthEnd DayEnd, YearEnd
      // e.g., December 31, 2025 - January 01, 2026
      return `${start.format('MMMM DD, YYYY')} - ${end.format('MMMM DD, YYYY')}`
    }
  }
}
</script>
