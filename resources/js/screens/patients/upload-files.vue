<template>
    <div :class="`fixed z-10 inset-0 overflow-y-auto `" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!--
              Background overlay, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0"
                To: "opacity-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100"
                To: "opacity-0"
            -->
            <div :class="`fixed inset-0 bg-gray-500 transition-opacity duration-200 ${opened ? 'bg-opacity-75' : 'bg-opacity-0'}`" aria-hidden="true" @click="back()"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!--
              Modal panel, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                To: "opacity-100 translate-y-0 sm:scale-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100 translate-y-0 sm:scale-100"
                To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            -->

            <div :class="`inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200  ${opened ? 'scale-100' : 'scale-0'}`">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-300 text-right">
                    <h3 class="text-lg text-gray-700 font-normal">ملفات المريض</h3>
                </div>
                <div class="bg-white px-4 pt-5 sm:p-6">
                    <div v-if="loaded" class="grid grid-cols-2 gap-6">
                        <div class="col-span-full">
                            <file-pond-component :files="files" folder="patients" type="images" @update-files="setImages" />
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-revers">
                    <button
                        type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="back()"
                    >
                        إلغاء
                    </button>
                    <!--                    <async-button-->
                    <!--                        type="submit"-->
                    <!--                        :loading="submitted"-->
                    <!--                        @click="update"-->
                    <!--                        class="w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm">-->
                    <!--                        حفظ-->
                    <!--                    </async-button>-->
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios"

export default {
    name: "UploadFiles",
    data() {
        return {
            id: null,
            loaded: false,
            opened: false,
            errors: {},
            submitted: false,
            files: [],
        }
    },
    mounted() {
        this.id = this.$route.params.id
        this.loaded = false
        axios.get(`/api/patients/${this.id}`).then(({ data }) => {
            this.patient = { ...data }
            this.files = this.patient.images.map((image) => {
                return {
                    source: image.image,
                    options: {
                        type: "local",
                        metadata: {
                            poster: image.image,
                        },
                    },
                }
            })
            setTimeout(() => {
                this.opened = true
                this.loaded = true
            }, 50)
            console.log(this.files)
        })
    },
    methods: {
        back() {
            this.opened = false
            setTimeout(() => this.$router.back(), 300)
        },
        setImages(images) {
            this.files = images
            if (this.files.length) {
                this.update()
            }
        },
        update() {
            const self = this
            this.submitted = true
            axios
                .patch(`/api/patients/${this.id}/images`, { images: this.files })
                .then(({ data }) => {
                    bus.$emit("flash-message", { text: data.message, type: "success" })
                    // self.back();
                })
                .catch((error) => {
                    bus.$emit("flash-message", { text: error.response.message, type: "danger" })
                })
                .finally(() => {
                    this.submitted = false
                })
        },
    },
}
</script>

<style scoped></style>
