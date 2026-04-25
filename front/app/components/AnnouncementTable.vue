<template>
  <Dialog
    v-model:visible="visible"
    modal
    :closeOnEscape="false"
    :draggable="false"
    class="!w-[min(96vw,88rem)]"
    pt:root:class="!border-0 !bg-transparent !shadow-none"
    pt:mask:class="backdrop-blur-sm !bg-slate-950/45"
  >
    <template #container="{ closeCallback }">
      <section
        class="flex max-h-[92vh] min-h-0 flex-col overflow-hidden rounded-xl border border-slate-200 bg-white shadow-2xl shadow-slate-950/20 dark:border-surface-700 dark:bg-surface-950"
      >
        <header
          class="flex shrink-0 flex-col gap-4 border-b border-slate-200 bg-slate-950 px-5 py-4 text-white sm:px-6 lg:flex-row lg:items-center lg:justify-between dark:border-surface-800"
        >
          <div class="flex min-w-0 items-start gap-4">
            <div
              class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-white text-blue-800"
            >
              <Icon name="lucide:megaphone" class="size-6" />
            </div>
            <div class="min-w-0">
              <p class="text-xs font-semibold uppercase text-blue-200">
                Content Management
              </p>
              <h2 class="mt-1 text-2xl font-bold leading-tight text-white">
                Announcements
              </h2>
              <p class="mt-1 max-w-2xl text-sm leading-6 text-slate-300">
                Manage publication dates, content, and the image used on the public announcement carousel.
              </p>
            </div>
          </div>

          <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
            <div
              class="rounded-lg border border-white/10 bg-white/10 px-4 py-2.5 text-sm"
            >
              <span class="block text-xs font-semibold uppercase text-blue-200">
                Total Records
              </span>
              <span class="text-xl font-bold text-white">{{ totalRecords }}</span>
            </div>
            <Button
              label="Add announcement"
              icon="pi pi-plus"
              class="!rounded-lg !border-blue-600 !bg-blue-600 !px-4 !py-2.5 hover:!border-blue-500 hover:!bg-blue-500"
              @click="addAnnouncement()"
            />
            <Button
              icon="pi pi-times"
              rounded
              text
              severity="secondary"
              aria-label="Close announcements"
              class="!h-10 !w-10 !text-slate-200 hover:!bg-white/10 hover:!text-white"
              @click="closeCallback"
            />
          </div>
        </header>

        <div class="flex min-h-0 flex-1 flex-col gap-3 bg-slate-50 p-4 sm:p-5 dark:bg-surface-900">
          <div
            class="flex shrink-0 flex-col gap-3 rounded-lg border border-slate-200 bg-white p-3 shadow-sm sm:flex-row sm:items-center sm:justify-between dark:border-surface-700 dark:bg-surface-950"
          >
            <div>
              <h3 class="text-base font-bold text-slate-950 dark:text-white">
                Announcement Library
              </h3>
              <p class="mt-1 text-sm text-slate-600 dark:text-surface-300">
                Showing {{ paginationLabel }}. Pages load from the server as you move through the table.
              </p>
            </div>

            <span class="relative w-full sm:w-80">
              <i
                class="pi pi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"
              />
              <InputText
                v-model="search"
                placeholder="Search title or description"
                class="w-full !rounded-lg !border-slate-300 !py-2.5 !pl-10 !pr-3 dark:!border-surface-700 dark:!bg-surface-900"
              />
            </span>
          </div>

          <DataTable
            :value="announcements"
            lazy
            paginator
            scrollable
            scrollHeight="flex"
            :rows="rows"
            :first="first"
            :totalRecords="totalRecords"
            :loading="loading"
            :rowsPerPageOptions="[5, 10, 20, 50]"
            dataKey="id"
            stripedRows
            tableStyle="min-width: 64rem"
            class="announcement-table min-h-0 flex-1 overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm dark:border-surface-700"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown CurrentPageReport"
            currentPageReportTemplate="{first} to {last} of {totalRecords}"
            @page="onPage"
          >
            <Column header="Announcement" class="min-w-[22rem]">
              <template #body="{ data }">
                <div class="flex items-center gap-4">
                  <img
                    :src="announcementImage(data)"
                    :alt="data.title"
                    class="h-10 w-14 shrink-0 rounded-md object-cover ring-1 ring-slate-200 dark:ring-surface-700"
                    @error="replaceWithFallbackImage"
                  />
                  <div class="min-w-0">
                    <p
                      class="truncate text-sm font-bold text-slate-950 dark:text-white"
                    >
                      {{ data.title }}
                    </p>
                    <p
                      class="mt-0.5 line-clamp-1 max-w-xl text-xs leading-5 text-slate-600 dark:text-surface-300"
                    >
                      {{ plainDescription(data.description) || 'No description' }}
                    </p>
                  </div>
                </div>
              </template>
            </Column>

            <Column header="Schedule" class="min-w-[17rem]">
              <template #body="{ data }">
                <div class="space-y-1 text-sm">
                  <div class="flex items-center gap-2 text-slate-700 dark:text-surface-200">
                    <Icon name="lucide:calendar-days" class="size-4 text-blue-700 dark:text-blue-300" />
                    <span>{{ formatDate(data.start) }}</span>
                  </div>
                  <div class="flex items-center gap-2 text-slate-500 dark:text-surface-400">
                    <Icon name="lucide:flag" class="size-4" />
                    <span>{{ formatDate(data.end) }}</span>
                  </div>
                </div>
              </template>
            </Column>

            <Column header="Status" class="min-w-[9rem]">
              <template #body="{ data }">
                <Tag
                  :value="announcementStatus(data).label"
                  :severity="announcementStatus(data).severity"
                  class="!rounded-full !px-3 !py-1"
                />
              </template>
            </Column>

            <Column header="Actions" class="w-[12rem]">
              <template #body="{ data }">
                <div class="flex items-center gap-1">
                  <Button
                    icon="pi pi-pencil"
                    rounded
                    text
                    aria-label="Edit announcement"
                    title="Edit"
                    @click="editAnnouncement(data)"
                  />
                  <Button
                    icon="pi pi-image"
                    rounded
                    text
                    severity="success"
                    aria-label="Upload announcement image"
                    title="Upload image"
                    @click="uploadAnnouncement(data)"
                  />
                  <Button
                    icon="pi pi-trash"
                    rounded
                    text
                    severity="danger"
                    aria-label="Delete announcement"
                    title="Delete"
                    @click="deleteAnnouncement(data)"
                  />
                </div>
              </template>
            </Column>

            <template #empty>
              <div class="px-4 py-12 text-center">
                <Icon
                  name="lucide:megaphone-off"
                  class="mx-auto size-10 text-slate-400"
                />
                <h3 class="mt-4 text-lg font-bold text-slate-950 dark:text-white">
                  No announcements found
                </h3>
                <p class="mt-2 text-sm text-slate-600 dark:text-surface-300">
                  Add a new announcement or adjust the search text.
                </p>
              </div>
            </template>
          </DataTable>
        </div>
      </section>
    </template>
  </Dialog>

  <LazyFormAnnouncement
    v-model:visible="showAnnouncementForm"
    v-model:operation="announcementFormOperation"
    v-model:announcement="selectedAnnouncement"
    @onFormSuccess="handleFormSuccess"
  />
  <LazyFormAnnouncementUpload
    v-model:visible="showAnnouncementUploadForm"
    v-model:announcement="selectedAnnouncement"
    @onFormSuccess="handleFormSuccess"
  />
