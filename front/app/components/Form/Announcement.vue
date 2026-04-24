<template>
  <Dialog
    v-model:visible="visible"
    modal
    :closeOnEscape="false"
    class="!max-w-[50rem]"
  >
    <template #header>
      <div
        class="text-blue-900 dark:text-blue-500 text-3xl font-semibold w-full text-center py-5"
      >
        {{ announcement.title }}
      </div>
    </template>
    <div>
      <Fluid>
        <Tag
          :class="[
            'mb-2 border',
            {
              '!bg-orange-300 !border-orange-700 !text-orange-700 ':
                operation == 'update',
              '!bg-green-200 !border-green-700 !text-green-700 ':
                operation == 'create',
              '!bg-red-200 !border-red-700 !text-red-700 ':
                operation == 'delete',
            },
          ]"
        >
          {{
            operation == 'create'
              ? 'New'
              : operation == 'delete'
              ? 'Delete'
              : 'Update'
          }}
        </Tag>
        <div v-if="operation == 'delete'">
          Are you sure you want to delete
          <strong class="mx-1">
            <code
              class="px-1 bg-red-50 text-red-600 border rounded border-red-500"
              >{{ announcement.title }}</code
            > </strong
          >?
        </div>
        <Form
          v-if="operation !== 'delete'"
          v-slot="$form"
          :initialValues="announcement"
          :resolver
          @submit="onFormSubmit"
        >
          <div class="bg-surface-0 p-6 border rounded-border">
            <div class="grid grid-cols-12 gap-4">
              <!-- for hidden -->
              <div class="flex-col gap-2 !hidden">
                <InputText id="id" name="id" class="!hidden" />
              </div>

              <div class="mb-4 col-span-12">
                <label
                  for="title"
                  class="font-medium text-surface-900 block mb-1"
                  >Title</label
                >
                <InputText id="title" name="title" type="text" />
                <Message
                  v-if="$form.title?.invalid"
                  size="small"
                  variant="simple"
                  class="!text-red-400"
                  >{{ $form.title.error?.message }}</Message
                >
              </div>
              <div class="mb-4 col-span-12 md:col-span-6">
                <label
                  for="start"
                  class="font-medium text-surface-900 block mb-1"
                >
                  Start
                </label>
                <DatePicker id="start" :show-icon="true" name="start" />
                <Message
                  v-if="$form.start?.invalid"
                  size="small"
                  variant="simple"
                  class="!text-red-400"
                  >{{ $form.start.error?.message }}</Message
                >
              </div>

              <div class="mb-4 col-span-12 md:col-span-6">
                <label
                  for="end"
                  class="font-medium text-surface-900 block mb-1"
                >
                  End
                </label>
                <DatePicker id="end" name="end" :show-icon="true" />
                <Message
                  v-if="$form.end?.invalid"
                  size="small"
                  variant="simple"
                  class="!text-red-400"
                  >{{ $form.end.error?.message }}</Message
                >
              </div>
              <div
                class="border-surface border-t opacity-50 mb-4 col-span-12"
              />
              <div class="mb-4 col-span-12">
                <label
                  for="description"
                  class="font-medium text-surface-900 block mb-1"
                >
                  description
                </label>
                <Editor name="description" editorStyle="height: 320px" />
                <Message
                  v-if="$form.description?.invalid"
                  size="small"
                  variant="simple"
                  class="!text-red-400"
                  >{{ $form.description.error?.message }}</Message
                >
              </div>
              <div
                class="border-surface border-t opacity-50 mb-4 col-span-12"
              />
            </div>
            <Button
              label="Save"
              icon="pi pi-file"
              class="w-auto"
              type="submit"
            />
          </div>
        </Form>
      </Fluid>

      <div class="w-full mt-10">
        <Button
          v-if="operation == 'delete'"
          label="Continue"
          icon="pi pi-file"
          class="flex w-full"
          @click="deleteThisItem"
        />
      </div>
    </div>
  </Dialog>
</template>

<script setup lang="ts">
import { ANNOUNCEMENT } from '~/constant/Announcement'
import { z } from 'zod'
import { zodResolver } from '@primevue/forms/resolvers/zod'

const dayjs = useDayjs()

const emit = defineEmits<{
  onFormSuccess: [value: number]
}>()

const visible = defineModel('visible', { default: false })
const announcement = defineModel('announcement', { default: ANNOUNCEMENT })
const operation = defineModel<'create' | 'update' | 'delete'>('operation', {
  default: 'create',
})

const resolver = ref(
  zodResolver(
    z.object({
      id: z.number({
        // Optional custom messages
        required_error: 'ID is required',
        invalid_type_error: 'Input must be a number',
      }),
      title: z.string().min(1, { message: 'title is required via Zod.' }),
      description: z
        .string()
        .min(1, { message: 'Description is required via Zod.' }),
      start: z.union(
        [
          z.string(), // Allows any string
          z.date(), // Allows any valid JavaScript Date object
        ],
        // Optional: Custom error message if the value matches neither
        {
          invalid_type_error: 'Input must be either a string or a Date object.',
          // required_error: "A string or date is required." // If used in an object and is required
        }
      ),
      end: z.union(
        [
          z.string(), // Allows any string
          z.date(), // Allows any valid JavaScript Date object
        ],
        // Optional: Custom error message if the value matches neither
        {
          invalid_type_error: 'Input must be either a string or a Date object.',
          // required_error: "A string or date is required." // If used in an object and is required
        }
      ),
    })
  )
)

async function onFormSubmit(e: any) {
  console.log(e, 'onFormSubmit')
  await task()
    .do(async () => useLoaderStore().processStep('announcement'))
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
        const { data, status, error, refresh } = await useSanctumPost(
          `/api/announcements`,
          operation.value,
          e.values
        )

        console.log(status)

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

function deleteThisItem() {
  onFormSubmit({
    valid: true,
    values: announcement.value,
  })
}
</script>
