<template>
    <div class="s-detailHeader bg-gray-800">
        <div class="s-detailHeader__left">
            <slot name="left"></slot>
        </div>
        <div class="s-detailHeader__right">
            <slot name="default" />
        </div>
    </div>
</template>

<style lang="scss" scoped>
.s-detailHeader {
    width: 100%;
    padding: 6px 16px;
    gap: 8px;
    display: flex;
    justify-content: space-between;
}

.s-detailHeader__right {
    display: flex;
    gap: 8px;
}
</style>
