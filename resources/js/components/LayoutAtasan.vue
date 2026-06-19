<template>
<div>

  <!-- SIDEBAR DESKTOP -->
  <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
    <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6">

      <div class="flex items-center gap-3 pt-5">
  
        <img :src="'/logo.png'" class="w-12 h-12 object-contain" />

        <span class="font-bold text-black-700 text-base">
          Task Management
        </span>

      </div>

      <nav class="flex flex-1 flex-col">
        <ul class="space-y-2">

          <li>
            <router-link
              to="/atasan/dashboard"
              class="flex gap-3 p-2 rounded hover:bg-gray-100"
            >
              <span class="icon-[mingcute--task-2-line]"></span>
              Tugas
            </router-link>
          </li>

          <li>
            <router-link
              to="/atasan/kinerja"
              class="flex gap-3 p-2 rounded hover:bg-gray-100"
            >
              <span class="icon-[mdi--performance]"></span>
              Laporan Kinerja
            </router-link>
          </li>

        </ul>
      </nav>

    </div>
  </div>


  <!-- NAVBAR MOBILE -->
  <div class="sticky top-0 z-40 flex items-center justify-between bg-white px-4 py-4 shadow lg:hidden">

    <button @click="sidebarOpen = true">
      <span class="icon-[tabler--menu-2] text-2xl"></span>
    </button>

    <div class="font-semibold">
      Dashboard 
    </div>

    <img
      class="w-8 h-8 rounded-full"
      src="https://i.pravatar.cc/100"
    />

  </div>


  <!-- SIDEBAR MOBILE -->
  <div v-if="sidebarOpen" class="fixed inset-0 z-50 flex">

    <div class="w-64 bg-white p-6">

      <button
        class="mb-4"
        @click="sidebarOpen=false"
      >
        <span class="icon-[tabler--x] text-xl"></span>
      </button>

      <nav class="space-y-3">

        <router-link
          to="/atasan/dashboard"
          class="block p-2 rounded hover:bg-gray-100"
          @click="sidebarOpen=false"
        >
          Tugas
        </router-link>

        <router-link
          to="/atasan/kinerja"
          class="block p-2 rounded hover:bg-gray-100"
          @click="sidebarOpen=false"
        >
          Laporan Kinerja
        </router-link>

      </nav>

    </div>

    <div
      class="flex-1 bg-black/50"
      @click="sidebarOpen=false"
    ></div>

  </div>


  <!-- CONTENT -->
  <main class="lg:pl-64 py-6">

    <div class="px-4 lg:px-8">
      <router-view />
    </div>

  </main>


  <!-- FOOTER -->
  <footer class="fixed bottom-0 right-0 lg:left-64 bg-gray-100 text-xs text-gray-600 px-4 py-2">
    Power By Tim Capstone Politeknik Samarinda © 2025 PT Jamkrida Kaltim
  </footer>

</div>
</template>

<script setup>

import { ref } from "vue"

const sidebarOpen = ref(false)

</script>