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
              School Year-End Activities
            </h1>
            <p class="text-sm lg:text-base text-white leading-normal">
              Events and activities marking the end of the school year
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
                The Year-End Activities like Recognition Program, Moving-Up
                Program, and Commencement Exercises are considered to be the
                “must” programs to celebrate every time each school year comes
                to close.
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                These are the highlight events when learners are proudly
                recognized of their well-earned awards. With the set schedule as
                stipulated in school calendar, learners together with their
                supportive parents and friends go up the stage and receive the
                product of their hard work for the whole school year.
              </div>
            </div>

            <div class="flex flex-col gap-6">
              <h1
                class="text-3xl font-black text-blue-900 dark:text-blue-500 leading-tight"
              >
                Recognition Program
              </h1>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                Studies have shown that learners who receive awards and
                recognition are often encouraged to continue striving for higher
                academic achievement. Learners feel good about the effort they
                are giving when they are rewarded for good performance.
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                This program is for Nursery 1-2, Grades 1-5, Grades 7-9, and
                Grade 11.
              </div>
            </div>

            <div class="flex flex-col gap-6">
              <h1
                class="text-3xl font-black text-blue-900 dark:text-blue-500 leading-tight"
              >
                Moving-Up Program
              </h1>
              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                In accordance with the Kto12 educational system, learners will
                observe the Moving-Up ceremony instead of the traditional
                graduation. This paves way for their entry to Primary School & Middle School for
                Kindergarten completers and Senior High School for Grade 10
                completers.
              </div>
            </div>

            <div class="flex flex-col gap-6">
              <h1
                class="text-3xl font-black text-blue-900 dark:text-blue-500 leading-tight"
              >
                Graduation Program/Commencement Exercises
              </h1>
              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                Graduation is the traditional end-of-school year rites marking
                the accomplishment of a particular educational stage to begin a
                new one. This is the reason why the ceremony is called
                Commencement Exercises as commence means to begin. The grade 6
                graduates will begin their academic journey in their Junior High
                School, and the Grade 10 to begin their academic journey as they
                set their best foot forward in their Senior High School years.
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
</script>
