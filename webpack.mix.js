let mix = require('laravel-mix');

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


mix.webpackConfig({
    resolve: {
        alias: {
            jquery: "jquery/src/jquery",
            'popper.js': "popper.js/dist/umd/popper.js"
        }
    }
});

mix.extract(['popper.js', 'bootstrap', 'axios', 'lodash', 'vue'])
        .autoload({
            'jquery': ['$', 'jQuery', 'jquery', 'window.jQuery'],
            'popper.js': ['Popper', 'window.Popper']
        });

mix.sass('resources/assets/sass/app.scss', 'public/css');
mix.js('resources/assets/js/app.js', 'public/js')
mix.js('resources/assets/js/components/pack/packs.js', 'public/js')
mix.js('resources/assets/js/components/slots/slots.js', 'public/js');

mix.js('resources/assets/js/widgets/rates-selector.js', 'public/js')

if (mix.config.inProduction) {
    mix.version();
}
