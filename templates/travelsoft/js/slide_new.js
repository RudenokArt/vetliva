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
            navigation:false,
            itemsCustom: [[320, 1],[480, 1], [768, 1], [992, 1], [1200, 1]],
            pagination:false
        });
        if(document.querySelectorAll('#slide-room-sm .owl-item').length <= 3){
            document.querySelector('.nav-slide.prev').style.display = 'none';
            document.querySelector('.nav-slide.next').style.display = 'none';
        }
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

           let sizer = 0;

          document.querySelector('.button-next').onclick = (e) =>{
              let itemHeight = document.querySelectorAll('#slide-room-sm .owl-item');
              [].slice.call
              let maxHeight = (itemHeight.length-5) * itemHeight[0].offsetHeight;

              if(sizer >= -maxHeight){

                  sizer -= itemHeight[0].offsetHeight;
                  document.querySelector('#slide-room-sm .owl-wrapper').style = `transform: translateY(${sizer}px)`;
              }

          }
        document.querySelector('.button-prev').onclick = (e) =>{
            let itemHeight = document.querySelectorAll('#slide-room-sm .owl-item');

            console.log(sizer);
            if(sizer <= -itemHeight[0].offsetHeight){
                sizer += itemHeight[0].offsetHeight;
                document.querySelector('#slide-room-sm .owl-wrapper').style = `transform: translateY(${sizer}px)`;
            }
            else{
                sizer = 0;
                document.querySelector('#slide-room-sm .owl-wrapper').style = `transform: translateY(${sizer}px)`;
            }

        }

    }
});
})(jQuery);