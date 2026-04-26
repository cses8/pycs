<template>
  <main class="overflow-x-hidden bg-slate-50 text-slate-950 dark:bg-slate-950 dark:text-white">
    <section
      class="relative isolate overflow-hidden bg-cover bg-center"
      style="background-image: url('/images/banner1.webp')"
    >
      <div class="absolute inset-0 bg-slate-950/72" />
      <div
        class="absolute inset-0 bg-[linear-gradient(90deg,rgba(15,23,42,0.96)_0%,rgba(30,64,175,0.76)_54%,rgba(15,23,42,0.88)_100%)]"
      />

      <div class="relative mx-auto max-w-7xl px-5 pb-16 pt-28 sm:px-8 sm:pt-24 lg:px-10 lg:pb-20 lg:pt-28">
        <div class="grid items-end gap-8 lg:grid-cols-[minmax(0,1fr)_380px]">
          <div class="min-w-0 max-w-3xl">
            <div class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-100">
              <Icon name="solar:document-text-linear" class="size-4" />
              School Updates
            </div>
            <h1 class="text-4xl font-black leading-tight text-white sm:text-5xl lg:text-6xl">
              News, announcements, stories, and events from PYCS
            </h1>
            <p class="mt-5 max-w-2xl text-base leading-7 text-blue-50 sm:text-lg">
              A central place for official campus updates, community stories, scheduled programs, and timely information for students and families.
            </p>

            <div class="mt-7 flex flex-wrap gap-2">
              <button
                v-for="type in updateTypes"
                :key="type.value"
                type="button"
                :class="typeButtonClass(type.value)"
                @click="filters.type = type.value"
              >
                <Icon :name="type.icon" class="size-4" />
                {{ type.label }}
              </button>
            </div>
          </div>

          <aside class="rounded-2xl border border-white/15 bg-white/10 p-5 text-white shadow-2xl shadow-slate-950/30 backdrop-blur">
            <div class="flex items-center gap-3">
              <div class="flex size-11 items-center justify-center rounded-xl bg-white text-blue-900">
                <Icon name="solar:radio-linear" class="size-5" />
              </div>
              <div class="min-w-0">
                <p class="text-xs font-semibold uppercase text-blue-100">
                  Latest Feed
                </p>
                <p class="text-3xl font-black leading-none text-white">
                  {{ totalRecords }}
                </p>
                <p class="mt-1 text-sm text-blue-50">
                  {{ activeType.label }} updates
                </p>
              </div>
            </div>
            <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
              <div
                v-for="type in visibleTypeCounts"
                :key="type.value"
                class="rounded-xl border border-white/15 bg-white/10 p-3"
              >
                <div class="flex items-center justify-between gap-2">
                  <p class="text-lg font-black text-white">{{ type.count }}</p>
                  <Icon :name="type.icon" class="size-4 text-blue-100" />
                </div>
                <p class="mt-1 text-xs font-semibold uppercase text-blue-100">
                  {{ type.label }}
                </p>
              </div>
            </div>
            <div class="mt-5 flex items-center gap-2 rounded-xl border border-white/15 bg-slate-950/20 p-3 text-sm leading-6 text-blue-50">
              <Icon name="solar:clock-circle-linear" class="size-5 shrink-0 text-blue-100" />
              <span>Published items refresh automatically while this page is open.</span>
            </div>
            <Button
              v-if="isAuthenticated"
              icon="pi pi-plus"
              label="Add update"
              class="mt-5 w-full !border-white/20 !bg-white !text-blue-900 hover:!bg-blue-50"
              @click="openCreateForm"
            />
          </aside>
        </div>
      </div>
    </section>

    <section id="school-updates-feed" class="mx-auto max-w-7xl px-5 py-8 sm:px-8 lg:px-10 lg:py-10">
      <div class="grid gap-5 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="min-w-0 space-y-5">
          <article
            v-if="featuredUpdate"
            class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5"
          >
            <div class="grid lg:grid-cols-[minmax(0,1fr)_25rem]">
              <button
                type="button"
                class="relative min-h-80 overflow-hidden bg-slate-200 text-left dark:bg-slate-800"
                @click="openReader(featuredUpdate)"
              >
                <img
                  :src="featuredImage(featuredUpdate)"
                  :alt="featuredUpdate.title"
                  class="size-full object-cover transition duration-500 hover:scale-105"
                  @error="replaceWithFallbackImage"
                >
                <div class="absolute inset-x-0 bottom-0 h-28 bg-gradient-to-t from-slate-950/70 to-transparent" />
                <span class="absolute left-4 top-4 inline-flex items-center gap-2 rounded-full bg-white/95 px-3 py-1.5 text-xs font-bold uppercase text-blue-800 shadow-sm">
                  <Icon :name="typeIcon(featuredUpdate.type)" class="size-4" />
                  Featured {{ typeLabel(featuredUpdate.type) }}
                </span>
              </button>
              <div class="flex flex-col justify-between p-6">
                <div>
                  <div class="flex flex-wrap gap-2">
                    <Tag :value="featuredUpdate.category || 'General'" severity="info" class="!rounded-full" />
                    <Tag :value="statusLabel(featuredUpdate)" :severity="statusSeverity(featuredUpdate)" class="!rounded-full" />
                  </div>
                  <h2 class="mt-4 text-3xl font-black leading-tight text-slate-950 dark:text-white">
                    {{ featuredUpdate.title }}
                  </h2>
                  <p class="mt-3 text-sm font-semibold text-blue-700 dark:text-blue-300">
                    {{ primaryDate(featuredUpdate) }}
                  </p>
                  <p class="mt-4 line-clamp-4 text-sm leading-6 text-slate-600 dark:text-slate-300">
                    {{ featuredSummary(featuredUpdate) }}
                  </p>
                </div>
                <div class="mt-6 flex flex-wrap items-center gap-2">
                  <Button label="Read update" icon="pi pi-arrow-right" icon-pos="right" @click="openReader(featuredUpdate)" />
                  <Button
                    v-if="isAuthenticated"
                    icon="pi pi-pencil"
                    label="Edit"
                    severity="secondary"
                    outlined
                    @click="openEditForm(featuredUpdate)"
                  />
                </div>
              </div>
            </div>
          </article>

          <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5 sm:p-5">
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
              <div>
                <p class="text-xs font-semibold uppercase text-blue-700 dark:text-blue-300">
                  Browse Feed
                </p>
                <h2 class="text-2xl font-black text-slate-950 dark:text-white">
                  Latest school updates
                </h2>
              </div>
              <Button
                v-if="hasActiveFilters"
                label="Clear filters"
                icon="pi pi-filter-slash"
                severity="secondary"
                outlined
                size="small"
                @click="clearFilters"
              />
            </div>
            <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_12rem_12rem]">
              <span class="relative">
                <i class="pi pi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                <InputText
                  v-model="filters.search"
                  placeholder="Search updates"
                  class="w-full !rounded-lg !pl-10"
                  aria-label="Search school updates"
                />
              </span>
              <Select
                v-model="filters.category"
                :options="categoryOptions"
                option-label="label"
                option-value="value"
                class="w-full"
                aria-label="Filter by category"
              />
              <Select
                v-if="isAuthenticated"
                v-model="filters.status"
                :options="statusOptions"
                option-label="label"
                option-value="value"
                class="w-full"
                aria-label="Filter by status"
              />
              <Select
                v-else
                v-model="filters.tag"
                :options="tagOptions"
                option-label="label"
                option-value="value"
                class="w-full"
                aria-label="Filter by tag"
              />
            </div>
            <div class="mt-4 flex gap-2 overflow-x-auto pb-1">
              <button
                v-for="tag in tagOptions"
                :key="tag.value"
                type="button"
                :class="tagButtonClass(tag.value)"
                @click="filters.tag = tag.value"
              >
                {{ tag.label }}
              </button>
            </div>
          </div>

          <div v-if="loading" class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <div
              v-for="index in 6"
              :key="index"
              class="h-96 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5"
            >
              <Skeleton height="13rem" class="!rounded-lg" />
              <Skeleton height="1.5rem" class="!mt-5" />
              <Skeleton height="1rem" class="!mt-3" />
              <Skeleton height="4rem" class="!mt-4" />
            </div>
          </div>

          <div
            v-else-if="errorMessage"
            class="rounded-xl border border-red-200 bg-red-50 p-8 text-center text-red-900 dark:border-red-400/30 dark:bg-red-950/30 dark:text-red-100"
          >
            <Icon name="solar:danger-triangle-linear" class="mx-auto size-10" />
            <h2 class="mt-4 text-xl font-black">Updates could not load</h2>
            <p class="mt-2 text-sm">{{ errorMessage }}</p>
            <Button label="Try again" icon="pi pi-refresh" class="mt-5" severity="danger" @click="fetchUpdates" />
          </div>

          <div v-else-if="updates.length" class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            <article
              v-for="update in updates"
              :key="update.id"
              class="group flex min-h-[26rem] flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-lg dark:border-white/10 dark:bg-white/5 dark:hover:border-blue-300/40"
            >
              <button
                type="button"
                class="relative h-52 overflow-hidden bg-slate-200 text-left dark:bg-slate-800"
                @click="openReader(update)"
              >
                <img
                  :src="featuredImage(update)"
                  :alt="update.title"
                  class="size-full object-cover transition duration-500 group-hover:scale-105"
                  @error="replaceWithFallbackImage"
                >
                <span class="absolute left-3 top-3 inline-flex items-center gap-2 rounded-full bg-white/95 px-3 py-1 text-xs font-bold uppercase text-slate-700 shadow-sm">
                  <Icon :name="typeIcon(update.type)" class="size-4 text-blue-700" />
                  {{ typeLabel(update.type) }}
                </span>
              </button>

              <div class="flex flex-1 flex-col p-5">
                <div class="flex flex-wrap gap-2">
                  <Tag :value="update.category || 'General'" severity="info" class="!rounded-full" />
                  <Tag
                    v-if="isAuthenticated"
                    :value="statusLabel(update)"
                    :severity="statusSeverity(update)"
                    class="!rounded-full"
                  />
                </div>
                <h3 class="mt-4 line-clamp-2 text-xl font-black leading-6 text-slate-950 dark:text-white">
                  {{ update.title }}
                </h3>
                <p class="mt-2 text-sm font-semibold text-blue-700 dark:text-blue-300">
                  {{ primaryDate(update) }}
                </p>
                <p class="mt-3 line-clamp-3 text-sm leading-6 text-slate-600 dark:text-slate-300">
                  {{ featuredSummary(update) }}
                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                  <span
                    v-for="tag in update.tags.slice(0, 3)"
                    :key="tag"
                    class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:bg-white/10 dark:text-slate-300"
                  >
                    #{{ tag }}
                  </span>
                </div>
                <div class="mt-auto flex items-center justify-between gap-2 pt-5">
                  <Button label="Read" icon="pi pi-arrow-right" icon-pos="right" size="small" text @click="openReader(update)" />
                  <div v-if="isAuthenticated" class="flex gap-1">
                    <Button icon="pi pi-pencil" rounded text aria-label="Edit update" @click="openEditForm(update)" />
                    <Button icon="pi pi-trash" rounded text severity="danger" aria-label="Delete update" @click="deleteUpdate(update)" />
                  </div>
                </div>
              </div>
            </article>
          </div>

          <div
            v-else
            class="overflow-hidden rounded-2xl border border-dashed border-slate-300 bg-white text-center shadow-sm dark:border-white/15 dark:bg-white/5"
          >
            <div class="bg-slate-950 px-6 py-8 text-white">
              <Icon name="solar:document-text-linear" class="mx-auto size-11 text-blue-100" />
              <h2 class="mt-4 text-2xl font-black">
                No updates found
              </h2>
              <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-blue-50">
                Published school updates will appear here when they match the selected filters.
              </p>
            </div>
            <div class="flex flex-wrap justify-center gap-2 p-5">
              <Button
                v-if="isAuthenticated"
                label="Add first update"
                icon="pi pi-plus"
                @click="openCreateForm"
              />
              <Button
                v-if="hasActiveFilters"
                label="Clear filters"
                icon="pi pi-filter-slash"
                severity="secondary"
                outlined
                @click="clearFilters"
              />
            </div>
          </div>
        </div>

        <aside class="space-y-5">
          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="flex items-center gap-2">
              <Icon name="solar:folder-with-files-linear" class="size-5 text-blue-700 dark:text-blue-300" />
              <h2 class="text-base font-black text-slate-950 dark:text-white">
                Categories
              </h2>
            </div>
            <div class="mt-4 space-y-2">
              <button
                v-for="category in categoryOptions"
                :key="category.value"
                type="button"
                :class="sideFilterClass(filters.category === category.value)"
                @click="filters.category = category.value"
              >
                <span>{{ category.label }}</span>
                <Icon name="solar:alt-arrow-right-linear" class="size-4" />
              </button>
            </div>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-white/5">
            <div class="mb-4 flex items-center justify-between gap-3">
              <div>
                <p class="text-xs font-semibold uppercase text-blue-700 dark:text-blue-300">
                  Next Up
                </p>
                <h2 class="text-xl font-black text-slate-950 dark:text-white">
                  Upcoming Events
                </h2>
              </div>
              <Icon name="solar:calendar-date-linear" class="size-5 text-slate-400" />
            </div>
            <div v-if="upcomingEvents.length" class="space-y-3">
              <button
                v-for="event in upcomingEvents"
                :key="event.id"
                type="button"
                class="w-full rounded-xl border border-slate-200 bg-slate-50 p-3 text-left shadow-sm transition hover:border-blue-200 hover:bg-blue-50 dark:border-white/10 dark:bg-slate-950/50 dark:hover:border-blue-300/40 dark:hover:bg-blue-400/10"
                @click="openReader(event)"
              >
                <p class="text-xs font-bold uppercase text-blue-700 dark:text-blue-300">
                  {{ eventDate(event) }}
                </p>
                <p class="mt-1 line-clamp-2 text-sm font-black text-slate-950 dark:text-white">
                  {{ event.title }}
                </p>
              </button>
            </div>
            <p v-else class="mt-4 text-sm leading-6 text-slate-600 dark:text-slate-300">
              No upcoming events match the current feed.
            </p>
          </div>
        </aside>
      </div>
    </section>

    <Dialog
      v-model:visible="showReader"
      modal
      :style="{ width: 'min(94vw, 56rem)' }"
      class="school-update-dialog"
      pt:mask:class="backdrop-blur-sm !bg-slate-950/45"
    >
      <template #header>
        <div class="min-w-0">
          <p class="text-xs font-bold uppercase text-blue-700 dark:text-blue-300">
            {{ readerUpdate ? typeLabel(readerUpdate.type) : 'School Update' }}
          </p>
          <h2 class="mt-1 line-clamp-2 text-xl font-black text-slate-950 dark:text-white">
            {{ readerUpdate?.title }}
          </h2>
        </div>
      </template>
      <article v-if="readerUpdate" class="space-y-5">
        <img
          :src="featuredImage(readerUpdate)"
          :alt="readerUpdate.title"
          class="max-h-[24rem] w-full rounded-lg object-cover"
          @error="replaceWithFallbackImage"
        >
        <div class="flex flex-wrap gap-2">
          <Tag :value="readerUpdate.category || 'General'" severity="info" class="!rounded-full" />
          <Tag :value="primaryDate(readerUpdate)" severity="secondary" class="!rounded-full" />
        </div>
        <AppSafeHtml class="prose max-w-none prose-slate dark:prose-invert" :html="readerUpdate.content" />
      </article>
    </Dialog>

    <Dialog
      v-model:visible="showForm"
      modal
      :close-on-escape="false"
      :draggable="false"
      :style="{ width: 'min(96vw, 58rem)' }"
      pt:root:class="!border-0 !bg-transparent !shadow-none"
      pt:mask:class="backdrop-blur-sm !bg-slate-950/45"
      aria-labelledby="school-update-form-title"
    >
      <template #container="{ closeCallback }">
        <section
          class="pycs-modal-shell rounded-xl border border-slate-200 bg-white shadow-2xl shadow-slate-950/20 dark:border-surface-700 dark:bg-surface-950"
        >
          <header
            class="pycs-modal-header flex items-start justify-between gap-4 border-b border-slate-200 bg-slate-950 px-6 py-5 text-white dark:border-surface-800"
          >
            <div class="flex min-w-0 gap-4">
              <div
                class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-white text-blue-800"
              >
                <Icon :name="form.id ? 'solar:pen-2-linear' : 'solar:add-circle-linear'" class="size-6" />
              </div>
              <div class="min-w-0">
                <p class="text-xs font-semibold uppercase text-blue-200">
                  School Update
                </p>
                <h2
                  id="school-update-form-title"
                  class="mt-1 truncate text-xl font-bold leading-tight text-white sm:text-2xl"
                >
                  {{ form.id ? 'Edit school update' : 'Create school update' }}
                </h2>
                <p class="mt-2 text-sm leading-6 text-slate-300">
                  Set the type, timing, public content, and image used in the school updates feed.
                </p>
              </div>
            </div>

            <Button
              icon="pi pi-times"
              rounded
              text
              severity="secondary"
              aria-label="Close school update form"
              class="!-mr-2 !-mt-2 !h-10 !w-10 !text-slate-200 hover:!bg-white/10 hover:!text-white"
              @click="closeCallback"
            />
          </header>

          <div class="pycs-modal-body bg-slate-50 p-5 dark:bg-surface-900">
            <div
              class="mb-4 inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-bold uppercase"
              :class="
                form.id
                  ? 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-900 dark:bg-amber-950/40 dark:text-amber-300'
                  : 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-300'
              "
            >
              <span
                class="size-2 rounded-full"
                :class="form.id ? 'bg-amber-500' : 'bg-emerald-500'"
              />
              {{ form.id ? 'Update' : 'New' }}
            </div>

            <form @submit.prevent="saveUpdate">
              <div
                class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm dark:border-surface-700 dark:bg-surface-950"
              >
                <div class="grid grid-cols-12 gap-4">
                  <div class="col-span-12 flex flex-col gap-2">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="school-update-title"
                    >
                      Title
                    </label>
                    <InputText
                      id="school-update-title"
                      v-model="form.title"
                      class="w-full !rounded-lg !border-slate-300 !py-3 dark:!border-surface-700 dark:!bg-surface-900"
                      :invalid="Boolean(formErrors.title)"
                    />
                    <Message v-if="formErrors.title" severity="error" size="small" variant="simple">
                      {{ formErrors.title }}
                    </Message>
                  </div>

                  <div class="col-span-12 flex flex-col gap-2 md:col-span-6">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="school-update-type"
                    >
                      Type
                    </label>
                    <Select
                      id="school-update-type"
                      v-model="form.type"
                      :options="updateTypes.filter(type => type.value !== 'all')"
                      option-label="label"
                      option-value="value"
                      class="w-full"
                    />
                  </div>

                  <div class="col-span-12 flex flex-col gap-2 md:col-span-6">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="school-update-status"
                    >
                      Status
                    </label>
                    <Select
                      id="school-update-status"
                      v-model="form.status"
                      :options="statusOptions.filter(status => status.value !== 'all')"
                      option-label="label"
                      option-value="value"
                      class="w-full"
                    />
                  </div>

                  <div class="col-span-12 flex flex-col gap-2 md:col-span-6">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="school-update-category"
                    >
                      Category
                    </label>
                    <InputText
                      id="school-update-category"
                      v-model="form.category"
                      class="w-full !rounded-lg !border-slate-300 !py-3 dark:!border-surface-700 dark:!bg-surface-900"
                    />
                  </div>

                  <div class="col-span-12 flex flex-col gap-2 md:col-span-6">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="school-update-tags"
                    >
                      Tags
                    </label>
                    <InputText
                      id="school-update-tags"
                      v-model="tagInput"
                      class="w-full !rounded-lg !border-slate-300 !py-3 dark:!border-surface-700 dark:!bg-surface-900"
                      placeholder="students, events, parents"
                    />
                  </div>

                  <div class="col-span-12 flex flex-col gap-2 md:col-span-6">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="school-update-published"
                    >
                      Publish date
                    </label>
                    <DatePicker
                      id="school-update-published"
                      v-model="form.published_at"
                      show-time
                      hour-format="12"
                      class="w-full"
                    />
                  </div>

                  <div v-if="form.type === 'event'" class="col-span-12 flex flex-col gap-2 md:col-span-6">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="school-update-event-start"
                    >
                      Event starts
                    </label>
                    <DatePicker
                      id="school-update-event-start"
                      v-model="form.event_start_at"
                      show-time
                      hour-format="12"
                      class="w-full"
                      :invalid="Boolean(formErrors.event_start_at)"
                    />
                  </div>

                  <div v-if="form.type === 'event'" class="col-span-12 flex flex-col gap-2 md:col-span-6">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="school-update-event-end"
                    >
                      Event ends
                    </label>
                    <DatePicker
                      id="school-update-event-end"
                      v-model="form.event_end_at"
                      show-time
                      hour-format="12"
                      class="w-full"
                      :invalid="Boolean(formErrors.event_end_at)"
                    />
                    <Message v-if="formErrors.event_end_at" severity="error" size="small" variant="simple">
                      {{ formErrors.event_end_at }}
                    </Message>
                  </div>

                  <div class="col-span-12 flex flex-col gap-2">
                    <label
                      class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                      for="school-update-summary"
                    >
                      Summary
                    </label>
                    <Textarea
                      id="school-update-summary"
                      v-model="form.summary"
                      rows="3"
                      auto-resize
                      class="w-full !rounded-lg !border-slate-300 dark:!border-surface-700 dark:!bg-surface-900"
                    />
                  </div>

                  <div class="col-span-12 flex flex-col gap-2">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                      <label
                        class="text-sm font-semibold text-slate-800 dark:text-surface-100"
                        for="school-update-content"
                      >
                        Content
                      </label>
                      <FileUpload
                        v-if="form.id"
                        mode="basic"
                        name="file"
                        accept="image/*"
                        :auto="true"
                        choose-label="Insert image"
                        :max-file-size="5120000"
                        @select="uploadContentImage"
                      />
                    </div>
                    <Editor
                      id="school-update-content"
                      v-model="form.content"
                      placeholder="Write the school update content with formatting."
                      editor-style="height: 320px"
                      :class="[
                        'pycs-rich-editor',
                        { 'pycs-rich-editor-invalid': formErrors.content },
                      ]"
                    >
                      <template #toolbar>
                        <AppRichEditorToolbar />
                      </template>
                    </Editor>
                    <Message v-if="formErrors.content" severity="error" size="small" variant="simple">
                      {{ formErrors.content }}
                    </Message>
                  </div>

                  <div class="col-span-12 flex flex-col gap-2">
                    <label class="text-sm font-semibold text-slate-800 dark:text-surface-100">
                      Featured image
                    </label>
                    <div
                      class="grid gap-4 rounded-lg border border-slate-200 bg-slate-50 p-4 md:grid-cols-[12rem_minmax(0,1fr)] dark:border-white/10 dark:bg-slate-950/50"
                    >
                      <img
                        :src="formPreviewImage"
                        alt=""
                        class="h-36 w-full rounded-lg object-cover"
                        @error="replaceWithFallbackImage"
                      >
                      <div class="min-w-0">
                        <FileUpload
                          mode="basic"
                          name="file"
                          accept="image/*"
                          choose-label="Choose image"
                          :max-file-size="5120000"
                          @select="onFeaturedImageSelected"
                        />
                        <p class="mt-3 truncate text-sm text-slate-600 dark:text-slate-300">
                          {{ featuredImageFile?.name || form.featured_image || 'No image selected' }}
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="mt-6 grid gap-3 sm:grid-cols-[1fr_auto]">
                  <Button
                    type="button"
                    label="Cancel"
                    icon="pi pi-times"
                    severity="secondary"
                    outlined
                    class="!rounded-lg !py-3"
                    @click="closeCallback"
                  />
                  <Button
                    type="submit"
                    :label="saving ? 'Saving' : 'Save update'"
                    icon="pi pi-save"
                    :loading="saving"
                    class="!rounded-lg !py-3"
                  />
                </div>
              </div>
            </form>
          </div>
        </section>
      </template>
    </Dialog>
  </main>
