<template>
  <div v-if="!isAuthenticated">
    <Dialog
      v-model:visible="visible"
      pt:root:class="!border-0 !bg-transparent"
      pt:mask:class="backdrop-blur-sm"
    >
      <template #container="{ closeCallback }">
        <Form
          v-slot="$form"
          :initialValues="credentials"
          :resolver
          @submit="onFormSubmit"
        >
          <div
            class="flex flex-col px-8 py-8 gap-6 rounded-2xl"
            style="
              background-image: radial-gradient(
                circle at left top,
                var(--p-blue-600),
                var(--p-blue-900)
              );
            "
          >
            <div class="flex justify-center w-full">
              <Image src="/images/logo.webp" width="150" height="150" />
            </div>

            <div class="inline-flex flex-col gap-2">
              <InputText id="device_name" name="device_name" class="!hidden" />
            </div>

            <div class="inline-flex flex-col gap-2">
              <label for="email" class="text-primary-50 font-semibold">
                Email
              </label>

              <InputText
                id="email"
                name="email"
                class="!bg-white/20 !border-0 !p-4 !text-primary-50 w-80"
              />
              <Message
                v-if="$form.email?.invalid"
                size="small"
                variant="simple"
                class="!text-red-400"
                >{{ $form.email.error?.message }}</Message
              >
            </div>
            <div class="inline-flex flex-col gap-2">
              <label for="password" class="text-primary-50 font-semibold">
                Password
              </label>
              <InputText
                id="password"
                name="password"
                class="!bg-white/20 !border-0 !p-4 !text-primary-50 w-80"
                type="password"
              />
              <Message
                v-if="$form.password?.invalid"
                size="small"
                variant="simple"
                class="!text-red-400"
              >
                {{ $form.password.error?.message }}
              </Message>
            </div>

            <div class="flex items-center justify-between mb-12">
              <div class="flex items-center">
                <Checkbox name="remember" binary class="mr-2" />
                <label for="remember" class="text-white">Remember me</label>
              </div>
            </div>

            <div class="flex items-center gap-4">
              <Button
                label="Cancel"
                @click="closeCallback"
                text
                class="!p-4 w-full !text-primary-50 !border !border-white/30 hover:!bg-white/10"
              />
              <Button
                label="Sign-In"
                type="submit"
                text
                class="!p-4 w-full !text-primary-50 !border !border-white/30 hover:!bg-white/10"
              />
            </div>
          </div>
        </Form>
      </template>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
const { login, isAuthenticated } = useSanctumAuth()
import { z } from 'zod'
import { zodResolver } from '@primevue/forms/resolvers/zod'

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

async function onFormSubmit(e: any) {
  if (e.valid) {
    loaderStore.processStep('login')
    let response: any
    try {
      response = await login(e.values)
    } catch (error) {
      response = error
    }

    console.log(response)
    if (Object.hasOwn(response, 'two_factor')) {
      loaderStore.setDoneToTheOpenLoader()
    } else {
      loaderStore.setDoneToTheOpenLoader('failed')
    }
  }
}
</script>
