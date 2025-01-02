<template>
    <nav v-if="showNav" class="bg-gray-800">
        <div class="mx-auto px-2">
            <div class="relative flex items-center justify-between h-16">
                <div class="absolute flex inset-y-0 items-center pl-2 left-0 sm:mr-6 sm:pl-0 ltr:left-auto ltr:right-0 ltr:pl-0 ltr:sm:ml-6 ltr:sm:pr-0 ltr:sm:mr-0">
                    <div ref="languagesDropdown" class="mr-3 ltr:mr-0 ltr:ml-3 relative">
                        <div>
                            <button id="user-menu-button" type="button" class="text-white flex text-base focus:outline-none hover:border-b" aria-expanded="false" aria-haspopup="true" @click="toggleLanguagesDropdown">
                                <span class="sr-only">Open user menu</span>
                                {{ $t(`global.languages.${locale}`) }}
                            </button>
                        </div>
                        <transition name="fade" mode="out-in">
                            <div
                                v-if="showLanguagesDropdown"
                                class="origin-top-left ltr:origin-top-right absolute left-0 ltr:left-auto ltr:right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-20"
                                role="menu"
                                aria-orientation="vertical"
                                aria-labelledby="user-menu-button"
                                tabindex="-1"
                            >
                                <a
                                    v-for="(language, index) in availableLocales"
                                    :id="`language-item-${index}`"
                                    :key="language"
                                    href="javascript:void(0);"
                                    class="block px-4 py-2 text-sm text-gray-700 transition duration-200 bg-white hover:bg-gray-100"
                                    role="menuitem"
                                    tabindex="-1"
                                    @click="changeLang(language)"
                                >
                                    {{ $t(`global.languages.${language}`) }}
                                </a>
                            </div>
                        </transition>
                    </div>
                    <!-- Profile dropdown -->
                    <div ref="profileDropdown" class="mr-3 ltr:mr-0 ltr:ml-3 relative">
                        <div>
                            <button id="user-menu-button" type="button" class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" aria-expanded="false" aria-haspopup="true" @click="toggleDropdown">
                                <span class="sr-only">Open user menu</span>
                                <img class="h-8 w-8 rounded-full" src="https://s3.amazonaws.com/laracasts/images/default-square-avatar.jpg" alt="" />
                            </button>
                        </div>
                        <transition name="fade" mode="out-in">
                            <div
                                v-if="showProfileDropdown"
                                class="origin-top-left ltr:origin-top-right absolute left-0 ltr:left-auto ltr:right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-20"
                                role="menu"
                                aria-orientation="vertical"
                                aria-labelledby="user-menu-button"
                                tabindex="-1"
                            >
                                <a id="user-menu-item-0" href="javascript:void(0);" class="block px-4 py-2 text-sm text-gray-700 transition duration-200 bg-white hover:bg-gray-100" role="menuitem" tabindex="-1">{{ $t("global.hello") }} @{{ accountStore.user!.name }}</a>
                                <a id="user-menu-item-1" href="#" class="block px-4 py-2 text-sm text-gray-700 transition duration-200 bg-white hover:bg-gray-100" role="menuitem" tabindex="-1" @click.prevent="logout">{{ $t("global.logout") }}</a>
                            </div>
                        </transition>
                    </div>
                </div>
                <div class="absolute flex md:hidden inset-y-0 items-center pl-2 left-0">
                    <div class="ml-3 relative">
                        <div @click="toggleSideBar">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from "vue"
import { useRoute } from "vue-router"
import { useAccountStore } from "@/modules/auth/accountStore"
import { useLayoutStore } from "@/store/layoutStore"
import { useI18n } from "vue-i18n"
import { availableLanguages, setSelectedLanguage } from "@/logic/i18n"

const accountStore = useAccountStore()

const { locale, availableLocales } = useI18n()
const languagesDropdown = ref<HTMLElement | null>(null)
const profileDropdown = ref<HTMLElement | null>(null)
const i18n = useI18n()
const showProfileDropdown = ref(false)
const showLanguagesDropdown = ref(false)
const layoutStore = useLayoutStore()
const route = useRoute()

const toggleDropdown = () => {
    showProfileDropdown.value = !showProfileDropdown.value
}

const toggleSideBar = () => {
    layoutStore.toggleSideBar()
}

const logout = () => {
    accountStore.logout()
}

const toggleLanguagesDropdown = () => {
    showLanguagesDropdown.value = !showLanguagesDropdown.value
}

const changeLang = async (language: keyof typeof availableLanguages) => {
    i18n.locale.value = language
    await setSelectedLanguage(language)
}

const showNav = computed(() => {
    return accountStore.user && !route.path.includes("unauthorized") && !route.path.includes("404")
})

const handleClickOutside = (event: MouseEvent) => {
    if (profileDropdown.value && !profileDropdown.value.contains(event.target as Node)) {
        showProfileDropdown.value = false
    }
    if (languagesDropdown.value && !languagesDropdown.value.contains(event.target as Node)) {
        showLanguagesDropdown.value = false
    }
}

onMounted(() => {
    document.addEventListener("click", handleClickOutside)
})

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside)
})
</script>
