# Name
   
   Project launch file set

# Requirement

* Composer 1.10.1
* npm 6.13.4
* Apache 24
* PHP 7.2

# Installation

Install php-cs-fixer 
 
```bash
composer require --dev friendsofphp/php-cs-fixer
```

Install webpack

```bash
npm i -D webpack webpack-cli webpack-dev-server
```

Install loader and Plugins

```bash
npm i -D babel-loader @babel/core @babel/preset-env core-js@3 style-loader css-loader sass-loader sass node-sass postcss-loader autoprefixer url-loader file-loader @fortawesome/fontawesome-free compression-webpack-plugin css-declaration-sorter import-glob-loader postcss-sort-media-queries babel-polyfill
```

and add to package.json
```bash
  "browserslist": [
    "last 1 version",
    "> 1%",
    "IE 10"
  ]
```

if you use React

```bash
npm i -D @babel/preset-react @babel/register react react-dom
```

and write `presets : ["@babel/react"]` to webpack.config.js



# Author
 
* KyosukeShinmen
* kyosuke.dhl@gmail.com