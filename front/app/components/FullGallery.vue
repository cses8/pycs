<template>
  <FormGalleryUpload
    v-model:visible="showFullGalleryForm"
    v-model:gallery="props.gallery"
    v-model:operation="fullGalleryFormOperation"
  />
  <Dialog
    v-model:visible="visible"
    modal
    :closable="false"
    :close-on-escape="true"
    :dismissable-mask="true"
    :draggable="false"
    class="!mx-4 !max-h-[calc(100vh-2rem)] !w-[min(1120px,calc(100vw-2rem))] !overflow-hidden !rounded-2xl !border-none"
    :pt="{
      root: { class: '!overflow-hidden !bg-slate-950 dark:!bg-slate-950' },
      header: { class: '!items-stretch !gap-0 !bg-slate-950 !p-0 !text-white' },
      content: { class: '!bg-slate-50 !p-0 dark:!bg-slate-950' },
    }"
  >
    <template #header>
      <div class="min-w-0 flex-1 bg-slate-950 px-5 py-5 text-white sm:px-6">
        <div class="flex items-start justify-between gap-4">
          <div class="min-w-0">
            <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase text-blue-100 ring-1 ring-white/15">
              <Icon name="solar:gallery-linear" class="size-4" />
              Gallery Collection
            </div>
            <h2 class="text-xl font-black leading-tight pr-2 sm:text-3xl">
              {{ gallery.title }}
            </h2>
            <div class="mt-3 flex flex-wrap gap-2 text-sm text-blue-50">
              <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 ring-1 ring-white/15">
                <Icon name="solar:calendar-date-linear" class="size-4" />
                {{ formatRange(gallery.start, gallery.end) }}
              </span>
              <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 ring-1 ring-white/15">
                <Icon name="solar:gallery-linear" class="size-4" />
                {{ imageCount }} photos
              </span>
            </div>
          </div>

          <div class="flex shrink-0 items-center gap-2">
            <Button
              v-if="isAuthenticated"
              icon="pi pi-plus"
              label="Add Image"
              size="small"
              severity="secondary"
              class="hidden sm:inline-flex"
              @click="showFullGalleryForm = true"
            />
            <button
              type="button"
              aria-label="Close gallery dialog"
              class="inline-flex size-10 items-center justify-center rounded-full border border-white/15 bg-white/10 text-white transition hover:border-white/25 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/30"
              @click="visible = false"
            >
              <Icon name="solar:close-circle-linear" class="size-5" />
            </button>
          </div>
        </div>

        <Button
          v-if="isAuthenticated"
          icon="pi pi-plus"
          label="Add Image"
          size="small"
          severity="secondary"
          class="mt-4 w-full sm:hidden"
          @click="showFullGalleryForm = true"
        />
      </div>
    </template>

    <div class="max-h-[calc(100vh-13rem)] overflow-y-auto bg-slate-50 dark:bg-slate-950">
      <section class="border-b border-slate-200 bg-white px-5 py-5 dark:border-white/10 dark:bg-white/5 sm:px-6">
<AppSafeHtml
v-if="gallery.description"
class="max-w-3xl text-sm leading-6 text-slate-600 dark:text-slate-300"
 :html="gallery.description"
/>
        <p v-else class="text-sm text-slate-500 dark:text-slate-400">
          No gallery description has been added yet.
        </p>
      </section>

      <section class="px-4 py-5 sm:px-6">
        <div
          v-if="groupItems.length"
          :class="`grid grid-cols-1 ${gridClassesAtLargerThanSm} gap-4`"
        >
          <div
            v-for="(groupItem, index) in groupItems"
            :key="index"
            class="grid gap-4"
          >
            <div
              v-for="(item, index2) in groupItem"
              :key="`${index}_${index2}`"
              class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5"
            >
              <Image
                :src="getGalleryImage(item)"
                preview
                image-class="h-full w-full object-cover"
                :pt="{
                  root: {
                    class: 'block h-full',
                  },
                  image: {
                    class: [
                      'w-full cursor-zoom-in object-cover transition duration-300 hover:scale-[1.02]',
                      {
                        'h-72 md:h-full': groupItem.length > 1,
                        'h-auto min-h-72': groupItem.length == 1,
                      },
                    ],
                  },
                  previewMask: {
                    class: '!rounded-none',
                  },
                }"
                @error="markGalleryImageAsFailed(item)"
                @contextmenu="onImageRightClick($event, item)"
              />
            </div>
          </div>
        </div>

        <div
          v-else
          class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center dark:border-white/15 dark:bg-white/5"
        >
          <Icon name="solar:gallery-remove-linear" class="mx-auto size-10 text-slate-400" />
          <h3 class="mt-4 text-lg font-black text-slate-950 dark:text-white">
            No photos yet
          </h3>
          <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600 dark:text-slate-300">
            Images uploaded to this gallery will appear here.
          </p>
        </div>
      </section>
    </div>

    <ContextMenu v-if="isAuthenticated" ref="galleryImgRef" :model="menuItem" />
  </Dialog>
</template>

<script setup lang="ts">
import { GALLERY } from '~/constant/Gallery'

const { isAuthenticated } = useSanctumAuth()
const fullGalleryFormOperation = ref<'create' | 'update'>('create')
const showFullGalleryForm = ref(false)
const fallbackImage = '/images/no-image.svg'

const props = withDefaults(
  defineProps<{
    gallery: Gallery
  }>(),
  {
    gallery: () => GALLERY,
  }
)

const gridClassesAtLargerThanSm = computed(() => {
  switch (groupItems.value.length) {
    case 1:
      return 'md:grid-cols-1'
    case 2:
      return 'md:grid-cols-2'
    case 3:
      return 'md:grid-cols-3'
    default:
      return 'md:grid-cols-4'
  }
})

const menuItem = ref([
  {
    label: 'Delete',
    icon: 'pi pi-trash',
    command: () => {
      deleteThisImage()
    },
  },
])
const selectedImage = ref()
const newImageCount = ref(0)
const failedImages = ref<Set<number>>(new Set())
const visible = defineModel('visible', { default: true })

const imageCount = computed(() => {
  return newImageCount.value || props.gallery.image_count
})

const groupItems = computed(() => {
  const items = imageCount.value
    ? genRandomNumArr(imageCount.value, 1, imageCount.value)
    : []

  return groupArr(items, 3, [2, 3])
})

watch(
  () => props.gallery.id,
  () => {
    failedImages.value = new Set()
    newImageCount.value = 0
  }
)

function getGalleryImage(image: number) {
  if (failedImages.value.has(image)) {
    return fallbackImage
  }

  return useResolvedImage(`/storage/galleries/${props.gallery.id}/${image}.webp`)
}

function markGalleryImageAsFailed(image: number) {
  const nextFailedImages = new Set(failedImages.value)
  nextFailedImages.add(image)
  failedImages.value = nextFailedImages
}

function onImageRightClick(event: Event, image: number) {
  selectedImage.value = image

  if (!galleryImgRef.value) {
    return
  }

  galleryImgRef.value.show(event)
}

const galleryImgRef = useTemplateRef('galleryImgRef')

async function deleteThisImage() {
  const response = await useSanctumPost<{
    gallery: Record<string, any>
    imageCount: number
    message: string
  }>(`/api/upload/gallery/${props.gallery.id}`, 'delete', {
    id: selectedImage.value,
  })

  if (response.status.value == 'success') {
    if (response.data.value) {
      newImageCount.value = response.data.value.imageCount
    }
  }
}
</script>
