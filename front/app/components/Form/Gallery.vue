<template>
  <Dialog
    v-model:visible="visible"
    modal
    :closeOnEscape="false"
    :draggable="false"
    :style="{ width: 'min(94vw, 54rem)' }"
    pt:root:class="!border-0 !bg-transparent !shadow-none"
    pt:mask:class="backdrop-blur-sm !bg-slate-950/45"
    aria-labelledby="gallery-form-title"
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
              <Icon :name="operation == 'create' ? 'lucide:plus' : 'lucide:pencil'" class="size-6" />
            </div>
            <div class="min-w-0">
              <p class="text-xs font-semibold uppercase text-blue-200">
                Gallery
              </p>
              <h2
                id="gallery-form-title"
                class="mt-1 truncate text-xl font-bold leading-tight text-white sm:text-2xl"
              >
                {{ operation == 'create' ? 'Create gallery' : gallery.title }}
              </h2>
              <p class="mt-2 text-sm leading-6 text-slate-300">
                Set the gallery title, date range, and public description.
              </p>
            </div>
          </div>

          <Button
            icon="pi pi-times"
            rounded
            text
            severity="secondary"
            aria-label="Close gallery form"
            class="!-mr-2 !-mt-2 !h-10 !w-10 !text-slate-200 hover:!bg-white/10 hover:!text-white"
            @click="closeCallback"
          />
        </header>

        <div class="pycs-modal-body bg-slate-50 p-5 dark:bg-surface-900">
          <div
            class="mb-4 inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-bold uppercase"
            :class="
              operation == 'create'
                ? 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300'
                : 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-300'
            "
          >
            <span
              class="size-2 rounded-full"
              :class="operation == 'create' ? 'bg-emerald-500' : 'bg-amber-500'"
            />
            {{ operation == 'create' ? 'New' : 'Update' }}
          </div>

          <Fluid>
            <Form
              v-slot="$form"
              :initialValues="gallery"
              :resolver
              @submit="onFormSubmit"
            >
              <div
                class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-surface-700 dark:bg-surface-950"
              >
                <div class="grid grid-cols-12 gap-4">
                  <div class="hidden">
                    <InputText id="id" name="id" />
                  </div>

                  <div class="col-span-12 flex flex-col gap-2">
                    <label
                      for="title"
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                    >
                      Title
                    </label>
                    <InputText
                      id="title"
                      name="title"
                      type="text"
                      class="w-full !rounded-lg !border-slate-300 !py-3 dark:!border-surface-700 dark:!bg-surface-900"
                    />
                    <Message
                      v-if="$form.title?.invalid"
                      size="small"
                      variant="simple"
                      class="!text-red-600 dark:!text-red-300"
                      >{{ $form.title.error?.message }}</Message
                    >
                  </div>

                  <div class="col-span-12 flex flex-col gap-2 md:col-span-6">
                    <label
                      for="start"
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                    >
                      Start date
                    </label>
                    <DatePicker
                      id="start"
                      :show-icon="true"
                      name="start"
                      class="w-full"
                    />
                    <Message
                      v-if="$form.start?.invalid"
                      size="small"
                      variant="simple"
                      class="!text-red-600 dark:!text-red-300"
                      >{{ $form.start.error?.message }}</Message
                    >
                  </div>

                  <div class="col-span-12 flex flex-col gap-2 md:col-span-6">
                    <label
                      for="end"
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                    >
                      End date
                    </label>
                    <DatePicker
                      id="end"
                      name="end"
                      :show-icon="true"
                      class="w-full"
                    />
                    <Message
                      v-if="$form.end?.invalid"
                      size="small"
                      variant="simple"
                      class="!text-red-600 dark:!text-red-300"
                      >{{ $form.end.error?.message }}</Message
                    >
                  </div>

                  <div class="col-span-12 flex flex-col gap-2">
                    <label
                      for="description"
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                    >
                      Description
                    </label>
                    <Editor
                      name="description"
                      placeholder="Write the gallery details with formatting."
                      editor-style="height: 300px"
                      :class="[
                        'pycs-rich-editor',
                        { 'pycs-rich-editor-invalid': $form.description?.invalid },
                      ]"
                    >
                      <template #toolbar>
                        <AppRichEditorToolbar />
                      </template>
                    </Editor>
                    <Message
                      v-if="$form.description?.invalid"
                      size="small"
                      variant="simple"
                      class="!text-red-600 dark:!text-red-300"
                      >{{ $form.description.error?.message }}</Message
                    >
                  </div>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-[1fr_auto]">
                  <Button
                    label="Cancel"
                    icon="pi pi-times"
                    severity="secondary"
                    outlined
                    class="!rounded-lg !py-3"
                    @click="closeCallback"
                  />
                  <Button
                    :label="operation == 'create' ? 'Create gallery' : 'Save changes'"
                    icon="pi pi-check"
                    class="!rounded-lg !py-3"
                    type="submit"
                  />
                </div>
              </div>
            </Form>
          </Fluid>
        </div>
      </section>
    </template>
  </Dialog>
</template>

<script setup lang="ts">
import { GALLERY } from '~/constant/Gallery'
import { z } from 'zod'
import { zodResolver } from '@primevue/forms/resolvers/zod'

const dayjs = useDayjs()

const emit = defineEmits<{
  onFormSuccess: [value: number]
}>()

const visible = defineModel('visible', { default: false })
const gallery = defineModel('gallery', { default: GALLERY })
const operation = defineModel<'create' | 'update'>('operation', {
  default: 'create',
})

type GalleryFormValues = {
  id: number
  title: string
  description: string
  start: string | Date
  end: string | Date
}

type GalleryFormSubmitEvent = {
  valid: boolean
  values: GalleryFormValues
}

const resolver = ref(
  zodResolver(
    z.object({
      id: z.number({
        required_error: 'ID is required',
        invalid_type_error: 'Input must be a number',
      }),
      title: z.string().min(1, { message: 'Title is required.' }),
      description: z.string().min(1, { message: 'Description is required.' }),
      start: z.union([z.string(), z.date()], {
        invalid_type_error: 'Input must be either a string or a Date object.',
      }),
      end: z.union([z.string(), z.date()], {
        invalid_type_error: 'Input must be either a string or a Date object.',
      }),
    })
  )
)

async function onFormSubmit(e: GalleryFormSubmitEvent) {
  await task()
    .do(async () => useLoaderStore().processStep('gallery'))
    .do(async () =>
      Object.assign(e.values, {
        start: dayjs(e.values.start)
          .startOf('day')
          .format('YYYY-MM-DD HH:mm:ss'),
        end: dayjs(e.values.end).startOf('day').format('YYYY-MM-DD HH:mm:ss'),
      })
    )
    .do(async () => {
      if (!e.valid) {
        useLoaderStore().setDoneToTheOpenLoader('failed')
      } else {
        useLoaderStore().setDoneToTheOpenLoader()
      }
    })
    .do(async () => {
      if (e.valid) {
        const { status } = await useSanctumPost(
          `/api/galleries`,
          operation.value,
          e.values
        )

        if (status.value == 'success') {
          emit('onFormSuccess', Math.random())
          useLoaderStore().setDoneToTheOpenLoader()
          visible.value = false
        } else {
          useLoaderStore().setDoneToTheOpenLoader('failed')
        }
      }
    })
    .end()
}
</script>
