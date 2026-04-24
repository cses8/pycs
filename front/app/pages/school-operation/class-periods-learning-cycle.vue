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
              Class Periods and Learning Cycle
            </h1>
            <p class="text-sm lg:text-base text-white leading-normal">
              Understand class times and the learning structure
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="px-6 md:px-12 lg:px-20 py-4">
      <div class="grid grid-cols-12 gap-4 lg:gap-8">
        <div class="col-span-12 lg:col-span-2"></div>

        <div class="col-span-12 lg:col-span-8 flex flex-col gap-8 py-4">
          <div class="flex flex-col gap-6">
            <div class="flex flex-col gap-6">
              <h1
                class="text-3xl font-black text-blue-900 dark:text-blue-500 leading-tight"
              ></h1>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                PYCS, as it is highly engaged to improve the learners’ study
                habit and learning commitment took the first big step to embrace
                the six (6)-day learning cycle. Yuh Chiauians will be counting
                the days of school year as part of a new rotating six-day
                learning cycle. With this new class schedule, the biggest
                advantages are consistency, continuity of learning, flexibility,
                and shorter periods.
              </div>
            </div>

            <div class="flex flex-col gap-6">
              <h1
                class="text-3xl font-black text-blue-900 dark:text-blue-500 leading-tight"
              >
                Enrollment Period and Opening of Classes
              </h1>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                Private schools may modify the calendar to suit their context as
                long as the appropriate DepEd Regional Officer, as the case may
                be, is informed in advance of any modification and the school
                opening date and the minimum number of class hours and class
                days are observed.
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                Private schools may, therefore, adopt their calendar as long as
                the following are observed:
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                <ul>
                  <li
                    v-for="schoolCalendarRule in schoolCalendarRules"
                    :key="schoolCalendarRule.id"
                    class="my-1"
                  >
                    <div class="inline-flex">
                      <code
                        class="text-xl font-semibold leading-tight text-red-500"
                      >
                        {{ schoolCalendarRule.step }}.
                      </code>
                      <span class="mx-3">&nbsp;</span>
                      <span class="leading-tight">
                        {{ schoolCalendarRule.text }}
                      </span>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <div class="flex flex-col gap-6">
              <h1
                class="text-3xl font-black text-blue-900 dark:text-blue-500 leading-tight"
              >
                Enrollment and Class Size
              </h1>
              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                The enrolment and class size in every subject of each private
                school shall be determined by the School Principal taking into
                account the total absorption capacity of its facilities, level
                of instruction, the nature of the subject, and such other
                factors as may be conducive to the learning process.
              </div>
              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                From a psychological viewpoint – in terms of how learners learn
                – what matters is the number of learners who are physically
                present actively interacting among themselves and with the
                teacher.
              </div>
              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                PYCS, as a private Filipino-Chinese school has a right to set
                the class size limit.
              </div>
            </div>
          </div>
        </div>

        <div
          class="col-span-12 lg:col-span-2 border-surface-200 dark:border-surface-700"
        ></div>
      </div>
    </div>
  </div>
</template>
<script setup>
definePageMeta({
  layout: 'welcome',
})

const schoolCalendarRules = [
  {
    id: ulid(),
    step: 1,
    text: 'The opening of classes is within the released DepEd Order for the current school year;',
  },
  {
    id: ulid(),
    step: 2,
    text: 'The calendar consists of not less than the number of school days stated in the DepEd school calendars;',
  },
  {
    id: ulid(),
    step: 3,
    text: "The DepEd regional officer is notified of the school's modified calendar not later than fifteen (15) days before the opening of the school term.",
  },
]
</script>
