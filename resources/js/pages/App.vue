<template>
  <div>
    <TransitionRoot as="template" :show="sidebarOpen">
      <Dialog class="relative z-50 lg:hidden" @close="sidebarOpen = false">
        <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="" leave="transition-opacity ease-linear duration-300" leave-from="" leave-to="opacity-0">
          <div class="fixed inset-0 bg-gray-900/80"></div>
        </TransitionChild>

        <div class="fixed inset-0 flex">
          <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
            <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
              <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="" leave="ease-in-out duration-300" leave-from="" leave-to="opacity-0">
                <div class="absolute top-0 left-full flex w-16 justify-center pt-5">
                  <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                    <span class="sr-only">Close sidebar</span>
                    <span class="icon-[tabler--x] size-6 text-white" aria-hidden="true" />
                  </button>
                </div>
              </TransitionChild>

              <!-- Sidebar component, swap this element with another sidebar if you like -->
              <div class="relative flex grow flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-2">
                <div class="relative flex h-16 shrink-0 items-center">
                  <img class="h-8 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=teal&shade=600" alt="Your Company" />
                </div>
                <nav class="relative flex flex-1 flex-col">
                  <ul role="list" class="flex flex-1 flex-col gap-y-7">
                    <li>
                      <ul role="list" class="-mx-2 space-y-1">
                        <li v-for="item in navigation" :key="item.name">
                          <a :href="item.href" :class="[item.current ? 'bg-gray-50 text-teal-600' : 'text-gray-700 hover:bg-gray-50 hover:text-teal-600', 'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold']" @click="sidebarOpen = false">
                            <span :class="[item.current ? 'text-teal-600' : 'text-gray-400 group-hover:text-teal-600', 'size-6 shrink-0', item.icon]" aria-hidden="true" />
                            {{ item.name }}
                          </a>
                        </li>
                      </ul>
                    </li>
                    <li v-show="teams.length > 0">
                      <div class="text-xs/6 font-semibold text-gray-400">Your teams</div>
                      <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li v-for="team in teams" :key="team.name">
                          <a :href="team.href" :class="[team.current ? 'bg-gray-50 text-teal-600' : 'text-gray-700 hover:bg-gray-50 hover:text-teal-600', 'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold']">
                            <span :class="[team.current ? 'border-teal-600 text-teal-600' : 'border-gray-200 text-gray-400 group-hover:border-teal-600 group-hover:text-teal-600', 'flex size-6 shrink-0 items-center justify-center rounded-lg border bg-white text-[0.625rem] font-medium']">{{ team.initial }}</span>
                            <span class="truncate">{{ team.name }}</span>
                          </a>
                        </li>
                      </ul>
                    </li>
                  </ul>
                </nav>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </Dialog>
    </TransitionRoot>

    <!-- Static sidebar for desktop -->
    <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-64 lg:flex-col">
      <!-- Sidebar component, swap this element with another sidebar if you like -->
      <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6">
        <div class="flex h-16 shrink-0 items-center">
          <img class="h-8 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=teal&shade=600" alt="Your Company" />
          <span class="ml-2 font-bold text-teal-800">Task</span>
        </div>
        <nav class="flex flex-1 flex-col">
          <ul role="list" class="flex flex-1 flex-col gap-y-7">
            <li>
              <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                  <ul role="list" class="-mx-2 space-y-1">
                    <li v-for="item in navigation" :key="item.name">
                      <a :href="item.href" :class="[item.current ? 'bg-gray-50 text-teal-600' : 'text-gray-700 hover:bg-gray-50 hover:text-teal-600', 'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold']">
                        <span :class="[item.current ? 'text-teal-600' : 'text-gray-400 group-hover:text-teal-600', 'size-6 shrink-0', item.icon]" aria-hidden="true" />
                        {{ item.name }}
                      </a>
                    </li>
                  </ul>
                </li>
                <li v-show="teams.length > 0">
                  <div class="text-xs/6 font-semibold text-gray-400">Your teams</div>
                  <ul role="list" class="-mx-2 mt-2 space-y-1">
                    <li v-for="team in teams" :key="team.name">
                      <a :href="team.href" :class="[team.current ? 'bg-gray-50 text-teal-600' : 'text-gray-700 hover:bg-gray-50 hover:text-teal-600', 'group flex gap-x-3 rounded-md p-2 text-sm/6 font-semibold']">
                        <span :class="[team.current ? 'border-teal-600 text-teal-600' : 'border-gray-200 text-gray-400 group-hover:border-teal-600 group-hover:text-teal-600', 'flex size-6 shrink-0 items-center justify-center rounded-lg border bg-white text-[0.625rem] font-medium']">{{ team.initial }}</span>
                        <span class="truncate">{{ team.name }}</span>
                      </a>
                    </li>
                  </ul>
                </li>
              </ul>
            </li>
            <li class="-mx-6 mt-auto">
              <a href="#/profile" class="flex items-center gap-x-4 px-6 py-3 text-sm/6 font-semibold text-gray-900 hover:bg-gray-50">
                <img class="size-8 rounded-full bg-gray-50 outline -outline-offset-1 outline-black/5" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
                <span class="sr-only">Your profile</span>
                <span aria-hidden="true">Tom Cook</span>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </div>


    <div class="sticky w-full top-0 z-40 flex items-center gap-x-6 bg-white px-4 py-4 shadow-xs sm:px-6 lg:hidden">
      <button type="button" class="-m-2.5 p-2.5 text-gray-700 hover:text-gray-900 lg:hidden" @click="sidebarOpen = true">
        <span class="sr-only">Open sidebar</span>
        <span class="icon-[tabler--menu-2] size-6" aria-hidden="true" />
      </button>
      <div class="flex justify-end w-full">
        <button href="#/profile" class="flex items-center justify-end gap-x-4 text-sm/6 font-semibold text-gray-900 hover:bg-gray-50">
          <span class="sr-only">Your profile</span>
          <span aria-hidden="true">Tom Cook</span>
          <img class="size-8 rounded-full bg-gray-50 outline -outline-offset-1 outline-black/5" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80" alt="" />
        </button>
      </div>
    </div>

    <main class="py-2 lg:pl-64">
      <div class="px-2 sm:px-4 lg:px-6">
        <router-view></router-view>
      </div>
    </main>
    <footer>
      <div class="fixed bottom-0 right-0 z-40 flex bg-gray-100 rounded">
        <div class="w-full py-2 text-xs lg:text-sm text-gray-500 text-right px-4">
          <span class="block sm:inline">Power By Tim Capstone Politeknik Samarinda. &copy; 2025 PT Jamkrida Kaltim.</span>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue'

const navigation = [
  { name: 'Dashboard', href: '#', icon: 'icon-[tabler--dashboard]', current: false },
  { name: 'Team', href: '#', icon: 'icon-[tabler--users]', current: false },
  { name: 'Projects', href: '#', icon: 'icon-[tabler--folder]', current: false },
  { name: 'Calendar', href: '#', icon: 'icon-[tabler--calendar]', current: false },
  { name: 'Documents', href: '#', icon: 'icon-[tabler--file]', current: false },
  { name: 'Reports', href: '#', icon: 'icon-[tabler--printer]', current: false },
  { name: 'Settings', href: '#/settings', icon: 'icon-[tabler--settings]', current: false },
]

const teams = [
  // { id: 1, name: 'Heroicons', href: '#', initial: 'H', current: false },
  // { id: 2, name: 'Tailwind Labs', href: '#', initial: 'T', current: false },
  // { id: 3, name: 'Workcation', href: '#', initial: 'W', current: false },
]

const sidebarOpen = ref(false)
</script>
