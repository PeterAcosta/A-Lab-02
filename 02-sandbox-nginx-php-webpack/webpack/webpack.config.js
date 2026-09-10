const path = require('path');
const glob = require('glob');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');

const isProd = process.env.NODE_ENV === 'production';
const distPath = 'www';

// Orden alfabético — importa si tus estilos/scripts dependen del orden
const jsFiles = glob.sync('./sources/JS/*.js').sort();
const cssFiles = glob.sync('./sources/CSS/*.css').sort();

module.exports = {

    // mode: isProd ? 'production' : 'development',
    // watch: !isProd,

	// mode: "development"
    mode: "production",
    
	watch: true, // <-- Habilita el modo watcher continuo
	cache: { type: 'filesystem' },

	
    entry: {
        main: jsFiles,     // -> www/_main.js
        styles: cssFiles,  // -> www/_main.css (vía MiniCssExtractPlugin)
    },
    output: {
        path: path.resolve(__dirname, distPath),
        filename: '_[name].js', // genera _main.js
    },
    plugins: [
        new RemoveEmptyScriptsPlugin(), // evita que se genere un _styles.js vacío
        new MiniCssExtractPlugin({
            filename: '_main.css',
        }),
    ],
    module: {
        rules: [
            {
                test: /\.css$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    { loader: 'css-loader', options: { url: false } },
                ],
            },
        ],
    },
    optimization: {
        minimizer: ['...', new CssMinimizerPlugin()],
    },
};