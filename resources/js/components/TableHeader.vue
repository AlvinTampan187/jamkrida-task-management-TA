<template>
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between text-sm mb-2 mt-2 gap-y-2 sm:gap-y-0 sm:gap-x-8">
    <div class="flex items-center gap-x-2">
      Tampilkan
      <select v-model="modelPerPage" @change="emitUpdate" class="base-select bg-white">
        <option v-for="opt in [15, 25, 50, 100]" :key="opt" :value="opt">
          {{ opt }}
        </option>
      </select>
      data
    </div>

    <!-- <div class="flex items-center gap-x-2">
      Pencarian
      <input
        v-model="modelSearch"
        @input="debounceSearch"
        placeholder="Cari..."
        class="base-input bg-white"
      />
    </div> -->
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  filter: String,
  perPage: Number
})

const emit = defineEmits(['update:filter', 'update:perPage'])

const modelSearch = ref(props.filter)
const modelPerPage = ref(props.perPage ?? 15)

watch(() => props.filter, val => modelSearch.value = val)
watch(() => props.perPage, val => modelPerPage.value = val)

let timer = null
const debounceSearch = () => {
  clearTimeout(timer)
  timer = setTimeout(() => {
    emit('update:filter', modelSearch.value)
  }, 400)
}

const emitUpdate = () => {
  emit('update:perPage', modelPerPage.value)
}
</script>

