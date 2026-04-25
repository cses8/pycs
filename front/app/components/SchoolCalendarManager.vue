<template>
  <section class="min-w-0 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-6">
    <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <p class="text-xs font-semibold uppercase text-blue-700 dark:text-blue-300">
          Calendar Management
        </p>
        <h2 class="text-2xl font-black text-slate-950 dark:text-white">
          Manage Events
        </h2>
      </div>
      <Button label="Add event" icon="pi pi-plus" @click="openCreateForm" />
    </div>

    <div class="mb-4 grid gap-3 md:grid-cols-[minmax(0,1fr)_14rem]">
      <span class="relative">
        <i class="pi pi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
        <InputText
          v-model="search"
          placeholder="Search calendar events"
          class="w-full !rounded-lg !pl-10"
        />
      </span>
      <Button
        label="Refresh"
        icon="pi pi-refresh"
        severity="secondary"
        outlined
        :loading="loading"
        @click="fetchCalendarEvents"
      />
    </div>

    <div class="-mx-2 block max-w-[calc(100%+1rem)] overflow-x-auto px-2 pb-1">
      <DataTable
        :value="filteredCalendarEvents"
        :loading="loading"
        data-key="id"
        striped-rows
        paginator
        :rows="8"
        :rows-per-page-options="[8, 12, 20, 50]"
        paginator-template="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown CurrentPageReport"
        current-page-report-template="{first}-{last} of {totalRecords} events"
        table-style="min-width: 72rem"
        class="w-full rounded-lg border border-slate-200 bg-white shadow-sm dark:border-white/10"
      >
        <Column header="Event" class="min-w-[28rem]">
          <template #body="{ data }">
            <div class="flex items-center gap-4">
              <img
                :src="calendarImage(data)"
                :alt="data.title"
                class="h-16 w-24 rounded-lg object-cover ring-1 ring-slate-200 dark:ring-white/10"
                @error="replaceWithFallbackImage"
              >
              <div class="min-w-0">
                <p class="truncate text-sm font-bold text-slate-950 dark:text-white">
                  {{ data.title }}
                </p>
                <p class="mt-1 line-clamp-2 max-w-2xl text-sm leading-5 text-slate-600 dark:text-slate-300">
                  {{ stripHtml(data.description) || 'No description' }}
                </p>
              </div>
            </div>
          </template>
        </Column>

        <Column header="Schedule" class="min-w-[16rem]">
          <template #body="{ data }">
            <div class="space-y-1 text-sm">
              <div class="flex items-center gap-2 text-slate-700 dark:text-slate-200">
                <Icon name="lucide:calendar-days" class="size-4 text-blue-700 dark:text-blue-300" />
                <span>{{ formatDate(data.start) }}</span>
              </div>
              <div class="flex items-center gap-2 text-slate-500 dark:text-slate-400">
                <Icon name="lucide:flag" class="size-4" />
                <span>{{ formatDate(data.end) }}</span>
              </div>
            </div>
          </template>
        </Column>

        <Column header="School Year" class="min-w-[14rem]">
          <template #body="{ data }">
            <Tag :value="schoolYearLabel(data.school_year_id)" severity="info" class="!rounded-full" />
          </template>
        </Column>

        <Column header="Actions" class="w-[10rem]">
          <template #body="{ data }">
            <div class="flex items-center gap-1">
              <Button icon="pi pi-pencil" rounded text aria-label="Edit calendar event" @click="openEditForm(data)" />
              <Button icon="pi pi-trash" rounded text severity="danger" aria-label="Delete calendar event" @click="deleteCalendarEvent(data)" />
            </div>
          </template>
        </Column>

        <template #empty>
          <div class="px-4 py-12 text-center">
            <Icon name="lucide:calendar-x" class="mx-auto size-10 text-slate-400" />
            <h3 class="mt-4 text-lg font-bold text-slate-950 dark:text-white">
              No calendar events found
            </h3>
          </div>
        </template>
      </DataTable>
    </div>

    <Dialog
      v-model:visible="showForm"
      modal
      :close-on-escape="false"
      :draggable="false"
      :style="{ width: 'min(96vw, 54rem)' }"
      pt:root:class="!border-0 !bg-transparent !shadow-none"
      pt:mask:class="backdrop-blur-sm !bg-slate-950/45"
      aria-labelledby="calendar-event-form-title"
    >
      <template #container="{ closeCallback }">
        <section
          class="pycs-modal-shell rounded-xl border border-slate-200 bg-white shadow-2xl shadow-slate-950/20 dark:border-surface-700 dark:bg-surface-950"
        >
          <header
            class="pycs-modal-header flex items-start justify-between gap-4 border-b border-slate-200 bg-slate-950 px-6 py-5 text-white dark:border-surface-800"
          >
            <div class="flex min-w-0 gap-4">
              <div
                class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-white text-blue-800"
              >
                <Icon :name="form.id ? 'lucide:pencil' : 'lucide:calendar-plus'" class="size-6" />
              </div>
              <div class="min-w-0">
                <p class="text-xs font-semibold uppercase text-blue-200">
                  School Calendar
                </p>
                <h2
                  id="calendar-event-form-title"
                  class="mt-1 truncate text-xl font-bold leading-tight text-white sm:text-2xl"
                >
                  {{ form.id ? 'Edit calendar event' : 'Create calendar event' }}
                </h2>
                <p class="mt-2 text-sm leading-6 text-slate-300">
                  Set the school year, date range, image, and event details shown on the public calendar.
                </p>
              </div>
            </div>

            <Button
              icon="pi pi-times"
              rounded
              text
              severity="secondary"
              aria-label="Close calendar event form"
              class="!-mr-2 !-mt-2 !h-10 !w-10 !text-slate-200 hover:!bg-white/10 hover:!text-white"
              @click="closeCallback"
            />
          </header>

          <div class="pycs-modal-body bg-slate-50 p-5 dark:bg-surface-900">
            <div
              class="mb-4 inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-bold uppercase"
              :class="
                form.id
                  ? 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-300'
                  : 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300'
              "
            >
              <span
                class="size-2 rounded-full"
                :class="form.id ? 'bg-amber-500' : 'bg-emerald-500'"
              />
              {{ form.id ? 'Update' : 'New' }}
            </div>

            <form @submit.prevent="saveCalendarEvent">
              <div
                class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-surface-700 dark:bg-surface-950"
              >
                <div class="grid grid-cols-12 gap-4">
                  <div class="col-span-12 flex flex-col gap-2">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="calendar-title"
                    >
                      Title
                    </label>
                    <InputText
                      id="calendar-title"
                      v-model="form.title"
                      class="w-full !rounded-lg !border-slate-300 !py-3 dark:!border-surface-700 dark:!bg-surface-900"
                      :invalid="Boolean(formErrors.title)"
                    />
                    <Message v-if="formErrors.title" severity="error" size="small" variant="simple">
                      {{ formErrors.title }}
                    </Message>
                  </div>

                  <div class="col-span-12 flex flex-col gap-2 md:col-span-6">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="calendar-start"
                    >
                      Start date
                    </label>
                    <input
                      id="calendar-start"
                      v-model="form.start"
                      type="date"
                      class="w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:border-surface-700 dark:bg-surface-900 dark:text-white dark:focus:ring-blue-400/20"
                    >
                    <Message v-if="formErrors.start" severity="error" size="small" variant="simple">
                      {{ formErrors.start }}
                    </Message>
                  </div>

                  <div class="col-span-12 flex flex-col gap-2 md:col-span-6">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="calendar-end"
                    >
                      End date
                    </label>
                    <input
                      id="calendar-end"
                      v-model="form.end"
                      type="date"
                      class="w-full rounded-lg border border-slate-300 bg-white px-3 py-3 text-sm text-slate-950 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100 dark:border-surface-700 dark:bg-surface-900 dark:text-white dark:focus:ring-blue-400/20"
                    >
                    <Message v-if="formErrors.end" severity="error" size="small" variant="simple">
                      {{ formErrors.end }}
                    </Message>
                  </div>

                  <div class="col-span-12 flex flex-col gap-2 md:col-span-6">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="calendar-school-year"
                    >
                      School year
                    </label>
                    <Select
                      id="calendar-school-year"
                      v-model="form.school_year_id"
                      :options="schoolYearOptions"
                      option-label="label"
                      option-value="value"
                      class="w-full"
                    />
                  </div>

                  <div class="col-span-12 flex flex-col gap-2 md:col-span-6">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="calendar-image"
                    >
                      Image path or URL
                    </label>
                    <InputText
                      id="calendar-image"
                      v-model="form.image"
                      class="w-full !rounded-lg !border-slate-300 !py-3 dark:!border-surface-700 dark:!bg-surface-900"
                      placeholder="/images/school_calendar.webp"
                    />
                  </div>

                  <div class="col-span-12 flex flex-col gap-2">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="calendar-description"
                    >
                      Description
                    </label>
                    <Editor
                      id="calendar-description"
                      v-model="form.description"
                      placeholder="Write the calendar event details with formatting."
                      editor-style="height: 300px"
                      :class="[
                        'pycs-rich-editor',
                        { 'pycs-rich-editor-invalid': formErrors.description },
                      ]"
                    >
                      <template #toolbar>
                        <AppRichEditorToolbar />
                      </template>
                    </Editor>
                    <Message v-if="formErrors.description" severity="error" size="small" variant="simple">
                      {{ formErrors.description }}
                    </Message>
                  </div>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-[1fr_auto]">
                  <Button
                    type="button"
                    label="Cancel"
                    icon="pi pi-times"
                    severity="secondary"
                    outlined
                    class="!rounded-lg !py-3"
                    @click="closeCallback"
                  />
                  <Button
                    type="submit"
                    :label="saving ? 'Saving' : 'Save event'"
                    icon="pi pi-save"
                    :loading="saving"
                    class="!rounded-lg !py-3"
                  />
                </div>
              </div>
            </form>
          </div>
        </section>
      </template>
    </Dialog>
  </section>
