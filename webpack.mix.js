const mix = require('laravel-mix');
//const nrwlConfig = require("@nrwl/react/plugins/webpack.js");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sass('resources/sass/app.scss', 'public/css');



/*     module.exports = (config, context) => {
      // first call it so that @nrwl/react plugin adds its configs
      nrwlConfig(config);

      return {
        ...config,
        node: undefined
      };
    }; */
