<template>
  <section
    aria-labelledby="recent-activity-title"
    class="w-full bg-slate-50 px-6 py-12 dark:bg-surface-950 md:px-8 lg:px-10"
  >
    <div class="mx-auto max-w-7xl">
      <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div class="max-w-2xl">
          <p class="text-xs font-semibold uppercase text-blue-700 dark:text-blue-300">
            Campus Updates
          </p>
          <h2
            id="recent-activity-title"
            class="mt-1 text-2xl font-black leading-tight text-slate-950 dark:text-white sm:text-3xl"
          >
            Latest News, Events, and Highlights
          </h2>
        </div>

        <div class="flex flex-wrap gap-2">
          <NuxtLink
            v-if="isAuthenticated"
            to="/school-updates"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:hover:border-blue-300/40 dark:hover:bg-blue-400/10"
          >
            <Icon name="solar:document-text-linear" class="size-4" aria-hidden="true" />
            Manage news
          </NuxtLink>
          <NuxtLink
            v-if="isAuthenticated"
            to="/school-calendar"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-bold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:hover:border-blue-300/40 dark:hover:bg-blue-400/10"
          >
            <Icon name="solar:calendar-add-linear" class="size-4" aria-hidden="true" />
            Manage events
          </NuxtLink>
        </div>
      </div>

      <div
        v-if="pending"
        class="grid gap-4 md:grid-cols-2 xl:grid-cols-4"
        aria-label="Loading recent school activity"
      >
        <div
          v-for="item in 4"
          :key="item"
          class="h-72 animate-pulse rounded-xl border border-slate-200 bg-white dark:border-white/10 dark:bg-white/5"
        />
      </div>

      <div
        v-else-if="recentItems.length"
        class="grid gap-4 md:grid-cols-2 xl:grid-cols-4"
      >
        <article
          v-for="item in recentItems"
          :key="`${item.kind}-${item.id}`"
          class="group overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm transition hover:border-blue-200 hover:shadow-lg hover:shadow-slate-200/70 dark:border-white/10 dark:bg-white/5 dark:hover:border-blue-300/40 dark:hover:shadow-slate-950/40"
        >
          <NuxtLink
            :to="item.path"
            class="block h-full focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-blue-700"
            :aria-label="`Open ${item.title} in ${item.category}`"
          >
            <div class="relative h-36 overflow-hidden bg-slate-200 dark:bg-slate-800">
              <img
                :src="item.image"
                :alt="item.imageAlt"
                class="size-full object-cover transition duration-500 group-hover:scale-105"
                loading="lazy"
                @error="replaceWithFallbackImage"
              >
              <span class="absolute left-3 top-3 inline-flex items-center gap-1.5 rounded-full bg-white/95 px-2.5 py-1 text-[0.7rem] font-black uppercase text-blue-800 shadow-sm ring-1 ring-slate-200">
                <Icon :name="item.icon" class="size-3.5" aria-hidden="true" />
                {{ item.category }}
              </span>
            </div>

            <div class="flex min-h-40 flex-col p-4">
              <time
                :datetime="item.date"
                class="text-xs font-bold uppercase text-slate-500 dark:text-slate-400"
              >
                {{ item.formattedDate }}
              </time>
              <h3 class="mt-2 line-clamp-2 text-base font-black leading-6 text-slate-950 dark:text-white">
                {{ item.title }}
              </h3>
              <p class="mt-2 line-clamp-3 text-sm leading-6 text-slate-600 dark:text-slate-300">
                {{ item.excerpt }}
              </p>
              <span class="mt-auto inline-flex items-center gap-1 pt-4 text-sm font-bold text-blue-700 dark:text-blue-300">
                Read more
                <Icon name="solar:round-arrow-right-linear" class="size-4 transition group-hover:translate-x-0.5" aria-hidden="true" />
              </span>
            </div>
          </NuxtLink>
        </article>
      </div>

      <div
        v-else
        class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center dark:border-white/15 dark:bg-white/5"
      >
        <Icon name="solar:inbox-linear" class="mx-auto size-10 text-slate-400" aria-hidden="true" />
        <h3 class="mt-4 text-lg font-black text-slate-950 dark:text-white">
          No recent updates available
        </h3>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
type SchoolUpdateResponse = {
  data?: HomeSchoolUpdate[]
}

type HomeSchoolUpdate = SchoolUpdate & {
  created_at?: string
}

