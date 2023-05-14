$(function () {
    if ($('main').hasClass('policy')) {
        var sticky = new Sticky('.sticky');

        $(".policy-main__wrap-right-wrap ul a[href^='#']").on('click', function () {
            var _href = $(this).attr("href");
            $("html, body").animate({ scrollTop: $(_href).offset().top - 30 + "px" });
            return false;
        });

        $('.policy-main__wrap-right-wrap ul li').each(function (e) {
            var thisEL = $(this).data('el');

            ScrollTrigger.create({
                trigger: "#" + thisEL,
                start: "top bottom",
                end: "bottom top",
                onUpdate: function ({ progress, direction, isActive }) {
                    if (isActive) {
                        $('[data-el="' + thisEL + '"]').addClass('active');
                    } else {
                        $('[data-el="' + thisEL + '"]').removeClass('active');
                    }
                }
            });
        });
    }
});