</template>

<script setup lang="ts">
import { ANNOUNCEMENT } from '~/constant/Announcement'

type PaginationMeta = {
  curPage: number
  from: number | null
  to: number | null
  perPage: number
  lastPage: number
  total: number
}

type PaginatedAnnouncements = {
  data: Announcement[]
  pagination: PaginationMeta
}

const dayjs = useDayjs()
const visible = defineModel('visible', { default: false })

const announcements = ref<Announcement[]>([])
const selectedAnnouncement = ref<Announcement>(ANNOUNCEMENT)
const showAnnouncementForm = ref<boolean>(false)
const showAnnouncementUploadForm = ref<boolean>(false)
const announcementFormOperation = ref<'create' | 'update' | 'delete'>(
  'update'
)
const loading = ref(false)
const rows = ref(10)
const first = ref(0)
const totalRecords = ref(0)
const search = ref('')
const pagination = ref<PaginationMeta>({
  curPage: 1,
  from: null,
  to: null,
  perPage: 10,
  lastPage: 1,
  total: 0,
})

let searchDebounce: number | undefined

const paginationLabel = computed(() => {
  if (!totalRecords.value || pagination.value.from === null) {
    return '0 records'
  }

  return `${pagination.value.from}-${pagination.value.to} of ${totalRecords.value}`
})

