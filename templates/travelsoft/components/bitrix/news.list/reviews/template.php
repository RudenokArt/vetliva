<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
/**$this->addExternalJS("/local/templates/partners/js/core/libraries/jquery.fancybox.js");
$this->addExternalCss("/local/templates/partners/css/jquery.fancybox.css");*/

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/slick-slider.min.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/slick.min.js");
?>
<?$this->SetViewTarget("menu-item-review");?>
        <li><a data-toggle="tab" href="#iblock_detail_reviews"><?=GetMessage('REVIEWS_TITLE_MENU')?></a></li>
<?$this->EndViewTarget();?>
<h3 style="color:#264B87;padding-top:20px;font-size:25px;font-weight:300;" class="review-block-h"><?= GetMessage('REVIEW_BLOCK') ?></h3>	
<? if(empty($arResult["ITEMS"])): ?>
<span><?=GetMessage('REVIEW_TEXT')?></span>
<? endif; ?>
<?if(!empty($arResult["ITEMS"])):?>

	<!-- Review Tabs -->
    <?if($arParams["DISPLAY_RATING_BLOCK"] == "Y" || !isset($arParams["DISPLAY_RATING_BLOCK"])):?>
		<?$params = array();
        $params = array(
            "price_quality" => !is_nan(round($arResult["PRICE_QUALITY"]["SUMM"]/$arResult["PRICE_QUALITY"]["COUNT"], 1)) ? round($arResult["PRICE_QUALITY"]["SUMM"]/$arResult["PRICE_QUALITY"]["COUNT"], 1) : 0,
            "location" => !is_nan(round($arResult["LOCATION"]["SUMM"]/$arResult["LOCATION"]["COUNT"], 1)) ? round($arResult["LOCATION"]["SUMM"]/$arResult["LOCATION"]["COUNT"], 1) : 0,
            "staff" => !is_nan(round($arResult["STAFF"]["SUMM"]/$arResult["STAFF"]["COUNT"], 1)) ? round($arResult["STAFF"]["SUMM"]/$arResult["STAFF"]["COUNT"], 1) : 0,
            "purity" => !is_nan(round($arResult["PURITY"]["SUMM"]/$arResult["PURITY"]["COUNT"], 1)) ? round($arResult["PURITY"]["SUMM"]/$arResult["PURITY"]["COUNT"], 1) : 0,
            "rooms" => !is_nan(round($arResult["ROOMS"]["SUMM"]/$arResult["ROOMS"]["COUNT"], 1)) ? round($arResult["ROOMS"]["SUMM"]/$arResult["ROOMS"]["COUNT"], 1) : 0,
            "food" => !is_nan(round($arResult["FOOD"]["SUMM"]/$arResult["FOOD"]["COUNT"], 1)) ? round($arResult["FOOD"]["SUMM"]/$arResult["FOOD"]["COUNT"], 1) : 0
        );?>
	<div class="review-tabs">
		<!--<h3><?/*=getMessage('REVIEWS_CLIENTS')*/?></h3>-->
		<!-- Tabs Content -->
		<div class="tab-content">
			<div id="section1" class="tab-pane fade in active">
				<div class="review-tabs-cn">
					<div class="row ts-px-4">
						<div class="col-sm-4 col-md-3 col-lg-4 col-lg-push-8 col-md-push-9 col-sm-push-8">
							<div class="review-vote text-center">
								<h3>
									<?=GetMessage('RATING')?>
								</h3>
								<span class="vote-score"><?=$arResult["RATING"]?></span>
								<span class="vote-number"><?=GetMessage('ALL_REVIEWS')?>: <strong><?=count($arResult["ITEMS"])?></strong></span>
								<p>
									<span><strong><?=$arResult["RECOMMEND"]?></strong>%</span>
									<?=GetMessage('RECOMMEND')?>
								</p>
							</div>
						</div>
						<div class="col-sm-8 col-md-9 col-lg-8 col-lg-pull-4 col-md-pull-3 col-sm-pull-4">
							<div class="review-st">
								<!-- Rule -->
								<div class="row row-rule">
									<div class="col-md-5 lable-st">&nbsp;</div>
									<div class="col-md-7">
										<div class="rule-point">
											<span>0</span>
											<span>1</span>
											<span>2</span>
											<span>3</span>
											<span>4</span>
											<span>5</span>
										</div>
									</div>
								</div>
								<!-- End Rule -->
								<!-- Item -->
								<div class="row">
									<div class="col-md-5 lable-st"><?=GetMessage('PRICE_QUALITY')?></div>
									<div class="col-md-7">
										<div class="progress-rv" data-value="<?=$params["price_quality"]?>"></div>
									</div>
								</div>
								<!-- End Item -->
								<!-- Item -->
								<div class="row">
									<div class="col-md-5 lable-st"><?=GetMessage('LOCATION')?></div>
									<div class="col-md-7">
										<div class="progress-rv" data-value="<?=$params["location"]?>"></div>
									</div>
								</div>
								<!-- End Item -->
								<!-- Item -->
								<div class="row">
									<div class="col-md-5 lable-st"><?=GetMessage('STAFF')?></div>
									<div class="col-md-7">
										<div class="progress-rv" data-value="<?=$params["staff"]?>"></div>
									</div>
								</div>
								<!-- End Item -->
								<!-- Item -->
								<div class="row">
									<div class="lable-st col-md-5"><?=GetMessage('PURITY')?></div>
									<div class="col-md-7">
										<div class="progress-rv" data-value="<?=$params["purity"]?>"></div>
									</div>
								</div>
								<!-- End Item -->
								<!-- Item -->
								<div class="row">
									<div class="lable-st col-md-5"><?=GetMessage('ROOMS')?></div>
									<div class="col-md-7">
										<div class="progress-rv" data-value="<?=$params["rooms"]?>"></div>
									</div>
								</div>
								<!-- End Item -->
								<!-- Item -->
								<div class="row">
									<div class="lable-st col-md-5"><?=GetMessage('FOOD')?></div>
									<div class="col-md-7">
										<div class="progress-rv" data-value="<?=$params["food"]?>"></div>
									</div>
								</div>
								<!-- End Item -->
							</div>
						</div>

					</div>
				</div>
			</div>
			<div id="section2" class="tab-pane fade">

			</div>
		</div>
		<!-- Tabs Content -->
	</div>
    <?endif;?>
	<!-- End Review Tabs -->
	<!-- Review All -->
	<div class="review-all js-show-hide-wrp">
        <div class="list-title-block">
            <h4 class="review-h list-title-block__title">
                <?=GetMessage('ALL_REVIEWS2')?> (<?=count($arResult["ITEMS"])?>)
            </h4>
			
			 <?/*if (!$USER->IsAuthorized()):*/?>
			 
			 	<a href="#header-auth-popup" class="show-header-auth-popup review-flex-mob">
					<div class="btn-add-review-wrap">
						<img class="btn-add-review btn-add-review-mob" src="<?=SITE_TEMPLATE_PATH."/images/btn_add_review" . POSTFIX_PROPERTY .".png"?>">
						<img class="btn-add-review1 btn-add-review-mob" src="<?=SITE_TEMPLATE_PATH."/images/btn_add_review_up" . POSTFIX_PROPERTY .".png"?>">
					</div>
				</a>
                <!--a href="#header-auth-popup" class="show-header-auth-popup add-to-cart awe-btn awe-btn-1 awe-btn-small"><?=GetMessage('ADD_REVIEWS_SUBMIT_TEXT')?></a-->
            
			<?/*endif*/?>
						 
            <?php if(count($arResult['ITEMS']) > 1):?>
                <button class="list-title-block__button list-title-block__button--more list-title-block__button--more--desc awe-btn awe-btn-5 arrow-right awe-btn-lager text-uppercase js-show-hide-btn" type="button" aria-label="<?=GetMessage('SHOW_ALL')?>"><?=GetMessage('SHOW_ALL')?></button>
            <?php endif?>
        </div>
		<?$imgAvatar = NO_PHOTO_PEOPLE_PATH;?>
		<?foreach ($arResult["ITEMS"] as $key=>$item):?>
            <?//dm($item,false,false,true);?>
		<!-- Review Item -->
			<div class="row review-item<?=($key > 0) ? ' js-show-hide-element' : ''?> num-review-<?=($key)?> ">
				<div class="col-xs-3 review-number">
					<!--<ins><?/*=$item["ITEM_RATING"]*/?></ins>-->
					<ins>
						<?if(!empty($item["PROPERTIES"]["USER"]["VALUE"])):?>
                            <?$rsUser = CUser::GetByID($item["PROPERTIES"]["USER"]["VALUE"]);
                            $arUser = $rsUser->Fetch();
                            if(!empty($arUser["PERSONAL_PHOTO"])){
                                $file = CFile::ResizeImageGet($arUser["PERSONAL_PHOTO"], array('width'=>90, 'height'=>90), BX_RESIZE_IMAGE_EXACT, true);
                                $imgAvatar = $file["src"];
                            }?>
                        <?endif?>
                        <img class="img-avatar" src="<?=$imgAvatar?>" alt="<?=$item["PROPERTIES"]["USER_NAME"]["VALUE"]?>">
					</ins>
					<span><?=$item["PROPERTIES"]["USER_NAME"]["VALUE"]?></span>
					<small><?if(strlen($item["PROPERTIES"]["COUNTRY"]["VALUE"]) > 0):?><?=$item["PROPERTIES"]["COUNTRY"]["VALUE"]?><?endif?> <?=$item["DISPLAY_DATE_CREATE"]?></small>
				</div>
				
				<?if(!empty($item["PROPERTIES"]["PHOTO"]["VALUE"])):?>
                        <div class="gallery-review--mob <?if(count($item["PROPERTIES"]["PHOTO"]["VALUE"]) > 1):?> slider-rev <?else:?>not-slider-rev<?endif?>">
                            <?$j = 1;?>
                            <?foreach ($item["PROPERTIES"]["PHOTO"]["VALUE"] as $img):?>
                                <?$file_real = CFile::GetPath($img);
                                $file_small = CFile::ResizeImageGet($img, Array('width' => 200, 'height' => 133), BX_RESIZE_IMAGE_EXACT, true);?>
                                <a class="forfancy images-<?=$j?>" data-fancybox="gallery-mob-<?=($key)?>" href="<?=$file_real?>"><img src="<?=$file_small["src"]?>" alt="<?=$arResult["ITEMS_NAME"][$item["PROPERTIES"]["ITEM"]["VALUE"]]?>-<?=$j?>"  /></a>
                                <?$j++?>
                            <?endforeach;?>
                        </div>
                    <?endif?>
					
					
				<div class="col-xs-9 review-text">
                    <?if($item["ACTIVE"] != "Y"):?>
                        <span style="float:right;font-size: 12px;text-decoration: underline;"><?=GetMessage('NOT_ACTIVE')?></span>
                        <div style="clear: both"></div>
                    <?endif?>
					
					<?if(!empty($item["PROPERTIES"]["PHOTO"]["VALUE"])):?>
                        <div style="padding-top: 15px;" class="gallery-review--desc" id="slider-<?=($key)?>">
                            <?$j = 1;?>
                            <?foreach ($item["PROPERTIES"]["PHOTO"]["VALUE"] as $img):?>
                                <?$file_real = CFile::GetPath($img);
                                $file_small = CFile::ResizeImageGet($img, Array('width' => 90, 'height' => 60), BX_RESIZE_IMAGE_EXACT, true);?>
									<a class="forfancy images-<?=$j?>" data-fancybox="gallery-<?=($key)?>" href="<?=$file_real?>"><img src="<?=$file_small["src"]?>" alt="<?=$arResult["ITEMS_NAME"][$item["PROPERTIES"]["ITEM"]["VALUE"]]?>-<?=$j?>"  /></a>
                                <?$j++?>
                            <?endforeach;?>
                        </div>
                    <?endif?>
					<?if(!empty($item["PREVIEW_TEXT"])):?>
						<div class="rev-block">
							<div class="reviews-content-text">
								<p>
									<?=$item["PREVIEW_TEXT"]?>
								</p>
							</div>
							<div class="reviews-content-button">
								<?=GetMessage('READ_MORE')?>
							</div>
						</div>
					<?endif?>
                                        <? if(!empty($item["PROPERTIES"]['ANSWER']['VALUE']['TEXT'])):?>
                                        <? // if(true):?>
						<div class="rev-block">
                                                    <label>Ответ:</label>
							<div class="reviews-content-text">
								<p>
									<?=$item["PROPERTIES"]['ANSWER']['VALUE']['TEXT']?>
								</p>
							</div>
						</div>
					<?endif?>
				</div>
			</div>
			<!-- End Review Item -->
		<?endforeach;?>
		
		 <?php if(count($arResult['ITEMS']) > 1):?>
		 <div class="review-more_btn">
                <button class="list-title-block__button list-title-block__button--more list-title-block__button--more--mob awe-btn awe-btn-5 arrow-right awe-btn-lager text-uppercase js-show-hide-btn" type="button" aria-label="<?=GetMessage('SHOW_ALL')?>"><?=GetMessage('SHOW_ALL')?></button>
         </div>
		 <?php endif?>
	</div>
	<!-- End Review All -->
