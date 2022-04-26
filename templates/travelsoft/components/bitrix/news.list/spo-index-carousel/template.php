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
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    
    $detailLink = getCalculateDetailLink($arItem["DETAIL_PAGE_URL"], array("id" => array($arItem["ID"])))
    ?>
<div class="item">
        <a class="destinations-item-a" href="<?= $detailLink ?>" title="<? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>">
            <div class="destinations-item">
                <figure class="destinations-img"> 
                    <?
                    if (!empty($arItem["PREVIEW_PICTURE"])):
                        $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 344, 'height' => 210), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
                        $pre_photo = $an_file["src"];
                    elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
                        $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 344, 'height' => 210), BX_RESIZE_IMAGE_EXACT, true, array(), false, 70);
                        $pre_photo = $an_file["src"];
                    else:
                        $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto.jpg";
                    endif;
                    ?>
                    <img src="<?= $pre_photo ?>" alt=""> 
                </figure>
                <div class="destinations-text">
                    <div class="destinations-name">
    <? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>
                    </div>
                    <div class="home-destinations-places">
                    </div>
                    <div class="price-box">
                        <?
                        if ($arCalculations[$arItem["ID"]] > 0):
                            $price = \travelsoft\Currency::getInstance()->convertCurrency(
                                    $arCalculations[$arItem["ID"]]["PRICE"], $arCalculations[$arItem["ID"]]['CURRENCY_ID']
                            );
                            ?>
                            <div class="price-box" onclick="location.href='<?= $detailLink?>'" Style="cursor: pointer;">
                                <span class="price"><?= Loc::getMessage("NIGHT__", array("#PRICE#" => $price))?></span>
                            </div>
                            <?else:?>
                                <div class="price-box">
                                   <a class="destinations-item-a" href="<?echo $detailLink?>" title=""><span class="detail"><?=Loc::getMessage("MORE")?></span></a>
                                </div>
                            <? endif ?>
							<a href="<?= $detailLink?>" class="add-to-cart awe-btn awe-btn-1 awe-btn-small2"><?=Loc::getMessage("RESERVATION")?></a>
                        <!---->
                    </div>
                </div>
            </div>
        </a>
</div>
<? endforeach; ?>
</div>

<script>
    $('#popular-slide').owlCarousel({
        items: 4,
        loop:true,
        margin:10,
        navigation:true,
        navigationText: ['<span class="prev-next-room prev-room"></span>','<span class="prev-next-room next-room"></span>'],
        dots: false
    })
</script>