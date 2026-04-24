<template>
  <Dialog v-model:visible="visible" :closeOnEscape="false" class="!w-[80rem]">
    <template #header>
      <div
        class="text-blue-900 dark:text-blue-500 text-3xl font-semibold w-full text-center py-5"
      >
        Announcement
      </div>
    </template>
    <div class="card">
      <Button
        class="bg-green-50 text-green-600 my-1 border-green-600"
        size="small"
        @click="addAnnouncement()"
      >
        Add
      </Button>
      <DataTable
        :value="announcements"
        showGridlines
        tableStyle="min-width: 50rem"
      >
        <Column field="image" header="Image">
          <template #body="{ data, index }">
            <Image
              :src="apiUrl(`/storage/announcements/${data.id}/${data.id}.webp`)"
              width="100px"
              preview
            />
          </template>
        </Column>
        <Column field="title" header="Title"></Column>
        <Column field="description" header="Description">
          <template #body="{ data, index }">
            <div v-html="data.description" />
          </template>
        </Column>
        <Column field="start" header="Start"></Column>
        <Column field="end" header="End"></Column>
        <Column field="action" header="Action">
          <template #body="{ data, index }">
            <div>
              <Button
                class="bg-orange-50 text-orange-600 my-1 border-orange-600"
                size="small"
                @click="editAnnouncement(data)"
              >
                edit
              </Button>
            </div>
            <div>
              <Button
                class="bg-red-50 text-red-600 my-1 border-red-600"
                size="small"
                @click="deleteAnnouncement(data)"
              >
                delete
              </Button>
            </div>

            <div>
              <Button
                class="bg-green-50 text-green-600 my-1 border-green-600"
                size="small"
                @click="uploadAnnouncement(data)"
              >
                upload
              </Button>
            </div>
          </template>
        </Column>
      </DataTable>
    </div>

    <LazyFormAnnouncement
      v-model:visible="showAnnouncementForm"
      v-model:operation="announcementFormOperation"
      v-model:announcement="selectedAnnouncement"
      @onFormSuccess="() => announcementsInit++"
    />
    <LazyFormAnnouncementUpload
      v-model:visible="showAnnouncementUploadForm"
      v-model:announcement="selectedAnnouncement"
      v-model:operation="announcementFormOperation"
      @onFormSuccess="() => announcementsInit++"
    />
  </Dialog>
</template>

<script setup lang="ts">
import { ANNOUNCEMENT } from '~/constant/Announcement'

const visible = defineModel('visible', { default: false })

const announcementsInit = ref<number>(0)
const announcements = ref<Announcement[]>([])
const selectedAnnouncement: Ref = ref()
const showAnnouncementForm = ref<boolean>(false)
const showAnnouncementUploadForm = ref<boolean>(false)
const announcementFormOperation: Ref = ref('update')
async function fetchAnnouncement() {
  const response = await useGetFetch<Announcement[]>('api/announcements')

  if (Array.isArray(response)) {
    announcements.value = response
  }
}

function editAnnouncement(announcement: Announcement) {
  selectedAnnouncement.value = announcement
  announcementFormOperation.value = 'update'
  showAnnouncementForm.value = true
}

function deleteAnnouncement(announcement: Announcement) {
  selectedAnnouncement.value = announcement
  announcementFormOperation.value = 'delete'
  showAnnouncementForm.value = true
}

function addAnnouncement() {
  selectedAnnouncement.value = ANNOUNCEMENT
  announcementFormOperation.value = 'create'
  showAnnouncementForm.value = true
}

function uploadAnnouncement(announcement: Announcement) {
  selectedAnnouncement.value = announcement
  announcementFormOperation.value = 'upload'
  showAnnouncementUploadForm.value = true
}

onMounted(() => {
  fetchAnnouncement()
})
watch(visible, val => {
  if (!val) {
    location.reload()
  } else {
    fetchAnnouncement()
  }
})

watch(announcementsInit, () => {
  fetchAnnouncement()
})
</script>
