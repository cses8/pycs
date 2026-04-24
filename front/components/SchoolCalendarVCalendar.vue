<template>
  <VCalendar
    ref="calendar"
    :rows="1"
    :view="'monthly'"
    title-position="left"
    :is-dark="isDark"
    class="!w-full dark:!border dark:!border-white/30 !bg-black/10 dark:!bg-white/20 dark:!text-slate-300 !h-full"
    :masks="{ weekdays: 'WWW' }"
    :attributes="attributes"
    @update:pages="calendarOnchange"
  >
    <!-- <template #day-content="{ day }">
      <div class="flex-center w-full h-full">
        <Button
          class="text-xs font-bold"
          text
          rounded
          @click="calenderOnDayClick($event, day)"
        >
          {{ day.label }}
        </Button>
      </div>
    </template> -->
  </VCalendar>
  <Message
    v-for="schoolCalendarEvent in cloneDeep(schoolCalendarEvents).reverse()"
    :key="schoolCalendarEvent.id"
    class="my-2"
  >
    <span class="bg-black/10 px-1 rounded border border-black/10 text-black/60">
      {{
        formatRange(
          schoolCalendarEvent.customData.data.start,
          schoolCalendarEvent.customData.data.end
        )
      }}
    </span>
    <div class="text-2xl font font-bold my-2">
      {{ schoolCalendarEvent.customData.data.title }}
    </div>
    <div v-html="schoolCalendarEvent.customData.data.description" />
  </Message>
</template>

<script setup lang="ts">
const dayjs = useDayjs()
const isDark = useDark()
const schoolYearStore = useSchoolYearStore()

const todayEvents = ref([
  // for highlighting of current dat
  {
    key: 'today',
    highlight: {
      color: 'gray',
      fillMode: 'outline',
    },
    dates: dayjs().format('YYYY-MM-DD'),
    customData: {
      events: [],
    },
  },
])
const schoolCalendarEvents: Ref = ref([])

const calendar = ref(null)
const calendarMonthStartDate = ref(dayjs().format('YYYY-MM-DD'))
const calendarMonthEndDate = ref(dayjs().format('YYYY-MM-DD'))
const calendarCurrentYear: Ref = ref(dayjs().format('YYYY'))

const attributes = computed(() => {
  return [...todayEvents.value, ...schoolCalendarEvents.value]
})

async function calendarOnchange(evt: any[]) {
  console.log('calendarOnchange', evt)
  if (evt[0]) {
    if (
      evt[0].hasOwnProperty('viewDays') &&
      Array.isArray(evt[0].viewDays) &&
      evt[0].viewDays.length
    ) {
      // Days in each month for a non-leap year and a leap year
      const daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]
      const daysInLeapMonth = [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]

      // Function to check if a year is a leap year
      function isLeapYear(year: number) {
        return (year % 4 === 0 && year % 100 !== 0) || year % 400 === 0
      }

      const year = evt[0].year
      const monthStartDate: string = `${year}-${pad(evt[0].month, 2)}-01`
      const monthEndDate: string = `${year}-${pad(evt[0].month, 2)}-${
        isLeapYear(year)
          ? daysInLeapMonth[evt[0].month - 1]
          : daysInMonth[evt[0].month - 1]
      }`

      calendarCurrentYear.value = year

      if (
        isNE(calendarMonthStartDate.value, monthStartDate) ||
        isNE(calendarMonthEndDate.value, monthEndDate)
      ) {
        console.log(calendarMonthEndDate.value, monthEndDate)
        calendarMonthStartDate.value = monthStartDate
        calendarMonthEndDate.value = monthEndDate
        nextTick(async () => {
          await processSchoolCalendarFilter()
        })
      }
    }
  }
}

async function processSchoolCalendarFilter() {
  const response = await useGetFetch<SchoolCalendar[]>(
    `api/school-calendars?schoolYearId=${schoolYearStore.selectedSchoolYear.id}&start=${calendarMonthStartDate.value}&end=${calendarMonthEndDate.value}`
  )

  if (Array.isArray(response)) {
    schoolCalendarEvents.value = response.map((curr: any) => ({
      key: 'event',
      dot: {
        color: 'indigo',
      },
      dates: curr.start,
      popover: false,
      customData: {
        type: 'Event',
        data: curr,
      },
    }))
  }
}

watch(
  () => schoolYearStore.selectedSchoolYear.id,
  (/*val, oldVal*/) => {
    processSchoolCalendarFilter()
  }
)
</script>
