<template>
  <Title title="Dashboard Monitoring Kinerja Karyawan">
    <template #actions>
      <button @click="exportReport" class="flex items-center bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">
        <span class="icon-[tabler--download] h-4 w-4 mr-2"></span>
        Export Laporan
      </button>
    </template>
  </Title>

  <div class="pt-4">
    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Total Tugas -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="rounded-full bg-blue-100 p-3 mr-4">
            <span class="icon-[tabler--clipboard-list] h-6 w-6 text-blue-600"></span>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-600">Total Tugas</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.totalTugas }}</p>
          </div>
        </div>
        <div class="mt-4">
          <div class="flex items-center text-sm text-gray-500">
            <span class="icon-[tabler--trending-up] h-4 w-4 text-green-500 mr-1"></span>
            <span>{{ stats.tugasSelesai }} selesai</span>
          </div>
        </div>
      </div>

      <!-- Tugas Selesai -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="rounded-full bg-green-100 p-3 mr-4">
            <span class="icon-[tabler--circle-check] h-6 w-6 text-green-600"></span>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-600">Tugas Selesai</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.tugasSelesai }}</p>
          </div>
        </div>
        <div class="mt-4">
          <div class="flex items-center text-sm text-gray-500">
            <span>{{ Math.round((stats.tugasSelesai / stats.totalTugas) * 100) }}% dari total</span>
          </div>
        </div>
      </div>

      <!-- Keterlambatan -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="rounded-full bg-red-100 p-3 mr-4">
            <span class="icon-[tabler--alert-triangle] h-6 w-6 text-red-600"></span>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-600">Keterlambatan</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.keterlambatan }}</p>
          </div>
        </div>
        <div class="mt-4">
          <div class="flex items-center text-sm text-gray-500">
            <span class="icon-[tabler--trending-down] h-4 w-4 text-red-500 mr-1"></span>
            <span>{{ Math.round((stats.keterlambatan / stats.totalTugas) * 100) }}% dari total</span>
          </div>
        </div>
      </div>

      <!-- Rata-rata Penyelesaian -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="rounded-full bg-purple-100 p-3 mr-4">
            <span class="icon-[tabler--clock] h-6 w-6 text-purple-600"></span>
          </div>
          <div>
            <p class="text-sm font-medium text-gray-600">Rata-rata Waktu</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.rataPenyelesaian }}h</p>
          </div>
        </div>
        <div class="mt-4">
          <div class="flex items-center text-sm text-gray-500">
            <span>per tugas</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
      <!-- Distribusi Beban Kerja -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Beban Kerja</h3>
        <div class="space-y-4">
          <div v-for="karyawan in distribusiBeban" :key="karyawan.id" class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center mr-3">
                <span class="text-sm font-medium text-teal-600">{{ karyawan.inisial }}</span>
              </div>
              <span class="text-sm font-medium text-gray-700">{{ karyawan.nama }}</span>
            </div>
            <div class="flex items-center">
              <div class="w-32 bg-gray-200 rounded-full h-2 mr-3">
                <div 
                  class="bg-teal-600 h-2 rounded-full" 
                  :style="{ width: `${(karyawan.tugas / Math.max(...distribusiBeban.map(k => k.tugas))) * 100}%` }"
                ></div>
              </div>
              <span class="text-sm font-medium text-gray-900">{{ karyawan.tugas }} tugas</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Status Tugas -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Tugas</h3>
        <div class="flex items-center justify-center">
          <div class="relative w-48 h-48">
            <!-- Pie Chart sederhana -->
            <div class="absolute inset-0 flex items-center justify-center">
              <div class="text-center">
                <p class="text-2xl font-bold text-gray-900">{{ stats.totalTugas }}</p>
                <p class="text-sm text-gray-500">Total Tugas</p>
              </div>
            </div>
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-4">
          <div class="flex items-center">
            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
            <span class="text-sm text-gray-600">Selesai: {{ stats.tugasSelesai }}</span>
          </div>
          <div class="flex items-center">
            <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
            <span class="text-sm text-gray-600">Proses: {{ stats.tugasProses }}</span>
          </div>
          <div class="flex items-center">
            <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
            <span class="text-sm text-gray-600">Terlambat: {{ stats.keterlambatan }}</span>
          </div>
          <div class="flex items-center">
            <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
            <span class="text-sm text-gray-600">Pending: {{ stats.tugasPending }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Performa Karyawan -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Performa Karyawan</h3>
        <div class="flex items-center space-x-4">
          <select v-model="filterDepartemen" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">Semua Departemen</option>
            <option value="IT">IT</option>
            <option value="Marketing">Marketing</option>
            <option value="Keuangan">Keuangan</option>
            <option value="HRD">HRD</option>
          </select>
        </div>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-teal-800">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Karyawan</th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Departemen</th>
              <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Total Tugas</th>
              <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Selesai</th>
              <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Terlambat</th>
              <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Rate Penyelesaian</th>
              <th scope="col" class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Skor</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="karyawan in filteredKaryawan" :key="karyawan.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center mr-3">
                    <span class="text-sm font-medium text-teal-600">{{ karyawan.inisial }}</span>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ karyawan.nama }}</div>
                    <div class="text-sm text-gray-500">{{ karyawan.jabatan }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ karyawan.departemen }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">{{ karyawan.totalTugas }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">{{ karyawan.tugasSelesai }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                <span :class="['inline-flex rounded-full px-2 text-xs font-semibold leading-5', 
                  karyawan.tugasTerlambat > 0 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800']">
                  {{ karyawan.tugasTerlambat }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                {{ Math.round((karyawan.tugasSelesai / karyawan.totalTugas) * 100) }}%
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                <span :class="['inline-flex rounded-full px-2 text-xs font-semibold leading-5', 
                  karyawan.skor >= 80 ? 'bg-green-100 text-green-800' :
                  karyawan.skor >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800']">
                  {{ karyawan.skor }}
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
import Title from '@/components/Title.vue'

// Data statistik
const stats = ref({
  totalTugas: 156,
  tugasSelesai: 124,
  keterlambatan: 12,
  tugasProses: 15,
  tugasPending: 5,
  rataPenyelesaian: 24
})

// Data distribusi beban kerja
const distribusiBeban = ref([
  { id: 1, nama: 'Ahmad Santoso', inisial: 'AS', tugas: 25 },
  { id: 2, nama: 'Siti Rahayu', inisial: 'SR', tugas: 18 },
  { id: 3, nama: 'Budi Pratama', inisial: 'BP', tugas: 22 },
  { id: 4, nama: 'Dewi Anggraini', inisial: 'DA', tugas: 15 },
  { id: 5, nama: 'Rizki Ramadhan', inisial: 'RR', tugas: 20 }
])

// Data performa karyawan
const dataKaryawan = ref([
  { id: 1, nama: 'Ahmad Santoso', jabatan: 'Team Lead', departemen: 'IT', inisial: 'AS', totalTugas: 25, tugasSelesai: 22, tugasTerlambat: 1, skor: 88 },
  { id: 2, nama: 'Siti Rahayu', jabatan: 'Senior Developer', departemen: 'IT', inisial: 'SR', totalTugas: 18, tugasSelesai: 17, tugasTerlambat: 0, skor: 94 },
  { id: 3, nama: 'Budi Pratama', jabatan: 'Marketing Manager', departemen: 'Marketing', inisial: 'BP', totalTugas: 22, tugasSelesai: 18, tugasTerlambat: 3, skor: 75 },
  { id: 4, nama: 'Dewi Anggraini', jabatan: 'Accountant', departemen: 'Keuangan', inisial: 'DA', totalTugas: 15, tugasSelesai: 14, tugasTerlambat: 1, skor: 85 },
  { id: 5, nama: 'Rizki Ramadhan', jabatan: 'HR Specialist', departemen: 'HRD', inisial: 'RR', totalTugas: 20, tugasSelesai: 16, tugasTerlambat: 2, skor: 78 }
])

// Filter
const filterDepartemen = ref('')

// Computed untuk filter data
const filteredKaryawan = computed(() => {
  if (!filterDepartemen.value) return dataKaryawan.value
  return dataKaryawan.value.filter(k => k.departemen === filterDepartemen.value)
})

// Fungsi export
const exportReport = () => {
  console.log('Exporting report...')
  // Implementasi export logic di sini
}

onMounted(() => {
  // Load data awal jika diperlukan
})
</script>