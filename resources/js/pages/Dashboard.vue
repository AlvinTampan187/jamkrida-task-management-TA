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
    <TableHeader v-model:filter="filterQuery" v-model:perPage="itemsPerPage" />
      <div class="mt-6 flow-root">
        <div class="overflow-x-auto">
          <div class="inline-block min-w-full py-2 align-middle">
            <div class="overflow-hidden shadow-sm rounded">
              <table class="min-w-full divide-y divide-gray-300">
                <thead class="bg-teal-800">
                  <tr>
                    <th scope="col" class="table-th text-white text-sm text-left">Kode</th>
                    <th scope="col" class="table-th text-white text-sm text-left">Nama</th>
                    <th scope="col" class="table-th text-white text-sm text-left">Tipe</th>
                    <th scope="col" class="table-th text-white text-sm text-right">Saldo</th>
                    <th scope="col" class="table-th-r text-white text-sm">
                      <span class="sr-only">Edit</span>
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                  <template v-for="item, index in items" :key="item.id" v-if="items.length > 0">
                    <tr :class="['hover:bg-gray-100 text-sm', { 'bg-teal-50 font-semibold text-teal-900': item.is_group } ]">
                      <td :class="indentClass(item.level)">{{ item.code }}</td>
                      <td :class="indentClass(item.level)">{{ item.name }}</td>
                      <td :class="indentClass(item.level)">{{ item.type }}</td>
                      <td class="table-td text-right">{{ formatCurrency(item.saldo) }}</td>
                      <td class="table-td-r flex gap-x-2">
                        <router-link :to="{name: 'manajemen.akun.create', query: { id: item.uuid}}" class="base-link-primary">
                          <span class="icon-[tabler--plus] h-4 w-4 mr-1"/>
                          Tambah
                          <span class="sr-only">, {{ item.name }}</span>
                        </router-link>
                        <router-link :to="{name: 'manajemen.akun.detail', params: { uuid: item.uuid}}" class="base-link">
                          <span class="icon-[tabler--clipboard-list] h-4 w-4 mr-1"/>
                          Detail
                          <span class="sr-only">, {{ item.name }}</span>
                        </router-link>
                      </td>
                    </tr>
                  </template>
                  <template v-else>
                    <tr>
                      <td class="table-td text-center" colspan="10"><em>Data Tidak ditemukan</em></td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    <TableFooter :total="totalItems" :currentPage="currentPage" :lastPage="totalPages" :perPage="itemsPerPage" @change-page="currentPage = $event"/>
  </div>
</template>

<script setup>
import NProgress from 'nprogress'
import Title from '@/components/Title.vue';
import TableHeader from "@/components/TableHeader.vue"
import TableFooter from "@/components/TableFooter.vue"
import { getSuccess, getError, getInfo, getConfirm } from '@/services/alertService'
import { ref } from 'vue';

NProgress.start()
NProgress.done()

const itemsPerPage = ref(15)
const currentPage = ref(1)
const totalItems = ref(0)
const totalPages = ref(0)
const filterQuery = ref('')
const items = ref([])

</script>
