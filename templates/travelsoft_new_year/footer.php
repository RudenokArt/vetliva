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
            <div class="container">
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
								<input type="hidden" name="default_list_id" value="8567819">
								<input type="hidden" name="overwrite" value="2">
								<input type="hidden" name="is_v5" value="1">
							</form>
						</div>
                        <!-- End Subscribe Form -->
                        <!-- Follow us -->
                        <div class="follow-us">
                           <div class="follow-group">
								  <a href="<?= Loc::getMessage("FACEBOOK")?>" title="Vetliva Facebook" target="_blank"><i class="fa fa-facebook"></i></a>
								  <a href="<?= Loc::getMessage("INSTAGRAM")?>" title="Vetliva Instagram" target="_blank"><i class="fa fa-instagram"></i></a>
								  <a href="https://vk.com/vetliva" title="Vetliva Vkontakte" target="_blank"><i class="fa fa-vk"></i></a>
								  <a href="https://www.ok.ru/group/53238835314823" title="Vetliva Odnoklassniki" target="_blank"><i class="fa fa-odnoklassniki"></i></a>
                           </div>
                        </div>
                        <!-- Follow us -->
                     </div>
                     <!-- End Subscribe -->
                     <!-- <p class="copyright">
                        <?= Loc::getMessage("DEVELOPER")?>
                     </p> -->
                  </div>
                  <!-- End Footer Currency, Language -->
               </div>
				<div class="row">
					<div class="col-md-12">
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
						 <p class="copyright">
							© 2017 VETLIVA™ <?= Loc::getMessage("ALLRIGHTSRESERVED")?>
						 </p>
						 <!--CopyRight-->
					</div>
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
      <?$APPLICATION->ShowPanel()?>
   </body>
</html>