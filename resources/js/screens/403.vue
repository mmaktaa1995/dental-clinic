<template>
    <div class="h-screen w-screen bg-gray-100 flex items-center">
        <div class="container flex flex-col md:flex-row items-center justify-center px-5 text-gray-700">
            <div class="max-w-md">
                <div class="text-5xl font-dark font-bold">403</div>
                <p
                    class="text-2xl md:text-3xl font-light leading-normal"
                >Sorry you are not authorized to view this page. </p>

                <router-link tag="button" :to="{name:'home'}"
                             class="px-4 inline py-2 text-sm font-medium leading-5 shadow text-white transition-colors duration-150 border border-transparent rounded-lg focus:outline-none focus:shadow-outline-blue bg-blue-600 active:bg-blue-600 hover:bg-blue-700">
                    back to homepage
                </router-link>
            </div>
            <div class="max-w-lg">
                <img src="" alt="">
            </div>

        </div>
    </div>
</template>

<script>
export default {
}
</script>

<style scoped>

</style>