</template>

<script setup lang="ts">
type CalendarForm = {
  id: number
  school_year_id: number | null
  start: string
  end: string
  image: string
  title: string
  description: string
}

const emit = defineEmits<{
  changed: []
}>()

const dayjs = useDayjs()
const config = useRuntimeConfig()
const schoolYearStore = useSchoolYearStore()
const notificationStore = useNotificationStore()

const fallbackImage = '/images/school_calendar.webp'
const calendarEvents = ref<SchoolCalendar[]>([])
const schoolYears = ref<SchoolYear[]>([])
const loading = ref(false)
const saving = ref(false)
const showForm = ref(false)
const search = ref('')
const formErrors = ref<Record<string, string>>({})
const form = ref<CalendarForm>(blankForm())

const filteredCalendarEvents = computed(() => {
  const term = search.value.trim().toLowerCase()

  if (!term) {
    return calendarEvents.value
  }

  return calendarEvents.value.filter(event =>
    [event.title, event.description, schoolYearLabel(event.school_year_id)]
      .join(' ')
      .toLowerCase()
      .includes(term)
  )
})

const schoolYearOptions = computed(() => [
  { label: 'No school year', value: null },
  ...schoolYears.value.map(schoolYear => ({
    label: schoolYear.description,
    value: schoolYear.id,
  })),
])

