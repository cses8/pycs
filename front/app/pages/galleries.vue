<template>
  <div class="bg-surface-0 dark:bg-surface-950">
    <FormGallery
      v-model:visible="showGalleryForm"
      v-model:gallery="selectedGallery"
      v-model:operation="galleryFormOperation"
      @onFormSuccess="() => galleriesInit++"
    />
    <FullGallery v-model:visible="showFullGallery" :gallery="galleryData" />
    <div
      class="bg-cover bg-center h-[420px] flex flex-col justify-end gap-4"
      style="
        background: linear-gradient(
            0deg,
            rgba(0, 0, 0, 1) 0%,
            oklch(42.4% 0.199 265.638 / 0.8) 100%
          ),
          url('/images/banner1.webp');
      "
    >
      <div class="px-6 md:px-12 lg:px-20">
        <div class="grid grid-cols-12 gap-8">
          <div class="hidden lg:block col-span-2" />

          <div
            class="col-span-12 lg:col-span-8 py-4 lg:py-8 flex flex-col gap-4"
          >
            <h1
              class="text-3xl lg:text-5xl font-bold text-white leading-tight flex"
            >
              Galleries
              <Button
                v-if="isAuthenticated"
                icon="pi pi-plus"
                label="Add Gallery"
                secondary
                outlined
                class="ml-10 w-auto bg-green-50"
                @click="addThisGallery()"
              />
            </h1>
            <p class="text-sm lg:text-base text-white leading-normal">
              Explore the vibrant life of our school! Galleries featuring
              academics, athletics, arts, and student activities.
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="px-6 md:px-12 lg:px-20 py-4">
      <div class="grid grid-cols-12 gap-4 lg:gap-8">
        <div class="col-span-12 lg:col-span-10 flex flex-col gap-8 py-4">
          <div class="flex flex-col gap-6">
            <div class="bg-surface-0 dark:bg-surface-950">
              <div class="flex flex-col items-center gap-14">
                <div
                  class="w-full grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3"
                >
                  <template v-for="gallery in galleries" :key="gallery.id">
                    <div
                      class="group relative w-full h-[25rem] cursor-pointer overflow-hidden rounded-xl"
                    >
                      <Image
                        class="w-full h-full object-cover"
                        :src="
                          useResolvedImage(
                            `/storage/galleries/${gallery.id}/${randomIntRange(
                              !gallery.image_count ? 0 : 1,
                              gallery.image_count
                            )}.webp`
                          )
                        "
                        :alt="gallery.title"
                      />

                      <div
                        class="absolute inset-0 bg-black/50"
                        @click="openFullGallery(gallery)"
                      />

                      <div
                        v-if="isAuthenticated"
                        class="absolute p-2 left-4 transition-all duration-300 top-4 flex flex-col gap-6 rounded-lg bg-white/20 backdrop-blur-[5px] shadow-[0px_0px_50px_0px_rgba(0,0,0,0.05)_inset] border items-center"
                      >
                        <Icon
                          name="mdi:pencil"
                          class="text-white flex"
                          size="1.6rem"
                          @click="editThisGallery(gallery)"
                        />

                        <Divider class="!m-0" />

                        <Icon
                          name="mdi:trash"
                          class="text-white flex"
                          size="1.6rem"
                          @click="deleteThisGallery(gallery)"
                        />
                      </div>

                      <div
                        class="absolute p-2 right-4 transition-all duration-300 top-4 flex flex-col gap-6 rounded-lg bg-white/20 backdrop-blur-[5px] shadow-[0px_0px_50px_0px_rgba(0,0,0,0.05)_inset]"
                      >
                        <span
                          class="flex items-center justify-center cursor-pointer !text-xs !leading-none text-white"
                        >
                          {{ $dayjs(gallery.start).format('MMMM') }}
                        </span>
                        <code
                          class="flex items-center justify-center cursor-pointer !text-2xl !leading-none text-white"
                        >
                          {{ $dayjs(gallery.start).format('DD') }}
                        </code>
                        <span
                          class="flex items-center justify-center cursor-pointer !text-xs !leading-none text-white"
                        >
                          {{ $dayjs(gallery.start).format('YYYY') }}
                        </span>
                      </div>

                      <div
                        class="absolute bottom-4 left-4 right-4 bg-white/20 backdrop-blur-[5px] shadow-[0px_0px_50px_0px_rgba(0,0,0,0.05)_inset] rounded p-4"
                      >
                        <h4
                          class="text-xl font-semibold text-white leading-tight"
                        >
                          {{ gallery.title }}
                        </h4>
                      </div>
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div
          class="col-span-12 lg:col-span-2 lg:border-l border-surface-200 dark:border-surface-700"
        >
          <SchoolYear />
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { GALLERY } from '~/constant/Gallery'
const { isAuthenticated } = useSanctumAuth()

definePageMeta({
  layout: 'welcome',
})

const emit = defineEmits<{
  onFormSuccess: [value: number]
}>()

const galleriesInit = ref<number>(0)
const showFullGallery = ref<boolean>(false)

const showGalleryForm = ref<boolean>(false)
const selectedGallery = ref<Gallery>(GALLERY)
const galleryFormOperation = ref<'create' | 'update'>('create')

const galleryData = ref(GALLERY)
const galleries = computedAsync<Gallery[]>(async () => {
  galleriesInit.value
  const response = await useGetFetch<Gallery[]>('api/galleries')

  if (Array.isArray(response)) {
    return response as Gallery[]
  }

  return []
}, [])

function openFullGallery(gallery: Gallery) {
  showFullGallery.value = true
  galleryData.value = gallery
}

function editThisGallery(gallery: Gallery) {
  showGalleryForm.value = true
  galleryFormOperation.value = 'update'
  selectedGallery.value = gallery
}

async function deleteThisGallery(gallery: Gallery) {
  const confirmation = window.confirm(
    'Are you sure you want to delete this gallery?'
  )

  if (confirmation) {
    const { data, status, error, refresh } = await useSanctumPost(
      `/api/galleries`,
      'delete',
      gallery
    )

    if (status.value == 'success') {
      galleriesInit.value++
    }
  }
}

function addThisGallery() {
  showGalleryForm.value = true
  galleryFormOperation.value = 'create'
  selectedGallery.value = GALLERY
}
</script>
