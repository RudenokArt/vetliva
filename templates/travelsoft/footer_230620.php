  <?use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);?>
<?if($APPLICATION->GetDirProperty("NOT_SHOW_INDEX") != "N"):?>
                     <?if($APPLICATION->GetDirProperty("NOT_SHOW_SIDEBAR") != "Y"):?>
                  </div>
                  <?endif;?>
               </div>
            </div>
         </div>
         <?endif;?>
         <!-- Footer -->
         <footer>
            <div class="container" style="padding-left:25px;padding-right:25px;">
               <div class="row">
                  <!-- Logo -->
                  <div class="col-md-4">
                     <div class="ul-ft">
                        <?$APPLICATION->IncludeComponent(
                           "bitrix:main.include",
                           "",
                           Array(
                           	"AREA_FILE_RECURSIVE" => "Y",
                           	"AREA_FILE_SHOW" => "sect",
                           	"AREA_FILE_SUFFIX" => "inc-footertext",
                           	"EDIT_TEMPLATE" => ""
                           )
                           );?>
                     </div>
                  </div>
                  <!-- End Logo -->
                  <!-- Navigation Footer -->
                  <div class="col-sm-3 col-md-2">
                     <div class="ul-ft">
                        <?  //Главное меню сайта
                           $APPLICATION->IncludeComponent(
	"bitrix:menu", 
	"top", 
	array(
		"ROOT_MENU_TYPE" => "footertop",
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "left",
		"USE_EXT" => "N",
		"DELAY" => "N",
		"ALLOW_MULTI_SELECT" => "N",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => array(
		),
		"COMPONENT_TEMPLATE" => "top"
	),
	false
);?>
                     </div>
                  </div>
                  <!-- End Navigation Footer -->
                  <!-- Navigation Footer -->
                  <div class="col-sm-3 col-md-2">
                     <div class="ul-ft">
                        <?  //Главное меню сайта
                           $APPLICATION->IncludeComponent(
                           "bitrix:menu", 
                           "top", 
                           array(
                           "ROOT_MENU_TYPE" => "footer",
                           "MAX_LEVEL" => "1",
                           "CHILD_MENU_TYPE" => "left",
                           "USE_EXT" => "N",
                           "DELAY" => "N",
                           "ALLOW_MULTI_SELECT" => "N",
                           "MENU_CACHE_TYPE" => "A",
                           "MENU_CACHE_TIME" => "3600",
                           "MENU_CACHE_USE_GROUPS" => "Y",
                           "MENU_CACHE_GET_VARS" => array(
                           ),
                           "COMPONENT_TEMPLATE" => "top"
                           ),
                           false
                           );?>
                     </div>
                  </div>
                  <!-- End Navigation Footer -->
                  <!-- Footer Currency, Language -->
                  <div class="col-sm-6 col-md-4">
                     <div class="ul-ft">
                        <?  //Главное меню сайта
                           $APPLICATION->IncludeComponent(
                           "bitrix:menu", 
                           "top", 
                           array(
                           "ROOT_MENU_TYPE" => "footerenter",
                           "MAX_LEVEL" => "1",
                           "CHILD_MENU_TYPE" => "left",
                           "USE_EXT" => "N",
                           "DELAY" => "N",
                           "ALLOW_MULTI_SELECT" => "N",
                           "MENU_CACHE_TYPE" => "A",
                           "MENU_CACHE_TIME" => "3600",
                           "MENU_CACHE_USE_GROUPS" => "Y",
                           "MENU_CACHE_GET_VARS" => array(
                           ),
                           "COMPONENT_TEMPLATE" => "top"
                           ),
                           false
                           );?>
                     </div>
                     <!-- Subscribe -->
                     <div class="ul-ft subscribe open">
                        <!-- Subscribe Form -->
                        <div class="clear"></div>
						<!-- unisender.com -->
						<div class="subscribe-form">
							<form method="POST" action="<?= Loc::getMessage("UNISENDER")?>" name="subscribtion_form">
								<input class="subscribe-input" placeholder="<?= Loc::getMessage("YOU")?> email" type="text" name="email" value="">
								<input class="awe-btn awe-btn-5 arrow-right text-uppercase awe-btn-lager" type="submit" value="<?= Loc::getMessage("SUBSCRIBE")?>">
								<input type="hidden" name="charset" value="UTF-8">
								<input type="hidden" name="default_list_id" value="<?= Loc::getMessage("UNISENDER_ID")?>">
								<input type="hidden" name="overwrite" value="2">
								<input type="hidden" name="is_v5" value="1">
							</form>
						</div>
                        <!-- End Subscribe Form -->
                        <!-- Follow us -->
                        <div class="follow-us">
                           <div class="follow-group">
								  <a href="<?= Loc::getMessage("FACEBOOK")?>" rel="nofollow" title="Vetliva Facebook" target="_blank"><i class="fa fa-facebook"></i></a>
								  <a href="<?= Loc::getMessage("INSTAGRAM")?>" rel="nofollow" title="Vetliva Instagram" target="_blank"><i class="fa fa-instagram"></i></a>
								  <a href="https://vk.com/vetliva" title="Vetliva Vkontakte"  rel="nofollow" target="_blank"><i class="fa fa-vk"></i></a>
								  <a href="https://ok.ru/vetliva" title="Vetliva Odnoklassniki" rel="nofollow" target="_blank"><i class="fa fa-odnoklassniki"></i></a>
								  <a href="https://www.youtube.com/channel/UCrQxDrbRy3tBWhTNOjz51TA" rel="nofollow" title="Vetliva YouTube" target="_blank"><i class="fa fa-youtube"></i></a>
							  	  <a href="https://t.me/vetliva" title="Vetliva Telegram" rel="nofollow" target="_blank"><i class="fa fa-telegram"></i></a>

                           </div>
                        </div>
                        <!-- Follow us -->
                        <!-- Error Find -->
                        <div class="error-finder">
							<?= Loc::getMessage("ERRORFINDER")?> <b>Ctrl+Enter</b>
                        </div>
                        <!-- Error Find -->
                     </div>
                     <!-- End Subscribe -->
                     <!-- <p class="copyright">
                        <?= Loc::getMessage("DEVELOPER")?>
                     </p> -->
                  </div>
				   <div class="col-md-4">
						<br>
						<p class="pay">
						 <img src="/local/templates/travelsoft/images/pay.png" title="Payment systems of online booking service in Belarus">
						</p>
						<br>
				   </div>
				   <div class="col-md-4">
						<br>
						<p class="pay" style="background:#fff;">
						 <a target="_blank" href="https://belarusbank.by" rel="nofollow"><img alt="Belarusbank" src="/local/templates/travelsoft/images/belarusbank.png"></a>
						</p>
						<br>
				   </div>
                  <!-- End Footer Currency, Language -->
               </div>
				<div class="row">

                        <?$APPLICATION->IncludeComponent(
                           "bitrix:main.include",
                           "",
                           Array(
                           	"AREA_FILE_RECURSIVE" => "Y",
                           	"AREA_FILE_SHOW" => "sect",
                           	"AREA_FILE_SUFFIX" => "inc-footerlink",
                           	"EDIT_TEMPLATE" => ""
                           )
                           );?>
						 <!--CopyRight-->
						 <div class="col-md-4 footer-link-col" style="width:28%">
							 <p class="copyright" style="margin-top: 10px">
								© 2020 VETLIVA™ <?= Loc::getMessage("ALLRIGHTSRESERVED")?>
							 </p>
						</div>
						 <!--CopyRight-->

						<script>
						$(document).ready(function() {
							if(navigator.userAgent.match(/Android|iPhone|iPad|iPod|BlackBerry|Opera Mini|IEMobile/i))
							{
								$(".footer-link-col").removeAttr("style");
							}
						});
						</script>
				</div>
            </div>
         </footer>
         <!-- End Footer -->
      </div>
		<?//include_once ($_SERVER['DOCUMENT_ROOT']."/popup/hellouser.php");?>
		<!-- start Back To Top -->
        <div id="back-to-top">
            <a href="#"><i class="fa fa-angle-up"></i></a>
        </div>
        <!-- end Back To Top -->

