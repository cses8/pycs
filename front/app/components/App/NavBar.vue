<template>
  <div
    class="w-full fixed top-0 py-4 px-6 md:px-12 lg:px-20 shadow-[0px_4px_10px_rgba(0,0,0,0.03),0px_0px_2px_rgba(0,0,0,0.06),0px_2px_6px_rgba(0,0,0,0.12)] flex items-center justify-between bg-white/70 backdrop-blur-[70px]"
    @mouseleave="closeMenu"
  >
    <LazyAppLogin v-model:visible="openLogin" />
    <NuxtLink class="flex items-center gap-4" :to="'/'">
      <Image src="/images/logo.webp" alt="Image" width="50" height="50" />

      <span class="text-lg font-semibold leading-normal text-surface-800">
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
      class="relative cursor-pointer block lg:hidden text-surface-700 mr-0 ml-auto"
    >
      <i class="pi pi-bars text-3xl" />
    </a>

    <div
      class="border-b border-surface lg:border-0 animate-fadeinup absolute lg:static bg-white lg:bg-transparent lg:dark:bg-transparent w-full left-0 pb-4 lg:py-0 hidden lg:flex flex-1 items-center top-[5rem]"
    >
      <ul
        class="border-y lg:mt-0 border-surface lg:border-0 select-none relative flex-1 flex lg:flex-row flex-col lg:mb-0 mb-4 lg:items-center lg:justify-center gap-2 lg:gap-4 p-0"
        @mouseleave="hoveredItem = null"
      >
        <template v-for="(item, index) of navs" :key="index">
          <li @mouseenter="setActiveItem(item)" @click="setActiveItem(item)">
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
                  ? 'border-primary text-primary'
                  : 'border-transparent text-surface-800'
              "
              class="group relative lg:border-b p-2 lg:p-2 transition-all flex items-center justify-between gap-2 hover:text-blue-800 text-base font-medium leading-tight"
            >
              {{ item.label }}
              <i class="pi pi-angle-down !text-base !leading-none" />
              <span
                :class="
                  activeItem?.label === item.label ? 'opacity-100' : 'opacity-0'
                "
                class="hidden lg:block absolute top-full left-1/2 -translate-x-1/2 transition-all border-l-[4px] border-l-transparent border-r-[4px] border-transparent border-t-[4px] border-t-primary"
              />
            </a>
            <NuxtLink
              v-else
              class="py-2 px-4 lg:p-4 transition-all flex text-surface-800 hover:text-blue-800 text-base font-medium leading-tight"
              :to="item.to"
              >{{ item.label }}
            </NuxtLink>
            <div
              v-if="item?.subMenu"
              class="lg:hidden pl-12 hidden animate-fadein"
            >
              <ul class="flex flex-col gap-6 my-2">
                <template v-for="(subItem, j) of item.subMenu" :key="j">
                  <li>
                    <NuxtLink
                      :to="subItem.to"
                      class="flex items-center gap-4 text-surface-700 dark:text-gray-400"
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
              class="lg:block hidden animate-fadein animate-duration-150 w-[305px] absolute top-full rounded-[10px] overflow-hidden bg-surface-100 dark:bg-surface-900 transition-all border border-surface-200 dark:border-surface-700 shadow-[0px_48px_80px_rgba(0,0,0,0.04)]"
            >
              <div class="bg-surface-0 dark:bg-surface-950">
                <template v-for="(subItem, j) of activeItem?.subMenu" :key="j">
                  <div class="p-1">
                    <NuxtLink
                      :to="subItem.to"
                      class="flex items-start gap-2.5 w-full px-4 py-2 transition-all hover:bg-surface-100 dark:hover:bg-surface-900 rounded-lg"
                      @mouseenter="selectedCategory = Number(j)"
                    >
                      <span
                        class="w-8 h-8 rounded-[6px] border border-surface-200 dark:border-surface-700 bg-surface-0 dark:bg-surface-900 flex items-center justify-center p-1.5 dark:text-white"
                      >
                        <Icon :name="subItem.icon" />
                      </span>
                      <span class="flex-1">
                        <h4
                          class="text-base leading-tight font-medium text-surface-900 dark:text-surface-0"
                        >
                          {{ subItem.label }}
                        </h4>
                        <span
                          class="text-sm leading-tight font-light text-surface-500 dark:text-surface-400"
                          >{{ subItem.description }}</span
                        >
                      </span>
                    </NuxtLink>
                  </div>
                </template>
              </div>
              <div
                class="p-2.5 border-t border-surface-200 dark:border-surface-700"
              >
                <div class="flex items-center">
                  <div class="flex-1 flex items-center justify-center gap-2">
                    <Icon
                      name="typcn:info-large"
                      size="1.4rem"
                      class="text-gray-500 mr-3"
                    />
                    <span
                      class="text-base font-medium leading-none dark:text-gray-500"
                    >
                      {{ item.label }}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </li>
        </template>
      </ul>
      <div class="flex items-center place-content-between px-8 lg:px-0">
        <Button
          v-if="!isAuthenticated"
          label="Sign In"
          icon="pi pi-sign-in"
          icon-pos="right"
          severity="contrast"
          class="hover:!bg-blue-600 hover:!text-white mr-2"
          size="small"
          @click="openLogin = !openLogin"
        />

        <LazyAppProfileMenu />

        <Divider layout="vertical" class="!mx-auto" />

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
const { user, isAuthenticated, logout } = useSanctumAuth()

