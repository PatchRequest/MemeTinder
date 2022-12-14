<script setup>
import { Head, Link } from '@inertiajs/inertia-vue3';

defineProps({
  canLogin: Boolean,
  canRegister: Boolean,
  laravelVersion: String,
  phpVersion: String,
});
</script>

<template>
  <Head title="Welcome" />
  <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
    <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">

      <Link v-if="$page.props.user" :href="route('dashboard')" class="text-sm text-gray-700 dark:text-gray-500 underline">Dashboard</Link>

      <template v-else>
        <div id="bg_head" class="h-screen m-0 overflow-hidden p-0 w-screen">
          <div id="bg_head_bg" class="h-full absolute top-0 left-0 w-full"></div>
          <canvas id="bg_head_canvas" class="absolute top-0 left-0"></canvas>
          <div id="bg_head_fg" class="grid place-content-center h-full absolute top-0 left-0 w-full z-0">
            <div class="bg-white grid">
              <img src="https://i.imgur.com/ZJcZOh7.png" alt="" class="place-self-center w-full">
              <span class="mx-5"></span>
              <div class="grid place-items-center">
                <Link :href="route('login')" class="text-sm text-gray-700 dark:text-gray-500 underline">Log in</Link>
                <Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</Link>
              </div>
            </div>
          </div>
        </div>
       
      </template>

    </div>
  </div>
</template>

<style>
  @keyframes move {
    0% {
      background-position: 0 0;
    }
    100% {
      background-position: calc(100vh * 2102) calc(100vh * 1689);
    }
  }
  #bg_head_bg {
    z-index: -2;
    opacity: 0.2;
    background-image: url(https://i.imgur.com/jvbHjjP.jpeg);
    background-size: 50%;
    animation: move 80000s linear infinite reverse;
  }
  #bg_head > canvas {
    z-index: -1;
  }
  #bg_head_fg {
    grid-template-columns: auto min(600px, 90%) auto;
    grid-template-rows: auto min(250px, 80%) auto;
  }
  #bg_head_fg > div {
    border-radius: 10px;
    grid-column: 2;
    grid-row: 2;
    grid-template-rows: 75% 1px calc(25% - 1px);
  }
  #bg_head_fg > div > span {
    background-color: rgba(233, 238, 250, 1);
  }
  #bg_head_fg > div > div {
    grid-template-columns: 50% 50%;
  }
</style>
