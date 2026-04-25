<!-- eslint-disable vue/no-v-html -->
<template>
  <div class="space-y-5">
    <div class="school-calendar-mobile-shell rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-slate-950/40 sm:hidden">
      <div class="mb-4 flex items-center justify-between gap-3">
        <h3 class="text-lg font-black text-slate-950 dark:text-white">
          {{ currentMonthLabel }}
        </h3>
        <div class="flex items-center gap-1">
          <button
            type="button"
            class="mobile-calendar-nav"
            aria-label="Previous month"
            @click="moveMonth(-1)"
          >
            <Icon name="solar:alt-arrow-left-linear" class="size-4" />
          </button>
          <button
            type="button"
            class="mobile-calendar-nav"
            aria-label="Next month"
            @click="moveMonth(1)"
          >
            <Icon name="solar:alt-arrow-right-linear" class="size-4" />
          </button>
        </div>
      </div>

      <div class="mobile-calendar-weekdays">
        <span
          v-for="weekday in weekdays"
          :key="weekday"
        >
          {{ weekday }}
        </span>
      </div>

      <div class="mobile-calendar-grid">
        <button
          v-for="day in mobileMonthDays"
          :key="day.key"
          type="button"
          class="mobile-calendar-day"
          :class="{
            'mobile-calendar-day-muted': !day.inMonth,
            'mobile-calendar-day-today': day.isToday,
            'mobile-calendar-day-events': day.events.length,
          }"
        >
          <span>{{ day.label }}</span>
          <span
            v-if="day.events.length"
            class="mobile-calendar-dot"
          />
        </button>
      </div>
    </div>

    <div class="school-calendar-shell hidden overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-slate-950/40 sm:block">
      <VCalendar
        ref="calendar"
        expanded
        borderless
        transparent
        :rows="1"
        :view="'monthly'"
        title-position="left"
        :is-dark="isDark"
        :masks="{ weekdays: 'WWW' }"
        :attributes="attributes"
        @update:pages="calendarOnchange"
      >
        <template #day-content="{ day, attributes: dayAttributes }">
          <button
            type="button"
            class="calendar-day-cell"
            :class="{
              'calendar-day-cell-today': day.isToday,
              'calendar-day-cell-muted': !day.inMonth,
              'calendar-day-cell-has-events': dayEvents(dayAttributes).length,
            }"
          >
            <span class="flex items-center justify-between gap-2">
              <span class="calendar-day-label">{{ day.label }}</span>
              <span
                v-if="dayEvents(dayAttributes).length"
                class="calendar-day-count"
              >
                {{ dayEvents(dayAttributes).length }}
              </span>
            </span>

            <span
              v-for="event in dayEvents(dayAttributes).slice(0, 2)"
              :key="`${day.id}-${event.id}`"
              class="calendar-day-event"
            >
              {{ event.title }}
            </span>

            <span
              v-if="dayEvents(dayAttributes).length > 2"
              class="calendar-day-more"
            >
              +{{ dayEvents(dayAttributes).length - 2 }} more
            </span>
          </button>
        </template>
      </VCalendar>
    </div>

    <section class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-white/10 dark:bg-slate-950/50 sm:p-5">
      <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <p class="text-xs font-semibold uppercase text-blue-700 dark:text-blue-300">
            Monthly Events
          </p>
          <h3 class="text-xl font-black text-slate-950 dark:text-white">
            {{ currentMonthLabel }}
          </h3>
        </div>
        <div class="inline-flex w-fit items-center gap-2 rounded-full bg-white px-3 py-1 text-xs font-bold text-slate-700 ring-1 ring-slate-200 dark:bg-white/10 dark:text-slate-200 dark:ring-white/10">
          <Icon name="solar:list-check-linear" class="size-4 text-blue-700 dark:text-blue-300" />
          {{ monthlyEvents.length }} {{ monthlyEvents.length === 1 ? 'event' : 'events' }}
        </div>
      </div>

      <div
        v-if="loading"
        class="grid gap-3 md:grid-cols-2"
      >
        <div
          v-for="item in 4"
          :key="item"
          class="h-36 animate-pulse rounded-xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5"
        />
      </div>

      <div
        v-else-if="!monthlyEvents.length"
        class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center dark:border-white/15 dark:bg-white/5"
      >
        <Icon name="solar:calendar-search-linear" class="mx-auto size-10 text-slate-400" />
        <h4 class="mt-3 text-lg font-black text-slate-950 dark:text-white">
          No events in this month
        </h4>
        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600 dark:text-slate-300">
          Navigate to another month to view the events scheduled for that range.
        </p>
      </div>

      <div
        v-else
        class="grid gap-3 md:grid-cols-2"
      >
        <article
          v-for="event in monthlyEvents"
          :key="event.id"
          class="group overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:border-blue-200 hover:shadow-md dark:border-white/10 dark:bg-white/5 dark:hover:border-blue-300/40"
        >
          <div class="grid gap-0 sm:grid-cols-[9rem_minmax(0,1fr)]">
            <img
              :src="calendarImage(event)"
              :alt="event.title"
              class="h-36 w-full object-cover sm:h-full"
              @error="replaceWithFallbackImage"
            >
            <div class="p-4">
              <div class="mb-3 flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-800 ring-1 ring-blue-100 dark:bg-blue-400/15 dark:text-blue-200 dark:ring-blue-300/20">
                  <Icon name="solar:clock-circle-linear" class="size-3.5" />
                  {{ formatEventRange(event) }}
                </span>
              </div>
              <h4 class="line-clamp-2 text-base font-black leading-6 text-slate-950 dark:text-white">
                {{ event.title }}
              </h4>
