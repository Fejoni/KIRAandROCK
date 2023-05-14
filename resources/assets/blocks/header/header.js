$(function () {

    var feedbackModal = new SmModal({
        modalSelector: '#feedback',
        backgroundBlur: true,
        backgroundBlurValue: '10px',
        overlay: 'background-color: rgba(36, 43, 47, 0.75)',
        afterOpen: function () {
            // blockArrowAnimate();
            // arrowSizeOpen();

            arrowSizeOpen($('#feedback .feedback__right-form .btn'), $('#feedback .feedback__arr .block-arrow'));

            $(window).on('resize', function () {
                arrowSizeResize($('#feedback .feedback__right-form .btn'), $('#feedback .feedback__arr .block-arrow'));
            });

            function arrowSizeOpen(arrowBtn, arrow) {
                console.log('asdasdas');
                var btnTop = arrowBtn.offset().top,
                    btnLeft = arrowBtn.offset().left,
                    btnHeight = arrowBtn.height(),
                    arrowTop = arrow.offset().top,
                    arrowLeft = arrow.offset().left,
                    arrowHeight = (btnTop + (btnHeight / 2)) - arrowTop,
                    arrowWidth = (btnLeft - arrowLeft - 30);

                arrow.animate({
                    height: arrowHeight + 'px',
                    opacity: 1,
                }, 500, 'linear', function () {
                    arrow.find('span').addClass('active');
                    arrow.animate({
                        width: arrowWidth + 'px',
                    }, 500, 'linear');
                });
            }

            function arrowSizeResize(arrowBtn, arrow) {
                var btnTop = arrowBtn.offset().top,
                    btnLeft = arrowBtn.offset().left,
                    btnHeight = arrowBtn.height(),
                    arrowTop = arrow.offset().top,
                    arrowLeft = arrow.offset().left,
                    arrowHeight = (btnTop + (btnHeight / 2)) - arrowTop,
                    arrowWidth = (btnLeft - arrowLeft - 30);

                arrow.css({
                    'height': arrowHeight + 'px',
                    'width': arrowWidth + 'px',
                });
            }
        },
        afterClose: function () {
            $('.feedback__arr .block-arrow').height(0).width(0);
            $('.feedback__arr .block-arrow span').removeClass('active');
        },
    });

    $('.contact-us').on('click', function (e) {
        e.preventDefault();

        feedbackModal.openModal();
    });

    var mobMenu = new SmModal({
        modalSelector: '#menu',
        backgroundBlur: true,
        backgroundBlurValue: '10px',
        overlay: 'background-color: rgba(36, 43, 47, 0.75)',
    });

    $('.header .header__burger').on('click', function (e) {
        e.preventDefault();

        mobMenu.openModal();
    });

    // Форма

    // $(".feedback__right-form input[name=phone]").mask("+7 9 9 9  9 9 9  9 9  9 9", {
    //     placeholder: "-"
    // }).on('click', function () {
    //     if ($(this).val() === '+7 - - -  - - -  - -  - -') {
    //         $(this).get(0).setSelectionRange(4, 4);
    //     }
    // });

    $(".feedback__right-form input[name=phone]").inputmask("+7 999 999 99 99");


    $('.feedback__right-form').on('submit', function (e) {
        //- Остановка отправки формы https://learn.javascript.ru/forms-submit
        e.preventDefault();

        //- Переменная для отправки формы
        var $this = $(this);

        //- Валидация ------------------------------


        //- Получаем все данные из инпутов а так же создаем регулярное выражение на проверку email
        var name = $this.find('input[name=name]').val();
        var phone = $this.find('input[name=phone]').val();
        var email = $this.find('input[name=email]').val();
        var text = $this.find('textarea').val();
        var namePar = $this.find('input[name=name]').parent();
        var phonePar = $this.find('input[name=phone]').parent();
        var emailPar = $this.find('input[name=email]').parent();
        var textPar = $this.find('textarea').parent();
        var pattern = /^[a-z0-9_-]+@[a-z0-9-]+\.([a-z]{1,6}\.)?[a-z]{2,6}$/i;
        var successEmail = false;


        //- Проверка на пустое поле
        if (name.length !== 0) {
            //- Проверка выполнена успешно (поле заполнено)
            namePar.removeClass('wrong');
        } else {
            //- Проверка неудачна (поле пустое)
            namePar.addClass('wrong');
        };

        //- Проверка на пустое поле
        if (phone.length !== 0) {
            //- Проверка выполнена успешно (поле заполнено)
            phonePar.removeClass('wrong');
        } else {
            //- Проверка неудачна (поле пустое)
            phonePar.addClass('wrong');
        }

        //- Проверка на пустое поле
        if (email.length !== 0) {
            //- Проверка выполнена успешно (поле заполнено)
            emailPar.removeClass('wrong');

            //- Проверка на соответсвие регулярному выражению
            if (email.search(pattern) == 0) {
                //- Проверка выполнена успешно (значение в поле ввода соответсвует регулярному выражению)
                emailPar.removeClass('wrong');
                successEmail = true;
            } else {
                //- Проверка неудачна (значение в поле ввода НЕ соответсвует регулярному выражению)
                emailPar.addClass('wrong');
            }

        } else {
            //- Проверка неудачна (поле пустое)
            emailPar.addClass('wrong');
        }


        //- Проверка на пустое поле
        if (text.length !== 0) {
            //- Проверка выполнена успешно (поле заполнено)
            textPar.removeClass('wrong');
        } else {
            //- Проверка неудачна (поле пустое)
            textPar.addClass('wrong');
        }

        //- Проверка на соответсвие всех полей необходимым проверкам
        if ($this.find('.wrong').length == 0) {
            //- Конец валидации ----------------------------------------------

            console.log('submit');
            feedbackThanks.openModal();
            feedbackModal.closeModal();

            var fd = new FormData(this);
            //- Выполнить ajax отправку данных на сервер
            $.ajax({
                url: $this.attr('action'), // Файл в который отправить данные
                type: $this.attr('method'), // Метод отправки данных
                data: fd,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                success: function () { // Функция выполняемая в случае успешной отправки формы
                    //- Очистить форму после отправки
                    $this[0].reset();
                    feedbackThanks.openModal();
                    feedbackModal.closeModal();
                }
            });
        }
    });


    // Ответ по форме

    var feedbackThanks = new SmModal({
        modalSelector: '#tahnksmod',
        backgroundBlur: true,
        backgroundBlurValue: '10px',
        overlay: 'background-color: rgba(36, 43, 47, 0.75)',
        afterOpen: function () {
            // blockArrowAnimate();
            // arrowSizeOpen();
            $('#tahnksmod .thanks-mod__arrow-wp').addClass('thanks-mod__arrow-wp_active');
        },
        afterClose: function () {
            $('#tahnksmod .thanks-mod__arrow-wp').removeClass('thanks-mod__arrow-wp_active');
            // $('.feedback__arr .block-arrow').height(0).width(0);
            // $('.feedback__arr .block-arrow span').removeClass('active');
        },
    });



    // Форма бриф

    var brifModal = new SmModal({
        modalSelector: '#brifmod',
        backgroundBlur: true,
        backgroundBlurValue: '10px',
        overlay: 'background-color: rgba(36, 43, 47, 0.75)',
        afterOpen: function () {
            // $('.main-hero__arrow-item').removeClass('main-hero__arrow-item_stop-height');
            // setTimeout(function () {
            //     $('.main-hero__arrow-item').removeClass('main-hero__arrow-item_stop-width');
            // }, 900);
            arrowSizeOpen($('#brifmod .feedback__right .btn'), $('#brifmod .feedback__right .main-hero__arrow-item'));
            arrowSizeOpen($('#brifmod .feedback__left .btn'), $('#brifmod .feedback__left .main-hero__arrow-item'));

            $(window).on('resize', function () {
                arrowSizeResize($('#brifmod .feedback__right .btn'), $('#brifmod .feedback__right .main-hero__arrow-item'));
                arrowSizeResize($('#brifmod .feedback__left .btn'), $('#brifmod .feedback__left .main-hero__arrow-item'));
            });

            function arrowSizeOpen(arrowBtn, arrow) {
                console.log('asdasdas');
                let btnTop = arrowBtn.offset().top,
                    btnLeft = arrowBtn.offset().left,
                    btnHeight = arrowBtn.height(),
                    arrowTop = arrow.offset().top,
                    arrowLeft = arrow.offset().left,
                    arrowHeight = (btnTop + (btnHeight / 2)) - arrowTop,
                    arrowWidth = (btnLeft - arrowLeft - 30);

                arrow.animate({
                    height: arrowHeight + 'px',
                    opacity: 1,
                }, 500, 'linear', function () {
                    arrow.find('span').addClass('active');
                    arrow.animate({
                        width: arrowWidth + 'px',
                    }, 500, 'linear');
                });
            }

            function arrowSizeResize(arrowBtn, arrow) {
                var btnTop = arrowBtn.offset().top,
                    btnLeft = arrowBtn.offset().left,
                    btnHeight = arrowBtn.height(),
                    arrowTop = arrow.offset().top,
                    arrowLeft = arrow.offset().left,
                    arrowHeight = (btnTop + (btnHeight / 2)) - arrowTop,
                    arrowWidth = (btnLeft - arrowLeft - 30);

                arrow.css({
                    'height': arrowHeight + 'px',
                    'width': arrowWidth + 'px',
                    'opacity': 1,
                });
            }
        },
        afterClose: function () {
            $('.main-hero__arrow-item').addClass('main-hero__arrow-item_stop-height');
            $('.main-hero__arrow-item').addClass('main-hero__arrow-item_stop-width');
        },
    });

    $('.header__logo-cont, .footer__left a').on('click', function (e) {
        e.preventDefault();

        brifModal.openModal();
    });



    ///////////

    $('.to-top').on('click', function (e) {
        e.preventDefault();

        $('html,body').stop().animate({
            scrollTop: 0
        }, 1000);
        e.preventDefault();
    });

    $(window).on('load scroll', function (e) {
        if (window.pageYOffset > 200) {
            $('.to-top').addClass('active');
        } else {
            $('.to-top').removeClass('active');
        }
    });

    // var toTopLink = ScrollTrigger.create({
    //     trigger: "body",
    //     start: $('.header').outerHeight() + "px top",
    //     scrub: 0.5,
    //     onUpdate: function (self) {
    //         if (self.isActive) {
    //             $('.to-top').addClass('active');
    //         } else {
    //             $('.to-top').removeClass('active');
    //         }
    //     }
    // });


    $(window).on('resize', function () {
        if ($(window).width() > 991) {
            mobMenu.closeModal();
        }
    });

    // function blockArrowAnimate() {
    //     setTimeout(() => {
    //         $('.block-arrow').addClass('active1');
    //         setTimeout(() => {
    //             $('.block-arrow').addClass('active2');
    //             setTimeout(() => {
    //                 $('.block-arrow').addClass('active3');
    //             }, 300);
    //         }, 280);
    //     }, 300);
    // }

    // фикс хидера
    var headerScroll = ScrollTrigger.create({
        trigger: "body",
        start: $('.header').outerHeight() + "px top",
        scrub: 0.5,
        onUpdate: function (self) {
            if (self.isActive) {
                $('.header').addClass('fixed');
                if (self.direction == '-1') {
                    if (self.progress == 0) {
                        $('.header').removeClass('active');
                        if ($('main').hasClass('policy')) {
                            $('.policy-main__wrap-right-wrap ul').removeClass('active');
                        }
                    } else {
                        $('.header').addClass('active');
                        if ($('main').hasClass('policy') && $(window).width() > 1300) {
                            let ul_style = $('.policy-main__wrap-right-wrap ul').attr('style');
                            if (typeof ul_style != "undefined" && ul_style.length > 0) {
                                $('.policy-main__wrap-right-wrap ul').addClass('active');
                            } else {
                                $('.policy-main__wrap-right-wrap ul').removeClass('active');
                            }
                        }
                    }
                } else {
                    $('.header').removeClass('active');
                    if ($('main').hasClass('policy')) {
                        $('.policy-main__wrap-right-wrap ul').removeClass('active');
                    }
                }
            } else {
                $('.header').removeClass('fixed active');
                if ($('main').hasClass('policy')) {
                    $('.policy-main__wrap-right-wrap ul').removeClass('active');
                }
            }
        }
    });
    $(window).on('resize', function () {
        headerScroll.vars.start = $('.header').outerHeight() + 'px top';
        headerScroll.refresh();
    });
    // конец фикса хидера


    // $(window).on('resize', function () {
    //     var $this = $(this);
    //     var $delay = 100;

    //     clearTimeout($this.data('timer'));

    //     $this.data('timer', setTimeout(function () {
    //         $this.removeData('timer');

    //         arrowSizeResize();

    //     }, $delay));
    // });
    function arrowSizeOpen() {
        var btnTop = $('.feedback__right button').offset().top,
            btnLeft = $('.feedback__right button').offset().left,
            btnHeight = $('.feedback__right button').height(),
            arrowTop = $('.feedback__arr .block-arrow').offset().top,
            arrowLeft = $('.feedback__arr .block-arrow').offset().left,
            arrowHeight = (btnTop + (btnHeight / 2)) - arrowTop,
            arrowWidth = (btnLeft - arrowLeft - 10);

        $('.feedback__arr .block-arrow').animate({
            height: arrowHeight + 'px',
            opacity: 1,
        }, 500, 'linear', function () {
            $('.feedback__arr .block-arrow span').addClass('active');
            $('.feedback__arr .block-arrow').animate({
                width: arrowWidth + 'px',
            }, 500, 'linear');
        });
    }


    // Инпут файл для формы

    $('.input-file input').on('change', function (e) {
        //  $('.input-file .message').html('');
        projectFile = this.files;
        //  for (var i = 0; i < projectFile.length; i++) {
        previewFile(projectFile[0]);
        //  }
    });

    function previewFile(file) {
        var expansion = file.name.slice(file.name.lastIndexOf('.') + 1);
        if (expansion === 'docx' || expansion === 'doc' || expansion === 'pdf' || expansion === 'txt' || expansion === 'jpg') {
            if (file.size <= 2097152) {
                var filseSize = file.size / 1048576;
                // arrFiles.push(file);
                let reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onloadend = function () {
                    $('.input-file .info').hide();
                    $('.input-file .file').fadeIn();
                    $('.input-file .file p').text(file.name);
                }
            } else {
                $('.input-file .file p').text('<p>Файл ' + file.name + ' превышает 2 Мб</p>');
            }
        } else {
            $('.input-file .file p').text('<p>Формат файла ' + file.name + ' не поддерживается</p>');
        }
    }

    $('.input-file .file a').on('click', function (e) {
        e.preventDefault();

        $('.input-file .info').fadeIn();
        $('.input-file .file').hide();
        $('.input-file input').val('');
    });
});


