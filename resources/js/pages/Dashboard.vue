<template>
  <Title title="Dashboard">
    <template #actions>
      <button @click="getSuccess()" class="p-2 mx-2 bg-teal-300 rounded">Success</button>
      <button @click="getError()" class="p-2 mx-2 bg-red-300 rounded">Error</button>
      <button @click="getConfirm()" class="p-2 mx-2 bg-yellow-300 rounded">Confirm</button>
      <button @click="getInfo()" class="p-2 mx-2 bg-blue-300 rounded">Info</button>
    </template>
  </Title>
  <div class="pt-4">
    <div class="flex items-center justify-between mb-2">
      <TableHeader v-model:filter="filterQuery" v-model:perPage="itemsPerPage" />
      <button @click="onAdd" class="flex items-center bg-teal-600 text-white px-4 py-3 rounded hover:bg-teal-700">
        <span class="icon-[tabler--plus] h-3 w-3 mr-1"></span>
        Tambah
      </button>
    </div>
    <div class="mt-6 flow-root">
      <div class="overflow-x-auto">
        <div class="inline-block min-w-full py-2 align-middle">
          <div class="overflow-hidden shadow-sm rounded">
            <table class="min-w-full divide-y divide-gray-300">
              <thead class="bg-teal-800">
                <tr>
                  <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-white sm:pl-6">Tanggal</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Bagian</th>
                  <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-white">Keterangan</th>
                  <th scope="col" class="px-3 py-3.5 text-right text-sm font-semibold text-white">Status</th>
                  <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                    <span class="sr-only">Actions</span>
                  </th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="item in items" :key="item.id" class="hover:bg-gray-50">
                  <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ item.tanggal }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ item.bagian }}</td>
                  <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ item.keterangan }}</td>
                 <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500 text-right">
                    <span :class="['inline-flex rounded-full px-2 text-xs font-semibold leading-5', 
                      item.status === 'Selesai' ? 'bg-green-100 text-green-800' : 
                      item.status === 'Pending' ? 'bg-red-100 text-red-800' :
                      'bg-yellow-100 text-yellow-800']">
                      {{ item.status }}
                    </span>
                  </td>
                  <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                    <button class="text-black-600 hover:text-black-900 mr-2">Detail</button>
                    <button class="text-orange-600 hover:text-orange-900 mr-2">Edit</button>
                    <button class="text-teal-600 hover:text-teal-900">Selesai</button>
                  </td>
                </tr>
                <tr v-if="items.length === 0">
                  <td colspan="5" class="px-3 py-4 text-sm text-gray-500 text-center">
                    <div class="flex flex-col items-center justify-center py-8">
                      <span class="icon-[tabler--database-off] h-12 w-12 text-gray-400 mb-2"></span>
                      <p>Tidak ada data yang ditemukan</p>
                      <button @click="loadDummyData" class="mt-2 text-teal-600 hover:text-teal-700 text-sm">
                        Muat data contoh
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
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
const router = useRouter()
import NProgress from 'nprogress'
import Title from '@/components/Title.vue';
import TableHeader from "@/components/TableHeader.vue"
import TableFooter from "@/components/TableFooter.vue"
import { getSuccess, getError, getInfo, getConfirm } from '@/services/alertService'
import { ref, onMounted } from 'vue';

NProgress.start()
NProgress.done()

const itemsPerPage = ref(15)
const currentPage = ref(1)
const totalItems = ref(0)
const totalPages = ref(0)
const filterQuery = ref('')
const items = ref([])

// Data dummy yang sesuai dengan struktur tabel
const loadDummyData = () => {
  items.value = [
    {
      id: 1,
      tanggal: '2024-01-15',
      bagian: 'Keuangan',
      keterangan: 'Laporan bulanan januari',
      status: 'Selesai'
    },
    {
      id: 2,
      tanggal: '2024-01-16',
      bagian: 'Marketing',
      keterangan: 'Campaign produk baru',
      status: 'Dalam Proses'
    },
    {
      id: 3,
      tanggal: '2024-01-17',
      bagian: 'IT',
      keterangan: 'Maintenance server',
      status: 'Pending'
    }
  ];
  
  totalItems.value = items.value.length;
  totalPages.value = 1;
  currentPage.value = 1;
}

// Test function untuk memastikan data terload
const testDataLoad = () => {
  console.log('Items:', items.value);
  console.log('Total items:', totalItems.value);
  getSuccess('Data berhasil dimuat!');
}

onMounted(() => {
  loadDummyData();
  // Test untuk memastikan data terload
  setTimeout(() => {
    testDataLoad();
  }, 1000);
});

const onAdd = () => {
  router.push('/Form')
}
</script>

