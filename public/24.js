(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[24],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/patients/upload-files.vue?vue&type=script&lang=js&":
/*!*****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/patients/upload-files.vue?vue&type=script&lang=js& ***!
  \*****************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_0__);
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

/* harmony default export */ __webpack_exports__["default"] = ({
  name: "upload-files",
  data: function data() {
    return {
      id: null,
      loaded: false,
      opened: false,
      errors: {},
      submitted: false,
      files: []
    };
  },
  mounted: function mounted() {
    var _this = this;

    this.id = this.$route.params.id;
    this.loaded = false;
    axios__WEBPACK_IMPORTED_MODULE_0___default.a.get("/api/patients/".concat(this.id)).then(function (_ref) {
      var data = _ref.data;
      _this.patient = _objectSpread({}, data);
      _this.files = _this.patient.images.map(function (image) {
        return {
          source: image.image,
          options: {
            type: 'local',
            metadata: {
              poster: image.image
            }
          }
        };
      });
      setTimeout(function () {
        _this.opened = true;
        _this.loaded = true;
      }, 50);
      console.log(_this.files);
    });
  },
  methods: {
    back: function back() {
      var _this2 = this;

      this.opened = false;
      setTimeout(function () {
        return _this2.$router.back();
      }, 300);
    },
    setImages: function setImages(images) {
      this.files = images;

      if (this.files.length) {
        this.update();
      }
    },
    update: function update() {
      var _this3 = this;

      var self = this;
      this.submitted = true;
      axios__WEBPACK_IMPORTED_MODULE_0___default.a.patch("/api/patients/".concat(this.id, "/images"), {
        images: this.files
      }).then(function (_ref2) {
        var data = _ref2.data;
        bus.$emit('flash-message', {
          text: data.message,
          type: 'success'
        }); // self.back();
      })["catch"](function (error) {
        bus.$emit('flash-message', {
          text: error.response.message,
          type: 'danger'
        });
      })["finally"](function () {
        _this3.submitted = false;
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/patients/upload-files.vue?vue&type=template&id=515a0ff6&scoped=true&":
/*!*********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/patients/upload-files.vue?vue&type=template&id=515a0ff6&scoped=true& ***!
  \*********************************************************************************************************************************************************************************************************************************/
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
                return _vm.back()
              },
            },
          }),
          _vm._v(" "),
          _c(
            "span",
            {
              staticClass: "hidden sm:inline-block sm:align-middle sm:h-screen",
              attrs: { "aria-hidden": "true" },
            },
            [_vm._v("​")]
          ),
          _vm._v(" "),
          _c(
            "div",
            {
              class:
                "inline-block w-full align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full duration-200  " +
                (_vm.opened ? "scale-100" : "scale-0"),
            },
            [
              _vm._m(0),
              _vm._v(" "),
              _c("div", { staticClass: "bg-white px-4 pt-5 sm:p-6" }, [
                _vm.loaded
                  ? _c("div", { staticClass: "grid grid-cols-2 gap-6" }, [
                      _c(
                        "div",
                        { staticClass: "col-span-full" },
                        [
                          _c("file-pond-component", {
                            attrs: {
                              files: _vm.files,
                              folder: "patients",
                              type: "images",
                            },
                            on: { updateFiles: _vm.setImages },
                          }),
                        ],
                        1
                      ),
                    ])
                  : _vm._e(),
              ]),
              _vm._v(" "),
              _c(
                "div",
                {
                  staticClass:
                    "bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-revers",
                },
                [
                  _c(
                    "button",
                    {
                      staticClass:
                        "mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm",
                      attrs: { type: "button" },
                      on: {
                        click: function ($event) {
                          return _vm.back()
                        },
                      },
                    },
                    [
                      _vm._v(
                        "\n                        إلغاء\n                    "
                      ),
                    ]
                  ),
                ]
              ),
            ]
          ),
        ]
      ),
    ]
  )
}
var staticRenderFns = [
  function () {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass: "bg-gray-50 px-4 py-2 border-b border-gray-300 text-right",
      },
      [
        _c("h3", { staticClass: "text-lg text-gray-700 font-normal" }, [
          _vm._v(" ملفات المريض"),
        ]),
      ]
    )
  },
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/screens/patients/upload-files.vue":
/*!********************************************************!*\
  !*** ./resources/js/screens/patients/upload-files.vue ***!
  \********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _upload_files_vue_vue_type_template_id_515a0ff6_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./upload-files.vue?vue&type=template&id=515a0ff6&scoped=true& */ "./resources/js/screens/patients/upload-files.vue?vue&type=template&id=515a0ff6&scoped=true&");
/* harmony import */ var _upload_files_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./upload-files.vue?vue&type=script&lang=js& */ "./resources/js/screens/patients/upload-files.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _upload_files_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _upload_files_vue_vue_type_template_id_515a0ff6_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _upload_files_vue_vue_type_template_id_515a0ff6_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "515a0ff6",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/screens/patients/upload-files.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/screens/patients/upload-files.vue?vue&type=script&lang=js&":
/*!*********************************************************************************!*\
  !*** ./resources/js/screens/patients/upload-files.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_upload_files_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./upload-files.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/patients/upload-files.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_upload_files_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/screens/patients/upload-files.vue?vue&type=template&id=515a0ff6&scoped=true&":
/*!***************************************************************************************************!*\
  !*** ./resources/js/screens/patients/upload-files.vue?vue&type=template&id=515a0ff6&scoped=true& ***!
  \***************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_upload_files_vue_vue_type_template_id_515a0ff6_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./upload-files.vue?vue&type=template&id=515a0ff6&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/patients/upload-files.vue?vue&type=template&id=515a0ff6&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_upload_files_vue_vue_type_template_id_515a0ff6_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_upload_files_vue_vue_type_template_id_515a0ff6_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);