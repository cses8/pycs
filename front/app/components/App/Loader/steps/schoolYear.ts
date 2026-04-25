export default () => [
  {
    text: 'Preparing school year data',
    async: true,
    afterText: 'Data prepared',
    errorText: 'Failed to prepare data',
  },
  {
    text: 'Saving school year data',
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
