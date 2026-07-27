<template>
  <div class="p-8 text-black text-sm">

    <!-- HEADER -->
    <div class="text-center mb-6">
      <h1 class="text-xl font-bold">LAPORAN KINERJA KARYAWAN</h1>
      <p class="text-xs">Sistem Task Management dan Monitoring Kinerja</p>
    </div>

    <!-- ================= RINGKASAN ================= -->
    <table class="w-full border border-collapse text-sm mb-6">
      <tr class="bg-gray-100 font-semibold">
        <td colspan="8" class="border p-2 text-center">
          Ringkasan Kinerja
        </td>
      </tr>

      <tr>
        <td class="border p-2"><b>Total Tugas</b></td>
        <td class="border p-2 text-center">{{ stats.totalTugas }}</td>

        <td class="border p-2"><b>Selesai</b></td>
        <td class="border p-2 text-center">{{ stats.tugasSelesai }}</td>

        <td class="border p-2"><b>Terlambat</b></td>
        <td class="border p-2 text-center">{{ stats.keterlambatan }}</td>

        <td class="border p-2"><b>Progres</b></td>
        <td class="border p-2 text-center">{{ stats.tugasProses }}</td>
      </tr>
    </table>

    <!-- ================= DISTRIBUSI ================= -->
    <table class="w-full border border-collapse text-sm mb-6">
      <thead>
        <tr class="bg-gray-100 font-semibold">
            <td colspan="8" class="border p-2 text-center">
            Distribusi Beban Kerja
            </td>
        </tr>
        <tr class="bg-gray-200">
          <th class="border p-2">No</th>
          <th class="border p-2">Nama</th>
          <th class="border p-2">Jumlah Tugas</th>
          <th class="border p-2">Persentase Beban Kerja</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(k,i) in distribusiBeban" :key="k.id">
          <td class="border p-2 text-center">{{ i+1 }}</td>
          <td class="border p-2">{{ k.nama }}</td>
          <td class="border p-2 text-center">{{ k.tugas }}</td>
          <td class="border p-2 text-center">
            {{ persenDistribusi(k.tugas) }}%
          </td>
        </tr>

        <tr v-if="distribusiBeban.length === 0">
          <td colspan="4" class="border p-2 text-center">-</td>
        </tr>
      </tbody>
    </table>

    <!-- ================= TABEL KARYAWAN ================= -->
    <table class="w-full border border-collapse text-sm">
      <thead>
        <tr class="bg-gray-100 font-semibold">
            <td colspan="8" class="border p-2 text-center">
            Performa Karyawan
            </td>
        </tr>
        <tr class="bg-gray-200">
          <th class="border p-2">Nama</th>
          <th class="border p-2">Bagian</th>
          <th class="border p-2">Total</th>
          <th class="border p-2">Selesai</th>
          <th class="border p-2">Terlambat</th>
          <th class="border p-2">Performance</th>
          <th class="border p-2">Kontribusi</th>
          <th class="border p-2">Skor</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="k in dataKaryawan" :key="k.id">
          <td class="border p-2">{{ k.name }}</td>
          <td class="border p-2">{{ k.bagian }}</td>
          <td class="border p-2 text-center">{{ k.totalTugas }}</td>
          <td class="border p-2 text-center">{{ k.tugasSelesai }}</td>
          <td class="border p-2 text-center">{{ k.tugasTerlambat }}</td>
          <td class="border p-2 text-center">{{ k.performance }}%</td>
          <td class="border p-2 text-center">{{ k.contribution }}%</td>
          <td class="border p-2 text-center">{{ k.skor }}</td>
        </tr>

        <tr v-if="dataKaryawan.length === 0">
          <td colspan="8" class="border p-2 text-center">-</td>
        </tr>
      </tbody>
    </table>

    <!-- ================= TTD ================= -->
    <div class="mt-12 flex justify-between text-center">
      <div>
        <p>Mengetahui,</p>
        <br><br><br>
        <p>( Atasan )</p>
      </div>
      <div>
        <p>Disusun oleh,</p>
        <br><br><br>
        <p>( Sistem )</p>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const API = 'http://localhost:8000/api'

const stats = ref({})
const dataKaryawan = ref([])
const distribusiBeban = ref([])

/* ================= LOAD DATA ================= */
const loadData = async () => {
  const resStats = await axios.get(`${API}/dashboard-kinerja`)
  stats.value = resStats.data

  const res = await axios.get(`${API}/performa-karyawan`)
  dataKaryawan.value = res.data

  // 🔥 HITUNG DISTRIBUSI SETELAH DATA ADA
  distribusiBeban.value = dataKaryawan.value.map(k => ({
    id: k.id,
    nama: k.name,
    tugas: k.totalTugas
  }))
}

/* ================= PERSENTASE ================= */
const persenDistribusi = (val) => {
  const total = dataKaryawan.value.reduce(
    (sum, k) => sum + (k.totalTugas || 0), 
    0
  )

  if (!total) return 0
  return Math.round((val / total) * 100)
}

/* ================= INIT ================= */
onMounted(async () => {
  await loadData()

  setTimeout(() => {
    window.print()
  }, 500)
})
</script>

<style>
@media print {
  body {
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
}
</style>