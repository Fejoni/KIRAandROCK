"use strict";

function _instanceof(left, right) {
    if (right != null && typeof Symbol !== "undefined" && right[Symbol.hasInstance]) {
        return !!right[Symbol.hasInstance](left);
    } else {
        return left instanceof right;
    }
}

function _classCallCheck(instance, Constructor) {
    if (!_instanceof(instance, Constructor)) {
        throw new TypeError("Cannot call a class as a function");
    }
}

function _defineProperties(target, props) {
    for (var i = 0; i < props.length; i++) {
        var descriptor = props[i];
        descriptor.enumerable = descriptor.enumerable || false;
        descriptor.configurable = true;
        if ("value" in descriptor) descriptor.writable = true;
        Object.defineProperty(target, descriptor.key, descriptor);
    }
}

function _createClass(Constructor, protoProps, staticProps) {
    if (protoProps) _defineProperties(Constructor.prototype, protoProps);
    if (staticProps) _defineProperties(Constructor, staticProps);
    return Constructor;
}

// Класс модального окна
var overModal = [];

var SmModal = /*#__PURE__*/ function () {
    function SmModal(modalSettings) {
        var _this = this;

        _classCallCheck(this, SmModal);

        // Стандатные настройки плагина
        var modalSettingsDefault = {
            closeSelector: '.modal-close',
            modalSpeed: 500,
            openAnimate: 'smFade',
            closeAnimate: 'smFade',
            transitionTimingFunction: 'ease',
            backgroundBlur: false,
            backgroundBlurEl: '.sm-modal',
            backgroundBlurValue: '5px',
            overlay: 'background-color: rgba(0, 0, 0, 0.7)',
            verticalCenter: true,
            closeEsc: true
        }; // Установка пользовательских переменых экземпляра плагина

        this.beforeOpenVar = false;
        this.afterOpenVar = false;
        this.beforeCloseVar = false;
        this.afterCloseVar = false;
        this.modalSelector = modalSettings.modalSelector;
        this.closeSelector = modalSettings.closeSelector;
        this.modalSpeed = modalSettings.modalSpeed;
        this.openAnimate = modalSettings.openAnimate;
        this.closeAnimate = modalSettings.closeAnimate;
        this.transitionTimingFunction = modalSettings.transitionTimingFunction;
        this.beforeOpen = modalSettings.beforeOpen;
        this.afterOpen = modalSettings.afterOpen;
        this.beforeClose = modalSettings.beforeClose;
        this.afterClose = modalSettings.afterClose;
        this.currentPadding = parseInt($(this.modalSelector).find('.sm-modal-body').css('padding-top').replace('px', ''));
        this.backgroundBlur = modalSettings.backgroundBlur;
        this.backgroundBlurEl = modalSettings.backgroundBlurEl;
        this.backgroundBlurValue = modalSettings.backgroundBlurValue;
        this.overlay = modalSettings.overlay;
        this.verticalCenter = modalSettings.verticalCenter;
        this.closeEsc = modalSettings.closeEsc;
        this.sideModal = modalSettings.sideModal; // Установка стандартных переменных если нету пользовательских

        if (this.closeSelector === undefined) {
            this.closeSelector = modalSettingsDefault.closeSelector;
        }

        if (this.modalSpeed === undefined) {
            this.modalSpeed = modalSettingsDefault.modalSpeed;
        }

        if (this.openAnimate === undefined) {
            this.openAnimate = modalSettingsDefault.openAnimate;
        }

        if (this.closeAnimate === undefined) {
            this.closeAnimate = modalSettingsDefault.closeAnimate;
        }

        if (this.transitionTimingFunction === undefined) {
            this.transitionTimingFunction = modalSettingsDefault.transitionTimingFunction;
        }

        if (this.backgroundBlur === undefined) {
            this.backgroundBlur = modalSettingsDefault.backgroundBlur;
        }

        if (this.backgroundBlurEl === undefined) {
            this.backgroundBlurEl = modalSettingsDefault.backgroundBlurEl;
        }

        if (this.backgroundBlurValue === undefined) {
            this.backgroundBlurValue = modalSettingsDefault.backgroundBlurValue;
        }

        if (this.overlay === undefined) {
            this.overlay = modalSettingsDefault.overlay;
        }

        if (this.verticalCenter === undefined) {
            this.verticalCenter = modalSettingsDefault.verticalCenter;
        }

        if (this.closeEsc === undefined) {
            this.closeEsc = modalSettingsDefault.closeEsc;
        } // Боковая модалка


        if (this.sideModal === 'left') {
            $(this.modalSelector).addClass('leftModal');
        } else if (this.sideModal === 'right') {
            $(this.modalSelector).addClass('rightModal');
        } // Отмена вертикального центрирования для всех браузеров кроме IE


        if (this.verticalCenter === false) {
            $(this.modalSelector).find('.sm-modal-wrap').css('align-items', 'flex-start');
        } // Применить стили к подложке


        $(this.modalSelector).find('.sm-modal-body').attr('style', this.overlay); // Инициализация классов анимации

        $(this.modalSelector).css('transition', 'all 0s ' + this.transitionTimingFunction);
        $(this.modalSelector).find('.sm-modal-contant').css('transition', 'all 0s ' + this.transitionTimingFunction);
        $(this.modalSelector).removeClass(this.closeAnimate);
        $(this.modalSelector).addClass(this.openAnimate); // Закрытие при нажатии на Esc

        if (this.closeEsc === true) {
            $(document).on('keyup', function (e) {
                if (e.keyCode === 27 && $(_this.modalSelector).hasClass('active')) {
                    _this.closeModal();
                }

                ;
            });
        } // Закрытие при клике на overlay


        $(this.modalSelector).off('click').on('click', function (e) {
            _this.modalBody = $(_this.modalSelector).find('.sm-modal-body');
            _this.modalWrap = $(_this.modalSelector).find('.sm-modal-wrap');

            if ($(e.target).is(_this.modalBody) || _this.modalWrap.is($(e.target))) {
                _this.closeModal();
            }
        }); // Закрытие при клике на кнопку закрытия (параметр - "closeSelector")

        $(this.modalSelector + ' ' + this.closeSelector).off('click').on('click', function () {
            _this.closeModal();
        });
    } // Метод открытия модального окна


    _createClass(SmModal, [{
        key: "openModal",
        value: function openModal() {
            var _this2 = this;

            // Выполнить функцию beforeOpen, если она была задана
            if (this.beforeOpen !== undefined) {
                this.beforeOpen();
            } // Открываем модальное окно с z-index 5000 или выше уже открытого модального окна


            if (overModal.length === 0) {
                $(this.modalSelector).css('z-index', '5000');
            } else {
                $(this.modalSelector).css('z-index', parseInt(overModal[overModal.length - 1]) + 1);
            } // Устанавливаем сколл моадльного окна в 0


            $(this.modalSelector).find(".sm-modal-body").scrollTop(0); // Центрируем блок модалки по вертикале на IE

            if (this.verticalCenter === true) {
                this.fixCenterOnIE();
            } // Включить блюр заднего фона


            if (this.backgroundBlur === true) {
                $(this.backgroundBlurEl).css('filter', 'blur(' + this.backgroundBlurValue + ')');
            } // Анимация появления


            $(this.modalSelector).css('transition', 'all ' + this.modalSpeed + 'ms ' + this.transitionTimingFunction);
            $(this.modalSelector).find('.sm-modal-contant').css('transition', 'all ' + this.modalSpeed + 'ms ' + this.transitionTimingFunction); // Открытие модалки

            $(this.modalSelector).removeClass(this.openAnimate).addClass('active'); // Срытие скролл бара body при открытии

            if (overModal.length === 0) {
                $('body').css({
                    'overflow': 'hidden',
                    'padding-right': scrollbarWidth()
                });
            } // Модалка поверх модалки


            overModal.push($(this.modalSelector).css('z-index')); // Выполнить функцию afterOpen, если она была задана

            setTimeout(function () {
                if (_this2.afterOpen !== undefined) {
                    _this2.afterOpen();
                }
            }, this.modalSpeed);
        } // Метод закрытия модального окна

    }, {
        key: "closeModal",
        value: function closeModal() {
            var _this3 = this;

            // Выполнить функцию beforeClose, если она была задана
            if (this.beforeClose !== undefined) {
                this.beforeClose();
            }

            overModal.pop(); // Выключить блюр заднего фона

            if (this.backgroundBlur === true) {
                $(this.backgroundBlurEl).css('filter', 'blur(0px)');
            } // Закрытие модалки


            $(this.modalSelector).removeClass(this.openAnimate);
            $(this.modalSelector).addClass(this.closeAnimate).removeClass('active'); // Вернуть скролл бар body

            setTimeout(function () {
                if (overModal.length === 0) {
                    $('body').removeAttr('style');
                }

                $(_this3.modalSelector).css('transition', 'all 0s ' + _this3.transitionTimingFunction);
                $(_this3.modalSelector).find('.sm-modal-contant').css('transition', 'all 0s ' + _this3.transitionTimingFunction);
                $(_this3.modalSelector).removeClass(_this3.closeAnimate);
                $(_this3.modalSelector).addClass(_this3.openAnimate); // Выполнить функцию afterClose, если она была задана

                if (_this3.afterClose !== undefined) {
                    _this3.afterClose();
                }
            }, this.modalSpeed);
        } // Метод вертикального центрирования окна в браузере IE

    }, {
        key: "fixCenterOnIE",
        value: function fixCenterOnIE() {
            // Переменные для определения браузера IE
            var ua = window.navigator.userAgent.toLowerCase(),
                is_ie = /trident/gi.test(ua) || /msie/gi.test(ua); // Проверка на IE

            if (is_ie == true) {
                // Расчет необходимой величины padding-top b для центрирования
                var windowHeight = $(window).outerHeight();
                var contentHeight = $(this.modalSelector).find('.sm-modal-contant').outerHeight();
                var paddingTop = (windowHeight - contentHeight) / 2; // Если пользовательский паддинг меньше расчетного то центрировать блок

                if (this.currentPadding < paddingTop) {
                    $(this.modalSelector).find('.sm-modal-body').css('padding', paddingTop + 'px 0px');
                } else {
                    // В противном случае не меняем паддинг и даем блоку margin-bottom для фикса нижнего отступа на IE
                    $(this.modalSelector).find('.sm-modal-contant').css('margin-bottom', this.currentPadding + 'px');
                }
            }
        }
    }]);

    return SmModal;
}(); // Функция расчета ширины скроллбара


function scrollbarWidth() {
    var documentWidth = parseInt(document.documentElement.clientWidth);
    var windowsWidth = parseInt(window.innerWidth);
    var scrollbarWidth = windowsWidth - documentWidth;
    return scrollbarWidth;
}