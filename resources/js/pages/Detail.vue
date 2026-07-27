<template>

  <!-- HEADER -->
  <Title title="Detail Tugas">
    <template #actions>

      <div class="flex gap-2">

        <button
          @click="saveTask"
          class="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-800"
        >
          Simpan
        </button>

        <button
          @click="goBack"
          class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-800"
        >
          Kembali
        </button>

        <button
          @click="printPDF"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-800"
        >
          Cetak PDF
        </button>

      </div>

    </template>
  </Title>

  <div class="p-4 md:p-6 lg:p-8">

    <div class="pt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- LEFT -->
      <div class="lg:col-span-2 space-y-6 min-w-0">

        <!-- INFORMASI -->
        <div class="bg-white p-6 rounded shadow">
          <h1 class="font-semibold mb-3">Informasi Tugas</h1>

          <input 
            v-model="task.kategori" 
            placeholder="Kategori"
            :disabled="user?.role !== 'atasan'"
            class="w-full border p-2 mb-2 rounded"
            :class="user?.role !== 'atasan' ? 'bg-gray-100 cursor-not-allowed' : ''"
          />

          <input 
            v-model="task.bagian" 
            placeholder="Bagian"
            :disabled="user?.role !== 'atasan'"
            class="w-full border p-2 mb-2 rounded"
            :class="user?.role !== 'atasan' ? 'bg-gray-100 cursor-not-allowed' : ''"
          />

          <textarea 
            v-model="task.keterangan_laporan"
            placeholder="Keterangan"
            :disabled="user?.role !== 'atasan'"
            class="w-full border p-2 rounded"
            :class="user?.role !== 'atasan' ? 'bg-gray-100 cursor-not-allowed' : ''"
          ></textarea>
        </div>
        
        <!-- RINCIAN -->
        <div class="bg-white p-6 rounded shadow">

          <div class="flex justify-between mb-3">
            <h3 class="font-semibold">Rincian Tugas</h3>

            <!-- TAMBAH HANYA UNTUK KARYAWAN -->
            <button 
              v-if="user?.role === 'karyawan' && task.status !== 'Selesai'" 
              @click="addDetail"
              class="bg-teal-600 text-white px-2 py-1 rounded hover:bg-teal-800"
            >
              + Tambah
            </button>

          </div>


          <div 
            v-for="(item,index) in details"
            :key="item.id || index"
            class="border rounded p-3 mb-3"
          >


            <div class="flex gap-3 items-end flex-wrap">


              <!-- DESKRIPSI -->
              <div class="flex-1">

                <label class="text-sm text-gray-600 block mb-1">
                  Deskripsi
                </label>

                <input 
                  v-model="item.deskripsi"

                  :disabled="!canEditDetail(item)"

                  class="w-full border p-1 rounded"

                  :class="!canEditDetail(item)
                  ? 'bg-gray-100 cursor-not-allowed'
                  : ''"
                />

              </div>



              <!-- STATUS -->
              <div>

                <label class="text-sm text-gray-600 block mb-1">
                  Status
                </label>


                <select
                  v-model="item.status"

                  disabled

                  class="border p-1 rounded bg-gray-100 cursor-not-allowed"
                >

                  <option>Penugasan</option>
                  <option>Progres</option>
                  <option>Menunggu Persetujuan</option>
                  <option>Selesai</option>

                </select>


              </div>



              <!-- TANGGAL -->
              <div>

                <label class="text-sm text-gray-600 block mb-1">
                  Estimasi Selesai
                </label>


                <input 
                  type="date"

                  v-model="item.tanggal"

                  :disabled="!canEditDetail(item)"

                  class="border p-1 rounded"

                  :class="!canEditDetail(item)
                  ? 'bg-gray-100 cursor-not-allowed'
                  : ''"
                />

              </div>



              <!-- BUTTON KARYAWAN AJUKAN SELESAI -->
              <button
                v-if="
                  user?.role === 'karyawan'
                  && task.status !== 'Selesai'
                  && item.id
                  && item.status !== 'Selesai'
                  && item.status !== 'Menunggu Persetujuan'
                "

                @click="ajukanSelesai(item.id)"

                class="bg-green-600 text-white px-3 py-1 rounded mb-1 hover:bg-green-800"
              >
                Ajukan Selesai
              </button>



              <!-- BUTTON ATASAN SETUJUI -->
              <button

                v-if="
                  user?.role === 'atasan'
                  && item.status === 'Menunggu Persetujuan'
                "

                @click="setujuiSelesai(item.id)"

                class="bg-blue-600 text-white px-3 py-1 rounded mb-1 hover:bg-blue-800"
              >
                Setujui Selesai
              </button>



              <!-- DELETE HANYA ATASAN -->
              <button 
                v-if="user?.role === 'atasan' && task.status !== 'Selesai'"

                @click="removeDetail(index)"

                class="text-xl text-red-500 hover:text-red-700 mb-1"
              >

                <span class="icon-[material-symbols-light--delete-outline]"></span>

              </button>


            </div>



            <!-- PEMBUAT RINCIAN -->
            <div class="mt-3 text-sm text-gray-500">

              Dibuat oleh:

              <span class="font-semibold text-teal-600">
                {{ item.user?.name || 'Tidak diketahui' }}
              </span>

            </div>


          </div>

        </div>

        <!-- DISKUSI -->
        <div class="bg-white p-6 rounded shadow">
          <h3 class="font-semibold mb-3">Diskusi</h3>

          <div class="max-h-64 overflow-y-auto mb-3 space-y-2">
            <div v-for="d in diskusi" :key="d.id"
              class="p-3 rounded bg-gray-100">

              <div class="flex justify-between">
                <p class="text-sm font-semibold text-teal-600">
                  {{ d.user?.name || 'User' }}
                </p>
                <p class="text-xs text-gray-500">
                  {{ formatDate(d.created_at) }}
                </p>
              </div>

              <p class="text-sm mt-1">{{ d.pesan }}</p>
            </div>
          </div>

          <div class="flex gap-2">
            <input v-model="pesan"
              placeholder="Tulis pesan..."
              class="flex-1 border p-2 rounded" />

            <button
              v-if="task.status !== 'Selesai'"
              @click="kirimPesan"
              class="bg-teal-600 text-white px-4 rounded hover:bg-teal-800">
              Kirim
            </button>
          </div>
        </div>

      </div>

      <!-- RIGHT -->
      <div class="space-y-6 lg:sticky lg:top-6 self-start min-w-0">

        <!-- STATUS -->
        <div class="bg-white p-6 rounded shadow">
          <h3 class="font-semibold mb-3">Status</h3>

          <div 
            class="font-semibold mb-2"
            :class="task.status === 'Terlambat' ? 'text-red-600' : 'text-res-800'"
          >
            {{ task.status }}
          </div>

          <div class="mb-3">
            <div class="flex justify-between text-sm">
              <span>Progress</span>
              <span>{{ progress }}%</span>
            </div>

            <div class="bg-gray-200 h-3 rounded">
              <div class="bg-teal-600 h-3 rounded"
                :style="{ width: progress + '%' }"></div>
            </div>
          </div>
          
          <span>Dibuat pada:</span>
          <input type="date"
            v-model="createdDate"
            disabled
            class="w-full border p-2 mb-2 rounded bg-gray-100" />

          <span>Deadline tugas:</span>
          <input type="date"
            v-model="deadline"
            :disabled="user?.role !== 'atasan'"
            class="w-full border p-2 rounded"
            :class="user?.role !== 'atasan' ? 'bg-gray-100 cursor-not-allowed' : ''"
          />
        </div>

        <!-- PENUGASAN -->
        <div v-if="user?.role === 'atasan'"
          class="bg-white p-6 rounded shadow">

          <h3 class="font-semibold mb-3">Penugasan</h3>

          <select 
            v-if="users.length"
            v-model="assignedUser"
            class="w-full border p-2 mb-2 rounded"
          >
            <option value="">Pilih Karyawan</option>
            <option v-for="u in users" :key="u.id" :value="u.id">
              {{ u.name }} - {{ u.bagian }}
            </option>
          </select>

          <button @click="assignTask"
            v-if="task.status !== 'Selesai'"
            class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-800">
            Tugaskan
          </button>

          <ul class="mt-3 text-sm list-disc ml-5">
            <li v-for="u in assignedUsers" :key="u.id">
              {{ u.name }} - {{ u.bagian }}
            </li>
          </ul>
        </div>

        <!-- LAMPIRAN -->
        <div class="bg-white p-6 rounded shadow">

          <h3 class="font-semibold mb-3">Lampiran</h3>

          <input 
            v-model="keteranganFile"
            placeholder="Keterangan file (contoh: Laporan, Screenshot error)"
            class="w-full border p-2 rounded mb-2"
          />

          <label 
            v-if="task.status !== 'Selesai'"
            class="flex flex-col items-center justify-center w-full p-6 border-2 border-dashed border-gray-300 rounded cursor-pointer hover:border-teal-500 hover:bg-teal-50 transition"
          >
            <span class="text-gray-500 mb-2">
              Klik untuk pilih file
            </span>

           
            <input 
              type="file"
              ref="fileInput"
              @change="handleFile"
              class="hidden"
            />
          </label>
          <p 
            v-if="fileName"
            class="text-sm text-gray-600 mt-2"
          >
            File dipilih:
            <span class="font-semibold">
              {{ fileName }}
            </span>
          </p>

          <button 
            v-if="task.status !== 'Selesai'"
            @click="uploadFile"
            class="w-full bg-teal-600 text-white py-2 rounded my-2 hover:bg-teal-800">
            Upload
          </button>

          <div v-for="file in lampiran" :key="file.id"
            class="flex justify-between items-center border-b py-2">

            <span>
              {{ file.keterangan 
                  ? file.keterangan + '.' + file.nama_file.split('.').pop()
                  : file.nama_file 
              }}
            </span>

            <div class="flex gap-3 text-xl">

              <!-- DOWNLOAD -->
              <a :href="`${API}/lampiran/${file.id}/download`"
                class="text-blue-500 hover:text-blue-700">
                <span class="icon-[material-symbols-light--download-sharp]"></span>
              </a>

              <!-- DELETE -->
              <button 
                @click="deleteFile(file.id)"
                :disabled="user.role !== 'atasan' || task.status === 'Selesai'"
                :class="
                  user.role === 'atasan' && task.status !== 'Selesai'
                    ? 'text-red-500 hover:text-red-700'
                    : 'text-gray-400 cursor-not-allowed'
                "
              >
                <span class="icon-[material-symbols-light--delete-outline]"></span>
              </button>

            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import Title from '@/components/Title.vue'

