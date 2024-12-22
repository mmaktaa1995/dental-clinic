<template>
    <CDetailPage :store="patientDetailsStore" :load-data-immediately="false">
        <template #sidebarHeader>
            <div v-if="patientDetailsStore.entry.id" class="c-detailsWrapper__sidebarHeader flex items-center">
                <img src="/images/user.png" class="h-9 w-9 ml-3 ltr:mr-3 ltr:ml-0" :alt="patientDetailsStore.entry.name" />
                <div class="d-flex flex-column">
                    <div class="text-base font-semibold text-gray-900">{{ patientDetailsStore.entry.name }}</div>
                    <div v-if="!patientDetailsStore.isNewEntry" class="text-base font-normal text-teal-600">#{{ patientDetailsStore.entry.file_number }}</div>
                </div>
            </div>
        </template>
        <template #actionButtons>
            <CDropdown v-if="!patientDetailsStore.isNewEntry" :loading="isDeleting" :items="actions" :button-label="'الاإجرا،ات'" @select="handleAction"></CDropdown>
            <AsyncButton v-if="patientDetailsStore.isNewEntry" :disabled="!patientDetailsStore.watchers?.entry.isChanged" :loading="isSaving" type="primary" @click="save">
                {{ $t("global.create") }}
            </AsyncButton>
            <AsyncButton v-else :loading="isSaving" :disabled="!patientDetailsStore.watchers?.entry.isChanged" type="primary" @click="save">
                {{ $t("global.saveChanges") }}
            </AsyncButton>
        </template>
        <!--            <template v-if="patientDetailsStore.entry.isBinned" #sidebarContent>-->
        <!--                <KAlert>{{ $t("appointments.appointmentDetailsPage.alerts.isBinned") }}</KAlert>-->
        <!--            </template>-->
    </CDetailPage>
    <CConfirmModal v-model="isPatientDeleteModalOpened" :confirm-title="`حذف ${type}`" :confirm-body-message="`هل أنت متأكد من حذف هذا ال${type}؟`" @confirm-callback="deletePatient"> </CConfirmModal>
    <!--        <AppointmentDeleteModal v-model="isAppointmentDeleteModalOpen" @delete-appointment="deleteAppointment()"></AppointmentDeleteModal>-->
</template>

<script setup lang="ts">
import { useRoute, useRouter } from "vue-router"
import { computed, ref, watch } from "vue"
import { usePatientDetailStore } from "@/modules/patients/detailStore"
import axios from "axios"
import AsyncButton from "@/components/AsyncButton.vue"

// const accountStore = useAccountStore()
const isDeleting = ref(false)
const isPatientDeleteModalOpened = ref(false)
const patientDetailsStore = usePatientDetailStore()
const router = useRouter()
const route = useRoute()
patientDetailsStore.addWatcher("entry")

const isSaving = ref(false)

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
    let url = `/api/patients/`
    if (!patientDetailsStore.isNewEntry) {
        url += patientDetailsStore.entryId
    }
    axios[patientDetailsStore.isNewEntry ? "post" : "patch"](url, patientDetailsStore.entry)
        .then(({ data }) => {
            console.log(data)
            router.replace({ name: "patients/general", params: { id: data.id } })
        })
        .catch((error) => {
            if (error.response && error.response.status === 422) {
                patientDetailsStore.errors = error.response.data.errors
            }
        })
        .finally(() => {
            isSaving.value = false
        })
}

const actions = computed(() => {
    const actions: Record<string, string> = {
        delete: "Delete",
    }

    return actions
})
const deletePatient = () => {
    submitted.value = true
    axios
        .delete(`/api/patients/${patientDetailsStore.entryId}`)
        .then(({ data }) => {
            // bus.emit("flash-message", { text: data.message, type: "success" });
            // bus.emit("item-deleted", id.value);
            back()
        })
        .catch(({ response }) => {
            // bus.emit("flash-message", { text: response.data.message, type: "error" });
        })
        .finally(() => {
            submitted.value = false
        })
}
// const deleteAppointment = async () => {
//     if (isDeleting.value || patientDetailsStore.entry.isBinned) {
//         return
//     }
//     isDeleting.value = true
//
//     try {
//         await api.send(`/appointments/${patientDetailsStore.entryId}`, "DELETE", undefined)
//     } catch (e) {
//         isDeleting.value = false
//         if (typeof e === "string") snackbarsStore.error(e)
//         isAppointmentDeleteModalOpen.value = false
//         return
//     }
//
//     isDeleting.value = false
//     isAppointmentDeleteModalOpen.value = false
//     snackbarsStore.success(t("appointments.appointmentDeleting.deletedSuccessfully"))
//     if (customDeleteCallback) {
//         customDeleteCallback()
//     } else {
//         props.reloadList?.()
//         close()
//     }
// }
//
// const close = () => {
//     router.push(getRootRoutePath(route))
// }
//
const handleAction = async (action: string) => {
    switch (action) {
        case "delete": {
            isPatientDeleteModalOpened.value = true
            break
        }
    }
    console.log(action)
}
//     switch (action) {
//         case "delete": {
//             isAppointmentDeleteModalOpen.value = true
//             break
//         }
//         case "duplicate": {
//             isActionRunning.value = true
//             try {
//                 const response = await api.post(`/appointments/${patientDetailsStore.entryId}/duplicate`)
//                 isActionRunning.value = false
//                 await router.push({ name: `appointments/appointment-general`, params: { appointmentId: response.id } })
//                 snackbarsStore.success(t("appointments.appointmentDetailsPage.duplicatedSuccessFully"))
//             } catch (e) {
//                 isActionRunning.value = false
//                 snackbarsStore.error(t("global.dataSaveFailure"))
//             }
//             break
//         }
//     }
// }
</script>
