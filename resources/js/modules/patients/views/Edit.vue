<template>
    <c-container>
        <div class="grid grid-cols-2 gap-6">
            <CTextField v-model="patientDetailsStore.entry.file_number" :disabled="true" label="رقم الملف" :errors="patientDetailsStore.errors" name="file_number"></CTextField>
            <CTextField v-model="patientDetailsStore.entry.name" label="الاسم الكامل" :errors="patientDetailsStore.errors" name="name"></CTextField>
            <CTextField v-model="patientDetailsStore.entry.age" label="العمر" type="number" :errors="patientDetailsStore.errors" name="age"></CTextField>
            <CTextField v-model="patientDetailsStore.entry.phone" label="رقم الهاتف" type="tel" :errors="patientDetailsStore.errors" name="phone"></CTextField>
            <CTextField v-model="patientDetailsStore.entry.mobile" label="رقم الموبايل" type="tel" :errors="patientDetailsStore.errors" name="mobile"></CTextField>
        </div>
    </c-container>
</template>

<script setup>
import { usePatientDetailStore } from "@/modules/patients/detailStore.ts"

const patientDetailsStore = usePatientDetailStore()
</script>
