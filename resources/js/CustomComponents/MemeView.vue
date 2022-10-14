<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <!-- Display each meme in a frame a show next button -->
                        <div class="h-2/4 ">

                            <!--Card 1-->
                            <div class="grid rounded overflow-hidden shadow-lg justify-center content-center place-items-center pt-8">
                                <img :src="imageLink"  alt="Mountain">
                                <div class="px-6 py-4">

                                    <div v-if="notLoaded" wire:loading class="fixed top-0 left-0 right-0 bottom-0 w-full h-screen z-50 overflow-hidden bg-gray-700 opacity-95 flex flex-col items-center justify-center">
                                        <div class="loader ease-linear rounded-full border-4 border-t-4 border-gray-200 h-12 w-12 mb-4"></div>
                                        <h2 class="text-center text-white text-xl font-semibold">Loading...</h2>
                                        <p class="w-1/3 text-center text-white">This may take a few seconds, please don't close this page.</p>
                                    </div>

                                    <div class="inline-flex">
                                        <button  @click="increaseCounterSafe()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-l place-content-start">
                                            Dislike
                                        </button>
                                        <div class="font-bold text-xl mb-2"> {{imageTitle}}</div>
                                        <button @click="like()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-r place-content-end">
                                            Like!
                                        </button>
                                    </div>

                                </div>

                            </div>

                        </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

    export default {
        data: function () {
            return {
                currentMemes: null,
                currentIndex : 0,
                notLoaded: true,
                activeModal: false,
            }
        },
        mounted() {
            this.fetchMemes();
        },
        methods: {
            fetchMemes() {
                axios.get('/memes')
                    .then(response => {
                        // take response data as array of memes
                        this.currentMemes = response.data;
                        this.notLoaded = false;
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            like(){
                this.increaseCounterSafe();
                axios.post("/meme/"+this.currentMemes[this.currentIndex].id)
                    .then(response => {
                        console.log(response.data);
                        if (response.data != ""){
                            this.activeModal = true;
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            increaseCounterSafe(){
                if (this.currentIndex == this.currentMemes.length -1){
                    this.fetchMemes();
                    this.currentIndex = 0;
                } else {
                    this.currentIndex++;
                }
            }


        },

        computed: {
            imageLink() {
                if (this.currentMemes) {
                    return this.currentMemes[this.currentIndex].url;
                }
            },
            imageTitle() {
                if (this.currentMemes) {
                    return this.currentMemes[this.currentIndex].title;
                }
            }
        }
    }
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
