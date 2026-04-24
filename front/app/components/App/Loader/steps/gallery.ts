export default () => [
  {
    text: 'Preparing gallery data',
    async: true,
    afterText: 'Data prepared',
    errorText: 'Failed to prepare data',
  },
  {
    text: 'Saving gallery data',
    async: true,
    afterText: 'Data saved successfully',
    errorText: 'Save failed',
  },
  {
    text: 'Done',
    duration: 1500,
    action: () => ++useLoaderStore().loaderIsComplete,
  },
]
