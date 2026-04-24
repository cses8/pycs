<template>
  <div class="flex flex-col bg-surface-0 dark:bg-surface-950">
    <div
      class="flex flex-col lg:flex-row justify-between gap-8 p-20 pb-16 w-full overflow-hidden relative bg-gradient-to-b from-blue-900/30 to-transparent"
    >
      <div class="flex justify-center">
        <Image src="/images/logo.webp" height="150" width="150" alt="Image" />
      </div>
      <div
        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 w-full gap-4 relative z-0"
      >
        <div class="w-full flex flex-col gap-4 min-w-52"></div>
        <div class="w-full flex flex-col gap-4 min-w-52">
          <h3
            class="text-surface-900 dark:text-surface-0 text-lg font-medium leading-7"
          >
            Contact Us
          </h3>
          <ul
            class="flex flex-col gap-2 text-surface-500 dark:text-surface-400"
          >
            <li class="font-semibold leading-6 hover:text-blue-600">
              <span>Globe:</span>
              <span class="font-light ml-2">0966-416-1972</span>
            </li>
            <li class="font-semibold leading-6 hover:text-blue-600">
              <span>Smart:</span>
              <span class="font-light ml-2">0908-885-1783</span>
            </li>
            <li class="font-semibold leading-6 hover:text-blue-600">
              <span>Landline:</span>
              <span class="font-light ml-2">(078) 325-0842</span>
            </li>
            <li class="font-semibold leading-6 hover:text-blue-600">
              <span>Website:</span>
              <span class="font-light ml-2">pycs.school</span>
            </li>
          </ul>
        </div>
        <div class="w-full flex flex-col gap-4 min-w-52">
          <h3
            class="text-surface-900 dark:text-surface-0 text-lg font-medium leading-7"
          >
            Email
          </h3>
          <ul
            class="flex flex-col gap-2 text-surface-500 dark:text-surface-400"
          >
            <li
              class="font-semibold leading-6 hover:text-blue-600 flex flex-row items-center"
            >
              <Icon name="logos:google-gmail" class="mr-2" size="1.5rem" />
              <span class="font-light ml-2">pycs.65@gmail.com</span>
            </li>
          </ul>
        </div>
        <div class="w-full flex flex-col gap-4 min-w-52">
          <h3
            class="text-surface-900 dark:text-surface-0 text-lg font-medium leading-7"
          >
            Social Media
          </h3>
          <ul
            class="flex flex-col gap-2 text-surface-500 dark:text-surface-400"
          >
            <li
              class="font-semibold leading-6 hover:text-blue-600 flex flex-row items-center"
            >
              <Icon name="logos:facebook" class="mr-2" size="1.5rem" />
              <a
                href="https://www.facebook.com/PYCS.75"
                target="_blank"
                class="font-semibold leading-6 hover:text-blue-600 cursor-pointer"
              >
                <span class="font-light ml-2">www.facebook.com/PYCS.75</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <div
      class="py-2 px-20 flex flex-wrap items-center justify-between w-full border-t border-surface-200 dark:border-surface-700 gap-4"
    >
      <span class="text-surface-500 dark:text-surface-0 font-normal"
        >© Philippine Yuh Chiau School, Inc</span
      >
      <div class="flex items-center gap-2">
        <a
          href="https://www.facebook.com/PYCS.75"
          target="_blank"
          class="flex items-center justify-center px-4 py-2 border border-surface-200 dark:border-surface-700 rounded-3xl backdrop-blur-sm hover:bg-surface-100 dark:hover:bg-surface-800 duration-100 transition-colors"
        >
          <Icon name="logos:facebook" size="1.5rem" />
        </a>
      </div>
    </div>
  </div>
</template>
<script setup></script>
