<template>
    <div class="w-full">
        <!--        <CSearch>-->
        <!--            <template #create-btn>-->
        <!--                <CButton type="primary" :to="{ name: `patients/general`, params: { id: -1 } }"> إضافة</CButton>-->
        <!--            </template>-->
        <!--            <template #head>-->
        <!--                <tr class="bg-gray-200 text-gray-600 text-sm leading-normal">-->
        <!--                    <th class="py-2 px-3 text-right">الاسم</th>-->
        <!--                    <th class="py-2 px-3 text-right">رقم الملف</th>-->
        <!--                    <th class="py-2 px-3 text-right">رقم الموبايل</th>-->
        <!--                    <th class="py-2 px-3 text-right">تاريخ الإنشاء</th>-->
        <!--                    <th class="py-2 px-3 text-right"></th>-->
        <!--                </tr>-->
        <!--            </template>-->
        <!--            <template #row="{ entry }">-->
        <!--                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">-->
        <!--                    {{ entry.name }}-->
        <!--                </td>-->
        <!--                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">-->
        <!--                    {{ entry.file_number }}-->
        <!--                </td>-->
        <!--                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">-->
        <!--                    {{ entry.mobile ? entry.mobile : entry.phone }}-->
        <!--                </td>-->
        <!--                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">-->
        <!--                    {{ entry.created_at }}-->
        <!--                </td>-->
        <!--                <td class="px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">-->
        <!--                    <div class="flex item-center">-->
        <!--                        <router-link-->
        <!--                            :to="{-->
        <!--                                name: `patients-files-show`,-->
        <!--                                params: { id: entry.id },-->
        <!--                                query: entry.filters,-->
        <!--                            }"-->
        <!--                            class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150"-->
        <!--                        >-->
        <!--                            <c-icon-eye size="6" class="text-gray-400 hover:text-blue-500 transition-colors" />-->
        <!--                        </router-link>-->
        <!--                        &lt;!&ndash;                        <router-link&ndash;&gt;-->
        <!--                        &lt;!&ndash;                            :to="{&ndash;&gt;-->
        <!--                        &lt;!&ndash;                            name: `patients-visits`,&ndash;&gt;-->
        <!--                        &lt;!&ndash;                            params: { id: entry.id},&ndash;&gt;-->
        <!--                        &lt;!&ndash;                        }"&ndash;&gt;-->
        <!--                        &lt;!&ndash;                            tag="a"&ndash;&gt;-->
        <!--                        &lt;!&ndash;                            class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150"&ndash;&gt;-->
        <!--                        &lt;!&ndash;                        >&ndash;&gt;-->
        <!--                        &lt;!&ndash;                            <c-icon-list&ndash;&gt;-->
        <!--                        &lt;!&ndash;                                size="5"&ndash;&gt;-->
        <!--                        &lt;!&ndash;                                class=" text-gray-400 hover:text-indigo-500 transition-colors"&ndash;&gt;-->
        <!--                        &lt;!&ndash;                            />&ndash;&gt;-->
        <!--                        &lt;!&ndash;                        </router-link>&ndash;&gt;-->
        <!--                        <router-link-->
        <!--                            :to="{-->
        <!--                                name: `patients-files`,-->
        <!--                                params: { id: entry.id },-->
        <!--                            }"-->
        <!--                            class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150"-->
        <!--                        >-->
        <!--                            <c-icon-file size="5" class="text-gray-400 hover:text-indigo-500 transition-colors" />-->
        <!--                        </router-link>-->
        <!--                        <router-link-->
        <!--                            :to="{-->
        <!--                                name: `patients/general`,-->
        <!--                                params: { id: entry.id },-->
        <!--                                query: entry.filters,-->
        <!--                            }"-->
        <!--                            class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150"-->
        <!--                        >-->
        <!--                            <c-icon-edit size="5" class="text-gray-400 hover:text-blue-500 transition-colors" />-->
        <!--                        </router-link>-->
        <!--                        <div class="w-4 mr-2 transform hover:text-purple-500">-->
        <!--                            <router-link-->
        <!--                                :to="{-->
        <!--                                    name: `patients-delete`,-->
        <!--                                    params: { id: entry.id },-->
        <!--                                    query: { type: 'مريض' },-->
        <!--                                }"-->
        <!--                                class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent transition-colors hover:text-red-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150"-->
        <!--                            >-->
        <!--                                <c-icon-delete size="5" class="text-gray-400 hover:text-red-500 transition-colors" />-->
        <!--                            </router-link>-->
        <!--                        </div>-->
        <!--                    </div>-->
        <!--                </td>-->
        <!--            </template>-->
        <!--        </CSearch>-->
        <CDataTable :store="patientsStore" :columns="columns" @row-clicked="openPatientDetails">
            <template #header>
                <div class="font-semibold text-lg">{{ $t("patients.moduleName") }}</div>
            </template>
            <template #filters>
                <div class="grid grid-cols-2 gap-4 w-full">
                    <CTextField v-model="patientsStore.query" class="w-100" :label="$t('patients.name')" name="name"></CTextField>
                    <CTextField v-model="patientsStore.file_number" class="w-100" :label="$t('patients.fileNumber')" name="file_number"></CTextField>
                    <CDatePicker v-model="patientsStore.from_date" :label="$t('global.from_date')" name="from_date"></CDatePicker>
                    <CDatePicker v-model="patientsStore.to_date" :label="$t('global.to_date')" name="to_date"></CDatePicker>
                </div>
            </template>
        </CDataTable>
        <CDetailPageOutlet :reload-list="reload" />
    </div>
</template>

<script setup>
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import { usePatientsStore } from "@/modules/patients/store"
import { useRouter } from "vue-router"
import CDetailPageOutlet from "@/components/CDetailPage/CDetailPageOutlet.vue"
import { useI18n } from "vue-i18n"

const patientsStore = usePatientsStore()
const router = useRouter()
const { t } = useI18n()
const { reload } = useEntryListUpdater("/patients", patientsStore)

const columns = [
    { field: "name", headerName: t("patients.name") },
    { field: "file_number", headerName: t("patients.fileNumber") },
    { field: "mobile", headerName: t("patients.mobile") },
    { field: "created_at", headerName: t("patients.createdAt") },
]

const openPatientDetails = (rowData) => {
    router.push({ name: "patients/general", params: { id: rowData.id } })
}
</script>
