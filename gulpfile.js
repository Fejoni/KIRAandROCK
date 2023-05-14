"use strict";

const gulp = require('gulp'),
    sass = require('gulp-sass')(require('sass')),
    babel = require("gulp-babel"),
    cssnano = require('gulp-cssnano'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    autoprefixer = require('gulp-autoprefixer'),
    sourcemaps = require('gulp-sourcemaps'),
    plumber = require('gulp-plumber'),
    svgSprite = require('gulp-svg-sprites'),
    cheerio = require('gulp-cheerio'),
    replace = require('gulp-replace'),
    notify = require("gulp-notify");


gulp.task("scripts", function () {//orig
    return gulp.src([
        'resources/assets/scripts.js',
        'resources/assets/blocks/**/*.js'
    ])
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(babel())
        .pipe(concat("scripts.min.js"))
        .pipe(uglify())
        .pipe(sourcemaps.write("."))
        .pipe(gulp.dest('public/js/'));
});

gulp.task('styles', function () {//orig
    return gulp.src([
        'resources/assets/styles.scss',
    ])
        .pipe(plumber())
        .pipe(sourcemaps.init())
        // .pipe(concat('styles.scss'))
        .pipe(sass().on('error', notify.onError()))
        .pipe(autoprefixer(['last 15 versions', '> 1%', 'ie 8']))
        .pipe(cssnano({
            zindex: false,
            colormin: false
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest('public/css/'));
});

gulp.task('libscss', function () {
    return gulp.src([
        'resources/assets/test/*.css',
        "node_modules/swiper/css/swiper.min.css",
        //'resources/assets/libs/wow/css/libs/animate.css',
        // "node_modules/flickity/dist/flickity.css",
        // "node_modules/flickity-fade/flickity-fade.css",
        // "node_modules/slick-carousel/slick/slick.css",

        // "node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.css", //
        "node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.css",

        // "node_modules/daterangepicker/daterangepicker.css",
        // "node_modules/pickmeup/css/pickmeup.css",
        // "node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css",
        // "node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.min.css",
        // "resources/assets/libs/bootstrap-datepicker.standalone-theme-orange.css",
        // "node_modules/jquery-ui-dist/jquery-ui.min.css",
        // "node_modules/nouislider/distribute/nouislider.min.css",
        // "node_modules/rateyo/min/jquery.rateyo.min.css"
        // "node_modules/tippy.js/dist/tippy.css",
        // "node_modules/tippy.js/themes/light-border.css",
        // "node_modules/ion-rangeslider/css/ion.rangeSlider.min.css",
        'resources/assets/libs/smmodal/smmodal.min.css',
        "node_modules/swiper/css/swiper.min.css",
        "node_modules/nouislider/distribute/nouislider.css",
        // 'resources/assets/libs/guillotine/css/jquery.guillotine.css',
        //'resources/assets/libs/twentytwenty-master/css/twentytwenty.css',

    ])
        .pipe(concat('libs.min.css'))
        .pipe(cssnano())
        .pipe(gulp.dest("public/css/"));
});

gulp.task("libsjs", function () {
    return gulp.src([
        // 'resources/assets/test/*.js',
        "node_modules/jquery/dist/jquery.min.js", //npm i --save jquery
        // "node_modules/what-input/dist/what-input.js",//what-input
        // "node_modules/intersection-observer/intersection-observer.js",//npm install intersection-observer for IE => observer.POLL_INTERVAL = 100;

        "node_modules/swiper/js/swiper.min.js", //npm install swiper
        // "node_modules/slick-carousel/slick/slick.js",
        "node_modules/sticky-js/dist/sticky.min.js", // npm install sticky-js
        // "node_modules/clamp-js/clamp.js", // npm i clamp-js
        // "node_modules/flickity/dist/flickity.pkgd.min.js",//npm install flickity
        // "node_modules/flickity-fade/flickity-fade.js",//npm install flickity-fade

        // "node_modules/@fancyapps/fancybox/dist/jquery.fancybox.min.js",//npm i @fancyapps/fancybox
        // "node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.concat.min.js", // malihu-custom-scrollbar-plugin
        // "node_modules/nouislider/distribute/nouislider.min.js",
        // "node_modules/rateyo/min/jquery.rateyo.min.js",// rateyo
        // "node_modules/dotdotdot-js/dist/dotdotdot.js",
        "node_modules/inputmask/dist/jquery.inputmask.min.js", // npm i inputmask
        // "node_modules/jquery-validation/dist/jquery.validate.min.js", // npm i jquery-validation
        // "node_modules/jquery-validation/dist/localization/messages_ru.min.js",

        // "node_modules/pickmeup/dist/pickmeup.min.js",//календарь
        // "node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js", // bootstrap-datepicker
        // "node_modules/bootstrap-datepicker/dist/locales/bootstrap-datepicker.ru.min.js",
        // "node_modules/moment/min/moment.min.js",//npm install moment --save //если нужна локализация
        // "node_modules/moment/locale/ru.js",
        // "node_modules/daterangepicker/moment.min.js",//npm i daterangepicker
        // "node_modules/daterangepicker/daterangepicker.js",//npm i daterangepicker

        // "node_modules/popper.js/dist/umd/popper.js",//npm i popper.js
        // "node_modules/tippy.js/dist/tippy.iife.js",//npm i tippy.js // зависим от popper.js


        // "node_modules/ion-rangeslider/js/ion.rangeSlider.min.js", //npm i ion-rangeslider
        "node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.js", //npm i malihu-custom-scrollbar-plugin

        // 'resources/assets/libs/morph/TweenMax.min.js',//gsap v1.18
        // 'resources/assets/libs/morph/morph.min.js',//совместим только с v1.18

        // "node_modules/splitting/dist/splitting.js",//npm i splitting
        "node_modules/wowjs/dist/wow.js",
        "node_modules/gsap/dist/gsap.js", //gsap
        "node_modules/gsap/dist/ScrollTrigger.min.js",
        // "resources/assets/libs/CustomEase.js",
        // "node_modules/gsap/dist/Draggable.js",
        // "node_modules/scrollmagic/scrollmagic/minified/ScrollMagic.min.js", // scrollmagic
        // "node_modules/scrollmagic/scrollmagic/minified/plugins/animation.gsap.min.js",
        // "node_modules/scrollmagic/scrollmagic/minified/plugins/debug.addIndicators.min.js", //удалить перед релизом
        // "node_modules/pubsub-js/src/pubsub.js", // pubsub-js,


        // "node_modules/three/build/three.min.js",//three
        // "node_modules/three-orbit-controls/index.js",//three-orbit-controls //let OrbitControls = require('three-orbit-controls')(THREE);
        // "node_modules/three-effectcomposer/index.js",//three-effectcomposer //let EffectComposer = require('three-effectcomposer')(THREE);

        // 'resources/assets/libs/glitch/mgGlitch.min.js',
        // 'resources/assets/libs/wow/dist/wow.min.js',
        "node_modules/swiper/js/swiper.min.js", //npm install swiper
        'resources/assets/libs/smmodal/smmodal.min.js',
        // 'src/libs/jplayer/jquery.jplayer.min.js',
        //'resources/assets/libs/lazy/jquery.lazy.min.js',
        "node_modules/nouislider/distribute/nouislider.js",
        //'resources/assets/libs/maskedinput/jquery.maskedinput.min.js',
        //'resources/assets/libs/jquery_touch/jquery.touchSwipe.min.js',
        //'resources/assets/libs/twentytwenty-master/js/jquery.event.move.js',
        //'resources/assets/libs/twentytwenty-master/js/jquery.twentytwenty.js',
        // 'resources/assets/libs/guillotine/js/jquery.guillotine.min.js',

    ])
        .pipe(concat('libs.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest("public/js/"));
});

gulp.task('svg', function () {
    return gulp.src('resources/assets/img/svg/*.svg')
        // remove all fill and style declarations in out shapes
        .pipe(cheerio({
            run: function ($) {
                $('[fill]').removeAttr('fill');
                $('[style]').removeAttr('style');
            },
            parserOptions: {
                xmlMode: true
            }
        }))
        // cheerio plugin create unnecessary string '>', so replace it.
        .pipe(replace('&gt;', '>'))
        // build svg sprite
        .pipe(svgSprite({
            mode: "symbols",
            preview: false,
            selector: "icon-%f",
            svg: {
                symbols: 'svg-sprite.html'
            }
        }))
        .pipe(gulp.dest('resources/assets/img/svg'));
});
gulp.task('img', function () {
    return gulp.src('resources/assets/img/**')
        .pipe(gulp.dest('public/img/'));
});


gulp.task('static', function () {
    return gulp.src([
        'resources/assets/static/**/*',
    ])
        .pipe(gulp.dest('public/'));
});

gulp.task('watch', function () {
    //{usePolling: true}, перед gulp.parallel
    gulp.watch(['resources/assets/static/**/*'], {
        usePolling: true
    }, gulp.parallel('static'));
    gulp.watch(['resources/assets/styles.scss', 'resources/assets/blocks/**/*.scss'], {
        usePolling: true
    }, gulp.parallel('styles'));
    gulp.watch(['resources/assets/blocks/**/*.js', 'resources/assets/scripts.js'], {
        usePolling: true
    }, gulp.parallel('scripts'));
    gulp.watch('resources/assets/img/**/*.{png,jpg,jpeg,webp,raw,svg}', {
        usePolling: true
    }, gulp.parallel('svg', 'img'));
});

gulp.task('mix',gulp.parallel('styles', 'scripts', 'libscss', 'libsjs', 'static', 'img'))
gulp.task('default', gulp.series('mix', gulp.parallel('watch')));
