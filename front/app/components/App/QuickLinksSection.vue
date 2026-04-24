<template>
  <div
    class="bg-surface-0 dark:bg-surface-950 overflow-hidden py-20 px-6 md:px-12 lg:px-20"
  >
    <div class="flex flex-col gap-12 items-center">
      <UiBlurReveal :duration="0.5">
        <div class="flex flex-col gap-4 items-center max-w-3xl mx-auto">
          <h3
            class="text-surface-900 dark:text-surface-0 font-bold text-2xl leading-tight text-center"
          >
            Stay Connected
          </h3>
          <p
            class="text-surface-500 dark:text-surface-400 text-center leading-tight"
          >
            Find important updates, browse galleries, and check upcoming events.
          </p>
        </div>
      </UiBlurReveal>

      <div
        class="grid gap-4 grid-cols-1 md:grid-cols-1 xl:grid-cols-2 max-w-7xl mx-auto"
      >
        <template v-for="(quickLink, index) in quickLinks" :key="quickLink.id">
          <div
            v-motion
            :initial="{
              opacity: 0,
              y: 100,
              rotateZ: index % 2 === 0 ? -15 : 15,
            }"
            :visible="{
              opacity: 1,
              y: 0,
              rotateZ: 0,
              transition: {
                type: 'spring',
                stiffness: 100,
                damping: 15,
                delay: 300 + index * 150,
              },
            }"
          >
            <ClientOnly>
              <UiCardContainer>
                <UiCardBody
                  class="group/card relative size-auto rounded-xl border border-black/[0.1] bg-gray-50 p-6 dark:border-white/[0.2] dark:bg-white dark:hover:shadow-2xl dark:hover:shadow-emerald-500/[0.1]"
                >
                  <UiCardItem
                    :translate-z="50"
                    class="text-xl font-bold text-gray-600"
                  >
                    {{ quickLink.title }}
                  </UiCardItem>
                  <UiCardItem
                    as="p"
                    translate-z="60"
                    class="mt-2 max-w-sm text-sm text-gray-600"
                  >
                    {{ quickLink.description }}
                  </UiCardItem>
                  <UiCardItem :translate-z="100" class="mt-4 w-full">
                    <Icon
                      :name="quickLink.icon"
                      height="1000"
                      width="1000"
                      class="h-60 w-full rounded-xl object-cover group-hover/card:shadow-xl bg-black/30"
                    />
                  </UiCardItem>
                  <div class="mt-20 flex items-center justify-between">
                    <UiCardItem
                      :translate-z="20"
                      as="a"
                      class="rounded-xl bg-black px-4 py-2 text-xs font-bold text-white cursor-pointer"
                      @click="goToPage(quickLink.path)"
                    >
                      Explore
                    </UiCardItem>
                  </div>
                </UiCardBody>
              </UiCardContainer>
            </ClientOnly>
          </div>
        </template>
      </div>
    </div>
  </div>
</template>

<script setup>
const router = useRouter()

const quickLinks = ref([
  // {
  //   id: ulid(),
  //   icon: 'fluent-color:news-24',
  //   title: 'School Updates',
  //   description:
  //     'Keep up-to-date with everything happening on campus. Find news articles, updates, and stories here.',
  //   path: '/news',
  // },
  {
    id: ulid(),
    icon: 'flat-color-icons:gallery',
    title: 'School Moments',
    description:
      'See our students in action! Check out galleries from academic, sports, and extracurricular events.',
    path: '/galleries',
  },
  {
    id: ulid(),
    icon: 'flat-color-icons:calendar',
    title: 'School Calendar',
    description:
      'Stay organized with our official school calendar. Find important dates, holidays, exam schedules, and events.',
    path: '/school-calendar',
  },
])

function goToPage(path) {
  router.push({ path })
}
</script>
