<template>
    <div
        :class="`fixed z-10 inset-0 overflow-y-auto `"
        aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!--
              Background overlay, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0"
                To: "opacity-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100"
                To: "opacity-0"
            -->
            <div
                :class="`fixed inset-0 bg-gray-500 transition-opacity duration-200 ${opened?'bg-opacity-75':'bg-opacity-0'}`"
                @click="back()"
                aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!--
              Modal panel, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                To: "opacity-100 translate-y-0 sm:scale-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100 translate-y-0 sm:scale-100"
                To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            -->
            <div
                :class="`inline-block w-full align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200  ${opened?'scale-100':'scale-0'}` ">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <!-- Heroicon name: outline/exclamation -->
                            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                حذف {{ type }}
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    هل أنت متأكد من حذف هذا ال{{ type }}؟
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row">
                    <async-button
                        type="button"
                        :loading="submitted"
                        @click="deleteItem()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mr-3 sm:w-auto sm:text-sm"
                    >
                        حذف
                    </async-button>
                    <button type="button" @click="back()"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mr-3 sm:w-auto sm:text-sm">
                        إلغاء
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import AsyncButton from "../../components/AsyncButton";

export default {
    components: {
        AsyncButton
    },
    data() {
        return {
            id: null,
            opened: false,
            submitted: false,
            type: ''
        }
    },
    mounted() {
        this.id = this.$route.params.id;
        this.type = this.$route.query.type;
        setTimeout(() => {
            this.opened = true
        }, 50)
    },
    methods: {
        back() {
            this.opened = false
            setTimeout(() => this.$router.back(), 100)
        },
        deleteItem() {
            let self = this;
            this.submitted = true;
            axios.delete(`/api/appointments/${self.id}`).then(({data}) => {
                bus.$emit('flash-message', {text: data.message, type: 'success'});
                bus.$emit('appointment-changed', self.id);
                this.$router.push({name: 'appointments'})
            }).catch(({response}) => {
                bus.$emit('flash-message', {text: response.data.message, type: 'error'});
            }).finally(() => {
                this.submitted = false;
            })
        }
    }
};
</script>
