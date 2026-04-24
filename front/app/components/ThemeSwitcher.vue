<template>
  <div class="fixed top-4 right-4 z-50">
    <Button
      ref="menuTriggerButton"
      text
      rounded
      severity="secondary"
      aria-label="Select Theme"
      class="theme-trigger-button bg-white/10 hover:bg-white/20 hover:text-black"
      @click="toggleMenu"
      aria-haspopup="true"
      aria-controls="theme_popup_overlay_menu"
    >
      <Icon name="mdi:palette-outline" class="text-white" />
    </Button>

    <Menu
      ref="menu"
      id="theme_popup_overlay_menu"
      :model="themeMenuItems"
      popup
      class="theme-popup-menu"
      :pt="{
        /* PassThrough for internal element styling */
        menu: { class: 'theme-menu-list' }, // UL element
        menuitem: { class: 'theme-menu-item' }, // LI element
        content: { class: 'theme-menu-item-content' }, // Action/Link element (contains icon/label)
        label: { class: 'theme-menu-item-label' }, // Label span
        // separator: { class: 'my-1 border-t border-white/10' } // If using separators
      }"
    />
  </div>
</template>

<script setup lang="ts">
import Button from 'primevue/button'
import Menu from 'primevue/menu'
import type { MenuItem } from 'primevue/menuitem'

const { currentThemeClass, setTheme, availableThemes } = useTheme()
const menu = ref<InstanceType<typeof Menu> | null>(null)
const menuTriggerButton = ref<InstanceType<typeof Button> | null>(null)

const toggleMenu = (event: Event) => {
  menu.value?.toggle(event)
}

const themeMenuItems = computed<MenuItem[]>(() => {
  return availableThemes.map(themeName => ({
    label: themeName
      .replace('bg-theme-', '')
      .replace(/-/g, ' ')
      .replace(/\b\w/g, (l: string) => l.toUpperCase()),
    command: () => {
      setTheme(themeName)
    },
    class:
      currentThemeClass.value === themeName ? 'active-theme-menu-item' : '',
  }))
})
</script>

<style scoped>
/* Styles remain the same */
:deep(.theme-trigger-button.p-button) {
  width: 2.5rem;
  height: 2.5rem;
  border: 1px solid rgba(255, 255, 255, 0.2) !important;
}
:deep(.theme-popup-menu.p-menu) {
  background-color: rgba(31, 41, 55, 0.9) !important;
  border: 1px solid rgba(255, 255, 255, 0.2) !important;
  backdrop-filter: blur(5px);
  min-width: 13rem;
  box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
  border-radius: 6px;
  padding: 0.5rem 0;
}
:deep(.theme-menu-list.p-menu-list) {
  padding: 0;
}
:deep(.theme-menu-item.p-menuitem) {
  margin: 0 !important;
}
:deep(.theme-menu-item-content.p-menuitem-content) {
  transition: background-color 0.2s;
  border-radius: 4px;
  margin: 0.125rem 0.5rem;
  padding: 0.5rem 0.75rem;
}
:deep(.theme-menu-item-content.p-menuitem-content:hover) {
  background-color: rgba(255, 255, 255, 0.1) !important;
}
:deep(.theme-menu-item-label.p-menuitem-text) {
  color: rgba(229, 231, 235, 0.9) !important;
  font-size: 0.875rem;
}
:deep(.theme-menu-item-content:hover .p-menuitem-text) {
  color: #ffffff !important;
}
:deep(.active-theme-menu-item .p-menuitem-content) {
  background-color: rgba(99, 102, 241, 0.4) !important;
}
:deep(.active-theme-menu-item .p-menuitem-text) {
  color: #ffffff !important;
  font-weight: 600;
}
</style>
