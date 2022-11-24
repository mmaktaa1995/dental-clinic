<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="robots" content="noindex, nofollow"/>

    <!-- Title -->
    <title>Aktaa Dental</title>

    <!-- Style sheets -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"/>
    <link href="{{ mix('app.css') }}" rel="stylesheet" type="text/css"/>
</head>
<body>
<!-- Vue App-->
<div id="vapor-ui" class="antialiased min-h-screen flex overflow-hidden bg-gray-100" v-cloak>
    <div class="flex flex-shrink-0 sidebar" :class="{'active':showSidebar}"
         v-if="user && (!$route.path.includes('unauthorized') && !$route.path.includes('404'))">
        <div class="flex flex-col w-64">
            <div class="flex flex-col h-0 flex-1">

                <!-- Logo -->
                <div class="flex items-center justify-center px-4 bg-gray-800 text-xl text-white font-medium">
                    <img src="/images/long-logo.png" alt="logo" style="filter: brightness(11.5)  drop-shadow(2px 1px 5px #000)">
                </div>

                <div class="flex-1 flex flex-col overflow-y-auto bg-gray-800">
                    <!-- Logs tabs -->
                    <h3 class="px-3 mt-8 text-xs leading-4 font-semibold text-gray-500 uppercase tracking-wider">
                        النظام
                    </h3>

                    <nav class="px-2 py-4 bg-gray-800" v-if="user && user.admin">
                        <router-link
                            :to="{ name: `statistics`}"
                            href="#"
                            exact
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="mt-1 group flex items-center px-2 py-2 text-sm leading-5  text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <icon-chart-bar size="6"
                                            class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></icon-chart-bar>
                            الإحصائيات
                        </router-link>
                        <router-link
                            :to="{ name: `patients-index`}"
                            exact
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="group flex items-center px-2 py-2 text-sm leading-5  text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <icon-users size="6"
                                                   class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></icon-users>
                            المرضى
                        </router-link>
                        <router-link
                            :to="{ name: `patients-files-index`}"
                            exact
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="mt-1 group flex items-center px-2 py-2 text-sm leading-5  text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <icon-file size="6"
                                       class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></icon-file>
                            الإضبارات
                        </router-link>
                        <router-link
                            :to="{ name: `visits-index`}"
                            exact
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="mt-1 group flex items-center px-2 py-2 text-sm leading-5  text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <icon-desktop-computer size="6"
                                        class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></icon-desktop-computer>
                            الزيارات
                        </router-link>
                        <router-link
                            :to="{ name: `expenses-index`}"
                            href="#" exact
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="mt-1 group flex items-center px-2 py-2 text-sm leading-5  text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <icon-expenses size="6"
                                        class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></icon-expenses>
                            النفقات
                        </router-link>
                        <router-link
                            :to="{ name: `appointments`}"
                            href="#"
                            exact
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="mt-1 group flex items-center px-2 py-2 text-sm leading-5  text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <icon-calendar size="6"
                                        class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></icon-calendar>
                            المواعيد
                        </router-link>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <flash-message></flash-message>
    <div class="w-full">
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
        <router-view></router-view>
    </div>
</div>
<script>
    var app;
    var user = @json(Auth::guard('api')->user());
    var lastFileNumber = +@json($lastFileNumber);
</script>
<script src="{{ mix('app.js') }}"></script>
</body>
</html>
