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
              Promotion and Retention
            </h1>
            <p class="text-sm lg:text-base text-white leading-normal">
              Criteria for advancing to the next grade level.
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
                The general weighted average or
                <span class="font-bold">GWA</span>. in all learning areas
                determine the promotion or retention of a learner.
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                A learner is promoted to the next grade level if the GWA of 75%
                or above is obtained.
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                A learner is retained in the same grade level if there are three
                (3) learning areas with GWA of lower than 75%.
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                A learner who is retained for two school years may be asked to
                transfer to another school at the end of the school year.
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                <span class="font-bold">N.B.</span>
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                <ul>
                  <li class="my-1">
                    <div class="inline-flex">
                      <code
                        class="text-xl font-semibold leading-tight text-red-500"
                      >
                        1.
                      </code>
                      <span class="mx-3">&nbsp;</span>
                      <span class="leading-tight">
                        Learners from Grade 1 to Grade 6, Grade 7 to Grade 10
                        under the Regular Curriculum, and Grades 11 to 12 are
                        required to have a GWA of at least 75% to pass a
                        discipline.
                      </span>
                    </div>
                  </li>
                  <li class="my-1">
                    <div class="inline-flex">
                      <code
                        class="text-xl font-semibold leading-tight text-red-500"
                      >
                        2.
                      </code>
                      <span class="mx-3">&nbsp;</span>
                      <span class="leading-tight">
                        Learners under the Special Science Curriculum shall not
                        have a final rating below 85% in ESM-related subjects,
                        otherwise they are demoted to Regular Curriculum class.
                        Also, a final rating below 80 in any discipline means
                        outright demotion to Regular Curriculum class.
                      </span>
                    </div>
                  </li>
                </ul>
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

const schoolPaperObjectives = [
  {
    id: ulid(),
    step: 1,
    description:
      'To Improve the Creative Skills of the Learners - having a school paper empowers learners to showcase their writing skills and voice their opinions. Since young minds are bubbling with fresh ideas, a school paper is the best way to capture these in a creative manner.',
  },
  {
    id: ulid(),
    step: 2,
    description:
      'To Build the Confidence of the Learners - when learners get a platform to articulate their thoughts without any reservation, it helps to build their confidence. Their participation in school paper makes them feel special, and it adds great enthusiastic in them to present their skills.',
  },
  {
    id: ulid(),
    step: 3,
    description:
      'To Improve Communication - a learner-run school paper is a great way to build communication between all groups in the school. They come together to interact, to brainstorm, and to ideate, and they help improve association among each other.',
  },
  {
    id: ulid(),
    step: 4,
    description:
      'To Help Build Teamwork – brainstorming and producing a school paper provide the learners with valuable experience in collaboration and teamwork that can go a long way in their lives.',
  },
]
</script>
