<template>
    <c-container>
        <CAccordion :title="$t('users.userInfo')" :description="$t('users.userInfoDescription')">
            <div class="grid grid-cols-2 gap-6">
                <CTextField v-model="userStore.entry.name" :label="$t('users.name')" :errors="userStore.errors" name="name"></CTextField>
                <CTextField v-model="userStore.entry.email" :label="$t('users.email')" type="email" :errors="userStore.errors" name="email"></CTextField>
                <CTextField v-model="userStore.entry.password" :label="$t('users.password')" type="password" :errors="userStore.errors" name="password"></CTextField>
                <CTextField v-model="userStore.entry.password_confirmation" :label="$t('users.passwordConfirmation')" type="password" :errors="userStore.errors" name="password_confirmation"></CTextField>
            </div>
        </CAccordion>

        <CAccordion :title="$t('users.rolesAssignment')" :description="$t('users.rolesAssignmentDescription')">
            <div class="grid grid-cols-1 gap-6">
                <CMultiSelect 
                    v-model="userStore.entry.roles" 
                    :options="availableRoles" 
                    :label="$t('users.roles')" 
                    :errors="userStore.errors" 
                    name="roles"
                    option-label="name"
                    option-value="id"
                ></CMultiSelect>
            </div>
        </CAccordion>
    </c-container>
</template>

<script setup lang="ts">
import { useUserDetailsStore } from "@/modules/users/detailStore"
import { useI18n } from "vue-i18n"
import { onMounted, ref } from "vue"
import { api } from "@/logic/api"

const { t } = useI18n()
const userStore = useUserDetailsStore()
interface Role {
    id: number;
    name: string;
    slug: string;
}
const availableRoles = ref<Role[]>([])

const loadRoles = async () => {
    try {
        const response = await api.get("/roles/list")
        availableRoles.value = response
    } catch (error) {
        console.error("Failed to load roles", error)
    }
}

onMounted(() => {
    loadRoles()
})
</script>
