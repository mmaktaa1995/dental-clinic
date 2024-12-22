<template>
    <nav v-if="showNav" class="bg-gray-800">
        <div class="mx-auto px-2">
            <div class="relative flex items-center justify-between h-16">
                <div class="absolute flex inset-y-0 items-center pl-2 left-0 sm:mr-6 sm:pl-0">
                    <!-- Profile dropdown -->
                    <div class="mr-3 relative">
                        <div>
                            <button id="user-menu-button" type="button" class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white" aria-expanded="false" aria-haspopup="true" @click="toggleDropdown">
                                <span class="sr-only">Open user menu</span>
                                <img class="h-8 w-8 rounded-full" src="https://s3.amazonaws.com/laracasts/images/default-square-avatar.jpg" alt="" />
                            </button>
                        </div>

                        <!--
                          Dropdown menu, show/hide based on menu state.

                          Entering: "transition ease-out duration-100"
                            From: "transform opacity-0 scale-95"
                            To: "transform opacity-100 scale-100"
                          Leaving: "transition ease-in duration-75"
                            From: "transform opacity-100 scale-100"
                            To: "transform opacity-0 scale-95"
                        -->
                        <transition name="fade" mode="out-in">
                            <div v-if="show" class="origin-top-left absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-20" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <!-- Active: "bg-gray-100", Not Active: "" -->
                                <a id="user-menu-item-0" href="javascript:void(0);" class="block px-4 py-2 text-sm text-gray-700 transition transition-all duration-200 bg-white hover:bg-gray-100" role="menuitem" tabindex="-1">مرحبا @{{ accountStore.user!.name }}</a>
                                <a id="user-menu-item-1" href="#" class="block px-4 py-2 text-sm text-gray-700 transition transition-all duration-200 bg-white hover:bg-gray-100" role="menuitem" tabindex="-1" @click.prevent="logout()">تسجيل خروج</a>
                            </div>
                        </transition>
                    </div>
                </div>
                <div class="absolute flex md:hidden inset-y-0 items-center pl-2 left-0">
                    <!-- Profile dropdown -->
                    <div class="ml-3 relative">
                        <div @click="toggleSideBar()">
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
import { computed, ref } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useAccountStore, User } from "@/modules/auth/accountStore"
import axios from "axios"
import { useLayoutStore } from "@/store/layoutStore"

const accountStore = useAccountStore()

const show = ref(false)
const layoutStore = useLayoutStore()
const router = useRouter()
const route = useRoute()

const toggleDropdown = () => {
    show.value = !show.value
}

const toggleSideBar = () => {
    layoutStore.toggleSideBar()
}

const logout = () => {
    const logoutUrl = `/api/logout${accountStore.user?.admin ? "-admin" : ""}`
    axios.post(logoutUrl).then(() => {
        localStorage.clear()
        router.push({ name: "login" }).then(() => {
            // Assuming `bus` is a global event bus; adapt if necessary
            // EventBus.$emit('flash-message', {text: data.message, type: 'success'});
            accountStore.user = {} as User
            show.value = false
        })
    })
}

const showNav = computed(() => {
    return accountStore.user && !route.path.includes("unauthorized") && !route.path.includes("404")
})
</script>
