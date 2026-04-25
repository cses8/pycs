<template>
  <div
    class="min-h-screen h-full w-full bg-[#f8fafc] text-slate-900 dark:bg-surface-950 dark:text-surface-0"
  >
    <AppNavBar class="z-[999]" />
    <main
      :class="[
        'pycs-public-frame',
        applyContentRedesign ? 'pycs-content-pages' : 'pycs-raw-page',
      ]"
    >
      <slot />
    </main>
    <AppFooter />
  </div>
</template>
<script setup lang="ts">
const route = useRoute()

const excludedVisualRoutes = ['/galleries', '/school-calendar', '/school-updates']
const applyContentRedesign = computed(() => {
  return !excludedVisualRoutes.some(path => route.path.startsWith(path))
})
</script>