const hoveredItem: Ref = ref(null)
const selectedItem: Ref = ref(null)
const openLogin = ref<boolean>(false)

const activeItem = computed(() => hoveredItem.value || selectedItem.value)

const isDark = useDark()
const darkOrLightMode = ref(isDark)

const authenticatedUser = computed(() => {
  const userInfo = user.value as User
  if (userInfo) {
    const names = userInfo.name.split(' ')
    if (names) return names[0]
    return names
  }
  return ''
})

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
        icon: 'mdi:seed-outline',
        to: '/about/humble-beginnings-pycs', // Updated route
      },
      {
        label: 'Philosophy and Institutional Objectives',
        description: 'Guidelines for behavior and consequences', // Description from your input
        icon: 'mdi:shield-outline',
        to: '/about/philosophy-institutional-objectives', // Updated route
      },
      {
        label: 'General Standards and Policies',
        description: 'Medical guidelines and safety procedures', // Description from your input
        icon: 'mdi:scale-balance',
        to: '/about/general-standard-policies', // Updated route
      },
      {
        label: 'Discipline Policies',
        description: 'Miscellaneous rules and information', // Description from your input
        icon: 'mdi:gavel',
        to: '/about/discipline-policies', // Updated route
      },
      {
        label: 'Health Standards and Policies',
        description: 'Protocols for student wellness and safety', // Description from your input
        icon: 'mdi:heart-pulse',
        to: '/about/health-standards-policies', // Updated route
      },
      {
        label: 'General Policies',
        description: 'Broad guidelines for school operations', // Description from your input
        icon: 'mdi:book-open-page-variant-outline',
        to: '/about/general-policies', // Updated route
      },
    ],
  },
  {
    label: 'School Calendar',
    to: '/school-calendar',
  },
  {
    label: 'School Operation',
    subMenu: [
      {
        label: 'Class Periods and Learning Cycle',
        description: 'Understand class times and the learning structure', // Description from your input
        icon: 'mdi:school-outline',
        to: '/school-operation/class-periods-learning-cycle', // Generated route
      },
      {
        label: 'Enrollment of Learners',
        description: 'Information on how to register students', // Description from your input
        icon: 'tabler:user-pentagon',
        to: '/school-operation/enrollment-learners', // Generated route
      },
      {
        label: 'Admission Requirements',
        description: 'List of criteria and documents needed', // Description from your input
        icon: 'tabler:checklist',
        to: '/school-operation/admission-requirements', // Generated route
      },
      {
        label: 'Admission Procedures',
        description: 'Step-by-step guide to the application process', // Description from your input
        icon: 'fluent:arrow-sync-checkmark-24-regular',
        to: '/school-operation/admission-procedures', // Generated route
      },
      {
        label: 'Policies for Early Registration',
        description: 'Rules and timelines for registering early', // Description from your input
        icon: 'la:hourglass-start',
        to: '/school-operation/policies-early-registration', // Generated route
      },
      {
        label: 'Policy on Withholding of Credentials',
        description: 'Rules regarding holds on official documents', // Description from your input
        icon: 'tabler:certificate-off',
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
        icon: 'tabler:puzzle-2',
        to: '/academics/curriculum/pre-school', // Generated route
      },
      {
        label: 'Special Programs',
        description: 'Unique learning opportunities and focuses.',
        icon: 'tabler:user-pentagon',
        to: '/academics/special-programs', // Generated route
      },
      {
        label: 'Academic Policy',
        description: 'Rules governing studies, grades, and conduct.',
        icon: 'fa6-solid:book-open-reader',
        to: '/academics/academic-policy', // Generated route
      },
      {
        label: 'Grading System and Grade Description',
        description: 'How grades are calculated and what they mean.',
        icon: 'tabler:chart-infographic',
        to: '/academics/grading-system-grade-description', // Generated route
      },
      {
        label: 'Promotion and Retention',
        description: 'Criteria for advancing to the next grade level.',
        icon: 'tabler:arrow-big-up-line',
        to: '/academics/promotion-retention', // Generated route
      },
      {
        label: 'School Programs',
        description: 'General school activities and program offerings.',
        icon: 'bi:journals',
        to: '/academics/school-programs', // Generated route
      },
      {
        label: 'Clubs / Organization',
        description: 'Explore student groups and activities.',
        icon: 'fluent:people-community-24-regular',
        to: '/academics/clubs-organization', // Generated route
      },
      {
        label: 'School Year-End Activities',
        description:
          'Events and activities marking the end of the school year.',
        icon: 'fluent-emoji-high-contrast:party-popper',
        to: '/academics/school-year-end-activities', // Generated route
      },
      {
        label: 'Others',
        description: 'Find miscellaneous academic information here.',
        icon: 'tabler:dots',
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
