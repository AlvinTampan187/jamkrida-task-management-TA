<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">

    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      
      <!-- ICON TASK -->
      <div class="flex justify-center">
        <img :src="'/logo.png'" class="w-50 h-50 object-contain" />
      </div>
      <!-- <h2 class="mt-1 text-center text-1xl font-bold text-gray-900">
        Task Management dan Monitoring Kinerja
        PT.Jamkrida Kaltim
      </h2> -->
      <h2 class="mt-1 text-center text-2xl font-bold text-gray-900">
        Masukkan Akun Anda
      </h2>
    </div>

    <div class="mt-5 sm:mx-auto sm:w-full sm:max-w-sm">

      <!-- FORM -->
      <form
        class="space-y-6 bg-white p-4 rounded"
        @submit.prevent="login"
      >

        <!-- USERNAME -->
        <div>
          <label class="block text-sm font-medium text-gray-900">
            Username
          </label>

          <input
            v-model="username"
            type="text"
            required
            class="base-input w-full"
          />
        </div>

        <!-- PASSWORD -->
        <div>
          <label class="block text-sm font-medium text-gray-900">
            Password
          </label>

          <input
            v-model="password"
            type="password"
            required
            class="base-input w-full"
          />
        </div>

        <!-- BUTTON -->
        <div>
          <button
            type="submit"
            :disabled="loading"
            class="base-button flex w-full justify-center bg-teal-600 hover:bg-teal-500 text-white"
          >
            <span v-if="loading">Loading...</span>
            <span v-else>Masuk</span>
          </button>
        </div>

      </form>
        <a href="/userguide.pdf" download>
          <p class="mt-1 text-center text-gray-900 cursor-pointer hover:underline">
            Buku Panduan Pengguna
          </p>
        </a>
    </div>

  </div>
</template>

<script setup>

import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

const username = ref('')
const password = ref('')
const loading = ref(false)

const login = async () => {

  loading.value = true

  try {

    const res = await axios.post(
      'http://localhost:8000/api/login',
      {
        username: username.value,
        password: password.value
      }
    )

    const user = res.data.user

    // simpan user ke localStorage
    localStorage.setItem('user', JSON.stringify(user))

    // redirect berdasarkan role
    if (user.role === 'atasan') {
      router.push('/atasan/dashboard')
    } else {
      router.push('/karyawan/dashboard')
    }

  } catch (error) {

    console.log(error)

    if (error.response?.status === 401) {
      alert('Username atau password salah')
    } else {
      alert('Terjadi kesalahan pada server')
    }

  } finally {

    loading.value = false

  }

}

</script>