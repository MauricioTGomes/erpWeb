var mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js/app.js');



mix.combine([
    'resources/assets/js/mascaraCampos.min.js',
    'resources/assets/js/index.js',
    'resources/assets/js/mascaraCampos.js',
    'resources/assets/js/calculaParcela.js',
    'resources/assets/js/plugins.js',
    'resources/assets/js/Funcoes.js'
], 'public/js/main.js');

mix.copy('resources/assets/sass/select2.css', 'public/css/select2.css');
mix.copy('resources/assets/js/select2.js', 'public/js/select2.js');