</template>

<script setup lang="ts">
type SchoolUpdateType = 'news' | 'announcement' | 'blog' | 'event'
type SchoolUpdateStatus = 'draft' | 'scheduled' | 'published' | 'archived'

type SchoolUpdate = {
  id: number
  author_id: number | null
  author_name: string | null
  title: string
  slug: string
  summary: string | null
  content: string
  type: SchoolUpdateType
  category: string | null
  tags: string[]
  status: SchoolUpdateStatus
  published_at: string | null
  event_start_at: string | null
  event_end_at: string | null
  featured_image: string | null
  featured_image_url: string | null
}

type SchoolUpdateForm = Omit<SchoolUpdate, 'slug' | 'author_id' | 'author_name' | 'featured_image_url' | 'published_at' | 'event_start_at' | 'event_end_at'> & {
  published_at: Date | null
  event_start_at: Date | null
  event_end_at: Date | null
  featured_image_url: string | null
}

type SchoolUpdateResponse = {
  data: SchoolUpdate[]
  pagination: {
    curPage: number
    from: number | null
    to: number | null
    perPage: number
    lastPage: number
    total: number
  }
  meta?: {
    categories: string[]
    tags: string[]
  }
}

const { isAuthenticated } = useSanctumAuth()
const dayjs = useDayjs()
const notificationStore = useNotificationStore()
const config = useRuntimeConfig()

