(window.webpackJsonp=window.webpackJsonp||[]).push([[16],{CPal:function(t,e,s){"use strict";s.r(e);var r={mounted:function(){},computed:{isPatientVisits:function(){return"patients-visits"===this.$route.name}}},a=s("KHd+"),i=Object(a.a)(r,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return t.isPatientVisits?s("div",{staticClass:"w-full"},[s("router-view")],1):s("div",{staticClass:"w-full"},[s("search",{scopedSlots:t._u([{key:"filters",fn:function(t){t.filters,t.loadEntries}},{key:"row",fn:function(e){var r=e.entry;return[s("td",{staticClass:"px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500"},[t._v("\n                "+t._s(r.name)+"\n            ")]),t._v(" "),s("td",{staticClass:"px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500"},[t._v("\n                "+t._s(r.file_number)+"\n            ")]),t._v(" "),s("td",{staticClass:"px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500"},[t._v("\n                "+t._s(r.mobile)+"\n            ")]),t._v(" "),s("td",{staticClass:"px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500"},[t._v("\n                "+t._s(r.created_at)+"\n            ")]),t._v(" "),s("td",{staticClass:"px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500"},[s("div",{staticClass:"flex item-center"},[s("router-link",{staticClass:"w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150",attrs:{to:{name:"patients-files-show",params:{id:r.id},query:r.filters},tag:"a"}},[s("icon-eye",{staticClass:" text-gray-400 hover:text-blue-500 transition-colors",attrs:{size:"6"}})],1),t._v(" "),s("router-link",{staticClass:"w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150",attrs:{to:{name:"patients-visits",params:{id:r.id}},tag:"a"}},[s("icon-list",{staticClass:" text-gray-400 hover:text-indigo-500 transition-colors",attrs:{size:"5"}})],1),t._v(" "),s("router-link",{staticClass:"w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150",attrs:{to:{name:"patients-files",params:{id:r.id}},tag:"button",href:"#"}},[s("icon-file",{staticClass:" text-gray-400 hover:text-indigo-500 transition-colors",attrs:{size:"5"}})],1),t._v(" "),s("router-link",{staticClass:"w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150",attrs:{to:{name:"patients-edit",params:{id:r.id},query:r.filters},tag:"button",href:"#"}},[s("icon-edit",{staticClass:" text-gray-400 hover:text-blue-500 transition-colors",attrs:{size:"5"}})],1),t._v(" "),s("div",{staticClass:"w-4 mr-2 transform hover:text-purple-500"},[s("router-link",{staticClass:"w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent transition-colors hover:text-red-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150",attrs:{to:{name:"patients-delete",params:{id:r.id},query:{type:"مريض"}},tag:"button",href:"#"}},[s("icon-delete",{staticClass:" text-gray-400 hover:text-red-500 transition-colors",attrs:{size:"5"}})],1)],1)],1)])]}}],null,!1,878659964)},[t._v(" "),s("template",{slot:"troubleshooting"},[s("p",[t._v("It looks like there was an error. Please check your application logs.")]),t._v(" "),s("p",{staticClass:"mt-2"},[t._v('\n                Consider searching using a more recent "Starting from" date. The CloudWatch API may have long\n                response\n                times while searching far into the past. These requests may timeout or lead to unexpected errors.\n            ')])]),t._v(" "),s("template",{slot:"create-btn"},[s("router-link",{staticClass:"ml-4 flex items-center justify-center h-12 px-4 text-sm text-center text-gray-100 hover:text-gray-50 bg-gray-800 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-gray-600 focus:outline-none",attrs:{to:{name:"patients-create"}}},[t._v("\n                إضافة\n            ")])],1),t._v(" "),s("template",{slot:"head"},[s("tr",{staticClass:"bg-gray-200 text-gray-600 text-sm leading-normal"},[s("th",{staticClass:"py-2 px-3 text-right"},[t._v("الاسم")]),t._v(" "),s("th",{staticClass:"py-2 px-3 text-right"},[t._v("رقم الملف")]),t._v(" "),s("th",{staticClass:"py-2 px-3 text-right"},[t._v("رقم الموبايل")]),t._v(" "),s("th",{staticClass:"py-2 px-3 text-right"},[t._v("التاريخ")]),t._v(" "),s("th",{staticClass:"py-2 px-3 text-right"})])])],2),t._v(" "),s("router-view")],1)}),[],!1,null,null,null);e.default=i.exports}}]);