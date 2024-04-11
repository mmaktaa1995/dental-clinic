(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[1],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/auth/register.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/auth/register.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_0__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
  data: function data() {
    return {
      errors: {},
      type: 'api',
      username: '',
      firstName: '',
      lastName: '',
      reg_year: '',
      address: '',
      mobile_number: '',
      gender: '',
      name: '',
      email: '',
      password: ''
    };
  },
  methods: {
    register: function register() {
      var _this = this;

      this.errors = {};
      var data = {
        username: this.username,
        name: this.name,
        email: this.email,
        password: this.password,
        type: this.type
      };

      if (this.type === 'student') {
        data.first_name = this.firstName;
        data.last_name = this.lastName;
        data.reg_year = this.reg_year;
        data.address = this.address;
        data.mobile_number = this.mobile_number;
        data.gender = this.gender;
        delete data.name;
      }

      axios__WEBPACK_IMPORTED_MODULE_0___default.a.post('/api/register', data).then(function (_ref) {
        var data = _ref.data;
        localStorage.setItem('user', JSON.stringify(data.data.user));
        localStorage.setItem('access_token', data.data.access_token);
        bus.$emit('flash-message', {
          text: data.message,
          type: 'success'
        });
        app.user = data.data.user;
        axios__WEBPACK_IMPORTED_MODULE_0___default.a.defaults.headers.common['Authorization'] = 'Bearer ' + data.data.access_token;
        if (_this.type === 'api') _this.$router.push({
          name: 'courses-index'
        });else _this.$router.push({
          name: 'students-my-courses'
        });
      })["catch"](function (error) {
        if (error.response && error.response.status === 422) {
          _this.errors = error.response.data.errors;
        }

        bus.$emit('flash-message', {
          text: error.message,
          type: 'error'
        });
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/auth/register.vue?vue&type=style&index=0&id=6c013814&scoped=true&lang=css&":
/*!****************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--7-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/auth/register.vue?vue&type=style&index=0&id=6c013814&scoped=true&lang=css& ***!
  \****************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, ".fade-enter-active > *[data-v-6c013814],\n.fade-leave-active > *[data-v-6c013814] {\n  transition-duration: 200ms;\n  transition-property: opacity, transform;\n  transition-timing-function: cubic-bezier(0.6, 0.15, 0.35, 0.8);\n}\n.fade-enter > *[data-v-6c013814],\n.fade-leave-to > *[data-v-6c013814] {\n  opacity: 0;\n  transform: translateY(40px);\n}\n.fade-enter-active > *[data-v-6c013814]:nth-child(2) {\n  transition-delay: 100ms;\n}\n.fade-enter-active > *[data-v-6c013814]:nth-child(3) {\n  transition-delay: 200ms;\n}\n.fade-leave-active > *[data-v-6c013814]:nth-child(1) {\n  transition-delay: 200ms;\n}\n.fade-leave-active > *[data-v-6c013814]:nth-child(2) {\n  transition-delay: 100ms;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/auth/register.vue?vue&type=style&index=0&id=6c013814&scoped=true&lang=css&":
/*!********************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader??ref--7-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--7-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/auth/register.vue?vue&type=style&index=0&id=6c013814&scoped=true&lang=css& ***!
  \********************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js */ "./node_modules/style-loader/dist/runtime/injectStylesIntoStyleTag.js");
/* harmony import */ var _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _node_modules_css_loader_index_js_ref_7_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_vue_loader_lib_index_js_vue_loader_options_register_vue_vue_type_style_index_0_id_6c013814_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--7-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--7-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./register.vue?vue&type=style&index=0&id=6c013814&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/auth/register.vue?vue&type=style&index=0&id=6c013814&scoped=true&lang=css&");
/* harmony import */ var _node_modules_css_loader_index_js_ref_7_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_vue_loader_lib_index_js_vue_loader_options_register_vue_vue_type_style_index_0_id_6c013814_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_node_modules_css_loader_index_js_ref_7_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_vue_loader_lib_index_js_vue_loader_options_register_vue_vue_type_style_index_0_id_6c013814_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1__);

            

var options = {};

options.insert = "head";
options.singleton = false;

var update = _node_modules_style_loader_dist_runtime_injectStylesIntoStyleTag_js__WEBPACK_IMPORTED_MODULE_0___default()(_node_modules_css_loader_index_js_ref_7_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_vue_loader_lib_index_js_vue_loader_options_register_vue_vue_type_style_index_0_id_6c013814_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1___default.a, options);



/* harmony default export */ __webpack_exports__["default"] = (_node_modules_css_loader_index_js_ref_7_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_vue_loader_lib_index_js_vue_loader_options_register_vue_vue_type_style_index_0_id_6c013814_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_1___default.a.locals || {});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/auth/register.vue?vue&type=template&id=6c013814&scoped=true&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/auth/register.vue?vue&type=template&id=6c013814&scoped=true& ***!
  \*************************************************************************************************************************************************************************************************************************/
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
  return _c("div", { staticClass: "w-full" }, [
    _c(
      "div",
      {
        staticClass:
          "min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8",
      },
      [
        _c("div", { staticClass: "max-w-md w-full space-y-8" }, [
          _c("div", { staticClass: "text-center" }, [
            _c(
              "div",
              {
                staticClass:
                  "flex-1 font-medium h-16 inline-flex items-center px-4 text-center text-xl",
              },
              [
                _c(
                  "svg",
                  {
                    staticClass: "h-24 w-24",
                    staticStyle: { transform: "rotate(180deg)" },
                    attrs: {
                      viewBox: "0 0 40 40",
                      fill: "none",
                      xmlns: "http://www.w3.org/2000/svg",
                    },
                  },
                  [
                    _c("path", {
                      attrs: {
                        "fill-rule": "evenodd",
                        "clip-rule": "evenodd",
                        d: "M4.345 9h10.55L9.618 20 4.345 9zm21.099 0h10.55l-5.276 11-5.274-11z",
                        fill: "#E9F9FD",
                        "fill-opacity": ".1",
                      },
                    }),
                    _vm._v(" "),
                    _c("path", {
                      attrs: {
                        "fill-rule": "evenodd",
                        "clip-rule": "evenodd",
                        d: "M9.62 20h10.549l-5.275 11L9.62 20z",
                        fill: "#25C4F2",
                        "fill-opacity": ".22",
                      },
                    }),
                    _vm._v(" "),
                    _c("path", {
                      attrs: {
                        "fill-rule": "evenodd",
                        "clip-rule": "evenodd",
                        d: "M20.169 20h10.55l-5.275 11-5.275-11z",
                        fill: "#25C4F2",
                        "fill-opacity": ".2",
                      },
                    }),
                    _vm._v(" "),
                    _c("path", {
                      attrs: {
                        "fill-rule": "evenodd",
                        "clip-rule": "evenodd",
                        d: "M20.169 20H9.619l5.275-11 5.275 11z",
                        fill: "#25C4F2",
                        "fill-opacity": ".4",
                      },
                    }),
                    _vm._v(" "),
                    _c("path", {
                      attrs: {
                        "fill-rule": "evenodd",
                        "clip-rule": "evenodd",
                        d: "M30.718 20h-10.55l5.276-11 5.274 11z",
                        fill: "#25C4F2",
                        "fill-opacity": ".4",
                      },
                    }),
                    _vm._v(" "),
                    _c("path", {
                      attrs: {
                        "fill-rule": "evenodd",
                        "clip-rule": "evenodd",
                        d: "M25.444 31h-10.55l5.275-11 5.275 11z",
                        fill: "#25C4F2",
                        "fill-opacity": ".5",
                      },
                    }),
                    _vm._v(" "),
                    _c("path", {
                      attrs: {
                        "fill-rule": "evenodd",
                        "clip-rule": "evenodd",
                        d: "M3.494 8.467A1 1 0 0 1 4.34 8h10.55a1 1 0 0 1 .902.568l4.373 9.12 4.373-9.12A1 1 0 0 1 25.44 8h10.55a1 1 0 0 1 .902 1.432L26.345 31.424a1.001 1.001 0 0 1-.905.576H14.89a1 1 0 0 1-.902-.568l-10.55-22a1 1 0 0 1 .056-.965zm21.95 2.846L29.13 19h-7.372l3.686-7.687zM5.934 10l3.686 7.687L13.306 10H5.933zm8.96 1.313L18.58 19h-7.372l3.686-7.687zM27.032 10l3.686 7.687L34.405 10h-7.373zm-1.588 18.687L21.758 21h7.372l-3.686 7.687zM23.855 30l-3.686-7.687L16.483 30h7.372zm-8.96-1.313L11.207 21h7.372l-3.686 7.687z",
                        fill: "#25C4F2",
                      },
                    }),
                  ]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  {
                    staticClass:
                      "ml-2text-center text-3xl font-extrabold text-gray-900",
                  },
                  [_vm._v("Aktaa Dental")]
                ),
              ]
            ),
            _vm._v(" "),
            _c(
              "h2",
              {
                staticClass:
                  "mt-6 text-center text-3xl font-extrabold text-gray-900",
              },
              [
                _vm._v(
                  "\n                    Register new account\n                "
                ),
              ]
            ),
          ]),
          _vm._v(" "),
          _c(
            "form",
            {
              staticClass: "mt-8 space-y-6",
              attrs: { action: "#", method: "POST" },
              on: {
                submit: function ($event) {
                  $event.preventDefault()
                  return _vm.register()
                },
              },
            },
            [
              _c("nav", { staticClass: "flex flex-col sm:flex-row" }, [
                _c(
                  "label",
                  {
                    class:
                      "text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none w-1/2 text-center duration-75 cursor-pointer border-b-2 " +
                      (_vm.type === "api"
                        ? "text-blue-500 font-bold border-blue-500"
                        : "font-medium border-gray-200"),
                  },
                  [
                    _vm._v(
                      "\n                        Admin\n                        "
                    ),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.type,
                          expression: "type",
                        },
                      ],
                      staticClass: "hidden",
                      attrs: { type: "radio", name: "type", value: "api" },
                      domProps: { checked: _vm._q(_vm.type, "api") },
                      on: {
                        change: function ($event) {
                          _vm.type = "api"
                        },
                      },
                    }),
                  ]
                ),
                _vm._v(" "),
                _c(
                  "label",
                  {
                    class:
                      "text-gray-600 py-4 px-6 block hover:text-blue-500 focus:outline-none w-1/2 text-center duration-75 cursor-pointer border-b-2 " +
                      (_vm.type === "student"
                        ? "text-blue-500 font-bold border-blue-500"
                        : "font-medium border-gray-200"),
                  },
                  [
                    _vm._v(
                      "\n                        Student\n                        "
                    ),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.type,
                          expression: "type",
                        },
                      ],
                      staticClass: "hidden",
                      attrs: { type: "radio", name: "type", value: "student" },
                      domProps: { checked: _vm._q(_vm.type, "student") },
                      on: {
                        change: function ($event) {
                          _vm.type = "student"
                        },
                      },
                    }),
                  ]
                ),
              ]),
              _vm._v(" "),
              _c(
                "transition",
                {
                  attrs: {
                    name: "fade",
                    mode: "out-in",
                    appear: "",
                    duration: 500,
                  },
                },
                [
                  _vm.type === "api"
                    ? _c(
                        "div",
                        {
                          key: _vm.type + "-api",
                          staticClass: "rounded-md shadow-sm -space-y-px",
                        },
                        [
                          _c("div", [
                            _c(
                              "label",
                              {
                                staticClass: "sr-only",
                                attrs: { for: "username" },
                              },
                              [_vm._v("Username")]
                            ),
                            _vm._v(" "),
                            _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.username,
                                  expression: "username",
                                },
                              ],
                              class:
                                "appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm " +
                                (_vm.errors && _vm.errors.username
                                  ? "border-red-500"
                                  : ""),
                              attrs: {
                                id: "username",
                                name: "username",
                                type: "text",
                                autocomplete: "username",
                                placeholder: "Username",
                              },
                              domProps: { value: _vm.username },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.username = $event.target.value
                                },
                              },
                            }),
                            _vm._v(" "),
                            _vm.errors && _vm.errors.username
                              ? _c(
                                  "small",
                                  {
                                    staticClass:
                                      "text-red-600 text-xs text-right block",
                                  },
                                  [_vm._v(_vm._s(_vm.errors.username[0]))]
                                )
                              : _vm._e(),
                          ]),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "label",
                              {
                                staticClass: "sr-only",
                                attrs: { for: "name" },
                              },
                              [_vm._v("Name")]
                            ),
                            _vm._v(" "),
                            _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.name,
                                  expression: "name",
                                },
                              ],
                              class:
                                "appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm " +
                                (_vm.errors && _vm.errors.name
                                  ? "border-red-500"
                                  : ""),
                              attrs: {
                                id: "name",
                                name: "name",
                                type: "text",
                                autocomplete: "name",
                                placeholder: "Name",
                              },
                              domProps: { value: _vm.name },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.name = $event.target.value
                                },
                              },
                            }),
                            _vm._v(" "),
                            _vm.errors && _vm.errors.name
                              ? _c(
                                  "small",
                                  {
                                    staticClass:
                                      "text-red-600 text-xs text-right block",
                                  },
                                  [_vm._v(_vm._s(_vm.errors.name[0]))]
                                )
                              : _vm._e(),
                          ]),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "label",
                              {
                                staticClass: "sr-only",
                                attrs: { for: "email-address" },
                              },
                              [_vm._v("Email address")]
                            ),
                            _vm._v(" "),
                            _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.email,
                                  expression: "email",
                                },
                              ],
                              class:
                                "appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm " +
                                (_vm.errors && _vm.errors.email
                                  ? "border-red-500"
                                  : ""),
                              attrs: {
                                id: "email-address",
                                name: "email",
                                type: "email",
                                autocomplete: "email",
                                placeholder: "Email address",
                              },
                              domProps: { value: _vm.email },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.email = $event.target.value
                                },
                              },
                            }),
                            _vm._v(" "),
                            _vm.errors && _vm.errors.email
                              ? _c(
                                  "small",
                                  {
                                    staticClass:
                                      "text-red-600 text-xs text-right block",
                                  },
                                  [_vm._v(_vm._s(_vm.errors.email[0]))]
                                )
                              : _vm._e(),
                          ]),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "label",
                              {
                                staticClass: "sr-only",
                                attrs: { for: "password" },
                              },
                              [_vm._v("Password")]
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
                              class:
                                "appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm " +
                                (_vm.errors && _vm.errors.password
                                  ? "border-red-500"
                                  : ""),
                              attrs: {
                                id: "password",
                                name: "password",
                                type: "password",
                                autocomplete: "current-password",
                                placeholder: "Password",
                              },
                              domProps: { value: _vm.password },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.password = $event.target.value
                                },
                              },
                            }),
                            _vm._v(" "),
                            _vm.errors && _vm.errors.password
                              ? _c(
                                  "small",
                                  {
                                    staticClass:
                                      "text-red-600 text-xs text-right block",
                                  },
                                  [_vm._v(_vm._s(_vm.errors.password[0]))]
                                )
                              : _vm._e(),
                          ]),
                        ]
                      )
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.type === "student"
                    ? _c(
                        "div",
                        {
                          key: _vm.type + "-student",
                          staticClass: "rounded-md shadow-sm -space-y-px",
                        },
                        [
                          _c("div", [
                            _c(
                              "label",
                              {
                                staticClass: "sr-only",
                                attrs: { for: "username-student" },
                              },
                              [_vm._v("Username")]
                            ),
                            _vm._v(" "),
                            _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.username,
                                  expression: "username",
                                },
                              ],
                              class:
                                "appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm " +
                                (_vm.errors && _vm.errors.username
                                  ? "border-red-500"
                                  : ""),
                              attrs: {
                                id: "username-student",
                                name: "username",
                                type: "text",
                                autocomplete: "username",
                                placeholder: "Username",
                              },
                              domProps: { value: _vm.username },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.username = $event.target.value
                                },
                              },
                            }),
                            _vm._v(" "),
                            _vm.errors && _vm.errors.username
                              ? _c(
                                  "small",
                                  {
                                    staticClass:
                                      "text-red-600 text-xs text-right block",
                                  },
                                  [_vm._v(_vm._s(_vm.errors.username[0]))]
                                )
                              : _vm._e(),
                          ]),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "label",
                              {
                                staticClass: "sr-only",
                                attrs: { for: "firstName" },
                              },
                              [_vm._v("First Name")]
                            ),
                            _vm._v(" "),
                            _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.firstName,
                                  expression: "firstName",
                                },
                              ],
                              class:
                                "appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm " +
                                (_vm.errors && _vm.errors.first_name
                                  ? "border-red-500"
                                  : ""),
                              attrs: {
                                id: "firstName",
                                name: "firstName",
                                type: "text",
                                autocomplete: "firstName",
                                placeholder: "First Name",
                              },
                              domProps: { value: _vm.firstName },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.firstName = $event.target.value
                                },
                              },
                            }),
                            _vm._v(" "),
                            _vm.errors && _vm.errors.first_name
                              ? _c(
                                  "small",
                                  {
                                    staticClass:
                                      "text-red-600 text-xs text-right block",
                                  },
                                  [_vm._v(_vm._s(_vm.errors.first_name[0]))]
                                )
                              : _vm._e(),
                          ]),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "label",
                              {
                                staticClass: "sr-only",
                                attrs: { for: "lastName" },
                              },
                              [_vm._v("Last Name")]
                            ),
                            _vm._v(" "),
                            _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.lastName,
                                  expression: "lastName",
                                },
                              ],
                              class:
                                "appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm " +
                                (_vm.errors && _vm.errors.last_name
                                  ? "border-red-500"
                                  : ""),
                              attrs: {
                                id: "lastName",
                                name: "lastName",
                                type: "text",
                                autocomplete: "lastName",
                                placeholder: "Last Name",
                              },
                              domProps: { value: _vm.lastName },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.lastName = $event.target.value
                                },
                              },
                            }),
                            _vm._v(" "),
                            _vm.errors && _vm.errors.last_name
                              ? _c(
                                  "small",
                                  {
                                    staticClass:
                                      "text-red-600 text-xs text-right block",
                                  },
                                  [_vm._v(_vm._s(_vm.errors.last_name[0]))]
                                )
                              : _vm._e(),
                          ]),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "label",
                              {
                                staticClass: "sr-only",
                                attrs: { for: "email-address-student" },
                              },
                              [_vm._v("Email address")]
                            ),
                            _vm._v(" "),
                            _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.email,
                                  expression: "email",
                                },
                              ],
                              class:
                                "appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm " +
                                (_vm.errors && _vm.errors.email
                                  ? "border-red-500"
                                  : ""),
                              attrs: {
                                id: "email-address-student",
                                name: "email",
                                type: "email",
                                autocomplete: "email",
                                placeholder: "Email address",
                              },
                              domProps: { value: _vm.email },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.email = $event.target.value
                                },
                              },
                            }),
                            _vm._v(" "),
                            _vm.errors && _vm.errors.email
                              ? _c(
                                  "small",
                                  {
                                    staticClass:
                                      "text-red-600 text-xs text-right block",
                                  },
                                  [_vm._v(_vm._s(_vm.errors.email[0]))]
                                )
                              : _vm._e(),
                          ]),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "label",
                              {
                                staticClass: "sr-only",
                                attrs: { for: "reg_year-student" },
                              },
                              [_vm._v("Reg Year")]
                            ),
                            _vm._v(" "),
                            _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.reg_year,
                                  expression: "reg_year",
                                },
                              ],
                              class:
                                "appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm " +
                                (_vm.errors && _vm.errors.reg_year
                                  ? "border-red-500"
                                  : ""),
                              attrs: {
                                id: "reg_year-student",
                                name: "email",
                                type: "number",
                                autocomplete: "reg_year",
                                placeholder: "Reg Year",
                              },
                              domProps: { value: _vm.reg_year },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.reg_year = $event.target.value
                                },
                              },
                            }),
                            _vm._v(" "),
                            _vm.errors && _vm.errors.reg_year
                              ? _c(
                                  "small",
                                  {
                                    staticClass:
                                      "text-red-600 text-xs text-right block",
                                  },
                                  [_vm._v(_vm._s(_vm.errors.reg_year[0]))]
                                )
                              : _vm._e(),
                          ]),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "label",
                              {
                                staticClass: "sr-only",
                                attrs: { for: "address-student" },
                              },
                              [_vm._v("Address")]
                            ),
                            _vm._v(" "),
                            _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.address,
                                  expression: "address",
                                },
                              ],
                              class:
                                "appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm " +
                                (_vm.errors && _vm.errors.address
                                  ? "border-red-500"
                                  : ""),
                              attrs: {
                                id: "address-student",
                                name: "address",
                                type: "text",
                                autocomplete: "address",
                                placeholder: "Address",
                              },
                              domProps: { value: _vm.address },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.address = $event.target.value
                                },
                              },
                            }),
                            _vm._v(" "),
                            _vm.errors && _vm.errors.address
                              ? _c(
                                  "small",
                                  {
                                    staticClass:
                                      "text-red-600 text-xs text-right block",
                                  },
                                  [_vm._v(_vm._s(_vm.errors.address[0]))]
                                )
                              : _vm._e(),
                          ]),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "label",
                              {
                                staticClass: "sr-only",
                                attrs: { for: "mobile_number-student" },
                              },
                              [_vm._v("Mobile Number")]
                            ),
                            _vm._v(" "),
                            _c("input", {
                              directives: [
                                {
                                  name: "model",
                                  rawName: "v-model",
                                  value: _vm.mobile_number,
                                  expression: "mobile_number",
                                },
                              ],
                              class:
                                "appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm " +
                                (_vm.errors && _vm.errors.mobile_number
                                  ? "border-red-500"
                                  : ""),
                              attrs: {
                                id: "mobile_number-student",
                                name: "mobile_number",
                                type: "tel",
                                autocomplete: "mobile_number",
                                placeholder: "Mobile Number",
                              },
                              domProps: { value: _vm.mobile_number },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.mobile_number = $event.target.value
                                },
                              },
                            }),
                            _vm._v(" "),
                            _vm.errors && _vm.errors.mobile_number
                              ? _c(
                                  "small",
                                  {
                                    staticClass:
                                      "text-red-600 text-xs text-right block",
                                  },
                                  [_vm._v(_vm._s(_vm.errors.mobile_number[0]))]
                                )
                              : _vm._e(),
                          ]),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "label",
                              {
                                staticClass: "sr-only",
                                attrs: { for: "password-student" },
                              },
                              [_vm._v("Password")]
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
                              class:
                                "appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm " +
                                (_vm.errors && _vm.errors.password
                                  ? "border-red-500"
                                  : ""),
                              attrs: {
                                id: "password-student",
                                name: "password",
                                type: "password",
                                autocomplete: "current-password",
                                placeholder: "Password",
                              },
                              domProps: { value: _vm.password },
                              on: {
                                input: function ($event) {
                                  if ($event.target.composing) {
                                    return
                                  }
                                  _vm.password = $event.target.value
                                },
                              },
                            }),
                            _vm._v(" "),
                            _vm.errors && _vm.errors.password
                              ? _c(
                                  "small",
                                  {
                                    staticClass:
                                      "text-red-600 text-xs text-right block",
                                  },
                                  [_vm._v(_vm._s(_vm.errors.password[0]))]
                                )
                              : _vm._e(),
                          ]),
                          _vm._v(" "),
                          _c("div", [
                            _c(
                              "label",
                              {
                                staticClass:
                                  "block text-sm font-medium text-gray-700 mt-2",
                              },
                              [_vm._v("Gender")]
                            ),
                            _vm._v(" "),
                            _c("div", { staticClass: "grdi grid-cols-2" }, [
                              _c(
                                "label",
                                { staticClass: "inline-flex items-center" },
                                [
                                  _c("input", {
                                    directives: [
                                      {
                                        name: "model",
                                        rawName: "v-model",
                                        value: _vm.gender,
                                        expression: "gender",
                                      },
                                    ],
                                    staticClass:
                                      "form-radio h-5 w-5 text-indigo-600",
                                    attrs: {
                                      type: "radio",
                                      name: "gender",
                                      value: "male",
                                    },
                                    domProps: {
                                      checked: _vm._q(_vm.gender, "male"),
                                    },
                                    on: {
                                      change: function ($event) {
                                        _vm.gender = "male"
                                      },
                                    },
                                  }),
                                  _c(
                                    "span",
                                    { staticClass: "ml-2 text-gray-700" },
                                    [_vm._v("Male")]
                                  ),
                                ]
                              ),
                              _vm._v(" "),
                              _c(
                                "label",
                                { staticClass: "inline-flex items-center" },
                                [
                                  _c("input", {
                                    directives: [
                                      {
                                        name: "model",
                                        rawName: "v-model",
                                        value: _vm.gender,
                                        expression: "gender",
                                      },
                                    ],
                                    staticClass:
                                      "form-radio h-5 w-5 text-indigo-600",
                                    attrs: {
                                      type: "radio",
                                      name: "gender",
                                      value: "female",
                                    },
                                    domProps: {
                                      checked: _vm._q(_vm.gender, "female"),
                                    },
                                    on: {
                                      change: function ($event) {
                                        _vm.gender = "female"
                                      },
                                    },
                                  }),
                                  _c(
                                    "span",
                                    { staticClass: "ml-2 text-gray-700" },
                                    [_vm._v("Female")]
                                  ),
                                ]
                              ),
                            ]),
                            _vm._v(" "),
                            _vm.errors && _vm.errors.gender
                              ? _c(
                                  "small",
                                  {
                                    staticClass:
                                      "text-red-600 text-xs text-right block",
                                  },
                                  [_vm._v(_vm._s(_vm.errors.gender[0]))]
                                )
                              : _vm._e(),
                          ]),
                        ]
                      )
                    : _vm._e(),
                ]
              ),
              _vm._v(" "),
              _c("div", [
                _c(
                  "button",
                  {
                    staticClass:
                      "group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500",
                    attrs: { type: "submit" },
                  },
                  [
                    _c(
                      "span",
                      {
                        staticClass:
                          "absolute left-0 inset-y-0 flex items-center pl-3",
                      },
                      [
                        _c(
                          "svg",
                          {
                            staticClass:
                              "h-5 w-5 text-indigo-500 group-hover:text-indigo-400",
                            attrs: {
                              xmlns: "http://www.w3.org/2000/svg",
                              viewBox: "0 0 20 20",
                              fill: "currentColor",
                              "aria-hidden": "true",
                            },
                          },
                          [
                            _c("path", {
                              attrs: {
                                "fill-rule": "evenodd",
                                d: "M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z",
                                "clip-rule": "evenodd",
                              },
                            }),
                          ]
                        ),
                      ]
                    ),
                    _vm._v(
                      "\n                        Sign up\n                    "
                    ),
                  ]
                ),
              ]),
            ],
            1
          ),
        ]),
      ]
    ),
  ])
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/screens/auth/register.vue":
/*!************************************************!*\
  !*** ./resources/js/screens/auth/register.vue ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _register_vue_vue_type_template_id_6c013814_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./register.vue?vue&type=template&id=6c013814&scoped=true& */ "./resources/js/screens/auth/register.vue?vue&type=template&id=6c013814&scoped=true&");