definePageMeta({
  layout: 'welcome',
})

useHead({
  title: 'School Updates | Philippine Yuh Chiau School',
})

const fallbackImage = '/images/banner3.webp'
const updates = ref<SchoolUpdate[]>([])
const loading = ref(true)
const saving = ref(false)
const errorMessage = ref('')
const totalRecords = ref(0)
const showForm = ref(false)
const showReader = ref(false)
const readerUpdate = ref<SchoolUpdate | null>(null)
const featuredImageFile = ref<File | null>(null)
const tagInput = ref('')
const formErrors = ref<Record<string, string>>({})
const form = ref<SchoolUpdateForm>(blankForm())
const meta = ref({ categories: [] as string[], tags: [] as string[] })

const filters = reactive({
  search: '',
  type: 'all',
  category: 'all',
  tag: 'all',
  status: 'all',
})

const updateTypes = [
  { label: 'All', value: 'all', icon: 'solar:widget-4-linear' },
  { label: 'News', value: 'news', icon: 'solar:document-text-linear' },
  { label: 'Announcements', value: 'announcement', icon: 'solar:bell-bing-linear' },
  { label: 'Blogs', value: 'blog', icon: 'solar:book-bookmark-linear' },
  { label: 'Events', value: 'event', icon: 'solar:calendar-date-linear' },
]

