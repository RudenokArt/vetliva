$(document).ready(function (){
	

		
    $(".header-lang").magnificPopup({
        type: "inline",
        mainClass: 'mfp-lang-mobile',
        midClick: true
    });
	$('.tb-cell .menu-parent a').click(function(event){
	  event.preventDefault();
	});
		
	$('.mt-5:has(.class-auto-description)').addClass('mt-5-auto-desc');
	$('.wrong_male').parent('.form-field').addClass('male-field');
	$('.wrong_citizenship').parent('.form-field').addClass('citizen-field');
	$('.favorite-block .destinations-text').matchHeight();
	$('.favorite-block .destinations-name').matchHeight();
	$('.favorite-block .home-destinations-places').matchHeight();
	$('#interesting-slide .home-sales-text').matchHeight();
	$('.sales .home-sales-text').matchHeight();
	$('.destinations-name').matchHeight();
	$('.sales .home-sales-name').matchHeight();


	$('.sights_list .js-show-hide-btn').click(function(event){
	   $(this).toggleClass('not_show');
	});
		
	
	if ($(window).width() > 960) {	
		$('li.header-phone').hover(function(){
			$('.overlay').toggleClass('show-overlay');
		});
		
	}
	
	if ($(window).width() < 960) {	
		$('.news-section .home-sales-text').matchHeight();
		
		$('.header-phone > a').on('click', function(){
			event.preventDefault();
			$('.sub-menu.phone').toggleClass('opened_menu');
			$('.overlay').toggleClass('show-overlay');
		});
		
		$('.overlay').click(function(e) {
		    var div = $('.sub-menu.phone'); 
			
			if (!div.is(e.target) // если клик был не по нашему блоку
				&& div.has(e.target).length === 0) { // и не по его дочерним элементам
				$('.overlay').toggleClass('show-overlay');
				$('.sub-menu.phone').toggleClass('opened_menu');
			} 
			
			//$('.overlay').toggleClass('show-overlay');
			//$('.sub-menu.phone').toggleClass('opened_menu');
				
		});
		
		$('input[type="text"]').focusin(function() {
			$('body').addClass('focused');
		});

		$('input[type="text"]').focusout(function() {
			$('body').removeClass('focused');
		});
		
	}
	
		
		if(navigator.userAgent.toLowerCase().indexOf('Firefox') != -1){
			$("body").addClass("fire");
		}

		  
});



		$('.catalog_element').each(function(){
			  var height = $(this).height();
			  console.log("Высота блока " + height); 
			  var clientHeight = $(window).height();
		      var clientHeightNew = clientHeight * 0.50;
			  console.log(clientHeightNew); 
			  
			   if(height < clientHeightNew){
				   $(this).addClass('w_max');
				   $(this).closest('section').addClass('hide-btn');
					  } else {
				   $(this).addClass('with_max');
				   $(this).closest('section').removeClass('hide-btn');
				   $(this).closest('section').addClass('show-btn');
			   }
		});

function MenuResponsive() {
    var WindowWidth = $(window).width();
    var menuType = $('.navigation').data('menu-type'),
            windowWidth = window.innerWidth,
            _Navigation = $('.navigation'),
            _Header = $('.header');
    if (windowWidth < menuType) {
        _Navigation
                .addClass('nav')
                .removeClass('nav-desktop')
                .closest('.header');
        _Header.next().css('margin-top', 0);
        $('.bars, .bars-close, .logo-banner').show();

        $('.navigation .sub-menu').each(function () {
            $(this)
                    .removeClass('left right');
        });
    } else {
        _Navigation
                .removeClass('nav')
                .addClass('nav-desktop')
                .closest('.header');
        _Header
                .css('background-color', '#fff')
                .find('.logo')
                .css({
                    'opacity': '1',
                    'visibility': 'visible'
                });
        _Header.next().css('margin-top', $('.header').height());
        $('.bars, .bars-close, .logo-banner').hide();

        $('.navigation .sub-menu').each(function () {
            var offsetLeft = $(this).offset().left,
                    width = $(this).width(),
                    offsetRight = (WindowWidth - (offsetLeft + width));
            if (offsetRight < 60) {
                $(this)
                        .removeClass('left')
                        .addClass('right');
            } else {
                $(this)
                        .removeClass('right');
            }
            if (offsetLeft < 60) {
                $(this)
                        .removeClass('right')
                        .addClass('left');
            } else {
                $(this)
                        .removeClass('left');
            }
        });
    }
}