watch(
  () => schoolYearStore.selectedSchoolYear.id,
  () => {
    form.value.school_year_id = normalizedSchoolYearId()
    fetchCalendarEvents()
  }
)

onMounted(async () => {
  await Promise.all([fetchSchoolYears(), fetchCalendarEvents()])
})

function blankForm(): CalendarForm {
  const today = dayjs().format('YYYY-MM-DD')

  return {
    id: 0,
    school_year_id: normalizedSchoolYearId(),
    start: today,
    end: today,
    image: fallbackImage,
    title: '',
    description: '',
  }
}

function normalizedSchoolYearId() {
  return Number.isFinite(Number(schoolYearStore.selectedSchoolYear?.id))
    ? Number(schoolYearStore.selectedSchoolYear.id)
    : null
}

async function fetchSchoolYears() {
  const response = await useGetFetch<SchoolYear[]>('api/school-years')

  if (Array.isArray(response)) {
    schoolYears.value = response
  }
}

async function fetchCalendarEvents() {
  loading.value = true

  try {
    const response = await authenticatedFetch<SchoolCalendar[]>('/api/school-calendars', {
      method: 'GET',
      query: {
        schoolYearId: schoolYearStore.selectedSchoolYear.id,
      },
    })

    calendarEvents.value = Array.isArray(response) ? response : []
  } catch (error) {
    notificationStore.error({
      summary: 'Error',
      detail: error instanceof Error ? error.message : 'Calendar events could not be loaded.',
      life: 5000,
    })
  } finally {
    loading.value = false
  }
}

function openCreateForm() {
  form.value = blankForm()
  formErrors.value = {}
  showForm.value = true
}

function openEditForm(calendarEvent: SchoolCalendar) {
  form.value = {
    id: calendarEvent.id,
    school_year_id: calendarEvent.school_year_id ? Number(calendarEvent.school_year_id) : null,
    start: dayjs(calendarEvent.start).format('YYYY-MM-DD'),
    end: dayjs(calendarEvent.end).format('YYYY-MM-DD'),
    image: calendarEvent.image || fallbackImage,
    title: calendarEvent.title,
    description: calendarEvent.description,
  }
  formErrors.value = {}
  showForm.value = true
}

async function saveCalendarEvent() {
  formErrors.value = validateForm()

  if (Object.keys(formErrors.value).length) {
    return
  }

  saving.value = true

  try {
    const response = await authenticatedFetch<{ schoolCalendar: SchoolCalendar }>(
      form.value.id ? `/api/school-calendars/${form.value.id}` : '/api/school-calendars',
      {
        method: form.value.id ? 'PUT' : 'POST',
        body: serializeForm(),
      }
    )

    if (!response.schoolCalendar) {
      throw new Error('The calendar event could not be saved.')
    }

    notificationStore.success({
      summary: 'Saved',
      detail: 'Calendar event saved successfully.',
      life: 5000,
    })
    showForm.value = false
    await fetchCalendarEvents()
    emit('changed')
  } catch (error) {
    formErrors.value = validationErrors(error)
    notificationStore.error({
      summary: 'Error',
      detail: error instanceof Error ? error.message : 'Calendar event could not be saved.',
      life: 5000,
    })
  } finally {
    saving.value = false
  }
}

