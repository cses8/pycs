export default defineNuxtPlugin({
  name: 'pycs-sanctum-init',
  dependsOn: ['nuxt-auth-sanctum'],
  async setup() {
    const { init } = useSanctumAuth()

    try {
      await init()
    } catch {
      // A guest refresh should remain a normal unauthenticated page load.
    }
  },
})
