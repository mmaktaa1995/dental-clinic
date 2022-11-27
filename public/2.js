(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[2],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/appointments/index.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/appointments/index.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _fullcalendar_vue__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @fullcalendar/vue */ "./node_modules/@fullcalendar/vue/dist/main.js");
/* harmony import */ var _fullcalendar_daygrid__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @fullcalendar/daygrid */ "./node_modules/@fullcalendar/daygrid/main.js");
/* harmony import */ var _fullcalendar_timegrid__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @fullcalendar/timegrid */ "./node_modules/@fullcalendar/timegrid/main.js");
/* harmony import */ var _fullcalendar_interaction__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @fullcalendar/interaction */ "./node_modules/@fullcalendar/interaction/main.js");
/* harmony import */ var _fullcalendar_core_locales_ar__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @fullcalendar/core/locales/ar */ "./node_modules/@fullcalendar/core/locales/ar.js");
/* harmony import */ var _fullcalendar_core_locales_ar__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_fullcalendar_core_locales_ar__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! axios */ "./node_modules/axios/index.js");
/* harmony import */ var axios__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(axios__WEBPACK_IMPORTED_MODULE_5__);






/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    FullCalendar: _fullcalendar_vue__WEBPACK_IMPORTED_MODULE_0__["default"] // make the <FullCalendar> tag available

  },
  data: function data() {
    var _this = this;

    return {
      events: [],
      calendarOptions: {
        locale: _fullcalendar_core_locales_ar__WEBPACK_IMPORTED_MODULE_4___default.a,
        plugins: [_fullcalendar_daygrid__WEBPACK_IMPORTED_MODULE_1__["default"], _fullcalendar_timegrid__WEBPACK_IMPORTED_MODULE_2__["default"], _fullcalendar_interaction__WEBPACK_IMPORTED_MODULE_3__["default"] // needed for dateClick
        ],
        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        initialView: 'dayGridMonth',
        initialEvents: [],
        // alternatively, use the `events` setting to fetch from a feed
        editable: true,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        weekends: true,
        select: this.handleDateSelect,
        eventClick: this.handleEventClick,
        eventsSet: this.handleEvents
        /* you can update a remote database when these fire:
        eventAdd:
        eventChange:
        eventRemove:
        */
        ,
        events: this.events,
        datesSet: function datesSet(event) {
          var midDate = new Date((event.start.getTime() + event.end.getTime()) / 2);
          var month = "0".concat(midDate.getMonth() + 1);

          _this.getData(+month, midDate.getFullYear());
        }
      },
      currentEvents: [],
      key: 10,
      year: '',
      month: ''
    };
  },
  mounted: function mounted() {
    var self = this;
    bus.$on('appointment-changed', function () {
      self.getData(this.month, this.year);
    });
  },
  methods: {
    getData: function getData(month, year) {
      var _this2 = this;

      if (this.year !== year || this.month !== month) {
        this.year = year;
        this.month = month;
        axios__WEBPACK_IMPORTED_MODULE_5___default.a.get("/api/appointments?year=".concat(year, "&month=").concat(month)).then(function (_ref) {
          var data = _ref.data;
          _this2.events = data.map(function (_) {
            return {
              id: _.id,
              title: _.patient.name,
              description: _.notes,
              className: _.className,
              date: _.date.replace(/.(\d)*Z/g, ''),
              start: _.start.replace(/.(\d)*Z/g, ''),
              end: _.end.replace(/.(\d)*Z/g, '')
            };
          });
          _this2.calendarOptions.events = _this2.events;
        });
      }
    },
    handleWeekendsToggle: function handleWeekendsToggle() {
      this.calendarOptions.weekends = !this.calendarOptions.weekends; // update a property
    },
    handleDateSelect: function handleDateSelect(selectInfo) {
      this.$router.push({
        name: 'appointments-create',
        query: {
          date: selectInfo.startStr
        }
      });
    },
    handleEventClick: function handleEventClick(clickInfo) {
      this.$router.push({
        name: 'appointments-edit',
        params: {
          id: clickInfo.event.id
        }
      });
    },
    handleEvents: function handleEvents(events) {
      this.currentEvents = events;
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/appointments/index.vue?vue&type=template&id=6ec456bd&":
/*!******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/screens/appointments/index.vue?vue&type=template&id=6ec456bd& ***!
  \******************************************************************************************************************************************************************************************************************/
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
    { staticClass: "px-16 py-8" },
    [
      _c(
        "div",
        { staticClass: "demo-app-main" },
        [
          _c("FullCalendar", {
            key: _vm.key,
            ref: "fullCalendar",
            staticClass: "demo-app-calendar",
            attrs: { options: _vm.calendarOptions },
            scopedSlots: _vm._u([
              {
                key: "eventContent",
                fn: function (arg) {
                  return [
                    _c("b", [_vm._v(_vm._s(arg.timeText))]),
                    _vm._v(" "),
                    _c("i", [_vm._v(_vm._s(arg.event.title))]),
                  ]
                },
              },
            ]),
          }),
        ],
        1
      ),
      _vm._v(" "),
      _c("router-view"),
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/screens/appointments/index.vue":
/*!*****************************************************!*\
  !*** ./resources/js/screens/appointments/index.vue ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _index_vue_vue_type_template_id_6ec456bd___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./index.vue?vue&type=template&id=6ec456bd& */ "./resources/js/screens/appointments/index.vue?vue&type=template&id=6ec456bd&");
/* harmony import */ var _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.vue?vue&type=script&lang=js& */ "./resources/js/screens/appointments/index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _index_vue_vue_type_template_id_6ec456bd___WEBPACK_IMPORTED_MODULE_0__["render"],
  _index_vue_vue_type_template_id_6ec456bd___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/screens/appointments/index.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/screens/appointments/index.vue?vue&type=script&lang=js&":
/*!******************************************************************************!*\
  !*** ./resources/js/screens/appointments/index.vue?vue&type=script&lang=js& ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./index.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/appointments/index.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/screens/appointments/index.vue?vue&type=template&id=6ec456bd&":
/*!************************************************************************************!*\
  !*** ./resources/js/screens/appointments/index.vue?vue&type=template&id=6ec456bd& ***!
  \************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_6ec456bd___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./index.vue?vue&type=template&id=6ec456bd& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/screens/appointments/index.vue?vue&type=template&id=6ec456bd&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_6ec456bd___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_index_vue_vue_type_template_id_6ec456bd___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);