export default () => [
  {
    text: 'Nothing',
    duration: 1500,
  },
  {
    text: 'Done',
    duration: 1500,
    action: () => ++useLoaderStore().loaderIsComplete,
  },
]
