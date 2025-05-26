<template>
    <div>
        <CDetailPage :store="userDetailsStore" :load-data-immediately="false">
            <template #sidebarHeader>
                <div v-if="userDetailsStore.entry.id" class="c-detailsWrapper__sidebarHeader flex items-center">
                    <img src="/images/user.png" class="h-9 w-9 ml-3 ltr:mr-3 ltr:ml-0" :alt="userDetailsStore.entry.name" />
                    <div class="d-flex flex-column">
                        <div class="text-base font-semibold text-gray-700">
                            {{ userDetailsStore.entry.name }}
                        </div>
                        <div class="text-base font-normal text-cyan-700">{{ userDetailsStore.entry.email }}</div>
                    </div>
                </div>
            </template>
            <template #actionButtons>
                <CPermissionGuard v-if="!userDetailsStore.isNewEntry" permission="view-users">
                    <CDropdown type="accent" :loading="isDeleting" :items="actions" :button-label="$t('global.actions.label')" @select="handleAction"></CDropdown>
                </CPermissionGuard>
                <CPermissionGuard v-if="userDetailsStore.isNewEntry" permission="create-users">
                    <AsyncButton :disabled="!userDetailsStore.watchers?.entry?.isChanged" :loading="isSaving" type="primary" @click="save">
                        {{ $t("global.actions.create") }}
                    </AsyncButton>
                </CPermissionGuard>
                <CPermissionGuard v-else permission="edit-users">
                    <AsyncButton :loading="isSaving" :disabled="!userDetailsStore.watchers?.entry?.isChanged" type="primary" @click="save">
                        {{ $t("global.actions.saveChanges") }}
                    </AsyncButton>
                </CPermissionGuard>
            </template>
        </CDetailPage>
        <CConfirmModal
            v-model="isUserDeleteModalOpened"
            v-model:loading="isDeleting"
            :confirm-title="$t('users.deleteUserConfirmation')"
            :confirm-body-message="$t('users.deleteUserConfirmationMessage')"
            @confirm-callback="deleteUser"
        >
        </CConfirmModal>
    </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from "vue-router"
import { computed, onUnmounted, ref, watch } from "vue"
import { useUserDetailsStore } from "@/modules/users/detailStore"
import AsyncButton from "@/components/AsyncButton.vue"
import { useI18n } from "vue-i18n"
import { api } from "@/logic/api"
import { getRootRoutePath } from "@/logic/detailPage"
import { useToastStore } from "@/modules/global/toastStore"
import { hasPermission } from "@/utils/permissions"

const isDeleting = ref(false)
const isSaving = ref(false)
const isUserDeleteModalOpened = ref(false)
const userDetailsStore = useUserDetailsStore()
const toastStore = useToastStore()
const router = useRouter()
const route = useRoute()
const { t } = useI18n()

userDetailsStore.addWatcher("entry")

const props = defineProps<{
    reloadList?: () => any
}>()

watch(
    () => route.fullPath,
    async () => {
        if (route.params.id) {
            userDetailsStore.genericError = {}
            userDetailsStore.entryId = parseInt(route.params.id as string)
            await userDetailsStore.loadData()
            userDetailsStore.watchers?.entry.resetStore()
        }
    },
    {
        immediate: true,
    },
)

const save = () => {
    userDetailsStore.genericError = {}
    userDetailsStore.errors = {}
    isSaving.value = true
    let url = `/users/create`
    const isNew = userDetailsStore.isNewEntry
    console.log(userDetailsStore.isNewEntry)
    if (!userDetailsStore.isNewEntry) {
        url = `/users/${userDetailsStore.entryId}`
    }
    api.send(url, userDetailsStore.isNewEntry ? "POST" : "PATCH", {}, userDetailsStore.entry)
        .then((response: any) => {
            if (!isNew) {
                userDetailsStore.entry = response.user
            }
            console.log(response)
            router.replace({
                name: "users-edit",
                params: { id: response.user.id },
            })
            const message = userDetailsStore.isNewEntry ? "users.userCreatedSuccessfully" : "users.userUpdatedSuccessfully"
            toastStore.success(t(message))

            userDetailsStore.watchers?.entry?.resetStore()
            isSaving.value = false
            if (props?.reloadList) {
                props?.reloadList()
            }
        })
        .catch((error: any) => {
            isSaving.value = false
            if (error.errors && error.status === 422) {
                userDetailsStore.errors = error.errors
            }
        })
}

const actions = computed(() => {
    const actions: Record<string, string> = {}
    
    // Only add delete action if user has permission
    if (hasPermission('delete-users')) {
        actions.delete = t("global.actions.delete")
    }

    return actions
})
const deleteUser = () => {
    isDeleting.value = true
    api.delete(`/users/${userDetailsStore.entryId}`)
        .then(() => {
            isDeleting.value = false
            router.push(getRootRoutePath(route)).then(() => {
                toastStore.success(t("users.userDeletedSuccessfully"))
            })
            if (props?.reloadList) {
                props?.reloadList()
            }
        })
        .catch(() => {
            isDeleting.value = false
        })
}

const handleAction = async (action: string) => {
    switch (action) {
        case "delete": {
            isUserDeleteModalOpened.value = true
            break
        }
    }
}

onUnmounted(() => {
    userDetailsStore.$reset()
})
</script>
