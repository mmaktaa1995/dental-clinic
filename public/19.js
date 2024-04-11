(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[19],{

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
/* harmony import */ var vue2_datepicker__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! vue2-datepicker */ "./node_modules/vue2-datepicker/index.esm.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! moment */ "./node_modules/moment/moment.js");
/* harmony import */ var moment__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(moment__WEBPACK_IMPORTED_MODULE_3__);
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
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    AsyncButton: _components_AsyncButton__WEBPACK_IMPORTED_MODULE_1__["default"],
    DatePicker: vue2_datepicker__WEBPACK_IMPORTED_MODULE_2__["default"]
  },
  data: function data() {
    return {
      id: null,
      payment_id: null,
      isFormManipulating: false,
      submitted: false,
      isEdit: false,
      opened: false,
      isDeletedTab: false,
      patient_name: '',
      currentTab: 'PAYMENTS',
      patient_file_number: '',
      data: [],
      deletedPayments: [],
      type: '',
      totalPayments: 0,
      totalRemainingPayments: 0,
      currentPayment: {},
      form: {
        amount: '',
        remaining_amount: '',
        date: '',
        notes: ''
      }
    };
  },
  mounted: function mounted() {
    this.id = this.$route.params.id;
    this.type = this.$route.query.type;
    this.getData();
    var self = this;
    bus.$on('item-deleted', function () {
      self.getData();
    });
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
        remaining_amount: '',
        date: '',
        notes: ''
      };
    },
    getData: function getData() {
      var _this2 = this;

      axios__WEBPACK_IMPORTED_MODULE_0___default.a.get("/api/patients-files/".concat(this.id)).then(function (_ref) {
        var data = _ref.data;
        _this2.data = data.payments.map(function (item) {
          item.isEdit = false;
          item.isPayDebtOpened = false;
          return item;
        });
        _this2.deletedPayments = data.deleted_payments;

        if (_this2.data.length) {
          _this2.patient_name = _this2.data[0].patient.name;
          _this2.patient_file_number = _this2.data[0].patient.file_number;
          _this2.totalPayments = _this2.data.reduce(function (sum, payment) {
            return sum + +payment.amount;
          }, 0);
          _this2.totalRemainingPayments = _this2.data.reduce(function (sum, payment) {
            return sum + +payment.remaining_amount;
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

      data.date = moment__WEBPACK_IMPORTED_MODULE_3___default()(data.date, 'YYYY-MM-DD').add(1, 'days');
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
        remaining_amount: payment.remaining_amount,
        date: payment.date,
        notes: payment.visit.notes,
        patient_id: this.id
      };

      if (payment.isPayDebtOpened) {
        data.is_pay_debt = true;
        data.old_amount = this.currentPayment.amount;
      }

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

      this.payment_id = id;

      if (!this.opened) {
        this.opened = true;
        return;
      }

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
        _this5.opened = false;
      });
    },
    restorePayment: function restorePayment(payment) {
      var _this6 = this;

      axios__WEBPACK_IMPORTED_MODULE_0___default.a.post("/api/patients-files/".concat(payment.id, "/restore")).then(function (_ref9) {
        var data = _ref9.data;
        bus.$emit('flash-message', {
          text: data.message,
          type: 'success'
        });

        _this6.getData();
      })["catch"](function (_ref10) {
        var response = _ref10.response;
        bus.$emit('flash-message', {
          text: response.data.message,
          type: 'error'
        });
      })["finally"](function () {
        _this6.submitted = false;
        _this6.opened = false;
      });
    },
    editPayment: function editPayment(payment) {
      payment.isEdit = true;
      this.payment_id = payment.id;
      this.form = {
        amount: payment.amount,
        remaining_amount: payment.remaining_amount,
        date: payment.date,
        notes: payment.visit.notes
      };
    },
    addPaymentForDebt: function addPaymentForDebt(payment) {
      this.currentPayment = _objectSpread({}, payment);
      payment.isPayDebtOpened = true;
      this.payment_id = payment.id;
      payment.amount = 0;
    },
    cancelPayment: function cancelPayment(payment) {
      payment.isEdit = false;
      payment.isPayDebtOpened = false;
      payment.amount = this.currentPayment.amount;
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
          [_vm._v("إجمالي المبلغ\n                المدفوع")]
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
      _vm._v(" "),
      _c("div", {}, [
        _c(
          "label",
          {
            staticClass: "block text-sm font-medium text-gray-700 text-right",
            attrs: { for: "totalPayments" },
          },
          [_vm._v("إجمالي المبلغ\n                المتبقي")]
        ),
        _vm._v(" "),
        _c("input", {
          directives: [
            {
              name: "model",
              rawName: "v-model",
              value: _vm.totalRemainingPayments,
              expression: "totalRemainingPayments",
            },
          ],
          staticClass:
            "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
          class: { "text-red-600": _vm.totalRemainingPayments > 0 },
          attrs: { type: "text", id: "totalRemainingPayments", disabled: "" },
          domProps: { value: _vm.totalRemainingPayments },
          on: {
            input: function ($event) {
              if ($event.target.composing) {
                return
              }
              _vm.totalRemainingPayments = $event.target.value
            },
          },
        }),
      ]),
    ]),
    _vm._v(" "),
    _c("div", { staticClass: "sm:block mt-4" }, [
      _c("nav", { staticClass: "flex" }, [
        _c(
          "a",
          {
            class:
              "px-3 py-2 font-medium text-sm leading-5 rounded-md text-gray-500 hover:text-gray-700 focus:outline-none focus:text-indigo-600 focus:bg-indigo-200 " +
              (_vm.currentTab === "PAYMENTS"
                ? "text-indigo-700 bg-indigo-200"
                : ""),
            attrs: { href: "#" },
            on: {
              click: function ($event) {
                $event.preventDefault()
                _vm.currentTab = "PAYMENTS"
              },
            },
          },
          [_vm._v("\n                الدفعات\n            ")]
        ),
        _vm._v(" "),
        _c(
          "a",
          {
            class:
              "ml-4 px-3 py-2 font-medium text-sm leading-5 rounded-md text-gray-500 hover:text-gray-700 focus:outline-none focus:text-indigo-600 focus:bg-indigo-200 " +
              (_vm.currentTab === "DELETED_PAYMENTS"
                ? "text-indigo-700 bg-indigo-200"
                : ""),
            attrs: { href: "#" },
            on: {
              click: function ($event) {
                $event.preventDefault()
                _vm.currentTab = "DELETED_PAYMENTS"
              },
            },
          },
          [_vm._v("\n                الدفعات المحذوفة\n            ")]
        ),
      ]),
    ]),
    _vm._v(" "),
    _c(
      "div",
      [
        _c(
          "TransitionGroup",
          {
            staticClass:
              "align-middle min-w-full overflow-x-auto shadow overflow-hidden sm:rounded-lg mt-8",
            attrs: { name: "list", tag: "div" },
          },
          [
            _vm.currentTab === "PAYMENTS"
              ? _c(
                  "table",
                  {
                    key: "1",
                    staticClass: "bg-white min-w-full divide-y divide-gray-200",
                  },
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
                              attrs: { colspan: "4" },
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
                                  "\n                            إضافة\n                        "
                                ),
                              ]
                            ),
                          ]),
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "tr",
                        {
                          staticClass:
                            "bg-gray-50 text-gray-600 text-sm leading-normal",
                        },
                        [
                          _c("th", { staticClass: "py-2 px-3 text-right" }, [
                            _vm._v("الإجراء الذي تم"),
                          ]),
                          _vm._v(" "),
                          _c("th", { staticClass: "py-2 px-3 text-right" }, [
                            _vm._v("المبلغ المدفوع"),
                          ]),
                          _vm._v(" "),
                          _c("th", { staticClass: "py-2 px-3 text-right" }, [
                            _vm._v("المبلغ المتبقي"),
                          ]),
                          _vm._v(" "),
                          _c("th", { staticClass: "py-2 px-3 text-right" }, [
                            _vm._v("التاريخ"),
                          ]),
                          _vm._v(" "),
                          _c("th", { staticClass: "py-2 px-3 text-right" }),
                        ]
                      ),
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
                                    attrs: {
                                      type: "text",
                                      disabled: _vm.submitted,
                                    },
                                    domProps: { value: _vm.form.notes },
                                    on: {
                                      input: function ($event) {
                                        if ($event.target.composing) {
                                          return
                                        }
                                        _vm.$set(
                                          _vm.form,
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
                                    attrs: {
                                      type: "number",
                                      disabled: _vm.submitted,
                                    },
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
                                    "px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700",
                                },
                                [
                                  _c("input", {
                                    directives: [
                                      {
                                        name: "model",
                                        rawName: "v-model",
                                        value: _vm.form.remaining_amount,
                                        expression: "form.remaining_amount",
                                      },
                                    ],
                                    staticClass:
                                      "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
                                    attrs: {
                                      type: "number",
                                      disabled: _vm.submitted,
                                    },
                                    domProps: {
                                      value: _vm.form.remaining_amount,
                                    },
                                    on: {
                                      input: function ($event) {
                                        if ($event.target.composing) {
                                          return
                                        }
                                        _vm.$set(
                                          _vm.form,
                                          "remaining_amount",
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
                                  _c("date-picker", {
                                    attrs: {
                                      type: "date",
                                      disabled: _vm.submitted,
                                    },
                                    model: {
                                      value: _vm.form.date,
                                      callback: function ($$v) {
                                        _vm.$set(_vm.form, "date", $$v)
                                      },
                                      expression: "form.date",
                                    },
                                  }),
                                ],
                                1
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
                                        "\n                            حفظ\n                        "
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
                                        "\n                            إلغاء\n                        "
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
                                      attrs: {
                                        type: "text",
                                        disabled: _vm.submitted,
                                      },
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
                                !payment.isEdit && !payment.isPayDebtOpened
                                  ? _c("span", [
                                      _c("b", { staticClass: "font-medium" }, [
                                        _vm._v(
                                          _vm._s(
                                            _vm._f("numberFormat")(
                                              +payment.amount
                                            )
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
                                  "px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-700",
                              },
                              [
                                !payment.isEdit
                                  ? _c("span", [
                                      !payment.isPayDebtOpened
                                        ? _c(
                                            "b",
                                            {
                                              staticClass: "font-medium",
                                              class: {
                                                "text-red-500":
                                                  payment.remaining_amount > 0,
                                              },
                                            },
                                            [
                                              _vm._v(
                                                "\n                            " +
                                                  _vm._s(
                                                    _vm._f("numberFormat")(
                                                      +payment.remaining_amount
                                                    )
                                                  ) +
                                                  "\n                        "
                                              ),
                                            ]
                                          )
                                        : _c(
                                            "b",
                                            {
                                              staticClass: "font-medium",
                                              class: {
                                                "text-red-500":
                                                  payment.remaining_amount > 0,
                                              },
                                            },
                                            [
                                              _vm._v(
                                                "\n                            " +
                                                  _vm._s(
                                                    _vm._f("numberFormat")(
                                                      +(
                                                        payment.remaining_amount -
                                                        payment.amount
                                                      )
                                                    )
                                                  ) +
                                                  "\n                        "
                                              ),
                                            ]
                                          ),
                                    ])
                                  : _c("input", {
                                      directives: [
                                        {
                                          name: "model",
                                          rawName: "v-model",
                                          value: payment.remaining_amount,
                                          expression:
                                            "payment.remaining_amount",
                                        },
                                      ],
                                      staticClass:
                                        "block border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 mt-1 px-2 py-2 rounded-md shadow-sm sm:text-sm w-full",
                                      attrs: {
                                        type: "number",
                                        disabled: _vm.submitted,
                                      },
                                      domProps: {
                                        value: payment.remaining_amount,
                                      },
                                      on: {
                                        input: function ($event) {
                                          if ($event.target.composing) {
                                            return
                                          }
                                          _vm.$set(
                                            payment,
                                            "remaining_amount",
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
                                      attrs: {
                                        type: "date",
                                        disabled: _vm.submitted,
                                      },
                                      domProps: { value: payment.date },
                                      on: {
                                        input: function ($event) {
                                          if ($event.target.composing) {
                                            return
                                          }
                                          _vm.$set(
                                            payment,
                                            "date",
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
                                !payment.isEdit && !payment.isPayDebtOpened
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
                                !payment.isEdit && !payment.isPayDebtOpened
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
                                payment.remaining_amount > 0 &&
                                !payment.isPayDebtOpened &&
                                !payment.isEdit
                                  ? _c(
                                      "a",
                                      {
                                        staticClass:
                                          "py-1 inline-flex h-12 px-2 text-sm text-center text-teal-600 transition-colors duration-200 transform lg:h-8 hover:text-teal-700 focus:outline-none",
                                        attrs: { href: "#" },
                                        on: {
                                          click: function ($event) {
                                            return _vm.addPaymentForDebt(
                                              payment
                                            )
                                          },
                                        },
                                      },
                                      [
                                        _c("icon-money", {
                                          staticClass: "transition-colors",
                                          attrs: { size: "5" },
                                        }),
                                      ],
                                      1
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                payment.isEdit || payment.isPayDebtOpened
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
                                          "\n                            " +
                                            _vm._s(
                                              payment.isPayDebtOpened
                                                ? "حفظ"
                                                : "تعديل"
                                            ) +
                                            "\n                        "
                                        ),
                                      ]
                                    )
                                  : _vm._e(),
                                _vm._v(" "),
                                payment.isEdit || payment.isPayDebtOpened
                                  ? _c(
                                      "a",
                                      {
                                        staticClass:
                                          "ml-4 py-1 items-center justify-center h-12 px-4 text-sm text-center text-white bg-red-600 transition-colors duration-200 transform border rounded-lg lg:h-8 hover:bg-red-700 focus:outline-none",
                                        attrs: { href: "#" },
                                        on: {
                                          click: function ($event) {
                                            return _vm.cancelPayment(payment)
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
                )
              : _vm._e(),
            _vm._v(" "),
            _vm.currentTab === "DELETED_PAYMENTS"
              ? _c(
                  "table",
                  {
                    key: "2",
                    staticClass: "bg-white min-w-full divide-y divide-gray-200",
                  },
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
                              attrs: { colspan: "5" },
                            },
                            [_vm._v("الدفعات المحذوفة")]
                          ),
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "tr",
                        {
                          staticClass:
                            "bg-gray-50 text-gray-600 text-sm leading-normal",
                        },
                        [
                          _c("th", { staticClass: "py-2 px-3 text-right" }, [
                            _vm._v("الإجراء الذي تم"),
                          ]),
                          _vm._v(" "),
                          _c("th", { staticClass: "py-2 px-3 text-right" }, [
                            _vm._v("المبلغ المدفوع"),
                          ]),
                          _vm._v(" "),
                          _c("th", { staticClass: "py-2 px-3 text-right" }, [
                            _vm._v("المبلغ المتبقي"),
                          ]),
                          _vm._v(" "),
                          _c("th", { staticClass: "py-2 px-3 text-right" }, [
                            _vm._v("التاريخ"),
                          ]),
                          _vm._v(" "),
                          _c("th", { staticClass: "py-2 px-3 text-right" }),
                        ]
                      ),
                    ]),
                    _vm._v(" "),
                    _c(
                      "tbody",
                      { staticClass: "divide-y divide-gray-200" },
                      _vm._l(_vm.deletedPayments, function (payment) {
                        return _c("tr", [
                          _c(
                            "td",
                            {
                              staticClass:
                                "px-3 py-3 whitespace-no-wrap border-b border-gray-200 leading-5 text-gray-500",
                            },
                            [
                              _c("span", [
                                _vm._v(
                                  "\n                        " +
                                    _vm._s(
                                      payment.visit.notes
                                        ? payment.visit.notes
                                        : "-"
                                    ) +
                                    "\n                    "
                                ),
                              ]),
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
                              _c("span", [
                                _c("b", { staticClass: "font-medium" }, [
                                  _vm._v(
                                    _vm._s(
                                      _vm._f("numberFormat")(+payment.amount)
                                    )
                                  ),
                                ]),
                              ]),
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
                                    _c(
                                      "b",
                                      {
                                        staticClass: "font-medium",
                                        class: {
                                          "text-red-500":
                                            payment.remaining_amount > 0,
                                        },
                                      },
                                      [
                                        _vm._v(
                                          "\n                            " +
                                            _vm._s(
                                              _vm._f("numberFormat")(
                                                +payment.remaining_amount
                                              )
                                            ) +
                                            "\n                        "
                                        ),
                                      ]
                                    ),
                                  ])
                                : _vm._e(),
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
                              _c("span", [
                                _vm._v(
                                  "\n                        " +
                                    _vm._s(payment.date) +
                                    "\n                    "
                                ),
                              ]),
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
                                "a",
                                {
                                  staticClass:
                                    "py-1 inline-flex h-12 px-2 text-sm text-center text-green-600 transition-colors duration-200 transform lg:h-8 hover:text-green-700 focus:outline-none",
                                  attrs: { href: "#", title: "استعادة" },
                                  on: {
                                    click: function ($event) {
                                      return _vm.restorePayment(payment)
                                    },
                                  },
                                },
                                [
                                  _c("icon-restore", {
                                    staticClass: "transition-colors",
                                    attrs: { size: "5" },
                                  }),
                                ],
                                1
                              ),
                            ]
                          ),
                        ])
                      }),
                      0
                    ),
                  ]
                )
              : _vm._e(),
          ]
        ),
      ],
      1
    ),
    _vm._v(" "),
    _vm.opened
      ? _c(
          "div",
          {
            class: "fixed z-10 inset-0 overflow-y-auto ",
            attrs: {
              "aria-labelledby": "modal-title",
              role: "dialog",
              "aria-modal": "true",
            },
          },
          [
            _c(
              "div",
              {
                staticClass:
                  "flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0",
              },
              [
                _c("div", {
                  class:
                    "fixed inset-0 bg-gray-500 transition-opacity duration-200 " +
                    (_vm.opened ? "bg-opacity-75" : "bg-opacity-0"),
                  attrs: { "aria-hidden": "true" },
                  on: {
                    click: function ($event) {
                      _vm.opened = false
                    },
                  },
                }),
                _vm._v(" "),
                _c(
                  "span",
                  {
                    staticClass:
                      "hidden sm:inline-block sm:align-middle sm:h-screen",
                    attrs: { "aria-hidden": "true" },
                  },
                  [_vm._v("​")]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  {
                    class:
                      "inline-block w-full align-bottom bg-white rounded-lg text-right overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200  " +
                      (_vm.opened ? "scale-100" : "scale-0"),
                  },
                  [
                    _c(
                      "div",
                      { staticClass: "bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4" },
                      [
                        _c("div", { staticClass: "sm:flex sm:items-start" }, [
                          _c(
                            "div",
                            {
                              staticClass:
                                "mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10",
                            },
                            [
                              _c(
                                "svg",
                                {
                                  staticClass: "h-6 w-6 text-red-600",
                                  attrs: {
                                    xmlns: "http://www.w3.org/2000/svg",
                                    fill: "none",
                                    viewBox: "0 0 24 24",
                                    stroke: "currentColor",
                                    "aria-hidden": "true",
                                  },
                                },
                                [
                                  _c("path", {
                                    attrs: {
                                      "stroke-linecap": "round",
                                      "stroke-linejoin": "round",
                                      "stroke-width": "2",
                                      d: "M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z",
                                    },
                                  }),
                                ]
                              ),
                            ]
                          ),
                          _vm._v(" "),
                          _vm._m(0),
                        ]),
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass:
                          "bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row",
                      },
                      [
                        _c(
                          "async-button",
                          {
                            staticClass:
                              "mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mr-3 sm:w-auto sm:text-sm",
                            attrs: { type: "button", loading: _vm.submitted },
                            on: {
                              click: function ($event) {
                                return _vm.deletePayment(_vm.payment_id)
                              },
                            },
                          },
                          [
                            _vm._v(
                              "\n                        حذف\n                    "
                            ),
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "button",
                          {
                            staticClass:
                              "mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mr-3 sm:w-auto sm:text-sm",
                            attrs: { type: "button" },
                            on: {
                              click: function ($event) {
                                _vm.opened = false
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
                  ]
                ),
              ]
            ),
          ]
        )
      : _vm._e(),
  ])
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      { staticClass: "mt-3 text-center sm:mt-0 sm:mr-4 sm:text-right" },
      [
        _c(
          "h3",
          {
            staticClass: "text-lg leading-6 font-medium text-gray-900",
            attrs: { id: "modal-title" },
          },
          [
            _vm._v(
              "\n                                حذف دفعة\n                            "
            ),
          ]
        ),
        _vm._v(" "),
        _c("div", { staticClass: "mt-2" }, [
          _c("p", { staticClass: "text-sm text-gray-500" }, [
            _vm._v(
              "\n                                    هل أنت متأكد من حذف هذه الدفعة؟\n                                "
            ),
          ]),
        ]),
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