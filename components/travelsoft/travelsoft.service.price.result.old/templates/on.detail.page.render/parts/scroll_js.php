<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if ($_REQUEST['scroll-to-sp'] === 'Y'):
    // HACK: SCROLL TO PRICE RESULT POSITION
    ?>
$(document).ready(function () {
    var scrollTo = 0;
    if ($(window).outerWidth() >= 1200) {
        scrollTo = $('#sp').offset().top - 400;
    } else {
        scrollTo = $('#sp').offset().top - $('#header').outerHeight();
    }
    $('html, body').animate({
        scrollTop: scrollTo + 200
    }, 1000);
});
<? endif ?> 