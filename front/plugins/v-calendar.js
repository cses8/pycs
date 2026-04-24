import VCalendar from 'v-calendar';

export default defineNuxtPlugin({
  parallel: true,
  async setup (nuxtApp) {
    // the next plugin will be executed immediately
		nuxtApp.vueApp.use(VCalendar,{})
  }
})