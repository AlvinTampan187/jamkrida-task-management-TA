<template>

  <Title title="Dashboard Monitoring Kinerja Karyawan">
    <template #actions>

      <div class="flex flex-wrap gap-2">

        <!-- 🔥 LOGOUT -->
        <button 
          @click="logout"
          class="p-2 bg-red-600 text-white rounded hover:bg-red-800"
        >
          Logout
        </button>

        <button 
          @click="printKinerja"
          class="p-2 bg-blue-600 text-white rounded hover:bg-blue-800"
        >
          Cetak
        </button>

      </div>

    </template>
  </Title>

  <div class="pt-4">

    <!-- ================= STATISTIK ================= -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

      <!-- TOTAL -->
      <div class="bg-white rounded-lg shadow border p-6">
        <div class="flex items-center">
          <div class="bg-blue-100 p-3 rounded-full mr-4">
            <span class="icon-[tabler--clipboard-list] text-blue-600 h-6 w-6"></span>
          </div>
          <div>
            <p class="text-sm text-gray-600">Total Tugas</p>
            <p class="text-2xl font-bold">{{ stats.totalTugas }}</p>
          </div>
        </div>
      </div>

      <!-- SELESAI -->
      <div class="bg-white rounded-lg shadow border p-6">
        <div class="flex items-center">
          <div class="bg-green-100 p-3 rounded-full mr-4">
            <span class="icon-[tabler--circle-check] text-green-600 h-6 w-6"></span>
          </div>
          <div>
            <p class="text-sm text-gray-600">Tugas Selesai</p>
            <p class="text-2xl font-bold">{{ stats.tugasSelesai }}</p>
            <p class="text-sm text-gray-500">
              {{ percent(stats.tugasSelesai, stats.totalTugas) }}%
            </p>
          </div>
        </div>
      </div>

      <!-- TERLAMBAT -->
      <div class="bg-white rounded-lg shadow border p-6">
        <div class="flex items-center">
          <div class="bg-red-100 p-3 rounded-full mr-4">
            <span class="icon-[tabler--alert-triangle] text-red-600 h-6 w-6"></span>
          </div>
          <div>
            <p class="text-sm text-gray-600">Terlambat</p>
            <p class="text-2xl font-bold">{{ stats.keterlambatan }}</p>
            <p class="text-sm text-gray-500">
              {{ percent(stats.keterlambatan, stats.totalTugas) }}%
            </p>
          </div>
        </div>
      </div>

      <!-- PROSES -->
      <div class="bg-white rounded-lg shadow border p-6">
        <div class="flex items-center">
          <div class="bg-yellow-100 p-3 rounded-full mr-4">
            <span class="icon-[tabler--clock] text-yellow-600 h-6 w-6"></span>
          </div>
          <div>
            <p class="text-sm text-gray-600">Progres</p>
            <p class="text-2xl font-bold">{{ stats.tugasProses }}</p>
          </div>
        </div>
      </div>

    </div>

    <!-- ================= DISTRIBUSI ================= -->
    <div class="bg-white rounded shadow p-6 mb-6">
      <h3 class="font-semibold mb-4 flex items-center">
        <span class="icon-[tabler--chart-bar] mr-2"></span>
        Distribusi Beban Kerja
      </h3>

      <div v-for="k in distribusiBeban" :key="k.id" class="mb-3">

        <div class="flex items-center justify-between text-sm mb-1">

          <span class="font-medium whitespace-nowrap">
            {{ k.nama }}
          </span>


          <div class="flex items-center gap-3">

            <span>
              {{ k.tugas }} tugas
            </span>


            <button
              @click="detailKaryawan(k.id)"
              class="bg-teal-600 text-white px-2 py-1 rounded text-xs hover:bg-teal-800 whitespace-nowrap"
            >
              Detail
            </button>

          </div>


        </div>


        <div class="bg-gray-200 h-2 rounded">
          <div 
            class="bg-teal-600 h-2 rounded"
            :style="{ width: barWidth(k.tugas) + '%' }"
          ></div>
        </div>

      </div>
    </div>

    <!-- ================= TABEL ================= -->
   <div class="bg-white rounded shadow">

      <div class="p-4 border-b flex justify-between items-center">
        <h3 class="font-semibold flex items-center">
          <span class="icon-[tabler--users] mr-2"></span>
          Performa Karyawan
        </h3>

        <select 
          v-model="filterBagian"
          class="border px-2 py-1 rounded text-sm"
        >
          <option value="">Semua</option>
          <option v-for="b in bagianList" :key="b" :value="b">
            {{ b }}
          </option>
        </select>
      </div>

      <!-- 🔥 TAMBAHKAN INI -->
      <div class="p-4 overflow-x-auto">

        <table class="w-full text-sm min-w-[700px]">

          <thead class="bg-teal-800 text-white">
            <tr>
              <th class="p-2 text-center">Nama</th>
              <th class="p-2 text-center">Bagian</th>
              <th class="p-2 text-center">Total</th>
              <th class="p-2 text-center">Selesai</th>
              <th class="p-2 text-center">Terlambat</th>
              <th class="p-2 text-center">Performance</th>
              <th class="p-2 text-center">Kontribusi</th>
              <th class="p-2 text-center">Skor</th>
            </tr>
          </thead>

          <tbody>
            <tr 
              v-for="k in filteredKaryawan" 
              :key="k.id"
              class="border-b hover:bg-gray-50"
            >
              <td class="p-2 flex items-center gap-2 whitespace-nowrap">
                <span class="icon-[tabler--user] text-teal-600"></span>
                {{ k.name }}
              </td>

              <td class="p-2 text-center whitespace-nowrap">
                {{ k.bagian }}
              </td>

              <td class="text-center">{{ k.totalTugas }}</td>

              <td class="text-center text-green-600">
                {{ k.tugasSelesai }}
              </td>

              <td class="text-center">
                <span
                  :class="k.tugasTerlambat > 0
                    ? 'text-red-600 font-semibold'
                    : 'text-green-600'"
                >
                  {{ k.tugasTerlambat }}
                </span>
              </td>

              <td class="text-center">
                <span
                  :class="[
                    'font-semibold',
                    k.performance >= 80 ? 'text-green-600' :
                    k.performance >= 60 ? 'text-yellow-600' :
                    'text-red-600'
                  ]"
                >
                  {{ k.performance }}%
                </span>
              </td>

              <td class="text-center">
                <span class="text-purple-600 font-semibold">
                  {{ k.contribution }}%
                </span>
              </td>

              <td class="text-center">
                <span
                  :class="[
                    'px-2 py-1 mx-2 rounded text-xs flex items-center justify-center gap-1',
                    k.skor >= 80 ? 'bg-green-100 text-green-700' :
                    k.skor >= 60 ? 'bg-yellow-100 text-yellow-700' :
                    'bg-red-100 text-red-700'
                  ]"
                >
                  <span class="icon-[tabler--star]"></span>
                  {{ k.skor }}
                </span>
              </td>

            </tr>
          </tbody>

        </table>

      </div>
   </div>

  </div>

