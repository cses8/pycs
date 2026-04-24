<template>
  <div class="p-4">
    <div class="flex flex-col gap-1">
      <template v-for="item in schoolYears" :key="item.id">
        <Button
          @click="schoolYearStore.selectedSchoolYear = item"
          :class="[
            'w-full h-full p-4 rounded-border bg-black/30 dark:bg-white/30 border-0 hover:!bg-black/40 dark:hover:!bg-white/40',
            {
              '!border-2 !border-green-600 bg-black/40 dark:!bg-white/40':
                item.id == schoolYearStore.selectedSchoolYear.id,
            },
          ]"
        >
          <span class="text-xs xl:!text-xl text-white font-medium my-2">
            {{ item.description }}
          </span>
        </Button>
      </template>
    </div>
  </div>
</template>

<script setup lang="ts">
const schoolYearStore = useSchoolYearStore()
const schoolYears = computedAsync<SchoolYear[]>(async () => {
  const response = await useGetFetch<SchoolYear[]>('api/school-years')

  if (Array.isArray(response)) {
    const reversed = response.reverse()
    schoolYearStore.selectedSchoolYear = reversed[0]
    return reversed as SchoolYear[]
  }

  return []
}, [])
</script>
