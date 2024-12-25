<template>
    <CDetailPage :store="patientDetailsStore" :load-data-immediately="false">
        <template #sidebarHeader>
            <div v-if="patientDetailsStore.entry.id" class="c-detailsWrapper__sidebarHeader flex items-center">
                <img src="/images/user.png" class="h-9 w-9 ml-3 ltr:mr-3 ltr:ml-0" :alt="patientDetailsStore.entry.name" />
                <div class="d-flex flex-column">
                    <div class="text-base font-semibold text-gray-700">
                        {{ patientDetailsStore.entry.name }}
                    </div>
                    <div v-if="!patientDetailsStore.isNewEntry" class="text-base font-normal text-cyan-700">#{{ patientDetailsStore.entry.file_number }}</div>
                </div>
            </div>
        </template>
        <template #actionButtons>
            <CDropdown v-if="!patientDetailsStore.isNewEntry" :loading="isDeleting" :items="actions" :button-label="'الاإجرا،ات'" @select="handleAction"></CDropdown>
            <AsyncButton v-if="patientDetailsStore.isNewEntry" :disabled="!patientDetailsStore.watchers?.entry.isChanged" :loading="isSaving" type="primary" @click="save">
                {{ $t("global.actions.create") }}
            </AsyncButton>
            <AsyncButton v-else :loading="isSaving" :disabled="!patientDetailsStore.watchers?.entry.isChanged" type="primary" @click="save">
                {{ $t("global.actions.saveChanges") }}
            </AsyncButton>
        </template>
        <!--            <template v-if="patientDetailsStore.entry.isBinned" #sidebarContent>-->
        <!--                <KAlert>{{ $t("appointments.appointmentDetailsPage.alerts.isBinned") }}</KAlert>-->
        <!--            </template>-->
    </CDetailPage>
    <CConfirmModal
        v-model="isPatientDeleteModalOpened"
        v-model:loading="isDeleting"
        :confirm-title="$t('global.deleteEntryTitle', { type: $t('patients.entryName') })"
        :confirm-body-message="
            $t('global.deleteEntryBodyMessage', {
                type: $t('patients.entryName'),
            })
        "
        @confirm-callback="deletePatient"
    >
    </CConfirmModal>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from "vue-router"
import { computed, onUnmounted, ref, watch } from "vue"
import { usePatientDetailsStore } from "@/modules/patients/detailStore"
import AsyncButton from "@/components/AsyncButton.vue"
import { useI18n } from "vue-i18n"
import { api } from "@/logic/api"
import { getRootRoutePath } from "@/logic/detailPage"

const isDeleting = ref(false)
const isSaving = ref(false)
const isPatientDeleteModalOpened = ref(false)
const patientDetailsStore = usePatientDetailsStore()
const router = useRouter()
const route = useRoute()
const { t } = useI18n()

patientDetailsStore.addWatcher("entry")

const props = defineProps<{
    reloadList?: () => any
}>()

watch(
    () => route.fullPath,
    async () => {
        if (route.params.id) {
            patientDetailsStore.entryId = parseInt(route.params.id as string)
            await patientDetailsStore.loadData()
            patientDetailsStore.watchers?.entry.resetStore()
        }
    },
    {
        immediate: true,
    },
)

const save = () => {
    patientDetailsStore.errors = {}
    isSaving.value = true
    let url = `/patients/create`
    if (!patientDetailsStore.isNewEntry) {
        url = `/patients/${patientDetailsStore.entryId}`
    }
    api.send(url, patientDetailsStore.isNewEntry ? "POST" : "PATCH", {}, patientDetailsStore.entry)
        .then((response: any) => {
            console.log(response)
            router.replace({
                name: "patients/general",
                params: { id: response.id },
            })
            isSaving.value = false
            if (props?.reloadList) {
                props?.reloadList()
            }
        })
        .catch((error: any) => {
            isSaving.value = false
            if (error.errors && error.status === 422) {
                patientDetailsStore.errors = error.errors
            }
        })
}

const actions = computed(() => {
    const actions: Record<string, string> = {
        delete: t("global.actions.delete"),
    }

    return actions
})
const deletePatient = () => {
    isDeleting.value = true
    api.delete(`/patients/${patientDetailsStore.entryId}`)
        .then(() => {
            // bus.emit("flash-message", { text: data.message, type: "success" });
            // bus.emit("item-deleted", id.value);
            isDeleting.value = false
            router.push(getRootRoutePath(route))
            if (props?.reloadList) {
                props?.reloadList()
            }
        })
        .catch(() => {
            isDeleting.value = false
            // bus.emit("flash-message", { text: response.data.message, type: "error" });
        })
}

const handleAction = async (action: string) => {
    switch (action) {
        case "delete": {
            isPatientDeleteModalOpened.value = true
            break
        }
    }
}

onUnmounted(() => {
    patientDetailsStore.$reset()
})
</script>