const statusOptions = [
  { label: 'All statuses', value: 'all' },
  { label: 'Draft', value: 'draft' },
  { label: 'Scheduled', value: 'scheduled' },
  { label: 'Published', value: 'published' },
  { label: 'Archived', value: 'archived' },
]

const categoryOptions = computed(() => [
  { label: 'All categories', value: 'all' },
  ...meta.value.categories.map(category => ({ label: category, value: category })),
])

const tagOptions = computed(() => [
  { label: 'All tags', value: 'all' },
  ...meta.value.tags.map(tag => ({ label: `#${tag}`, value: tag })),
])

const activeType = computed(() => {
  return updateTypes.find(type => type.value === filters.type) ?? { label: 'All', value: 'all', icon: 'solar:widget-4-linear' }
})

const hasActiveFilters = computed(() =>
  Boolean(filters.search.trim()) ||
  filters.type !== 'all' ||
  filters.category !== 'all' ||
  filters.tag !== 'all' ||
  (isAuthenticated.value && filters.status !== 'all')
)

const featuredUpdate = computed(() => updates.value[0] ?? null)
const upcomingEvents = computed(() =>
  updates.value
    .filter(update => update.type === 'event' && update.event_start_at && dayjs(update.event_start_at).isAfter(dayjs().subtract(1, 'day')))
    .slice(0, 4)
)

