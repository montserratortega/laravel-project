const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.styles([
    'resources/plantilla/css/font-awesome.min.css',
    'resources/plantilla/css/simple-line-icons.min.css',
    'resources/plantilla/css/style.css'
], 'public/css/plantilla.css')
.scripts([
    'resources/js/jquery.min.js',
    'resources/js/popper.min.js',
    'resources/js/bootstrap.min.js',
    'resources/js/Chart.min.js',
    'resources/js/pace.min.js',
    'resources/js/template.js'
], 'public/js/plantilla.js')
.js(['resources/js/app.js'],'public/js/app.js').vue();
