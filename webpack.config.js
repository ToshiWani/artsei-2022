const path = require('path');
const CopyPlugin = require('copy-webpack-plugin');


module.exports = {
    mode: 'production',
    entry: './src/sass/main.scss',
    output: {
        filename: 'style.js',
        path: path.resolve(__dirname, 'artsei-theme/assets'),
        clean: true,
    },
    devServer: {
        static: [
            {
                directory: path.join(__dirname, 'artsei-theme'),
            },
            {
                //  referencing the mock html files in src

                directory: path.join(__dirname, 'src'),
            },
        ],
        port: 8765,
    },
    plugins: [
        new CopyPlugin({
            patterns: [
                {
                    from: './src/js',
                    to: './js',
                },
            ],
        }),
    ],
    module: {
        rules: [
            {
                test: /\.s[ac]ss$/i,
                use: [
                    'style-loader',
                    'css-loader',
                    'sass-loader',
                ],
            },
        ],
    }
};