<AppSafeHtml
class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600 dark:text-slate-300"
 :html="htmlTransformer(event.description)"
/>
            </div>
          </div>
        </article>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
type CalendarEventAttribute = {
  customData?: {
    type?: string
    data?: SchoolCalendar
  }
}

const dayjs = useDayjs()
const isDark = useDark()
const schoolYearStore = useSchoolYearStore()

const fallbackImage = '/images/school_calendar.webp'
const weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']
const calendar = ref<unknown>(null)
const monthlyEvents = ref<SchoolCalendar[]>([])
const loading = ref(false)
const calendarMonthStartDate = ref(dayjs().startOf('month').format('YYYY-MM-DD'))
const calendarMonthEndDate = ref(dayjs().endOf('month').format('YYYY-MM-DD'))
const calendarCurrentYear = ref(dayjs().format('YYYY'))

const todayEvents = computed(() => [
  {
    key: 'today',
    highlight: {
      color: 'blue',
      fillMode: 'light',
    },
    dates: dayjs().format('YYYY-MM-DD'),
  },
])

const eventAttributes = computed(() =>
  monthlyEvents.value.map(event => ({
    key: `event-${event.id}`,
    bar: {
      color: 'blue',
    },
    dates: {
      start: event.start,
      end: event.end,
    },
    popover: false,
    customData: {
      type: 'Event',
      data: event,
    },
  }))
)

const attributes = computed(() => [...todayEvents.value, ...eventAttributes.value])

const currentMonthLabel = computed(() => dayjs(calendarMonthStartDate.value).format('MMMM YYYY'))

const mobileMonthDays = computed(() => {
  const monthStart = dayjs(calendarMonthStartDate.value).startOf('month')
  const gridStart = monthStart.subtract(monthStart.day(), 'day')

  return Array.from({ length: 42 }, (_, index) => {
    const date = gridStart.add(index, 'day')
    const events = monthlyEvents.value.filter(event => isEventOnDate(event, date))

    return {
      key: date.format('YYYY-MM-DD'),
      label: date.format('D'),
      inMonth: date.isSame(monthStart, 'month'),
      isToday: date.isSame(dayjs(), 'day'),
      events,
    }
  })
})

onMounted(() => {
  processSchoolCalendarFilter()
})

watch(
  () => schoolYearStore.selectedSchoolYear.id,
  () => {
    processSchoolCalendarFilter()
  }
)

async function calendarOnchange(pages: Array<{ month: number, year: number }>) {
  const page = pages[0]

  if (!page) {
    return
  }

  const monthStartDate = dayjs(`${page.year}-${pad(page.month, 2)}-01`)
  const monthEndDate = monthStartDate.endOf('month')

  calendarCurrentYear.value = String(page.year)

  if (
    isNE(calendarMonthStartDate.value, monthStartDate.format('YYYY-MM-DD')) ||
    isNE(calendarMonthEndDate.value, monthEndDate.format('YYYY-MM-DD'))
  ) {
    calendarMonthStartDate.value = monthStartDate.format('YYYY-MM-DD')
    calendarMonthEndDate.value = monthEndDate.format('YYYY-MM-DD')
    await processSchoolCalendarFilter()
  }
}

async function moveMonth(direction: number) {
  const nextMonth = dayjs(calendarMonthStartDate.value).add(direction, 'month').startOf('month')

  calendarMonthStartDate.value = nextMonth.format('YYYY-MM-DD')
  calendarMonthEndDate.value = nextMonth.endOf('month').format('YYYY-MM-DD')
  calendarCurrentYear.value = nextMonth.format('YYYY')

  await processSchoolCalendarFilter()
}

