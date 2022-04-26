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
/*$this->addExternalJS("/local/templates/partners/js/core/libraries/jquery.fancybox.js");
$this->addExternalCss("/local/templates/partners/css/jquery.fancybox.css");*/
?>
<?if(!empty($arResult["ITEMS"])):?>
	<!-- Review All -->
	<div class="review-all">
		<h4 class="review-h">
			<?/*=GetMessage('ALL_REVIEWS2')*/?> <!--(--><?/*=count($arResult["ITEMS"])*/?><!--)-->
		</h4>
		<?foreach ($arResult["ITEMS"] as $key=>$item):?>
            <?$imgAvatar = NO_PHOTO_PEOPLE_PATH;?>
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
                                $imgAvatar = $file["src"];
                            }?>
                        <?endif?>
                        <img class="img-avatar" src="<?=$imgAvatar?>" alt="<?=$item["PROPERTIES"]["USER_NAME"]["VALUE"]?>">
					</ins>
					<span><?=$item["PROPERTIES"]["USER_NAME"]["VALUE"]?></span>
					<small><?if(strlen($item["PROPERTIES"]["COUNTRY"]["VALUE"]) > 0):?><?=$item["PROPERTIES"]["COUNTRY"]["VALUE"]?><?endif?> <?=$item["DISPLAY_DATE_CREATE"]?></small>
				</div>
				<div class="col-xs-9 review-text">
					<? if (!empty($item["PROPERTIES"]["ITEM" . POSTFIX_PROPERTY]["VALUE"])): ?>
						<? if (is_array($item["DISPLAY_PROPERTIES"]["ITEM" . POSTFIX_PROPERTY]["DISPLAY_VALUE"])): ?>
						<div class="policies-item">
						<? foreach($item["DISPLAY_PROPERTIES"]["ITEM" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] as $tmpitem):?>
							<p>
								<?=$tmpitem;?>
							</p>
						<? endforeach; ?>
						</div>
						<? else: ?>
						<div class="policies-item">
							<p>
							<?=$item["DISPLAY_PROPERTIES"]["ITEM" . POSTFIX_PROPERTY]["DISPLAY_VALUE"];?>
							</p>
						</div>
						<? endif; ?>
					<? elseif (!empty($item["PROPERTIES"]["ITEM"]["VALUE"])): ?>
						<? if (is_array($item["DISPLAY_PROPERTIES"]["ITEM"]["DISPLAY_VALUE"])): ?>
						<div class="policies-item">
						<? foreach($item["DISPLAY_PROPERTIES"]["ITEM"]["DISPLAY_VALUE"] as $tmpitem):?>
							<p>
								<?=$tmpitem;?>
							</p>
						<? endforeach; ?>
						</div>
						<? else: ?>
						<div class="policies-item">
							<p>
								<?=$item["DISPLAY_PROPERTIES"]["ITEM"]["DISPLAY_VALUE"];?>
							</p>
						</div>
						<? endif; ?>
					<?endif;?>
					
					<?if(!empty($item["PROPERTIES"]["PHOTO"]["VALUE"])):?>
                        <div style="padding-top: 15px;">
                            <?$j = 1;?>
                            <?foreach ($item["PROPERTIES"]["PHOTO"]["VALUE"] as $img):?>
                                <?$file_real = CFile::GetPath($img);
                                $file_small = CFile::ResizeImageGet($img, Array('width' => 90, 'height' => 60), BX_RESIZE_IMAGE_EXACT, true);?>
                                <a class="forfancy images-<?=$j?>" data-fancybox="gallery-<?=$item['ID']?>"  href="<?=$file_real?>"><img src="<?=$file_small["src"]?>" alt="<?=$arResult["ITEMS_NAME"][$item["PROPERTIES"]["ITEM"]["VALUE"]]?>-<?=$j?>" /></a>
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
								<a href="<?=$item["DETAIL_PAGE_URL"]?>"><?=GetMessage('READ_MORE')?></a>
							</div>
						</div>
					<?endif?>
				</div>
			</div>
			<!-- End Review Item -->
		<?endforeach;?>
        <div style="clear: both;"></div>
        <?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
            <br /><?=$arResult["NAV_STRING"]?>
        <?endif;?>
	</div>
	<!-- End Review All -->
<?else:?>
    <?if(isset($arParams["DISPLAY_RATING_BLOCK"]) && $arParams["DISPLAY_RATING_BLOCK"] != "Y"):?>
        <div class="alert alert-danger mt-20" role="alert"><?=GetMessage("EMPTY_REVIEWS");?></div>
    <?endif;?>
<?endif?>

<script>
	/*$('.rev-block').each(function () {
		var hgth = $(this).find(".reviews-content-text").height();
		var hgth_p = $(this).find(".reviews-content-text p").height();
		if (hgth > hgth_p)
			$(this).find(".reviews-content-button").css('display', 'none');
	});*/

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

	/*for(var i=1;i<='<?=count($arResult["ITEMS"])?>';i++){
        $('[data-fancybox="images-' + i + '"]').fancybox({
            image : {
                protect: true
            }
        });
    }*/
    $('.forfancy').fancybox({
        image : {
            protect: true
        } 
    });
</script>