const visibleTypeCounts = computed(() =>
  updateTypes
    .filter(type => type.value !== 'all')
    .map(type => ({
      ...type,
      count: updates.value.filter(update => update.type === type.value).length,
    }))
)

const initialPublicParams = {
  search: '',
  type: 'all',
  category: 'all',
  tag: 'all',
  status: 'published',
  per_page: 24,
}

const { data: initialPublicUpdates } = await useAsyncData('school-updates-initial', () =>
  $fetch<SchoolUpdateResponse>(`${config.public.backendBase}/api/school-updates`, {
    query: initialPublicParams,
  }),
)

if (initialPublicUpdates.value && isSchoolUpdateResponse(initialPublicUpdates.value)) {
  updates.value = initialPublicUpdates.value.data
  totalRecords.value = initialPublicUpdates.value.pagination.total
  meta.value = {
    categories: initialPublicUpdates.value.meta?.categories ?? [],
    tags: initialPublicUpdates.value.meta?.tags ?? [],
  }
  loading.value = false
}

const formPreviewImage = computed(() => {
  if (featuredImageFile.value) {
    return URL.createObjectURL(featuredImageFile.value)
  }

  return form.value.featured_image_url
    ? resolveMediaUrl(form.value.featured_image_url)
    : featuredImage({ featured_image: form.value.featured_image, featured_image_url: null } as SchoolUpdate)
})

