const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");

const basePath = __dirname ;
const distPath = "www"



module.exports = {

	// mode: "development"
    mode: "production",
    
	watch: true, // <-- Habilita el modo watcher continuo
	cache: { type: 'filesystem' },

    entry: './sources/JS/__entrypoint.js',
    output: {
        path: path.resolve(__dirname, distPath),
        filename: '_main.js',
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: '_main.css' // <-- Genera el archivo CSS físico en www/
        }),
    ],
    module: {
        rules: [
            {
                test: /\.css$/i,
                use: [
                    MiniCssExtractPlugin.loader, // <-- Extrae a archivo .css
                    {
                        loader: "css-loader",
                        options: {
                            url: false // <-- Evita el error "Can't resolve 'fonts/...'"
                        }
                    }
                ],
            },
        ],
    },
    optimization: {
        minimizer: [
            `...`,
            new CssMinimizerPlugin(),
        ],
    },
};

