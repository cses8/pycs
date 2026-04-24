<template>
  <!-- theme preloaded -->
  <div
    class="bg-theme-deep-metal bg-theme-night-sky bg-theme-subtle-stone bg-theme-cool-blues bg-theme-purple-haze bg-theme-deep-indigo bg-theme-ocean-vista bg-theme-emerald-wave bg-theme-deep-forest bg-theme-lime-twist bg-theme-sunset bg-theme-warm-rose bg-theme-fiery-sky bg-theme-playful-mix"
  />

  <div :class="['page-layout-centered', currentThemeClass]">
    <slot />
    <ThemeSwitcher />
  </div>
</template>

<script setup>
// Get theme state and methods from composable
const { currentThemeClass, loadThemeFromStorage } = useTheme()

// Load theme from storage when component mounts on the client side
onMounted(() => {
  loadThemeFromStorage()
})
</script>
