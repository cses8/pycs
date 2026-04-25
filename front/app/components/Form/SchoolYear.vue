<template>
  <Dialog
    v-model:visible="visible"
    modal
    :closeOnEscape="false"
    :draggable="false"
    :style="{ width: 'min(94vw, 34rem)' }"
    pt:root:class="!border-0 !bg-transparent !shadow-none"
    pt:mask:class="backdrop-blur-sm !bg-slate-950/45"
    aria-labelledby="school-year-form-title"
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
              <Icon :name="operationIcon" class="size-6" />
            </div>
            <div class="min-w-0">
              <p class="text-xs font-semibold uppercase text-blue-200">
                School Year
              </p>
              <h2
                id="school-year-form-title"
                class="mt-1 truncate text-xl font-bold leading-tight text-white sm:text-2xl"
              >
                {{ dialogTitle }}
              </h2>
              <p class="mt-2 text-sm leading-6 text-slate-300">
                {{ dialogDescription }}
              </p>
            </div>
          </div>

          <Button
            icon="pi pi-times"
            rounded
            text
            severity="secondary"
            aria-label="Close school year form"
            class="!-mr-2 !-mt-2 !h-10 !w-10 !text-slate-200 hover:!bg-white/10 hover:!text-white"
            @click="closeCallback"
          />
        </header>

        <div class="pycs-modal-body bg-slate-50 p-5 dark:bg-surface-900">
          <div
            class="mb-4 inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-bold uppercase"
            :class="operationBadgeClass"
          >
            <span class="size-2 rounded-full" :class="operationDotClass" />
            {{ operationLabel }}
          </div>

          <div
            v-if="formError"
            role="alert"
            class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300"
          >
            {{ formError }}
          </div>

          <div
            v-if="operation == 'delete'"
            class="rounded-lg border border-red-200 bg-white p-5 shadow-sm dark:border-red-900/60 dark:bg-surface-950"
          >
            <div class="flex gap-4">
              <div
                class="flex size-11 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-700 dark:bg-red-950/60 dark:text-red-300"
              >
                <Icon name="lucide:trash-2" class="size-5" />
              </div>
              <div>
                <h3 class="text-lg font-bold text-slate-950 dark:text-white">
                  Delete this school year?
                </h3>
                <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-surface-300">
                  This will remove
                  <strong class="font-semibold text-slate-950 dark:text-white">
                    {{ schoolYear.description }}
                  </strong>
                  from the school year list.
                </p>
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
                label="Delete school year"
                icon="pi pi-trash"
                severity="danger"
                class="!rounded-lg !py-3"
                @click="deleteThisItem"
              />
            </div>
          </div>

          <Fluid v-else>
            <Form
              v-slot="$form"
              :initialValues="schoolYear"
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
                      for="description"
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                    >
                      School year
                    </label>
                    <InputText
                      id="description"
                      name="description"
                      type="text"
                      placeholder="2025-2026"
                      class="w-full !rounded-lg !border-slate-300 !py-3 dark:!border-surface-700 dark:!bg-surface-900"
                    />
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
                    :label="operation == 'create' ? 'Create school year' : 'Save changes'"
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
import { SCHOOL_YEAR } from '~/constant/SchoolYear'
import { z } from 'zod'
import { zodResolver } from '@primevue/forms/resolvers/zod'

const emit = defineEmits<{
  onFormSuccess: [value: number]
}>()

const visible = defineModel('visible', { default: false })
const schoolYear = defineModel('schoolYear', { default: SCHOOL_YEAR })
const operation = defineModel<'create' | 'update' | 'delete'>('operation', {
  default: 'create',
})
const formError = ref('')

type SchoolYearFormValues = {
  id: number
  description: string
}

type SchoolYearFormSubmitEvent = {
  valid: boolean
  values: SchoolYearFormValues
}

const operationLabel = computed(() => {
  if (operation.value === 'create') {
    return 'New'
  }

  if (operation.value === 'delete') {
    return 'Delete'
  }

  return 'Update'
})

const operationIcon = computed(() => {
  if (operation.value === 'create') {
    return 'lucide:plus'
  }

  if (operation.value === 'delete') {
    return 'lucide:trash-2'
  }

  return 'lucide:pencil'
})

const dialogTitle = computed(() => {
  if (operation.value === 'create') {
    return 'Create school year'
  }

  return schoolYear.value.description || 'School year'
})

const dialogDescription = computed(() => {
  if (operation.value === 'delete') {
    return 'Confirm removal before deleting this school year.'
  }

  return 'Encode the academic year label used across school year filters.'
})

const operationBadgeClass = computed(() => {
  if (operation.value === 'create') {
    return 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300'
  }

  if (operation.value === 'delete') {
    return 'border-red-200 bg-red-50 text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300'
  }

  return 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-300'
})

const operationDotClass = computed(() => {
  if (operation.value === 'create') {
    return 'bg-emerald-500'
  }

  if (operation.value === 'delete') {
    return 'bg-red-500'
  }

  return 'bg-amber-500'
})

const resolver = ref(
  zodResolver(
    z.object({
      id: z.number({
        required_error: 'ID is required',
        invalid_type_error: 'Input must be a number',
      }),
      description: z
        .string()
        .min(1, { message: 'School year is required.' })
        .max(20, { message: 'School year must be 20 characters or fewer.' }),
    })
  )
)

async function onFormSubmit(e: SchoolYearFormSubmitEvent) {
  formError.value = ''

  await task()
    .do(async () => useLoaderStore().processStep('schoolYear'))
    .do(async () => {
      if (!e.valid) {
        useLoaderStore().setDoneToTheOpenLoader('failed')
      } else {
        useLoaderStore().setDoneToTheOpenLoader()
      }
    })
    .do(async () => {
      if (e.valid) {
        const { status, error } = await useSanctumPost(
          `/api/school-years`,
          operation.value,
          e.values
        )

        if (status.value == 'success') {
          emit('onFormSuccess', Math.random())
          useLoaderStore().setDoneToTheOpenLoader()
          visible.value = false
        } else {
          formError.value = getErrorMessage(error.value)
          useLoaderStore().setDoneToTheOpenLoader('failed')
        }
      }
    })
    .end()
}

function deleteThisItem() {
  onFormSubmit({
    valid: true,
    values: schoolYear.value,
  })
}

function getErrorMessage(error: unknown) {
  const fallback = 'Unable to save school year. Please review the record and try again.'

  if (!error || typeof error !== 'object') {
    return fallback
  }

  const payload = error as {
    data?: { message?: unknown }
    message?: unknown
  }

  if (typeof payload.data?.message === 'string') {
    return payload.data.message
  }

  if (typeof payload.message === 'string') {
    return payload.message
  }

  return fallback
}

watch(visible, (isVisible) => {
  if (!isVisible) {
    formError.value = ''
  }
})
</script>
