(window.webpackJsonp=window.webpackJsonp||[]).push([[21],{bkmz:function(t,e,s){"use strict";s.r(e);s("vDqi");var i={name:"upload-files",data:function(){return{id:null,opened:!1,errors:{},submitted:!1,files:[]}},mounted:function(){var t=this;this.id=this.$route.params.id,setTimeout((function(){t.opened=!0}),50)},methods:{back:function(){var t=this;this.opened=!1,setTimeout((function(){return t.$router.back()}),300)}}},n=s("KHd+"),a=Object(n.a)(i,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{class:"fixed z-10 inset-0 overflow-y-auto ",attrs:{"aria-labelledby":"modal-title",role:"dialog","aria-modal":"true"}},[s("div",{staticClass:"flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"},[s("div",{class:"fixed inset-0 bg-gray-500 transition-opacity duration-200 "+(t.opened?"bg-opacity-75":"bg-opacity-0"),attrs:{"aria-hidden":"true"},on:{click:function(e){return t.back()}}}),t._v(" "),s("span",{staticClass:"hidden sm:inline-block sm:align-middle sm:h-screen",attrs:{"aria-hidden":"true"}},[t._v("​")]),t._v(" "),s("div",{class:"inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200  "+(t.opened?"scale-100":"scale-0")},[t._m(0),t._v(" "),s("div",{staticClass:"bg-white px-4 pt-5 sm:p-6"},[s("div",{staticClass:"grid grid-cols-2 gap-6"},[s("div",{staticClass:"col-span-full"},[s("file-pond-component",{attrs:{folder:"patients",type:"images"}})],1)])]),t._v(" "),s("div",{staticClass:"bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-revers"},[s("button",{staticClass:"mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm",attrs:{type:"button"},on:{click:function(e){return t.back()}}},[t._v("\n                    إلغاء\n                ")]),t._v(" "),s("async-button",{staticClass:"w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm",attrs:{type:"submit",loading:t.submitted}},[t._v("\n                    حفظ\n                ")])],1)])])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("div",{staticClass:"bg-gray-50 px-4 py-2 border-b border-gray-300 text-right"},[e("h3",{staticClass:"text-lg text-gray-700 font-normal"},[this._v(" ملفات المريض")])])}],!1,null,"346b0580",null);e.default=a.exports}}]);