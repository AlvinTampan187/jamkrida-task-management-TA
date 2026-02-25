<template>
  <Title title="Form Permintaan">
    <template #actions>
      <button @click="submitForm" class="p-2 bg-teal-600 text-white rounded hover:bg-teal-700 mr-2">
        Simpan
      </button>
      <button @click="goBack" class="p-2 bg-yellow-400 text-white rounded hover:bg-yellow-500">
        Kembali
      </button>
    </template>
  </Title>

  <div class="max-w-3xl p-6 bg-white rounded shadow mt-4 space-y-4">

    <!-- Bagian -->
    <div>
      <label class="block font-medium">Bagian</label>
      <input v-model="form.bagian" type="text"
        class="border rounded w-full p-2" />
    </div>

    <!-- Kategori -->
    <div>
      <label class="block font-medium mb-1">Kategori</label>
      <div class="space-y-1">
        <label><input type="radio" value="Hardware" v-model="form.kategori"> Hardware</label><br>
        <label><input type="radio" value="Software/Aplikasi" v-model="form.kategori"> Software/Aplikasi</label><br>
        <label><input type="radio" value="Jaringan" v-model="form.kategori"> Jaringan</label>
      </div>
    </div>

    <!-- Jenis Laporan -->
    <div>
      <label class="block font-medium">Jenis Laporan</label>
      <textarea v-model="form.jenis_laporan"
        class="border rounded w-full p-2"></textarea>
    </div>

    <!-- Solusi Jawaban -->
    <div>
      <label class="block font-medium">Solusi Jawaban</label>
      <textarea v-model="form.solusi"
        class="border rounded w-full p-2"></textarea>
    </div>

    
  </div>
</template>

<script setup>
import Title from '@/components/Title.vue'
import { ref } from 'vue'
import { getSuccess, getError } from '@/services/alertService'
import { useRouter } from 'vue-router'

const router = useRouter()

const goBack = () => {
  router.push('/dashboard')
}

const form = ref({
  bagian: '',
  kategori: '',
  jenis_laporan: '',
  solusi: '',
  foto: null
})

const handleFile = (e) => {
  form.value.foto = e.target.files[0]
}

const submitForm = () => {
  if (!form.value.bagian || !form.value.kategori || !form.value.jenis_laporan) {
    getError('Lengkapi semua data')
    return
  }

  console.log(form.value)
  getSuccess('Data berhasil disimpan')
}
</script>