let searchDebounce: number | undefined
let refreshTimer: number | undefined

watch(
  () => ({ ...filters, authenticated: isAuthenticated.value }),
  () => {
    window.clearTimeout(searchDebounce)
    searchDebounce = window.setTimeout(fetchUpdates, 300)
  },
  { deep: true }
)

watch(tagInput, value => {
  form.value.tags = value
    .split(',')
    .map(tag => tag.trim())
    .filter(Boolean)
})

onMounted(() => {
  if (!updates.value.length) {
    fetchUpdates()
  }
  refreshTimer = window.setInterval(fetchUpdates, 60000)
})

onBeforeUnmount(() => {
  window.clearTimeout(searchDebounce)
  window.clearInterval(refreshTimer)
})

function blankForm(): SchoolUpdateForm {
  return {
    id: 0,
    title: '',
    summary: '',
    content: '',
    type: 'news',
    category: '',
    tags: [],
    status: 'published',
    published_at: new Date(),
    event_start_at: null,
    event_end_at: null,
    featured_image: null,
    featured_image_url: null,
  }
}

async function fetchUpdates() {
  loading.value = true
  errorMessage.value = ''

  try {
    const params = {
      search: filters.search,
      type: filters.type,
      category: filters.category,
      tag: filters.tag,
      status: isAuthenticated.value ? filters.status : 'published',
      per_page: 24,
    }
    let response: SchoolUpdateResponse | null = null

    if (isAuthenticated.value) {
      response = await authenticatedFetch<SchoolUpdateResponse>('/api/school-updates/manage', {
        method: 'GET',
        query: params,
      })
    } else {
      const publicResponse = await useGetFetch<SchoolUpdateResponse>('api/school-updates', params)
      response = isSchoolUpdateResponse(publicResponse) ? publicResponse : null
    }

    if (isSchoolUpdateResponse(response)) {
      updates.value = response.data
      totalRecords.value = response.pagination.total
      meta.value = {
        categories: response.meta?.categories ?? [],
        tags: response.meta?.tags ?? [],
      }
    }
  } catch (error) {
    errorMessage.value = error instanceof Error ? error.message : 'Please try again.'
  } finally {
    loading.value = false
  }
}

function isSchoolUpdateResponse(response: unknown): response is SchoolUpdateResponse {
  return (
    typeof response === 'object' &&
    response !== null &&
    Array.isArray((response as SchoolUpdateResponse).data) &&
    typeof (response as SchoolUpdateResponse).pagination?.total === 'number'
  )
}

