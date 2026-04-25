<template>
  <div class="card flex justify-center">
    <Button
      v-if="isAuthenticated"
      :label="authenticatedUser"
      icon="pi pi-ellipsis-v"
      icon-pos="right"
      severity="secondary"
      class="hover:!bg-blue-600 hover:!text-white"
      size="small"
      as="a"
      aria-haspopup="true"
      aria-controls="overlay_menu"
      @click="toggle"
    />
    <Menu ref="menu" id="overlay_menu" :model="items" :popup="true" />
  </div>

  <LazyAnnouncementTable v-model:visible="showAnnouncementTable" />
  <LazySchoolYearTable v-model:visible="showSchoolYearTable" />
</template>

<script setup>
import { ref } from 'vue'

const { user, isAuthenticated, logout } = useSanctumAuth()

const menu = ref()
const showAnnouncementTable = ref(false)
const showSchoolYearTable = ref(false)
const items = ref([
  {
    label: 'Options',
    items: [
      {
        label: 'School Years',
        icon: 'pi pi-calendar',
        command: () => {
          showSchoolYearTable.value = true
        },
      },
      {
        label: 'Announcement',
        icon: 'pi pi-calendar-plus',
        command: () => {
          showAnnouncementTable.value = true
        },
      },
      {
        label: 'Logout',
        icon: 'pi pi-sign-out',
        command: () => signOut(),
      },
    ],
  },
])

const authenticatedUser = computed(() => {
  const userInfo = user.value
  if (userInfo) {
    const names = userInfo.name.split(' ')
    if (names) return names[0]
    return names
  }
  return ''
})

const toggle = event => {
  menu.value.toggle(event)
}

async function signOut() {
  await task()
    .do(async () => useLoaderStore().processStep('logout'))
    .do(async () => asyncTimeout(1000))
    .do(async () => await logout())
    .do(async () => useLoaderStore().setDoneToTheOpenLoader())
    .do(() => redirectTo('/'))
    .end()
}
</script>
