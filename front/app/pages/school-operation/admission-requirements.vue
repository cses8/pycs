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
              Admission Requirements
            </h1>
            <p class="text-sm lg:text-base text-white leading-normal">
              List of criteria and documents needed
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
                Age Requirement
              </h1>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                A learner who is at least 4 years and 10 months old and / or at
                most 5 years and 9 months old by the month of opening of classes
                of the current school year is eligible to apply for
                Kindergarten.
              </div>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                A learner who is at least 5 years and 10 months old and/ or at
                most 6 years and 11 months by the month of opening of classes of
                the current school year is eligible to apply for Grade 1.
              </div>
            </div>

            <div class="flex flex-col gap-6">
              <h1
                class="text-3xl font-black text-blue-900 dark:text-blue-500 leading-tight"
              >
                Required Documents
              </h1>

              <div
                class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
              >
                <div
                  class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
                >
                  <div
                    class="text-lg font-semibold italic text-blue-900 dark:text-blue-500 leading-tight mb-5"
                  >
                    New / Returning Learner-Applicant
                  </div>
                  <ul>
                    <li>
                      <div class="inline-flex">
                        <span>
                          <Icon
                            name="mdi:checkbox-multiple-marked-circle-outline"
                            size="1.5rem"
                            class="text-red-500 mt-1"
                          />
                        </span>
                        <span class="mx-3">&nbsp;</span>
                        <span>
                          Original copy of PSA Birth Certificate with Registry
                          Number
                        </span>
                      </div>
                    </li>
                    <li>
                      <div class="inline-flex">
                        <span>
                          <Icon
                            name="mdi:checkbox-multiple-marked-circle-outline"
                            size="1.5rem"
                            class="text-red-500 mt-1"
                          />
                        </span>
                        <span class="mx-3">&nbsp;</span>
                        <span>
                          SF 9/Form 138/ Report Card (original copy)
                        </span>
                      </div>
                    </li>
                    <li>
                      <div class="inline-flex">
                        <span>
                          <Icon
                            name="mdi:checkbox-multiple-marked-circle-outline"
                            size="1.5rem"
                            class="text-red-500 mt-1"
                          />
                        </span>
                        <span class="mx-3">&nbsp;</span>
                        <span> Certificate of Good Moral Character </span>
                      </div>
                    </li>
                    <li>
                      <div class="inline-flex">
                        <span>
                          <Icon
                            name="mdi:checkbox-multiple-marked-circle-outline"
                            size="1.5rem"
                            class="text-red-500 mt-1"
                          />
                        </span>
                        <span class="mx-3">&nbsp;</span>
                        <span>
                          Learner’s Reference Number (LRN) (if not indicated in
                          the SF 9)
                        </span>
                      </div>
                    </li>
                    <li>
                      <div class="inline-flex">
                        <span>
                          <Icon
                            name="mdi:checkbox-multiple-marked-circle-outline"
                            size="1.5rem"
                            class="text-red-500 mt-1"
                          />
                        </span>
                        <span class="mx-3">&nbsp;</span>
                        <span>
                          ESC Certificate from previous school (if JHS/SHS
                          learner-applicant)
                        </span>
                      </div>
                    </li>
                    <li>
                      <div class="inline-flex">
                        <span>
                          <Icon
                            name="mdi:checkbox-multiple-marked-circle-outline"
                            size="1.5rem"
                            class="text-red-500 mt-1"
                          />
                        </span>
                        <span class="mx-3">&nbsp;</span>
                        <span>
                          4 recent 2”x2” colored pictures with complete name
                          written at the back of each picture
                        </span>
                      </div>
                    </li>
                  </ul>
                </div>

                <div
                  class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
                >
                  <div
                    class="text-lg dark:text-blue-500 font-semibold italic text-blue-900 dark:text-blue-500 leading-tight my-5"
                  >
                    Old Learners
                  </div>
                  <ul>
                    <li>
                      <div class="inline-flex">
                        <span>
                          <Icon
                            name="mdi:checkbox-multiple-marked-circle-outline"
                            size="1.5rem"
                            class="text-red-500 mt-1"
                          />
                        </span>
                        <span class="mx-3">&nbsp;</span>
                        <span>
                          SF 9/Form 138/ Report Card (original copy)
                        </span>
                      </div>
                    </li>
                    <li>
                      <div class="inline-flex">
                        <span>
                          <Icon
                            name="mdi:checkbox-multiple-marked-circle-outline"
                            size="1.5rem"
                            class="text-red-500 mt-1"
                          />
                        </span>
                        <span class="mx-3">&nbsp;</span>
                        <span>
                          SF 9/Form 138/ Report Card (original copy)
                        </span>
                      </div>
                    </li>
                  </ul>
                </div>

                <div
                  class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
                >
                  <div
                    class="text-lg dark:text-blue-500 font-semibold italic text-blue-900 dark:text-blue-500 leading-tight my-5"
                  >
                    Foreign Learners
                  </div>
                  <ul>
                    <li>
                      <div class="inline-flex">
                        <span>
                          <Icon
                            name="mdi:checkbox-multiple-marked-circle-outline"
                            size="1.5rem"
                            class="text-red-500 mt-1"
                          />
                        </span>
                        <span class="mx-3">&nbsp;</span>
                        <span> Report card or its equivalent </span>
                      </div>
                    </li>
                    <li>
                      <div class="inline-flex">
                        <span>
                          <Icon
                            name="mdi:checkbox-multiple-marked-circle-outline"
                            size="1.5rem"
                            class="text-red-500 mt-1"
                          />
                        </span>
                        <span class="mx-3">&nbsp;</span>
                        <span>
                          Certified true copy of the Alien Certificate of
                          Registration (ACR)(bring the original copy upon
                          submission for verification purposes)
                        </span>
                      </div>
                    </li>
                    <li>
                      <div class="inline-flex">
                        <span>
                          <Icon
                            name="mdi:checkbox-multiple-marked-circle-outline"
                            size="1.5rem"
                            class="text-red-500 mt-1"
                          />
                        </span>
                        <span class="mx-3">&nbsp;</span>
                        <span>
                          Certified true copy of passport((bring the original
                          copy upon submission for verification purposes)
                        </span>
                      </div>
                    </li>
                    <li>
                      <div class="inline-flex">
                        <span>
                          <Icon
                            name="mdi:checkbox-multiple-marked-circle-outline"
                            size="1.5rem"
                            class="text-red-500 mt-1"
                          />
                        </span>
                        <span class="mx-3">&nbsp;</span>
                        <span> Birth certificate or its equivalent </span>
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

const presidents = ref([
  { name: 'Charlton L. Uy', image: '', timeline: '(2023 – Present)' }, // Now first
  { name: 'Raymond P. Tan', image: '', timeline: '(2018 – 2023)' },
  { name: 'Jacinto T. De Jesus', image: '', timeline: '(2014 – 2018)' },
  { name: 'Richmond P. Subia', image: '', timeline: '(2012 – 2014)' },
  { name: 'Jimmy U. Pua', image: '', timeline: '(2010 – 2012)' },
  { name: 'Richard P. Tan', image: '', timeline: '(2006 – 2010)' },
  { name: 'Robinson K. Lim', image: '', timeline: '(2004 – 2006)' },
  { name: 'Leoncio Tan', image: '', timeline: '(2000 – 2004)' },
  { name: 'Fernando Ty', image: '', timeline: '(1998 – 2000)' },
  { name: 'William Uy', image: '', timeline: '(1994 – 1998)' },
  { name: 'Sergio Uy', image: '', timeline: '(1992 – 1994)' },
  { name: 'Jacinto Tio', image: '', timeline: '(1988 – 1992)' },
  { name: 'Teofilo Uy', image: '', timeline: '(1986 – 1988)' },
  { name: 'Reynaldo Tan', image: '', timeline: '(1980 – 1986)' },
  { name: 'Ernesto Subia', image: '', timeline: '(1978 – 1980)' },
  { name: 'Nicolas Gabitan', image: '', timeline: '(1976 – 1978)' },
  { name: 'Chua Ping Hong', image: '', timeline: '(1974 – 1976)' },
  { name: 'Venancio Tio', image: '', timeline: '(1972 – 1974)' },
  { name: 'Pantaleon Uy', image: '', timeline: '(1970 – 1972)' },
  { name: 'Ty Suan Suy', image: '', timeline: '(1969 – 1976)' }, // Corrected dates based on surrounding entries
  { name: 'Francisco Uy', image: '', timeline: '(1959 – 1962)' },
  { name: 'Eusebio Tan', image: '', timeline: '(1955 – 1957)' },
  { name: 'Eusebio Uy', image: '', timeline: '(1953 – 1955)' },
  {
    name: 'Kiana Uy',
    image: '',
    timeline: '(1950 – 1953, 1957 -1958, 1962 – 1967, 1969 – 1970)',
  },
  { name: 'Pedro Pua', image: '', timeline: '(1948 – 1950)' },
  { name: 'Lorenzo Uy', image: '', timeline: '(1946 – 1948)' }, // Now last
])

const principals = ref([
  { name: 'Marietess S. Cafirma', image: '', timeline: '(2017 – Present)' }, // Added 'Present'
  { name: 'Venus M. Gaffud', image: '', timeline: '(2014 – 2017)' },
  { name: 'Alberta L. Clemente', image: '', timeline: '(2007 – 2014)' },
  { name: 'Lin Na Wong', image: '', timeline: '(2004 – 2007)' },
  {
    name: 'Tiu Chin Kun',
    image: '',
    timeline: '(1954 – 1961, 1966 – 1978, 1982 – 2004)',
  },
  { name: 'Yí Wén Xuán', image: '', timeline: '(1979 – 1982)' },
  { name: 'Cu Cui Hong', image: '', timeline: '(1946 – 1948, 1976 – 1977)' },
])

const teams = ref([
  {
    name: 'PEDRO PUA',
    job: 'Founder',
    timeline: {
      job: 'President',
      range: '1948 – 1950',
    },
  },
  {
    name: 'LORENZO UY',
    job: 'Founder',
    timeline: {
      job: 'President',
      range: '1946 – 1948',
    },
  },
  {
    name: 'JUAN UY',
    job: 'Founder',
    timeline: {
      job: '',
      range: '',
    },
  },
  {
    name: 'MARIANO UY',
    job: 'Founder',
    timeline: {
      job: '',
      range: '',
    },
  },
  {
    name: 'MIGUEL DY',
    job: 'Founder',
    timeline: {
      job: '',
      range: '',
    },
  },
  {
    name: 'EUSEBIO UY',
    job: 'Founder',
    timeline: {
      job: 'President',
      range: '1953 – 1955',
    },
  },
  {
    name: 'JOAQUIN PUA',
    job: 'Founder',
    timeline: {
      job: '',
      range: '',
    },
  },
  {
    name: 'VICENTE PUA',
    job: 'Founder',
    timeline: {
      job: '',
      range: '',
    },
  },
  {
    name: 'CLEMENTE PAGGABAO',
    job: 'Founder',
    timeline: {
      job: '',
      range: '',
    },
  },
])
</script>
