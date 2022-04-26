<!-- Проверка юзер агента для адаптации размера видоса во фреймах  -->
<?
function isMobile() { 
	return preg_match("/(android|avantgo|Mobile|Phone|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
?>
<? if(isMobile()): ?>
<script>
$(document).ready(function () {
	var heightV = document.body.clientWidth * 0.65;
	$(".vidFrame").attr("height", heightV);
	$("#subscr_form").attr("style", "width: 100%; border: 1px solid #264B87; height: 130px; padding-right: 6%;");
	$("#subscr_txt").attr("style", "color: #ffffff; font-size: 12pt; margin-top: 10px;");
});
</script>
<? endif; ?>
<?
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/slider-prop.css");

$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/jquery.sliderPro.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/slide.js");


if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
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
<?$p=$arResult["PROPERTIES"];?>


<section class="head-detail">
    <div class="head-dt-cn">
		<div class="row">
            <div class="col-sm-9">
                <h1>
                    <? echo LANGUAGE_ID != "en" ? $arResult["NAME"] : $arResult["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>  
                </h1>

            </div>
            <div class="col-sm-3 text-right"><?= $GLOBALS["favorites_html"]?></div>
		</div>
    </div>
</section>
<?if (!empty($arResult["DETAIL_PICTURE"])): ?><div class="ticket_with_image"><?endif;?>

	<?if ($arParams["HIDE_DETAIL_PICTURE"] !== "Y"): ?>
		<? $waterMark = ''; ?>
		
		<?
		if (!empty($arResult["DETAIL_PICTURE"])):
			$an_file = CFile::ResizeImageGet($arResult["DETAIL_PICTURE"], array('width' => 300, 'height' => 436), BX_RESIZE_IMAGE_EXACT, true, $waterMark);
			$pre_photo_915 = $an_file["src"];
			?>
			<img src="<?= $pre_photo_915 ?>" alt="<?echo LANGUAGE_ID == "ru" ? $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"]["0"] : $arResult["PROPERTIES"]["IMG_DESCRIPTION".POSTFIX_PROPERTY]["VALUE"]["0"]?>" class="img-responsive">
		<? endif; ?>
	<? endif; ?>
	<div class="ticket_address">
		<?if ($arResult["PROPERTIES"]["place"]["VALUE"]):?>
        <?if (LANGUAGE_ID=='en') {
            if ($arResult["PROPERTIES"]["city_EN"]["VALUE"]!='') $arResult["PROPERTIES"]["city"]["VALUE"] = $arResult["PROPERTIES"]["city_EN"]["VALUE"];
            if ($arResult["PROPERTIES"]["place_EN"]["VALUE"]!='') $arResult["PROPERTIES"]["place"]["VALUE"] = $arResult["PROPERTIES"]["place_EN"]["VALUE"];
        }?>
		 <div class="info-star-address">
			<address class="info-address">
				<i class="fa fa-map-marker"></i>
				<span itemprop="location" itemscope="" itemtype="http://schema.org/Place">
					<span itemprop="name"><?=$arResult["PROPERTIES"]["city"]["VALUE"]?>, <?=$arResult["PROPERTIES"]["place"]["VALUE"]?></span>
					<span itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
					</span>
				 </span>
			</address>
		 </div>
		<?endif?>
		<? if (!empty($arResult["PROPERTIES"]["TIMESTART"]["VALUE"])): ?>
			<div>
				<span class="label"><a data-content="<?= GetMessage('DEPARTURE_TIME') ?>" class="border_icon"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon/clock.png"></a></span>  <?= GetMessage('DEPARTURE_TIME') ?>: <? echo date('H:i', strtotime($arResult["DISPLAY_PROPERTIES"]["TIMESTART"]['VALUE'])); ?> <br>
			</div>
			<div>
				<span class="label"><a data-content="<?= GetMessage('TOURS_DATE') ?>" class="border_icon"><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon/calendar.png"></a></span>  
				<? echo date('d.m.Y', strtotime($arResult["DISPLAY_PROPERTIES"]["TIMESTART"]['VALUE'])); ?><br>
			</div>
		<? endif; ?>

     </div>



<?if (!empty($arResult["DETAIL_PICTURE"])): ?></div><?endif;?>
<?if (LANGUAGE_ID=='en' && !empty($arResult["PROPERTIES"]['widget_EN']["VALUE"])) $arResult["PROPERTIES"]['widget']["VALUE"] = $arResult["PROPERTIES"]['widget_EN']["VALUE"];
if (!empty($arResult["PROPERTIES"]['widget']["VALUE"])): ?>
    <div class="policies-item">
        <iframe width="100%" height="800" src="<?=$arResult["PROPERTIES"]['widget']["VALUE"]?>" frameborder="0" allowfullscreen></iframe>
    </div>
<? endif;?>
<div style="clear: both;"></div>
<?
if (LANGUAGE_ID=='en') $APPLICATION->AddChainItem($arResult["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"], "");
?>