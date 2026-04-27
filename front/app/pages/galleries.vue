<template>
  <main class="bg-slate-50 text-slate-950 dark:bg-slate-950 dark:text-white">
    <FormGallery
      :key="`${galleryFormOperation}-${selectedGallery.id}-${selectedGallery.start}`"
      v-model:visible="showGalleryForm"
      v-model:gallery="selectedGallery"
      v-model:operation="galleryFormOperation"
      @onFormSuccess="() => galleriesInit++"
    />
    <FullGallery v-model:visible="showFullGallery" :gallery="galleryData" />

    <section
      class="relative isolate overflow-hidden bg-cover bg-center"
      style="background-image: url('/images/banner3.webp')"
    >
      <div class="absolute inset-0 bg-slate-950/72" />
      <div
        class="absolute inset-0 bg-[linear-gradient(90deg,rgba(15,23,42,0.95)_0%,rgba(30,64,175,0.7)_50%,rgba(15,23,42,0.82)_100%)]"
      />

      <div class="relative mx-auto max-w-7xl px-5 pb-14 pt-28 sm:px-8 sm:pt-24 lg:px-10 lg:pb-20 lg:pt-28">
        <div class="grid items-end gap-8 lg:grid-cols-[minmax(0,1fr)_360px]">
          <div class="max-w-3xl">
            <div
              class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-100"
            >
              <Icon name="solar:gallery-linear" class="size-4" />
              Campus Life Gallery
            </div>
            <h1 class="text-4xl font-black leading-tight text-white sm:text-5xl lg:text-6xl">
              Moments From School Life
            </h1>
            <p class="mt-5 max-w-2xl text-base leading-7 text-blue-50 sm:text-lg">
              Browse highlights from academic activities, celebrations, student programs, and community events at Philippine Yuh Chiau School.
            </p>
          </div>

          <div class="rounded-2xl border border-white/15 bg-white/10 p-5 text-white shadow-2xl shadow-slate-950/30 backdrop-blur">
            <div class="flex items-center gap-3">
              <div class="flex size-11 items-center justify-center rounded-xl bg-white text-blue-900">
                <Icon name="solar:camera-linear" class="size-6" />
              </div>
              <div>
                <p class="text-xs font-semibold uppercase text-blue-100">Gallery Archive</p>
                <p class="text-lg font-bold">{{ displayedGalleries.length }} Collections</p>
              </div>
            </div>
            <p class="mt-4 text-sm leading-6 text-blue-50">
              Open a collection to view the full photo set. New galleries appear here after publication.
            </p>
            <Button
              v-if="isAuthenticated"
              icon="pi pi-plus"
              label="Add Gallery"
              class="mt-5 w-full"
              severity="secondary"
              @click="addThisGallery()"
            />
          </div>
        </div>
      </div>
    </section>

    <section class="mx-auto max-w-7xl px-5 py-8 sm:px-8 lg:px-10 lg:py-10">
      <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <p class="text-xs font-semibold uppercase text-blue-700 dark:text-blue-300">
            Photo Collections
          </p>
          <h2 class="text-2xl font-black text-slate-950 dark:text-white">
            Latest Galleries
          </h2>
        </div>
        <div class="flex flex-wrap gap-2 text-sm text-slate-600 dark:text-slate-300">
          <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 dark:border-white/10 dark:bg-white/5">
            <Icon name="solar:folder-open-linear" class="size-4 text-blue-700 dark:text-blue-300" />
            {{ displayedGalleries.length }} collections
          </span>
          <span class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-1.5 dark:border-white/10 dark:bg-white/5">
            <Icon name="solar:gallery-linear" class="size-4 text-blue-700 dark:text-blue-300" />
            {{ totalImages }} photos
          </span>
        </div>
      </div>

      <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
        <div class="mb-3 flex items-center gap-2">
          <Icon name="solar:square-academic-cap-linear" class="size-5 text-blue-700 dark:text-blue-300" />
          <h3 class="text-sm font-bold uppercase text-slate-700 dark:text-slate-200">
            Academic Year
          </h3>
        </div>
        <div class="flex gap-2 overflow-x-auto pb-1">
          <button
            v-for="schoolYear in schoolYears"
            :key="schoolYear.id"
            type="button"
            :class="[
              'shrink-0 rounded-lg border px-4 py-2 text-sm font-semibold transition',
              schoolYear.id === schoolYearStore.selectedSchoolYear?.id
                ? 'border-blue-700 bg-blue-700 text-white shadow-sm dark:border-blue-400 dark:bg-blue-500'
                : 'border-slate-200 bg-slate-50 text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-900 dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:hover:border-blue-300/40 dark:hover:bg-blue-400/10',
            ]"
            @click="schoolYearStore.selectedSchoolYear = schoolYear"
          >
            {{ schoolYear.description }}
          </button>
        </div>
      </div>

      <div
        v-if="displayedGalleries.length"
        class="grid grid-cols-1 gap-5 md:grid-cols-2 xl:grid-cols-3"
      >
        <article
          v-for="gallery in displayedGalleries"
          :key="gallery.id"
          class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-xl hover:shadow-slate-200/70 dark:border-white/10 dark:bg-white/5 dark:hover:border-blue-300/40 dark:hover:shadow-slate-950/50"
        >
          <button
            type="button"
            class="relative block h-64 w-full overflow-hidden text-left"
            @click="openFullGallery(gallery)"
          >
            <img
              :src="getGalleryCoverImage(gallery)"
              :alt="gallery.title"
              class="size-full object-cover transition duration-500 group-hover:scale-105"
              @error="replaceWithFallbackImage"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/10 to-transparent" />
            <div class="absolute left-4 top-4 rounded-xl bg-white/95 px-3 py-2 text-center shadow-sm">
              <p class="text-xs font-bold uppercase text-blue-700">
                {{ $dayjs(gallery.start).format('MMM') }}
              </p>
              <p class="text-2xl font-black leading-none text-slate-950">
                {{ $dayjs(gallery.start).format('DD') }}
              </p>
              <p class="text-xs font-semibold text-slate-500">
                {{ $dayjs(gallery.start).format('YYYY') }}
              </p>
            </div>
            <div class="absolute bottom-4 left-4 right-4">
              <span class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold text-white ring-1 ring-white/20 backdrop-blur">
                <Icon name="solar:gallery-linear" class="size-3.5" />
                {{ gallery.image_count }} photos
              </span>
            </div>
          </button>

          <div class="space-y-4 p-5">
            <div>
              <h3 class="line-clamp-2 text-xl font-black leading-6 text-slate-950 dark:text-white">
                {{ gallery.title }}
              </h3>
              <p class="mt-2 text-sm font-semibold text-blue-700 dark:text-blue-300">
                {{ formatRange(gallery.start, gallery.end) }}
              </p>
              <AppSafeHtml
                v-if="gallery.description"
                class="mt-3 line-clamp-3 text-sm leading-6 text-slate-600 dark:text-slate-300"
                :html="gallery.description"
              />
            </div>

            <div class="flex items-center justify-between gap-3">
              <Button
                label="View Gallery"
                icon="pi pi-images"
                size="small"
                class="w-auto"
                @click="openFullGallery(gallery)"
              />
              <div v-if="isAuthenticated" class="flex gap-2">
                <Button
                  icon="pi pi-pencil"
                  rounded
                  text
                  aria-label="Edit gallery"
                  @click="editThisGallery(gallery)"
                />
                <Button
                  icon="pi pi-trash"
                  rounded
                  text
                  severity="danger"
                  aria-label="Delete gallery"
                  @click="deleteThisGallery(gallery)"
                />
              </div>
            </div>
          </div>
        </article>
      </div>

      <div
        v-else
        class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center shadow-sm dark:border-white/15 dark:bg-white/5"
      >
        <Icon name="solar:gallery-linear" class="mx-auto size-10 text-slate-400" />
        <h3 class="mt-4 text-xl font-black text-slate-950 dark:text-white">
          No galleries available for {{ selectedYearLabel }}
        </h3>
        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600 dark:text-slate-300">
          Published photo collections will appear here once they are available.
        </p>
      </div>
    </section>
  </main>
