<template>
  <Dialog
    v-model:visible="visible"
    modal
    :closeOnEscape="false"
    :draggable="false"
    :style="{ width: 'min(94vw, 46rem)' }"
    pt:root:class="!border-0 !bg-transparent !shadow-none"
    pt:mask:class="backdrop-blur-sm !bg-slate-950/45"
    aria-labelledby="gallery-upload-title"
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
              <Icon name="lucide:images" class="size-6" />
            </div>
            <div class="min-w-0">
              <p class="text-xs font-semibold uppercase text-blue-200">
                Gallery Upload
              </p>
              <h2
                id="gallery-upload-title"
                class="mt-1 truncate text-xl font-bold leading-tight text-white sm:text-2xl"
              >
                {{ gallery.title || 'Upload gallery photos' }}
              </h2>
              <p class="mt-2 text-sm leading-6 text-slate-300">
                Add one or more images to this gallery collection.
              </p>
            </div>
          </div>

          <Button
            icon="pi pi-times"
            rounded
            text
            severity="secondary"
            aria-label="Close gallery upload"
            class="!-mr-2 !-mt-2 !h-10 !w-10 !text-slate-200 hover:!bg-white/10 hover:!text-white"
            @click="closeCallback"
          />
        </header>

        <div class="pycs-modal-body bg-slate-50 p-5 dark:bg-surface-900">
          <div
            class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-surface-700 dark:bg-surface-950"
          >
            <FileUpload
              name="files[]"
              :multiple="true"
              accept="image/*"
              :maxFileSize="1000000"
              @upload="onTemplatedUpload($event)"
              @select="onSelectedFiles"
              @clear="clearFiles"
            >
              <template
                #header="{ chooseCallback, uploadCallback, clearCallback, files }"
              >
                <div class="flex flex-col gap-4">
                  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                      <h3 class="text-base font-bold text-slate-950 dark:text-white">
                        Gallery photos
                      </h3>
                      <p class="mt-1 text-sm text-slate-600 dark:text-surface-300">
                        Select image files under 1 MB each.
                      </p>
                    </div>
                    <div class="flex gap-2">
                      <Button
                        @click="chooseCallback()"
                        icon="pi pi-images"
                        rounded
                        outlined
                        severity="secondary"
                        aria-label="Choose images"
                      />
                      <Button
                        @click="uploadEvent(uploadCallback)"
                        icon="pi pi-cloud-upload"
                        rounded
                        severity="success"
                        aria-label="Upload images"
                        :disabled="!files || files.length === 0"
                      />
                      <Button
                        @click="clearSelectedFiles(clearCallback)"
                        icon="pi pi-times"
                        rounded
                        outlined
                        severity="danger"
                        aria-label="Clear selected images"
                        :disabled="!files || files.length === 0"
                      />
                    </div>
                  </div>

                  <div>
                    <div class="mb-2 flex justify-between text-xs font-semibold text-slate-500 dark:text-surface-400">
                      <span>{{ selectedSizeLabel }}</span>
                      <span>1 MB per image</span>
                    </div>
                    <ProgressBar
                      :value="totalSizePercent"
                      :showValue="false"
                      class="!h-2 !rounded-full"
                    />
                  </div>
                </div>
              </template>

              <template #empty>
                <div
                  class="flex min-h-64 flex-col items-center justify-center rounded-lg border border-dashed border-slate-300 bg-slate-50 px-6 py-10 text-center dark:border-surface-700 dark:bg-surface-900"
                >
                  <div
                    class="flex size-16 items-center justify-center rounded-full bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300"
                  >
                    <Icon name="lucide:cloud-upload" class="size-8" />
                  </div>
                  <h3 class="mt-4 text-base font-bold text-slate-950 dark:text-white">
                    Drop photos here
                  </h3>
                  <p class="mt-2 max-w-sm text-sm leading-6 text-slate-600 dark:text-surface-300">
                    Or use the image button above to choose files from your device.
                  </p>
                </div>
              </template>
            </FileUpload>
          </div>
        </div>
      </section>
    </template>
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

const totalSize = ref(0)
const totalSizePercent = ref(0)
const files = ref<UploadFile[]>([])

type UploadFile = File & {
  objectURL?: string
}

type FileUploadSelectEvent = {
  files: UploadFile[]
}

const selectedSizeLabel = computed(() => {
  if (!totalSize.value) {
    return 'No files selected'
  }

  return `${files.value.length} file${files.value.length === 1 ? '' : 's'} / ${formatSize(totalSize.value)}`
})

const onSelectedFiles = (event: FileUploadSelectEvent) => {
  files.value = event.files
  totalSize.value = files.value.reduce((sum, file) => sum + file.size, 0)
  totalSizePercent.value = Math.min((totalSize.value / 1000000) * 100, 100)
}

const uploadEvent = async (callback: () => void) => {
  await processUpload()
  callback()
}

const clearSelectedFiles = (callback: () => void) => {
  callback()
  clearFiles()
}

const clearFiles = () => {
  files.value = []
  totalSize.value = 0
  totalSizePercent.value = 0
}

const onTemplatedUpload = (event: unknown) => {
  console.log(event, 'onTemplatedUpload')
}

const formatSize = (bytes: number) => {
  const k = 1024
  const dm = 2
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
        formData.append('files[]', file as Blob, file.name)
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
        visible.value = false
      } else {
        useLoaderStore().setDoneToTheOpenLoader('failed')
      }
    })
    .end()
}
</script>
