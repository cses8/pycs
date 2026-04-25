<template>
  <div
    class="fixed top-0 z-[999] flex w-full items-center justify-between gap-3 border-b border-slate-200 bg-white/95 px-4 py-3 shadow-sm backdrop-blur md:px-8 xl:px-10 2xl:px-12 dark:border-surface-800 dark:bg-surface-950/95"
    @mouseleave="closeMenu"
  >
    <LazyAppLogin v-model:visible="openLogin" />
    <NuxtLink class="flex min-w-0 items-center gap-3" :to="'/'">
      <Image
        src="/images/logo.webp"
        alt="Philippine Yuh Chiau School logo"
        width="46"
        height="46"
        class="shrink-0"
      />

      <span
        class="hidden max-w-[11rem] truncate text-sm font-semibold leading-tight text-slate-900 sm:block lg:max-w-[13rem] xl:max-w-[10rem] 2xl:max-w-none 2xl:text-base dark:text-white"
      >
        Philippine Yuh Chiau School, Inc.
      </span>
    </NuxtLink>

    <a
      v-styleclass="{
        selector: '@next',
        enterFromClass: 'hidden',
        leaveToClass: 'hidden',
        hideOnOutsideClick: true,
      }"
      class="relative ml-auto block cursor-pointer rounded-lg border border-slate-200 p-2 text-slate-700 xl:hidden dark:border-surface-700 dark:text-surface-100"
      aria-label="Open navigation menu"
    >
      <i class="pi pi-bars text-xl" />
    </a>

    <div
      class="absolute left-0 top-[4.5rem] hidden w-full border-b border-slate-200 bg-white pb-4 shadow-lg animate-fadeinup xl:static xl:flex xl:flex-1 xl:items-center xl:border-0 xl:bg-transparent xl:pb-0 xl:shadow-none xl:dark:bg-transparent dark:border-surface-800 dark:bg-surface-950"
    >
      <ul
        class="relative mb-4 flex flex-1 select-none flex-col border-y border-slate-200 p-3 xl:mb-0 xl:mt-0 xl:flex-row xl:items-center xl:justify-center xl:gap-0 xl:border-0 xl:p-0 dark:border-surface-800"
        @mouseleave="hoveredItem = null"
      >
        <template v-for="(item, index) of navs" :key="index">
          <li
            class="relative border-b border-slate-100 last:border-b-0 xl:flex xl:items-center xl:border-b-0 xl:border-l xl:border-slate-200 xl:first:border-l-0 dark:border-surface-800 xl:dark:border-surface-700"
            @mouseenter="setActiveItem(item)"
            @click="setActiveItem(item)"
          >
            <a
              v-if="item?.subMenu"
              v-styleclass="{
                selector: '@next',
                enterFromClass: 'hidden',
                leaveToClass: 'hidden',
                hideOnOutsideClick: true,
              }"
              :class="
                activeItem?.label === item.label
                  ? 'text-indigo-700 dark:text-indigo-200'
                  : 'text-slate-700 dark:text-surface-200'
              "
              class="group relative flex min-h-10 items-center justify-between gap-2 px-1 py-2 text-sm font-semibold leading-tight transition-colors hover:text-indigo-700 xl:min-h-9 xl:whitespace-nowrap xl:px-3 xl:py-0 2xl:px-4 dark:hover:text-indigo-200"
            >
              <span>{{ item.label }}</span>
              <i
                class="pi pi-angle-down !text-xs !leading-none text-slate-400 transition-colors group-hover:text-indigo-600 dark:text-surface-500 dark:group-hover:text-indigo-200"
              />
              <span
                :class="
                  activeItem?.label === item.label ? 'opacity-100' : 'opacity-0'
                "
                class="absolute inset-x-3 bottom-0 hidden h-0.5 rounded-full bg-indigo-600 transition-all group-hover:opacity-100 xl:block"
              />
            </a>
            <NuxtLink
              v-else
              class="flex min-h-10 items-center px-1 py-2 text-sm font-semibold leading-tight text-slate-700 transition-colors hover:text-indigo-700 xl:min-h-9 xl:whitespace-nowrap xl:px-3 xl:py-0 2xl:px-4 dark:text-surface-200 dark:hover:text-indigo-200"
              :to="item.to"
              >{{ item.label }}
            </NuxtLink>
            <div
              v-if="item?.subMenu"
              class="hidden pl-6 animate-fadein xl:hidden"
            >
              <ul class="my-2 flex flex-col gap-2">
                <template v-for="(subItem, j) of item.subMenu" :key="j">
                  <li>
                    <NuxtLink
                      :to="subItem.to"
                      class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm text-slate-700 hover:bg-slate-100 dark:text-gray-300 dark:hover:bg-surface-900"
                    >
                      <Icon :name="subItem.icon" />
                      <span class="">{{ subItem.label }}</span>
                    </NuxtLink>
                  </li>
                </template>
              </ul>
            </div>

            <div
              v-if="activeItem?.subMenu && activeItem?.label == item?.label"
              :class="
                activeItem
                  ? 'opacity-100 visible z-[99]'
                  : 'opacity-0 invisible z-[-99]'
              "
              class="absolute top-full mt-2 hidden w-[320px] overflow-hidden rounded-lg border border-slate-200 bg-white shadow-xl transition-all animate-fadein animate-duration-150 xl:block dark:border-surface-700 dark:bg-surface-950"
            >
              <div class="bg-white p-1 dark:bg-surface-950">
                <template v-for="(subItem, j) of activeItem?.subMenu" :key="j">
                  <div>
                    <NuxtLink
                      :to="subItem.to"
                      class="flex w-full items-start gap-3 rounded-lg px-3 py-2.5 transition-colors hover:bg-slate-100 dark:hover:bg-surface-900"
                      @mouseenter="selectedCategory = Number(j)"
                    >
                      <span
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 p-1.5 text-indigo-700 dark:border-surface-700 dark:bg-surface-900 dark:text-indigo-200"
                      >
                        <Icon :name="subItem.icon" />
                      </span>
                      <span class="flex-1">
                        <h4
                          class="text-sm font-semibold leading-tight text-slate-900 dark:text-surface-0"
                        >
                          {{ subItem.label }}
                        </h4>
                        <span
                          class="mt-1 block text-xs leading-snug text-slate-500 dark:text-surface-400"
                          >{{ subItem.description }}</span
                        >
                      </span>
                    </NuxtLink>
                  </div>
                </template>
              </div>
              <div
                class="border-t border-slate-200 bg-slate-50 p-2.5 dark:border-surface-700 dark:bg-surface-900"
              >
                <div class="flex items-center">
                  <div class="flex-1 flex items-center justify-center gap-2">
                    <Icon
                      name="solar:info-circle-linear"
                      size="1.4rem"
                      class="text-gray-500 mr-3"
                    />
                    <span class="text-sm font-semibold leading-none text-slate-600 dark:text-gray-300">
                      {{ item.label }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </li>
        </template>
      </ul>
      <div
        class="flex items-center justify-between px-4 xl:ml-3 xl:border-l xl:border-slate-200 xl:pl-3 xl:pr-0 dark:xl:border-surface-700"
      >
        <Button
          v-if="!isAuthenticated"
          label="Sign In"
          icon="pi pi-sign-in"
          icon-pos="right"
          severity="contrast"
          class="mr-2 !rounded-lg hover:!bg-indigo-700 hover:!text-white"
          size="small"
          @click="openLogin = !openLogin"
        />

        <LazyAppProfileMenu />

        <Divider layout="vertical" class="!mx-3" />

        <ToggleSwitch
          v-model="darkOrLightMode"
          class="ml-2"
          :pt="{
            slider: {
              class:
                '!border-1 dark:!bg-gray-300 dark:!border-gray-800 !bg-orange-100 !border-orange-500',
            },
            handle: {
              class: '!bg-transparent !text-orange-500 dark:!text-gray-800',
            },
          }"
        >
          <template #handle="{ checked }">
            <i
              :class="[
                '!text-xs p-1 pi',
                {
                  'pi-moon': checked,
                  'pi-sun': !checked,
                },
              ]"
            />
          </template>
        </ToggleSwitch>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
