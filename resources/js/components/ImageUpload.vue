<template>
    <CFilePondComponent v-model="files"></CFilePondComponent>
</template>

<script setup lang="ts">
const files = defineModel({ required: true })
</script>
