<template>
  <div>
    <TransitionRoot as="template" :show="sidebarOpen">
      <Dialog class="relative z-50 lg:hidden" @close="sidebarOpen = false">

        <TransitionChild
          as="template"
          enter="transition-opacity ease-linear duration-300"
          enter-from="opacity-0"
          leave="transition-opacity ease-linear duration-300"
          leave-to="opacity-0"
        >
          <div class="fixed inset-0 bg-gray-900/80"></div>
        </TransitionChild>

        <div class="fixed inset-0 flex">

          <TransitionChild
            as="template"
            enter="transition ease-in-out duration-300 transform"
            enter-from="-translate-x-full"
            enter-to="translate-x-0"
            leave="transition ease-in-out duration-300 transform"
            leave-from="translate-x-0"
            leave-to="-translate-x-full"
          >

            <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">

              <TransitionChild
                as="template"
                enter="ease-in-out duration-300"
                enter-from="opacity-0"
                leave="ease-in-out duration-300"
                leave-to="opacity-0"
              >

                <div class="absolute top-0 left-full flex w-16 justify-center pt-5">
                  <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                    <span class="icon-[tabler--x] size-6 text-white" />
                  </button>
                </div>

              </TransitionChild>


              <!-- MOBILE SIDEBAR -->
              <div class="relative flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-2">

                <div class="relative flex h-16 shrink-0 items-center">
                  <span class="font-bold text-teal-700 text-lg">Tugas</span>
                </div>

                <nav class="relative flex flex-1 flex-col">
                  <ul class="-mx-2 space-y-1">

                    <li v-for="item in navigation" :key="item.name">
                      <a
                        :href="item.href"
                        :class="[
                          route.path.includes(item.path)
                          ? 'bg-gray-50 text-teal-600'
                          : 'text-gray-700 hover:bg-gray-50 hover:text-teal-600',
                          'group flex gap-x-3 rounded-md p-2 text-sm font-semibold'
                        ]"
                        @click="sidebarOpen = false"
                      >
                        <span :class="[item.icon,'size-6 text-gray-400 group-hover:text-teal-600']"></span>
                        {{ item.name }}
                      </a>
                    </li>

                  </ul>
                </nav>

              </div>

            </DialogPanel>

          </TransitionChild>

        </div>

      </Dialog>
    </TransitionRoot>



    <!-- DESKTOP SIDEBAR -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">

      <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6">

        <div class="flex h-16 shrink-0 items-center">
          <span class="font-bold text-teal-800 text-lg">Tugas</span>
        </div>

        <nav class="flex flex-1 flex-col">
          <ul class="-mx-2 space-y-1">

            <li v-for="item in navigation" :key="item.name">

              <a
                :href="item.href"
                :class="[
                  route.path.includes(item.path)
                  ? 'bg-gray-50 text-teal-600'
                  : 'text-gray-700 hover:bg-gray-50 hover:text-teal-600',
                  'group flex gap-x-3 rounded-md p-2 text-sm font-semibold'
                ]"
              >
                <span :class="[item.icon,'size-6 text-gray-400 group-hover:text-teal-600']"></span>
                {{ item.name }}
              </a>

            </li>

          </ul>
        </nav>


        <!-- PROFILE -->
        <!-- <div class="-mx-6 mt-auto">

          <a
            href="#/profile"
            class="flex items-center gap-x-4 px-6 py-3 text-sm font-semibold text-gray-900 hover:bg-gray-50"
          >

            <img
              class="size-8 rounded-full"
              src="https://i.pravatar.cc/100"
            />

            <span>Tom Cook</span>

          </a>

        </div> -->

      </div>

    </div>



    <!-- TOPBAR MOBILE -->
    <div class="sticky w-full top-0 z-40 flex items-center gap-x-6 bg-white px-4 py-4 shadow sm:px-6 lg:hidden">

      <button
        type="button"
        class="-m-2.5 p-2.5 text-gray-700"
        @click="sidebarOpen = true"
      >
        <span class="icon-[tabler--menu-2] size-6" />
      </button>

      <div class="flex justify-end w-full">

        <a
          href="#/profile"
          class="flex items-center gap-x-4 text-sm font-semibold text-gray-900"
        >

          <span>Tom Cook</span>

          <img
            class="size-8 rounded-full"
            src="https://i.pravatar.cc/100"
          />

        </a>

      </div>

    </div>



    <!-- CONTENT -->
    <main class="py-2 lg:pl-64">

      <div class="px-2 sm:px-4 lg:px-6">
        <router-view />
      </div>

    </main>



    <!-- FOOTER -->
    <footer>

      <div class="fixed bottom-0 right-0 z-40 flex bg-gray-100 rounded">

        <div class="w-full py-2 text-xs lg:text-sm text-gray-500 text-right px-4">

          <span>
            Power By Tim Capstone Politeknik Samarinda © 2025 PT Jamkrida Kaltim
          </span>

        </div>

      </div>

    </footer>

  </div>
</template>


<script setup>

import { ref, computed } from 'vue'
import { useRoute } from 'vue-router'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'

const sidebarOpen = ref(false)
const route = useRoute()



const navigationAtasan = [
  {
    name: 'Tugas',
    href: '#/dashboard0',
    path: 'dashboard0',
    icon: 'icon-[mingcute--task-2-line]'
  },
  {
    name: 'Laporan Kinerja',
    href: '#/kinerja',
    path: 'kinerja',
    icon: 'icon-[mdi--performance]'
  }
]


const navigationKaryawan = [
  {
    name: 'Tugas',
    href: '#/dashboard1',
    path: 'dashboard1',
    icon: 'icon-[mingcute--task-2-line]'
  }
]



const navigation = computed(() => {

  if (
    route.path.includes('dashboard0') ||
    route.path.includes('kinerja') ||
    route.path.includes('detail') ||
    route.path.includes('form')
  ) {
    return navigationAtasan
  }

  if (route.path.includes('dashboard1')) {
    return navigationKaryawan
  }

  return navigationAtasan
})
</script>