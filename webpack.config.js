const path = require('path');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
const CopyPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    mode: 'production',
    entry: {
        app: './assets/js/app.js',
        form: './assets/js/form.js',
        rating: './assets/js/rating.js'
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'public/build/'),
    },
    module: {
        rules: [
            {
                test: /\.s[ac]ss$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ]
            },
            {
                test: /\.(ttf|woff2)$/,
                type: 'asset/resource',
                generator: {
                    publicPath: '/build/fonts/',
                    outputPath: 'fonts/'
                }
            },
        ]
    },
    plugins: [
        new CleanWebpackPlugin(),
        new CopyPlugin({
            patterns: [
                { from: 'assets/images', to: 'images' }
            ]
        }),
        new MiniCssExtractPlugin({
            filename: '[name].css',
            chunkFilename: '[id].css'
        })
    ]
};
