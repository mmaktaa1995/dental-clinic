<template>
    <div
        class="s-accordion flex flex-col gap-2"
        :class="{
            '-noCollapsing': hideCollapsing,
            'pt-2': !$slots.title && !title && !description,
        }"
    >
        <div v-if="$slots.title || title || description" class="s-accordion__header" @click="expand">
            <div v-if="!$slots.title">
                <div v-if="title" class="text-base font-semibold text-gray-700">
                    {{ title }}
                </div>
                <p v-if="description" class="s-accordion__description text-sm text-gray-500">
                    {{ description }}
                </p>
            </div>
            <template v-else>
                <slot name="title" :is-expanded="isExpanded"></slot>
            </template>
            <c-icon v-if="!hideCollapsing" class="mr-3 s-accordion__chevron text-gray-900"> fas {{ isExpanded ? "fa-chevron-down" : "fa-chevron-down fa-rotate-180" }} </c-icon>
        </div>
        <div v-if="isExpanded" class="s-accordion__content">
            <slot></slot>
        </div>
        <div v-if="!hideDivider" class="s-accordion__divider"></div>
    </div>
</template>

<script lang="ts" setup>
import { ref, watch } from "vue"

const props = withDefaults(
    defineProps<{
        title?: string
        description?: string
        expandedByDefault?: boolean
        hideDivider?: boolean
        hideCollapsing?: boolean
    }>(),
    {
        title: "",
        description: "",
        expandedByDefault: true,
        hideDivider: false,
        hideCollapsing: false,
    },
)
const isExpanded = ref(props.expandedByDefault)

watch(
    () => props.expandedByDefault,
    (expandedByDefault) => {
        isExpanded.value = expandedByDefault
    },
)

const expand = () => {
    if (props.hideCollapsing) {
        return
    }
    isExpanded.value = !isExpanded.value
}
</script>

<style lang="scss" scoped>
.s-panel {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.s-accordion__header {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    padding: 8px 0;

    .s-accordion.-noCollapsing & {
        cursor: unset;
    }
}

.s-accordion__chevron {
    transition: 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

.s-accordion__content {
    margin-bottom: 8px;
}

.s-accordion__divider {
    height: 1px;
    margin-bottom: 8px;

    @apply bg-gray-200;
}
</style>