<?else:?>
    <?if(isset($arParams["DISPLAY_RATING_BLOCK"]) && $arParams["DISPLAY_RATING_BLOCK"] != "Y"):?>
        <div class="alert alert-danger mt-20" role="alert"><?=GetMessage("EMPTY_REVIEWS");?></div>
    <?endif;?>
<?endif?>

<script>
	$('.rev-block').each(function () {
		var hgth = $(this).find(".reviews-content-text").height();
		var hgth_p = $(this).find(".reviews-content-text p").height();
		if (hgth > hgth_p)
			$(this).find(".reviews-content-button").css('display', 'none');
	});

	$(".reviews-content-button").click(function () {
		$('.reviews-content-text').show(function () {
			var reducedHeight = $(this).height();
			$(this).css('height', 'auto');
			var fullHeight = $(this).height();
			$(this).height(reducedHeight);
			$(this).animate({height: fullHeight}, 500);
		});
		$(this).css('display', 'none');
	});
	
	$(".list-title-block__button--more--mob").click(function (){
		
	    $(this).toggleClass('not-show');
	
	});

	$('.forfancy').fancybox({
        image : {
            protect: true
        } 
    });

	
		$( ".slider-rev" ).each(function() {
			$(this).slick({
				slidesToShow: 1,
				slidesToScroll: 1,
				arrows: true,
				infinite: true
			});
		});
	


	
	
</script>

