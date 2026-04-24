// composables/usePostFetch.ts

export default async function usePostFetch<T>(
  endpoint: string, // API endpoint
  body: Record<string, unknown>, // Request body
  params?: Record<string, string> // Query parameters
): Promise<SuccessResponseType<T> | ErrorResponseType> {
  // Access the runtime config
  const config = useRuntimeConfig()
  const backendBaseUrl = config.public.backendBase // This will be '/api'

  /**
   * Cleans up duplicate slashes in a URL path, specifically avoiding modification of the protocol slashes (e.g., "https://").
   * @param urlString The URL string to clean.
   * @returns The cleaned URL string.
   */
  const cleanDuplicateSlashes = (urlString: string): string => {
    // Use replace with a regular expression
    // It looks for a slash (/) preceded by either a colon (:) or another slash (/)
    // and replaces multiple subsequent slashes with a single one.
    // This avoids touching the protocol slashes like https://
    return urlString.replace(/([^:]\/)\/+/g, '$1')
  }

  const resolvedFullPath = cleanDuplicateSlashes(
    `${backendBaseUrl}/${endpoint}`
  )

  // Make a POST request to the server
  const response = await $fetch(
    resolvedFullPath,
    cleanObject({
      method: 'POST',
      body: body,
      query: params,
      headers: bearerToken().length
        ? null
        : {
            Authorization: `Bearer ${bearerToken()}`,
          },
    })
  ).catch(error => error.data)
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
