<template>
  <nav class="py-2 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-y-2 sm:gap-y-0" aria-label="Pagination">
    <p class="text-sm text-gray-700 text-center sm:text-left">
      Menampilkan {{ firstItem }} - {{ lastItem }} dari {{ total }} data
    </p>
    <div class="flex flex-wrap justify-center sm:justify-end gap-2">
      <button
        type="button"
        @click="changePage(currentPage - 1)" :disabled="currentPage === 1"
        class="base-button bg-white"
        :class="{ 'cursor-not-allowed': currentPage === 1 }"
      >
        <span class="icon-[tabler--chevron-left] text-center h-5 w-5" aria-hidden="false" />
      </button>
      <button
        v-for="page in pages"
        :key="page"
        :class="{ active: page === currentPage ? 'bg-gray-100' : '' }"
        class="base-button bg-white"
        @click="changePage(page)"
      >
        {{ page }}
      </button>
      <button
        type="button"
        @click="changePage(currentPage + 1)" :disabled="currentPage === lastPage"
        class="base-button bg-white"
        :class="{ 'cursor-not-allowed': currentPage === lastPage }"
      >
        <span class="icon-[tabler--chevron-right] text-center h-5 w-5" aria-hidden="false" />
      </button>
    </div>
  </nav>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  total: Number,
  currentPage: Number,
  perPage: Number,
  lastPage: Number
})
const emit = defineEmits(['change-page'])

const changePage = (page) => {
  if (page >= 1 && page <= props.lastPage) {
    emit('change-page', page)
  }
}

const firstItem = computed(() => {
  return props.total === 0 ? 0 : (props.currentPage - 1) * props.perPage + 1
})

const lastItem = computed(() => {
  return Math.min(props.currentPage * props.perPage, props.total)
})

const pages = computed(() => {
  const max = 5
  const curr = props.currentPage
  const last = props.lastPage
  let start = Math.max(1, curr - 2)
  let end = Math.min(last, start + max - 1)
  if (end - start < max - 1) start = Math.max(1, end - max + 1)
  return Array.from({ length: end - start + 1 }, (_, i) => start + i)
})
</script>
