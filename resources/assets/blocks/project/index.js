$(function () {

    if ($('main').hasClass('project')) {

        $(window).on('load', function () {
            $("#twocont").twentytwenty({
                default_offset_pct: 0.7,
                no_overlay: true,
                move_slider_on_hover: true,
                move_with_handle_only: true,
                click_to_move: false
            });
        });

        var thanksClient = new SmModal({
            modalSelector: '#thanksClient',
            backgroundBlur: true,
            backgroundBlurValue: '10px',
            overlay: 'background-color: rgba(36, 43, 47, 0.75)',

        });

        $('.thanks-letter').on('click', function (e) {
            e.preventDefault();

            thanksClient.openModal();
        });


        // var sticky = new Sticky('.project-main__left-wp');


        // Кастомный стики

        $(window).on('load resize scroll', function (e) {
            var fixEl = $('.project-main__left-cont'),
                parEl = fixEl.parent(),
                clidEl = $('.project-main__left-cont .project-main__left-wp'),
                deltaTop = window.pageYOffset + window.innerHeight > $('footer').offset().top ? window.pageYOffset + window.innerHeight - $('footer').offset().top : 0,
                topPos = Number(parEl.css('paddingTop').replace('px', '')),
                leftPos = Number(parEl.css('paddingLeft').replace('px', '')),
                // rightPos = Number(parEl.css('paddingRight').replace('px', '')),
                elWidth = parEl.width();


            // console.log(parEl.offset().top + parEl.height());
            console.log(window.pageYOffset);

            console.log(topPos + (window.innerHeight - clidEl.height() - (window.pageYOffset + window.innerHeight - $('footer').offset().top)));

            if ($(window).width() > 1366) {
                topPos = topPos - deltaTop;

                if (window.pageYOffset > parEl.offset().top && window.pageYOffset + clidEl.height() < (parEl.offset().top + parEl.height())) {
                    fixEl.css({ position: 'fixed', left: (leftPos != 0 ? leftPos : 61), top: topPos, width: elWidth })
                } else if (window.pageYOffset < parEl.offset().top) {
                    fixEl.css({ position: 'static', top: topPos, width: 'auto' })
                }
                else {
                    fixEl.css({ top: topPos + (window.innerHeight - clidEl.height() - $('footer').height() - 40) })
                }

            } else {
                fixEl.css({ position: 'static', width: '100%' })
            }


            // console.log(e);

        });



        ////



        // Паралакс
        if ($('main').hasClass('project1')) {

            $(window).on('scroll', function (e) {


                if ($(window).width() > 1140) {

                    var docc = $(document).scrollTop();
                    var bobles = $('.project-main__sect_parall');
                    var boblesOff = $('.project-main__sect_parall').offset().top;

                    if (docc > (boblesOff - 2000) && docc < (boblesOff + $('.project-main__sect_parall').height())) {
                        // var transf = ((docc - boblesOff) / bobles.height()) * 100;
                        var transf = ((docc - boblesOff) / 30);
                        transf < -35 ? transf = -35 : transf;

                        $('.bobles').css('transform', 'translateY(' + (transf) * 3 + 'px)');
                    }

                } else {
                    $('.bobles').removeAttr('style');
                }
            });

        }


        $(document).ready(function () {
            var windowHeight = $(window).height();

            $(document).on('scroll', function () {
                $('.project-main__lamp').each(function () {
                    var self = $(this),
                        height = self.offset().top + self.height() / 1.5;
                    // console.log(height);
                    // console.log($(document).scrollTop() + windowHeight);

                    if ($(document).scrollTop() + windowHeight >= (height)) {
                        setTimeout(function () {
                            self.addClass('project-main__lamp_light');
                        }, 200)
                    }
                });


                $('.project-main__video').each(function () {
                    var self = $(this),
                        height = self.offset().top + self.height(),
                        scrollTop = $(document).scrollTop();
                    if (scrollTop + windowHeight >= height && height >= scrollTop) {
                        self.addClass('project-main__video_play');
                        self.find('video').get(0).play();
                    } else {
                        self.removeClass('project-main__video_play');
                        self.find('video').get(0).pause();
                    }
                });

                $('.project-main__car').each(function () {
                    var self = $(this),
                        height = self.offset().top + self.height() / 1.5;
                    // console.log(height);
                    // console.log($(document).scrollTop() + windowHeight);

                    if ($(document).scrollTop() + windowHeight >= (height)) {
                        setTimeout(function () {
                            self.addClass('project-main__car-move');
                        }, 200)
                    }
                });

                $('.project-main__video-mob').each(function () {
                    var self = $(this),
                        height = self.offset().top + self.height(),
                        scrollTop = $(document).scrollTop() + 100;
                    if (scrollTop + windowHeight >= height && height >= scrollTop) {
                        self.addClass('active');
                        self.find('video').get(0).play();
                    } else {
                        self.removeClass('active');
                        self.find('video').get(0).pause();
                    }
                });


            });
        });

        $('.project-main__video .video-play').on('click', function (e) {
            e.preventDefault();
            $('.project-main__video').addClass('project-main__video_play');
            var video = document.querySelectorAll('.project-main__video video')[0];
            video.play();
            // $('.project-main__video video').play();
        });

        // $(window).on('load', function (e) {
        $('.project-main__left-soc-fix').parent().height($('.project-main__left-soc-fix').outerHeight());
        // });

        $(document).on('scroll', function (e) {

            var fixElem = $('.project-main__left-soc-fix');
            var fixElemParentOff = $('.project-main__left-soc').offset().top;
            // fixElem.parent().height(fixElem.outerHeight());
            $('.header').find('.wow').removeClass('wow').css('visibility', 'visible');


            if ($(window).width() < 1366) {

                // console.log(fixElem);

                if ($(window).scrollTop() >= fixElemParentOff) {
                    fixElem.parent().addClass('fixed');
                } else {
                    fixElem.parent().removeClass('fixed');
                }


            }

            // if ($(document).scrollTop() > 0) {
            //     $('.header').addClass('fixed');

            //     var st = $(this).scrollTop();
            //     if (st > scrollPos) {
            //         // down
            //         $('.header').removeClass('fixed-visible');
            //         $('.header').find('.wow').removeClass('wow').css('visibility', 'visible');
            //     } else {
            //         // up
            //         $('.header').addClass('fixed-visible');
            //     }
            //     scrollPos = st;

            // } else {
            //     $('.header').removeClass('fixed');
            // }
        });


        // function trackLocation(e) {

        //     // e.preventDefault();


        //     var rect = videoContainer.getBoundingClientRect(),
        //         position = ((e.pageX - rect.left) / videoContainer.offsetWidth) * 100;
        //     // positionY = ((e.pageY - rect.top) / videoContainer.offsetHeight) * 100;
        //     // console.log(e.pageX);
        //     if (position <= 100) {
        //         videoClipper.style.width = position + 0.2 + "%";
        //         // ukazat.style.left = position + 0.2 + "%"
        //         // ukazat.style.top = positionY + 0.2 + "%"
        //         // clippedVideo.style.width = ((100 / position) * 100) + "%";
        //         // clippedVideo.style.zIndex = 3;
        //     }
        // }
        // var videoContainer = document.getElementById("twocont"),
        //     videoClipper = document.getElementById("twotwo");
        // // ukazat = document.getElementById("ukaz");
        // videoContainer.addEventListener("mousemove", trackLocation, false);
        // videoContainer.addEventListener("touchstart", trackLocation, false);
        // videoContainer.addEventListener("touchend", trackLocation, false);
        // videoContainer.addEventListener("touchmove", trackLocation, false);
        // videoContainer.addEventListener("click", trackLocation, false);


        // $(window).on('load resize', function (e) {

        //     var widt = $('#twocont').width();

        //     // console.log(widt);
        //     $('#twocont img').css('width', widt + 2);
        // });

        // jQuery('body').swipe({
        //     swipeStatus: function (event, phase, direction, distance, duration, fingerCount, fingerData, currentDirection) {
        //         console.log(phase);
        //     }
        // });


    }

    if ($('main').hasClass('project3')) {
        var swiper1 = {
            width: Infinity,
            swiperContainer: '.project-main__sliders-1',
            swiperContainerSlide: '.project-main__sliders-1 img',
            swiperVariable: undefined,
            swiperUpdateTime: undefined,
            swiperSettings: {
                slidesPerView: 'auto',
                speed: 15000,
                watchOverflow: true,
                spaceBetween: 46,
                loop: true,
                loopedSlides: 6,
                autoplay: {
                    delay: 0,
                    disableOnInteraction: false,
                },
            },
        }

        var swiper2 = {
            width: Infinity,
            swiperContainer: '.project-main__sliders-2',
            swiperContainerSlide: '.project-main__sliders-2 img',
            swiperVariable: undefined,
            swiperUpdateTime: undefined,
            swiperSettings: {
                slidesPerView: 'auto',
                speed: 18000,
                watchOverflow: true,
                spaceBetween: 44,
                loop: true,
                loopedSlides: 7,
                autoplay: {
                    delay: 0,
                    disableOnInteraction: false,
                },
            },
        }

        var swiper3 = {
            width: Infinity,
            swiperContainer: '.project-main__sliders-3',
            swiperContainerSlide: '.project-main__sliders-3 img',
            swiperVariable: undefined,
            swiperUpdateTime: undefined,
            swiperSettings: {
                slidesPerView: 'auto',
                speed: 15000,
                watchOverflow: true,
                spaceBetween: 46,
                loop: true,
                loopedSlides: 27,
                autoplay: {
                    delay: 0,
                    disableOnInteraction: false,
                },
            },
        }

        var swiper4 = {
            width: Infinity,
            swiperContainer: '.project-main__sliders-4',
            swiperContainerSlide: '.project-main__sliders-4 img',
            swiperVariable: undefined,
            swiperUpdateTime: undefined,
            swiperSettings: {
                slidesPerView: 'auto',
                speed: 18000,
                watchOverflow: true,
                spaceBetween: 44,
                loop: true,
                loopedSlides: 22,
                autoplay: {
                    delay: 0,
                    disableOnInteraction: false,
                },
            },
        }

        var swiper5 = {
            width: Infinity,
            swiperContainer: '.project-main__sliders-5',
            swiperContainerSlide: '.project-main__sliders-5 img',
            swiperVariable: undefined,
            swiperUpdateTime: undefined,
            swiperSettings: {
                slidesPerView: 'auto',
                speed: 12000,
                watchOverflow: true,
                spaceBetween: 28,
                loop: true,
                loopedSlides: 22,
                autoplay: {
                    delay: 0,
                    disableOnInteraction: false,
                },
            },
        }

        var swiper6 = {
            width: Infinity,
            swiperContainer: '.project-main__sliders-6',
            swiperContainerSlide: '.project-main__sliders-6 img',
            swiperVariable: undefined,
            swiperUpdateTime: undefined,
            swiperSettings: {
                slidesPerView: 'auto',
                speed: 15000,
                watchOverflow: true,
                spaceBetween: 32,
                loop: true,
                loopedSlides: 23,
                autoplay: {
                    delay: 0,
                    disableOnInteraction: false,
                },
            },
        }

        initCustomSwiper(swiper1);
        initCustomSwiper(swiper2);
        initCustomSwiper(swiper3);
        initCustomSwiper(swiper4);
        initCustomSwiper(swiper5);
        initCustomSwiper(swiper6);
        $(window).resize(function () {
            initCustomSwiper(swiper1);
            initCustomSwiper(swiper2);
            initCustomSwiper(swiper3);
            initCustomSwiper(swiper4);
            initCustomSwiper(swiper5);
            initCustomSwiper(swiper6);
        });
    }




    // Направление скролла
    //  if (docc > lastPos) {
    //      console.log('vnizzzz');
    //      transf = transf + 2;
    //  } else {
    //      console.log('vverh');
    //      transf = transf - 2;
    //  }



    $('.project-main__video-mob').on('click', function () {
        if ($(this).hasClass('active')) {
            $(this).removeClass('active').find('video').get(0).pause();
        } else {
            $(this).addClass('active').find('video').get(0).play();
        }
    })


    // var playBtn = document.querySelector('.project-main__video-mob .play');
    // var videoEl = document.querySelector('.project-main__video-mob video');

    // playBtn.addEventListener('click', function () {
    //     if (videoEl.paused) {  // если видео остановлено, запускаем
    //         videoEl.play();
    //     } else {
    //         videoEl.pause();
    //     }
    // }, false);


});