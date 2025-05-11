<template>
    <div class="mx-4">
        <div class="s-loadingInput"></div>
        <div class="s-loadingInput"></div>
        <div class="s-loadingInput"></div>
    </div>
</template>

<style lang="scss" scoped>
@keyframes skeleton-wave {
    0% {
        transform: translate(-100%);
    }
    to {
        transform: translate(100%);
    }
}
.s-loadingInput {
    width: 100%;
    height: 36px;
    border-radius: 4px;
    margin-bottom: 16px;
    position: relative;
    overflow: hidden;
    background: #e5e8eb;
    border: 1px solid #e5e8eb;

    &:after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: linear-gradient(90deg, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0));
        animation: skeleton-wave 1.5s linear 0.5s infinite;
    }
}
</style>
