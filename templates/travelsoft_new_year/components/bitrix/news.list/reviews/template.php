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
<?if(!empty($arResult["ITEMS"])):?>

    <?$this->SetViewTarget("menu-item-review");?>
        <li><a href="#iblock_detail_reviews" class="anchor"><?=GetMessage('REVIEWS_TITLE_MENU')?></a></li>
    <?$this->EndViewTarget();?>

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
					<div class="row">
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
	<div class="review-all">
		<h4 class="review-h">
			<?=GetMessage('ALL_REVIEWS2')?> (<?=count($arResult["ITEMS"])?>)
		</h4>
		<?$img = NO_PHOTO_PEOPLE_PATH;?>
		<?foreach ($arResult["ITEMS"] as $key=>$item):?>
            <?//dm($item,false,false,true);?>
		<!-- Review Item -->
			<div class="row review-item">
				<div class="col-xs-3 review-number">
					<!--<ins><?/*=$item["ITEM_RATING"]*/?></ins>-->
					<ins>
						<?if(!empty($item["PROPERTIES"]["USER"]["VALUE"])):?>
                            <?$rsUser = CUser::GetByID($item["PROPERTIES"]["USER"]["VALUE"]);
                            $arUser = $rsUser->Fetch();
                            if(!empty($arUser["PERSONAL_PHOTO"])){
                                $file = CFile::ResizeImageGet($arUser["PERSONAL_PHOTO"], array('width'=>90, 'height'=>90), BX_RESIZE_IMAGE_EXACT, true);
                                $img = $file["src"];
                            }?>
                        <?endif?>
                        <img class="img-avatar" src="<?=$img?>" alt="<?=$item["PROPERTIES"]["USER_NAME"]["VALUE"]?>">
					</ins>
					<span><?=$item["PROPERTIES"]["USER_NAME"]["VALUE"]?></span>
					<small><?if(strlen($item["PROPERTIES"]["COUNTRY"]["VALUE"]) > 0):?><?=$item["PROPERTIES"]["COUNTRY"]["VALUE"]?><?if(strlen($item["PROPERTIES"]["CITY"]["VALUE"]) > 0):?> ,<?$flag = true;?><?endif?><?endif?> <?if($flag):?><?=$item["PROPERTIES"]["CITY"]["VALUE"]?>, <?endif?><?=$item["DISPLAY_DATE_CREATE"]?></small>
				</div>
				<div class="col-xs-9 review-text">
                    <?if($item["ACTIVE"] != "Y"):?>
                        <span style="float:right;font-size: 12px;text-decoration: underline;"><?=GetMessage('NOT_ACTIVE')?></span>
                        <div style="clear: both"></div>
                    <?endif?>
					<?if(strlen($item["PROPERTIES"]["PLUS"]["VALUE"]) > 0 || strlen($item["PROPERTIES"]["MINUS"]["VALUE"]) > 0):?>
						<ul>
							<?if(strlen($item["PROPERTIES"]["PLUS"]["VALUE"]) > 0):?><li><span class="icon fa fa-plus"></span><?=$item["PROPERTIES"]["PLUS"]["VALUE"]?></li><?endif?>
							<?if(strlen($item["PROPERTIES"]["MINUS"]["VALUE"]) > 0):?><li><span class="icon icon-minus fa fa-minus"></span><?=$item["PROPERTIES"]["MINUS"]["VALUE"]?></li><?endif?>
						</ul>
					<?endif?>
					<?if(!empty($item["PROPERTIES"]["PHOTO"]["VALUE"])):?>
                        <div style="padding-top: 15px;">
                            <?$j = 1;?>
                            <?foreach ($item["PROPERTIES"]["PHOTO"]["VALUE"] as $img):?>
                                <?$file_real = CFile::GetPath($img);
                                $file_small = CFile::ResizeImageGet($img, Array('width' => 90, 'height' => 60), BX_RESIZE_IMAGE_EXACT, true);?>
                                <a data-fancybox="images-<?=$i?>" href="<?=$file_real?>"><img src="<?=$file_small["src"]?>" alt="<?=$arResult["ITEMS_NAME"][$item["PROPERTIES"]["ITEM"]["VALUE"]]?>-<?=$j?>" /></a>
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

