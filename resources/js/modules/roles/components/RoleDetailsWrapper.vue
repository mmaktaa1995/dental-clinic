<template>
    <div>
        <CDetailPage :store="roleDetailsStore" :load-data-immediately="false">
            <template #sidebarHeader>
                <div v-if="roleDetailsStore.entry.id" class="c-detailsWrapper__sidebarHeader flex items-center">
                    <img src="/images/role.png" class="h-9 w-9 ml-3 ltr:mr-3 ltr:ml-0" :alt="roleDetailsStore.entry.name" />
                    <div class="d-flex flex-column">
                        <div class="text-base font-semibold text-gray-700">
                            {{ roleDetailsStore.entry.name }}
                        </div>
                        <div class="text-base font-normal text-cyan-700">{{ roleDetailsStore.entry.slug }}</div>
                    </div>
                </div>
            </template>
            <template #actionButtons>
                <CPermissionGuard v-if="!roleDetailsStore.isNewEntry" permission="view-roles">
                    <CDropdown type="accent" :loading="isDeleting" :items="actions" :button-label="$t('global.actions.label')" @select="handleAction"></CDropdown>
                </CPermissionGuard>
                <CPermissionGuard v-if="roleDetailsStore.isNewEntry" permission="create-roles">
                    <AsyncButton :disabled="!roleDetailsStore.watchers?.entry?.isChanged" :loading="isSaving" type="primary" @click="save">
                        {{ $t("global.actions.create") }}
                    </AsyncButton>
                </CPermissionGuard>
                <CPermissionGuard v-else permission="edit-roles">
                    <AsyncButton :loading="isSaving" :disabled="!roleDetailsStore.watchers?.entry?.isChanged" type="primary" @click="save">
                        {{ $t("global.actions.saveChanges") }}
                    </AsyncButton>
                </CPermissionGuard>
            </template>
        </CDetailPage>
        <CConfirmModal
            v-model="isRoleDeleteModalOpened"
            v-model:loading="isDeleting"
            :confirm-title="$t('roles.deleteRoleConfirmation')"
            :confirm-body-message="$t('roles.deleteRoleConfirmationMessage')"
            @confirm-callback="deleteRole"
        >
        </CConfirmModal>
    </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from "vue-router"
import { computed, onUnmounted, ref, watch } from "vue"
import { useRoleDetailsStore } from "@/modules/roles/detailStore"
import AsyncButton from "@/components/AsyncButton.vue"
import { useI18n } from "vue-i18n"
import { api } from "@/logic/api"
import { getRootRoutePath } from "@/logic/detailPage"
import { useToastStore } from "@/modules/global/toastStore"
import { hasPermission } from "@/utils/permissions"

const isDeleting = ref(false)
const isSaving = ref(false)
const isRoleDeleteModalOpened = ref(false)
const roleDetailsStore = useRoleDetailsStore()
const toastStore = useToastStore()
const router = useRouter()
const route = useRoute()
const { t } = useI18n()

roleDetailsStore.addWatcher("entry")

const props = defineProps<{
    reloadList?: () => any
}>()

watch(
    () => route.fullPath,
    async () => {
        
        if (route.params.id) {
            roleDetailsStore.genericError = {}
            roleDetailsStore.entryId = parseInt(route.params.id as string)
            await roleDetailsStore.loadData()
            roleDetailsStore.watchers?.entry.resetStore()
            await roleDetailsStore.loadPermissions()
        }
    },
    {
        immediate: true,
    },
)

const save = () => {
    roleDetailsStore.genericError = {}
    roleDetailsStore.errors = {}
    isSaving.value = true
    let url = `/roles/create`
    const isNew = roleDetailsStore.isNewEntry
    if (!roleDetailsStore.isNewEntry) {
        url = `/roles/${roleDetailsStore.entryId}`
    }
    api.send(url, roleDetailsStore.isNewEntry ? "POST" : "PATCH", {}, roleDetailsStore.entry)
        .then((response: any) => {
            if (!isNew) {
                roleDetailsStore.entry = response.role
                roleDetailsStore.entry.permissions = response.role.permissions.map((permission: any) => permission.id)
            }
            router.replace({
                name: "roles-edit",
                params: { id: response.role.id },
            })
            const message = roleDetailsStore.isNewEntry ? "roles.roleCreatedSuccessfully" : "roles.roleUpdatedSuccessfully"
            toastStore.success(t(message))

            roleDetailsStore.watchers?.entry?.resetStore()
            isSaving.value = false
            if (props?.reloadList) {
                props?.reloadList()
            }
        })
        .catch((error: any) => {
            isSaving.value = false
            if (error.errors && error.status === 422) {
                roleDetailsStore.errors = error.errors
            }
        })
}

const actions = computed(() => {
    const actions: Record<string, string> = {}
    
    // Only add delete action if user has permission
    if (hasPermission('delete-roles')) {
        actions.delete = t("global.actions.delete")
    }

    return actions
})
const deleteRole = () => {
    isDeleting.value = true
    api.delete(`/roles/${roleDetailsStore.entryId}`)
        .then(() => {
            isDeleting.value = false
            router.push(getRootRoutePath(route)).then(() => {
                toastStore.success(t("roles.roleDeletedSuccessfully"))
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
            isRoleDeleteModalOpened.value = true
            break
        }
    }
}

onUnmounted(() => {
    roleDetailsStore.$reset()
})
</script>
