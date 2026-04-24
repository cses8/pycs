import { defineStore } from 'pinia'

type ToastType = {
	severity?: string
	summary: string
	detail: string | string[]
	life: number
}

export const useNotificationStore = defineStore('Notification', () => {
  const showNotification: Ref<ToastType> = ref({
    severity: 'info',
    summary: '',
    detail: '',
    life: 5000,
  })

  const success = (options: ToastType) => {
		const {
			severity = 'success',
			summary = '',
			detail = '',
			life = 5000,
		} = options;

    showNotification.value = { severity, summary, detail, life }
  }

  const error = (options: ToastType) => {
		const {
			severity = 'error',
			summary = 'Failed',
			detail = 'Something went wrong!',
			life = 5000,
		} = options;

    showNotification.value = { severity, summary, detail, life }
  }

  const warn = ({
    severity = 'warning',
    summary = 'Notice',
    detail = 'Error unknown',
    life = 5000,
  }: ToastType) => {
    showNotification.value = { severity, summary, detail, life }
  }

  return {
    showNotification,

    success,
    error,
    warn,
  }
})