type RecentActivityItem = {
  id: number | string
  kind: 'news' | 'event' | 'gallery'
  title: string
  excerpt: string
  date: string
  sortDate: number
  formattedDate: string
  category: string
  icon: string
  image: string
  imageAlt: string
  path: string
}

const dayjs = useDayjs()
const config = useRuntimeConfig()
const schoolYearStore = useSchoolYearStore()
const { isAuthenticated } = useSanctumAuth()

const fallbackImage = '/images/school_calendar.webp'
const galleryFallbackImage = '/images/no-image.svg'

const { data: recentItems, pending } = await useAsyncData<RecentActivityItem[]>(
  'home-recent-activity',
  async () => {
    const schoolYears = await safeGet<SchoolYear[]>('api/school-years', [])
    const schoolYearId = activeSchoolYearId(schoolYears)

    const [updates, events, galleries] = await Promise.all([
      safeGet<SchoolUpdateResponse>('api/school-updates?per_page=4&page=1', {}),
      safeGet<SchoolCalendar[]>(
        schoolYearId
          ? `api/school-calendars?schoolYearId=${schoolYearId}&upcoming=true`
          : 'api/school-calendars?upcoming=true',
        []
      ),
      safeGet<Gallery[]>('api/galleries', []),
    ])

    const liveItems = [
      ...normalizeUpdates(updates),
      ...normalizeEvents(events),
      ...normalizeGalleries(galleries),
    ]
      .sort((a, b) => b.sortDate - a.sortDate)
      .slice(0, 5)

    return liveItems.length ? liveItems : previewItems()
  },
  {
    default: () => [],
    deep: false,
    dedupe: 'defer',
    getCachedData(key, nuxtApp) {
      return nuxtApp.payload.data[key] || nuxtApp.static.data[key]
    },
  }
)

function activeSchoolYearId(schoolYears: SchoolYear[]) {
  if (schoolYearStore.selectedSchoolYear?.id) {
    return schoolYearStore.selectedSchoolYear.id
  }

  return [...schoolYears].reverse()[0]?.id
}

async function safeGet<T>(endpoint: string, fallback: T) {
  try {
    return await $fetch<T>(apiUrl(endpoint))
  } catch {
    return fallback
  }
}

function normalizeUpdates(response: SchoolUpdateResponse): RecentActivityItem[] {
  return (response.data ?? []).map(update => {
    const date = update.published_at || update.created_at || update.event_start_at || ''
    const category = update.category || updateTypeLabel(update.type)

    return {
      id: update.id,
      kind: 'news',
      title: update.title,
      excerpt: excerpt(update.summary || update.content),
      date,
      sortDate: sortableDate(date),
      formattedDate: formatDate(date),
      category,
      icon: updateTypeIcon(update.type),
      image: schoolUpdateImage(update),
      imageAlt: `${update.title} thumbnail`,
      path: '/school-updates',
    }
  })
}

function normalizeEvents(events: SchoolCalendar[]): RecentActivityItem[] {
  return events.map(event => ({
    id: event.id,
    kind: 'event',
    title: event.title,
    excerpt: excerpt(event.description),
    date: event.start,
    sortDate: sortableDate(event.start),
    formattedDate: formatDate(event.start),
    category: 'Event',
    icon: 'solar:calendar-date-linear',
    image: calendarImage(event),
    imageAlt: `${event.title} event image`,
    path: '/school-calendar',
  }))
}

function normalizeGalleries(galleries: Gallery[]): RecentActivityItem[] {
  return galleries
    .filter(gallery => gallery.image_count > 0)
    .map(gallery => ({
      id: gallery.id,
      kind: 'gallery',
      title: gallery.title,
      excerpt: excerpt(gallery.description || 'View moments from this school activity.'),
      date: gallery.start,
      sortDate: sortableDate(gallery.start),
      formattedDate: formatDate(gallery.start),
      category: 'Gallery',
      icon: 'solar:gallery-linear',
      image: galleryCoverImage(gallery),
      imageAlt: `${gallery.title} gallery cover`,
      path: '/galleries',
    }))
}

