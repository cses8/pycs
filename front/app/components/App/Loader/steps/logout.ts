export default () => [
  {
    text: 'Logging Out',
    async: true,
    afterText: 'Logged Out',
  },
  {
    text: 'Done',
    duration: 1500,
    action: () => ++useLoaderStore().loaderIsComplete,
  },
]
