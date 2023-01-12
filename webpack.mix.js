const mix = require('laravel-mix');
require('laravel-mix-purgecss');

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

mix
    .scripts([
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.bundle.js',
        'node_modules/clipboard/dist/clipboard.min.js',
        'resources/js/functions.js',
    ], 'public/js/app.js')
    .scripts([
        'node_modules/moment/min/moment.min.js',
        'node_modules/daterangepicker/daterangepicker.js',
        'node_modules/chart.js/dist/chart.min.js',
        'node_modules/svg-pan-zoom/dist/svg-pan-zoom.min.js',
        'node_modules/svgmap/dist/svgMap.min.js'
    ], 'public/js/app.extras.js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/app.rtl.scss', 'public/css')
    .sass('resources/sass/app.dark.scss', 'public/css')
    .sass('resources/sass/app.rtl.dark.scss', 'public/css')
    .purgeCss({
        safelist: {
            greedy: [
                /* DateRangePicker */
                /daterangepicker/,
                /* SVG Map */
                /svgMap/,
                /* Bootstrap */
                /popover/,
                /tooltip/,
                /modal/,
                /fade/,
                /show/,
                /hide/,
                /alert/,
                /badge/,
                /bg/,
                /arrow/,
                /collapse/,
                /collapsing/
            ]
        },
        enabled: true
    });
