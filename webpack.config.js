const path = require('path');
const CompressionPlugin = require("compression-webpack-plugin");

module.exports = {
  entry: {
      'index': './docs/src/index.js',
  },
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /(node_modules)/,
        use: [
          {
            loader: 'babel-loader',
            options: {
              presets: [
                [
                  "@babel/preset-env",
                  {
                    useBuiltIns: 'usage',
                    corejs: 3
                  },
                ]
              ]
            }
          },
          "import-glob-loader"
        ]
      },
      {
        test: /\.(scss|sass|css)$/i,
        use: [
          "style-loader",
          {
            loader: "css-loader",
            options: {
              url: false,
              importLoaders: 2
            }
          },
          {
            loader: "postcss-loader",
            options: {
              plugins: [
                require("autoprefixer")({
                  grid: true
                }),
                require('css-declaration-sorter')({
                  order: 'alphabetical'
                }),
                require('postcss-sort-media-queries')({
                  sort: 'desktop-first',
                }),
              ]
            }
          },
          "sass-loader",
          "import-glob-loader"
        ]
      },
      {
        test: /\.(ttf|eot|woff|woff2|svg)$/,
        use: [{
            loader: 'file-loader',
            options: {
                name: "[name].[ext]",
                outputPath: './webfonts',
                publicPath: '../webfonts',
            }
        }]
      },
      {
        test: /\.(gif|png|jpg)$/,
        loader: "url-loader"
      }
    ]
  },
  plugins: [
    new CompressionPlugin({
      test: /\.(css)|(js)$/,
      compressionOptions: {
        level: 9
      }
    })
  ],
  resolve: {
    extensions: ['.js', 'jsx', '.webpack.js', '.web.js', '.scss', '.woff', 'woff2', '.ttf', '.eot', '.svg']
  },
  output: {
    path: path.resolve(__dirname, 'docs/compiled'),
    filename: '[name].js'
  }
};