import { defineStore } from 'pinia'
import announcement from '~/components/App/Loader/steps/announcement'
import announcementUpload from '~/components/App/Loader/steps/announcementUpload'
import gallery from '~/components/App/Loader/steps/gallery'
import galleryUpload from '~/components/App/Loader/steps/galleryUpload'
import login from '~/components/App/Loader/steps/login'
import logout from '~/components/App/Loader/steps/logout'
import none from '~/components/App/Loader/steps/none'
import test from '~/components/App/Loader/steps/test'

export interface Step {
  text: string // Display text for the step
  afterText?: string // Text to show after step completion
  errorText?: string // Text to show after step completion, failed
  async?: boolean // If true, waits for external trigger to proceed
  duration?: number // Duration in ms before proceeding (default: 2000)
  action?: () => void // Function to execute when step is active
}

export const useLoaderStore = defineStore('Loader', () => {
  // start the process
  const startAsyncLoading = ref<number>(0)

  // set the value of steps
  const asyncLoadingSteps = ref<Step[]>([])

  const loaderIsComplete = ref<number>(0)

  function processStep(step: string = 'none') {
    if (step == 'test') asyncLoadingSteps.value = test()
    if (step == 'none') asyncLoadingSteps.value = none()
    if (step == 'login') asyncLoadingSteps.value = login()
    if (step == 'logout') asyncLoadingSteps.value = logout()
    if (step == 'gallery') asyncLoadingSteps.value = gallery()
    if (step == 'galleryUpload') asyncLoadingSteps.value = galleryUpload()
    if (step == 'announcement') asyncLoadingSteps.value = announcement()
    if (step == 'announcementUpload')
      asyncLoadingSteps.value = announcementUpload()

    ++startAsyncLoading.value
  }

  async function setDoneToTheOpenLoader(errorText?: 'failed') {
    await nextTick
    if (isArray(asyncLoadingSteps.value)) {
      for (const asyncLoadingStep of asyncLoadingSteps.value) {
        if (
          Object.hasOwn(asyncLoadingStep, 'async') &&
          asyncLoadingStep.async
        ) {
          if (errorText === 'failed') {
            asyncLoadingStep.afterText = asyncLoadingStep.errorText
          }

          asyncLoadingStep.async = false
          break
        }
      }
    }
  }

  return {
    startAsyncLoading,
    processStep,
    setDoneToTheOpenLoader,
    asyncLoadingSteps,
    loaderIsComplete,
  }
})
