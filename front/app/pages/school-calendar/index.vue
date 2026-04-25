<template>
  <main class="overflow-x-hidden bg-slate-50 text-slate-950 dark:bg-slate-950 dark:text-white">
    <section
      class="relative isolate overflow-hidden bg-cover bg-center"
      style="background-image: url('/images/banner1.webp')"
    >
      <div class="absolute inset-0 bg-slate-950/70" />
      <div
        class="absolute inset-0 bg-[linear-gradient(90deg,rgba(15,23,42,0.95)_0%,rgba(30,64,175,0.72)_52%,rgba(15,23,42,0.86)_100%)]"
      />

      <div class="relative mx-auto max-w-7xl px-5 pb-14 pt-28 sm:px-8 sm:pt-24 lg:px-10 lg:pb-20 lg:pt-28">
        <div class="grid items-end gap-8 lg:grid-cols-[minmax(0,1fr)_360px]">
          <div class="max-w-3xl">
            <div
              class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-100"
            >
              <Icon name="lucide:calendar-days" class="size-4" />
              Official School Calendar
            </div>
            <h1 class="text-4xl font-black leading-tight text-white sm:text-5xl lg:text-6xl">
              Plan Around Every School Milestone
            </h1>
            <p class="mt-5 max-w-2xl text-base leading-7 text-blue-50 sm:text-lg">
              Track class activities, examinations, holidays, and school-wide events in one organized view for the selected academic year.
            </p>
          </div>

          <div class="rounded-2xl border border-white/15 bg-white/10 p-5 text-white shadow-2xl shadow-slate-950/30 backdrop-blur">
            <div class="flex items-center gap-3">
              <div class="flex size-11 items-center justify-center rounded-xl bg-white text-blue-900">
                <Icon name="lucide:calendar-check-2" class="size-6" />
              </div>
              <div>
                <p class="text-xs font-semibold uppercase text-blue-100">Current View</p>
                <p class="text-lg font-bold">School Year Schedule</p>
              </div>
            </div>
            <p class="mt-4 text-sm leading-6 text-blue-50">
              Use the year selector below to switch timelines. The upcoming list and monthly calendar update together.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section
      class="mx-auto grid min-w-0 gap-5 px-5 py-8 sm:px-8 lg:grid-cols-[280px_minmax(0,1fr)] lg:px-10 lg:py-10"
      :class="isAuthenticated ? 'max-w-[96rem]' : 'max-w-7xl'"
    >
      <SchoolCalendarManager
        v-if="isAuthenticated"
        class="min-w-0 lg:col-span-2"
        @changed="refreshCalendarViews"
      />

      <aside class="min-w-0 space-y-5">
        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
          <div class="mb-3 flex items-center gap-2">
            <Icon name="lucide:graduation-cap" class="size-5 text-blue-700 dark:text-blue-300" />
            <h2 class="text-sm font-bold uppercase text-slate-700 dark:text-slate-200">
              Academic Year
            </h2>
          </div>
          <SchoolYear />
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
          <div class="mb-4 flex items-center justify-between gap-3">
            <div>
              <p class="text-xs font-semibold uppercase text-blue-700 dark:text-blue-300">
                Next Up
              </p>
              <h2 class="text-xl font-black text-slate-950 dark:text-white">
                Upcoming
              </h2>
            </div>
            <Icon name="lucide:arrow-up-right" class="size-5 text-slate-400" />
          </div>
          <SchoolCalendarUpcoming :key="`upcoming-${calendarRefreshKey}`" />
        </div>
      </aside>

      <div class="min-w-0 space-y-5">
        <section class="min-w-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-6">
          <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
            <div>
              <p class="text-xs font-semibold uppercase text-blue-700 dark:text-blue-300">
                Month Overview
              </p>
              <h2 class="text-2xl font-black text-slate-950 dark:text-white">
                Calendar
              </h2>
            </div>
            <p class="max-w-md text-sm leading-6 text-slate-600 dark:text-slate-300">
              Select a month to refresh event details for the visible range.
            </p>
          </div>
          <SchoolCalendarVCalendar :key="`calendar-${calendarRefreshKey}`" />
        </section>
      </div>
    </section>
  </main>
</template>

<script setup lang="ts">
const { isAuthenticated } = useSanctumAuth()
const calendarRefreshKey = ref(0)

definePageMeta({
  layout: 'welcome',
})

function refreshCalendarViews() {
  calendarRefreshKey.value += 1
}
</script>
