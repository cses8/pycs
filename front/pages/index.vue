<template>
  <DelayHydration>
    <div class="bg-surface-0 dark:bg-surface-950 min-h-screen">
      <div class="bg-surface-900 p-12 lg:p-20 BgImgContainer">
        <div
          class="flex flex-wrap lg:flex-row flex-col-reverse gap-12 items-center"
        >
          <LazyUiBlurReveal>
            <div class="flex-1 p-4">
              <h1 class="text-5xl font-bold text-white mb-4 leading-tight">
                Experience the Culture
              </h1>
              <h1 class="text-5xl font-bold text-white mb-4 leading-tight">
                of Excellence
              </h1>
              <p class="text-2xl text-gray-400 leading-normal">
                Transforming Lives through Education
              </p>
              <p class="text-2xl text-gray-400 mb-8 leading-normal">
                with Quality Assurance
              </p>
              <ul class="list-none flex flex-col gap-4">
                <li class="flex items-center gap-2">
                  <i class="pi pi-trophy text-blue-300 !text-xl" /><span
                    class="text-gray-400 leading-normal"
                    >Academic Excellence.</span
                  >
                </li>
                <li class="flex items-center gap-2">
                  <i class="pi pi-bolt text-blue-300 !text-xl" /><span
                    class="text-gray-400 leading-normal"
                    >Character and Discipline.</span
                  >
                </li>
              </ul>
            </div>
          </LazyUiBlurReveal>
          <div class="flex-1 text-center lg:text-right">
            <LazyAppActiveAnnouncement />
          </div>
        </div>
      </div>
      <section
        class="bg-surface-0 dark:bg-surface-950 gap-3 px-6 py-20 md:px-12 lg:px-20"
      >
        <div class="flex flex-col gap-4">
          <div class="relative overflow-hidden">
            <div
              class="absolute left-0 top-0 h-full w-[30%] z-10 bg-gradient-to-r from-surface-0 to-transparent dark:from-surface-950"
            />
            <div
              class="absolute right-0 top-0 h-full w-[30%] z-10 bg-gradient-to-l from-surface-0 to-transparent dark:from-surface-950"
            />

            <!-- <AppTeacher /> -->
          </div>
        </div>
      </section>
      <div
        class="bg-surface-0 dark:bg-surface-950 overflow-hidden py-20 px-6 md:px-12 lg:px-20"
      >
        <div class="flex flex-col gap-12 items-center">
          <LazyAppOurAcademicProgram />
        </div>
      </div>
      <div
        class="bg-surface-0 dark:bg-surface-900 py-20 px-6 md:px-12 lg:px-20 flex flex-col gap-8 justify-center items-center relative"
      >
        <LazyAppQuickLinksSection />
      </div>
    </div>
  </DelayHydration>
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
  background-image: linear-gradient(
      0deg,
      rgba(0, 0, 0, 0.8) 0%,
      oklch(42.4% 0.199 265.638) 100%
    ),
    url('/images/banner2.webp');
}
</style>