async function fetchAnnouncement() {
  loading.value = true

  try {
    const page = Math.floor(first.value / rows.value) + 1
    const response = await useGetFetch<Announcement[] | PaginatedAnnouncements>(
      'api/announcements',
      {
        page,
        per_page: rows.value,
        search: search.value.trim(),
      }
    )

    if (Array.isArray(response)) {
      announcements.value = response
      totalRecords.value = response.length
      pagination.value = {
        curPage: 1,
        from: response.length ? 1 : null,
        to: response.length,
        perPage: response.length,
        lastPage: 1,
        total: response.length,
      }
      return
    }

    if (isPaginatedAnnouncements(response)) {
      announcements.value = response.data
      pagination.value = response.pagination
      totalRecords.value = response.pagination.total
    }
  } finally {
    loading.value = false
  }
}

function isPaginatedAnnouncements(
  response: unknown
): response is PaginatedAnnouncements {
  return (
    typeof response === 'object' &&
    response !== null &&
    Array.isArray((response as PaginatedAnnouncements).data) &&
    typeof (response as PaginatedAnnouncements).pagination?.total === 'number'
  )
}

function onPage(event: { first: number; rows: number }) {
  first.value = event.first
  rows.value = event.rows
  fetchAnnouncement()
}

function handleFormSuccess() {
  fetchAnnouncement()
}

function editAnnouncement(announcement: Announcement) {
  selectedAnnouncement.value = { ...announcement }
  announcementFormOperation.value = 'update'
  showAnnouncementForm.value = true
}

function deleteAnnouncement(announcement: Announcement) {
  selectedAnnouncement.value = { ...announcement }
  announcementFormOperation.value = 'delete'
  showAnnouncementForm.value = true
}

function addAnnouncement() {
  selectedAnnouncement.value = { ...ANNOUNCEMENT }
  announcementFormOperation.value = 'create'
  showAnnouncementForm.value = true
}

function uploadAnnouncement(announcement: Announcement) {
  selectedAnnouncement.value = { ...announcement }
  showAnnouncementUploadForm.value = true
}

function announcementImage(announcement: Announcement) {
  return apiUrl(`/storage/announcements/${announcement.id}/${announcement.id}.webp`)
}

function plainDescription(value: string) {
  return (value || '')
    .replace(/<br\s*\/?>/gi, ' ')
    .replace(/<\/p>/gi, ' ')
    .replace(/<[^>]*>/g, ' ')
    .replace(/&nbsp;/g, ' ')
    .replace(/&amp;/g, '&')
    .replace(/&lt;/g, '<')
    .replace(/&gt;/g, '>')
    .replace(/&#39;/g, "'")
    .replace(/&quot;/g, '"')
    .replace(/\s+/g, ' ')
    .trim()
}

function replaceWithFallbackImage(event: Event) {
  const image = event.target as HTMLImageElement
  image.src = '/images/no-image.svg'
}

function formatDate(value: string) {
  return dayjs(value).format('MMM DD, YYYY')
}

function announcementStatus(announcement: Announcement) {
  const now = dayjs()
  const start = dayjs(announcement.start)
  const end = dayjs(announcement.end)

  if (now.isBefore(start)) {
    return { label: 'Scheduled', severity: 'info' as const }
  }

  if (now.isAfter(end)) {
    return { label: 'Expired', severity: 'secondary' as const }
  }

  return { label: 'Active', severity: 'success' as const }
}

onMounted(() => {
  if (visible.value) {
    fetchAnnouncement()
  }
})

watch(visible, val => {
  if (val) {
    fetchAnnouncement()
  }
})

watch(search, () => {
  window.clearTimeout(searchDebounce)
  searchDebounce = window.setTimeout(() => {
    first.value = 0
    fetchAnnouncement()
  }, 300)
})
</script>

<style scoped>
:deep(.announcement-table .p-datatable-table-container) {
  min-height: 0;
}

:deep(.announcement-table .p-datatable-thead > tr > th) {
  padding: 0.55rem 0.75rem;
}

:deep(.announcement-table .p-datatable-tbody > tr > td) {
  padding: 0.45rem 0.75rem;
}

:deep(.announcement-table .p-paginator) {
  padding: 0.35rem 0.5rem;
}
</style>