const API = 'http://localhost:8000/api'

const router = useRouter()
const route = useRoute()

const task = ref({})
const details = ref([])
const users = ref([])
const assignedUsers = ref([])
const lampiran = ref([])
const diskusi = ref([])

const selectedFile = ref(null)
const fileInput = ref(null)
const fileName = ref('')

const assignedUser = ref('')
const pesan = ref('')
const progress = ref(0)
const createdDate = ref('')
const deadline = ref('')
const keteranganFile = ref('')

const user = JSON.parse(localStorage.getItem('user') || '{}')

const printPDF = () => {
  const url = `${window.location.origin}/#/print/${task.value.id}`
  window.open(url, '_blank')
}
/* LOAD */
const loadTask = async () => {
  try {
    const res = await axios.get(`${API}/tugas/${route.params.id}`)
    task.value = res.data || {}

    createdDate.value = task.value.created_at?.substring(0,10) || ''
    deadline.value = task.value.deadline?.substring(0,10) || ''

    await loadProgress()
  } catch (e) {
    console.error(e)
  }
}

const loadDetail = async () => {
  try {
    const res = await axios.get(`${API}/tugas/${route.params.id}/detail`)

    details.value = (res.data || []).map(item => ({
      ...item,
      tanggal: item.tanggal ? item.tanggal.substring(0,10) : ''
    }))

  } catch (e) {
    console.log(e)
  }
}