function openCreateForm() {
  form.value = blankForm()
  tagInput.value = ''
  featuredImageFile.value = null
  formErrors.value = {}
  showForm.value = true
}

function openEditForm(update: SchoolUpdate) {
  form.value = {
    id: update.id,
    title: update.title,
    summary: update.summary,
    content: update.content,
    type: update.type,
    category: update.category,
    tags: [...update.tags],
    status: update.status,
    published_at: parseDate(update.published_at),
    event_start_at: parseDate(update.event_start_at),
    event_end_at: parseDate(update.event_end_at),
    featured_image: update.featured_image,
    featured_image_url: update.featured_image_url,
  }
  tagInput.value = update.tags.join(', ')
  featuredImageFile.value = null
  formErrors.value = {}
  showForm.value = true
}

function openReader(update: SchoolUpdate) {
  readerUpdate.value = update
  showReader.value = true
}

function clearFilters() {
  filters.search = ''
  filters.type = 'all'
  filters.category = 'all'
  filters.tag = 'all'
  filters.status = 'all'
}

async function saveUpdate() {
  formErrors.value = validateForm()

  if (Object.keys(formErrors.value).length) {
    return
  }

  saving.value = true

  try {
    const payload = serializeForm()
    const response = await authenticatedFetch<{ schoolUpdate: SchoolUpdate }>(
      form.value.id ? `/api/school-updates/${form.value.id}` : '/api/school-updates',
      {
        method: form.value.id ? 'PUT' : 'POST',
        body: payload,
      }
    )

    if (!response.schoolUpdate) {
      throw new Error('The update could not be saved.')
    }

    const savedUpdate = response.schoolUpdate

    if (featuredImageFile.value) {
      await uploadFeaturedImage(savedUpdate.id)
    }

    notificationStore.success({
      summary: 'Saved',
      detail: 'School update saved successfully.',
      life: 5000,
    })
    showForm.value = false
    await fetchUpdates()
  } catch (error) {
    notificationStore.error({
      summary: 'Error',
      detail: error instanceof Error ? error.message : 'School update could not be saved.',
      life: 5000,
    })
  } finally {
    saving.value = false
  }
}

async function deleteUpdate(update: SchoolUpdate) {
  const confirmed = window.confirm(`Delete "${update.title}"?`)

  if (!confirmed) {
    return
  }

  try {
    await authenticatedFetch(`/api/school-updates/${update.id}`, {
      method: 'DELETE',
    })

    notificationStore.success({
      summary: 'Deleted',
      detail: 'School update deleted successfully.',
      life: 5000,
    })
    await fetchUpdates()
  } catch {
    notificationStore.error({
      summary: 'Error',
      detail: 'School update could not be deleted.',
      life: 5000,
    })
  }
}

function validateForm() {
  const errors: Record<string, string> = {}

  if (!form.value.title.trim()) {
    errors.title = 'Title is required.'
  }

  if (!stripHtml(form.value.content).trim()) {
    errors.content = 'Content is required.'
  }

  if (
    form.value.type === 'event' &&
    form.value.event_start_at &&
    form.value.event_end_at &&
    dayjs(form.value.event_end_at).isBefore(dayjs(form.value.event_start_at))
  ) {
    errors.event_end_at = 'Event end must be after the start.'
  }

  return errors
}

function serializeForm() {
  return {
    id: form.value.id,
    title: form.value.title.trim(),
    summary: form.value.summary?.trim() || null,
    content: form.value.content,
    type: form.value.type,
    category: form.value.category?.trim() || null,
    tags: form.value.tags,
    status: form.value.status,
    published_at: serializeDate(form.value.published_at),
    event_start_at: form.value.type === 'event' ? serializeDate(form.value.event_start_at) : null,
    event_end_at: form.value.type === 'event' ? serializeDate(form.value.event_end_at) : null,
  }
}

function serializeDate(value: Date | null) {
  if (!value) {
    return null
  }

  return value.toISOString()
}

function parseDate(value: string | null) {
  return value ? dayjs(value).toDate() : null
}

function onFeaturedImageSelected(event: { files?: File[] }) {
  featuredImageFile.value = event.files?.[0] ?? null
}

async function uploadFeaturedImage(id: number) {
  if (!featuredImageFile.value) {
    return
  }

  const formData = new FormData()
  formData.append('file', featuredImageFile.value)

  await authenticatedFetch(resolveApiPath(`/api/upload/school-updates/${id}/featured-image`), {
    method: 'POST',
    body: formData,
  })
}

async function uploadContentImage(event: { files?: File[] }) {
  const file = event.files?.[0]

  if (!file || !form.value.id) {
    return
  }

  const formData = new FormData()
  formData.append('file', file)

  try {
    const response = await authenticatedFetch<{ url: string }>(
      resolveApiPath(`/api/upload/school-updates/${form.value.id}/content-image`),
      {
        method: 'POST',
        body: formData,
      }
    )

    form.value.content += `<p><img src="${resolveMediaUrl(response.url)}" alt=""></p>`
    notificationStore.success({
      summary: 'Uploaded',
      detail: 'Image inserted into the update.',
      life: 5000,
    })
  } catch {
    notificationStore.error({
      summary: 'Upload failed',
      detail: 'The content image could not be uploaded.',
      life: 5000,
    })
  }
}

