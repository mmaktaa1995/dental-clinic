(window.webpackJsonp=window.webpackJsonp||[]).push([[12],{d6Hv:function(t,e,a){"use strict";a.r(e);var s=a("vDqi"),n=a.n(s);function i(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,s)}return a}function r(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?i(Object(a),!0).forEach((function(e){o(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):i(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}function o(t,e,a){return e in t?Object.defineProperty(t,e,{value:a,enumerable:!0,configurable:!0,writable:!0}):t[e]=a,t}var d={components:{AsyncButton:a("gU9Z").default},data:function(){return{id:null,payment_id:null,isFormManipulating:!1,submitted:!1,isEdit:!1,patient_name:"",patient_file_number:"",data:[],type:"",totalPayments:0,form:{amount:"",date:"",notes:""}}},mounted:function(){this.id=this.$route.params.id,this.type=this.$route.query.type,this.getData()},methods:{back:function(){var t=this;setTimeout((function(){return t.$router.back()}),100)},resetForm:function(){this.form={amount:"",date:"",notes:""}},getData:function(){var t=this;n.a.get("/api/patients-files/".concat(this.id)).then((function(e){var a=e.data;t.data=a.map((function(t){return t.isEdit=!1,t})),t.data.length&&(t.patient_name=t.data[0].patient.name,t.patient_file_number=t.data[0].patient.file_number,t.totalPayments=t.data.reduce((function(t,e){return t+ +e.amount}),0))}))},print:function(){n.a.get("/api/patients-files/".concat(this.id,"/print")).then((function(t){var e=t.data;window.open(e.file,"blank")}))},addPayment:function(){var t=this;this.submitted=!0;var e=r(r({},this.form),{},{patient_id:this.id});n.a.post("/api/patients-files",e).then((function(e){var a=e.data;bus.$emit("flash-message",{text:a.message,type:"success"}),t.resetForm(),t.isFormManipulating=!1,t.getData()})).catch((function(t){var e=t.response;bus.$emit("flash-message",{text:e.data.message,type:"error"})})).finally((function(){t.submitted=!1}))},savePayment:function(t){var e=this;this.submitted=!0;var a={amount:t.amount,date:t.date,notes:t.visit.notes,patient_id:this.id};n.a.put("/api/patients-files/".concat(this.payment_id),a).then((function(a){var s=a.data;bus.$emit("flash-message",{text:s.message,type:"success"}),e.resetForm(),t.isEdit=!1,e.getData()})).catch((function(t){var e=t.response;bus.$emit("flash-message",{text:e.data.message,type:"error"})})).finally((function(){e.submitted=!1}))},deletePayment:function(t){var e=this;n.a.delete("/api/patients-files/".concat(t)).then((function(t){var a=t.data;bus.$emit("flash-message",{text:a.message,type:"success"}),e.getData()})).catch((function(t){var e=t.response;bus.$emit("flash-message",{text:e.data.message,type:"error"})})).finally((function(){e.submitted=!1}))},editPayment:function(t){t.isEdit=!0,this.payment_id=t.id,this.form={amount:t.amount,date:t.date,notes:t.visit.notes}}},computed:{isPatientFilesDetails:function(){return"patients-files-show"===this.$route.name}}},l=a("KHd+"),u=Object(l.a)(d,(function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"px-12 py-6"},[a("div",{staticClass:"w-full text-left"},[a("button",{staticClass:"py-1 items-center justify-center h-12 px-4 text-sm text-center text-gray-100 hover:text-gray-50 bg-purple-500 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-purple-600 focus:outline-none",on:{click:function(e){return t.print()}}},[t._v("\n            طباعة\n        ")])]),t._v(" "),a("div",{staticClass:"grid grid-cols-1 sm:grid-cols-2 gap-6"},[a("div",{},[a("label",{staticClass:"block text-sm font-medium text-gray-700 text-right",attrs:{for:"name"}},[t._v("الاسم")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.patient_name,expression:"patient_name"}],staticClass:"block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",attrs:{type:"text",id:"name",disabled:""},domProps:{value:t.patient_name},on:{input:function(e){e.target.composing||(t.patient_name=e.target.value)}}})]),t._v(" "),a("div",{},[a("label",{staticClass:"block text-sm font-medium text-gray-700 text-right",attrs:{for:"file_number"}},[t._v("رقم الملف")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.patient_file_number,expression:"patient_file_number"}],staticClass:"block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",attrs:{type:"text",id:"file_number",disabled:""},domProps:{value:t.patient_file_number},on:{input:function(e){e.target.composing||(t.patient_file_number=e.target.value)}}})]),t._v(" "),a("div",{},[a("label",{staticClass:"block text-sm font-medium text-gray-700 text-right",attrs:{for:"totalPayments"}},[t._v("إجمالي المبلغ\n                الدفوع")]),t._v(" "),a("input",{directives:[{name:"model",rawName:"v-model",value:t.totalPayments,expression:"totalPayments"}],staticClass:"block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",attrs:{type:"text",id:"totalPayments",disabled:""},domProps:{value:t.totalPayments},on:{input:function(e){e.target.composing||(t.totalPayments=e.target.value)}}})])]),t._v(" "),a("div",{staticClass:"align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg mt-8"},[a("table",{staticClass:"bg-white min-w-full divide-y divide-gray-200 "},[a("thead",[a("tr",{staticClass:"bg-gray-200 text-gray-600 text-sm leading-normal"},[a("th",{staticClass:"py-2 px-3 text-right",attrs:{colspan:"3"}},[t._v("الدفعات")]),t._v(" "),a("th",{staticClass:"py-2 px-3 text-left"},[a("a",{staticClass:"ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-gray-100 hover:text-gray-50 bg-gray-800 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-gray-600 focus:outline-none",attrs:{href:"#"},on:{click:function(e){t.isFormManipulating=!0,t.isEdit=!1}}},[t._v("\n                        إضافة\n                    ")])])]),t._v(" "),t._m(0)]),t._v(" "),a("tbody",{staticClass:"divide-y divide-gray-200"},[t.isFormManipulating?a("tr",[a("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.notes,expression:"form.notes"}],staticClass:"block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",attrs:{type:"text",disabled:t.submitted},domProps:{value:t.form.notes},on:{input:function(e){e.target.composing||t.$set(t.form,"notes",e.target.value)}}})]),t._v(" "),a("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.amount,expression:"form.amount"}],staticClass:"block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",attrs:{type:"number",disabled:t.submitted},domProps:{value:t.form.amount},on:{input:function(e){e.target.composing||t.$set(t.form,"amount",e.target.value)}}})]),t._v(" "),a("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500"},[a("input",{directives:[{name:"model",rawName:"v-model",value:t.form.date,expression:"form.date"}],staticClass:"block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",attrs:{type:"date",disabled:t.submitted},domProps:{value:t.form.date},on:{input:function(e){e.target.composing||t.$set(t.form,"date",e.target.value)}}})]),t._v(" "),a("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500"},[a("async-button",{staticClass:"ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-green-500 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-green-600 focus:outline-none",attrs:{loading:t.submitted},on:{click:function(e){return t.addPayment()}}},[t._v("\n                        حفظ\n                    ")]),t._v(" "),a("a",{staticClass:"ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-red-600 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-red-700 focus:outline-none",attrs:{href:"#"},on:{click:function(e){t.resetForm(),t.isFormManipulating=!1}}},[t._v("\n                        إلغاء\n                    ")])],1)]):t._e(),t._v(" "),t._l(t.data,(function(e){return a("tr",[a("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500"},[e.isEdit?a("input",{directives:[{name:"model",rawName:"v-model",value:e.visit.notes,expression:"payment.visit.notes"}],staticClass:"block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",attrs:{type:"text",disabled:t.submitted},domProps:{value:e.visit.notes},on:{input:function(a){a.target.composing||t.$set(e.visit,"notes",a.target.value)}}}):a("span",[t._v("\n                        "+t._s(e.visit.notes?e.visit.notes:"-")+"\n                    ")])]),t._v(" "),a("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700"},[e.isEdit?a("input",{directives:[{name:"model",rawName:"v-model",value:e.amount,expression:"payment.amount"}],staticClass:"block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",attrs:{type:"number",disabled:t.submitted},domProps:{value:e.amount},on:{input:function(a){a.target.composing||t.$set(e,"amount",a.target.value)}}}):a("span",[a("b",{staticClass:"font-medium"},[t._v(t._s(t._f("numberFormat")(+e.amount)))])])]),t._v(" "),a("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500"},[e.isEdit?a("input",{directives:[{name:"model",rawName:"v-model",value:e.date,expression:"payment.date"}],staticClass:"block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",attrs:{type:"date",disabled:t.submitted},domProps:{value:e.date},on:{input:function(a){a.target.composing||t.$set(e,"date",a.target.value)}}}):a("span",[t._v("\n                        "+t._s(e.date)+"\n                    ")])]),t._v(" "),a("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500"},[e.isEdit?t._e():a("a",{staticClass:"py-1 inline-flex h-12 px-2 text-sm text-center text-green-600 transition-colors duration-200 transform lg:h-8 hover:text-green-700 focus:outline-none",attrs:{href:"#"},on:{click:function(a){return t.editPayment(e)}}},[a("icon-edit",{staticClass:"transition-colors",attrs:{size:"5"}})],1),t._v(" "),e.isEdit?t._e():a("a",{staticClass:"py-1 inline-flex h-12 px-2 text-sm text-center text-red-600 transition-colors duration-200 transform lg:h-8 hover:text-red-700 focus:outline-none",attrs:{href:"#"},on:{click:function(a){return t.deletePayment(e.id)}}},[a("icon-delete",{staticClass:"transition-colors",attrs:{size:"5"}})],1),t._v(" "),e.isEdit?a("async-button",{staticClass:"ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-green-500 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-green-600 focus:outline-none",attrs:{loading:t.submitted},on:{click:function(a){return t.savePayment(e)}}},[t._v("\n                        تعديل\n                    ")]):t._e(),t._v(" "),e.isEdit?a("a",{staticClass:"ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-red-600 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-red-700 focus:outline-none",attrs:{href:"#"},on:{click:function(t){e.isEdit=!1}}},[a("span",[t._v("إلغاء")])]):t._e()],1)])}))],2)])])])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("tr",{staticClass:"bg-gray-50 text-gray-600 text-sm leading-normal"},[e("th",{staticClass:"py-2 px-3 text-right"},[this._v("الإجراء الذي تم")]),this._v(" "),e("th",{staticClass:"py-2 px-3 text-right"},[this._v("المبلغ")]),this._v(" "),e("th",{staticClass:"py-2 px-3 text-right"},[this._v("التاريخ")]),this._v(" "),e("th",{staticClass:"py-2 px-3 text-right"})])}],!1,null,null,null);e.default=u.exports}}]);