<template>
    <button v-bind="$attrs" v-on="$listeners" :type="type" class="relative" :class="{ 'cursor-not-allowed': loading }" :disabled="disabled">
        <span class="absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%)" v-if="loading">
            <loader color="white" width="32"></loader>
        </span>

        <span :class="{ invisible: loading }">
            <slot></slot>
        </span>
    </button>
</template>

<script>
import Loader from './Loader';

export default {
    name: 'AsyncButton',
    components: {
        Loader,
    },

    props: {
        type: {
            default: 'button',
        },
        loading: {},
        disabled: {},
    },
    mounted(){
    }
};
</script>
