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

<style lang="scss" scoped>
$topMargin: 4px;
$color-grey1: #fbfbfe;
$color-grey2: #e5e8eb;
$color-grey3: #a1a9b0;
$color-grey4: #7a7e8a;
$color-orange: #fea400;
$color-pink: #f95779;
$color-red: #b71c1c;
$color-tu1: #4ecede;
$color-tu2: #18b7ce;

:global(.c-detailPage) {
    position: fixed !important;
    min-height: calc(100vh - #{64px}) !important;
    border-top-right-radius: 10px;
    overflow: hidden;
    min-width: 660px;
    max-width: 1200px;
    width: 80vw !important;
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
    padding-left: 1px;
    background: $color-grey2;
}

.c-detailPage__sidebarHeader {
    background: $color-grey1;
}

.c-detailPage__sidebarContent {
    background: $color-grey1;
    flex: 1;
}

.c-detailPage__main {
    scroll-padding-top: 10px;
    overflow-y: auto;
    padding-bottom: 40px;
    flex: 1;
}
</style>
