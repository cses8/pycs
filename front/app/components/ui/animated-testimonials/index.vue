<template>
  <div
    class="mx-auto w-full max-w-3xl rounded-xl border border-white/15 bg-white/10 p-4 text-left font-sans antialiased shadow-2xl shadow-slate-950/20 backdrop-blur md:p-5"
  >
    <div class="relative grid grid-cols-1 gap-6 md:grid-cols-[220px_1fr]">
      <div>
        <div class="relative h-64 w-full overflow-hidden rounded-lg md:h-full">
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
      <div class="flex min-h-64 flex-col justify-between py-2">
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
          <h3 class="text-2xl font-bold leading-tight text-white">
            {{ props.testimonials[active]?.name }}
          </h3>
          <p class="mt-1 text-sm font-medium text-indigo-100">
            {{ props.testimonials[active]?.designation }}
          </p>
          <Motion as="p" class="mt-5 text-base font-normal leading-7 text-slate-200">
            <Motion
              v-for="(word, index) in activeTestimonialQuote"
              :key="index"
              as="span"
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
                delay: 0.02 * index,
              }"
              class="inline text-left"
            >
              <span class="!break-words" v-html="word" />
              &nbsp;
            </Motion>
          </Motion>
        </Motion>
        <div class="flex gap-3 pt-8 md:pt-0">
          <button
            class="group/button flex size-9 items-center justify-center rounded-lg border border-white/20 bg-white/10 transition-colors hover:bg-white/20"
            type="button"
            aria-label="Previous announcement"
            @click="handlePrev"
          >
            <Icon
              name="lucide:arrow-left"
              class="size-5 text-white transition-transform duration-300 group-hover/button:-translate-x-0.5"
            />
          </button>
          <button
            class="group/button flex size-9 items-center justify-center rounded-lg border border-white/20 bg-white/10 transition-colors hover:bg-white/20"
            type="button"
            aria-label="Next announcement"
            @click="handleNext"
          >
            <Icon
              name="lucide:arrow-right"
              class="size-5 text-white transition-transform duration-300 group-hover/button:translate-x-0.5"
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

const activeTestimonialQuote = computed(() => {
  return props.testimonials[active.value]?.quote.split(' ') ?? []
})

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
