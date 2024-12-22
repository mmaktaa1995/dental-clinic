<!--<template>-->
<!--  <v-dialog v-model="isUnsavedChangesWarningOpen" max-width="500">-->
<!--    <v-card>-->
<!--      <v-card-title>{{ $t("cDetailPage.unsavedChangesWarnings.title") }}</v-card-title>-->
<!--      <v-card-text class="text-grey-darken-4"> {{ $t("cDetailPage.unsavedChangesWarnings.discardUnsavedChanges") }} </v-card-text>-->
<!--      <v-card-actions class="justify-end">-->
<!--        <k-button primary @click="discardChanges">-->
<!--          {{ $t("cDetailPage.unsavedChangesWarnings.discardChanges") }}-->
<!--        </k-button>-->
<!--        <k-button @click="closeUnsavedChangesWarning">{{ $t("global.cancel") }}</k-button>-->
<!--      </v-card-actions>-->
<!--    </v-card>-->
<!--  </v-dialog>-->
<!--</template>-->

<!--<script setup lang="ts">-->
<!--import { computed } from "vue"-->

<!--const props = defineProps<{-->
<!--  modelValue: boolean-->
<!--  closeUnsavedChangesWarning: () => void-->
<!--  discardChanges: () => void-->
<!--}>()-->
<!--const emit = defineEmits(["update:modelValue"])-->

<!--const isUnsavedChangesWarningOpen = computed({-->
<!--  get() {-->
<!--    return props.modelValue-->
<!--  },-->
<!--  set(newValue: boolean) {-->
<!--    emit("update:modelValue", newValue)-->
<!--  },-->
<!--})-->
<!--</script>-->