<!-- Код для отображения сообщения о куках -->
  <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
<script>
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


</script>

		<!-- Таймер для отображения банера по экскурсиям 
       <script>
            $(document).ready(function(){

				var isMobileNot = {
					Android: function () {
						return navigator.userAgent.match(/Android/i);
					},
					BlackBerry: function () {
						return navigator.userAgent.match(/BlackBerry/i);
					},
					iOS: function () {
						return navigator.userAgent.match(/iPhone|iPad|iPod/i);
					},
					Opera: function () {
						return navigator.userAgent.match(/Opera Mini/i);
					},
					Windows: function () {
						return navigator.userAgent.match(/IEMobile/i);
					},
					any: function () {
						return (isMobileNot.Android() || isMobileNot.BlackBerry() || isMobileNot.iOS() || isMobileNot.Opera() || isMobileNot.Windows());
					}
				}

				if(typeof $.cookie !== "undefined" && $.cookie && !$.cookie('egnotify')) {
					if(isMobileNot.any()) { $(".modal-dialog.modal-side.modal-top-right").attr("style", "width:50%;height:50%;"); }
					setTimeout("$('#EGNotify').modal('show');", 25000);
					setTimeout("$.cookie('egnotify',true,{expires: 365,path:'/'});", 25000);
					$("body").css("overflow", "auto");
				}
            });
        </script>
		-->

      <?$APPLICATION->ShowPanel()?>
   </body>
</html>