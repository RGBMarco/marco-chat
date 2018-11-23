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
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
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
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 149);
/******/ })
/************************************************************************/
/******/ ({

/***/ 149:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(150);


/***/ }),

/***/ 150:
/***/ (function(module, exports) {

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var websocket = new WebSocket("ws://localhost:13000");
websocket.onopen = function (event) {
    requestInit();
};
websocket.onmessage = function (event) {
    requestDebug("接收到网络信息!");
    postMessage(event.data);
};
websocket.onclose = function (event) {
    // postMessage("close!");
};
self.onmessage = function (event) {
    var request = JSON.parse(event.data);
    if (!isBaseRequest(request)) {
        return;
    }
    switch (String(request.request)) {
        case "init":
            handleInitMessage(request);
            break;
        case "message":
            handleSenderMessage(request);
            break;
        default:
            return;
    }
};

function isBaseRequest(request) {
    return !(_typeof(request.reuqest) === undefined) && !(_typeof(request.data) === undefined);
}
function handleInitMessage(request) {
    websocket.send(JSON.stringify(request));
    requestDebug("已发送初始化消息!");
}

function requestInit() {
    var request = {
        request: "init",
        data: {}
    };
    postMessage(JSON.stringify(request));
}
function requestDebug(message) {
    var request = {
        request: "debug",
        data: {
            message: message
        }
    };
    postMessage(JSON.stringify(request));
}

function handleSenderMessage(request) {
    websocket.send(JSON.stringify(request));
    requestDebug("已发送发送者消息!");
}

/***/ })

/******/ });