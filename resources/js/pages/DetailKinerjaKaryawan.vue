<template>

    <Title 
        class="pt-2 space-y-3 max-w-7xl mx-auto"
        title="Detail Kinerja Karyawan"
        >

        <template #actions>

            <button
            @click="$router.back()"
            class="p-2 bg-gray-600 text-white rounded hover:bg-gray-800 flex items-center gap-2"
            >

            <span class="icon-[tabler--arrow-left]"></span>

            Kembali

            </button>

        </template>

    </Title>


  <div class="pt-2 space-y-3 max-w-7xl mx-auto">


    <!-- ================= PROFIL ================= -->

    <div class="bg-white rounded shadow border p-6">

      <h3 class="font-semibold flex items-center mb-5">

        <span class="icon-[tabler--user-circle] mr-2 text-teal-600"></span>

        Informasi Karyawan

      </h3>



      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">


        <div>

          <p class="text-sm text-gray-500">
            Nama
          </p>

          <p class="font-semibold">
            {{ data.nama }}
          </p>

        </div>



        <div>

          <p class="text-sm text-gray-500">
            Bagian
          </p>

          <p class="font-semibold">
            {{ data.bagian }}
          </p>

        </div>



        <div>

          <p class="text-sm text-gray-500">
            Total Tugas
          </p>

          <p class="font-semibold text-teal-600">
            {{ data.tugas?.length }}
          </p>

        </div>


      </div>


    </div>





    <!-- ================= TUGAS ================= -->


    <div class="bg-white rounded shadow border p-6">


      <h3 class="font-semibold flex items-center mb-5">

        <span class="icon-[tabler--clipboard-text] mr-2 text-teal-600"></span>

        Tugas Utama

      </h3>




      <div
        v-for="(t, index) in data.tugas"
        :key="t.id"
        class="border rounded-lg p-5 mb-4 hover:bg-gray-50 transition"
      >


        <div class="flex justify-between items-start">


          <div>

            <h4 class="font-semibold text-teal-700 text-lg flex items-center gap-2">

                <span
                    class="bg-teal-100 text-teal-700 rounded-full w-7 h-7 flex items-center justify-center text-sm"
                >
                    {{ index + 1 }}
                </span>

                {{ t.kategori }}

            </h4>


            <p class="text-gray-600 mt-1">
              {{ t.keterangan_laporan }}
            </p>

          </div>




          <!-- STATUS TUGAS -->

          <span
            class="px-3 py-1 rounded text-xs font-semibold"
            :class="{

              'bg-green-100 text-green-700':
              t.status === 'Selesai',

              'bg-yellow-100 text-yellow-700':
              t.status === 'Progres',

              'bg-red-100 text-red-700':
              t.status === 'Terlambat',

              'bg-gray-100 text-gray-700':
              t.status === 'Penugasan'

            }"
          >

            {{ t.status }}

          </span>


        </div>





        <!-- ================= RINCIAN ================= -->


        <div class="mt-5">


          <h4 class="font-semibold text-sm mb-3 flex items-center">

            <span class="icon-[tabler--list-details] mr-2 text-teal-600"></span>

            Rincian Tugas

          </h4>





          <div
            v-for="(d, index) in t.detail"
            :key="d.id"
            class="border rounded p-3 mb-2 flex justify-between items-center"
          >


            <div class="flex items-start gap-3">

                <span
                    class="bg-teal-100 text-teal-700 rounded-full w-7 h-7 flex items-center justify-center text-sm font-semibold"
                >
                    {{ index + 1 }}
                </span>


                <div>

                    <p class="font-medium">
                    {{ d.deskripsi }}
                    </p>


                    <p class="text-xs text-gray-500 mt-1">
                    Tanggal :
                    {{ d.tanggal ?? '-' }}
                    </p>

                </div>

            </div>




            <span
              class="px-3 py-1 rounded text-xs font-semibold"
              :class="{

                'bg-green-100 text-green-700':
                d.status === 'Selesai',

                'bg-yellow-100 text-yellow-700':
                d.status === 'Menunggu Persetujuan',

                'bg-blue-100 text-blue-700':
                d.status === 'Progres'

              }"
            >

              {{ d.status }}

            </span>



          </div>


        </div>



      </div>





      <div
        v-if="!data.tugas?.length"
        class="text-center text-gray-500 py-5"
      >

        Belum ada tugas

      </div>



    </div>



  </div>


</template>



<script setup>

import { ref,onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'
import Title from '@/components/Title.vue'


const route = useRoute()


const data = ref({
 nama:'',
 bagian:'',
 tugas:[]
})


const loadData = async()=>{

const res = await axios.get(
`http://localhost:8000/api/kinerja/karyawan/${route.params.id}`
)


data.value = res.data


}


onMounted(()=>{
loadData()
})


</script>