</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import Title from '@/components/Title.vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const stats = ref({})
const dataKaryawan = ref([])
const distribusiBeban = ref([])
const bagianList = ref([])

const filterBagian = ref('')

const detailKaryawan = (id) => {
  router.push(`/kinerja/karyawan/${id}`)
}

const printKinerja = () => {
  const url = `${window.location.origin}/#/print-kinerja`
  window.open(url, '_blank')
}

const percent = (a, b) => {
  if (!b) return 0
  return Math.round((a / b) * 100)
}

const barWidth = (val) => {
  const max = Math.max(...distribusiBeban.value.map(x => x.tugas || 1))
  return (val / max) * 100
}

const filteredKaryawan = computed(() => {
  if (!filterBagian.value) return dataKaryawan.value
  return dataKaryawan.value.filter(k => k.bagian === filterBagian.value)
})

const loadData = async () => {
  const resStats = await axios.get('http://localhost:8000/api/dashboard-kinerja')
  stats.value = resStats.data

  const resKaryawan = await axios.get('http://localhost:8000/api/performa-karyawan')
  dataKaryawan.value = resKaryawan.data

  distribusiBeban.value = dataKaryawan.value.map(k => ({
    id: k.id,
    nama: k.name,
    tugas: k.totalTugas
  }))
}

const loadBagian = async () => {
  const res = await axios.get('http://localhost:8000/api/bagian')
  bagianList.value = res.data
}

/* 🔥 LOGOUT */
const logout = () => {
  if (confirm('Yakin ingin logout?')) {
    localStorage.removeItem('user')
    router.push('/login')
  }
}

onMounted(() => {
  loadData()
  loadBagian()
})
</script>