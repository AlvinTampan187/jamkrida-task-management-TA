<template>
  <div class="flex min-h-full flex-1 flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <img class="mx-auto h-10 w-auto" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=teal&shade=600" alt="Your Company" />
      <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Create your account</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6 bg-white p-4 rounded" action="#" method="POST">
        <div>
          <label for="username" class="block text-sm/6 font-medium text-gray-900">Username</label>
          <div class="mt-2">
            <input type="input" name="username" id="username" autocomplete="username" required="" class="base-input sm:text-sm/6" />
          </div>
        </div>

        <div>
          <label for="email" class="block text-sm/6 font-medium text-gray-900">Email</label>
          <div class="mt-2">
            <input type="email" name="email" id="email" autocomplete="email" required="" class="base-input sm:text-sm/6" />
          </div>
        </div>

        <div>
          <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
          <div class="mt-2">
            <input type="password" name="password" id="password" autocomplete="password" required="" class="base-input sm:text-sm/6" />
          </div>
        </div>

        <div>
          <label for="password_confirmation" class="block text-sm/6 font-medium text-gray-900">Password Confirmation</label>
          <div class="mt-2">
            <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="password-confirmation" required="" class="base-input sm:text-sm/6" />
          </div>
        </div>

        <div>
          <button type="submit" class="base-button flex w-full justify-center bg-teal-600 hover:bg-teal-500 text-white text-sm/6">Register</button>
        </div>
      </form>

      <p class="mt-10 text-center text-sm/6 text-gray-500">
        Already member?
        {{ ' ' }}
        <a href="#/login" class="font-semibold text-teal-600 hover:text-teal-500">Log In</a>
      </p>
    </div>
  </div>
</template>
