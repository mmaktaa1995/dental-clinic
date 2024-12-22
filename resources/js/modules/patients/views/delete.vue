<template>
    <CConfirmModal v-model="opened" :confirm-title="`حذف ${type}`" :confirm-body-message="`هل أنت متأكد من حذف هذا ال${type}؟`" @confirm-callback="deleteItem"> </CConfirmModal>
</template>

<script setup>
import { ref, onMounted } from "vue"
import { useRouter, useRoute } from "vue-router"
import axios from "axios"

const opened = ref(false)
const submitted = ref(false)
const id = ref(null)
const type = ref("")
const router = useRouter()
const route = useRoute()

const back = () => {
    opened.value = false
    setTimeout(() => router.back(), 100)
}

const deleteItem = () => {
    submitted.value = true
    axios
        .delete(`/api/patients/${id.value}`)
        .then(({ data }) => {
            // bus.emit("flash-message", { text: data.message, type: "success" });
            // bus.emit("item-deleted", id.value);
            back()
        })
        .catch(({ response }) => {
            // bus.emit("flash-message", { text: response.data.message, type: "error" });
        })
        .finally(() => {
            submitted.value = false
        })
}

onMounted(() => {
    id.value = route.params.id
    type.value = route.query.type
    setTimeout(() => {
        opened.value = true
    }, 50)
})
</script>

<style>
.icon-container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 3rem;
    width: 3rem;
    background-color: #fee2e2; /* Tailwind: bg-red-100 */
    border-radius: 50%;
}
</style>
