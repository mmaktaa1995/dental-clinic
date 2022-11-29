<nav class="bg-gray-800" v-if="user && (!$route.path.includes('unauthorized') && !$route.path.includes('404'))">
    <div class="mx-auto px-2">
        <div class="relative flex items-center justify-between h-16">
            <div class="absolute flex inset-y-0 items-center pl-2 left-0 sm:mr-6 sm:pl-0">
                <!-- Profile dropdown -->
                <div class="mr-3 relative">
                    <div>
                        <button @click="toggleDropdown()"  type="button"
                                class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                                id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            <span class="sr-only">Open user menu</span>
                            <img class="h-8 w-8 rounded-full"
                                 src="https://s3.amazonaws.com/laracasts/images/default-square-avatar.jpg" alt="">
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
                    <transition name="fade" duration="150" mode="out-in">
                        <div v-if="show"
                             class="origin-top-left absolute left-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-20"
                             role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                             tabindex="-1">
                            <!-- Active: "bg-gray-100", Not Active: "" -->
                            <a href="javascript:void(0);"
                               class="block px-4 py-2 text-sm text-gray-700 transition transition-all duration-200 bg-white hover:bg-gray-100"
                               role="menuitem" tabindex="-1" id="user-menu-item-0">مرحبا @{{user.full_name}}</a>
                            <a href="#" @click.prevent="logout()"
                               class="block px-4 py-2 text-sm text-gray-700 transition transition-all duration-200 bg-white hover:bg-gray-100"
                               role="menuitem" tabindex="-1" id="user-menu-item-1">تسجيل خروج</a>
                        </div>
                    </transition>
                </div>
            </div>
            <div class="absolute flex md:hidden inset-y-0 items-center pl-2 left-0">
                <!-- Profile dropdown -->
                <div class="ml-3 relative">
                    <div @click="toggleSideBar()">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