/* harmony import */ var _register_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./register.vue?vue&type=script&lang=js& */ "./resources/js/screens/auth/register.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _register_vue_vue_type_style_index_0_id_6c013814_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./register.vue?vue&type=style&index=0&id=6c013814&scoped=true&lang=css& */ "./resources/js/screens/auth/register.vue?vue&type=style&index=0&id=6c013814&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _register_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _register_vue_vue_type_template_id_6c013814_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _register_vue_vue_type_template_id_6c013814_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "6c013814",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/screens/auth/register.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/screens/auth/register.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/screens/auth/register.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_register_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./register.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/auth/register.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_register_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/screens/auth/register.vue?vue&type=style&index=0&id=6c013814&scoped=true&lang=css&":
/*!*********************************************************************************************************!*\
  !*** ./resources/js/screens/auth/register.vue?vue&type=style&index=0&id=6c013814&scoped=true&lang=css& ***!
  \*********************************************************************************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_dist_cjs_js_node_modules_css_loader_index_js_ref_7_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_7_2_node_modules_vue_loader_lib_index_js_vue_loader_options_register_vue_vue_type_style_index_0_id_6c013814_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader/dist/cjs.js!../../../../node_modules/css-loader??ref--7-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--7-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./register.vue?vue&type=style&index=0&id=6c013814&scoped=true&lang=css& */ "./node_modules/style-loader/dist/cjs.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/auth/register.vue?vue&type=style&index=0&id=6c013814&scoped=true&lang=css&");
/* empty/unused harmony star reexport */

/***/ }),

/***/ "./resources/js/screens/auth/register.vue?vue&type=template&id=6c013814&scoped=true&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/screens/auth/register.vue?vue&type=template&id=6c013814&scoped=true& ***!
  \*******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_register_vue_vue_type_template_id_6c013814_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./register.vue?vue&type=template&id=6c013814&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/auth/register.vue?vue&type=template&id=6c013814&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_register_vue_vue_type_template_id_6c013814_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_register_vue_vue_type_template_id_6c013814_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);