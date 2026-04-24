import { defineStore } from 'pinia'

export const useSchoolYearStore = defineStore('SchoolYear', () => {
  const selectedSchoolYear: Ref<SchoolYear> = ref({
    id: ulid(),
    description: ulid(),
  })

  return { selectedSchoolYear }
})
