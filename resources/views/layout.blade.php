<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="robots" content="noindex, nofollow"/>

    <!-- Title -->
    <title>MWS Admin</title>

    <!-- Style sheets -->
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet"/>
    <link href="{{ asset('app.css') }}" rel="stylesheet" type="text/css"/>
</head>
<body>
<!-- Vue App-->
<div id="vapor-ui" class="font-sans antialiased h-screen flex overflow-hidden bg-gray-100" v-cloak>
    <div class="flex flex-shrink-0 sidebar" :class="{'active':showSidebar}"
         v-show="user && (!$route.path.includes('unauthorized') && !$route.path.includes('404'))">
        <div class="flex flex-col w-64">
            <div class="flex flex-col h-0 flex-1">

                <!-- Logo -->
                <div class="flex items-center h-16 px-4 bg-gray-900 text-xl text-white font-medium">
                    <svg viewBox="0 0 40 40" class="h-12 w-12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M4.345 9h10.55L9.618 20 4.345 9zm21.099 0h10.55l-5.276 11-5.274-11z" fill="#E9F9FD"
                              fill-opacity=".1"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M9.62 20h10.549l-5.275 11L9.62 20z"
                              fill="#25C4F2" fill-opacity=".22"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M20.169 20h10.55l-5.275 11-5.275-11z"
                              fill="#25C4F2" fill-opacity=".2"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M20.169 20H9.619l5.275-11 5.275 11z"
                              fill="#25C4F2" fill-opacity=".4"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M30.718 20h-10.55l5.276-11 5.274 11z"
                              fill="#25C4F2" fill-opacity=".4"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M25.444 31h-10.55l5.275-11 5.275 11z"
                              fill="#25C4F2" fill-opacity=".5"/>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                              d="M3.494 8.467A1 1 0 0 1 4.34 8h10.55a1 1 0 0 1 .902.568l4.373 9.12 4.373-9.12A1 1 0 0 1 25.44 8h10.55a1 1 0 0 1 .902 1.432L26.345 31.424a1.001 1.001 0 0 1-.905.576H14.89a1 1 0 0 1-.902-.568l-10.55-22a1 1 0 0 1 .056-.965zm21.95 2.846L29.13 19h-7.372l3.686-7.687zM5.934 10l3.686 7.687L13.306 10H5.933zm8.96 1.313L18.58 19h-7.372l3.686-7.687zM27.032 10l3.686 7.687L34.405 10h-7.373zm-1.588 18.687L21.758 21h7.372l-3.686 7.687zM23.855 30l-3.686-7.687L16.483 30h7.372zm-8.96-1.313L11.207 21h7.372l-3.686 7.687z"
                              fill="#25C4F2"/>
                    </svg>
                    <div class="ml-2" style="padding-top: 2px;">MWS AMW</div>
                </div>

                <div class="flex-1 flex flex-col overflow-y-auto bg-gray-800">
                    <!-- Logs tabs -->
                    <h3 class="px-3 mt-8 text-xs leading-4 font-semibold text-gray-500 uppercase tracking-wider">
                        System
                    </h3>

                    <nav class="px-2 py-4 bg-gray-800" v-if="user && user.admin">
                        <router-link
                            :to="{ name: `courses-index`}"
                            href="#"
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="group flex items-center px-2 py-2 text-sm leading-5 font-medium text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <icon-desktop-computer size="6"
                                                   class="mr-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></icon-desktop-computer>
                            Courses
                        </router-link>
                        <router-link
                            :to="{ name: `students-index`}"
                            href="#" exact
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="mt-1 group flex items-center px-2 py-2 text-sm leading-5 font-medium text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <icon-users size="6"
                                        class="mr-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></icon-users>
                            Students
                        </router-link>
                        <router-link
                            :to="{ name: `instructors-index`}"
                            href="#"
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="mt-1 group flex items-center px-2 py-2 text-sm leading-5 font-medium text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <icon-users size="6"
                                        class="mr-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></icon-users>
                            Instructors
                        </router-link>
                        <router-link
                            :to="{ name: `sections-index`}"
                            href="#"
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="mt-1 group flex items-center px-2 py-2 text-sm leading-5 font-medium text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <icon-collection size="6"
                                             class="mr-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></icon-collection>
                            Sections
                        </router-link>
                        <router-link
                            :to="{ name: `enrollments-index`}"
                            href="#"
                            exact
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="mt-1 group flex items-center px-2 py-2 text-sm leading-5 font-medium text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <icon-information-circle size="6"
                                                     class="mr-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></icon-information-circle>
                            Enrollments
                        </router-link>
                    </nav>
                    <nav class="px-2 py-4 bg-gray-800" v-if="user && !user.admin">
                        <router-link
                            :to="{ name: `students-my-courses`}"
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="group flex items-center px-2 py-2 text-sm leading-5 font-medium text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <icon-desktop-computer size="6"
                                                   class="mr-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></icon-desktop-computer>
                            My Courses
                        </router-link>
                        <router-link
                            :to="{ name: `students-enroll`}"
                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                            class="mt-1 group flex items-center px-2 py-2 text-sm leading-5 font-medium text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                        >
                            <icon-terminal size="6"
                                           class="mr-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></icon-terminal>
                            Enroll
                        </router-link>
                    </nav>

                    <!-- Logs tabs -->
                    {{--                    <h3 class="px-3 text-xs leading-4 font-semibold text-gray-500 uppercase tracking-wider" v-if="user && user.admin">--}}
                    {{--                        Analytics & Advanced Search--}}
                    {{--                    </h3>--}}

                    {{--                    <nav class="px-2 py-4 bg-gray-800" v-if="user && user.admin">--}}
                    {{--                        <router-link--}}
                    {{--                            :to="{ name: `metrics`}"--}}
                    {{--                            href="#"--}}
                    {{--                            active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"--}}
                    {{--                            class="group flex items-center px-2 py-2 text-sm leading-5 font-medium text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"--}}
                    {{--                        >--}}
                    {{--                            <icon-chart-bar size="6" class="mr-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"> </icon-chart-bar>--}}
                    {{--                            Metrics--}}
                    {{--                        </router-link>--}}

                    {{--                    </nav>--}}
                </div>
            </div>
        </div>
    </div>
    <flash-message></flash-message>
    <div class="w-full">
        <nav class="bg-gray-800" v-show="user && (!$route.path.includes('unauthorized') && !$route.path.includes('404'))">
            <div class="mx-auto px-2">
                <div class="relative flex items-center justify-between h-16">
                    <div class="absolute flex inset-y-0 items-center pr-2 right-0 sm:ml-6 sm:pr-0">
                        <!-- Profile dropdown -->
                        <div class="ml-3 relative">
                            <div>
                                <button @click="toggleDropdown()" type="button"
                                        class="bg-gray-800 flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-800 focus:ring-white"
                                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full"
                                         src="https://laracasts.com/images/reviews/tyler-walker.jpg" alt="">
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
                            <transition name="fade" duration="250" mode="out-in">
                                <div v-if="show"
                                     class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-20"
                                     role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button"
                                     tabindex="-1">
                                    <!-- Active: "bg-gray-100", Not Active: "" -->
                                    <a href="javascript:void(0);"
                                       class="block px-4 py-2 text-sm text-gray-700 transition transition-all duration-200 bg-white hover:bg-gray-100"
                                       role="menuitem" tabindex="-1" id="user-menu-item-0">Hello @{{user.full_name}}</a>
                                    <a href="#" @click.prevent="logout()"
                                       class="block px-4 py-2 text-sm text-gray-700 transition transition-all duration-200 bg-white hover:bg-gray-100"
                                       role="menuitem" tabindex="-1" id="user-menu-item-1">Sign out</a>
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
</script>
<script src="{{ asset('app.js') }}"></script>
</body>
</html>
