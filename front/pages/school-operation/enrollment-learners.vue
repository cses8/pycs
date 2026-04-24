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
              Enrollment of Learners
            </h1>
            <p class="text-sm lg:text-base text-white leading-normal">
              Information on how to register students
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
                Our learners at Philippine Yuh Chiau School, Inc. are not
                abstract beings, but of real families. The cooperation of the
                parents / guardian in the academic and discipline formations of
                the learners are paramount. Parents / guardian therefore are
                expected to work with the Administration, the Faculty and the
                learners in the pursuit of the school philosophy, vision, and
                mission, core values, and Confucian virtues.
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                Each learner must be academically ready for the grade level for
                which he is applying. Special education is NOT provided for at
                Philippine Yuh Chiau School, Inc. Therefore, registration and/or
                enrollment to PYCS pledges their support and commitment to the
                school’s philosophy, policies and regulations.
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                <ul>
                  <li
                    v-for="enrollmentRule in enrollmentRules"
                    :key="enrollmentRule.id"
                    class="my-1"
                  >
                    <div class="inline-flex">
                      <code
                        class="text-xl font-semibold leading-tight text-red-500"
                      >
                        {{ enrollmentRule.label }}.
                      </code>
                      <span class="mx-3">&nbsp;</span>
                      <span class="leading-tight">
                        {{ enrollmentRule.text }}
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

const enrollmentRules = [
  {
    id: ulid(),
    label: 'A',
    text: "A learner's registration at PYCS by his parents or guardian is considered an expression of willingness on the part of the learner and his parents / guardian to abide by all the rules and regulations prevailing in the school.",
  },
  {
    id: ulid(),
    label: 'B',
    text: "A learner's registration means that he is enrolling for the entire school year.",
  },
  {
    id: ulid(),
    label: 'C',
    text: 'A learner who does not live with his parents should have a legal guardian who is duly authorized by the parents to assume the parental authority and responsibilities towards the learner.',
  },
  {
    id: ulid(),
    label: 'D',
    text: 'PYCS adopts a policy of selective admission of its learners following prescribed standards of academic background / preparation, potential for development and general acceptance of the philosophy, vision, and mission of the school. It, therefore, reserves the right to refuse an applicant who, it believes, fails to meet the criteria for admission.',
  },
  {
    id: ulid(),
    label: 'E',
    text: 'The school accepts only qualified applicants for enrollment. Every applicant for enrollment must comply with the requirements and follow standard operating procedures.',
  },
  {
    id: ulid(),
    label: 'F',
    text: 'No old learner is allowed to enroll unless all requirements are presented at the scheduled time of enrollment.',
  },
  {
    id: ulid(),
    label: 'G',
    text: 'Learners with academic deficiency are required to take summer remedial classes before they are re-admitted the following school year.',
  },
  {
    id: ulid(),
    label: 'H',
    // Note: Rule H text seems combined with G or missing in the image provided.
    // Representing it as combined with the second sentence of G for completeness based on common formatting.
    text: 'Learners who were suspended twice within the school year, provided that the length of each suspension is at least three (3) days, and those committed grave offenses shall not be re-admitted.',
  },
  {
    id: ulid(),
    label: 'I',
    text: 'Learners who were not able to comply with the requirements of their probation, academic or disciplinary agreement will not be re-admitted.',
  },
  {
    id: ulid(),
    label: 'J',
    text: 'Learners who had been previously dismissed shall not be re-admitted.',
  },
  {
    id: ulid(),
    label: 'K',
    text: 'Learners should enroll on the specified enrollment period only.',
  },
]
</script>