function arrowSizeOpen(arrowBtn, arrow) {
    // console.log('asdasdas');
    var btnTop = arrowBtn.offset().top,
        btnLeft = arrowBtn.offset().left,
        btnHeight = arrowBtn.height(),
        arrowTop = arrow.offset().top,
        arrowLeft = arrow.offset().left,
        arrowHeight = (btnTop + (btnHeight / 2)) - arrowTop,
        arrowWidth = (btnLeft - arrowLeft - 30);

    arrow.animate({
        height: arrowHeight + 'px',
        opacity: 1,
    }, 500, 'linear', function () {
        arrow.find('span').addClass('active');
        arrow.animate({
            width: arrowWidth + 'px',
        }, 500, 'linear');
    });
}

function arrowSizeResize(arrowBtn, arrow) {
    var btnTop = arrowBtn.offset().top,
        btnLeft = arrowBtn.offset().left,
        btnHeight = arrowBtn.height(),
        arrowTop = arrow.offset().top,
        arrowLeft = arrow.offset().left,
        arrowHeight = (btnTop + (btnHeight / 2)) - arrowTop,
        arrowWidth = (btnLeft - arrowLeft - 30);

    arrow.css({
        'height': arrowHeight + 'px',
        'width': arrowWidth + 'px',
    });
}
