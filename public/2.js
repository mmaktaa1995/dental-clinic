(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[2],{

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
/* harmony import */ var _mixins_interactsWithMetrics__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./../../mixins/interactsWithMetrics */ "./resources/js/mixins/interactsWithMetrics.js");
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
  mixins: [_mixins_interactsWithMetrics__WEBPACK_IMPORTED_MODULE_2__["default"]],
  data: function data() {
    return {
      loading: false,
      passwordCorrect: false,
      year: year,
      month: '',
      password: '',
      day: '',
      patientsTotalCount: 0,
      expensesTotal: 0,
      totalPatients: 0,
      incomeTotal: 0,
      expensesSum: 0,
      totalDebts: 0,
      incomesSum: 0,
      expenses: [],
      debts: [],
      patients: [],
      visits: [],
      incomes: [],
      days: [],
      years: [],
      months: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.years = this.range(2018, moment__WEBPACK_IMPORTED_MODULE_1___default()().year());
    this.getData();
    this.$refs.passwordStatistics.focus();
    setInterval(function () {
      _this.passwordCorrect = false;
      _this.password = '';
    }, 120000);
  },
  methods: {
    monthChanged: function monthChanged() {
      var year = this.year ? this.year : '2022';
      this.days = this.range(1, moment__WEBPACK_IMPORTED_MODULE_1___default()("".concat(year, "-").concat(this.month)).daysInMonth());
    },
    range: function range(start, stop) {
      var step = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 1;
      return Array.from({
        length: (stop - start) / step + 1
      }, function (_, i) {
        return start + i * step;
      });
    },
    getData: function getData() {
      var _this2 = this;

      this.loading = true;
      var query = [];
      var queryParams = '';

      if (this.year) {
        query.push('year=' + this.year);
      }

      if (this.month) {
        query.push('month=' + this.month);
      }

      if (this.day && this.month) {
        query.push('day=' + this.day);
      }

      queryParams = query.join('&');
      axios__WEBPACK_IMPORTED_MODULE_0___default.a.get('/api/statistics?' + queryParams).then(function (_ref) {
        var data = _ref.data;
        _this2.expenses = data.expenses;
        _this2.visits = data.visits;
        _this2.incomes = data.incomes;
        _this2.patients = data.patients;
        _this2.debts = data.debts;
        _this2.totalPatients = _this2.patients.reduce(function (sum, item) {
          return sum + +item.value;
        }, 0);
        _this2.expensesSum = _this2.expenses.reduce(function (sum, item) {
          return sum + +item.value;
        }, 0);
        _this2.incomesSum = _this2.incomes.reduce(function (sum, item) {
          return sum + +item.value;
        }, 0);
        _this2.patientsTotalCount = data.patientsTotalCount;
        _this2.expensesTotal = data.expensesTotal;
        _this2.incomeTotal = data.incomeTotal;
        _this2.totalDebts = data.totalDebts;
      })["finally"](function () {
        _this2.loading = false;
      });
    },
    checkPassword: function checkPassword() {
      this.passwordCorrect = this.password === '1256';
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
  return !_vm.passwordCorrect
    ? _c("div", [
        _c("div", { staticClass: " max-w-md mx-auto py-4" }, [
          _c(
            "label",
            {
              staticClass: "block text-sm font-medium text-gray-700 text-right",
              attrs: { for: "password-statistics" },
            },
            [_vm._v("كلمة\n            المرور")]
          ),
          _vm._v(" "),
          _c("input", {
            directives: [
              {
                name: "model",
                rawName: "v-model",
                value: _vm.password,
                expression: "password",
              },
            ],
            ref: "passwordStatistics",
            staticClass:
              "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
            attrs: {
              type: "password",
              id: "password-statistics",
              autocomplete: "off",
              autofocus: "autofocus",
            },
            domProps: { value: _vm.password },
            on: {
              input: [
                function ($event) {
                  if ($event.target.composing) {
                    return
                  }
                  _vm.password = $event.target.value
                },
                _vm.checkPassword,
              ],
            },
          }),
        ]),
      ])
    : _c(
        "div",
        {
          staticClass:
            "w-full grid grid-cols-1 md:grid-cols-2 gap-8 px-16 py-8",
        },
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
                    "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-full px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
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
                  _c("option", { attrs: { value: "" } }, [
                    _vm._v("اختر السنة"),
                  ]),
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
                    "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-full px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
                  on: {
                    change: [
                      function ($event) {
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
                      function ($event) {
                        return _vm.monthChanged()
                      },
                    ],
                  },
                },
                [
                  _vm._v(">\n                "),
                  _c("option", { attrs: { value: "" } }, [
                    _vm._v("اختر الشهر"),
                  ]),
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
            _vm.month
              ? _c("div", { staticClass: "w-1/5" }, [
                  _c(
                    "select",
                    {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.day,
                          expression: "day",
                        },
                      ],
                      staticClass:
                        "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 h-full px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
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
                          _vm.day = $event.target.multiple
                            ? $$selectedVal
                            : $$selectedVal[0]
                        },
                      },
                    },
                    [
                      _vm._v(">\n                "),
                      _c("option", { attrs: { value: "" } }, [
                        _vm._v("اختر اليوم"),
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.days, function (d) {
                        return _c("option", { domProps: { value: d } }, [
                          _vm._v(_vm._s(d)),
                        ])
                      }),
                    ],
                    2
                  ),
                ])
              : _vm._e(),
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
              _c("div", { staticClass: "w-full lg:w-6/12 xl:w-3/12 pl-4" }, [
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
                                  _vm._s(
                                    _vm._f("numberFormat")(+_vm.incomeTotal)
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
              _c("div", { staticClass: "w-full lg:w-6/12 xl:w-3/12 pr-4" }, [
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
                  {
                    staticClass:
                      "bg-white min-w-full divide-y divide-gray-200 ",
                  },
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
                              "px-3 py-3 whitespace-no-wrap border-b border-gray-200 text-md leading-5 text-red-500",
                          },
                          [
                            _vm._v(
                              "\n                        " +
                                _vm._s(
                                  _vm._f("numberFormat")(+_vm.totalDebts)
                                ) +
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
                                _vm._s(
                                  _vm._f("numberFormat")(_vm.expensesSum)
                                ) +
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
                        _vm._m(5),
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
                                    Math.sign(
                                      _vm.incomesSum - _vm.expensesSum
                                    ) === 1,
                                  "text-red-500":
                                    Math.sign(
                                      _vm.incomesSum - _vm.expensesSum
                                    ) === -1,
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
                      ? _c("apex-polar-chart", {
                          attrs: {
                            label: "مريض",
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
                      ? _c("apex-polar-chart", {
                          attrs: {
                            label: "زيارة",
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
                      ? _c("apex-line-chart", {
                          attrs: {
                            label: "<b class='mr-1'>واردات</b>",
                            color: "green",
                            formatTooltipTitle: ["الواردات"],
                            "suggested-max": _vm.suggestedMax(_vm.incomes),
                            data: _vm.incomes,
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
                      ? _c("apex-line-chart", {
                          attrs: {
                            label: "<b class='mr-1'>نفقات</b>",
                            color: "red",
                            formatTooltipTitle: ["النفقات"],
                            "suggested-max": _vm.suggestedMax(_vm.expenses),
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
                  _vm._v("الواردات و النفقات"),
                ]),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "card-body" },
                  [
                    _vm.incomes.length
                      ? _c("apex-line-chart", {
                          attrs: {
                            label: "واردات",
                            colors: ["blue", "red"],
                            formatTooltipTitle: ["الواردات"],
                            "suggested-max": _vm.suggestedMax(_vm.incomes),
                            data: _vm.incomes,
                            series: [
                              {
                                name: "<b class='mr-1'>واردات </b>",
                                data: _vm.incomes.map(function (_) {
                                  return _.value
                                }),
                              },
                              {
                                name: "<b class='mr-1'>نفقات </b>",
                                data: _vm.expenses.map(function (_) {
                                  return _.value
                                }),
                              },
                            ],
                          },
                        })
                      : _vm._e(),
                  ],
                  1
                ),
              ])
            : _vm._e(),
          _vm._v(" "),
          !_vm.loading && _vm.debts.length
            ? _c("div", { staticClass: "card col-span-full" }, [
                _c("h1", { staticClass: "text-lg font-semibold card-title" }, [
                  _vm._v("المبالغ المتبقية"),
                ]),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "card-body" },
                  [
                    _vm.debts.length
                      ? _c("apex-line-chart", {
                          attrs: {
                            label: "<b class='mr-1'>ديون</b>",
                            formatTooltipTitle: ["المبالغ المتبقية"],
                            "suggested-max": _vm.suggestedMax(_vm.debts),
                            color: "default",
                            data: _vm.debts,
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
      [
        _c("b", { staticClass: "font-medium" }, [
          _vm._v(" المبالغ المتبقية لدى المرضى"),
        ]),
      ]
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

/***/ "./resources/js/mixins/interactsWithMetrics.js":
/*!*****************************************************!*\
  !*** ./resources/js/mixins/interactsWithMetrics.js ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! moment */ "./node_modules/moment/moment.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _interactsWithQuantity__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./interactsWithQuantity */ "./resources/js/mixins/interactsWithQuantity.js");
function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"]; if (_i == null) return; var _arr = []; var _n = true; var _d = false; var _s, _e; try { for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }



/* harmony default export */ __webpack_exports__["default"] = ({
  /**
   * The mixin's mixins.
   */
  mixins: [_interactsWithQuantity__WEBPACK_IMPORTED_MODULE_1__["default"]],

  /**
   * The mixin's methods.
   */
  methods: {
    /**
     * Formats the give date to a human readable format.
     */
    formatMetricDate: function formatMetricDate(date) {
      return moment__WEBPACK_IMPORTED_MODULE_0___default.a.utc(date * 1000, 'x').local().format('LT').replace(/:[0-9]{2}/, '');
    },

    /**
     * Formats the given label to a tooltip title.
     */
    formatTooltipTitle: function formatTooltipTitle(_ref) {
      var _ref2 = _slicedToArray(_ref, 1),
          label = _ref2[0].label;

      return label + ' - ' + moment__WEBPACK_IMPORTED_MODULE_0___default()(label, 'LT').add(1, 'hours').local(true).format('LT').replace(/:[0-9]{2}/, '');
    },

    /**
     * Gets the max value of the given timeseries.
     */
    suggestedMax: function suggestedMax(timeseries) {
      var data = timeseries.map(function (point) {
        return point.value;
      });
      return Math.max(Math.max.apply(Math, _toConsumableArray(data)), 1);
    }
  }
});

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