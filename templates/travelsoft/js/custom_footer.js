(function($, bx) {
$(function() {
  var popup = null;
  if (typeof $.cookie !== "undefined" && $.cookie && !$.cookie('is_cookie_msg')) {

    popup = BX.PopupWindowManager.create("popup-cookies-notify", window.body, {
        content: $("#cookies-notify").html(),
        autoHide: true,
        closeByEsc : true,
        overlay: {
            backgroundColor: '#000', 
			opacity: 10
        }
    });

    popup.show();
    
    $("#popup-cookies-notify .arcticmodal-close").on("click", function () {
        popup.close();
    });
    
    $.cookie('is_cookie_msg', true, {
        expires: 365,
        path: '/'
      });
  }

});
})(jQuery, BX);