<template>
  <main>
    <div v-if="showModal" class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div
          class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">

        <div class="fixed inset-0 z-10 overflow-y-auto">
          <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div

                class="relative transform overflow-hidden rounded-lg bg-white px-4 pt-5 pb-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
              <div>
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-200">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-900">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                  </svg>

                </div>
                <div class="mt-3 text-center sm:mt-5">
                  <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">You got a match</h3>
                  <div class="mt-2">
                    <p class="text-sm text-gray-500">{{ match.name }}</p>
                  </div>
                </div>
              </div>
              <div class="mt-5 sm:mt-6 grid grid-cols-2 gap-4">
                <a :href="`mailto:${match.email}`" @click="showModal = false"
                   class="inline-flex w-full justify-center items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:text-sm">
                  Mail an {{ match.email }}
                </a>
                <button type="button" @click="showModal = false"
                        class="inline-flex w-full justify-center items-center rounded-md border border-transparent bg-gray-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 sm:text-sm">
                  Ablehnen
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="!loaded"
         class="fixed top-0 left-0 right-0 bottom-0 w-full h-screen z-50 overflow-hidden bg-gray-700 opacity-95 flex flex-col items-center justify-center">
      <div
          class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12 mb-4"></div>
      <h2 class="text-center text-white text-xl font-semibold">Loading...</h2>
      <p class="w-1/3 text-center text-white">This may take a few seconds, please don't close this
        page.</p>
    </div>

    <div class="h-1/2 flex flex-col rounded overflow-hidden shadow-lg justify-center">
      <div class="px-6 py-4">
        <div class="grid grid-cols-3 gap-4">
          <button @click="increaseCounterSafe()"
                  class="bg-red-300 hover:bg-red-400 text-gray-800 text-lg font-bold py-2 px-4 rounded-lg">
            Dislike
          </button>

          <div class="font-bold text-xl text-center"> {{ imageTitle }}</div>

          <button @click="like()"
                  class="bg-green-300 hover:bg-green-400 text-gray-800 text-lg font-bold py-2 px-4 rounded-lg">
            Like!
          </button>
        </div>

      </div>

      <img v-if="loaded" :src="imageLink" alt="Mountain">
    </div>
  </main>
</template>

<script setup>
import {computed, onMounted, ref, watch} from "vue";

let currentMemes = ref();
let currentIndex = ref(0);
let loaded = ref(false);
let showModal = ref(false);
let match = ref();

onMounted(() => {
  fetchMemes()
});

const fetchMemes = () => {
  axios.get('/memes')
      .then(response => {
        // take response data as array of memes
        currentMemes.value = response.data;
        loaded.value = true;
      })
      .catch(error => {
        console.log(error);
      });
}

const like = () => {
  axios.post("/meme/" + currentMemes.value[currentIndex.value].id)
      .then(response => {
        if (response.status === 200) {
          showModal.value = true;
          match.value = response.data
        } else {
          increaseCounterSafe();
        }
      })
}

watch(showModal, (newValue) => {
  if (newValue === false) {
    increaseCounterSafe();
  }
})

const increaseCounterSafe = () => {
  if (currentIndex.value === currentMemes.value.length - 1) {
    fetchMemes();
    currentIndex.value = 0;
  } else {
    currentIndex.value++;
  }
}

const imageLink = computed(() => {
  if (currentMemes.value) {
    return currentMemes.value[currentIndex.value].url;
  }
});

const imageTitle = computed(() => {
  if (currentMemes.value) {
    return currentMemes.value[currentIndex.value].title;
  }
});
</script>
<style>
.loader {
  border-top-color: #3498db;
  -webkit-animation: spinner 1.5s linear infinite;
  animation: spinner 1.5s linear infinite;
}

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
  }
}

@keyframes spinner {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}


</style>
