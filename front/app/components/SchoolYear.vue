<template>
  <div class="flex flex-col gap-2">
    <template v-for="item in schoolYears" :key="item.id">
      <button
        type="button"
        :class="[
          'group flex w-full items-center justify-between gap-3 rounded-lg border px-3 py-3 text-left text-sm font-semibold transition',
          item.id === schoolYearStore.selectedSchoolYear?.id
            ? 'border-blue-700 bg-blue-700 text-white shadow-sm dark:border-blue-400 dark:bg-blue-500'
            : 'border-slate-200 bg-slate-50 text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-900 dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:hover:border-blue-300/40 dark:hover:bg-blue-400/10',
        ]"
        @click="schoolYearStore.selectedSchoolYear = item"
      >
        <span class="min-w-0 leading-5">{{ item.description }}</span>
        <Icon
          name="lucide:check"
          :class="[
            'size-4 shrink-0',
            item.id === schoolYearStore.selectedSchoolYear?.id
              ? 'opacity-100'
              : 'opacity-0 group-hover:opacity-40',
          ]"
        />
      </button>
    </template>

    <div
      v-if="!schoolYears.length"
      class="rounded-lg border border-dashed border-slate-300 p-4 text-sm text-slate-500 dark:border-white/15 dark:text-slate-400"
    >
      No school years available.
    </div>
  </div>
</template>

<script setup lang="ts">
const schoolYearStore = useSchoolYearStore()

const schoolYears = computedAsync<SchoolYear[]>(async () => {
  const response = await useGetFetch<SchoolYear[]>('api/school-years')

  if (Array.isArray(response)) {
    const reversed = response.reverse()

    if (
      !reversed.some((schoolYear) => {
        return schoolYear.id === schoolYearStore.selectedSchoolYear?.id
      })
    ) {
      schoolYearStore.selectedSchoolYear = reversed[0]
    }

    return reversed as SchoolYear[]
  }

  return []
}, [])
</script>
