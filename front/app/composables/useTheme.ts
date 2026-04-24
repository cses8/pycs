// composables/useTheme.ts
import themeClassNames from '~/assets/themes/themes.json'

const defaultTheme = themeClassNames[0] || 'bg-theme-original'

export const useTheme = () => {
  const currentThemeClass = useState<string>('currentTheme', () => defaultTheme)

  const setTheme = (themeName: string) => {
    if (themeClassNames.includes(themeName)) {
      // Only update if value actually changed
      if (currentThemeClass.value !== themeName) {
        currentThemeClass.value = themeName
        if (process.client) {
          try {
            localStorage.setItem('appTheme', themeName)
          } catch (e) {
            console.error('[useTheme] Error setting localStorage:', e)
          }
        }
      }
    } else {
      console.warn(`Attempted to set invalid theme: ${themeName}`)
    }
  }

  const loadThemeFromStorage = () => {
    if (process.client) {
      const savedTheme = localStorage.getItem('appTheme')
      if (savedTheme && themeClassNames.includes(savedTheme)) {
        if (currentThemeClass.value !== savedTheme) {
          currentThemeClass.value = savedTheme
        }
      } else if (!themeClassNames.includes(currentThemeClass.value)) {
        currentThemeClass.value = defaultTheme
        localStorage.setItem('appTheme', defaultTheme)
      }
    }
  }

  // NEW: Computed property formatted for PrimeVue Dropdown
  const themeOptionsForDropdown = computed(() => {
    return themeClassNames.map(themeName => ({
      // Creates a user-friendly label from the class name
      label: themeName
        .replace('bg-theme-', '')
        .replace(/-/g, ' ')
        .replace(/\b\w/g, l => l.toUpperCase()), // Capitalize words
      value: themeName, // The actual CSS class name
    }))
  })

  return {
    currentThemeClass: readonly(currentThemeClass), // Expose as readonly ref
    setTheme,
    loadThemeFromStorage,
    availableThemes: themeClassNames, // Keep raw names if needed
    themeOptions: themeOptionsForDropdown, // Provide formatted options
  }
}
