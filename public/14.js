(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[14],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/patients-files/show.vue?vue&type=script&lang=js&":
/*!***************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/patients-files/show.vue?vue&type=script&lang=js& ***!
  \***************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _components_AsyncButton__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../components/AsyncButton */ "./resources/js/components/AsyncButton.vue");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); enumerableOnly && (symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; })), keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = null != arguments[i] ? arguments[i] : {}; i % 2 ? ownKeys(Object(source), !0).forEach(function (key) { _defineProperty(target, key, source[key]); }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    AsyncButton: _components_AsyncButton__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  data: function data() {
    return {
      id: null,
      payment_id: null,
      isFormManipulating: false,
      submitted: false,
      isEdit: false,
      patient_name: '',
      patient_file_number: '',
      data: [],
      type: '',
      totalPayments: 0,
      form: {
        amount: '',
        date: '',
        notes: ''
      }
    };
  },
  mounted: function mounted() {
    this.id = this.$route.params.id;
    this.type = this.$route.query.type;
    this.getData();
  },
  methods: {
    back: function back() {
      var _this = this;

      setTimeout(function () {
        return _this.$router.back();
      }, 100);
    },
    resetForm: function resetForm() {
      this.form = {
        amount: '',
        date: '',
        notes: ''
      };
    },
    getData: function getData() {
      var _this2 = this;

      axios__WEBPACK_IMPORTED_MODULE_0___default.a.get("/api/patients-files/".concat(this.id)).then(function (_ref) {
        var data = _ref.data;
        _this2.data = data.map(function (item) {
          item.isEdit = false;
          return item;
        });

        if (_this2.data.length) {
          _this2.patient_name = _this2.data[0].patient.name;
          _this2.patient_file_number = _this2.data[0].patient.file_number;
          _this2.totalPayments = _this2.data.reduce(function (sum, payment) {
            return sum + +payment.amount;
          }, 0);
        }
      });
    },
    print: function print() {
      axios__WEBPACK_IMPORTED_MODULE_0___default.a.get("/api/patients-files/".concat(this.id, "/print")).then(function (_ref2) {
        var data = _ref2.data;
        window.open(data.url, 'blank');
        console.log(data);
      });
    },
    addPayment: function addPayment() {
      var _this3 = this;

      this.submitted = true;

      var data = _objectSpread(_objectSpread({}, this.form), {}, {
        patient_id: this.id
      });

      axios__WEBPACK_IMPORTED_MODULE_0___default.a.post("/api/patients-files", data).then(function (_ref3) {
        var data = _ref3.data;
        bus.$emit('flash-message', {
          text: data.message,
          type: 'success'
        });

        _this3.resetForm();

        _this3.isFormManipulating = false;

        _this3.getData();
      })["catch"](function (_ref4) {
        var response = _ref4.response;
        bus.$emit('flash-message', {
          text: response.data.message,
          type: 'error'
        });
      })["finally"](function () {
        _this3.submitted = false;
      });
    },
    savePayment: function savePayment(payment) {
      var _this4 = this;

      this.submitted = true;
      var data = {
        amount: payment.amount,
        date: payment.date,
        notes: payment.visit.notes,
        patient_id: this.id
      };
      axios__WEBPACK_IMPORTED_MODULE_0___default.a.put("/api/patients-files/".concat(this.payment_id), data).then(function (_ref5) {
        var data = _ref5.data;
        bus.$emit('flash-message', {
          text: data.message,
          type: 'success'
        });

        _this4.resetForm();

        payment.isEdit = false;

        _this4.getData();
      })["catch"](function (_ref6) {
        var response = _ref6.response;
        bus.$emit('flash-message', {
          text: response.data.message,
          type: 'error'
        });
      })["finally"](function () {
        _this4.submitted = false;
      });
    },
    deletePayment: function deletePayment(id) {
      var _this5 = this;

      axios__WEBPACK_IMPORTED_MODULE_0___default.a["delete"]("/api/patients-files/".concat(id)).then(function (_ref7) {
        var data = _ref7.data;
        bus.$emit('flash-message', {
          text: data.message,
          type: 'success'
        });

        _this5.getData();
      })["catch"](function (_ref8) {
        var response = _ref8.response;
        bus.$emit('flash-message', {
          text: response.data.message,
          type: 'error'
        });
      })["finally"](function () {
        _this5.submitted = false;
      });
    },
    editPayment: function editPayment(payment) {
      payment.isEdit = true;
      this.payment_id = payment.id;
      this.form = {
        amount: payment.amount,
        date: payment.date,
        notes: payment.visit.notes
      };
    }
  },
  computed: {
    isPatientFilesDetails: function isPatientFilesDetails() {
      return this.$route.name === 'patients-files-show';
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/patients-files/show.vue?vue&type=template&id=a90b99c4&":
/*!*******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/patients-files/show.vue?vue&type=template&id=a90b99c4& ***!
  \*******************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "px-12 py-6" }, [
    _c("div", { staticClass: "w-full text-left" }, [
      _c(
        "button",
        {
          staticClass:
            "py-1 items-center justify-center h-12 px-4 text-sm text-center text-gray-100 hover:text-gray-50 bg-purple-500 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-purple-600 focus:outline-none",
          on: {
            click: function ($event) {
              return _vm.print()
            },
          },
        },
        [_vm._v("\n            طباعة\n        ")]
      ),
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "grid grid-cols-1 sm:grid-cols-2 gap-6" }, [
      _c("div", {}, [
        _c(
          "label",
          {
            staticClass: "block text-sm font-medium text-gray-700 text-right",
            attrs: { for: "name" },
          },
          [_vm._v("الاسم")]
        ),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.patient_name,
              expression: "patient_name",
            },
          ],
          staticClass:
            "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
          attrs: { type: "text", id: "name", disabled: "" },
          domProps: { value: _vm.patient_name },
          on: {
            input: function ($event) {
              if ($event.target.composing) {
                return
              }
              _vm.patient_name = $event.target.value
            },
          },
        }),
      ]),
      _vm._v(" "),
      _c("div", {}, [
        _c(
          "label",
          {
            staticClass: "block text-sm font-medium text-gray-700 text-right",
            attrs: { for: "file_number" },
          },
          [_vm._v("رقم الملف")]
        ),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.patient_file_number,
              expression: "patient_file_number",
            },
          ],
          staticClass:
            "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
          attrs: { type: "text", id: "file_number", disabled: "" },
          domProps: { value: _vm.patient_file_number },
          on: {
            input: function ($event) {
              if ($event.target.composing) {
                return
              }
              _vm.patient_file_number = $event.target.value
            },
          },
        }),
      ]),
      _vm._v(" "),
      _c("div", {}, [
        _c(
          "label",
          {
            staticClass: "block text-sm font-medium text-gray-700 text-right",
            attrs: { for: "totalPayments" },
          },
          [_vm._v("إجمالي المبلغ\n                الدفوع")]
        ),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.totalPayments,
              expression: "totalPayments",
            },
          ],
          staticClass:
            "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
          attrs: { type: "text", id: "totalPayments", disabled: "" },
          domProps: { value: _vm.totalPayments },
          on: {
            input: function ($event) {
              if ($event.target.composing) {
                return
              }
              _vm.totalPayments = $event.target.value
            },
          },
        }),
      ]),
    ]),
    _vm._v(" "),
    _c(
      "div",
      {
        staticClass:
          "align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg mt-8",
      },
      [
        _c(
          "table",
          { staticClass: "bg-white min-w-full divide-y divide-gray-200 " },
          [
            _c("thead", [
              _c(
                "tr",
                {
                  staticClass:
                    "bg-gray-200 text-gray-600 text-sm leading-normal",
                },
                [
                  _c(
                    "th",
                    {
                      staticClass: "py-2 px-3 text-right",
                      attrs: { colspan: "3" },
                    },
                    [_vm._v("الدفعات")]
                  ),
                  _vm._v(" "),
                  _c("th", { staticClass: "py-2 px-3 text-left" }, [
                    _c(
                      "a",
                      {
                        staticClass:
                          "ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-gray-100 hover:text-gray-50 bg-gray-800 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-gray-600 focus:outline-none",
                        attrs: { href: "#" },
                        on: {
                          click: function ($event) {
                            _vm.isFormManipulating = true
                            _vm.isEdit = false
                          },
                        },
                      },
                      [
                        _vm._v(
                          "\n                        إضافة\n                    "
                        ),
                      ]
                    ),
                  ]),
                ]
              ),
              _vm._v(" "),
              _vm._m(0),
            ]),
            _vm._v(" "),
            _c(
              "tbody",
              { staticClass: "divide-y divide-gray-200" },
              [
                _vm.isFormManipulating
                  ? _c("tr", [
                      _c(
                        "td",
                        {
                          staticClass:
                            "px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500",
                        },
                        [
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.form.notes,
                                expression: "form.notes",
                              },
                            ],
                            staticClass:
                              "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
                            attrs: { type: "text", disabled: _vm.submitted },
                            domProps: { value: _vm.form.notes },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(_vm.form, "notes", $event.target.value)
                              },
                            },
                          }),
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "td",
                        {
                          staticClass:
                            "px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700",
                        },
                        [
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.form.amount,
                                expression: "form.amount",
                              },
                            ],
                            staticClass:
                              "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
                            attrs: { type: "number", disabled: _vm.submitted },
                            domProps: { value: _vm.form.amount },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(
                                  _vm.form,
                                  "amount",
                                  $event.target.value
                                )
                              },
                            },
                          }),
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "td",
                        {
                          staticClass:
                            "px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500",
                        },
                        [
                          _c("input", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.form.date,
                                expression: "form.date",
                              },
                            ],
                            staticClass:
                              "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
                            attrs: { type: "date", disabled: _vm.submitted },
                            domProps: { value: _vm.form.date },
                            on: {
                              input: function ($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.$set(_vm.form, "date", $event.target.value)
                              },
                            },
                          }),
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "td",
                        {
                          staticClass:
                            "px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500",
                        },
                        [
                          _c(
                            "async-button",
                            {
                              staticClass:
                                "ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-green-500 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-green-600 focus:outline-none",
                              attrs: { loading: _vm.submitted },
                              on: {
                                click: function ($event) {
                                  return _vm.addPayment()
                                },
                              },
                            },
                            [
                              _vm._v(
                                "\n                        حفظ\n                    "
                              ),
                            ]
                          ),
                          _vm._v(" "),
                          _c(
                            "a",
                            {
                              staticClass:
                                "ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-red-600 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-red-700 focus:outline-none",
                              attrs: { href: "#" },
                              on: {
                                click: function ($event) {
                                  _vm.resetForm()
                                  _vm.isFormManipulating = false
                                },
                              },
                            },
                            [
                              _vm._v(
                                "\n                        إلغاء\n                    "
                              ),
                            ]
                          ),
                        ],
                        1
                      ),
                    ])
                  : _vm._e(),
                _vm._v(" "),
                _vm._l(_vm.data, function (payment) {
                  return _c("tr", [
                    _c(
                      "td",
                      {
                        staticClass:
                          "px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500",
                      },
                      [
                        !payment.isEdit
                          ? _c("span", [
                              _vm._v(
                                "\n                        " +
                                  _vm._s(
                                    payment.visit.notes
                                      ? payment.visit.notes
                                      : "-"
                                  ) +
                                  "\n                    "
                              ),
                            ])
                          : _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: payment.visit.notes,
                                  expression: "payment.visit.notes",
                                },
                              ],
                              staticClass:
                                "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
                              attrs: { type: "text", disabled: _vm.submitted },
                              domProps: { value: payment.visit.notes },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.$set(
                                    payment.visit,
                                    "notes",
                                    $event.target.value
                                  )
                                },
                              },
                            }),
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "td",
                      {
                        staticClass:
                          "px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700",
                      },
                      [
                        !payment.isEdit
                          ? _c("span", [
                              _c("b", { staticClass: "font-medium" }, [
                                _vm._v(
                                  _vm._s(
                                    _vm._f("numberFormat")(+payment.amount)
                                  )
                                ),
                              ]),
                            ])
                          : _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: payment.amount,
                                  expression: "payment.amount",
                                },
                              ],
                              staticClass:
                                "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
                              attrs: {
                                type: "number",
                                disabled: _vm.submitted,
                              },
                              domProps: { value: payment.amount },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.$set(
                                    payment,
                                    "amount",
                                    $event.target.value
                                  )
                                },
                              },
                            }),
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "td",
                      {
                        staticClass:
                          "px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500",
                      },
                      [
                        !payment.isEdit
                          ? _c("span", [
                              _vm._v(
                                "\n                        " +
                                  _vm._s(payment.date) +
                                  "\n                    "
                              ),
                            ])
                          : _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: payment.date,
                                  expression: "payment.date",
                                },
                              ],
                              staticClass:
                                "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
                              attrs: { type: "date", disabled: _vm.submitted },
                              domProps: { value: payment.date },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.$set(payment, "date", $event.target.value)
                                },
                              },
                            }),
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "td",
                      {
                        staticClass:
                          "px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500",
                      },
                      [
                        !payment.isEdit
                          ? _c(
                              "a",
                              {
                                staticClass:
                                  "py-1 inline-flex h-12 px-2 text-sm text-center text-green-600 transition-colors duration-200 transform lg:h-8 hover:text-green-700 focus:outline-none",
                                attrs: { href: "#" },
                                on: {
                                  click: function ($event) {
                                    return _vm.editPayment(payment)
                                  },
                                },
                              },
                              [
                                _c("icon-edit", {
                                  staticClass: "transition-colors",
                                  attrs: { size: "5" },
                                }),
                              ],
                              1
                            )
                          : _vm._e(),
                        _vm._v(" "),
                        !payment.isEdit
                          ? _c(
                              "a",
                              {
                                staticClass:
                                  "py-1 inline-flex h-12 px-2 text-sm text-center text-red-600 transition-colors duration-200 transform lg:h-8 hover:text-red-700 focus:outline-none",
                                attrs: { href: "#" },
                                on: {
                                  click: function ($event) {
                                    return _vm.deletePayment(payment.id)
                                  },
                                },
                              },
                              [
                                _c("icon-delete", {
                                  staticClass: "transition-colors",
                                  attrs: { size: "5" },
                                }),
                              ],
                              1
                            )
                          : _vm._e(),
                        _vm._v(" "),
                        payment.isEdit
                          ? _c(
                              "async-button",
                              {
                                staticClass:
                                  "ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-green-500 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-green-600 focus:outline-none",
                                attrs: { loading: _vm.submitted },
                                on: {
                                  click: function ($event) {
                                    return _vm.savePayment(payment)
                                  },
                                },
                              },
                              [
                                _vm._v(
                                  "\n                        تعديل\n                    "
                                ),
                              ]
                            )
                          : _vm._e(),
                        _vm._v(" "),
                        payment.isEdit
                          ? _c(
                              "a",
                              {
                                staticClass:
                                  "ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-red-600 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-red-700 focus:outline-none",
                                attrs: { href: "#" },
                                on: {
                                  click: function ($event) {
                                    payment.isEdit = false
                                  },
                                },
                              },
                              [_c("span", [_vm._v("إلغاء")])]
                            )
                          : _vm._e(),
                      ],
                      1
                    ),
                  ])
                }),
              ],
              2
            ),
          ]
        ),
      ]
    ),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "tr",
      { staticClass: "bg-gray-50 text-gray-600 text-sm leading-normal" },
      [
        _c("th", { staticClass: "py-2 px-3 text-right" }, [
          _vm._v("الإجراء الذي تم"),
        ]),
        _vm._v(" "),
        _c("th", { staticClass: "py-2 px-3 text-right" }, [_vm._v("المبلغ")]),
        _vm._v(" "),
        _c("th", { staticClass: "py-2 px-3 text-right" }, [_vm._v("التاريخ")]),
        _vm._v(" "),
        _c("th", { staticClass: "py-2 px-3 text-right" }),
      ]
    )
  },
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/screens/patients-files/show.vue":
/*!******************************************************!*\
  !*** ./resources/js/screens/patients-files/show.vue ***!
  \******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _show_vue_vue_type_template_id_a90b99c4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./show.vue?vue&type=template&id=a90b99c4& */ "./resources/js/screens/patients-files/show.vue?vue&type=template&id=a90b99c4&");
/* harmony import */ var _show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./show.vue?vue&type=script&lang=js& */ "./resources/js/screens/patients-files/show.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _show_vue_vue_type_template_id_a90b99c4___WEBPACK_IMPORTED_MODULE_0__["render"],
  _show_vue_vue_type_template_id_a90b99c4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/screens/patients-files/show.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/screens/patients-files/show.vue?vue&type=script&lang=js&":
/*!*******************************************************************************!*\
  !*** ./resources/js/screens/patients-files/show.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./show.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/patients-files/show.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_show_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/screens/patients-files/show.vue?vue&type=template&id=a90b99c4&":
/*!*************************************************************************************!*\
  !*** ./resources/js/screens/patients-files/show.vue?vue&type=template&id=a90b99c4& ***!
  \*************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_show_vue_vue_type_template_id_a90b99c4___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./show.vue?vue&type=template&id=a90b99c4& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/patients-files/show.vue?vue&type=template&id=a90b99c4&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_show_vue_vue_type_template_id_a90b99c4___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_show_vue_vue_type_template_id_a90b99c4___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);