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
			
			<div class="footer-menu-wrap ul-ft"> 
					<div class="container">
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
			
              <div class="container">
               <div class="row">
                  <!-- Logo -->
                  <div class="col-md-8">
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
					 
					<p class="pay">
						<img loading="lazy"  src="/local/templates/travelsoft/images/cards-pay.png" title="Payment systems of online booking service in Belarus">
					</p>
					
                  </div>
                  <!-- End Logo -->
                 
                 
                  <!-- Footer Currency, Language -->
                  <div class="col-sm-6 col-md-4 subscribe-col">
				  
				   
                    <!-- Follow us -->
                  <div class="follow-us">
                           <div class="follow-group">
								  <a href="<?= Loc::getMessage("FACEBOOK")?>" rel="nofollow" title="Vetliva Facebook" target="_blank" class="follow-fb"><i class="fa fa-facebook"></i></a>
								  <a href="<?= Loc::getMessage("INSTAGRAM")?>" rel="nofollow" title="Vetliva Instagram" target="_blank" class="follow-inst"><i class="fa fa-instagram"></i></a>
								  <a href="https://vk.com/vetliva" title="Vetliva Vkontakte"  rel="nofollow" target="_blank" class="follow-vk"><i class="fa fa-vk"></i></a>
								  <a href="https://ok.ru/vetliva" title="Vetliva Odnoklassniki" rel="nofollow" target="_blank" class="follow-od"><i class="fa fa-odnoklassniki"></i></a>
								  <a href="https://www.youtube.com/channel/UCrQxDrbRy3tBWhTNOjz51TA" rel="nofollow" title="Vetliva YouTube" target="_blank" class="follow-yt"><i class="fa fa-youtube"></i></a> 
							  	  <a href="https://t.me/vetliva" title="Vetliva Telegram" rel="nofollow" target="_blank" class="follow-tg"><i class="fa fa-telegram"></i></a>

                           </div>
                    </div>
					
					
                    <!-- Follow us -->
                  
                     <!-- Subscribe -->
                     <div class="ul-ft subscribe open">
                        <!-- Subscribe Form -->
						<!-- unisender.com -->
						<div class="d-flex">
							<div class="sub-wrap">
								 <div class="label-form"><?= Loc::getMessage("SUBSCRIBE_ON")?></div>
								 <div class="subscribe-form"> 
									
									<form method="POST" action="<?= Loc::getMessage("UNISENDER")?>" name="subscribtion_form">
										<input class="subscribe-input" placeholder="<?= Loc::getMessage("YOU")?> email" type="text" name="email" value="">
										<input class="awe-btn awe-btn-5 arrow-right text-uppercase awe-btn-lager" type="submit" value="<?/*= Loc::getMessage("SUBSCRIBE")*/?>">
										<input type="hidden" name="charset" value="UTF-8">
										<input type="hidden" name="default_list_id" value="<?= Loc::getMessage("UNISENDER_ID")?>">
										<input type="hidden" name="overwrite" value="2">
										<input type="hidden" name="is_v5" value="1">
									</form>
							
								</div>
							</div>
							
							<!-- Error Find -->
							<div class="error-finder" title="<?= Loc::getMessage("ERRORFINDER_MESSAGE")?>">
								<img src="<?=SITE_TEMPLATE_PATH . "/images/mistake.svg";?>"><span><?= Loc::getMessage("ERRORFINDER")?></span>
							</div>
							<!-- Error Find -->
							
						</div> <!-- End d-flex -->
						 <!-- End Subscribe Form -->
						 
						 <div class="label-form"><?= Loc::getMessage("SEARCH_TITLE")?></div>
						<?$APPLICATION->IncludeComponent(
							"bitrix:search.title",
							"visual1",
							Array(
								"CATEGORY_0" => array(),
								"CATEGORY_0_TITLE" => "",
								"CHECK_DATES" => "N",
								"CONTAINER_ID" => "title-search",
								"INPUT_ID" => "title-search-input",
								"NUM_CATEGORIES" => "1",
								"ORDER" => "date",
								"PAGE" => "#SITE_DIR#search/",
								"SHOW_INPUT" => "Y",
								"SHOW_OTHERS" => "N",
								"TOP_COUNT" => "5",
								"USE_LANGUAGE_GUESS" => "Y"
							)
						);?>
						
                  
                     </div>
                     <!-- End Subscribe -->
                    
                  </div>
				  
				  <p class="pay-mob">
					  <img loading="lazy"  src="/local/templates/travelsoft/images/pay-system.png" title="Payment systems of online booking service in Belarus">
				  </p>
				
				   
				   <!--
				  <div class="col-md-4">
						<br>
						<p class="pay" style="background:#fff;">
						 <a target="_blank" href="https://belarusbank.by" rel="nofollow"><img loading="lazy"  alt="Belarusbank" src="/local/templates/travelsoft/images/belarusbank.png"></a>
						</p>
						<br>
				   </div> -->
                  <!-- End Footer Currency, Language -->
               </div>
				<div class="row row-copyright">

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
						 <div class="col-md-4 footer-link-col">
							 <p class="copyright" style="margin-top: 10px">
								© 2021 VETLIVA™ <?= Loc::getMessage("ALLRIGHTSRESERVED")?>
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
		<!-- start Back To Top -->
        <div id="back-to-top">
            <a href="#"><i class="fa fa-angle-up"></i></a>
        </div>
        <!-- end Back To Top -->

<!-- Код для отображения сообщения о куках -->
  <script src="<?=SITE_TEMPLATE_PATH?>/js/babel.min.js"></script>
  <script src="<?=SITE_TEMPLATE_PATH?>/js/custom_footer.js"></script>

      <?$APPLICATION->ShowPanel()?>
   </body>
</html>