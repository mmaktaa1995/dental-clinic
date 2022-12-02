(window.webpackJsonp=window.webpackJsonp||[]).push([[22],{TpGE:function(t,e,s){"use strict";s.r(e);var a=s("vDqi"),i=s.n(a),l=s("wd/R"),n=s.n(l),r=(new Date).getFullYear(),o={data:function(){return{loading:!1,year:r,month:"",day:"",patientsTotalCount:0,expensesTotal:0,totalPatients:0,incomeTotal:0,expensesSum:0,incomesSum:0,expenses:[],patients:[],visits:[],incomes:[],days:[],years:[],months:[1,2,3,4,5,6,7,8,9,10,11,12]}},mounted:function(){this.years=this.range(2018,n()().year()),this.getData()},methods:{monthChanged:function(){var t=this.year?this.year:"2022";this.days=this.range(1,n()("".concat(t,"-").concat(this.month)).daysInMonth())},range:function(t,e){var s=arguments.length>2&&void 0!==arguments[2]?arguments[2]:1;return Array.from({length:(e-t)/s+1},(function(e,a){return t+a*s}))},getData:function(){var t=this;this.loading=!0;var e,s=[];this.year&&s.push("year="+this.year),this.month&&s.push("month="+this.month),this.day&&this.month&&s.push("day="+this.day),e=s.join("&"),i.a.get("/api/statistics?"+e).then((function(e){var s=e.data;t.expenses=s.expenses,t.visits=s.visits,t.incomes=s.incomes,t.patients=s.patients,t.totalPatients=t.patients.reduce((function(t,e){return t+ +e.value}),0),t.expensesSum=t.expenses.reduce((function(t,e){return t+ +e.value}),0),t.incomesSum=t.incomes.reduce((function(t,e){return t+ +e.value}),0),t.patientsTotalCount=s.patientsTotalCount,t.expensesTotal=s.expensesTotal,t.incomeTotal=s.incomeTotal})).finally((function(){t.loading=!1}))}}},d=s("KHd+"),c=Object(d.a)(o,(function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"w-full grid grid-cols-1 md:grid-cols-2 gap-8 px-16 py-8"},[s("div",{staticClass:"col-span-full flex gap-5 mb-4"},[s("div",{staticClass:"w-1/5"},[s("select",{directives:[{name:"model",rawName:"v-model",value:t.year,expression:"year"}],staticClass:"block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-full px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",on:{change:function(e){var s=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.year=e.target.multiple?s:s[0]}}},[t._v(">\n                "),s("option",{attrs:{value:""}},[t._v("اختر السنة")]),t._v(" "),t._l(t.years,(function(e){return s("option",{domProps:{value:e}},[t._v(t._s(e))])}))],2)]),t._v(" "),s("div",{staticClass:"w-1/5"},[s("select",{directives:[{name:"model",rawName:"v-model",value:t.month,expression:"month"}],staticClass:"block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-full px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",on:{change:[function(e){var s=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.month=e.target.multiple?s:s[0]},function(e){return t.monthChanged()}]}},[t._v(">\n                "),s("option",{attrs:{value:""}},[t._v("اختر الشهر")]),t._v(" "),t._l(t.months,(function(e){return s("option",{domProps:{value:e}},[t._v(t._s(e))])}))],2)]),t._v(" "),t.month?s("div",{staticClass:"w-1/5"},[s("select",{directives:[{name:"model",rawName:"v-model",value:t.day,expression:"day"}],staticClass:"block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-full px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",on:{change:function(e){var s=Array.prototype.filter.call(e.target.options,(function(t){return t.selected})).map((function(t){return"_value"in t?t._value:t.value}));t.day=e.target.multiple?s:s[0]}}},[t._v(">\n                "),s("option",{attrs:{value:""}},[t._v("اختر اليوم")]),t._v(" "),t._l(t.days,(function(e){return s("option",{domProps:{value:e}},[t._v(t._s(e))])}))],2)]):t._e(),t._v(" "),s("div",{staticClass:"w-1/5"},[s("async-button",{staticClass:"w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm",attrs:{type:"button",loading:t.loading},on:{click:function(e){return t.getData()}}},[t._v("\n                بحث\n            ")])],1)]),t._v(" "),s("div",{staticClass:"col-span-full"},[s("div",{staticClass:"flex flex-wrap"},[s("div",{staticClass:"w-full lg:w-6/12 xl:w-3/12 pl-4"},[s("div",{staticClass:"relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg"},[s("div",{staticClass:"flex-auto p-4"},[s("div",{staticClass:"flex flex-wrap"},[s("div",{staticClass:"relative w-full pr-4 max-w-full flex-grow flex-1"},[s("h5",{staticClass:"text-blueGray-400 uppercase font-bold text-xs"},[t._v("العدد الإجمالي للمرضى")]),t._v(" "),s("span",{staticClass:"font-semibold text-xl text-blueGray-700 mt-3"},[t._v(t._s(t.patientsTotalCount))])]),t._v(" "),s("div",{staticClass:"relative w-auto pl-4 flex-initial"},[s("div",{staticClass:"text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500"},[s("icon-users",{staticClass:"text-white",attrs:{size:"6"}})],1)])])])])]),t._v(" "),s("div",{staticClass:"w-full lg:w-6/12 xl:w-3/12 px-4"},[s("div",{staticClass:"relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg"},[s("div",{staticClass:"flex-auto p-4"},[s("div",{staticClass:"flex flex-wrap"},[s("div",{staticClass:"relative w-full pr-4 max-w-full flex-grow flex-1"},[s("h5",{staticClass:"text-blueGray-400 uppercase font-bold text-xs"},[t._v("إجمالي الدخل")]),t._v(" "),s("span",{staticClass:"font-semibold text-xl text-green-600 mt-3"},[t._v(t._s(t._f("numberFormat")(+t.incomeTotal)))])]),t._v(" "),s("div",{staticClass:"relative w-auto pl-4 flex-initial"},[s("div",{staticClass:"text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-orange-500"},[s("icon-money",{staticClass:"text-white",attrs:{size:"6"}})],1)])])])])]),t._v(" "),s("div",{staticClass:"w-full lg:w-6/12 xl:w-3/12 px-4"},[s("div",{staticClass:"relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg"},[s("div",{staticClass:"flex-auto p-4"},[s("div",{staticClass:"flex flex-wrap"},[s("div",{staticClass:"relative w-full pr-4 max-w-full flex-grow flex-1"},[s("h5",{staticClass:"text-blueGray-400 uppercase font-bold text-xs"},[t._v("إجمالي النفقات")]),t._v(" "),s("span",{staticClass:"font-semibold text-xl text-red-600 mt-3"},[t._v("-"+t._s(t._f("numberFormat")(+t.expensesTotal)))])]),t._v(" "),s("div",{staticClass:"relative w-auto pl-4 flex-initial"},[s("div",{staticClass:"text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-pink-500"},[s("icon-expenses",{staticClass:"text-white",attrs:{size:"6"}})],1)])])])])]),t._v(" "),s("div",{staticClass:"w-full lg:w-6/12 xl:w-3/12 pr-4"},[s("div",{staticClass:"relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg"},[s("div",{staticClass:"flex-auto p-4"},[s("div",{staticClass:"flex flex-wrap"},[s("div",{staticClass:"relative w-full pr-4 max-w-full flex-grow flex-1"},[s("h5",{staticClass:"text-blueGray-400 uppercase font-bold text-xs"},[t._v("صافي الربح الإجمالي")]),t._v(" "),s("span",{staticClass:"font-semibold text-xl mt-3",class:{"text-green-600":1===Math.sign(t.incomeTotal-t.expensesTotal),"text-red-600":-1===Math.sign(t.incomeTotal-t.expensesTotal)}},[t._v(t._s(t._f("numberFormat")(t.incomeTotal-t.expensesTotal)))])]),t._v(" "),s("div",{staticClass:"relative w-auto pl-4 flex-initial"},[s("div",{staticClass:"text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-teal-500"},[s("icon-money",{staticClass:"text-white",attrs:{size:"6"}})],1)])])])])])])]),t._v(" "),s("div",{staticClass:"col-span-full flex gap-5 mb-4"},[s("div",{staticClass:"align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg"},[s("table",{staticClass:"bg-white min-w-full divide-y divide-gray-200 "},[t._m(0),t._v(" "),s("tbody",{staticClass:"divide-y divide-gray-200"},[s("tr",[t._m(1),t._v(" "),s("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500"},[t._v("\n                        "+t._s(t.totalPatients)+"\n                    ")])]),t._v(" "),s("tr",[t._m(2),t._v(" "),s("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500"},[t._v("\n                        "+t._s(t._f("numberFormat")(t.expensesSum))+"\n                    ")])]),t._v(" "),s("tr",[t._m(3),t._v(" "),s("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500"},[t._v("\n                        "+t._s(t._f("numberFormat")(t.incomesSum))+"\n                    ")])]),t._v(" "),s("tr",[t._m(4),t._v(" "),s("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500"},[s("span",{class:{"text-green-500":1===Math.sign(t.incomesSum-t.expensesSum),"text-red-500":-1===Math.sign(t.incomesSum-t.expensesSum)}},[t._v("\n                            "+t._s(t._f("numberFormat")(t.incomesSum-t.expensesSum)))])])])])])])]),t._v(" "),!t.loading&&t.patients.length?s("div",{staticClass:"card"},[s("h1",{staticClass:"text-lg font-semibold card-title"},[t._v("المرضى")]),t._v(" "),s("div",{staticClass:"card-body"},[t.patients.length?s("pie-chart",{attrs:{label:"مريض",formatTooltipTitle:["المرضى"],data:t.patients}}):t._e()],1)]):t._e(),t._v(" "),!t.loading&&t.visits.length?s("div",{staticClass:"card"},[s("h1",{staticClass:"text-lg font-semibold card-title"},[t._v("الزيارات")]),t._v(" "),s("div",{staticClass:"card-body"},[t.visits.length?s("pie-chart",{attrs:{label:"زيارة",formatTooltipTitle:["الزيارات"],data:t.visits}}):t._e()],1)]):t._e(),t._v(" "),!t.loading&&t.expenses.length?s("div",{staticClass:"card col-span-full"},[s("h1",{staticClass:"text-lg font-semibold card-title"},[t._v("النفقات")]),t._v(" "),s("div",{staticClass:"card-body"},[t.expenses.length?s("bar-chart",{attrs:{label:"نفقات",color:"red",formatTooltipTitle:["النفقات"],data:t.expenses}}):t._e()],1)]):t._e(),t._v(" "),!t.loading&&t.incomes.length?s("div",{staticClass:"card col-span-full"},[s("h1",{staticClass:"text-lg font-semibold card-title"},[t._v("الواردات")]),t._v(" "),s("div",{staticClass:"card-body"},[t.incomes.length?s("bar-chart",{attrs:{label:"واردات",color:"green",formatTooltipTitle:["الواردات"],data:t.incomes}}):t._e()],1)]):t._e()])}),[function(){var t=this.$createElement,e=this._self._c||t;return e("thead",[e("tr",{staticClass:"bg-gray-50 text-gray-600 text-sm leading-normal"},[e("th",{staticClass:"py-2 px-3 text-right",attrs:{colspan:"2"}},[this._v("هذه الإحصائيات تبعا للقيم المختارة أعلاه")])])])},function(){var t=this.$createElement,e=this._self._c||t;return e("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700"},[e("b",{staticClass:"font-medium"},[this._v("عدد المرضى")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700"},[e("b",{staticClass:"font-medium"},[this._v("النفقات")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700"},[e("b",{staticClass:"font-medium"},[this._v("الواردات")])])},function(){var t=this.$createElement,e=this._self._c||t;return e("td",{staticClass:"px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700"},[e("b",{staticClass:"font-medium"},[this._v("صافي الأرباح")])])}],!1,null,null,null);e.default=c.exports}}]);