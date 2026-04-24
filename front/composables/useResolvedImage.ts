// composables/useResolvedImage.ts

export default function useResolvedImage(
  endpoint: string // API endpoint
): string {
  // Access the runtime config
  const config = useRuntimeConfig()
  const backendBaseUrl = config.public.backendBase

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

  const resolveImage = cleanDuplicateSlashes(`${backendBaseUrl}/${endpoint}`)

  return resolveImage as string
}
