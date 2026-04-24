<script setup lang="ts">
import { breakpointsTailwind } from '@vueuse/core'

const visible = defineModel('visible', { default: false })

const breakpoints = useBreakpoints(breakpointsTailwind)

const smWidth = breakpointsTailwind.sm

const reactiveStuff = shallowRef<keyof typeof breakpointsTailwind>('sm')
const isGreaterThanBreakpoint = breakpoints.greaterOrEqual(
  () => reactiveStuff.value
)

const current = breakpoints.current()
const active = breakpoints.active()
const xs = breakpoints.smaller('sm')
const xse = breakpoints.smallerOrEqual('sm')
const sm = breakpoints.between('sm', 'md')
const md = breakpoints.between('md', 'lg')
const lg = breakpoints.between('lg', 'xl')
const xl = breakpoints.between('xl', '2xl')
const xxl = breakpoints['2xl']
</script>

<template>
  <Dialog
    v-model:visible="visible"
    modal
    header="Division Schools Press Conference Finals"
    class="!max-w-[66rem] !w-full !border-none mx-6 shadow-[0px_4px_10px_0px_rgba(0,0,0,0.03),0px_0px_2px_0px_rgba(0,0,0,0.06),0px_2px_6px_0px_rgba(0,0,0,0.12)] !rounded-xl lg:!rounded-2xl dark:!bg-white/80 backdrop-blur-xl"
  >
    <div class="font-mono">
      <div>Current breakpoints: {{ current }}</div>
      <div>Active breakpoint: {{ active }}</div>
      <div>xs(&lt;{{ smWidth }}px): <DevBooleanDisplay :value="xs" /></div>
      <div>xs(&lt;={{ smWidth }}px): <DevBooleanDisplay :value="xse" /></div>
      <div>sm: <DevBooleanDisplay :value="sm" /></div>
      <div>md: <DevBooleanDisplay :value="md" /></div>
      <div>lg: <DevBooleanDisplay :value="lg" /></div>
      <div>xl: <DevBooleanDisplay :value="xl" /></div>
      <div>2xl: <DevBooleanDisplay :value="xxl" /></div>
      <div>
        greaterThanBreakPoint:
        <DevBooleanDisplay :value="isGreaterThanBreakpoint" />
      </div>
    </div>
  </Dialog>
</template>
