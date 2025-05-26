<template>
    <div>
        <CDataTable :store="rolesStore" :columns="columns" @row-clicked="openRoleDetails">
            <template #header>
                <div class="flex justify-between items-center">
                    <div class="font-semibold text-lg">{{ $t("roles.moduleName") }}</div>
                    <CPermissionGuard permission="create-roles">
                        <CButton sm type="primary" :to="{ name: 'roles-create' }"> {{ $t("global.actions.create") }}</CButton>
                    </CPermissionGuard>
                </div>
            </template>
            <template #filters>
                <div class="grid grid-cols-2 gap-4 w-full">
                    <CTextField v-model="rolesStore.query" class="w-100" :label="$t('roles.name')" name="name"></CTextField>
                </div>
            </template>
        </CDataTable>
        <CDetailPageOutlet :reload-list="reload" />
    </div>
</template>

<script setup lang="ts">
import { useEntryListUpdater } from "@/composables/entryListUpdater"
import { RoleEntry, useRolesStore } from "@/modules/roles/store"
import { useRouter } from "vue-router"
import CDetailPageOutlet from "@/components/CDetailPage/CDetailPageOutlet.vue"
import { useI18n } from "vue-i18n"
import { DataTableColumn } from "@/components/Table/DataTable.vue"
import DateTime from "@/components/Table/components/DateTime.vue"
import { useRouteQueryParam } from "@/logic/routeQuerySync"
import { storeToRefs } from "pinia"
import PermissionTags from "../components/PermissionTags.vue"

const rolesStore = useRolesStore()
const router = useRouter()
const { t } = useI18n()
const { query } = storeToRefs(rolesStore)

useRouteQueryParam("query", undefined, "string", { targetRef: query })

const { reload } = useEntryListUpdater("/roles", rolesStore)

const columns: DataTableColumn[] = [
    { field: "name", headerName: t("roles.name") },
    { field: "slug", headerName: t("roles.slug") },
    { field: "permissions", headerName: t("roles.permissions"), cellRenderer: PermissionTags },
    { field: "created_at", headerName: t("roles.createdAt"), cellRenderer: DateTime },
]

const openRoleDetails = (rowData: RoleEntry) => {
    router.push({ name: "roles/general", params: { id: rowData.id } })
}
</script>
