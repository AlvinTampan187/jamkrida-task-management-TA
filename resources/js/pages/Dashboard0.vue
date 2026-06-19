<template>

  <Title title="Dashboard">
    <template #actions>

      <div class="flex flex-wrap gap-2">

        <!-- <button @click="getSuccess()" class="p-2 bg-teal-300 rounded">Success</button>
        <button @click="getError()" class="p-2 bg-red-300 rounded">Error</button>
        <button @click="getConfirm()" class="p-2 bg-yellow-300 rounded">Confirm</button>
        <button @click="getInfo()" class="p-2 bg-blue-300 rounded">Info</button> -->

        <!-- 🔥 LOGOUT -->
        <button 
          @click="logout"
          class="p-2 bg-red-600 text-white rounded hover:bg-red-800"
        >
          Logout
        </button>

      </div>

    </template>
  </Title>


  <div class="pt-4">

    <!-- HEADER TABLE -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">

      <TableHeader
        v-model:filter="filterQuery"
        v-model:perPage="itemsPerPage"
      />

      <button
        v-if="user?.role === 'atasan'"
        @click="onAdd"
        class="flex items-center justify-center bg-teal-600 text-white px-4 py-3 rounded hover:bg-teal-700 w-full md:w-auto"
      >
        <span class="icon-[tabler--plus] h-4 w-4 mr-1"></span>
        Tambah
      </button>

    </div>


    <!-- TABLE -->
    <div class="overflow-x-auto">
      <div class="shadow-sm rounded">

        <table class="min-w-full divide-y divide-gray-300">

          <thead class="bg-teal-800">
            <tr>
              <th class="py-3 px-4 text-left text-sm font-semibold text-white">Tanggal</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-white">Bagian</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-white">Keterangan</th>
              <th class="px-4 py-3 text-right text-sm font-semibold text-white">Status</th>
              <th class="px-4 py-3 text-right text-sm font-semibold text-white">Aksi</th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-200 bg-white">

            <tr v-for="item in items" :key="item.id" class="hover:bg-gray-50">

              <td class="px-4 py-3 text-sm text-gray-900">
                {{ item.tanggal }}
              </td>

              <td class="px-4 py-3 text-sm text-gray-500">
                {{ item.bagian }}
              </td>

              <td class="px-4 py-3 text-sm text-gray-500 max-w-xs break-words">
                {{ item.keterangan }}
              </td>

              <td class="px-4 py-3 text-sm text-right">
                <span
                  :class="[
                    'inline-flex rounded-full px-2 text-xs font-semibold',
                    item.status === 'Selesai'
                      ? 'bg-green-100 text-green-800'
                      : item.status === 'Terlambat'
                      ? 'bg-red-100 text-red-800'
                      : item.status === 'Penugasan'
                      ? 'bg-red-100 text-red-800'
                      : 'bg-yellow-100 text-yellow-800'
                  ]"
                >
                  {{ item.status }}
                </span>
              </td>

              <td class="px-4 py-3 text-sm text-right">
                <div class="flex justify-end gap-2">

                  <button
                    @click="goDetail(item.id)"
                    class="text-blue-600 hover:text-blue-800"
                  >
                    Detail
                  </button>

                </div>
              </td>

            </tr>

            <tr v-if="items.length === 0">
              <td colspan="5" class="text-center py-10">
                <p class="text-gray-500">Tidak ada data</p>
              </td>
            </tr>

          </tbody>

        </table>

      </div>
    </div>


    <!-- FOOTER -->
    <TableFooter
      :total="totalItems"
      :currentPage="currentPage"
      :lastPage="totalPages"
      :perPage="itemsPerPage"
      @change-page="currentPage = $event"
    />

  </div>

</template>



<script setup>

import { useRouter } from 'vue-router'
import axios from 'axios'
import NProgress from 'nprogress'
import Title from '@/components/Title.vue'
import TableHeader from "@/components/TableHeader.vue"
import TableFooter from "@/components/TableFooter.vue"
import { getSuccess, getError } from '@/services/alertService'
import { ref, onMounted } from 'vue'

const router = useRouter()

const itemsPerPage = ref(15)
const currentPage = ref(1)
const totalItems = ref(0)
const totalPages = ref(0)
const filterQuery = ref('')
const items = ref([])


const user = ref(null)

const goDetail = (id) => {
  if (user.value?.role === 'atasan') {
    router.push('/atasan/detail-tugas/' + id)
  } else {
    router.push('/karyawan/detail-tugas/' + id)
  }
}


/* 🔥 LOGOUT */
const logout = () => {
  if (confirm('Yakin ingin logout?')) {
    localStorage.removeItem('user')
    router.push('/login')
  }
}


/* LOAD DATA */
const loadTugas = async () => {

  try {

    const userData = localStorage.getItem('user')

    if (!userData) {
      getError('Silakan login terlebih dahulu')
      router.push('/login')
      return
    }

    const userLocal = JSON.parse(userData)

    NProgress.start()

    let res

    if (userLocal.role === 'atasan') {
      res = await axios.get('http://localhost:8000/api/tugas')
    } else {
      res = await axios.get(`http://localhost:8000/api/tugas/user/${userLocal.id}`)
    }

    items.value = res.data.map(t => ({
      id: t.id,
      tanggal: new Date(t.created_at).toLocaleDateString('id-ID'),
      bagian: t.bagian,
      keterangan: t.keterangan_laporan,
      status: t.status ?? 'Penugasan'
    }))

    totalItems.value = items.value.length
    totalPages.value = 1

    NProgress.done()

  } catch (error) {

    console.log(error)
    getError('Gagal mengambil data tugas')
    NProgress.done()

  }

}


// untuk menampilkan tombol tambah saat login sebagai atasan
onMounted(() => {
  const userData = localStorage.getItem('user')
  if (userData) {
    user.value = JSON.parse(userData)
  }
  loadTugas()
})


const onAdd = () => {
  router.push('/atasan/form')
}

</script>