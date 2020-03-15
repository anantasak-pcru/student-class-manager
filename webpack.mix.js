const mix = require('laravel-mix');

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
require('jquery');
mix
    .autoload({
        jquery: ['$', 'window.jQuery', 'jQuery']
    })
    .js('resources/js/app.js', 'public/js/script')
    .js('resources/js/jquery.js', 'public/js/script')
    .js('resources/js/sweetalert.js', 'public/js/script')
    .js('resources/js/alertifyjs.js', 'public/js/script')
    .sass('resources/sass/fontausome.scss', 'public/css/base')
    .sass('resources/sass/sweetalert.scss', 'public/css/base')
    .sass('resources/sass/fomaticui.scss', 'public/css/base')
    .sass('resources/sass/app.scss', 'public/css/base');
