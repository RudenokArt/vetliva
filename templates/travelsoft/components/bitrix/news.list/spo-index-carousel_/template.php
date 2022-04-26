<?
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

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

// ПОДКЛЮЧАЕМ КОМПОНЕНТ ДЛЯ РАСЧЁТА ЦЕН
$elementsId = null;
foreach ($arResult["ITEMS"] as $arItem) {
    $elementsId[] = $arItem["ID"];
} 

if ($elementsId) {
    Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");

    $arCalculations = $APPLICATION->IncludeComponent(
                "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
            "RETURN_RESULT" => "Y",
            "FILTER_BY_PRICES_FOR_CITIZEN" => "N",
            "TYPE" => $arParams["IS_EXCURSION"] == "Y" ? "excursions" : "placements",
            "MAKE_ORDER_PAGE" => "/booking/",
            "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
            "__BOOKING_REQUEST" => array("id" => $elementsId),
                    "MP" => "Y"
                )
        );
}

?>
<div id="popular-slide" class="owl-carousel">
<? foreach ($arResult["ITEMS"] as $arItem): ?>
    <?

    $detailLink = getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], array("id" => array($arItem["ID"])))
    ?>
<div class="item">
       
            <div class="destinations-item">
			<a class="destinations-item-a" href="<?= $detailLink ?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>">
                <figure class="destinations-img">
                    <div class="stickers">
                        <?if (is_array($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"])):?>
                        <?
                            // отображение по сортировке
                            asort($arItem["PROPERTIES"]["HIT"]["VALUE_SORT"]);
                            $sorts_key = array_keys($arItem["PROPERTIES"]["HIT"]["VALUE_SORT"]);
                            $sorts_key = array_slice($sorts_key,0,3);
                        ?>
							<?foreach($sorts_key as $key){
							    if (LANGUAGE_ID != "ru") $arItem["PROPERTIES"]["HIT"]["VALUE"][$key] = GetMessage(strtolower($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"][$key]));
                                ?>
								<div class="sticker_<?=strtolower($arItem["PROPERTIES"]["HIT"]["VALUE_XML_ID"][$key]);?>" title="<?=$arItem["PROPERTIES"]["HIT"]["VALUE"][$key]?>"></div>
							<?}?>
						<?endif;?>
					</div> 
                    <?
                    if (!empty($arItem["PREVIEW_PICTURE"])):
                        $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 426, 'height' => 279), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
                        $pre_photo = $an_file["src"];
                    elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
                        $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 426, 'height' => 279), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
                        $pre_photo = $an_file["src"];
                    else:
                        $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto.jpg";
                    endif;
                    ?>
                    <img src="<?= $pre_photo ?>" alt=""> 
                </figure>
				
				</a>
                <div class="destinations-text">
                    <div class="destinations-name">
						<a class="destinations-item-a" href="<?= $detailLink ?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>">
							<? echo LANGUAGE_ID == "ru" ? substr2($arItem["NAME"],70) : substr2($arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"],70) ?>
						</a>
					</div>
                    <div class="home-destinations-places"></div>
                    
                    <div class="price-box price-box--wrap">
                        <?
                        if ($arCalculations[$arItem["ID"]] > 0):
                            $price = \travelsoft\Currency::getInstance()->convertCurrency(
                                    $arCalculations[$arItem["ID"]]["PRICE"], $arCalculations[$arItem["ID"]]['CURRENCY_ID']
                            );
                            ?>
                            <div class="price-box " onclick="location.href='<?= $detailLink?>'" Style="cursor: pointer;">
                                <span class="price"><?= Loc::getMessage("NIGHT__", array("#PRICE#" => $price))?></span>
                            </div>
                         
                        <? endif ?>
							<div class="link-box">
								<a href="<?= $detailLink?>" class="add-to-cart awe-btn awe-btn-1 awe-btn-small2"><?=Loc::getMessage("RESERVATION")?></a>
								<a href="<?=Loc::getMessage($arItem['TYPE'])['link']?>" class="all-offers"><?=Loc::getMessage($arItem['TYPE'])['title']?></a>
							</div>
                        <!---->
                    </div>
                </div>
            </div>
        
</div>
<? endforeach; ?>
</div>

<script>
    $('#popular-slide').owlCarousel({
        items: 3,
        loop:true,
        margin: 20,
        nav:true,
        navText: ['<span class="prev-next-room prev-room"></span>','<span class="prev-next-room next-room"></span>'],
        dots: true,
		pagination : true,
		responsive : {
				
				0 : {
					items: 1,
					margin: 10,
					slideBy:1,
					stagePadding: 20,
				},
				
				480 : {
					items: 2,
					slideBy:1,
					margin: 10,
					stagePadding: 20,
					
				},
				
				768 : {
					items: 2,
				},
				991 : {
					items: 3,
				},
				
		}
    });
	
	
	$('.destinations-name').matchHeight();
	$('.price-box--wrap').matchHeight();
	
	
</script>