<template>
  <Dialog
    v-model:visible="visible"
    modal
    :closeOnEscape="false"
    class="!w-[50rem]"
  >
    <template #header>
      <div
        class="text-blue-900 dark:text-blue-500 text-3xl font-semibold w-full text-center py-5"
      >
        {{ gallery.title }}
      </div>
    </template>
    <div>
      <div class="bg-surface-0 p-6 border rounded-border">
        <div class="gap-4">
          <FileUpload
            name="demo[]"
            @upload="onTemplatedUpload($event)"
            :multiple="true"
            accept="image/*"
            :maxFileSize="1000000"
            @select="onSelectedFiles"
          >
            <template
              #header="{ chooseCallback, uploadCallback, clearCallback, files }"
            >
              <div
                class="flex flex-wrap justify-between items-center flex-1 gap-4"
              >
                <div class="flex gap-2">
                  <Button
                    @click="chooseCallback()"
                    icon="pi pi-images"
                    rounded
                    outlined
                    severity="secondary"
                  ></Button>
                  <Button
                    @click="uploadEvent(uploadCallback)"
                    icon="pi pi-cloud-upload"
                    rounded
                    outlined
                    severity="success"
                    :disabled="!files || files.length === 0"
                  ></Button>
                  <Button
                    @click="clearCallback()"
                    icon="pi pi-times"
                    rounded
                    outlined
                    severity="danger"
                    :disabled="!files || files.length === 0"
                  ></Button>
                </div>
                <ProgressBar
                  :value="totalSizePercent"
                  :showValue="false"
                  class="h-1 w-full md:ml-auto"
                >
                  <span class="whitespace-nowrap">{{ totalSize }}B / 1Mb</span>
                </ProgressBar>
              </div>
            </template>

            <template #empty>
              <div class="flex items-center justify-center flex-col">
                <i
                  class="pi pi-cloud-upload !border-2 !rounded-full !p-8 !text-4xl !text-muted-color"
                />
                <p class="mt-6 mb-0">Drag and drop files to here to upload.</p>
              </div>
            </template>
          </FileUpload>
        </div>
      </div>
    </div>
  </Dialog>
</template>

<script setup lang="ts">
import { GALLERY } from '~/constant/Gallery'

const $primevue = usePrimeVue()

const emit = defineEmits<{
  onFormSuccess: [value: number]
}>()

const visible = defineModel('visible', { default: false })
const gallery = defineModel('gallery', { default: GALLERY })

const totalSize: Ref = ref(0)
const totalSizePercent: Ref = ref(0)
const files: Ref = ref([])

interface FileObject {
  name: string
  type: string
  size: number
  objectURL?: string
}

const onSelectedFiles = (event: any) => {
  files.value = event.files as FileObject[]
  files.value.forEach((file: FileObject) => {
    totalSize.value += parseInt(formatSize(file.size))
  })
}

const uploadEvent = async (callback: () => void) => {
  totalSizePercent.value = totalSize.value / 10
  await processUpload()
  callback()
}

const onTemplatedUpload = (event: any) => {
  console.log(event, 'onTemplatedUpload')
}

const formatSize = (bytes: number) => {
  const k = 1024
  const dm = 3
  const sizes = $primevue.config.locale?.fileSizeTypes ?? [
    'Bytes',
    'KB',
    'MB',
    'GB',
  ]

  if (bytes === 0) {
    return `0 ${sizes[0]}`
  }

  const i = Math.floor(Math.log(bytes) / Math.log(k))
  const formattedSize = parseFloat((bytes / Math.pow(k, i)).toFixed(dm))

  return `${formattedSize} ${sizes[i]}`
}

async function processUpload() {
  const formData = new FormData()

  await task()
    .do(async () => useLoaderStore().processStep('galleryUpload'))
    .do(async () => {
      for (const file of files.value) {
        formData.append('files[]', file)
      }
    })
    .do(async () => {
      useLoaderStore().setDoneToTheOpenLoader()
    })
    .do(async () => {
      const response = await useSanctumPost(
        `/api/upload/gallery`,
        'upload',
        {
          id: gallery.value.id,
        },
        formData
      )

      if (response.status.value == 'success') {
        emit('onFormSuccess', Math.random())
        totalSizePercent.value = 100
        useLoaderStore().setDoneToTheOpenLoader()
      } else {
        useLoaderStore().setDoneToTheOpenLoader('failed')
      }
    })
    .do(async () => {
      await asyncTimeout(2000)
      window.location.reload()
    })
    .end()
}
</script>