async function deleteCalendarEvent(calendarEvent: SchoolCalendar) {
  const confirmed = window.confirm(`Delete "${calendarEvent.title}"?`)

  if (!confirmed) {
    return
  }

  try {
    await authenticatedFetch(`/api/school-calendars/${calendarEvent.id}`, {
      method: 'DELETE',
    })

    notificationStore.success({
      summary: 'Deleted',
      detail: 'Calendar event deleted successfully.',
      life: 5000,
    })
    await fetchCalendarEvents()
    emit('changed')
  } catch (error) {
    notificationStore.error({
      summary: 'Error',
      detail: error instanceof Error ? error.message : 'Calendar event could not be deleted.',
      life: 5000,
    })
  }
}

function validateForm() {
  const errors: Record<string, string> = {}

  if (!form.value.title.trim()) {
    errors.title = 'Title is required.'
  }

  if (!form.value.start) {
    errors.start = 'Start date is required.'
  }

  if (!form.value.end) {
    errors.end = 'End date is required.'
  }

  if (form.value.start && form.value.end && dayjs(form.value.end).isBefore(dayjs(form.value.start), 'day')) {
    errors.end = 'End date must be the same as or after start date.'
  }

  if (!stripHtml(form.value.description).trim()) {
    errors.description = 'Description is required.'
  }

  return errors
}

function serializeForm() {
  return {
    school_year_id: form.value.school_year_id,
    start: form.value.start,
    end: form.value.end,
    image: form.value.image.trim() || fallbackImage,
    title: form.value.title.trim(),
    description: form.value.description.trim(),
  }
}

function validationErrors(error: unknown) {
  const responseErrors = (error as { data?: { errors?: Record<string, string[]> } })?.data?.errors

  if (!responseErrors) {
    return {}
  }

  return Object.fromEntries(
    Object.entries(responseErrors).map(([field, messages]) => [field, messages[0] ?? 'Invalid value.'])
  )
}

function calendarImage(calendarEvent: SchoolCalendar) {
  const image = calendarEvent.image?.trim()

  if (!image) {
    return fallbackImage
  }

  if (image.startsWith('http') || image.startsWith('/images/')) {
    return image
  }

  if (image.startsWith('/storage/')) {
    return resolveMediaUrl(image)
  }

  return image
}

function replaceWithFallbackImage(event: Event) {
  const image = event.target as HTMLImageElement
  image.src = fallbackImage
}

function schoolYearLabel(schoolYearId: number | string | null | undefined) {
  if (!schoolYearId) {
    return 'No school year'
  }

  return schoolYears.value.find(schoolYear => schoolYear.id === Number(schoolYearId))?.description ?? String(schoolYearId)
}

function formatDate(value: string) {
  return dayjs(value).format('MMM DD, YYYY')
}

function stripHtml(value: string) {
  return value?.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim()
}

type AuthenticatedFetchOptions = {
  method?: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
  query?: Record<string, unknown>
  body?: Record<string, unknown>
  headers?: Record<string, string>
}

async function authenticatedFetch<T>(
  endpoint: string,
  options: AuthenticatedFetchOptions = {}
) {
  const token = bearerToken()
  const csrfToken = getCookie('XSRF-TOKEN')

  return await $fetch<T>(resolveApiUrl(endpoint), {
    ...options,
    credentials: 'include',
    headers: {
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...(csrfToken ? { 'X-XSRF-TOKEN': csrfToken } : {}),
      ...(options.headers ?? {}),
    },
  })
}

function resolveApiUrl(endpoint: string) {
  return cleanDuplicateSlashes(`${config.public.backendBase}/${endpoint}`)
}

function resolveMediaUrl(endpoint: string) {
  return cleanDuplicateSlashes(`${config.public.backendBase}/${endpoint}`)
}

function cleanDuplicateSlashes(urlString: string) {
  return urlString.replace(/([^:]\/)\/+/g, '$1')
}

function getCookie(name: string) {
  if (typeof document === 'undefined') {
    return ''
  }

  const value = (
    document.cookie
      .split('; ')
      .find(cookie => cookie.startsWith(`${name}=`))
      ?.split('=')
      .slice(1)
      .join('=') ?? ''
  )

  return value ? decodeURIComponent(value) : ''
}
</script>
