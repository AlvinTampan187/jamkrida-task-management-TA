<template>
  <Title title="Detail Tugas">
    <template #actions>
      <button @click="saveTask" class="flex items-center bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 mr-2">
        <span class="icon-[tabler--device-floppy] h-4 w-4 mr-2"></span>
        Simpan
      </button>
      <button @click="goBack" class="flex items-center bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
        <span class="icon-[tabler--arrow-left] h-4 w-4 mr-2"></span>
        Kembali
      </button>
    </template>
  </Title>

  <div class="pt-4">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Kolom Kiri - Informasi Tugas -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Informasi Utama Tugas -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Tugas</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Judul Tugas</label>
              <input 
                v-model="task.judul" 
                type="text" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500"
                placeholder="Masukkan judul tugas"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Prioritas</label>
              <select 
                v-model="task.prioritas" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500"
              >
                <option value="rendah">Rendah</option>
                <option value="sedang">Sedang</option>
                <option value="tinggi">Tinggi</option>
                <option value="kritis">Kritis</option>
              </select>
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Tugas</label>
              <textarea 
                v-model="task.deskripsi" 
                rows="4" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500"
                placeholder="Deskripsikan detail tugas secara lengkap..."
              ></textarea>
            </div>
          </div>
        </div>

        <!-- Rincian Tugas -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Rincian Tugas</h3>
            <button 
              @click="addDetail" 
              class="flex items-center bg-teal-600 text-white px-3 py-2 rounded hover:bg-teal-700 text-sm"
            >
              <span class="icon-[tabler--plus] h-4 w-4 mr-1"></span>
              Tambah Rincian
            </button>
          </div>
          <div class="space-y-3">
            <div 
              v-for="(detail, index) in task.rincian" 
              :key="index" 
              class="flex items-start space-x-3 p-3 border border-gray-200 rounded-lg"
            >
              <input 
                type="checkbox" 
                v-model="detail.selesai" 
                class="mt-1.5"
                @change="updateProgress"
              >
              <div class="flex-1">
                <input 
                  v-model="detail.deskripsi" 
                  type="text" 
                  class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-teal-500"
                  placeholder="Deskripsi rincian tugas..."
                >
                <div class="flex items-center mt-2 space-x-4">
                  <span class="text-xs text-gray-500">Estimasi:</span>
                  <input 
                    v-model="detail.estimasi" 
                    type="number" 
                    class="w-20 border border-gray-300 rounded-md px-2 py-1 text-sm"
                    placeholder="Jam"
                  >
                  <span class="text-xs text-gray-500">Status:</span>
                  <select 
                    v-model="detail.status" 
                    class="text-sm border border-gray-300 rounded-md px-2 py-1"
                  >
                    <option value="todo">Belum Mulai</option>
                    <option value="progress">Dalam Pengerjaan</option>
                    <option value="review">Review</option>
                    <option value="done">Selesai</option>
                  </select>
                </div>
              </div>
              <button 
                @click="removeDetail(index)" 
                class="text-red-600 hover:text-red-800 p-1"
              >
                <span class="icon-[tabler--trash] h-4 w-4"></span>
              </button>
            </div>
          </div>
        </div>

        <!-- Diskusi -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Diskusi</h3>
          <div class="space-y-4">
            <!-- Input Komentar Baru -->
            <div class="flex space-x-3">
              <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-medium text-teal-600">Y</span>
              </div>
              <div class="flex-1">
                <textarea 
                  v-model="newComment" 
                  rows="3" 
                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500"
                  placeholder="Tulis komentar..."
                ></textarea>
                <div class="flex justify-end mt-2">
                  <button 
                    @click="addComment" 
                    :disabled="!newComment.trim()"
                    class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    Kirim Komentar
                  </button>
                </div>
              </div>
            </div>

            <!-- Daftar Komentar -->
            <div v-for="comment in task.diskusi" :key="comment.id" class="flex space-x-3">
              <div class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                <span class="text-sm font-medium text-teal-600">{{ comment.inisial }}</span>
              </div>
              <div class="flex-1 bg-gray-50 rounded-lg p-4">
                <div class="flex justify-between items-start mb-2">
                  <span class="font-medium text-gray-900">{{ comment.nama }}</span>
                  <span class="text-xs text-gray-500">{{ comment.waktu }}</span>
                </div>
                <p class="text-gray-700 text-sm">{{ comment.pesan }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Kolom Kanan - Informasi Tambahan -->
      <div class="space-y-6">
        <!-- Status & Progress -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Status & Progress</h3>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Status Tugas</label>
              <select 
                v-model="task.status" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500"
              >
                <option value="pending">Pending</option>
                <option value="progress">Dalam Pengerjaan</option>
                <option value="review">Review</option>
                <option value="completed">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Progress</label>
              <div class="flex items-center space-x-3">
                <div class="flex-1 bg-gray-200 rounded-full h-2">
                  <div 
                    class="bg-teal-600 h-2 rounded-full transition-all duration-300" 
                    :style="{ width: `${task.progress}%` }"
                  ></div>
                </div>
                <span class="text-sm font-medium text-gray-700">{{ task.progress }}%</span>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
              <div>
                <span class="text-gray-500">Dibuat:</span>
                <p class="font-medium">{{ task.tanggalDibuat }}</p>
              </div>
              <div>
                <span class="text-gray-500">Deadline:</span>
                <input 
                  v-model="task.deadline" 
                  type="date" 
                  class="w-full border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-teal-500"
                >
              </div>
            </div>
          </div>
        </div>

        <!-- Penugasan -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Penugasan</h3>
          <div class="space-y-3">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Ditugaskan Kepada</label>
              <select 
                v-model="task.ditugaskanKepada" 
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-teal-500"
              >
                <option value="">Pilih Karyawan</option>
                <option v-for="karyawan in daftarKaryawan" :key="karyawan.id" :value="karyawan.id">
                  {{ karyawan.nama }} - {{ karyawan.departemen }}
                </option>
              </select>
            </div>

            <div v-if="task.ditugaskanKepada" class="bg-teal-50 rounded-lg p-3">
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-teal-100 flex items-center justify-center">
                  <span class="text-sm font-medium text-teal-600">
                    {{ getKaryawanInisial(task.ditugaskanKepada) }}
                  </span>
                </div>
                <div>
                  <p class="font-medium text-gray-900 text-sm">{{ getKaryawanNama(task.ditugaskanKepada) }}</p>
                  <p class="text-xs text-gray-500">{{ getKaryawanDepartemen(task.ditugaskanKepada) }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Lampiran -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Lampiran</h3>
            <button class="flex items-center text-teal-600 hover:text-teal-700 text-sm">
              <span class="icon-[tabler--paperclip] h-4 w-4 mr-1"></span>
              Tambah File
            </button>
          </div>
          <div class="space-y-2">
            <div v-for="file in task.lampiran" :key="file.id" class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
              <div class="flex items-center space-x-3">
                <span class="icon-[tabler--file] h-5 w-5 text-gray-400"></span>
                <div>
                  <p class="text-sm font-medium text-gray-700">{{ file.nama }}</p>
                  <p class="text-xs text-gray-500">{{ file.ukuran }}</p>
                </div>
              </div>
              <button class="text-red-600 hover:text-red-800">
                <span class="icon-[tabler--trash] h-4 w-4"></span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import Title from '@/components/Title.vue'

const router = useRouter()

// Data tugas
const task = ref({
  id: 1,
  judul: 'Pengembangan Fitur Dashboard Monitoring',
  deskripsi: 'Mengembangkan dashboard untuk memantau kinerja karyawan dan progress tugas',
  prioritas: 'tinggi',
  status: 'progress',
  progress: 65,
  tanggalDibuat: '2024-01-15',
  deadline: '2024-02-15',
  ditugaskanKepada: 2,
  rincian: [
    { id: 1, deskripsi: 'Analisis kebutuhan sistem', estimasi: 8, status: 'done', selesai: true },
    { id: 2, deskripsi: 'Desain UI/UX dashboard', estimasi: 16, status: 'done', selesai: true },
    { id: 3, deskripsi: 'Development frontend', estimasi: 24, status: 'progress', selesai: false },
    { id: 4, deskripsi: 'Testing dan debugging', estimasi: 12, status: 'todo', selesai: false }
  ],
  diskusi: [
    { id: 1, nama: 'Ahmad Santoso', inisial: 'AS', waktu: '2 jam lalu', pesan: 'Progress development frontend sudah 80% menyelesaikan komponen utama.' },
    { id: 2, nama: 'Siti Rahayu', inisial: 'SR', waktu: '1 jam lalu', pesan: 'Apakah perlu menambahkan fitur export laporan?' }
  ],
  lampiran: [
    { id: 1, nama: 'dokumen-spesifikasi.pdf', ukuran: '2.4 MB' },
    { id: 2, nama: 'mockup-dashboard.fig', ukuran: '1.8 MB' }
  ]
})

// Data komentar baru
const newComment = ref('')

// Daftar karyawan untuk penugasan
const daftarKaryawan = ref([
  { id: 1, nama: 'Ahmad Santoso', departemen: 'IT', inisial: 'AS' },
  { id: 2, nama: 'Siti Rahayu', departemen: 'IT', inisial: 'SR' },
  { id: 3, nama: 'Budi Pratama', departemen: 'Marketing', inisial: 'BP' },
  { id: 4, nama: 'Dewi Anggraini', departemen: 'Keuangan', inisial: 'DA' }
])

// Fungsi untuk menambah rincian tugas
const addDetail = () => {
  task.value.rincian.push({
    id: Date.now(),
    deskripsi: '',
    estimasi: 0,
    status: 'todo',
    selesai: false
  })
}

// Fungsi untuk menghapus rincian
const removeDetail = (index) => {
  task.value.rincian.splice(index, 1)
  updateProgress()
}

// Fungsi update progress otomatis
const updateProgress = () => {
  const total = task.value.rincian.length
  const completed = task.value.rincian.filter(d => d.selesai).length
  task.value.progress = total > 0 ? Math.round((completed / total) * 100) : 0
}

// Fungsi menambah komentar
const addComment = () => {
  if (newComment.value.trim()) {
    task.value.diskusi.unshift({
      id: Date.now(),
      nama: 'Anda',
      inisial: 'Y',
      waktu: 'Baru saja',
      pesan: newComment.value.trim()
    })
    newComment.value = ''
  }
}

// Fungsi helper untuk mendapatkan data karyawan
const getKaryawanNama = (id) => {
  const karyawan = daftarKaryawan.value.find(k => k.id === id)
  return karyawan ? karyawan.nama : ''
}

const getKaryawanInisial = (id) => {
  const karyawan = daftarKaryawan.value.find(k => k.id === id)
  return karyawan ? karyawan.inisial : ''
}

const getKaryawanDepartemen = (id) => {
  const karyawan = daftarKaryawan.value.find(k => k.id === id)
  return karyawan ? karyawan.departemen : ''
}

// Fungsi simpan
const saveTask = () => {
  console.log('Menyimpan tugas:', task.value)
  // Implementasi save logic di sini
}

// Fungsi kembali
const goBack = () => {
  router.back()
}

onMounted(() => {
  updateProgress()
})
</script>