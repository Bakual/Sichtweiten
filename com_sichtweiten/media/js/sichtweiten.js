$(document).ready(function () {
    $('.accordion-toggle').on('click', function () {
        let button = $(this);
        let target = button.attr('data-accordion');
        let span = button.children('span').first();
        if (span.hasClass('fa-plus')) {
            $(target + ' .accordion-collapse').collapse('show');
            span.removeClass('fa-plus');
            span.addClass('fa-minus');
        } else {
            $(target + ' .accordion-collapse').collapse('hide');
            span.removeClass('fa-minus');
            span.addClass('fa-plus');
        }
    });
});
