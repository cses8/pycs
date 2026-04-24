export default (endpoint: string = '') =>
  `${useRuntimeConfig().public.backendBase}${endpoint}`
