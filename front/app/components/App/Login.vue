<template>
  <div v-if="!isAuthenticated">
    <Dialog
      v-model:visible="visible"
      modal
      :draggable="false"
      :style="{ width: 'min(92vw, 440px)' }"
      :breakpoints="{ '640px': '92vw' }"
      pt:root:class="!border-0 !bg-transparent !shadow-none"
      pt:mask:class="backdrop-blur-sm !bg-slate-950/45"
      aria-labelledby="login-dialog-title"
    >
      <template #container="{ closeCallback }">
        <Form
          v-slot="$form"
          :initialValues="credentials"
          :resolver
          @submit="onFormSubmit"
        >
          <div
            class="pycs-modal-shell rounded-lg border border-slate-200 bg-white shadow-2xl shadow-slate-950/20 dark:border-surface-700 dark:bg-surface-950"
          >
            <div
              class="pycs-modal-header flex items-start gap-4 border-b border-slate-200 bg-slate-950 px-6 py-5 text-white dark:border-surface-800"
            >
              <div
                class="flex h-16 w-16 shrink-0 items-center justify-center rounded-lg bg-white p-2"
              >
                <Image
                  src="/images/logo.webp"
                  alt="Philippine Yuh Chiau School logo"
                  width="52"
                  height="52"
                />
              </div>

              <div class="min-w-0 flex-1 pt-1">
                <p class="text-xs font-semibold uppercase tracking-wide text-indigo-200">
                  Secure access
                </p>
                <h2
                  id="login-dialog-title"
                  class="mt-1 text-xl font-semibold leading-tight text-white"
                >
                  Sign in to PYCS
                </h2>
                <p class="mt-2 text-sm leading-snug text-slate-300">
                  Use your school account to manage protected content.
                </p>
              </div>

              <Button
                icon="pi pi-times"
                text
                rounded
                severity="secondary"
                aria-label="Close login dialog"
                class="!-mr-2 !-mt-2 !h-9 !w-9 !text-slate-200 hover:!bg-white/10 hover:!text-white"
                @click="closeCallback"
              />
            </div>

            <div class="pycs-modal-body flex flex-col gap-5 px-6 py-6">
              <InputText id="device_name" name="device_name" class="!hidden" />

              <div class="flex flex-col gap-2">
                <label
                  for="email"
                  class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                >
                  Email
                </label>

                <div class="relative">
                  <i
                    class="pi pi-envelope pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"
                  />
                  <InputText
                    id="email"
                    name="email"
                    type="email"
                    autocomplete="email"
                    class="w-full !rounded-lg !border-slate-300 !py-3 !pl-10 !pr-3 !text-slate-900 focus:!border-indigo-500 focus:!ring-2 focus:!ring-indigo-100 dark:!border-surface-700 dark:!bg-surface-900 dark:!text-surface-0"
                  />
                </div>
                <Message
                  v-if="$form.email?.invalid"
                  size="small"
                  variant="simple"
                  class="!text-red-600 dark:!text-red-300"
                  >{{ $form.email.error?.message }}</Message
                >
              </div>

              <div class="flex flex-col gap-2">
                <label
                  for="password"
                  class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                >
                  Password
                </label>
                <div class="relative">
                  <i
                    class="pi pi-lock pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"
                  />
                  <InputText
                    id="password"
                    name="password"
                    class="w-full !rounded-lg !border-slate-300 !py-3 !pl-10 !pr-3 !text-slate-900 focus:!border-indigo-500 focus:!ring-2 focus:!ring-indigo-100 dark:!border-surface-700 dark:!bg-surface-900 dark:!text-surface-0"
                    type="password"
                    autocomplete="current-password"
                  />
                </div>
                <Message
                  v-if="$form.password?.invalid"
                  size="small"
                  variant="simple"
                  class="!text-red-600 dark:!text-red-300"
                >
                  {{ $form.password.error?.message }}
                </Message>
              </div>

              <div
                class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 dark:border-surface-700 dark:bg-surface-900"
              >
                <div class="flex items-center gap-2">
                  <Checkbox input-id="remember" name="remember" binary />
                  <label
                    for="remember"
                    class="cursor-pointer text-sm font-medium text-slate-700 dark:text-surface-200"
                  >
                    Remember me
                  </label>
                </div>
                <i class="pi pi-shield text-sm text-indigo-600 dark:text-indigo-300" />
              </div>

              <div class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_1.4fr]">
                <Button
                  label="Cancel"
                  icon="pi pi-times"
                  severity="secondary"
                  outlined
                  class="!rounded-lg !border-slate-300 !py-3"
                  @click="closeCallback"
                />
                <Button
                  label="Sign in"
                  icon="pi pi-sign-in"
                  icon-pos="right"
                  type="submit"
                  class="!rounded-lg !border-indigo-700 !bg-indigo-700 !py-3 hover:!border-indigo-800 hover:!bg-indigo-800"
                />
              </div>
            </div>
          </div>
        </Form>
      </template>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { z } from 'zod'
import { zodResolver } from '@primevue/forms/resolvers/zod'

type LoginFormSubmitEvent = {
  valid: boolean
  values: {
    email: string
    password: string
    remember: boolean
    device_name: 'user_browser' | 'mobile_app'
  }
}

const { login, isAuthenticated } = useSanctumAuth()
const visible = defineModel('visible', { default: false })
const loaderStore = useLoaderStore()

const credentials = ref({
  email: '',
  password: '',
  remember: false,
  device_name: 'user_browser',
})

const resolver = ref(
  zodResolver(
    z.object({
      email: z
        .string()
        .min(1, { message: 'Email is required via Zod.' })
        .email('This is not a valid email.'),
      password: z.string().min(1, { message: 'Password is required via Zod.' }),
      remember: z.boolean(),
      device_name: z.enum(['user_browser', 'mobile_app']),
    })
  )
)

async function onFormSubmit(e: LoginFormSubmitEvent) {
  if (e.valid) {
    loaderStore.processStep('login')
    let response: unknown
    try {
      response = await login(e.values)
    } catch (error) {
      response = error
    }

    console.log(response)
    if (response && typeof response === 'object' && Object.hasOwn(response, 'two_factor')) {
      loaderStore.setDoneToTheOpenLoader()
    } else {
      loaderStore.setDoneToTheOpenLoader('failed')
    }
  }
}
</script>
