// composables/useGetFetch.ts

export default async function useSanctumPost<T>(
  endpoint: string, // API endpoint
  operation: 'update' | 'create' | 'upload' | 'delete',
  payload: Record<string, unknown>,
  formData?: FormData // Query parameters
) {
  let finalEndpoint = endpoint
  let finalMethod: 'POST' | 'PUT' | 'DELETE' = 'POST'
  let postData: any = payload

  if (['update'].includes(operation)) {
    finalEndpoint = `${endpoint}/${payload.id}`
    finalMethod = 'PUT'
  }

  if (['delete'].includes(operation)) {
    finalEndpoint = `${endpoint}/${payload.id}`
    finalMethod = 'DELETE'
  }

  if (['upload'].includes(operation)) {
    finalEndpoint = `${endpoint}/${payload.id}`
    finalMethod = 'POST'
    postData = formData
  }

  // { data, status, error, refresh }
  const response = await useSanctumFetch<T>(finalEndpoint, {
    method: finalMethod,
    body: postData,
  })

  return response
}
