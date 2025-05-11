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
                        <c-icon-chart-bar size="6"
                                        class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></c-icon-chart-bar>
                        الإحصائيات
                    </router-link>
                    <router-link
                        :to="{ name: `patients-index`}"
                        exact
                        active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                        class="group flex items-center px-2 py-2 text-sm leading-5  text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                    >
                        <c-icon-users size="6"
                                    class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></c-icon-users>
                        المرضى
                    </router-link>
                    <router-link
                        :to="{ name: `patients-files-index`}"
                        exact
                        active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                        class="mt-1 group flex items-center px-2 py-2 text-sm leading-5  text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                    >
                        <c-icon-file size="6"
                                   class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></c-icon-file>
                        الإضبارات
                    </router-link>
                    <router-link
                        :to="{ name: `payments-index`}"
                        exact
                        active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                        class="mt-1 group flex items-center px-2 py-2 text-sm leading-5  text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                    >
                        <c-icon-money size="6"
                                               class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></c-icon-money>
                        الدفعات
                    </router-link>
                    <router-link
                        :to="{ name: `debits-index`}"
                        href="#" exact
                        active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                        class="mt-1 group flex items-center px-2 py-2 text-sm leading-5  text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                    >
                        <c-icon-debit size="6"
                                       class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></c-icon-debit>
                        المبالغ المتبقية
                    </router-link>
                    <router-link
                        :to="{ name: `expenses-index`}"
                        href="#" exact
                        active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                        class="mt-1 group flex items-center px-2 py-2 text-sm leading-5  text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                    >
                        <c-icon-expenses size="6"
                                       class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></c-icon-expenses>
                        النفقات
                    </router-link>
                    <router-link
                        :to="{ name: `appointments-index`}"
                        href="#"
                        exact
                        active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                        class="mt-1 group flex items-center px-2 py-2 text-sm leading-5  text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                    >
                        <c-icon-calendar size="6"
                                       class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></c-icon-calendar>
                        المواعيد
                    </router-link>
                    <router-link
                        :to="{ name: `services-index`}"
                        href="#"
                        exact
                        active-class="text-white bg-gray-900 focus:outline-none focus:bg-gray-900 transition ease-in-out duration-150"
                        class="mt-1 group flex items-center px-2 py-2 text-sm leading-5  text-gray-300 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700 transition ease-in-out duration-150"
                    >
                        <c-icon-collection size="6"
                                       class="ml-3 text-gray-400 group-hover:text-gray-300 group-focus:text-gray-300 transition ease-in-out duration-150"></c-icon-collection>
                        الخدمات
                    </router-link>
                </nav>
            </div>
        </div>
    </div>
</div>
