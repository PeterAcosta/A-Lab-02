const path = require('path');
const glob = require('glob');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const { minify } = require('html-minifier-terser');

const isProd = process.env.NODE_ENV === 'production';
const distPath = 'www';

// Orden alfabético — importa si tus estilos/scripts dependen del orden
const jsFiles = glob.sync('./sources/JS/*.js').sort().map(f => './' + f);
const cssFiles = glob.sync('./sources/CSS/*.css').sort().map(f => './' + f);


console.log('JS encontrados:', jsFiles);
console.log('CSS encontrados:', cssFiles);


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

		new CopyWebpackPlugin({
			patterns: [
				{
					from: 'sources/HTML/*.html',
					to: 'HTML/[name][ext]', // -> www/HTML/archivo.html
					transform: {
						transformer: async (content) => {
							return await minify(content.toString(), {
								collapseWhitespace: true,
								removeComments: true,
								minifyCSS: true,
								minifyJS: true,
								removeRedundantAttributes: true,
								removeScriptTypeAttributes: true,
								removeStyleLinkTypeAttributes: true,
								useShortDoctype: true,
							});
						},
					},
				},
			],
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