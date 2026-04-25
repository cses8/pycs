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
              Humble Beginnings
            </h1>
            <p class="text-sm lg:text-base text-white leading-normal">
              Learn how PYCS was founded
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="px-6 md:px-12 lg:px-20 py-4">
      <div class="grid grid-cols-12 gap-4 lg:gap-8">
        <div class="col-span-12 lg:col-span-2" />

        <div class="col-span-12 lg:col-span-7 flex flex-col gap-8 py-4">
          <div class="flex flex-col gap-6">
            <p
              class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
            >
              In the 1930’s, a considerable number of Chinese migrants from
              Mainland China found their way to Isabela. Some of them settled in
              the locality of Cabatuan, Aurora, and Roxas. Cabatuan was then a
              small, unprogressive barrio of Cauayan. Transportation was very
              backward and commercial activities were limited. There were only
              more than ten Chinese families living in these three towns, mostly
              businessmen engaged in corner sari- sari store and grains trading.
              They were not rich but deep in their hearts, they were highly
              nationalists. They loved their ancestral land and culture. They
              did not want their children to forget their Chinese origin and
              roots. They wanted this culture to be part of their lives, handed
              down from generation to generation. It was these common goal and
              motivation that brought these gentlemen together. The desire to
              provide their descendants a chance to learn Chinese traditions and
              values mandated them to establish a school that would serve this
              purpose. By putting their meager means together, they put up a
              Chinese school in Cabatuan, Isabela. It was founded in September
              1946 by the first Board of Trustees composed of:
              <span class="font-bold">
                Pedro Pua, Mariano Uy, Joaquin Uy, Lorenzo Uy, Miguel Dy,
                Vicente Pua, Juan Uy, Eusebio Uy, Clemente Paggabao.
              </span>
            </p>

            <p
              class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
            >
              It was only a rented house affair in front of the San Andres-Del
              Pilar auditorium with more than thirty (30) learners. They were
              all accommodated free. Such was the humble beginnings of the
              school. The school was known as Philippine Chinese Yuh Chiau
              School for several years, the Chinese name bearing the word
              “Magat”, named after the famous Magat River coursing along the
              adjacent towns of Cabatuan, Aurora, and Roxas, also known as the
              “Magat Area”, where members of the original Board of Trustees came
              to settle. Under the able management of the Board of Trustees, the
              school managed to grow and learners increased to hundreds. It
              necessitated a school building of its own. In 1952, the first
              building was constructed in Del Pilar. The school subsequently
              changed its name to Chinese Yuh Chiau School. During the
              incumbency of the Board of Trustees Chairman Eusebio Uy or Uy Cho
              Giok, then the youngest member of the Board, he proposed the
              establishment of a high school department to give access to the
              elementary graduates for higher education. Cabatuan then, being a
              poor town, has very few residents who could not afford to send
              their children to nearby towns or Metro Manila for further
              studies.
            </p>

            <p
              class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
            >
              Chairman Eusebio Tan, together with the Board of Trustees members,
              persevered with the high school course despite the problems in
              additional manpower, facilities, logistics, and finances. In 1955,
              the English Department obtained a permit from the Bureau of
              Private Schools. The following year, the Chinese Overseas
              Commission granted the school the accreditation of the Chinese
              Department. Thereafter, the student population grew steadily that
              it necessitated the construction of additional infrastructures to
              accommodate learners coming from various towns in the province.
            </p>

            <p
              class="text-lg font-light text-surface-500 dark:text-surface-400 leading-normal"
            >
              The declaration of Martial Law in 1972 brought the Filipinization
              of Chinese Schools all over the land. In view of perceived growing
              influence and discriminating policies exercised by the schools,
              the government, by virtue of a law, enforced that all Chinese
              schools with names bearing the word “Chinese”, must be changed to
              “Philippine” signifying that said institutions were under the
              Philippine sovereignty. For this reason that CHINESE YUH CHIAU
              SCHOOL was changed to PHILIPPINE YUH CHIAU SCHOOL. The law further
              required that members of the Board of Trustees, as well as school
              officials, must be Filipino citizens.
            </p>

            <div
              class="founders-showcase bg-surface-100 px-5 py-16 dark:bg-surface-900 sm:px-6 md:px-10 lg:px-12"
            >
              <div class="flex flex-col items-center gap-10 lg:gap-14">
                <div class="flex flex-col items-center gap-4">
                  <h2
                    class="max-w-4xl text-center text-3xl font-semibold leading-tight text-surface-900 dark:text-surface-0 sm:text-4xl lg:text-[2.75rem]"
                  >
                    Founders of Philippine Yuh Chiau School
                  </h2>
                  <div
                    class="max-w-[42rem] text-center text-base leading-relaxed text-surface-600 dark:text-surface-300 sm:text-lg"
                  >
                    Founded in 1946 by Chinese migrants seeking to preserve
                    culture, the school started small, grew significantly, and
                    later became Philippine Yuh Chiau School due to policy
                    changes.
                  </div>
                </div>

                <div
                  class="mx-auto grid w-full max-w-[1048px] grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4"
                >
                  <div
                    v-for="(member, index) of teams"
                    :key="index"
                    class="founder-card group relative overflow-hidden rounded-b-2xl bg-surface-200 shadow-sm"
                  >
                    <Image
                      class="founder-card__image block w-full object-cover"
                      :style="`object-position: ${
                        member.imagePosition || 'center'
                      }`"
                      :src="`/images/founders/${member.name}.webp`"
                      :alt="member.name"
                    />
                    <div
                      class="founder-card__details absolute inset-x-3 bottom-3 flex min-h-[5.25rem] flex-col justify-end gap-1 rounded-md p-3"
                    >
                      <h3 class="founder-card__name">
                        {{ member.name }}
                      </h3>
                      <div class="founder-card__meta">
                        <div class="font-semibold">
                          {{ member.job }}
                        </div>
                        <div
                          v-if="member.timeline.job || member.timeline.range"
                          class="flex flex-wrap gap-x-2"
                        >
                          <span class="font-semibold">{{
                            member.timeline.job
                          }}</span>
                          <span>{{ member.timeline.range }}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div
          class="col-span-12 lg:col-span-3 lg:border-l border-surface-200 dark:border-surface-700"
        >
          <div class="p-4">
            <div class="flex border-b border-surface pb-4 mb-4">
              <Avatar
                size="xlarge"
                :label="
                  presidents[0] && presidents[0]?.image
                    ? null
                    : presidents[0].name[0]
                "
                class="mr-4 h-20 w-20"
                :image="
                  presidents[0] && presidents[0]?.image
                    ? presidents[0].image
                    : null
                "
              />
              <div class="flex flex-col items-start gap-2">
                <span
                  class="text-lg text-surface-900 dark:text-surface-0 font-medium"
                  >{{ presidents[0].name }}</span
                >
                <span
                  class="text-surface-600 dark:text-surface-200 font-medium"
                >
                  {{ presidents[0].timeline }}
                </span>
              </div>
            </div>

            <div class="flex border-b border-surface pb-4 mb-4">
              <Avatar
                size="xlarge"
                :label="
                  principals[0] && principals[0]?.image
                    ? null
                    : principals[0].name[0]
                "
                class="mr-4 h-20 w-20"
                :image="
                  principals[0] && principals[0]?.image
                    ? principals[0].image
                    : null
                "
              />

              <div class="flex flex-col items-start gap-2">
                <span
                  class="text-lg text-surface-900 dark:text-surface-0 font-medium"
                >
                  {{ principals[0].name }}
                </span>
                <span
                  class="text-surface-600 dark:text-surface-200 font-medium"
                >
                  {{ principals[0].timeline }}
                </span>
              </div>
            </div>

            <span
              class="text-surface-900 dark:text-surface-0 font-semibold text-xl block mb-4"
            >
              Presidents of the Board of Trustees
            </span>
            <div class="flex flex-col gap-4 mb-8 overflow-y-auto h-[40rem]">
              <div
                v-for="president in presidents"
                :key="president"
                class="flex p-2"
              >
                <Avatar
                  size="xlarge"
                  :label="
                    president && president?.image ? null : president.name[0]
                  "
                  class="mr-4 h-20 w-20"
                  :image="
                    president && president?.image ? president.image : null
                  "
                />
                <div class="flex flex-col items-start gap-2">
                  <span
                    class="text-lg text-surface-900 dark:text-surface-0 font-medium"
                    >{{ president.name }}</span
                  >
                  <span class="text-surface-500 dark:text-surface-200">{{
                    president.timeline
                  }}</span>
                </div>
              </div>
            </div>

            <span
              class="text-surface-900 dark:text-surface-0 font-semibold text-xl block mb-4"
            >
              School Principals of Philippine Yuh Chiau School, Inc.
            </span>
            <div class="flex flex-col gap-4 mb-8 overflow-y-auto h-[40rem]">
              <div
                v-for="principal in principals"
                :key="principal"
                class="flex p-2"
              >
                <Avatar
                  size="xlarge"
                  :label="
                    principal && principal?.image ? null : principal.name[0]
                  "
                  class="mr-4 h-20 w-20"
                  :image="
                    principal && principal?.image ? principal.image : null
                  "
                />

                <div class="flex flex-col items-start gap-2">
                  <span
                    class="text-lg text-surface-900 dark:text-surface-0 font-medium"
                    >{{ principal.name }}</span
                  >
                  <span class="text-surface-500 dark:text-surface-200">{{
                    principal.timeline
                  }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
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

useSeoMeta({
  title: 'About Us | History & Founders',
  description:
    'Discover the rich history, and dedicated founders of the Philippine Yuh Chiau School',
  ogTitle: 'History & Founders | Philippine Yuh Chiau School',
  ogDescription:
    'Learn about the founders that shaped the Philippine Yuh Chiau School.',
  ogImage: 'https://staging.pycs.school/images/logo.webp',
  twitterCard: 'summary_large_image',
})
</script>
<style scoped>
.founders-showcase {
  position: relative;
  left: 50%;
  width: min(1120px, calc(100vw - 32px));
  transform: translateX(-50%);
  z-index: 1;
}

.founder-card {
  min-height: 300px;
}

.founder-card__image {
  height: 300px;
}

.founder-card__details {
  background:
    linear-gradient(
      180deg,
      rgba(255, 255, 255, 0.46) 0%,
      rgba(226, 232, 240, 0.76) 100%
    ),
    rgba(15, 23, 42, 0.2);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.35);
}

.founder-card__name {
  color: #ffffff !important;
  font-size: 18px;
  font-weight: 800;
  line-height: 1.15;
  overflow-wrap: anywhere;
  word-break: normal;
  text-shadow: 0 1px 8px rgba(15, 23, 42, 0.45);
}

.founder-card__meta {
  color: rgba(255, 255, 255, 0.86) !important;
  font-size: 12px;
  line-height: 1.35;
  text-shadow: 0 1px 8px rgba(15, 23, 42, 0.35);
}

@media (max-width: 639px) {
  .founders-showcase {
    width: min(360px, calc(100vw - 32px));
  }
}

@media (min-width: 1280px) {
  .founder-card {
    min-height: 310px;
  }

  .founder-card__image {
    height: 310px;
  }
}
</style>
