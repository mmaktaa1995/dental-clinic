<template>
    <CDetailPage :store="serviceDetailsStore" :load-data-immediately="false">
        <template #sidebarHeader>
            <div v-if="serviceDetailsStore.entry.id" class="c-detailsWrapper__sidebarHeader flex items-center">
                <div class="text-base font-semibold text-gray-700">
                    {{ serviceDetailsStore.entry.name }}
                </div>
            </div>
        </template>
        <template #actionButtons>
            <CDropdown v-if="!serviceDetailsStore.isNewEntry" type="accent" :loading="isDeleting" :items="actions" :button-label="$t('global.actions.label')" @select="handleAction"></CDropdown>
            <AsyncButton v-if="serviceDetailsStore.isNewEntry" :disabled="!serviceDetailsStore.watchers?.entry?.isChanged" :loading="isSaving" type="primary" @click="save">
                {{ $t("global.actions.create") }}
            </AsyncButton>
            <AsyncButton v-else :loading="isSaving" :disabled="!serviceDetailsStore.watchers?.entry?.isChanged" type="primary" @click="save">
                {{ $t("global.actions.saveChanges") }}
            </AsyncButton>
        </template>
    </CDetailPage>
    <CConfirmModal
        v-model="isServiceDeleteModalOpened"
        v-model:loading="isDeleting"
        :confirm-title="$t('global.deleteEntryTitle', { type: $t('services.entryName') })"
        :confirm-body-message="
            $t('global.deleteEntryBodyMessage', {
                type: $t('services.entryName'),
            })
        "
        @confirm-callback="deleteService"
    >
    </CConfirmModal>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from "vue-router"
import { computed, onUnmounted, ref, watch } from "vue"
import AsyncButton from "@/components/AsyncButton.vue"
import { useI18n } from "vue-i18n"
import { api } from "@/logic/api"
import { getRootRoutePath } from "@/logic/detailPage"
import { useServiceDetailsStore } from "@/modules/services/detailStore"
import { useToastStore } from "@/modules/account/toastStore"

const isDeleting = ref(false)
const isSaving = ref(false)
const isServiceDeleteModalOpened = ref(false)
const serviceDetailsStore = useServiceDetailsStore()
const toastStore = useToastStore()
const router = useRouter()
const route = useRoute()
const { t } = useI18n()

serviceDetailsStore.addWatcher("entry")

const props = defineProps<{
    reloadList?: () => any
}>()

watch(
    () => route.fullPath,
    async () => {
        if (route.params.id) {
            serviceDetailsStore.entryId = parseInt(route.params.id as string)
            await serviceDetailsStore.loadData()
            serviceDetailsStore.watchers?.entry?.resetStore()
        }
    },
    {
        immediate: true,
    },
)

const save = () => {
    serviceDetailsStore.errors = {}
    isSaving.value = true
    let url = `/services/create`
    if (!serviceDetailsStore.isNewEntry) {
        url = `/services/${serviceDetailsStore.entryId}`
    }
    api.send(url, serviceDetailsStore.isNewEntry ? "POST" : "PATCH", {}, serviceDetailsStore.entry)
        .then((response: any) => {
            router.replace({
                name: "services/general",
                params: { id: response.id },
            })
            serviceDetailsStore.watchers?.entry?.resetStore()
            const message = serviceDetailsStore.isNewEntry ? "services.serviceCreatedSuccessfully" : "services.serviceUpdatedSuccessfully"
            toastStore.success(t(message))
            isSaving.value = false
            if (props?.reloadList) {
                props?.reloadList()
            }
        })
        .catch((error: any) => {
            isSaving.value = false
            if (error.errors && error.status === 422) {
                serviceDetailsStore.errors = error.errors
            }
        })
}

const actions = computed(() => {
    const actions: Record<string, string> = {
        delete: t("global.actions.delete"),
    }

    return actions
})
const deleteService = () => {
    isDeleting.value = true
    api.delete(`/services/${serviceDetailsStore.entryId}`)
        .then(() => {
            isDeleting.value = false
            router.push(getRootRoutePath(route)).then(() => {
                toastStore.success(t("services.serviceDeletedSuccessfully"))
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
    console.log(action)
    switch (action) {
        case "delete": {
            isServiceDeleteModalOpened.value = true
            break
        }
    }
}

onUnmounted(() => {
    serviceDetailsStore.$reset()
})
</script>
