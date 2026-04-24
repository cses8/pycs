export default () => [
  {
    text: 'Logging In',
    async: true,
    afterText: 'Logged In',
    errorText: 'Login Failed',
  },
  {
    text: 'Done',
    duration: 1500,
    action: () => ++useLoaderStore().loaderIsComplete,
  },
]
