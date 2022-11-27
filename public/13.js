(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[13],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/patients-files/index.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/patients-files/index.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  methods: {
    clicked: function clicked() {
      console.log('clicked');
    }
  },
  computed: {
    isPatientFilesDetails: function isPatientFilesDetails() {
      return this.$route.name === 'patients-files-show';
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/patients-files/index.vue?vue&type=template&id=07bd3781&":
/*!********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/patients-files/index.vue?vue&type=template&id=07bd3781& ***!
  \********************************************************************************************************************************************************************************************************************/
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
  return !_vm.isPatientFilesDetails
    ? _c(
        "div",
        { staticClass: "w-full" },
        [
          _c(
            "search",
            {
              scopedSlots: _vm._u(
                [
                  {
                    key: "filters",
                    fn: function (ref) {
                      var filters = ref.filters
                      var loadEntries = ref.loadEntries
                      return undefined
                    },
                  },
                  {
                    key: "row",
                    fn: function (ref) {
                      var entry = ref.entry
                      return [
                        _c(
                          "td",
                          {
                            staticClass:
                              "px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500",
                            on: {
                              click: function ($event) {
                                return _vm.clicked()
                              },
                            },
                          },
                          [
                            _vm._v(
                              "\n                " +
                                _vm._s(entry.patient.name) +
                                "\n            "
                            ),
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "td",
                          {
                            staticClass:
                              "px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500",
                          },
                          [
                            _vm._v(
                              "\n                " +
                                _vm._s(entry.patient.file_number) +
                                "\n            "
                            ),
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "td",
                          {
                            staticClass:
                              "px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500",
                          },
                          [
                            _vm._v(
                              "\n                " +
                                _vm._s(entry.latest_payment_date) +
                                "\n            "
                            ),
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "td",
                          {
                            staticClass:
                              "px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500",
                          },
                          [
                            _vm._v(
                              "\n                " +
                                _vm._s(
                                  _vm._f("numberFormat")(+entry.latest_payment)
                                ) +
                                "\n            "
                            ),
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "td",
                          {
                            staticClass:
                              "px-3 py-1 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500",
                          },
                          [
                            _c(
                              "div",
                              { staticClass: "flex item-center" },
                              [
                                _c(
                                  "router-link",
                                  {
                                    staticClass:
                                      "w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150",
                                    attrs: {
                                      to: {
                                        name: "patients-files-show",
                                        params: { id: entry.patient.id },
                                        query: entry.filters,
                                      },
                                      tag: "a",
                                    },
                                  },
                                  [
                                    _c("icon-eye", {
                                      staticClass:
                                        " text-gray-400 hover:text-blue-500 transition-colors",
                                      attrs: { size: "6" },
                                    }),
                                  ],
                                  1
                                ),
                              ],
                              1
                            ),
                          ]
                        ),
                      ]
                    },
                  },
                ],
                null,
                false,
                250605539
              ),
            },
            [
              _vm._v(" "),
              _c("template", { slot: "troubleshooting" }, [
                _c("p", [
                  _vm._v(
                    "It looks like there was an error. Please check your application logs."
                  ),
                ]),
                _vm._v(" "),
                _c("p", { staticClass: "mt-2" }, [
                  _vm._v(
                    '\n                Consider searching using a more recent "Starting from" date. The CloudWatch API may have long\n                response\n                times while searching far into the past. These requests may timeout or lead to unexpected errors.\n            '
                  ),
                ]),
              ]),
              _vm._v(" "),
              _c("template", { slot: "head" }, [
                _c(
                  "tr",
                  {
                    staticClass:
                      "bg-gray-200 text-gray-600 text-sm leading-normal",
                  },
                  [
                    _c("th", { staticClass: "py-2 px-3 text-right" }, [
                      _vm._v("الاسم"),
                    ]),
                    _vm._v(" "),
                    _c("th", { staticClass: "py-2 px-3 text-right" }, [
                      _vm._v("رقم الملف"),
                    ]),
                    _vm._v(" "),
                    _c("th", { staticClass: "py-2 px-3 text-right" }, [
                      _vm._v("تاريخ اخر دفعة"),
                    ]),
                    _vm._v(" "),
                    _c("th", { staticClass: "py-2 px-3 text-right" }, [
                      _vm._v("اخر دفعة"),
                    ]),
                    _vm._v(" "),
                    _c("th", { staticClass: "py-2 px-3 text-right" }),
                  ]
                ),
              ]),
            ],
            2
          ),
          _vm._v(" "),
          _c("router-view"),
        ],
        1
      )
    : _c("div", { staticClass: "w-full" }, [_c("router-view")], 1)
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/screens/patients-files/index.vue":
/*!*******************************************************!*\
  !*** ./resources/js/screens/patients-files/index.vue ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_vue_vue_type_template_id_07bd3781___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.vue?vue&type=template&id=07bd3781& */ "./resources/js/screens/patients-files/index.vue?vue&type=template&id=07bd3781&");
/* harmony import */ var _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.vue?vue&type=script&lang=js& */ "./resources/js/screens/patients-files/index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _index_vue_vue_type_template_id_07bd3781___WEBPACK_IMPORTED_MODULE_0__["render"],
  _index_vue_vue_type_template_id_07bd3781___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/screens/patients-files/index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/screens/patients-files/index.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/js/screens/patients-files/index.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/patients-files/index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/screens/patients-files/index.vue?vue&type=template&id=07bd3781&":
/*!**************************************************************************************!*\
  !*** ./resources/js/screens/patients-files/index.vue?vue&type=template&id=07bd3781& ***!
  \**************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_07bd3781___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./index.vue?vue&type=template&id=07bd3781& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/patients-files/index.vue?vue&type=template&id=07bd3781&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_07bd3781___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_07bd3781___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);