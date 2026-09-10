// webpack-loaders/filename-banner-CSS.js
const path = require('path');

module.exports = function (source) {
    const filename = path.basename(this.resourcePath);
    // el "!" es importante — lo explico abajo
    return `/*! esto viene del archivo ${filename} */\n${source}`;
};