const { isAuthenticated } = useSanctumAuth()

const hoveredItem: Ref = ref(null)
const selectedItem: Ref = ref(null)
const openLogin = ref<boolean>(false)

const activeItem = computed(() => hoveredItem.value || selectedItem.value)

const isDark = useDark()
const darkOrLightMode = ref(isDark)

watch(darkOrLightMode, val => {
  isDark.value = val
})

function setActiveItem(item: Navs) {
  if (item?.subMenu) {
    hoveredItem.value = item
    selectedItem.value = item
  } else {
    hoveredItem.value = null
    selectedItem.value = null
  }
}

const closeMenu = () => {
  hoveredItem.value = null
  selectedItem.value = null
}

const selectedCategory = ref(0)
const navs = ref<Navs[]>([
  {
    label: 'Home',
    to: '/',
  },
  {
    label: 'About',
    subMenu: [
      {
        label: 'Humble Beginnings of PYCS',
        description: 'Learn how PYCS was founded', // Description from your input
        icon: 'solar:leaf-linear',
        to: '/about/humble-beginnings-pycs', // Updated route
      },
      {
        label: 'Philosophy and Institutional Objectives',
        description: 'Guidelines for behavior and consequences', // Description from your input
        icon: 'solar:shield-check-linear',
        to: '/about/philosophy-institutional-objectives', // Updated route
      },
      {
        label: 'General Standards and Policies',
        description: 'Medical guidelines and safety procedures', // Description from your input
        icon: 'solar:scale-linear',
        to: '/about/general-standard-policies', // Updated route
      },
      {
        label: 'Discipline Policies',
        description: 'Miscellaneous rules and information', // Description from your input
        icon: 'solar:sledgehammer-linear',
        to: '/about/discipline-policies', // Updated route
      },
      {
        label: 'Health Standards and Policies',
        description: 'Protocols for student wellness and safety', // Description from your input
        icon: 'solar:heart-pulse-linear',
        to: '/about/health-standards-policies', // Updated route
      },
      {
        label: 'General Policies',
        description: 'Broad guidelines for school operations', // Description from your input
        icon: 'solar:book-bookmark-linear',
        to: '/about/general-policies', // Updated route
      },
    ],
  },
  {
    label: 'School Calendar',
    to: '/school-calendar',
  },
  {
    label: 'School Updates',
    to: '/school-updates',
  },
  {
    label: 'School Operation',
    subMenu: [
      {
        label: 'Class Periods and Learning Cycle',
        description: 'Understand class times and the learning structure', // Description from your input
        icon: 'solar:map-point-school-linear',
        to: '/school-operation/class-periods-learning-cycle', // Generated route
      },
      {
        label: 'Enrollment of Learners',
        description: 'Information on how to register students', // Description from your input
        icon: 'solar:user-circle-linear',
        to: '/school-operation/enrollment-learners', // Generated route
      },
      {
        label: 'Admission Requirements',
        description: 'List of criteria and documents needed', // Description from your input
        icon: 'solar:list-check-linear',
        to: '/school-operation/admission-requirements', // Generated route
      },
      {
        label: 'Admission Procedures',
        description: 'Step-by-step guide to the application process', // Description from your input
        icon: 'solar:refresh-circle-linear',
        to: '/school-operation/admission-procedures', // Generated route
      },
      {
        label: 'Policies for Early Registration',
        description: 'Rules and timelines for registering early', // Description from your input
        icon: 'solar:hourglass-linear',
        to: '/school-operation/policies-early-registration', // Generated route
      },
      {
        label: 'Policy on Withholding of Credentials',
        description: 'Rules regarding holds on official documents', // Description from your input
        icon: 'solar:diploma-linear',
        to: '/school-operation/policy-withholding-credentials', // Generated route
      },
    ],
  },
  {
    label: 'Academics',
    subMenu: [
      {
        label: 'Curriculum',
        description: 'Overview of courses and subjects offered.',
        icon: 'solar:widget-4-linear',
        to: '/academics/curriculum/pre-school', // Generated route
      },
      {
        label: 'Special Programs',
        description: 'Unique learning opportunities and focuses.',
        icon: 'solar:user-circle-linear',
        to: '/academics/special-programs', // Generated route
      },
      {
        label: 'Academic Policy',
        description: 'Rules governing studies, grades, and conduct.',
        icon: 'solar:book-bookmark-linear',
        to: '/academics/academic-policy', // Generated route
      },
      {
        label: 'Grading System and Grade Description',
        description: 'How grades are calculated and what they mean.',
        icon: 'solar:chart-linear',
        to: '/academics/grading-system-grade-description', // Generated route
      },
      {
        label: 'Promotion and Retention',
        description: 'Criteria for advancing to the next grade level.',
        icon: 'solar:arrow-up-linear',
        to: '/academics/promotion-retention', // Generated route
      },
      {
        label: 'School Programs',
        description: 'General school activities and program offerings.',
        icon: 'solar:library-linear',
        to: '/academics/school-programs', // Generated route
      },
      {
        label: 'Clubs / Organization',
        description: 'Explore student groups and activities.',
        icon: 'solar:users-group-rounded-linear',
        to: '/academics/clubs-organization', // Generated route
      },
      {
        label: 'School Year-End Activities',
        description:
          'Events and activities marking the end of the school year.',
        icon: 'solar:confetti-linear',
        to: '/academics/school-year-end-activities', // Generated route
      },
      {
        label: 'Others',
        description: 'Find miscellaneous academic information here.',
        icon: 'solar:menu-dots-linear',
        to: '/academics/others', // Generated route
      },
    ],
  },
  {
    label: 'Galleries',
    to: '/galleries',
    description:
      'Explore the vibrant life of our school! Galleries featuring academics, athletics, arts, and student activities.',
  },

  // SOON
  // {
  //   label: 'News',
  //   to: '/news',
  // },
])
</script>