const loadUsers = async () => {
  const res = await axios.get(`${API}/users/karyawan`)
  users.value = res.data || []
}

const loadAssignedUsers = async () => {
  const res = await axios.get(`${API}/tugas/${route.params.id}/users`)
  assignedUsers.value = res.data || []
}

const loadLampiran = async () => {
  const res = await axios.get(`${API}/tugas/${route.params.id}/lampiran`)
  lampiran.value = res.data || []
}

const loadDiskusi = async () => {
  const res = await axios.get(`${API}/tugas/${route.params.id}/diskusi`)
  diskusi.value = res.data || []
}

const loadProgress = async () => {
  const res = await axios.get(`${API}/tugas/${route.params.id}/progress`)
  progress.value = res.data?.progress || 0
}

/* ACTION */
const addDetail = () => {

  details.value.push({
    deskripsi: '',
    status: 'Progres',
    estimasi: '',
    tanggal: ''
  })

}

const canEditDetail = (item) => {

  // tugas sudah selesai tidak boleh diedit
  if (task.value.status === 'Selesai') {
    return false
  }


  // atasan hanya melihat
  if (user?.role === 'atasan') {
    return false
  }


  // rincian baru
  if (!item.id) {
    return true
  }


  // karyawan hanya edit miliknya
  return item.user_id === user.id

}

