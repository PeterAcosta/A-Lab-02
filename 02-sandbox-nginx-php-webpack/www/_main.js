/*
 * ATTENTION: The "eval" devtool has been used (maybe by default in mode: "development").
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./sources/JS/00_Base.js"
/*!*******************************!*\
  !*** ./sources/JS/00_Base.js ***!
  \*******************************/
() {

eval("{\n\n\nfunction getFontSizeBase() {\n    var elemento = document.querySelector('html');\n    // Obtener el valor de una propiedad CSS\n    var valorPropiedad = window.getComputedStyle(elemento).getPropertyValue(\"font-size\");\n\n    var primerRenglon  = \"El valor de la propiedad base font-size:\" + valorPropiedad;\n    var segundoRenglon = \"1 rem = \"+valorPropiedad;\n    \n    var miParrafo = document.getElementById(\"root-data\");\n    miParrafo.innerHTML = primerRenglon + \"<br>\" + segundoRenglon;\n\n\n    return{valorPropiedad}\n}\n\nfunction obtenerMedidasMonitor() {\n    const ancho = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;\n    const alto = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;\n    return { ancho, alto };\n}\n\n\n\n\nfunction writeValues(){\n    const medidasMonitor = obtenerMedidasMonitor();\n    const fontSizeBase   = getFontSizeBase();\n\n    var line_1 = `Ancho: ${medidasMonitor.ancho}px, Alto: ${medidasMonitor.alto}px`;\n    var line_2 = \"El valor de la propiedad base font-size:\" + fontSizeBase.valorPropiedad;\n    var line_3 = \"1 rem = \" + fontSizeBase.valorPropiedad;\n    var parrafo = line_1 + \"<br>\" + line_2 + \"<br>\" + line_3\n\n    var miParrafo = document.getElementById(\"root-data\");\n    miParrafo.innerHTML = parrafo;\n\n    console.log(parrafo);\n\n\n}\n\n\n//# sourceURL=webpack://workdir/./sources/JS/00_Base.js?\n}");

/***/ },

/***/ "./sources/JS/01_javascript.js"
/*!*************************************!*\
  !*** ./sources/JS/01_javascript.js ***!
  \*************************************/
() {

eval("{// Obtener la fecha y hora actual\nvar fechaHoraActual = new Date();\n\n// Obtener la hora de la fecha y hora actual\nvar hora = fechaHoraActual.getHours();\n\n// Mostrar el valor de la hora en la consola\nconsole.log(\"La hora actual es \" + hora + \" horas.\");\n\n\n// Obtener la fecha actual\nvar fechaActual = new Date();\n\n// Obtener el día de la fecha actual\nvar dia = fechaActual.getDate();\n\n// Mostrar el valor del día en la consola\nconsole.log(\"Hoy es el día \" + dia);\n\n\n//# sourceURL=webpack://workdir/./sources/JS/01_javascript.js?\n}");

/***/ }

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval devtool is used.
/******/ 	__webpack_modules__["./sources/JS/00_Base.js"]();
/******/ 	let __webpack_exports__ = {};
/******/ 	__webpack_modules__["./sources/JS/01_javascript.js"]();
/******/ 	
/******/ })()
;