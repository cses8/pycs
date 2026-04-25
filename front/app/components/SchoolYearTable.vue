<template>
  <Dialog
    v-model:visible="visible"
    modal
    :closeOnEscape="false"
    :draggable="false"
    class="!w-[min(96vw,64rem)]"
    pt:root:class="!border-0 !bg-transparent !shadow-none"
    pt:mask:class="backdrop-blur-sm !bg-slate-950/45"
  >
    <template #container="{ closeCallback }">
      <section
        class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl shadow-slate-950/20 dark:border-surface-700 dark:bg-surface-950"
      >
        <header
          class="flex flex-col gap-5 border-b border-slate-200 bg-slate-950 px-5 py-5 text-white sm:px-6 lg:flex-row lg:items-center lg:justify-between dark:border-surface-800"
        >
          <div class="flex min-w-0 items-start gap-4">
            <div
              class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-white text-blue-800"
            >
              <Icon name="lucide:calendar-range" class="size-6" />
            </div>
            <div class="min-w-0">
              <p class="text-xs font-semibold uppercase text-blue-200">
                Maintenance
              </p>
              <h2 class="mt-1 text-2xl font-bold leading-tight text-white">
                School Years
              </h2>
              <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-300">
                Encode academic year labels used by school year filters.
              </p>
            </div>
          </div>

          <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div
              class="rounded-lg border border-white/10 bg-white/10 px-4 py-3 text-sm"
            >
              <span class="block text-xs font-semibold uppercase text-blue-200">
                Total Records
              </span>
              <span class="text-xl font-bold text-white">{{ schoolYears.length }}</span>
            </div>
            <Button
              label="Add school year"
              icon="pi pi-plus"
              class="!rounded-lg !border-blue-600 !bg-blue-600 !px-4 !py-3 hover:!border-blue-500 hover:!bg-blue-500"
              @click="addSchoolYear()"
            />
            <Button
              icon="pi pi-times"
              rounded
              text
              severity="secondary"
              aria-label="Close school years"
              class="!h-10 !w-10 !text-slate-200 hover:!bg-white/10 hover:!text-white"
              @click="closeCallback"
            />
          </div>
        </header>

        <div class="space-y-4 bg-slate-50 p-4 sm:p-6 dark:bg-surface-900">
          <div
            class="flex flex-col gap-3 rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:flex-row sm:items-center sm:justify-between dark:border-surface-700 dark:bg-surface-950"
          >
            <div>
              <h3 class="text-base font-bold text-slate-950 dark:text-white">
                School Year Library
              </h3>
              <p class="mt-1 text-sm text-slate-600 dark:text-surface-300">
                Showing {{ filteredSchoolYears.length }} of {{ schoolYears.length }} records.
              </p>
            </div>

            <span class="relative w-full sm:w-72">
              <i
                class="pi pi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"
              />
              <InputText
                v-model="search"
                placeholder="Search school year"
                class="w-full !rounded-lg !border-slate-300 !py-2.5 !pl-10 !pr-3 dark:!border-surface-700 dark:!bg-surface-900"
              />
            </span>
          </div>

          <DataTable
            :value="filteredSchoolYears"
            :loading="loading"
            dataKey="id"
            stripedRows
            tableStyle="min-width: 36rem"
            class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm dark:border-surface-700"
          >
            <Column header="School Year" class="min-w-[18rem]">
              <template #body="{ data }">
                <div class="flex items-center gap-3">
                  <div
                    class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300"
                  >
                    <Icon name="lucide:calendar-range" class="size-5" />
                  </div>
                  <span class="font-bold text-slate-950 dark:text-white">
                    {{ data.description }}
                  </span>
                </div>
              </template>
            </Column>

            <Column header="Created" class="min-w-[12rem]">
              <template #body="{ data }">
                <span class="text-sm text-slate-600 dark:text-surface-300">
                  {{ formatDate(data.created_at) }}
                </span>
              </template>
            </Column>

            <Column header="Actions" class="w-[10rem]">
              <template #body="{ data }">
                <div class="flex items-center gap-1">
                  <Button
                    icon="pi pi-pencil"
                    rounded
                    text
                    aria-label="Edit school year"
                    v-tooltip.top="'Edit'"
                    @click="editSchoolYear(data)"
                  />
                  <Button
                    icon="pi pi-trash"
                    rounded
                    text
                    severity="danger"
                    aria-label="Delete school year"
                    v-tooltip.top="'Delete'"
                    @click="deleteSchoolYear(data)"
                  />
                </div>
              </template>
            </Column>

            <template #empty>
              <div class="px-4 py-12 text-center">
                <Icon
                  name="lucide:calendar-x"
                  class="mx-auto size-10 text-slate-400"
                />
                <h3 class="mt-4 text-lg font-bold text-slate-950 dark:text-white">
                  No school years found
                </h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-surface-300">
                  Add a school year or adjust the search text.
                </p>
              </div>
            </template>
          </DataTable>
        </div>
      </section>
    </template>

  </Dialog>

  <LazyFormSchoolYear
    v-model:visible="showSchoolYearForm"
    v-model:operation="schoolYearFormOperation"
    v-model:schoolYear="selectedSchoolYear"
    @onFormSuccess="handleFormSuccess"
  />
</template>

<script setup lang="ts">
import { SCHOOL_YEAR } from '~/constant/SchoolYear'

const dayjs = useDayjs()
const visible = defineModel('visible', { default: false })

const schoolYears = ref<SchoolYear[]>([])
const selectedSchoolYear = ref<SchoolYear>({ ...SCHOOL_YEAR })
const showSchoolYearForm = ref<boolean>(false)
const schoolYearFormOperation = ref<'create' | 'update' | 'delete'>('update')
const loading = ref(false)
const search = ref('')

const filteredSchoolYears = computed(() => {
  const searchTerm = search.value.trim().toLowerCase()
  const sortedSchoolYears = [...schoolYears.value].sort((a, b) =>
    b.description.localeCompare(a.description)
  )

  if (!searchTerm) {
    return sortedSchoolYears
  }

  return sortedSchoolYears.filter((schoolYear) =>
    schoolYear.description.toLowerCase().includes(searchTerm)
  )
})

async function fetchSchoolYears() {
  loading.value = true

  try {
    const response = await useGetFetch<SchoolYear[]>('api/school-years')

    if (Array.isArray(response)) {
      schoolYears.value = response
    }
  } finally {
    loading.value = false
  }
}

function handleFormSuccess() {
  fetchSchoolYears()
}

function editSchoolYear(schoolYear: SchoolYear) {
  selectedSchoolYear.value = { ...schoolYear }
  schoolYearFormOperation.value = 'update'
  showSchoolYearForm.value = true
}

function deleteSchoolYear(schoolYear: SchoolYear) {
  selectedSchoolYear.value = { ...schoolYear }
  schoolYearFormOperation.value = 'delete'
  showSchoolYearForm.value = true
}

function addSchoolYear() {
  selectedSchoolYear.value = { ...SCHOOL_YEAR }
  schoolYearFormOperation.value = 'create'
  showSchoolYearForm.value = true
}

function formatDate(value?: string) {
  if (!value) {
    return 'Not available'
  }

  return dayjs(value).format('MMM DD, YYYY')
}

onMounted(() => {
  if (visible.value) {
    fetchSchoolYears()
  }
})

watch(visible, (val) => {
  if (val) {
    fetchSchoolYears()
  }
})
</script>
