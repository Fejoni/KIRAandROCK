$(function () {
    var wow = new WOW({
        mobile: true,
    });
    wow.init();


    $('img').Lazy();

    if ($('main').hasClass('main-page')) {
        var servicesSlider = {
            width: Infinity,
            swiperContainer: '.main-services__wp',
            swiperContainerSlide: '.main-services__slide',
            swiperVariable: undefined,
            swiperSettings: {
                slidesPerView: 'auto',
                speed: 500,
                grabCursor: true,
                scrollbar: {
                    el: '.main-services__scroll',
                    hide: false,
                    draggable: true,
                },
            },
        };
        initCustomSwiper(servicesSlider);
        $(window).resize(function () {
            initCustomSwiper(servicesSlider);
        });

        let puzzleTiles = document.querySelectorAll('.puzzle__el');

        let tl = new TimelineMax({
            repeat: -1,
            repeatDelay: 10,
            yoyo: true,
            defaults: {
                duration: 1.5,
                ease: Power1.easeInOut
            },
        });

        function step1() {
            tl
                .to(puzzleTiles[2], {
                    xPercent: 100
                })
                .to(puzzleTiles[3], {
                    yPercent: 100
                }, '<')
                .to(puzzleTiles[6], {
                    yPercent: -100
                }, '<')
                .to(puzzleTiles[7], {
                    xPercent: -100
                }, '<');

        }

        function step2() {
            tl
                .to(puzzleTiles[7], {
                    xPercent: -200
                })
                .to(puzzleTiles[5], {
                    yPercent: 100
                }, '<')
                .to(puzzleTiles[9], {
                    xPercent: 100
                }, '<')
                .to(puzzleTiles[10], {
                    yPercent: -100
                }, '<');
        }

        function step3() {
            tl
                .to(puzzleTiles[7], {
                    xPercent: -200,
                    yPercent: -100
                })
                .to(puzzleTiles[1], {
                    xPercent: -100
                }, '<')
                .to(puzzleTiles[0], {
                    yPercent: 100
                }, '<')
                .to(puzzleTiles[4], {
                    xPercent: 100
                }, '<');
        }

        function step4() {
            tl
                .to(puzzleTiles[0], {
                    xPercent: 100,
                    yPercent: 100
                })
                .to(puzzleTiles[4], {
                    xPercent: 100,
                    yPercent: 100,
                }, '<')
                .to(puzzleTiles[8], {
                    yPercent: -100
                }, '<')
                .to(puzzleTiles[5], {
                    yPercent: 100,
                    xPercent: -100
                }, '<');
        }


        step1();
        step2();
        step3();
        step4();

        arrowSizeOpen($('.main-hero__left .btn'), $('.main-hero__left .main-hero__arrow-item'));
        arrowSizeOpen($('.main-works__item_first .main-works__item-right .btn'), $('.main-works__item_first .main-hero__arrow-item'));
        arrowSizeOpen($('.main-works__item_second .main-works__item-right .main-works__soon'), $('.main-works__item_second .main-hero__arrow-item'));
        arrowSizeOpen($('.main-works__item_third .main-works__item-right .btn'), $('.main-works__item_third .main-hero__arrow-item'));

        // $(window).on('load', function () {


        // });

        $(window).on('resize', function () {
            arrowSizeResize($('.main-hero__left .btn'), $('.main-hero__left .main-hero__arrow-item'));
            arrowSizeResize($('.main-works__item_first .main-works__item-right .btn'), $('.main-works__item_first .main-hero__arrow-item'));
            arrowSizeResize($('.main-works__item_second .main-works__item-right .main-works__soon'), $('.main-works__item_second .main-hero__arrow-item'));
            arrowSizeResize($('.main-works__item_third .main-works__item-right .btn'), $('.main-works__item_third .main-hero__arrow-item'));
        });
    }

    //  Мигание глазом на главной в hero

    function blinkHero() {
        setTimeout(() => {
            $('.main-hero .blink').css('animation-play-state', 'initial');
        }, 6000);
        setTimeout(() => {
            $('.main-hero .blink').css('animation-play-state', 'paused');
        }, 16000);
        setTimeout(() => {
            $('.main-hero .blink').css('animation-play-state', 'initial');
        }, 22000);
        setTimeout(() => {
            $('.main-hero .blink').css('animation-play-state', 'paused');
        }, 32000);

    };

    blinkHero();

    setInterval(() => {
        blinkHero();
    }, 32000);

    // ...........\\\\\\\\\\\\\\\



    $('.arrow-small').each(function (e) {

        $(this).on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('arrow-small_visible')
        })
    });

    $(document).ready(function () {
        var windowHeight = $(window).height();

        $(document).on('scroll', function () {
            $('.arrow-small').each(function () {
                var self = $(this),
                    height = self.offset().top + self.height();
                if ($(document).scrollTop() + windowHeight >= height) {
                    setTimeout(function () {
                        self.addClass('arrow-small_pause');
                    }, 100)
                }
            });
        });
    });



    // var recurs = false;
    // $('.animate-block__wrap').on('click', function () {
    //     if (recurs == false) {
    //         assembleArt();
    //         recurs = true;
    //     } else if (recurs = true) {
    //         disassembleArt();
    //         recurs = false;
    //     }
    // });

    // function assembleArt() {
    //     var flugigeheiman = 1;

    //     var timerflugigeheiman = setInterval(() => {

    //         if (flugigeheiman == 4) {
    //             clearInterval(timerflugigeheiman);
    //         }

    //         $('.animate-block__el').addClass('step-' + flugigeheiman);
    //         flugigeheiman++;

    //     }, 300);
    // }

    // function disassembleArt() {
    //     var flugigeheiman = 4;

    //     var timerflugigeheiman = setInterval(() => {

    //         if (flugigeheiman == 0) {
    //             clearInterval(timerflugigeheiman);
    //         }

    //         $('.animate-block__el').removeClass('step-' + flugigeheiman);
    //         flugigeheiman--;

    //     }, 300);
    // }
});