async function processSchoolCalendarFilter() {
  loading.value = true

  try {
    const response = await useGetFetch<SchoolCalendar[]>(
      `api/school-calendars?schoolYearId=${schoolYearStore.selectedSchoolYear.id}&start=${calendarMonthStartDate.value}&end=${calendarMonthEndDate.value}`
    )

    monthlyEvents.value = Array.isArray(response)
      ? [...response].sort((a, b) => dayjs(a.start).valueOf() - dayjs(b.start).valueOf())
      : []
  } finally {
    loading.value = false
  }
}

function dayEvents(dayAttributes: CalendarEventAttribute[] = []) {
  return dayAttributes
    .map(attribute => attribute.customData?.type === 'Event' ? attribute.customData.data : null)
    .filter((event): event is SchoolCalendar => Boolean(event))
}

function calendarImage(calendarEvent: SchoolCalendar) {
  const image = calendarEvent.image?.trim()

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

function formatEventRange(calendarEvent: SchoolCalendar) {
  const start = dayjs(calendarEvent.start)
  const end = dayjs(calendarEvent.end)

  if (start.isSame(end, 'day')) {
    return start.format('MMM DD, YYYY')
  }

  if (start.isSame(end, 'month')) {
    return `${start.format('MMM DD')} - ${end.format('DD, YYYY')}`
  }

  return `${start.format('MMM DD, YYYY')} - ${end.format('MMM DD, YYYY')}`
}

function isEventOnDate(calendarEvent: SchoolCalendar, date: ReturnType<typeof dayjs>) {
  const start = dayjs(calendarEvent.start)
  const end = dayjs(calendarEvent.end)

  return !date.isBefore(start, 'day') && !date.isAfter(end, 'day')
}

function htmlTransformer(htmlString = '') {
  return htmlString
    .replace(/<ul>/g, '<ul style="list-style-type: circle !important;">')
    .replace(/<br>/g, '<div class="mb-1">&nbsp;</div>')
}
</script>

<style scoped>
.mobile-calendar-nav {
  display: inline-flex;
  height: 2rem;
  width: 2rem;
  align-items: center;
  justify-content: center;
  border-radius: 0.5rem;
  color: rgb(30 64 175);
}

.mobile-calendar-nav:hover {
  background: rgb(239 246 255);
}

.dark .mobile-calendar-nav {
  color: rgb(147 197 253);
}

.dark .mobile-calendar-nav:hover {
  background: rgb(30 41 59);
}

.mobile-calendar-weekdays,
.mobile-calendar-grid {
  display: grid;
  grid-template-columns: repeat(7, minmax(0, 1fr));
  gap: 0.25rem;
  min-width: 0;
  width: 100%;
}

.mobile-calendar-weekdays {
  color: rgb(100 116 139);
  font-size: 0.62rem;
  font-weight: 900;
  text-align: center;
  text-transform: uppercase;
}

.dark .mobile-calendar-weekdays {
  color: rgb(148 163 184);
}

.mobile-calendar-grid {
  margin-top: 0.5rem;
}

.mobile-calendar-day {
  display: flex;
  aspect-ratio: 1;
  min-width: 0;
  flex-direction: column;
  align-items: flex-start;
  justify-content: space-between;
  border-radius: 0.45rem;
  border: 1px solid rgb(226 232 240);
  background: rgb(248 250 252);
  padding: 0.25rem;
  color: rgb(15 23 42);
  font-size: 0.72rem;
  font-weight: 900;
  line-height: 1;
}

.dark .mobile-calendar-day {
  border-color: rgb(255 255 255 / 0.1);
  background: rgb(15 23 42 / 0.72);
  color: rgb(255 255 255);
}

.mobile-calendar-day-muted {
  opacity: 0.38;
}

.mobile-calendar-day-today {
  border-color: rgb(37 99 235);
  background: rgb(239 246 255);
}

.dark .mobile-calendar-day-today {
  border-color: rgb(96 165 250);
  background: rgb(30 58 138 / 0.35);
}

.mobile-calendar-day-events {
  border-color: rgb(147 197 253);
  background: rgb(255 255 255);
}

.dark .mobile-calendar-day-events {
  border-color: rgb(96 165 250 / 0.5);
  background: rgb(15 23 42);
}

.mobile-calendar-dot {
  height: 0.38rem;
  width: 0.38rem;
  border-radius: 999px;
  background: rgb(37 99 235);
}

.school-calendar-shell :deep(.vc-container) {
  width: 100%;
  max-width: 100%;
  min-width: 0;
  border: 0;
  font-family: inherit;
}

.school-calendar-shell :deep(.vc-pane-container),
.school-calendar-shell :deep(.vc-pane-layout) {
  max-width: 100%;
  min-width: 0;
}

.school-calendar-shell :deep(.vc-pane) {
  width: 100%;
  max-width: 100%;
  min-width: 100%;
  padding: 1rem;
}

.school-calendar-shell :deep(.vc-header) {
  align-items: center;
  margin-bottom: 1rem;
  padding: 0;
}

.school-calendar-shell :deep(.vc-title) {
  color: rgb(15 23 42);
  font-size: 1.25rem;
  font-weight: 900;
  line-height: 1.2;
}

.dark .school-calendar-shell :deep(.vc-title) {
  color: rgb(255 255 255);
}

.school-calendar-shell :deep(.vc-arrow) {
  border-radius: 0.5rem;
  color: rgb(30 64 175);
}

.school-calendar-shell :deep(.vc-arrow:hover) {
  background: rgb(239 246 255);
}

.dark .school-calendar-shell :deep(.vc-arrow) {
  color: rgb(147 197 253);
}

.dark .school-calendar-shell :deep(.vc-arrow:hover) {
  background: rgb(30 41 59);
}

.school-calendar-shell :deep(.vc-weekday) {
  color: rgb(71 85 105);
  font-size: 0.72rem;
  font-weight: 800;
  letter-spacing: 0;
  text-transform: uppercase;
}

.dark .school-calendar-shell :deep(.vc-weekday) {
  color: rgb(203 213 225);
}

.school-calendar-shell :deep(.vc-weeks) {
  width: 100%;
  max-width: 100%;
  gap: 0.35rem;
  min-width: 0;
}

.school-calendar-shell :deep(.vc-week),
.school-calendar-shell :deep(.vc-weekdays) {
  width: 100%;
  max-width: 100%;
  min-width: 0;
  grid-template-columns: repeat(7, minmax(0, 1fr));
}

.school-calendar-shell :deep(.vc-day) {
  width: 100%;
  max-width: 100%;
  min-width: 0;
  min-height: 7rem;
  padding: 0.18rem;
}

.calendar-day-cell {
  display: flex;
  min-width: 0;
  min-height: 6.65rem;
  width: 100%;
  flex-direction: column;
  gap: 0.35rem;
  overflow: hidden;
  border-radius: 0.75rem;
  border: 1px solid rgb(226 232 240);
  background: rgb(248 250 252);
  padding: 0.5rem;
  text-align: left;
  transition: border-color 0.15s ease, background-color 0.15s ease, box-shadow 0.15s ease;
}

.calendar-day-cell:hover {
  border-color: rgb(147 197 253);
  background: rgb(239 246 255);
  box-shadow: 0 10px 24px rgb(15 23 42 / 0.08);
}

.dark .calendar-day-cell {
  border-color: rgb(255 255 255 / 0.1);
  background: rgb(15 23 42 / 0.75);
}

.dark .calendar-day-cell:hover {
  border-color: rgb(96 165 250 / 0.55);
  background: rgb(30 41 59);
}

.calendar-day-cell-muted {
  opacity: 0.5;
}

.calendar-day-cell-today {
  border-color: rgb(37 99 235);
  background: rgb(239 246 255);
}

.dark .calendar-day-cell-today {
  border-color: rgb(96 165 250);
  background: rgb(30 58 138 / 0.35);
}

.calendar-day-cell-has-events {
  background: rgb(255 255 255);
}

.dark .calendar-day-cell-has-events {
  background: rgb(15 23 42);
}

.calendar-day-label {
  color: rgb(15 23 42);
  font-size: 0.88rem;
  font-weight: 900;
  line-height: 1;
}

.dark .calendar-day-label {
  color: rgb(255 255 255);
}

.calendar-day-count {
  display: inline-flex;
  min-width: 1.35rem;
  align-items: center;
  justify-content: center;
  border-radius: 999px;
  background: rgb(37 99 235);
  padding: 0.12rem 0.4rem;
  color: white;
  font-size: 0.68rem;
  font-weight: 900;
  line-height: 1;
}

.calendar-day-event {
  display: block;
  overflow: hidden;
  border-radius: 0.45rem;
  background: rgb(219 234 254);
  padding: 0.22rem 0.35rem;
  color: rgb(30 64 175);
  font-size: 0.68rem;
  font-weight: 800;
  line-height: 1.2;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.dark .calendar-day-event {
  background: rgb(59 130 246 / 0.18);
  color: rgb(191 219 254);
}

.calendar-day-more {
  color: rgb(71 85 105);
  font-size: 0.68rem;
  font-weight: 800;
  line-height: 1;
}

.dark .calendar-day-more {
  color: rgb(203 213 225);
}

@media (max-width: 640px) {
  .school-calendar-shell :deep(.vc-pane) {
    padding: 0.75rem;
  }

  .school-calendar-shell :deep(.vc-day) {
    min-height: 4.75rem;
  }

  .calendar-day-cell {
    min-height: 4.45rem;
    border-radius: 0.55rem;
    padding: 0.38rem;
  }

  .calendar-day-event,
  .calendar-day-more {
    display: none;
  }
}
</style>
