<template>
  <UiMultiStepLoader
    :steps="asyncLoadingSteps"
    :loading="uiState.isAfterTextLoading"
    :prevent-close="true"
    @state-change="handleStateChange"
    @complete="handleComplete"
    @close="uiState.closeAsync"
    :defaultDuration="300"
    class="!z-[9999]"
  />
</template>

<script setup lang="ts">
import type { Step } from '~/stores/app/loaderStore'

const loaderStore = useLoaderStore()

watch(
  () => loaderStore.startAsyncLoading,
  () => {
    startAsyncLoading()
  }
)

watch(
  () => loaderStore.loaderIsComplete,
  () => {
    handleAsyncLoadingComplete()
  }
)

const uiState = reactive({
  isSimpleLoading: false,
  isAfterTextLoading: false,
  closeSimple: () => {
    uiState.isSimpleLoading = false
  },
  closeAsync: () => {
    uiState.isAfterTextLoading = false
  },
})

// Async loading steps configuration
const asyncLoadingSteps = computedAsync<Step[]>(
  async () => loaderStore.asyncLoadingSteps,
  []
)

function handleAsyncLoadingComplete() {
  uiState.isAfterTextLoading = false
}

// Event handlers
function handleStateChange(state: number) {
  // Handle Loading State Change
}

function handleComplete() {
  // Handle Loading Complete
}

async function startAsyncLoading() {
  // Reset states
  uiState.isAfterTextLoading = true
}
</script>
