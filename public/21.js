(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[21],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/statistics/index.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/statistics/index.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! moment */ "./node_modules/moment/moment.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_1__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


var year = new Date().getFullYear();
/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      loading: false,
      year: year,
      month: '',
      patientsTotalCount: 0,
      expensesTotal: 0,
      totalPatients: 0,
      incomeTotal: 0,
      expensesSum: 0,
      incomesSum: 0,
      expenses: [],
      patients: [],
      visits: [],
      incomes: [],
      years: [],
      months: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
    };
  },
  mounted: function mounted() {
    this.years = this.range(2018, moment__WEBPACK_IMPORTED_MODULE_1___default()().year());
    this.getData();
  },
  methods: {
    range: function range(start, stop) {
      var step = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 1;
      return Array.from({
        length: (stop - start) / step + 1
      }, function (_, i) {
        return start + i * step;
      });
    },
    getData: function getData() {
      var _this = this;

      this.loading = true;
      var query = [];
      var queryParams = '';

      if (this.year) {
        query.push('year=' + this.year);
      }

      if (this.month) {
        query.push('month=' + this.month);
      }

      queryParams = query.join('&');
      axios__WEBPACK_IMPORTED_MODULE_0___default.a.get('/api/statistics?' + queryParams).then(function (_ref) {
        var data = _ref.data;
        _this.expenses = data.expenses;
        _this.visits = data.visits;
        _this.incomes = data.incomes;
        _this.patients = data.patients;
        _this.totalPatients = _this.patients.reduce(function (sum, item) {
          return sum + +item.value;
        }, 0);
        _this.expensesSum = _this.expenses.reduce(function (sum, item) {
          return sum + +item.value;
        }, 0);
        _this.incomesSum = _this.incomes.reduce(function (sum, item) {
          return sum + +item.value;
        }, 0);
        _this.patientsTotalCount = data.patientsTotalCount;
        _this.expensesTotal = data.expensesTotal;
        _this.incomeTotal = data.incomeTotal;
      })["finally"](function () {
        _this.loading = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/statistics/index.vue?vue&type=template&id=d848d828&":
/*!****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/statistics/index.vue?vue&type=template&id=d848d828& ***!
  \****************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function () {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "w-full grid grid-cols-1 md:grid-cols-2 gap-8 px-16 py-8" },
    [
      _c("div", { staticClass: "col-span-full flex gap-5 mb-4" }, [
        _c("div", { staticClass: "w-1/5" }, [
          _c(
            "select",
            {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.year,
                  expression: "year",
                },
              ],
              staticClass:
                "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
              on: {
                change: function ($event) {
                  var $$selectedVal = Array.prototype.filter
                    .call($event.target.options, function (o) {
                      return o.selected
                    })
                    .map(function (o) {
                      var val = "_value" in o ? o._value : o.value
                      return val
                    })
                  _vm.year = $event.target.multiple
                    ? $$selectedVal
                    : $$selectedVal[0]
                },
              },
            },
            [
              _vm._v(">\n                "),
              _c("option", { attrs: { value: "" } }, [_vm._v("اختر السنة")]),
              _vm._v(" "),
              _vm._l(_vm.years, function (y) {
                return _c("option", { domProps: { value: y } }, [
                  _vm._v(_vm._s(y)),
                ])
              }),
            ],
            2
          ),
        ]),
        _vm._v(" "),
        _c("div", { staticClass: "w-1/5" }, [
          _c(
            "select",
            {
              directives: [
                {
                  name: "model",
                  rawName: "v-model",
                  value: _vm.month,
                  expression: "month",
                },
              ],
              staticClass:
                "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
              on: {
                change: function ($event) {
                  var $$selectedVal = Array.prototype.filter
                    .call($event.target.options, function (o) {
                      return o.selected
                    })
                    .map(function (o) {
                      var val = "_value" in o ? o._value : o.value
                      return val
                    })
                  _vm.month = $event.target.multiple
                    ? $$selectedVal
                    : $$selectedVal[0]
                },
              },
            },
            [
              _vm._v(">\n                "),
              _c("option", { attrs: { value: "" } }, [_vm._v("اختر الشهر")]),
              _vm._v(" "),
              _vm._l(_vm.months, function (m) {
                return _c("option", { domProps: { value: m } }, [
                  _vm._v(_vm._s(m)),
                ])
              }),
            ],
            2
          ),
        ]),
        _vm._v(" "),
        _c(
          "div",
          { staticClass: "w-1/5" },
          [
            _c(
              "async-button",
              {
                staticClass:
                  "w-full inline-flex justify-center rounded-md border border-transparent transition duration-75 transition-all shadow-sm px-4 py-2 bg-teal-600 text-base font-medium text-white hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 sm:ml-3 sm:w-auto sm:text-sm",
                attrs: { type: "button", loading: _vm.loading },
                on: {
                  click: function ($event) {
                    return _vm.getData()
                  },
                },
              },
              [_vm._v("\n                بحث\n            ")]
            ),
          ],
          1
        ),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-span-full" }, [
        _c("div", { staticClass: "flex flex-wrap" }, [
          _c("div", { staticClass: "w-full lg:w-6/12 xl:w-3/12 px-4" }, [
            _c(
              "div",
              {
                staticClass:
                  "relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg",
              },
              [
                _c("div", { staticClass: "flex-auto p-4" }, [
                  _c("div", { staticClass: "flex flex-wrap" }, [
                    _c(
                      "div",
                      {
                        staticClass:
                          "relative w-full pr-4 max-w-full flex-grow flex-1",
                      },
                      [
                        _c(
                          "h5",
                          {
                            staticClass:
                              "text-blueGray-400 uppercase font-bold text-xs",
                          },
                          [_vm._v("العدد الإجمالي للمرضى")]
                        ),
                        _vm._v(" "),
                        _c(
                          "span",
                          {
                            staticClass:
                              "font-semibold text-xl text-blueGray-700 mt-3",
                          },
                          [_vm._v(_vm._s(_vm.patientsTotalCount))]
                        ),
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "relative w-auto pl-4 flex-initial" },
                      [
                        _c(
                          "div",
                          {
                            staticClass:
                              "text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-red-500",
                          },
                          [
                            _c("icon-users", {
                              staticClass: "text-white",
                              attrs: { size: "6" },
                            }),
                          ],
                          1
                        ),
                      ]
                    ),
                  ]),
                ]),
              ]
            ),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "w-full lg:w-6/12 xl:w-3/12 px-4" }, [
            _c(
              "div",
              {
                staticClass:
                  "relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg",
              },
              [
                _c("div", { staticClass: "flex-auto p-4" }, [
                  _c("div", { staticClass: "flex flex-wrap" }, [
                    _c(
                      "div",
                      {
                        staticClass:
                          "relative w-full pr-4 max-w-full flex-grow flex-1",
                      },
                      [
                        _c(
                          "h5",
                          {
                            staticClass:
                              "text-blueGray-400 uppercase font-bold text-xs",
                          },
                          [_vm._v("إجمالي الدخل")]
                        ),
                        _vm._v(" "),
                        _c(
                          "span",
                          {
                            staticClass:
                              "font-semibold text-xl text-green-600 mt-3",
                          },
                          [
                            _vm._v(
                              _vm._s(_vm._f("numberFormat")(+_vm.incomeTotal))
                            ),
                          ]
                        ),
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "relative w-auto pl-4 flex-initial" },
                      [
                        _c(
                          "div",
                          {
                            staticClass:
                              "text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-orange-500",
                          },
                          [
                            _c("icon-money", {
                              staticClass: "text-white",
                              attrs: { size: "6" },
                            }),
                          ],
                          1
                        ),
                      ]
                    ),
                  ]),
                ]),
              ]
            ),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "w-full lg:w-6/12 xl:w-3/12 px-4" }, [
            _c(
              "div",
              {
                staticClass:
                  "relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg",
              },
              [
                _c("div", { staticClass: "flex-auto p-4" }, [
                  _c("div", { staticClass: "flex flex-wrap" }, [
                    _c(
                      "div",
                      {
                        staticClass:
                          "relative w-full pr-4 max-w-full flex-grow flex-1",
                      },
                      [
                        _c(
                          "h5",
                          {
                            staticClass:
                              "text-blueGray-400 uppercase font-bold text-xs",
                          },
                          [_vm._v("إجمالي النفقات")]
                        ),
                        _vm._v(" "),
                        _c(
                          "span",
                          {
                            staticClass:
                              "font-semibold text-xl text-red-600 mt-3",
                          },
                          [
                            _vm._v(
                              "-" +
                                _vm._s(
                                  _vm._f("numberFormat")(+_vm.expensesTotal)
                                )
                            ),
                          ]
                        ),
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "relative w-auto pl-4 flex-initial" },
                      [
                        _c(
                          "div",
                          {
                            staticClass:
                              "text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-pink-500",
                          },
                          [
                            _c("icon-expenses", {
                              staticClass: "text-white",
                              attrs: { size: "6" },
                            }),
                          ],
                          1
                        ),
                      ]
                    ),
                  ]),
                ]),
              ]
            ),
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "w-full lg:w-6/12 xl:w-3/12 px-4" }, [
            _c(
              "div",
              {
                staticClass:
                  "relative flex flex-col min-w-0 break-words bg-white rounded mb-6 xl:mb-0 shadow-lg",
              },
              [
                _c("div", { staticClass: "flex-auto p-4" }, [
                  _c("div", { staticClass: "flex flex-wrap" }, [
                    _c(
                      "div",
                      {
                        staticClass:
                          "relative w-full pr-4 max-w-full flex-grow flex-1",
                      },
                      [
                        _c(
                          "h5",
                          {
                            staticClass:
                              "text-blueGray-400 uppercase font-bold text-xs",
                          },
                          [_vm._v("صافي الربح الإجمالي")]
                        ),
                        _vm._v(" "),
                        _c(
                          "span",
                          {
                            staticClass: "font-semibold text-xl mt-3",
                            class: {
                              "text-green-600":
                                Math.sign(
                                  _vm.incomeTotal - _vm.expensesTotal
                                ) === 1,
                              "text-red-600":
                                Math.sign(
                                  _vm.incomeTotal - _vm.expensesTotal
                                ) === -1,
                            },
                          },
                          [
                            _vm._v(
                              _vm._s(
                                _vm._f("numberFormat")(
                                  _vm.incomeTotal - _vm.expensesTotal
                                )
                              )
                            ),
                          ]
                        ),
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "relative w-auto pl-4 flex-initial" },
                      [
                        _c(
                          "div",
                          {
                            staticClass:
                              "text-white p-3 text-center inline-flex items-center justify-center w-12 h-12 shadow-lg rounded-full bg-teal-500",
                          },
                          [
                            _c("icon-money", {
                              staticClass: "text-white",
                              attrs: { size: "6" },
                            }),
                          ],
                          1
                        ),
                      ]
                    ),
                  ]),
                ]),
              ]
            ),
          ]),
        ]),
      ]),
      _vm._v(" "),
      _c("div", { staticClass: "col-span-full flex gap-5 mb-4" }, [
        _c(
          "div",
          {
            staticClass:
              "align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg",
          },
          [
            _c(
              "table",
              { staticClass: "bg-white min-w-full divide-y divide-gray-200 " },
              [
                _vm._m(0),
                _vm._v(" "),
                _c("tbody", { staticClass: "divide-y divide-gray-200" }, [
                  _c("tr", [
                    _vm._m(1),
                    _vm._v(" "),
                    _c(
                      "td",
                      {
                        staticClass:
                          "px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500",
                      },
                      [
                        _vm._v(
                          "\n                        " +
                            _vm._s(_vm.totalPatients) +
                            "\n                    "
                        ),
                      ]
                    ),
                  ]),
                  _vm._v(" "),
                  _c("tr", [
                    _vm._m(2),
                    _vm._v(" "),
                    _c(
                      "td",
                      {
                        staticClass:
                          "px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500",
                      },
                      [
                        _vm._v(
                          "\n                        " +
                            _vm._s(_vm._f("numberFormat")(_vm.expensesSum)) +
                            "\n                    "
                        ),
                      ]
                    ),
                  ]),
                  _vm._v(" "),
                  _c("tr", [
                    _vm._m(3),
                    _vm._v(" "),
                    _c(
                      "td",
                      {
                        staticClass:
                          "px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500",
                      },
                      [
                        _vm._v(
                          "\n                        " +
                            _vm._s(_vm._f("numberFormat")(_vm.incomesSum)) +
                            "\n                    "
                        ),
                      ]
                    ),
                  ]),
                  _vm._v(" "),
                  _c("tr", [
                    _vm._m(4),
                    _vm._v(" "),
                    _c(
                      "td",
                      {
                        staticClass:
                          "px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-500",
                      },
                      [
                        _c(
                          "span",
                          {
                            class: {
                              "text-green-500":
                                Math.sign(_vm.incomesSum - _vm.expensesSum) ===
                                1,
                              "text-red-500":
                                Math.sign(_vm.incomesSum - _vm.expensesSum) ===
                                -1,
                            },
                          },
                          [
                            _vm._v(
                              "\n                            " +
                                _vm._s(
                                  _vm._f("numberFormat")(
                                    _vm.incomesSum - _vm.expensesSum
                                  )
                                )
                            ),
                          ]
                        ),
                      ]
                    ),
                  ]),
                ]),
              ]
            ),
          ]
        ),
      ]),
      _vm._v(" "),
      !_vm.loading && _vm.patients.length
        ? _c("div", { staticClass: "card" }, [
            _c("h1", { staticClass: "text-lg font-semibold card-title" }, [
              _vm._v("المرضى"),
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "card-body" },
              [
                _vm.patients.length
                  ? _c("pie-chart", {
                      attrs: {
                        label: "مرضى",
                        formatTooltipTitle: ["المرضى"],
                        data: _vm.patients,
                      },
                    })
                  : _vm._e(),
              ],
              1
            ),
          ])
        : _vm._e(),
      _vm._v(" "),
      !_vm.loading && _vm.visits.length
        ? _c("div", { staticClass: "card" }, [
            _c("h1", { staticClass: "text-lg font-semibold card-title" }, [
              _vm._v("الزيارات"),
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "card-body" },
              [
                _vm.visits.length
                  ? _c("pie-chart", {
                      attrs: {
                        label: "زيارات",
                        formatTooltipTitle: ["الزيارات"],
                        data: _vm.visits,
                      },
                    })
                  : _vm._e(),
              ],
              1
            ),
          ])
        : _vm._e(),
      _vm._v(" "),
      !_vm.loading && _vm.expenses.length
        ? _c("div", { staticClass: "card col-span-full" }, [
            _c("h1", { staticClass: "text-lg font-semibold card-title" }, [
              _vm._v("النفقات"),
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "card-body" },
              [
                _vm.expenses.length
                  ? _c("bar-chart", {
                      attrs: {
                        label: "نفقات",
                        color: "red",
                        formatTooltipTitle: ["النفقات"],
                        data: _vm.expenses,
                      },
                    })
                  : _vm._e(),
              ],
              1
            ),
          ])
        : _vm._e(),
      _vm._v(" "),
      !_vm.loading && _vm.incomes.length
        ? _c("div", { staticClass: "card col-span-full" }, [
            _c("h1", { staticClass: "text-lg font-semibold card-title" }, [
              _vm._v("الواردات"),
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "card-body" },
              [
                _vm.incomes.length
                  ? _c("bar-chart", {
                      attrs: {
                        label: "واردات",
                        color: "green",
                        formatTooltipTitle: ["الواردات"],
                        data: _vm.incomes,
                      },
                    })
                  : _vm._e(),
              ],
              1
            ),
          ])
        : _vm._e(),
    ]
  )
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c(
        "tr",
        { staticClass: "bg-gray-50 text-gray-600 text-sm leading-normal" },
        [
          _c(
            "th",
            { staticClass: "py-2 px-3 text-right", attrs: { colspan: "2" } },
            [_vm._v("هذه الإحصائيات تبعا للقيم المختارة أعلاه")]
          ),
        ]
      ),
    ])
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "td",
      {
        staticClass:
          "px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700",
      },
      [_c("b", { staticClass: "font-medium" }, [_vm._v("عدد المرضى")])]
    )
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "td",
      {
        staticClass:
          "px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700",
      },
      [_c("b", { staticClass: "font-medium" }, [_vm._v("النفقات")])]
    )
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "td",
      {
        staticClass:
          "px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700",
      },
      [_c("b", { staticClass: "font-medium" }, [_vm._v("الواردات")])]
    )
  },
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "td",
      {
        staticClass:
          "px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-gray-700",
      },
      [_c("b", { staticClass: "font-medium" }, [_vm._v("صافي الأرباح")])]
    )
  },
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/screens/statistics/index.vue":
/*!***************************************************!*\
  !*** ./resources/js/screens/statistics/index.vue ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_vue_vue_type_template_id_d848d828___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.vue?vue&type=template&id=d848d828& */ "./resources/js/screens/statistics/index.vue?vue&type=template&id=d848d828&");
/* harmony import */ var _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.vue?vue&type=script&lang=js& */ "./resources/js/screens/statistics/index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _index_vue_vue_type_template_id_d848d828___WEBPACK_IMPORTED_MODULE_0__["render"],
  _index_vue_vue_type_template_id_d848d828___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/screens/statistics/index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/screens/statistics/index.vue?vue&type=script&lang=js&":
/*!****************************************************************************!*\
  !*** ./resources/js/screens/statistics/index.vue?vue&type=script&lang=js& ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/statistics/index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/screens/statistics/index.vue?vue&type=template&id=d848d828&":
/*!**********************************************************************************!*\
  !*** ./resources/js/screens/statistics/index.vue?vue&type=template&id=d848d828& ***!
  \**********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_d848d828___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./index.vue?vue&type=template&id=d848d828& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/statistics/index.vue?vue&type=template&id=d848d828&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_d848d828___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_d848d828___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);