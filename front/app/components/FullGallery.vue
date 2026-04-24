<template>
  <FormGalleryUpload
    v-model:visible="showFullGalleryForm"
    v-model:gallery="props.gallery"
    v-model:operation="fullGalleryFormOperation"
  />
  <Dialog
    v-model:visible="visible"
    :closeOnEscape="false"
    modal
    header="Division Schools Press Conference Finals"
    class="!max-w-[66rem] !w-full !border-none mx-6 shadow-[0px_4px_10px_0px_rgba(0,0,0,0.03),0px_0px_2px_0px_rgba(0,0,0,0.06),0px_2px_6px_0px_rgba(0,0,0,0.12)] !rounded-xl lg:!rounded-2xl dark:!bg-white/80 backdrop-blur-xl"
  >
    <template #header>
      <div
        class="text-blue-900 dark:text-blue-500 text-3xl font-semibold w-full text-center py-5"
      >
        {{ gallery.title }}
      </div>
    </template>

    <div class="text-gray-500 mt-4 flex">
      <Tag severity="secondary" class="outline outline-1">
        <Icon name="hugeicons:date-time" size="1.5rem" class="mr-2" />
        <span class="font-normal">{{
          $dayjs(gallery.start).format('MMMM DD, YYYY')
        }}</span>
      </Tag>

      <Tag severity="secondary" class="outline outline-1">
        <Icon name="hugeicons:image-01" size="1.5rem" class="mr-2" />
        <span class="font-normal">
          {{ newImageCount || props.gallery.image_count }}
        </span>
      </Tag>

      <Button
        v-if="isAuthenticated"
        icon="pi pi-plus"
        label="Add Image"
        secondary
        outlined
        size="small"
        class="ml-10 bg-green-50"
        @click="showFullGalleryForm = true"
      />
    </div>

    <div
      class="text-gray-500 font-light pt-4 text-justify text-lg"
      v-html="gallery.description"
    />

    <div
      :class="`grid grid-cols-2 ${gridClassesAtLargerThanSm} gap-4 pt-4 lg:pt-8`"
    >
      <div
        v-for="(groupItem, index) in groupItems"
        :key="index"
        class="grid gap-4"
      >
        <div v-for="(item, index2) in groupItem" :key="`${index}_${index2}`">
          <Image
            :src="
              useResolvedImage(
                `/storage/galleries/${props.gallery.id}/${item}.webp`
              )
            "
            preview
            :pt="{
              root: {
                class: {
                  'h-full': groupItem.length > 1,
                  'h-auto': groupItem.length == 1,
                },
              },
              image: {
                class: [
                  'max-w-full rounded-lg object-cover border dark:border-black/20 hover:border-red-400',
                  {
                    'h-full': groupItem.length > 1,
                    'h-auto': groupItem.length == 1,
                  },
                ],
              },
            }"
            @contextmenu="onImageRightClick($event, item)"
          />
        </div>
      </div>
    </div>
    <ContextMenu v-if="isAuthenticated" ref="galleryImgRef" :model="menuItem" />
  </Dialog>
</template>
<script setup lang="ts">
import { GALLERY } from '~/constant/Gallery'

const { isAuthenticated } = useSanctumAuth()

const props = withDefaults(
  defineProps<{
    gallery?: Gallery
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

const showFullGalleryForm = ref<boolean>(false)
const fullGalleryFormOperation = ref<'create' | 'update'>('create')
const galleryImgRef = ref()
const menuItem = ref([
  {
    label: 'Delete',
    icon: 'pi pi-trash',
    command: () => deleteThisImage(),
  },
])
const selectedImage = ref()
const newImageCount = ref(0)
const visible = defineModel('visible', { default: true })

const groupItems = computed(() => {
  const baseImageCount = newImageCount.value || props.gallery.image_count
  const items = baseImageCount
    ? genRandomNumArr(baseImageCount, 1, baseImageCount)
    : []

  return groupArr(items, 3, [2, 3])
})

function onImageRightClick(event: Event, image: number) {
  galleryImgRef.value.show(event)
  selectedImage.value = image
}

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
