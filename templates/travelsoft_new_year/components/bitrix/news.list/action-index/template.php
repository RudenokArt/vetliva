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

\Bitrix\Main\Loader::includeModule("travelsoft.currency");
?>
<div class="sales-cn sales-top-actions">
    <div class="row">
        <? $i = 0; ?>
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <? $i++; ?>
            <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
            ?>

            <div class="col-xs-6 <? if (($i != 3) & ($i != 4)): ?>col-md-3<? endif; ?>">
                <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="sales-item-a">
                    <div class="sales-item">
                        <figure class="home-sales-img">
                            <? if (($i != 3) & ($i != 4)): ?>
                                <?
                                if (!empty($arItem["PREVIEW_PICTURE"])):
                                    $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 292, 'height' => 180), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
                                    $pre_photo = $an_file["src"];
                                elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
                                    $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 292, 'height' => 180), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
                                    $pre_photo = $an_file["src"];
                                else:
                                    $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto-240x150.jpg";
                                endif;
                                ?>
                            <? else: ?>
                                <?
                                if (!empty($arItem["PREVIEW_PICTURE"])):
                                    $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 584, 'height' => 292), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
                                    $pre_photo = $an_file["src"];
                                elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
                                    $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 584, 'height' => 292), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
                                    $pre_photo = $an_file["src"];
                                else:
                                    $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto-240x150.jpg";
                                endif;
                                ?>
                                    <? endif; ?>
                            <img src="<?= $pre_photo ?>" alt="">
                        </figure>
                        <div class="home-sales-text">
                            <div class="home-sales-name-places">
                                <div class="home-sales-name">
                                    <? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>
                                </div>
                                <div class="home-sales-places">
                                    <?
$accomodation = $sanatorium = $attraction =$region = $town = "";
                                    if (!empty($arItem["PROPERTIES"]["REGIONS"]["VALUE"])) {
                                        $region = strip_tags($arItem["DISPLAY_PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"]);
                                        if (LANGUAGE_ID != "ru") {
                                            $prop = getIBElementProperties($arItem["PROPERTIES"]["REGIONS"]["VALUE"]);
                                            $region = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                        }
                                    }
                                    if (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])) {
                                        $town = strip_tags($arItem["DISPLAY_PROPERTIES"]["TOWN"]["DISPLAY_VALUE"]);
                                        if (LANGUAGE_ID != "ru") {
                                            $prop = getIBElementProperties($arItem["PROPERTIES"]["TOWN"]["VALUE"]);
                                            $town = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                        }
                                    }
                                    if (!empty($arItem["PROPERTIES"]["ACCOMODATION"]["VALUE"])) {
                                        $accomodation = strip_tags($arItem["DISPLAY_PROPERTIES"]["ACCOMODATION"]["DISPLAY_VALUE"]);
                                        if (LANGUAGE_ID != "ru") {
                                            $prop = getIBElementProperties($arItem["PROPERTIES"]["ACCOMODATION"]["VALUE"]);
                                            $accomodation = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                        }
                                    }
                                    if (!empty($arItem["PROPERTIES"]["SANATORIUM"]["VALUE"])) {
                                        $sanatorium = strip_tags($arItem["DISPLAY_PROPERTIES"]["SANATORIUM"]["DISPLAY_VALUE"]);
                                        if (LANGUAGE_ID != "ru") {
                                            $prop = getIBElementProperties($arItem["PROPERTIES"]["SANATORIUM"]["VALUE"]);
                                            $sanatorium = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                        }
                                    }
                                    if (!empty($arItem["PROPERTIES"]["ATTRACTION"]["VALUE"])) {
                                        $attraction = strip_tags($arItem["DISPLAY_PROPERTIES"]["ATTRACTION"]["DISPLAY_VALUE"]);
                                        if (LANGUAGE_ID != "ru") {
                                            $prop = getIBElementProperties($arItem["PROPERTIES"]["ATTRACTION"]["VALUE"]);
                                            $attraction = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                        }
                                    }
                                    ?>
    <? if ($accomodation): ?><?= $accomodation ?> <? endif; ?>
    <? if ($sanatorium): ?><?= $sanatorium ?> <? endif; ?>
    <? if ($attraction): ?><?= $attraction ?> <? endif; ?>
                                </div>
                            </div>
                            <?if ($arItem["PROPERTIES"]["PRICE"]["VALUE"] && $arItem["PROPERTIES"]["CURRENCY"]["VALUE"]):?>
                            <div class="price-box">
<?if ($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"] && $arItem["PROPERTIES"]["CURRENCY"]["VALUE"]):?>
								<div style="color: #264B87;text-align: right; text-decoration: line-through"><?=  \travelsoft\Currency::getInstance()->convertCurrency($arItem["PROPERTIES"]["OLD_PRICE"]["VALUE"], $arItem["PROPERTIES"]["CURRENCY"]["VALUE"])?></div>
<?endif?>
                                <span class="price old-price"><?= GetMessage("FROM") ?> </span> <span class="price special-price"> <?=  \travelsoft\Currency::getInstance()->convertCurrency($arItem["PROPERTIES"]["PRICE"]["VALUE"], $arItem["PROPERTIES"]["CURRENCY"]["VALUE"])?></span>
							</div>
                            <?endif?>
                        </div>
                    </div>
                </a>
            </div>
<? endforeach; ?>
    </div>
</div>
