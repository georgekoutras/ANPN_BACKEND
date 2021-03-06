const mix = require('laravel-mix');
var path = require('path');
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

//mix.js('resources/js/app.js', 'public/js')
//    .postCss('resources/css/app.css', 'public/css', [
        //
//    ]);

mix.webpackConfig(webpack => {
    return {
        plugins: [
            new webpack.ProvidePlugin({
                $: 'jquery',
                jQuery: 'jquery',
                'window.jQuery': 'jquery',
                Popper: ['popper.js', 'default'],
            })
        ],
        resolve: {
            alias: {
                'jquery': path.join(__dirname, 'node_modules/jquery/dist/jquery'),
            }
        }
    };
});


mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/rtl.scss', 'public/css')
    .copyDirectory('resources/static/images','public/images')
    .browserSync(process.env.APP_URL)
    .version()
    .sourceMaps();

