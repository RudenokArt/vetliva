(function($) {
    $(document).ready(function () {

        $( '#slider-line' ).sliderPro({
            // width: 300,
            height: 200,
            autoplay: false,
            visibleSize: '100%',
            autoSlideSize: true,
            arrows: true,
            buttons: false,
            touchSwipe: false,
            slideAnimationDuration: 300,
        });

        $( '#slider-line-left' ).sliderPro({
            // width: 300,
            height: 200,
            autoplay: false,
            visibleSize: '100%',
            autoSlideSize: true,
            arrows: true,
            buttons: false,
            touchSwipe: false,
            slideAnimationDuration: 300,
            centerSelectedSlide: false,
            loop:false,
        });

        // instantiate fancybox when a link is clicked
        $( ".slider-pro-fb" ).each(function(){
            var slider = $( this );

            slider.find( ".sp-image" ).parent( "a" ).on( "click", function( event ) {
                event.preventDefault();

                if ( slider.hasClass( "sp-swiping" ) === false ) {
                    var sliderInstance = slider.data( "sliderPro" ),
                        isAutoplay = sliderInstance.settings.autoplay;

                    $.fancybox.open( slider.find( ".sp-image" ).parent( "a" ), {
                        index: $( this ).parents( ".sp-slide" ).index(),
                        afterShow: function() {
                            if ( isAutoplay === true ) {
                                sliderInstance.settings.autoplay = false;
                                sliderInstance.stopAutoplay();
                            }
                        },
                        afterClose: function() {
                            if ( isAutoplay === true ) {
                                sliderInstance.settings.autoplay = true;
                                sliderInstance.startAutoplay();
                            }
                        }

                    });
                }
            });
        });

        if($('#slider-room').length == 1) {
            if( document.querySelector('#slider-room')){
                document.querySelector('#slider-room').style.opacity="0";

                setTimeout(()=>{
                    document.querySelector('#search-preloader-slider').style.display = 'none';
                    document.querySelector('#slider-room').style.opacity="1";
                },2000);
            }

            $( '#slider-room' ).sliderPro({
                width: 960,
                height: 500,
                arrows: true,
                vertical:true,
                thumbnailsPosition:'right',
                buttons: false,
                lazy: true,
                waitForLayers: true,
                thumbnailWidth: 220,
                thumbnailHeight: 100,
                thumbnailPointer: true,
                autoplay: false,
                autoScaleLayers: false,
                breakpoints: {
                    500: {
                        thumbnailWidth: 0,
                        thumbnailHeight: 0
                    }
                }
            });


            DetailSlide();
            /*===== Detail Slide =====*/
            function DetailSlide(){
                var slidelager = $("#slide-room-lg");
                var slidethumnail = $("#slide-room-sm");

                slidelager.owlCarousel({
                    singleItem : true,
                    autoPlay:false,
                    navigation: true,
                    navigationText:["<span class='prev-next-room prev-room'></span>","<span class='prev-next-room next-room'></span>"],
                    pagination:false,
                });

                slidethumnail.owlCarousel({
                    mouseDrag:false,
                    navigation:true,
                    navigationText:["<span class='prev-next-room prev-room'></span>","<span class='prev-next-room next-room'></span>"],
                    itemsCustom: [[320, 1],[480, 1], [768, 1], [992, 1], [1200, 1]],
                    pagination:false
                });

                $("#slide-room-sm").on("click", ".owl-item", function(e){
                    e.preventDefault();
                    if($(this).hasClass('synced')){
                        return false;
                    } else {
                        $('.synced').removeClass('synced')
                        $(this).addClass('synced')
                        var number = $(this).data("owlItem");
                        slidelager.data('owlCarousel').goTo(number);
                    }
                });
            }
        }

    });
})(jQuery);