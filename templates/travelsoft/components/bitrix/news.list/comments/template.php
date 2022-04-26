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
?>
<?if(!empty($arResult["ITEMS"])):?>

	<!-- Review All -->
	<div class="review-all">
		<h4 class="review-h">
			<?=GetMessage('REVIEWS_CLIENTS')?>
		</h4>
		<?$imgAvatar = NO_PHOTO_PEOPLE_PATH;?>
		<?foreach ($arResult["ITEMS"] as $key=>$item):?>

		<!-- Review Item -->
			<div class="row review-item">
				<div class="col-xs-2 review-number">
					<span><?=$item["PROPERTIES"]["USER_NAME"]["VALUE"]?></span>
					<small><?=$item["DISPLAY_DATE_CREATE"]?></small>
				</div>
				<div class="col-xs-10 review-text">
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
</script>

