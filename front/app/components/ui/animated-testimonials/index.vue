<template>
  <div
    class="mx-auto w-full max-w-2xl overflow-hidden rounded-xl border border-white/15 bg-white/10 p-4 text-left font-sans antialiased shadow-2xl shadow-slate-950/20 backdrop-blur sm:p-5"
  >
    <div class="relative grid min-w-0 grid-cols-1 gap-5 xl:grid-cols-[180px_minmax(0,1fr)]">
      <div class="min-w-0">
        <div class="relative h-48 w-full overflow-hidden rounded-lg sm:h-56 xl:h-full">
          <Motion
            v-for="(testimonial, index) in props.testimonials"
            :key="testimonial.image"
            as="div"
            :initial="{
              opacity: 0,
              scale: 0.9,
              z: -100,
              rotate: randomRotateY(),
            }"
            :animate="{
              opacity: isActive(index) ? 1 : 0.7,
              scale: isActive(index) ? 1 : 0.95,
              z: isActive(index) ? 0 : -100,
              rotate: isActive(index) ? 0 : randomRotateY(),
              zIndex: isActive(index) ? 40 : testimonials.length + 2 - index,
              y: isActive(index) ? [0, -80, 0] : 0,
            }"
            :exit="{
              opacity: 0,
              scale: 0.9,
              z: 100,
              rotate: randomRotateY(),
            }"
            :transition="{
              duration: 0.4,
              ease: 'easeInOut',
            }"
            class="absolute inset-0 origin-bottom"
          >
            <div class="h-full rounded-lg">
              <Image
                :src="getTestimonialImage(testimonial)"
                :alt="testimonial.name"
                width="500"
                height="500"
                preview
                class="size-full rounded-lg object-cover object-center"
                @error="markImageAsFailed(testimonial)"
              />
            </div>
          </Motion>
        </div>
      </div>
      <div class="flex min-h-0 min-w-0 flex-col justify-between py-1">
        <Motion
          :key="active"
          as="div"
          :initial="{
            y: 20,
            opacity: 0,
          }"
          :animate="{
            y: 0,
            opacity: 1,
          }"
          :exit="{
            y: -20,
            opacity: 0,
          }"
          :transition="{
            duration: 0.2,
            ease: 'easeInOut',
          }"
        >
          <h3 class="break-words text-xl font-bold leading-tight text-white sm:text-2xl">
            {{ props.testimonials[active]?.name }}
          </h3>
          <p class="mt-1 text-sm font-medium text-indigo-100">
            {{ props.testimonials[active]?.designation }}
          </p>
          <Motion
            as="div"
            class="mt-4 max-h-72 min-w-0 overflow-y-auto pr-1 text-sm font-normal leading-7 text-slate-200 sm:text-base xl:max-h-80"
            :initial="{
              filter: 'blur(10px)',
              opacity: 0,
              y: 5,
            }"
            :animate="{
              filter: 'blur(0px)',
              opacity: 1,
              y: 0,
            }"
            :transition="{
              duration: 0.2,
              ease: 'easeInOut',
            }"
          >
            <AppSafeHtml
              class="break-words [&_*]:max-w-full [&_a]:break-words [&_a]:text-white [&_a]:underline [&_img]:h-auto [&_img]:max-w-full [&_ol]:list-decimal [&_ol]:pl-5 [&_p]:mb-3 [&_table]:block [&_table]:overflow-x-auto [&_ul]:list-disc [&_ul]:pl-5"
              :html="props.testimonials[active]?.quote || ''"
            />
          </Motion>
        </Motion>
        <div class="flex gap-3 pt-5">
          <button
            class="group/button flex size-9 items-center justify-center rounded-lg border border-white/20 bg-white/10 transition-colors hover:bg-white/20"
            type="button"
            aria-label="Previous announcement"
            @click="handlePrev"
          >
            <span
              class="pi pi-arrow-left text-sm text-white transition-transform duration-300 group-hover/button:-translate-x-0.5"
              aria-hidden="true"
            />
          </button>
          <button
            class="group/button flex size-9 items-center justify-center rounded-lg border border-white/20 bg-white/10 transition-colors hover:bg-white/20"
            type="button"
            aria-label="Next announcement"
            @click="handleNext"
          >
            <span
              class="pi pi-arrow-right text-sm text-white transition-transform duration-300 group-hover/button:translate-x-0.5"
              aria-hidden="true"
            />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { Motion } from 'motion-v'

interface Testimonial {
  quote: string
  name: string
  designation: string
  image: string
}
interface Props {
  testimonials?: Testimonial[]
  autoplay?: boolean
  duration?: number
}

const props = withDefaults(defineProps<Props>(), {
  testimonials: () => [],
  autoplay: () => false,
  duration: 5000,
})

const FALLBACK_TESTIMONIAL_IMAGE = '/images/no-image.svg'
const active = ref(0)
const failedImages = ref<Set<string>>(new Set())

// eslint-disable-next-line @typescript-eslint/no-explicit-any
const interval = ref<any>()

onMounted(() => {
  if (props.autoplay && props.testimonials.length > 1) {
    interval.value = setInterval(handleNext, props.duration)
  }
})

onUnmounted(() => {
  if (interval.value) {
    clearInterval(interval.value)
  }
})

function getTestimonialImage(testimonial?: Testimonial) {
  const image = testimonial?.image?.trim()

  if (!image || failedImages.value.has(image)) {
    return FALLBACK_TESTIMONIAL_IMAGE
  }

  return image
}

function markImageAsFailed(testimonial: Testimonial) {
  const image = testimonial.image?.trim()

  if (!image || image === FALLBACK_TESTIMONIAL_IMAGE) {
    return
  }

  const nextFailedImages = new Set(failedImages.value)
  nextFailedImages.add(image)
  failedImages.value = nextFailedImages
}

function handleNext() {
  if (!props.testimonials.length) {
    return
  }
  active.value = (active.value + 1) % props.testimonials.length
}

function handlePrev() {
  if (!props.testimonials.length) {
    return
  }
  active.value =
    (active.value - 1 + props.testimonials.length) % props.testimonials.length
}

function isActive(index: number) {
  return active.value === index
}

function randomRotateY() {
  return Math.floor(Math.random() * 21) - 10
}
</script>
