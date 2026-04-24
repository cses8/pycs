export default () => [
  {
    text: 'Testing',
    duration: 9999999999,
  },
  {
    text: 'Done',
    duration: 1500,
    action: () => ++useLoaderStore().loaderIsComplete,
  },
]
