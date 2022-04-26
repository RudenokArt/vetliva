
if ($('.accordion-us').length > 0) {
    $('.accordion-us-header').on('click', function () {
        let fa = $(this).find('.fa');
        let body = $(this).next('.accordion-us-body');
        if (fa.hasClass('fa-angle-up')) {
            fa.removeClass('fa-angle-up');
            fa.addClass('fa-angle-down');
            // body.removeClass('active');
            body.hide();
        } else {
            fa.removeClass('fa-angle-down');
            fa.addClass('fa-angle-up');
            // body.addClass('active');
            body.show();
        }
    })
}