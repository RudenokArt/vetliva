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
$this->addExternalJS("/local/templates/partners/js/core/libraries/jquery.fancybox.js");
$this->addExternalCss("/local/templates/partners/css/jquery.fancybox.css");
?>
<?$this->SetViewTarget("menu-item-review");?>
        <li><a href="#iblock_detail_reviews" class="anchor"><?=GetMessage('REVIEWS_TITLE_MENU')?></a></li>
<?$this->EndViewTarget();?>
<h3 style="color:#264B87;padding-top:20px;font-size:25px;font-weight:300;"><?= GetMessage('REVIEW_BLOCK') ?></h3>	
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
            "staff" => !is_nan(round($arResult["STAFF"]["SUMM"]/$arResult["STAFF"]["COUNT"], 1)) ? round($arResult["STAFF"]["SUMM"]/$arResult["STAFF"]["COUNT"], 1) : 0
        );?>
	<div class="review-tabs">
		<!--<h3><?/*=getMessage('REVIEWS_CLIENTS')*/?></h3>-->
		<!-- Tabs Content -->
		<div class="tab-content">
			<div id="section1" class="tab-pane fade in active">
				<div class="review-tabs-cn">
					<div class="row ts-px-4" style="padding-right: calc(4rem/2) !important; padding-left: calc(4rem/2) !important;">
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
            <? if(count($arResult['ITEMS']) > 0):?>
                <button class="list-title-block__button list-title-block__button--more awe-btn awe-btn-5 arrow-right awe-btn-lager text-uppercase js-show-hide-btn" type="button" aria-label="<?=GetMessage('SHOW_ALL')?>"><?=GetMessage('SHOW_ALL')?></button>
            <? endif?>
        </div>
		<?$imgAvatar = NO_PHOTO_PEOPLE_PATH;?>
		<?foreach ($arResult["ITEMS"] as $key=>$item):?>
            <?//dm($item,false,false,true);?>
		<!-- Review Item -->
			<div class="row review-item<?=($key > 0) ? ' js-show-hide-element' : ''?>">
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
				<div class="col-xs-9 review-text">
                    <?if($item["ACTIVE"] != "Y"):?>
                        <span style="float:right;font-size: 12px;text-decoration: underline;"><?=GetMessage('NOT_ACTIVE')?></span>
                        <div style="clear: both"></div>
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
                                                    <label>??????????:</label>
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

	for(var i=1;i<='<?=count($arResult["ITEMS"])?>';i++){
        $('[data-fancybox="images-' + i + '"]').fancybox({
            image : {
                protect: true
            }
        });
    }
</script>

