const path = require('path');

module.exports = {
    entry: './src/index.js',
    output: {
        filename: 'main.js',
        path: path.resolve(__dirname, 'dist'),
    },
    module: {
        rules: [
            {
                test: /\.(scss)$/,
                exclude: /node_modules/,
                use: [
                    // inject CSS to page
                    {loader: 'style-loader'},
                    // translates CSS into CommonJS modules
                    {loader: 'css-loader'},
                    // Run postcss actions
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: function () {
                                    return [
                                        require('autoprefixer')
                                    ];
                                }
                            }
                        }
                    },
                    // compiles Sass to CSS
                    {loader: 'sass-loader'}
                ]
            }
        ]
    },
    mode: 'production'
};