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
              Special Programs
            </h1>
            <p class="text-sm lg:text-base text-white leading-normal">
              Unique learning opportunities and focuses.
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
              >
                ESM Intensive Program
              </h1>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                Philippine Yuh Chiau School, Inc. paves way to another avenue of
                honing the innate and developed skills of the learners in the
                fields of Science, Mathematics, and Language. The Yuh Chiauians
                set their academic standing in the top priority; hence, they
                really give importance to their academic class hours. To relieve
                the learners from worrying about missed learning and assessment
                activities during trainings and workshops in preparation for
                organized local, national, and international competitions, the
                school designed the ESM Intensive Program.
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                <ul>
                  <li>
                    <div class="inline-flex">
                      <span>
                        <Icon
                          name="material-symbols:circle"
                          size="1.5rem"
                          class="text-red-500"
                        />
                      </span>
                      <span class="mx-3">&nbsp;</span>
                      <span class="mb-5">
                        <div
                          class="text-xl font-semibold text-blue-900 dark:text-blue-500 leading-tight mb-1"
                        >
                          Objective
                        </div>

                        <div class="mb-5">
                          The <span class="italic">Intensive Program</span> is
                          designed to cater the needs of the learners who excel
                          in Science, Mathematics, and Language. The program
                          aims to focus on the learners from Primary School & Middle School to
                          Junior High School who have the
                          <span class="font-bold"
                            >final rating of 95 and above</span
                          >
                          in the specifically mentioned disciplines.
                        </div>

                        <div class="mb-5">
                          The learners are given time to concentrate in their
                          selected discipline and learn different topics above
                          Regular and these are prepared by the program
                          moderators. This program aims to intensify the skills
                          and Knowledge already acquired by the learners, and to
                          intensively prepare the learners for different
                          competitions relating to Science, Mathematics, and
                          Language.
                        </div>
                      </span>
                    </div>
                  </li>

                  <li>
                    <div class="inline-flex">
                      <span>
                        <Icon
                          name="material-symbols:circle"
                          size="1.5rem"
                          class="text-red-500"
                        />
                      </span>
                      <span class="mx-3">&nbsp;</span>
                      <span class="mb-5">
                        <div
                          class="text-xl font-semibold text-blue-900 dark:text-blue-500 leading-tight mb-1"
                        >
                          Description
                        </div>

                        <div class="mb-5">
                          The Intensive Program is held every scheduled Club
                          Time.Concerned learners in the Primary School & Middle School and Junior
                          High School are allotted 60 minutes per meeting.
                        </div>

                        <div class="mb-5">
                          The Intensive Science Program is open to learners in
                          grades 5 – 10 who meet the final rating requirement.
                          Learners are prepared intensively for any
                          Science-related competitions like Science and
                          Technology week, Science quiz bees, and research
                          presentations.
                        </div>

                        <div class="mb-5">
                          The Intensive Mathematics Program is open to learners
                          in grades 1 – 10 who meet the final rating
                          requirement. They are prepared intensively for any
                          Mathematics-related competitions like MMC and Math
                          quiz bees.
                        </div>

                        <div class="mb-5">
                          The Intensive Reading/Language Program is open to
                          learners in grade 4 – 10 who also meet the final
                          rating requirement. Learners are also prepared
                          intensively for Journalism-related competitions both
                          in English and Filipino, reading aloud competitions,
                          and re-telling story competitions.
                        </div>
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
</script>
