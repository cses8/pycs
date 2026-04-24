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
              Admission Procedures
            </h1>
            <p class="text-sm lg:text-base text-white leading-normal">
              Step-by-step guide to the application process
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
                <div
                  class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
                >
                  <div
                    class="text-lg font-semibold italic text-blue-900 dark:text-blue-500 leading-tight mb-5"
                  >
                    New / Returning / Foreign Learner-Applicant
                  </div>
                  <ul>
                    <li
                      v-for="newLearnerStep in newLearnerSteps"
                      :key="newLearnerStep.id"
                      class="my-1"
                    >
                      <div class="inline-flex">
                        <code
                          class="text-xl font-semibold leading-tight text-red-500"
                        >
                          {{ newLearnerStep.step }}.
                        </code>
                        <span class="mx-3">&nbsp;</span>
                        <span class="leading-tight">
                          {{ newLearnerStep.description }}
                        </span>
                      </div>
                    </li>
                  </ul>
                </div>

                <div
                  class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
                >
                  <div
                    class="text-lg font-semibold italic text-blue-900 leading-tight dark:text-blue-500 my-5"
                  >
                    Old Learners
                  </div>
                  <ul>
                    <li
                      v-for="oldLearnerStep in oldLearnerSteps"
                      :key="oldLearnerStep.id"
                      class="my-1"
                    >
                      <div class="inline-flex">
                        <code
                          class="text-xl font-semibold leading-tight text-red-500"
                        >
                          {{ oldLearnerStep.step }}.
                        </code>
                        <span class="mx-3">&nbsp;</span>
                        <span class="leading-tight">
                          {{ oldLearnerStep.description }}
                        </span>
                      </div>
                    </li>
                  </ul>
                </div>
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

// Array containing steps for New / Returning / Foreign Learner Applicants
const newLearnerSteps = [
  {
    id: ulid(),
    step: 1,
    description:
      'Submit all the required documents to the concerned admission officer-in-charge.',
  },
  {
    id: ulid(),
    step: 2,
    description:
      "Pay the assessment fee of Php 300.00 at the Cashier's Office. (The fee is NON-REFUNDABLE AND NON-TRANSFERRABLE)",
  },
  {
    id: ulid(),
    step: 3,
    description:
      'Take the assessment / placement examination administered by the admission officer-in-charge.',
  },
  {
    id: ulid(),
    step: 4,
    description:
      'Completely fill out the enrollment form. (This will be accomplished by a Parent/Guardian)',
  },
  {
    id: ulid(),
    step: 5,
    description:
      'Submit the enrollment form to the admission officer-in-charge.',
  },
  {
    id: ulid(),
    step: 6,
    description:
      'A "clearance slip" will be released by the concerned admission officer-in-charge.',
  },
  {
    id: ulid(),
    step: 7,
    description:
      "Proceed to the Cashier's table for payment of tuition and school patches fees.",
  },
  {
    id: ulid(),
    step: 8,
    description: 'Get a copy of the prescribed school uniform.',
  },
  {
    id: ulid(),
    step: 9,
    description: 'Proceed to the designated room for books payment.',
  },
]

// Array containing steps for Old Learners
const oldLearnerSteps = [
  {
    id: ulid(),
    step: 1,
    description:
      'Approach the admission officer-in-charge for verification of documents.',
  },
  {
    id: ulid(),
    step: 2,
    description:
      'Completely fill out the enrollment form issued by the admission officer-in-charge.',
  },
  {
    id: ulid(),
    step: 3,
    description:
      'Submit the enrollment form to the admission officer-in-charge.',
  },
  {
    id: ulid(),
    step: 4,
    description:
      'A "clearance slip" will be released by the concerned admission officer-in-charge.',
  },
  {
    id: ulid(),
    step: 5,
    description:
      "Proceed to the Cashier's table for payment of tuition and/or school patches fees.",
  },
  {
    id: ulid(),
    step: 6,
    description: 'Proceed to the designated room for books payment.',
  },
]
</script>
