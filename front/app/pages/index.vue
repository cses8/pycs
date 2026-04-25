<template>
  <div class="min-h-screen bg-[#f8fafc] dark:bg-surface-950">
    <div class="BgImgContainer">
      <div
        class="mx-auto flex min-h-[560px] max-w-7xl flex-col items-center gap-10 px-6 py-12 md:px-8 lg:min-h-[620px] lg:flex-row lg:px-10"
      >
        <div class="max-w-2xl flex-1">
          <div
            class="mb-5 inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-1 text-sm font-semibold text-white backdrop-blur"
          >
            <Icon name="solar:map-point-school-linear" class="text-indigo-100" />
            Philippine Yuh Chiau School, Inc.
          </div>
          <h1
            class="mb-5 text-4xl font-bold leading-[1.05] text-white md:text-5xl lg:text-6xl"
          >
            Experience the Culture of Excellence
          </h1>
          <p class="max-w-xl text-lg leading-8 text-slate-200 md:text-xl">
            Transforming lives through education with quality assurance,
            discipline, and academic growth.
          </p>
          <ul class="mt-8 flex list-none flex-col gap-3 sm:flex-row sm:flex-wrap">
            <li class="flex items-center gap-2">
              <span
                class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10 text-indigo-100"
              >
                <i class="pi pi-trophy !text-base" />
              </span>
              <span class="font-medium leading-normal text-slate-100"
                >Academic Excellence.</span
              >
            </li>
            <li class="flex items-center gap-2">
              <span
                class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10 text-indigo-100"
              >
                <i class="pi pi-bolt !text-base" />
              </span>
              <span class="font-medium leading-normal text-slate-100"
                >Character and Discipline.</span
              >
            </li>
          </ul>
        </div>
        <div class="w-full flex-1 text-center lg:text-right">
          <AppActiveAnnouncement />
        </div>
      </div>
    </div>
    <div
      class="overflow-hidden bg-[#f8fafc] px-6 py-12 md:px-8 lg:px-10 dark:bg-surface-950"
    >
      <div class="flex flex-col gap-12 items-center">
        <LazyAppOurAcademicProgram />
      </div>
    </div>
    <LazyAppRecentActivity />
    <div
      class="relative flex flex-col items-center justify-center gap-8 bg-white px-6 py-12 md:px-8 lg:px-10 dark:bg-surface-900"
    >
      <LazyAppQuickLinksSection />
    </div>
  </div>
</template>
<script setup>
definePageMeta({
  layout: 'welcome',
})
</script>
<style scoped>
@keyframes marquee {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}

.animate-marquee {
  animation: marquee 30s linear infinite;
}

.BgImgContainer {
  background-size: cover !important;
  background-repeat: no-repeat !important;
  background-position: center center !important;
  background-image: linear-gradient(90deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.78) 48%, rgba(79, 70, 229, 0.45) 100%),
    url('/images/banner2.webp');
}
</style>