function featuredImage(update: SchoolUpdate) {
  if (update.featured_image_url) {
    return resolveMediaUrl(update.featured_image_url)
  }

  if (update.featured_image) {
    return resolveMediaUrl(`/storage/${update.featured_image}`)
  }

  return fallbackImage
}

function resolveApiUrl(endpoint: string) {
  return cleanDuplicateSlashes(`${config.public.backendBase}/${endpoint}`)
}

function resolveApiPath(endpoint: string) {
  return endpoint
}

type AuthenticatedFetchOptions = {
  method?: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
  query?: Record<string, unknown>
  body?: BodyInit | Record<string, unknown>
  headers?: Record<string, string>
}

async function authenticatedFetch<T>(
  endpoint: string,
  options: AuthenticatedFetchOptions = {}
) {
  const token = bearerToken()
  const csrfToken = getCookie('XSRF-TOKEN')

  return await $fetch<T>(resolveApiUrl(endpoint), {
    ...options,
    credentials: 'include',
    headers: {
      ...(token ? { Authorization: `Bearer ${token}` } : {}),
      ...(csrfToken ? { 'X-XSRF-TOKEN': csrfToken } : {}),
      ...(options.headers ?? {}),
    },
  })
}

function getCookie(name: string) {
  if (typeof document === 'undefined') {
    return ''
  }

  const value = (
    document.cookie
      .split('; ')
      .find(cookie => cookie.startsWith(`${name}=`))
      ?.split('=')
      .slice(1)
      .join('=') ?? ''
  )

  return value ? decodeURIComponent(value) : ''
}

function resolveMediaUrl(endpoint: string) {
  if (endpoint.startsWith('http') || endpoint.startsWith('/images/')) {
    return endpoint
  }

  return cleanDuplicateSlashes(`${config.public.backendBase}/${endpoint}`)
}

function cleanDuplicateSlashes(urlString: string) {
  return urlString.replace(/([^:]\/)\/+/g, '$1')
}

function replaceWithFallbackImage(event: Event) {
  const image = event.target as HTMLImageElement
  image.src = fallbackImage
}

function stripHtml(value: string | null) {
  return (value || '').replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim()
}

function featuredSummary(update: SchoolUpdate) {
  return update.summary || stripHtml(update.content)
}

function primaryDate(update: SchoolUpdate) {
  if (update.type === 'event' && update.event_start_at) {
    return eventDate(update)
  }

  return update.published_at
    ? dayjs(update.published_at).format('MMMM D, YYYY')
    : 'Not scheduled'
}

function eventDate(update: SchoolUpdate) {
  if (!update.event_start_at) {
    return 'Date to be announced'
  }

  const start = dayjs(update.event_start_at)
  const end = update.event_end_at ? dayjs(update.event_end_at) : null

  if (end && !start.isSame(end, 'day')) {
    return `${start.format('MMM D')} - ${end.format('MMM D, YYYY')}`
  }

  return start.format('MMMM D, YYYY')
}

function typeLabel(type: string) {
  const labels: Record<string, string> = {
    news: 'News',
    announcement: 'Announcement',
    blog: 'Blog',
    event: 'Event',
  }

  return labels[type] ?? 'Update'
}

function typeIcon(type: string) {
  return updateTypes.find(item => item.value === type)?.icon ?? 'solar:document-text-linear'
}

function statusLabel(update: SchoolUpdate) {
  if (update.status === 'published' && update.published_at && dayjs(update.published_at).isAfter(dayjs())) {
    return 'Scheduled'
  }

  return update.status.charAt(0).toUpperCase() + update.status.slice(1)
}

function statusSeverity(update: SchoolUpdate) {
  if (update.status === 'published') {
    return update.published_at && dayjs(update.published_at).isAfter(dayjs()) ? 'warning' : 'success'
  }

  if (update.status === 'draft') {
    return 'secondary'
  }

  if (update.status === 'archived') {
    return 'contrast'
  }

  return 'warning'
}

function typeButtonClass(value: string) {
  const isActive = filters.type === value

  return [
    'inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm font-bold transition',
    isActive
      ? 'border-blue-700 bg-blue-700 text-white dark:border-blue-400 dark:bg-blue-500'
      : 'border-slate-200 bg-white text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-900 dark:border-white/10 dark:bg-white/5 dark:text-slate-200 dark:hover:border-blue-300/40 dark:hover:bg-blue-400/10',
  ]
}

function tagButtonClass(value: string) {
  const isActive = filters.tag === value

  return [
    'shrink-0 rounded-full border px-3 py-1.5 text-xs font-bold transition',
    isActive
      ? 'border-blue-700 bg-blue-700 text-white dark:border-blue-400 dark:bg-blue-500'
      : 'border-slate-200 bg-white text-slate-600 hover:border-blue-200 hover:bg-blue-50 dark:border-white/10 dark:bg-slate-950/50 dark:text-slate-300',
  ]
}

function sideFilterClass(active: boolean) {
  return [
    'flex w-full items-center justify-between rounded-lg px-3 py-2 text-left text-sm font-bold transition',
    active
      ? 'bg-blue-50 text-blue-800 dark:bg-blue-400/10 dark:text-blue-200'
      : 'text-slate-600 hover:bg-slate-50 hover:text-slate-950 dark:text-slate-300 dark:hover:bg-white/5 dark:hover:text-white',
  ]
}
</script>

<style scoped>
.school-update-dialog :deep(.p-dialog-content) {
  padding-top: 0;
}

:deep(.prose img) {
  border-radius: 0.75rem;
  margin: 1.25rem 0;
}
</style>