const ajukanSelesai = async (id) => {
  try {

    await axios.post(`${API}/tugas-detail/${id}/ajukan`, {
      user_id: user.id
    })


    alert('Berhasil mengajukan selesai')

    await loadDetail()

  } catch(error) {

    console.log(error)
    alert(error.response?.data?.message || 'Gagal mengajukan selesai')

  }
}



const setujuiSelesai = async(id)=>{

 await axios.post(`${API}/tugas-detail/${id}/setujui`,{
    user_id:user.id
 })

 await loadDetail()

 alert('Tugas disetujui')

}

const removeDetail = async (index) => {
  try {
    const item = details.value[index]

    if (item?.id) {
      await axios.delete(`${API}/tugas-detail/${item.id}`)
    }

    details.value.splice(index, 1)

    alert('Rincian tugas berhasil dihapus')
  } catch (error) {
    console.log(error)
    alert('Gagal menghapus rincian tugas')
  }
}

const saveTask = async () => {
  try {

    task.value.deadline = deadline.value


    // simpan informasi tugas (khusus atasan)
    await axios.put(`${API}/tugas/${task.value.id}`, {
      kategori: task.value.kategori,
      bagian: task.value.bagian,
      keterangan_laporan: task.value.keterangan_laporan,
      deadline: task.value.deadline
    })


    // hanya simpan rincian baru
    const newDetails = details.value.filter(
      item => !item.id
    )


    if (newDetails.length > 0) {

      await axios.post(`${API}/tugas/${task.value.id}/detail`, {
        details: newDetails,
        user_id: user.id
      })

    }


    alert('Berhasil disimpan')


    // reload data dari database
    await loadTask()
    await loadDetail()
    await loadProgress()


  } catch (e) {

    alert('Gagal simpan')
    console.error(e)

  }
}

const assignTask = async () => {
  if (!assignedUser.value) {
    alert('Pilih karyawan terlebih dahulu')
    return
  }

  try {
    await axios.post(`${API}/tugas/${task.value.id}/assign`, {
      user_id: assignedUser.value
    })

    assignedUser.value = ''
    await loadAssignedUsers()

    alert('Tugas berhasil ditugaskan')
  } catch (error) {
    console.log(error)
    alert('Gagal menugaskan tugas')
  }
}

const handleFile = (e) => {

  selectedFile.value = e.target.files[0]

  if (selectedFile.value) {
    fileName.value = selectedFile.value.name
  }

}

const uploadFile = async () => {

  if (!keteranganFile.value || keteranganFile.value.trim() === '') {
    alert('Keterangan file wajib diisi!')
    return
  }

  if (!selectedFile.value) {
    alert('Pilih file dulu')
    return
  }


  try {

    const formData = new FormData()

    formData.append('file', selectedFile.value)
    formData.append('keterangan', keteranganFile.value)


    await axios.post(
      `${API}/tugas/${task.value.id}/lampiran`,
      formData,
      {
        headers:{
          'Content-Type':'multipart/form-data'
        }
      }
    )


    alert('Lampiran berhasil diupload')


    await loadLampiran()


    selectedFile.value = null
    fileName.value = ''
    keteranganFile.value = ''

    if(fileInput.value){
      fileInput.value.value = ''
    }


  } catch(error){

    console.log(error.response)

    alert(
      error.response?.data?.message 
      || 'Gagal upload lampiran'
    )

  }

}

const deleteFile = async (id) => {
  try {
    await axios.delete(`${API}/lampiran/${id}`)
    await loadLampiran()

    alert('Lampiran berhasil dihapus')
  } catch (error) {
    console.log(error)
    alert('Gagal menghapus lampiran')
  }
}

const kirimPesan = async () => {

  if (!pesan.value.trim()) {
    alert('Pesan tidak boleh kosong')
    return
  }


  try {

    await axios.post(
      `${API}/tugas/${task.value.id}/diskusi`,
      {
        user_id: user.id,
        pesan: pesan.value
      }
    )


    pesan.value = ''


    await loadDiskusi()


  } catch(error){

    console.log(error.response)

    alert(
      error.response?.data?.message 
      || 'Gagal mengirim diskusi'
    )

  }

}

const formatDate = (d) => new Date(d).toLocaleString()

const goBack = () => router.back()

onMounted(async () => {
  await Promise.all([
    loadTask(),
    loadDetail(),
    loadUsers(),
    loadAssignedUsers(),
    loadLampiran(),
    loadDiskusi()
  ])
})
</script>