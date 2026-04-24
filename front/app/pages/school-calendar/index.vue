<template>
  <div class="bg-surface-0 dark:bg-surface-950">
    <div
      class="bg-cover bg-center h-[420px] flex flex-col justify-end gap-4"
      style="
        background: linear-gradient(
            0deg,
            rgba(0, 0, 0, 1) 0%,
            oklch(42.4% 0.199 265.638 / 0.8) 100%
          ),
          url('/images/banner1.webp');
      "
    >
      <div class="px-6 md:px-12 lg:px-20">
        <div class="grid grid-cols-12 gap-8">
          <div class="hidden lg:block col-span-2" />

          <div
            class="col-span-12 lg:col-span-8 py-4 lg:py-8 flex flex-col gap-4"
          >
            <h1 class="text-3xl lg:text-5xl font-bold text-white leading-tight">
              School Calendar
            </h1>
            <p class="text-sm lg:text-base text-white leading-normal">
              Stay organized with our official school calendar. Find important
              dates, holidays, exam schedules, and events.
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="px-6 md:px-12 lg:px-20 py-4">
      <div class="grid grid-cols-12 gap-4 lg:gap-8">
        <div
          class="col-span-12 order-3 lg:col-span-12 xl:order-1 xl:col-span-3"
        >
          <div class="text-blue-900 dark:text-blue-500">
            <h1 class="text-3xl font-black leading-tight">All</h1>
            <SchoolCalendarAll />
          </div>
        </div>

        <div
          class="col-span-12 flex flex-col gap-8 py-4 order-1 lg:col-span-10 xl:order-2 xl:col-span-7"
        >
          <div class="grid grid-cols-12">
            <div class="col-span-12 md:col-span-5">
              <div class="text-blue-900 dark:text-blue-500">
                <h1 class="text-3xl font-black leading-tight mb-4">
                  Upcoming Events
                </h1>

                <SchoolCalendarUpcoming />
              </div>
            </div>
            <div class="col-span-12 md:col-span-7 py-2 px-14">
              <div>
                <SchoolCalendarVCalendar />
              </div>
            </div>
          </div>
        </div>

        <div class="col-span-12 order-2 lg:col-span-2 xl:order-3 xl:col-span-2">
          <SchoolYear />
        </div>
      </div>
    </div>
  </div>
</template>
<script setup>
definePageMeta({
  layout: 'welcome',
})
</script>
