<template>
  <div class="flex flex-col items-center justify-center w-full">
    <div class="flex items-center justify-between w-full">
      <div class="h-16 flex items-center">
        <h1 class="text-lg font-semibold text-gray-900">{{ props.title }}</h1>
      </div>
      <div>
        <slot name="actions" />
      </div>
    </div>
  </div>
</template>
<script setup>
const props = defineProps({
  title: {
    type: String,
    default: 'Dashboard',
  },
})
</script>