function previewItems(): RecentActivityItem[] {
  const today = dayjs()

  return [
    {
      id: 'preview-news',
      kind: 'news',
      title: 'Enrollment Advisory for the New School Year',
      excerpt: 'Families may review enrollment reminders, requirements, and office schedules for the coming term.',
      date: today.format('YYYY-MM-DD'),
      sortDate: today.valueOf(),
      formattedDate: today.format('MMM DD, YYYY'),
      category: 'News',
      icon: 'solar:document-text-linear',
      image: '/images/banner2.webp',
      imageAlt: 'School campus preview image',
      path: '/school-updates',
    },
    {
      id: 'preview-event',
      kind: 'event',
      title: 'Student Recognition and Moving-Up Programs',
      excerpt: 'Upcoming ceremonies and school milestones will appear here for quick access by families.',
      date: today.add(7, 'day').format('YYYY-MM-DD'),
      sortDate: today.add(7, 'day').valueOf(),
      formattedDate: today.add(7, 'day').format('MMM DD, YYYY'),
      category: 'Event',
      icon: 'solar:calendar-date-linear',
      image: '/images/school_calendar.webp',
      imageAlt: 'School event preview image',
      path: '/school-calendar',
    },
    {
      id: 'preview-gallery',
      kind: 'gallery',
      title: 'Campus Life Photo Highlights',
      excerpt: 'Gallery highlights from academic activities, celebrations, and student programs will appear here.',
      date: today.subtract(3, 'day').format('YYYY-MM-DD'),
      sortDate: today.subtract(3, 'day').valueOf(),
      formattedDate: today.subtract(3, 'day').format('MMM DD, YYYY'),
      category: 'Gallery',
      icon: 'solar:gallery-linear',
      image: '/images/banner3.webp',
      imageAlt: 'Campus life gallery preview image',
      path: '/galleries',
    },
  ]
}

function schoolUpdateImage(update: SchoolUpdate) {
  if (update.featured_image_url) {
    return mediaUrl(update.featured_image_url)
  }

  if (update.featured_image) {
    return mediaUrl(`/storage/${update.featured_image}`)
  }

  return fallbackImage
}

function calendarImage(event: SchoolCalendar) {
  const image = event.image?.trim()

  if (!image) {
    return fallbackImage
  }

  if (image.startsWith('http') || image.startsWith('/images/')) {
    return image
  }

  return mediaUrl(image)
}

function galleryCoverImage(gallery: Gallery) {
  const imageIndex = (Number(gallery.id) % gallery.image_count) + 1

  return mediaUrl(`/storage/galleries/${gallery.id}/${imageIndex}.webp`)
}

function updateTypeLabel(type: string) {
  const labels: Record<string, string> = {
    news: 'News',
    announcement: 'Announcement',
    blog: 'Story',
    event: 'Event',
  }

  return labels[type] ?? 'Update'
}

function updateTypeIcon(type: string) {
  const icons: Record<string, string> = {
    news: 'solar:document-text-linear',
    announcement: 'solar:bell-bing-linear',
    blog: 'solar:book-bookmark-linear',
    event: 'solar:calendar-date-linear',
  }

  return icons[type] ?? 'solar:document-text-linear'
}

function excerpt(value = '') {
  const text = stripHtml(value).replace(/\s+/g, ' ').trim()

  if (!text) {
    return 'Read the latest update from Philippine Yuh Chiau School.'
  }

  if (text.length <= 100) {
    return text
  }

  return `${text.slice(0, 97).trim()}...`
}

function stripHtml(value = '') {
  return value
    .replace(/<img[^>]*>/gi, ' ')
    .replace(/<script[^>]*>[\s\S]*?<\/script>/gi, ' ')
    .replace(/<style[^>]*>[\s\S]*?<\/style>/gi, ' ')
    .replace(/<br\s*\/?>/gi, ' ')
    .replace(/<\/p>/gi, ' ')
    .replace(/<[^>]*>/g, ' ')
    .replace(/&nbsp;/gi, ' ')
    .replace(/&amp;/gi, '&')
}

function formatDate(value: string) {
  return value && dayjs(value).isValid() ? dayjs(value).format('MMM DD, YYYY') : 'Date pending'
}

function sortableDate(value: string) {
  return value && dayjs(value).isValid() ? dayjs(value).valueOf() : 0
}

function replaceWithFallbackImage(event: Event) {
  const image = event.target as HTMLImageElement
  image.src = galleryFallbackImage
}

function apiUrl(endpoint: string) {
  return cleanDuplicateSlashes(`${config.public.backendBase}/${endpoint}`)
}

function mediaUrl(endpoint: string) {
  if (endpoint.startsWith('http') || endpoint.startsWith('/images/')) {
    return endpoint
  }

  return cleanDuplicateSlashes(`${config.public.backendBase}/${endpoint}`)
}

function cleanDuplicateSlashes(urlString: string) {
  return urlString.replace(/([^:]\/)\/+/g, '$1')
}
</script>
