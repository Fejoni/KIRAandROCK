const mix = require('laravel-mix');

//require('laravel-mix-merge-manifest');
var gulp = require('gulp');
require('./gulpfile');
gulp.task('mix')();
mix.version([
    'public/js/scripts.min.js',
    'public/css/styles.min.css',
    'public/css/libs.min.css',
    'public/js/libs.min.js',
]);/**///.mergeManifest();
