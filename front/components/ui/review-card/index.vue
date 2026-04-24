<template>
  <figure
    class="relative w-64 cursor-pointer overflow-hidden rounded-xl border border-gray-950/[.1] bg-gray-950/[.01] p-4 hover:bg-gray-950/[.05] dark:border-gray-50/[.1] dark:bg-gray-50/[.10] dark:hover:bg-gray-50/[.15]"
  >
    <div class="flex flex-row items-center gap-2">
      <Image :src="img" class="rounded-full" width="32" height="32" alt="" />
      <div class="flex flex-col">
        <span class="text-md font-medium dark:text-gray-100">
          {{ name }}
        </span>
        <p class="text-xs font-medium text-gray-500">
          {{ username }}
        </p>
      </div>
    </div>
    <blockquote
      class="mt-2 text-sm font-light text-gray-700 dark:font-medium dark:text-gray-500"
    >
      {{ body }}
    </blockquote>
  </figure>
</template>

<script lang="ts" setup>
interface Props {
  img: string
  name: string
  username: string
  body: string
}

defineProps<Props>()
</script>