</template>

<script setup lang="ts">
import { GALLERY } from '~/constant/Gallery'

const { isAuthenticated } = useSanctumAuth()
const dayjs = useDayjs()
const schoolYearStore = useSchoolYearStore()

definePageMeta({
  layout: 'welcome',
})

const galleriesInit = ref<number>(0)
const showFullGallery = ref<boolean>(false)
const showGalleryForm = ref<boolean>(false)
const selectedGallery = ref<Gallery>(createGalleryDraft())
const galleryFormOperation = ref<'create' | 'update'>('create')
const galleryData = ref(createGalleryDraft())
const fallbackImage = '/images/no-image.svg'

const schoolYears = computedAsync<SchoolYear[]>(async () => {
  const response = await useGetFetch<SchoolYear[]>('api/school-years')

  if (Array.isArray(response)) {
    const sorted = [...response].sort((a, b) => {
      return getSchoolYearStartYear(b.description) - getSchoolYearStartYear(a.description)
    })

    if (
      sorted.length &&
      !sorted.some((schoolYear) => {
        return schoolYear.id === schoolYearStore.selectedSchoolYear?.id
      })
    ) {
      schoolYearStore.selectedSchoolYear = sorted[0]
    }

    return sorted as SchoolYear[]
  }

  return []
}, [])

