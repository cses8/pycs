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
              Grading System and Grade Description
            </h1>
            <p class="text-sm lg:text-base text-white leading-normal">
              How grades are calculated and what they mean.
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
                Grading system helps learners figure the amount of knowledge
                they have gained. It is also a method used to assess learners’
                educational performance.
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                The Averaging System of grading as mandated by the Department of
                Education. This means that the
                <span class="font-bold">final rating</span> in each discipline
                is computed by taking the average of the grades obtained from
                the first to fourth quarters. A school year is divided into four
                (4) grading periods called quarters. A Transmutation Table, also
                prescribed by the Department of Education, is used to compute
                for the final quarterly grades of the learners, and that all
                grades shall be expressed in whole numbers. It is in the same
                manner that the
                <span class="font-bold">general weighted average</span> or
                <span class="font-bold">GWA</span> is computed.
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                <span class="font-bold">N.B.</span>(RMRPSBE; Sec. 144)
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                Although mandatory for the public schools, private schools have
                discretion whether to adopt the same or not. Private school may
                adopt their own grading system as long as the basic requirement
                that the academic grades are solely based on scholastic
                performance is strictly complied with. Also, there shall be
                established policy stipulated in a manual.
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
                          Grade Description (Preschool)
                        </div>
                      </span>
                    </div>
                  </li>
                  <li class="mb-5 lg:ml-12">
                    <ul>
                      <li>
                        <div>
                          <span class="font-bold">E</span>
                          – Exemplary- Extremely good in surpassing expectations
                        </div>
                        <div>
                          <span class="font-bold">A</span>
                          – Accomplished- Meeting Expectations
                        </div>
                        <div>
                          <span class="font-bold">D</span>
                          – Developing- Moderately meeting expectations
                        </div>
                        <div>
                          <span class="font-bold">B</span>
                          – Beginning- Slightly meeting expectations
                        </div>
                      </li>
                    </ul>
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
                          Grade Description (Grades 1 to 12)
                        </div>
                      </span>
                    </div>
                  </li>
                  <li class="mb-5">
                    <table class="max-w-50 divide-y divide-gray-200">
                      <thead class="bg-gray-50">
                        <tr>
                          <th
                            scope="col"
                            class="px-3 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                          >
                            Numeric Grading Scale
                          </th>
                          <th
                            scope="col"
                            class="px-3 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                          >
                            Description
                          </th>
                          <th
                            scope="col"
                            class="px-3 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                          >
                            Remarks
                          </th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        <tr>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs font-medium text-gray-900"
                          >
                            96 - 100
                          </td>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs text-gray-500"
                          >
                            Excellent
                          </td>
                          <td
                            class="px-3 py-1.5 text-xs text-gray-500 whitespace-normal"
                          >
                            Extremely good in surpassing expectations
                          </td>
                        </tr>
                        <tr>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs font-medium text-gray-900"
                          >
                            90 - 95
                          </td>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs text-gray-500"
                          >
                            Highly Satisfactory
                          </td>
                          <td
                            class="px-3 py-1.5 text-xs text-gray-500 whitespace-normal"
                          >
                            Fairly surpassing expectations
                          </td>
                        </tr>
                        <tr>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs font-medium text-gray-900"
                          >
                            85 - 89
                          </td>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs text-gray-500"
                          >
                            Good
                          </td>
                          <td
                            class="px-3 py-1.5 text-xs text-gray-500 whitespace-normal"
                          >
                            Meeting expectations
                          </td>
                        </tr>
                        <tr>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs font-medium text-gray-900"
                          >
                            80 - 84
                          </td>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs text-gray-500"
                          >
                            Satisfactory
                          </td>
                          <td
                            class="px-3 py-1.5 text-xs text-gray-500 whitespace-normal"
                          >
                            Moderately meeting expectations
                          </td>
                        </tr>
                        <tr>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs font-medium text-gray-900"
                          >
                            76 - 79
                          </td>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs text-gray-500"
                          >
                            Fair
                          </td>
                          <td
                            class="px-3 py-1.5 text-xs text-gray-500 whitespace-normal"
                          >
                            Slightly meeting expectations
                          </td>
                        </tr>
                        <tr>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs font-medium text-gray-900"
                          >
                            75
                          </td>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs text-gray-500"
                          >
                            Pass
                          </td>
                          <td
                            class="px-3 py-1.5 text-xs text-gray-500 whitespace-normal"
                          >
                            Conditionally meeting expectations
                          </td>
                        </tr>
                        <tr>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs font-medium text-gray-900"
                          >
                            74 and below
                          </td>
                          <td
                            class="px-3 py-1.5 whitespace-nowrap text-xs text-gray-500"
                          >
                            Fail
                          </td>
                          <td
                            class="px-3 py-1.5 text-xs text-gray-500 whitespace-normal"
                          >
                            Fail Not meeting expectations
                          </td>
                        </tr>
                      </tbody>
                    </table>
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
                          Report Card
                        </div>

                        <div class="mb-5">
                          A report card or SF 9 communicates a learner’s
                          performance academically. It is an official document
                          issued by the school to the learner’s parent and/or
                          guardian at the close of each quarter, to keep the
                          parents and/or guardians posted on the learner’s
                          performance and behavior in school. The report card is
                          released during the Parent-Teacher Consultation and
                          returned to the class adviser not later than three (3)
                          days after issuance.
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
