$(function () {
    // if (window.innerWidth >= 992) {
    $('.simple-select__drop-inner').mCustomScrollbar({
        scrollInertia: 0, //500
        scrollEasing: "linear"
    });
    // } else {
    // $('.selectmenu__drop-inner').mCustomScrollbar('destroy');
    // }
    $(document).off('.simple-select');
    $(document).on('click.simple-select', '.simple-select .simple-select__main', function (e) {
        let $dropdown = $(this).closest('.simple-select');

        $('.simple-select').not($dropdown).removeClass('is-active');
        $dropdown.toggleClass('is-active');
        if (e.originalEvent) {
            $dropdown.find('.focus').removeClass('focus');
            return;
        }
        if ($dropdown.hasClass('is-active')) {
            $dropdown.find('.focus').removeClass('focus');
            if ($dropdown.find('.simple-select__item.is-active').length) {
                $dropdown.find('.is-active').addClass('focus');
            } else {
                $dropdown.find('.simple-select__item').first().addClass('focus');
            }
        } else {
            $dropdown.focus();
        }
    });
    $(document).on('click.selectmenu', '.selectmenu .selectmenu__main', function (e) {
        let $dropdown = $(this).closest('.selectmenu');

        $('.selectmenu').not($dropdown).removeClass('is-active');
        $dropdown.toggleClass('is-active');
        if (e.originalEvent) {
            $dropdown.find('.focus').removeClass('focus');
            return;
        }
        if ($dropdown.hasClass('is-active')) {
            $dropdown.find('.focus').removeClass('focus');
            if ($dropdown.find('.selectmenu__item.is-active').length) {
                $dropdown.find('.is-active').addClass('focus');
            } else {
                $dropdown.find('.selectmenu__item').first().addClass('focus');
            }
        } else {
            $dropdown.focus();
        }
    });
    $(document).on('click.simple-select', '.simple-select .simple-select__item:not(.is-active)', function (e) {
        let val = $(this).data('value');
        let select = $(this).closest('.simple-select');
        let text = $(this).text();

        select.removeClass('is-active');
        select.find('.simple-select__item').removeClass('is-active');
        select.find('.simple-select__selected').text(text);
        select.find('input').val(val).change();
        $(this).addClass('is-active').blur(); //blur для закрытия списка, из-за стилей, которые позволяют открыть список при фокусе на эл. списка
    });
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.simple-select').length) {
            $('.simple-select').removeClass('is-active');
        }
        if (!$(e.target).closest('.selectmenu').length && window.innerWidth >= 992) {
            $('.selectmenu').removeClass('is-active');
        }
    });
    $(document).on('keydown.simple-select', '.simple-select', function (event) {
        let $dropdown = $(this);
        let $toggle = $dropdown.find('.simple-select__main');
        let $focused_option = $($dropdown.find('.focus') || $dropdown.find('.simple-select__item.is-active'));
        $focused_option.length === 0 ? $focused_option = $dropdown.find('.simple-select__item').first() : '';
        if (event.keyCode === 32 || event.keyCode === 13) { // Space or Enter
            if ($dropdown.hasClass('is-active')) {
                $focused_option.trigger('click');
            } else {
                $toggle.trigger('click');
            }
            return false;
        } else if (event.keyCode === 40) { // Down
            if (!$dropdown.hasClass('is-active')) {
                $toggle.trigger('click');
            } else {
                let $next = $focused_option.nextAll('.simple-select__item:not(.disabled)').first();
                if ($next.length > 0) {
                    $dropdown.find('.focus').removeClass('focus');
                    $next.addClass('focus');
                }
            }
            return false;
        } else if (event.keyCode === 38) { // Up
            if (!$dropdown.hasClass('is-active')) {
                $toggle.trigger('click');
            } else {
                var $prev = $focused_option.prevAll('.simple-select__item:not(.disabled)').first();
                if ($prev.length > 0) {
                    $dropdown.find('.focus').removeClass('focus');
                    $prev.addClass('focus');
                }
            }
            return false;
        } else if (event.keyCode === 27) { // Esc
            if ($dropdown.hasClass('is-active')) {
                $toggle.trigger('click');
            }
        } else if (event.keyCode === 9) { // Tab
            if ($dropdown.hasClass('is-active')) {
                return false;
            }
        }
    });
    $('.selectmenu__back').click(function () {
        $(this).closest('.selectmenu').removeClass('is-active');
    });
});

function resetSelect(select) {
    $(select).each(function (i, el) {
        let pb = $(this);

        let defaultValue = pb.find('input').data('default-value');
        let placeholder = pb.find('.simple-select__selected').data('placeholder');

        if (defaultValue && !placeholder) {
            let item = pb.find(`.simple-select__item[data-value=${defaultValue}]`);

            pb.find('input').val(defaultValue);
            pb.find('.simple-select__selected').text(item.eq(0).text());
            pb.find('.simple-select__item').removeClass('is-active');
            item.eq(0).addClass('is-active');
        } else {
            pb.find('input').val('');
            pb.find('.simple-select__selected').text('');
            pb.find('.simple-select__item').removeClass('is-active');
        }
    });
}