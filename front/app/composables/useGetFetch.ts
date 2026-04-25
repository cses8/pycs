// composables/useGetFetch.ts

export default async function useGetFetch<T>(
  endpoint: string, // API endpoint
  params?: Record<string, unknown> // Query parameters
): Promise<SuccessResponseType<T> | ErrorResponseType> {
  // Access the runtime config
  const config = useRuntimeConfig()
  const backendBaseUrl = config.public.backendBase // This will be '/api' (proxied) in dev, 'https://api.pycs.school' in build

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

  // Make a GET request to the server
  const response = await $fetch(resolvedFullPath, {
    method: 'GET',
    query: params,
    headers: {
      Authorization: `Bearer ${bearerToken()}`,
    },
  }).catch(error => error.data)
  // 401 Unauthorized
  await handleUnauthorized(response as ErrorResponseType)
  // 500 Internal Server Error
  await handleInternalServerError(response as ErrorResponseType)
  // Return the response
  return response as SuccessResponseType<T>
}

// Function to handle 401 Unauthorized
async function handleUnauthorized(response: ErrorResponseType) {
  // If the response is 401
  if (response.httpCode === 401) {
    // Clear localStorage before redirecting
    await new Promise<void>(resolve => {
      localStorage.clear()
      resolve()
    })
    // Redirect to login
    useRouter().push('/login')
  }
}

// Function to handle 500 Internal Server Error
async function handleInternalServerError(response: ErrorResponseType) {
  if (response.httpCode === 500) {
    // Handle 500 Internal Server Error (e.g., display a generic error message)
    // For example, you could use:
    // showErrorNotification('An internal server error occurred.');
    throw createError({
      statusCode: 500,
      statusMessage: response.message,
      message: response.data,
    })
  }
}
