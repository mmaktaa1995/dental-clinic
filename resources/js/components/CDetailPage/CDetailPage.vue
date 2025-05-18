<template>
    <c-navigation-drawer v-model="isOpen" class="c-detailPage">
        <div class="c-detailPage__wrapper">
            <slot name="asd"></slot>
            <CDetailHeader>
                <template #left>
                    <CButton type="link" @click="(isOpen = false)">
                        <CIconChevronRight size="6" class="inline-flex ltr:rotate-180" />
                        {{ $t("cDetailPage.backToOverview") }}
                    </CButton>
                </template>
                <template #default>
                    <slot v-if="!store.isLoading" name="actionButtons" />
                </template>
            </CDetailHeader>
            <div class="c-detailPage__content">
                <div v-if="store.hasSubPages || $slots.sidebarHeader" class="c-detailPage__sidebar">
                    <div v-if="$slots.sidebarHeader" class="c-detailPage__sidebarHeader p-3">
                        <slot name="sidebarHeader"></slot>
                    </div>

                    <div class="c-detailPage__sidebarContent">
                        <KDetailPageMenu v-if="store.hasSubPages" :store="store" />
                        <div v-if="$slots.sidebarContent">
                            <slot name="sidebarContent"></slot>
                        </div>
                    </div>
                </div>
                <div class="c-detailPage__main">
                    <router-view v-if="!store.isLoading" />
                    <CDetailLoading v-else />
                </div>
            </div>
        </div>
    </c-navigation-drawer>
    <UnsavedChangesModal v-model="isUnsavedChangesWarningOpen" :close-unsaved-changes-warning="closeUnsavedChangesWarning" :discard-changes="discardChanges" />
</template>

<script lang="ts" setup>
import { computed, onUnmounted, toRef, watch } from "vue"
import { useRoute, useRouter } from "vue-router"
import KDetailPageMenu from "@/components/CDetailPage/CDetailPageMenu.vue"
import { getRootRoutePath } from "@/logic/detailPage"
import { DetailPageStore } from "@/store/factories/detailPageStore"
import { useMagicKeys, whenever } from "@vueuse/core"
import CDetailHeader from "@/components/CDetailPage/CDetailHeader.vue"
import CDetailLoading from "@/components/CDetailPage/CDetailLoading.vue"
import UnsavedChangesModal from "@/components/CDetailPage/UnsavedChangesModal.vue"
import { useUnsavedChangesWarning } from "@/composables/unsavedChangesWarning"

const props = withDefaults(
    defineProps<{
        store: DetailPageStore
        loadDataImmediately?: boolean
    }>(),
    {
        loadDataImmediately: true,
    },
)

const route = useRoute()
const router = useRouter()
const store = toRef(props, "store")

const { isUnsavedChangesWarningOpen, closeUnsavedChangesWarning, discardChanges, handleUnload } = useUnsavedChangesWarning(store.value)

const isOpen = computed({
    get() {
        return route.meta.isDetailPage === true
    },
    set(shouldBeOpen: boolean): void {
        if (shouldBeOpen) {
            if (props.loadDataImmediately) {
                props.store.loadData()
            }
        } else {
            router.push(getRootRoutePath(route))
        }
    },
})

onUnmounted(() => {
    tearDown()
})

function tearDown() {
    window.removeEventListener("beforeunload", handleUnload)
    document.documentElement.style.overflow = "unset"
}

// Reload the detail page's data whenever it's opened
watch(
    isOpen,
    () => {
        if (isOpen.value) {
            if (props.store.entryId && props.loadDataImmediately) {
                props.store.loadData()
            }
            window.addEventListener("beforeunload", handleUnload)
            document.documentElement.style.overflow = "hidden"
        } else {
            tearDown()
        }
    },
    {
        immediate: true,
    },
)

const keys = useMagicKeys()

whenever(keys.escape, () => {
    isOpen.value = false
})
</script>

<style lang="scss">
.c-detailPage {
    position: fixed !important;
    min-height: calc(100vh - #{64px}) !important;
    border-top-right-radius: 10px;
    overflow: hidden;
    min-width: 660px;
    max-width: 1200px;
    width: 80vw !important;
    box-shadow:
        0 8px 10px -5px var(--v-shadow-key-umbra-opacity, rgba(0, 0, 0, 0.2)),
        0 16px 24px 2px var(--v-shadow-key-penumbra-opacity, rgba(0, 0, 0, 0.14)),
        0 6px 30px 5px var(--v-shadow-key-ambient-opacity, rgba(0, 0, 0, 0.12));
}

.c-detailPage__wrapper {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.c-detailPage__content {
    display: flex;
    align-items: stretch;
    overflow: hidden;
    flex: 1;
}

.c-detailPage__sidebar {
    width: 240px;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
    gap: 1px;
    padding-right: 1px;
    background: #e5e8eb;
}

[dir="rtl"] .c-detailPage__sidebar {
    padding-left: 1px;
}

.c-detailPage__sidebarHeader {
    background: #fbfbfe;
}

.c-detailPage__sidebarContent {
    background: #fbfbfe;
    flex: 1;
}

.c-detailPage__main {
    scroll-padding-top: 10px;
    overflow-y: auto;
    padding-bottom: 40px;
    flex: 1;
}

//.drawer-slide-in-enter-active,
//.drawer-slide-in-leave-active {
//    transform: translateX(0) !important;
//}
//
//.drawer-slide-in-enter-from,
//.drawer-slide-in-leave-to {
//    .v-navigation-drawer {
//        transform: translateX(100%) !important;
//    }
//}
</style>