const galleries = computedAsync<Gallery[]>(async () => {
  galleriesInit.value
  const response = await useGetFetch<Gallery[]>('api/galleries')

  if (Array.isArray(response)) {
    return response as Gallery[]
  }

  return []
}, [])

const totalImages = computed(() => {
  return displayedGalleries.value.reduce(
    (total, gallery) => total + gallery.image_count,
    0
  )
})

const selectedYearLabel = computed(() => {
  return schoolYearStore.selectedSchoolYear?.description || 'the selected year'
})

const displayedGalleries = computed(() => {
  const selectedRange = getSchoolYearRange(selectedYearLabel.value)

  if (!selectedRange) {
    return galleries.value
  }

  return galleries.value.filter((gallery) => {
    if (
      schoolYearStore.selectedSchoolYear?.id &&
      gallery.school_year_id != null
    ) {
      return gallery.school_year_id === schoolYearStore.selectedSchoolYear.id
    }

    const start = dayjs(gallery.start)

    return (
      start.isValid() &&
      (start.isSame(selectedRange.start) ||
        start.isSame(selectedRange.end) ||
        (start.isAfter(selectedRange.start) && start.isBefore(selectedRange.end)))
    )
  })
})

function getGalleryCoverImage(gallery: Gallery) {
  if (!gallery.image_count) {
    return fallbackImage
  }

  const imageIndex = (gallery.id % gallery.image_count) + 1
  return useResolvedImage(`/storage/galleries/${gallery.id}/${imageIndex}.webp`)
}

function replaceWithFallbackImage(event: Event) {
  const image = event.target as HTMLImageElement
  image.src = fallbackImage
}

function getSchoolYearRange(description: string) {
  const match = description.match(/(\d{4}).*?(\d{4})/)

  if (!match) {
    return null
  }

  return {
    start: dayjs(`${match[1]}-06-01`).startOf('day'),
    end: dayjs(`${match[2]}-05-31`).endOf('day'),
  }
}

function getSchoolYearStartYear(description: string) {
  const match = description.match(/(\d{4})/)

  return match ? Number(match[1]) : 0
}

function createGalleryDraft(): Gallery {
  const selectedSchoolYear = schoolYearStore.selectedSchoolYear
  const selectedRange = getSchoolYearRange(selectedSchoolYear?.description || '')
  const today = dayjs()
  const draftDate =
    selectedRange &&
    today.isValid() &&
    (today.isSame(selectedRange.start) ||
      today.isSame(selectedRange.end) ||
      (today.isAfter(selectedRange.start) && today.isBefore(selectedRange.end)))
      ? today
      : selectedRange?.start || today

  return {
    ...GALLERY,
    id: randomIntRange(1, 9999999999),
    school_year_id: selectedSchoolYear?.id || null,
    start: draftDate.startOf('day').format('YYYY-MM-DD HH:mm:ss'),
    end: draftDate.endOf('day').format('YYYY-MM-DD HH:mm:ss'),
  }
}

function openFullGallery(gallery: Gallery) {
  galleryData.value = gallery
  showFullGallery.value = true
}

function editThisGallery(gallery: Gallery) {
  galleryFormOperation.value = 'update'
  selectedGallery.value = gallery
  showGalleryForm.value = true
}

async function deleteThisGallery(gallery: Gallery) {
  const confirmation = window.confirm(
    'Are you sure you want to delete this gallery?'
  )

  if (confirmation) {
    const { status } = await useSanctumPost(`/api/galleries`, 'delete', gallery)

    if (status.value == 'success') {
      galleriesInit.value++
    }
  }
}

function addThisGallery() {
  galleryFormOperation.value = 'create'
  selectedGallery.value = createGalleryDraft()
  showGalleryForm.value = true
}
</script>
