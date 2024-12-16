<template>
    <div :class="`fixed z-10 inset-0 overflow-y-auto`" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div :class="`fixed inset-0 bg-gray-500 transition-opacity duration-200 ${opened ? 'bg-opacity-75' : 'bg-opacity-0'}`" aria-hidden="true" @click="back"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div :class="`inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200  ${opened.value ? 'scale-100' : 'scale-0'}`">
                <div class="bg-gray-50 px-4 py-2 border-b border-gray-300 text-right">
                    <h3 class="text-lg text-gray-700 font-normal">ملفات المريض</h3>
                </div>
                <div class="bg-white px-4 pt-5 sm:p-6">
                    <div v-if="loaded" class="grid grid-cols-2 gap-6">
                        <div class="col-span-full">
                            <c-file-pond-component :files="files" folder="patients" type="images" @update-files="setImages" />
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-revers">
                    <button
                        type="button"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="back"
                    >
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue"
import axios from "axios"
import { useRoute, useRouter } from "vue-router"

const id = ref(null)
const opened = ref(false)
const loaded = ref(false)
const files = ref([])
const patient = reactive({})
const submitted = ref(false)
const route = useRoute()
const router = useRouter()

const back = () => {
    opened.value = false
    router.back() // If using Vue Router v3 or vue-router v4, adjust accordingly
    setTimeout(() => {
        // Ensure you have access to the router before using this line
    }, 300)
}

const setImages = (images) => {
    files.value = images
    if (files.value.length) {
        update()
    }
}

const update = () => {
    submitted.value = true
    axios
        .patch(`/api/patients/${id.value}/images`, { images: files.value })
        .then(({ data }) => {
            // bus.$emit("flash-message", { text: data.message, type: "success" })
        })
        .catch((error) => {
            // bus.$emit("flash-message", { text: error.response.message, type: "danger" })
        })
        .finally(() => {
            submitted.value = false
        })
}

onMounted(() => {
    id.value = route.params.id
    axios.get(`/api/patients/${id.value}`).then(({ data }) => {
        Object.assign(patient, data)
        files.value = patient.images.map((image) => ({
            source: image.image,
            options: {
                type: "local",
                metadata: {
                    poster: image.image,
                },
            },
        }))
        opened.value = true
        loaded.value = true
    })
})
</script>

<style scoped></style>
