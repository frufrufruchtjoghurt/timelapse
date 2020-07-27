/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/table.js":
/*!*******************************!*\
  !*** ./resources/js/table.js ***!
  \*******************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function search(table, filter, search_list) {
  var table_body = table.tBodies[0];
  var tr = Array.from(table_body.querySelectorAll("tr"));

  var _loop = function _loop(i) {
    var td = tr[i].querySelectorAll("td");
    var txt_value = "";
    search_list.forEach(function (item) {
      txt_value += td[item].textContent || td[item].innerText;
    });

    if (txt_value.toUpperCase().indexOf(filter) > -1) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }
  };

  for (var i = 0; i < tr.length; ++i) {
    _loop(i);
  }
}

document.querySelectorAll(".search-input").forEach(function (input_field) {
  input_field.addEventListener("keyup", function () {
    var table_element = document.querySelector("table");
    var filter = input_field.value.toUpperCase();
    var search_cols = Array();
    table_element.querySelectorAll(".searchable").forEach(function (col) {
      search_cols.push(Array.prototype.indexOf.call(col.parentElement.children, col));
    });
    search(table_element, filter, search_cols);
  });
});

function sortTable(table, column) {
  var asc = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : true;
  var dir_mod = asc ? 1 : -1;
  var table_body = table.tBodies[0];
  var rows = Array.from(table_body.querySelectorAll("tr"));
  var sorted_rows = rows.sort(function (a, b) {
    var a_col_text = a.querySelectorAll("td")[column].textContent.trim().toLowerCase();
    var b_col_text = b.querySelectorAll("td")[column].textContent.trim().toLowerCase();
    return a_col_text > b_col_text ? 1 * dir_mod : -1 * dir_mod;
  });

  while (table_body.firstChild) {
    table_body.removeChild(table_body.firstChild);
  }

  table_body.append.apply(table_body, _toConsumableArray(sorted_rows));
  table.querySelectorAll("th").forEach(function (th) {
    return th.classList.remove("th-sort-asc", "th-sort-desc");
  });
  table.querySelector("th:nth-child(".concat(column + 1, ")")).classList.toggle("th-sort-asc", asc);
  table.querySelector("th:nth-child(".concat(column + 1, ")")).classList.toggle("th-sort-desc", !asc);
}

document.querySelectorAll(".table-sortable th").forEach(function (header_cell) {
  if (!header_cell.classList.contains("no-sort")) {
    header_cell.addEventListener("click", function () {
      var table_element = header_cell.parentElement.parentElement.parentElement;
      var header_index = Array.prototype.indexOf.call(header_cell.parentElement.children, header_cell);
      var is_ascending = header_cell.classList.contains("th-sort-asc");
      sortTable(table_element, header_index, !is_ascending);
    });
  }
});
document.querySelectorAll(".table-sort-asc .sort-by").forEach(function (sort_base) {
  var table_element = sort_base.parentElement;

  while (table_element.tagName.toLowerCase() != "table") {
    table_element = table_element.parentElement;
  }

  var header_index = Array.prototype.indexOf.call(sort_base.parentElement.children, sort_base);
  sortTable(table_element, header_index, true);
});

/***/ }),

/***/ 1:
/*!*************************************!*\
  !*** multi ./resources/js/table.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/pi/timelapse/resources/js/table.js */"./resources/js/table.js");


/***/ })

/******/ });