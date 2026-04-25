import { defineStore } from 'pinia'

export const useSchoolYearStore = defineStore('SchoolYear', () => {
  const selectedSchoolYear: Ref<SchoolYear> = ref({
    id: 0,
    description: '',
  })

  return { selectedSchoolYear }
})
