<template>
  <div class="print-container">

    <!-- HEADER -->
    <div class="header">
      <h1>LAPORAN TUGAS</h1>
      <p>Sistem Task Management dan Monitoring Kinerja Karyawan</p>
      <hr />
    </div>

    <!-- INFORMASI -->
    <section class="section">
      <h2>Informasi Tugas</h2>
      <table class="info-table">
        <tr><td>Kategori</td><td>: {{ task.kategori }}</td></tr>
        <tr><td>Bagian</td><td>: {{ task.bagian }}</td></tr>
        <tr><td>Keterangan</td><td>: {{ task.keterangan_laporan }}</td></tr>
        <tr><td>Tanggal Dibuat</td><td>: {{ formatDate(task.created_at) }}</td></tr>
        <tr><td>Deadline</td><td>: {{ formatDate(task.deadline) }}</td></tr>
        <tr><td>Status</td><td>: {{ task.status }}</td></tr>
        <tr><td>Progress</td><td>: {{ progress }}%</td></tr>
      </table>
    </section>

    <!-- RINCIAN -->
    <section class="section">
      <h2>Rincian Tugas</h2>
      <table class="table">
        <thead>
          <tr>
            <th>No</th>
            <th>Deskripsi</th>
            <th>Status</th>
            <th>Estimasi Selesai</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(d,i) in details" :key="i">
            <td>{{ i+1 }}</td>
            <td>{{ d.deskripsi }}</td>
            <td>{{ d.status }}</td>
            <td>{{ formatDate(d.tanggal) }}</td>
          </tr>
        </tbody>
      </table>
    </section>

    <!-- PENUGASAN -->
    <section class="section">
      <h2>Petugas</h2>
      <ul>
        <li v-for="u in assignedUsers" :key="u.id">{{ u.name }} - {{ u.bagian }}</li>
        <li v-if="assignedUsers.length === 0">-</li>
      </ul>
    </section>

    <!-- LAMPIRAN -->
    <section class="section">
      <h2>Lampiran</h2>
      <ul>
        <li v-for="l in lampiran" :key="l.id">
          {{ l.keterangan || l.nama_file }}
        </li>
        <li v-if="lampiran.length === 0">-</li>
      </ul>
    </section>

    <!-- DISKUSI -->
    <section class="section">
      <h2>Diskusi</h2>
      <div v-for="d in diskusi" :key="d.id" class="diskusi-item">
        <p class="nama">
          {{ d.user?.name || 'User' }}
          <span>({{ formatDateTime(d.created_at) }})</span>
        </p>
        <p>{{ d.pesan }}</p>
      </div>
      <p v-if="diskusi.length === 0">-</p>
    </section>

    <!-- TTD -->
    <section class="ttd">
      <div>
        <p>Mengetahui,</p>
        <div class="space"></div>
        <p>( Atasan )</p>
      </div>
      <div>
        <p>Dikerjakan oleh,</p>
        <div class="space"></div>
        <p>( Karyawan )</p>
      </div>
    </section>

  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

const API = 'http://localhost:8000/api'
const route = useRoute()

const task = ref({})
const details = ref([])
const assignedUsers = ref([])
const lampiran = ref([])
const diskusi = ref([])
const progress = ref(0)

const loadData = async () => {
  const id = route.params.id

  const [t, d, u, l, ds, p] = await Promise.all([
    axios.get(`${API}/tugas/${id}`),
    axios.get(`${API}/tugas/${id}/detail`),
    axios.get(`${API}/tugas/${id}/users`),
    axios.get(`${API}/tugas/${id}/lampiran`),
    axios.get(`${API}/tugas/${id}/diskusi`),
    axios.get(`${API}/tugas/${id}/progress`)
  ])

  task.value = t.data
  details.value = d.data
  assignedUsers.value = u.data
  lampiran.value = l.data
  diskusi.value = ds.data
  progress.value = p.data?.progress || 0
}

const formatDate = (d) => {
  if (!d) return '-'
  return new Date(d).toLocaleDateString()
}

const formatDateTime = (d) => {
  if (!d) return '-'
  return new Date(d).toLocaleString()
}

onMounted(async () => {
  await loadData()

  setTimeout(() => {
    window.print()
  }, 500)
})
</script>

<style>
.print-container {
  padding: 40px;
  font-family: "Times New Roman", serif;
  color: black;
  font-size: 12pt;
}

/* HEADER */
.header {
  text-align: center;
  margin-bottom: 20px;
}

.header h1 {
  font-size: 18pt;
  font-weight: bold;
}

.header p {
  font-size: 11pt;
}

.header hr {
  margin-top: 10px;
  border: 1px solid black;
}

/* SECTION */
.section {
  margin-bottom: 20px;
}

.section h2 {
  font-size: 13pt;
  font-weight: bold;
  margin-bottom: 8px;
}

/* TABLE INFORMASI */
.info-table td:first-child {
  width: 180px;
  font-weight: bold;
}

/* TABLE RINCIAN */
.table {
  width: 100%;
  border-collapse: collapse;
}

.table th,
.table td {
  border: 1px solid black;
  padding: 6px;
}

.table th {
  background: #f0f0f0;
}

/* DISKUSI */
.diskusi-item {
  margin-bottom: 8px;
}

.diskusi-item .nama {
  font-weight: bold;
}

.diskusi-item span {
  font-size: 10pt;
}

/* TTD */
.ttd {
  margin-top: 60px;
  display: flex;
  justify-content: space-between;
  text-align: center;
}

.space {
  height: 80px;
}

/* PRINT */
@media print {
  body {
    -webkit-print-color-adjust: exact;
  }

  .section {
    page-break-inside: avoid;
  }
}
</style>