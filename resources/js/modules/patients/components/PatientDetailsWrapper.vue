<template>
    <div>
        <CDetailPage :store="patientDetailsStore" :load-data-immediately="false">
            <template #sidebarHeader>
                <div v-if="patientDetailsStore.entry.id" class="c-detailsWrapper__sidebarHeader flex items-center">
                    <img src="/images/user.png" class="h-9 w-9 ml-3 ltr:mr-3 ltr:ml-0" :alt="patientDetailsStore.entry.name" />
                    <div class="d-flex flex-column">
                        <div class="text-base font-semibold text-gray-700">
                            {{ patientDetailsStore.entry.name }}
                        </div>
                        <div class="text-base font-normal text-cyan-700">#{{ patientDetailsStore.entry.file_number }}</div>
                    </div>
                </div>
            </template>
            <template #actionButtons>
                <CDropdown v-if="!patientDetailsStore.isNewEntry" type="accent" :loading="isDeleting" :items="actions" :button-label="$t('global.actions.label')" @select="handleAction"></CDropdown>
                <AsyncButton v-if="patientDetailsStore.isNewEntry" :disabled="!patientDetailsStore.watchers?.entry?.isChanged" :loading="isSaving" type="primary" @click="save">
                    {{ $t("global.actions.create") }}
                </AsyncButton>
                <AsyncButton v-else :loading="isSaving" :disabled="!patientDetailsStore.watchers?.entry?.isChanged" type="primary" @click="save">
                    {{ $t("global.actions.saveChanges") }}
                </AsyncButton>
            </template>
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
    </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from "vue-router"
import { computed, onUnmounted, ref, watch } from "vue"
import { usePatientDetailsStore } from "@/modules/patients/detailStore"
import AsyncButton from "@/components/AsyncButton.vue"
import { useI18n } from "vue-i18n"
import { api } from "@/logic/api"
import { getRootRoutePath } from "@/logic/detailPage"
import { usePatientDiagnosisStore } from "@/modules/patients/patientDiagnosisStore"
import { usePatientSymptomsStore } from "@/modules/patients/patientSymptomsStore"
import { useToastStore } from "@/modules/global/toastStore"
import { useSettingsStore } from "@/modules/global/settingsStore"

const isDeleting = ref(false)
const isSaving = ref(false)
const isPatientDeleteModalOpened = ref(false)
const patientDetailsStore = usePatientDetailsStore()
const patientDiagnosisStore = usePatientDiagnosisStore()
const patientSymptomsStore = usePatientSymptomsStore()
const toastStore = useToastStore()
const settingsStore = useSettingsStore()
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
            patientDetailsStore.genericError = {}
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
    patientDetailsStore.genericError = {}
    patientDetailsStore.errors = {}
    isSaving.value = true
    let url = `/patients/create`
    const isNew = patientDetailsStore.isNewEntry
    if (!patientDetailsStore.isNewEntry) {
        url = `/patients/${patientDetailsStore.entryId}`
    }
    api.send(url, patientDetailsStore.isNewEntry ? "POST" : "PATCH", {}, patientDetailsStore.entry)
        .then((response: any) => {
            if (!isNew) {
                patientDetailsStore.entry = response.patient
            }
            patientDiagnosisStore.entries = response.patient.diagnosis
            patientSymptomsStore.entries = response.patient.symptoms
            router.replace({
                name: "patients/general",
                params: { id: response.patient.id },
            })
            const message = patientDetailsStore.isNewEntry ? "patients.patientCreatedSuccessfully" : "patients.patientUpdatedSuccessfully"
            toastStore.success(t(message))

            patientDetailsStore.watchers?.entry?.resetStore()
            isSaving.value = false
            if (props?.reloadList) {
                props?.reloadList()
            }
            if (isNew) {
                settingsStore.getLastFileNumber()
            }
        })
        .catch((error: any) => {
            isSaving.value = false
            if (error.errors && error.status === 422) {
                patientDetailsStore.errors = error.errors
            }
            if (error.id && error.file_number) {
                patientDetailsStore.genericError = {
                    id: error.id,
                    message: error.message,
                    code: "EXIST",
                }
                patientDetailsStore.watchers?.entry?.resetStore()
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
            isDeleting.value = false
            router.push(getRootRoutePath(route)).then(() => {
                toastStore.success(t("patients.patientDeletedSuccessfully"))
            })
            if (props?.reloadList) {
                props?.reloadList()
            }
            settingsStore.getLastFileNumber()
        })
        .catch(() => {
            isDeleting.value = false
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
