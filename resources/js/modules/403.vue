<template>
    <div class="h-screen w-screen bg-gray-100 flex items-center">
        <div class="container flex flex-col md:flex-row items-center justify-center px-5 text-gray-700">
            <div class="max-w-md">
                <div class="text-5xl font-dark font-bold">{{ $t("error.errorCode403", "403") }}</div>
                <p class="text-2xl md:text-3xl font-light leading-normal mb-3">{{ $t("error.notAuthorizedToViewPage") }}</p>

                <router-link :to="{ name: 'home' }" class="px-4 inline py-2 text-sm font-medium leading-5 shadow text-white transition-colors duration-150 border border-transparent rounded-lg focus:outline-none focus:shadow-outline-blue bg-sky-600 active:bg-sky-600 hover:bg-sky-700">
                    {{ $t("error.backToHomepage") }}
                </router-link>
            </div>
            <div class="max-w-lg">
                <img src="/images/long-logo.png" alt="Logo" />
            </div>
        </div>
    </div>
</template>
