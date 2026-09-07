const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");

const basePath = __dirname ;
const distPath = "www"


module.exports = {
    // mode - modo de funcionamiento
    // mode: "production",
    mode: 'development',
    // watch: true,             // Habilita la vigilancia de archivos

    entry: './sources/JS/__entrypoint.js',     // archivo principal del proyecto

    output: {
        path: path.resolve(__dirname, distPath),
        filename: '_main.js',
    },

    
    
    plugins: [
        /* new HtmlWebpackPlugin({
            minify:true,            // true o false
            scriptLoading:defer     // defer o blocking
        
        }),*/
        new MiniCssExtractPlugin(),
    ],
    


    module: {
        rules: [
        {
            test: /\.css$/i,
            // use: [MiniCssExtractPlugin.loader, "css-loader"],   // prod
            use: ["style-loader", "css-loader"],             // dev
        },
        ],
    },





};
