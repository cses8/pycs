<template>
  <footer class="flex flex-col border-t border-slate-200 bg-white dark:border-surface-800 dark:bg-surface-950">
    <div
      class="relative mx-auto flex w-full max-w-7xl flex-col gap-10 overflow-hidden px-6 py-12 md:px-8 lg:flex-row lg:items-start lg:justify-between lg:px-10"
    >
      <div class="flex max-w-sm flex-col gap-4">
        <Image
          src="/images/logo.webp"
          height="84"
          width="84"
          alt="Philippine Yuh Chiau School logo"
        />
        <div>
          <h2 class="text-lg font-semibold leading-tight text-slate-900 dark:text-white">
            Philippine Yuh Chiau School, Inc.
          </h2>
          <p class="mt-2 text-sm leading-6 text-slate-600 dark:text-surface-300">
            Experience the Culture of Excellence through education with quality assurance.
          </p>
        </div>
      </div>
      <div
        class="relative z-0 grid w-full grid-cols-1 gap-8 sm:grid-cols-2 lg:max-w-3xl lg:grid-cols-3"
      >
        <div class="flex w-full min-w-52 flex-col gap-4">
          <h3
            class="text-base font-semibold leading-7 text-slate-900 dark:text-surface-0"
          >
            Contact Us
          </h3>
          <ul
            class="flex flex-col gap-2 text-sm text-slate-600 dark:text-surface-300"
          >
            <li class="leading-6">
              <span>Globe:</span>
              <span class="ml-2 font-medium text-slate-900 dark:text-surface-0">0966-416-1972</span>
            </li>
            <li class="leading-6">
              <span>Smart:</span>
              <span class="ml-2 font-medium text-slate-900 dark:text-surface-0">0908-885-1783</span>
            </li>
            <li class="leading-6">
              <span>Landline:</span>
              <span class="ml-2 font-medium text-slate-900 dark:text-surface-0">(078) 325-0842</span>
            </li>
            <li class="leading-6">
              <span>Website:</span>
              <span class="ml-2 font-medium text-slate-900 dark:text-surface-0">pycs.school</span>
            </li>
          </ul>
        </div>
        <div class="flex w-full min-w-52 flex-col gap-4">
          <h3
            class="text-base font-semibold leading-7 text-slate-900 dark:text-surface-0"
          >
            Email
          </h3>
          <ul
            class="flex flex-col gap-2 text-sm text-slate-600 dark:text-surface-300"
          >
            <li
              class="flex flex-row items-center leading-6"
            >
              <Icon name="logos:google-gmail" class="mr-2" size="1.5rem" />
              <span class="ml-2 font-medium text-slate-900 dark:text-surface-0">pycs.65@gmail.com</span>
            </li>
          </ul>
        </div>
        <div class="flex w-full min-w-52 flex-col gap-4">
          <h3
            class="text-base font-semibold leading-7 text-slate-900 dark:text-surface-0"
          >
            Social Media
          </h3>
          <ul
            class="flex flex-col gap-2 text-sm text-slate-600 dark:text-surface-300"
          >
            <li
              class="flex flex-row items-center leading-6"
            >
              <Icon name="logos:facebook" class="mr-2" size="1.5rem" />
              <a
                href="https://www.facebook.com/PYCS.75"
                target="_blank"
                class="cursor-pointer font-medium leading-6 text-slate-900 hover:text-indigo-700 dark:text-surface-0 dark:hover:text-indigo-200"
              >
                <span class="ml-2">www.facebook.com/PYCS.75</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div
      class="mx-auto flex w-full max-w-7xl flex-wrap items-center justify-between gap-4 border-t border-slate-200 px-6 py-4 text-sm md:px-8 lg:px-10 dark:border-surface-800"
    >
      <span class="font-normal text-slate-500 dark:text-surface-300"
        >© Philippine Yuh Chiau School, Inc</span
      >
      <div class="flex items-center gap-2">
        <a
          href="https://www.facebook.com/PYCS.75"
          target="_blank"
          class="flex items-center justify-center rounded-lg border border-slate-200 px-3 py-2 backdrop-blur-sm transition-colors duration-100 hover:bg-slate-100 dark:border-surface-700 dark:hover:bg-surface-800"
          aria-label="Open PYCS Facebook page"
        >
          <Icon name="logos:facebook" size="1.5rem" />
        </a>
      </div>
    </div>
  </footer>
</template>
<script setup></script>
