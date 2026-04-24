// composables/usePutFetch.ts

export default async function usePutFetch<T>(
  url: string, // API endpoint
  body: Record<string, unknown>, // Request body
  params?: Record<string, string> // Query parameters
): Promise<SuccessResponseType<T> | ErrorResponseType> {
  // Make a PUT request to the server
  const response = await $fetch(url, {
    method: 'PUT',
    body: body,
    query: params,
    headers: {
      Authorization: `Bearer ${bearerToken()}`,
    },
  }).catch(error => error.data)
  // 200
  await handleSuccess(response as SuccessResponseType<T>)

  // 401 Unauthorized
  await handleUnauthorized(response as ErrorResponseType)
  // 500 Internal Server Error
  await handleInternalServerError(response as ErrorResponseType)
  // 400 Bad Request
  await handleBadRequest(response)
  // Return the response
  return response as SuccessResponseType<T> | ErrorResponseType
}

// Function to handle 500 Internal Server Error
function handleInternalServerError(response: ErrorResponseType): void {
  const NotificationStore = useNotificationStore()

  // If the response is 500
  if (response.httpCode === 500) {
    // Log the error message
    console.error(`${response.message}: ${response.data}`)
    NotificationStore.error({
      severity: 'error',
      summary: response.message,
      detail: response.data,
      life: 5000,
    })
  }
}

// Function to handle 401 Unauthorized
async function handleUnauthorized(response: ErrorResponseType): Promise<void> {
  const NotificationStore = useNotificationStore()

  // If the response is 401
  if (response.httpCode === 401) {
    NotificationStore.error({
      severity: 'error',
      summary: 'Unauthorized',
      detail: 'Redirecting to login page...',
      life: 5000,
    })

    // Clear the local storage
    await new Promise<void>(resolve => {
      localStorage.clear()
      resolve()
    })
    // Redirect to login page
    useRouter().push('/login')
  }
}

// Function to handle 400
async function handleBadRequest(response: any): Promise<void> {
  const NotificationStore = useNotificationStore()

  // If the response is 400
  if (response.httpCode === 400 || response?.data?.httpCode === 400) {
    // if the response is error
    NotificationStore.error({
      summary: 'Error',
      detail: response?.data?.details,
      life: 5000,
    })
  }
}

// Function to handle 200
async function handleSuccess(response: any): Promise<void> {
  const NotificationStore = useNotificationStore()

  // If the response is 200, and true 200
  if (response.httpCode === 200 && response?.data?.httpCode == undefined) {
    // if the response is error
    NotificationStore.success({
      summary: 'Ok',
      detail: response?.data?.details,
      life: 5000,
    })
  }

  // If the response is 200, but 401
  if (response.httpCode === 200 && response?.data?.httpCode == 401) {
    // if the response is error
    NotificationStore.error({
      summary: 'Error',
      detail: response?.data?.details,
      life: 5000,
    })
  }
}
