(function($) {
$(document).ready(function () {
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
            itemsCustom: [[320, 3],[480, 5], [768, 6], [992, 7], [1200, 8]],
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
});
})(jQuery);