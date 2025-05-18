<template>
    <CDetailPage :store="expenseDetailsStore" :load-data-immediately="false">
        <template #sidebarHeader>
            <div v-if="expenseDetailsStore.entry.id" class="c-detailsWrapper__sidebarHeader flex items-center">
                <div class="text-base font-semibold text-gray-700">
                    {{ expenseDetailsStore.entry.name }}
                </div>
            </div>
        </template>
        <template #actionButtons>
            <CDropdown v-if="!expenseDetailsStore.isNewEntry" type="accent" :loading="isDeleting" :items="actions" :button-label="$t('global.actions.label')" @select="handleAction"></CDropdown>
            <AsyncButton v-if="expenseDetailsStore.isNewEntry" :disabled="!expenseDetailsStore.watchers?.entry?.isChanged" :loading="isSaving" type="primary" @click="save">
                {{ $t("global.actions.create") }}
            </AsyncButton>
            <AsyncButton v-else :loading="isSaving" :disabled="!expenseDetailsStore.watchers?.entry?.isChanged" type="primary" @click="save">
                {{ $t("global.actions.saveChanges") }}
            </AsyncButton>
        </template>
    </CDetailPage>
    <CConfirmModal
        v-model="isServiceDeleteModalOpened"
        v-model:loading="isDeleting"
        :confirm-title="$t('global.deleteEntryTitle', { type: $t('expenses.entryName') })"
        :confirm-body-message="
            $t('global.deleteEntryBodyMessage', {
                type: $t('expenses.entryName'),
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
import { useExpenseDetailsStore } from "@/modules/expenses/detailStore"
import { useToastStore } from "@/modules/global/toastStore"

const isDeleting = ref(false)
const isSaving = ref(false)
const isServiceDeleteModalOpened = ref(false)
const expenseDetailsStore = useExpenseDetailsStore()
const toastStore = useToastStore()
const router = useRouter()
const route = useRoute()
const { t } = useI18n()

expenseDetailsStore.addWatcher("entry")

const props = defineProps<{
    reloadList?: () => any
}>()

watch(
    () => route.fullPath,
    async () => {
        if (route.params.id) {
            expenseDetailsStore.entryId = parseInt(route.params.id as string)
            await expenseDetailsStore.loadData()
            expenseDetailsStore.watchers?.entry.resetStore()
        }
    },
    {
        immediate: true,
    },
)

const save = () => {
    expenseDetailsStore.errors = {}
    isSaving.value = true
    let url = `/expenses/create`
    if (!expenseDetailsStore.isNewEntry) {
        url = `/expenses/${expenseDetailsStore.entryId}`
    }
    api.send(url, expenseDetailsStore.isNewEntry ? "POST" : "PATCH", {}, expenseDetailsStore.entry)
        .then((response: any) => {
            router.replace({
                name: "expenses/general",
                params: { id: response.id },
            })
            expenseDetailsStore.watchers?.entry?.resetStore()
            const message = expenseDetailsStore.isNewEntry ? "expenses.expenseCreatedSuccessfully" : "expenses.expenseUpdatedSuccessfully"
            toastStore.success(t(message))
            isSaving.value = false
            if (props?.reloadList) {
                props?.reloadList()
            }
        })
        .catch((error: any) => {
            isSaving.value = false
            if (error.errors && error.status === 422) {
                expenseDetailsStore.errors = error.errors
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
    api.delete(`/expenses/${expenseDetailsStore.entryId}`)
        .then(() => {
            isDeleting.value = false
            router.push(getRootRoutePath(route)).then(() => {
                toastStore.success(t("expenses.expenseDeletedSuccessfully"))
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
            isServiceDeleteModalOpened.value = true
            break
        }
    }
}

onUnmounted(() => {
    expenseDetailsStore.$reset()
})
</script>
