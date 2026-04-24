export default () => [
  {
    text: 'Preparing files for upload',
    async: true,
    afterText: 'Files ready for upload',
    errorText: 'Failed to prepare files',
  },
  {
    text: 'Uploading files',
    async: true,
    afterText: 'Files uploaded successfully',
    errorText: 'Upload failed',
  },
  {
    text: 'Done',
    duration: 1500,
    action: () => ++useLoaderStore().loaderIsComplete, // Assuming the same store pattern
  },
]
