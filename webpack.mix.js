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

mix.styles([
   '../AdminLTE/plugins/fontawesome-free/css/all.min.css',
   '../AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css',
   '../AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
   '../AdminLTE/dist/css/adminlte.min.css',
   '../AdminLTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css',
   '../AdminLTE/plugins/summernote/summernote-bs4.css',
   '../AdminLTE/plugins/daterangepicker/daterangepicker.css',
], 'public/css/admin.css');

mix.scripts([
   '../AdminLTE/plugins/jquery/jquery.min.js',
   '../AdminLTE/plugins/jquery-ui/jquery-ui.min.js',
   '../AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js',
   '../AdminLTE/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js',
   '../AdminLTE/plugins/moment/moment.min.js',
   '../AdminLTE/plugins/daterangepicker/daterangepicker.js',
   '../AdminLTE/dist/js/adminlte.js'
], 'public/js/admin.js');

mix.copyDirectory('../AdminLTE/plugins/fontawesome-free/webfonts', 'public/webfonts');