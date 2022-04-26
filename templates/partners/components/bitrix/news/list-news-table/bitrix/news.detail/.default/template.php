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
?>

<? /* h1><?echo LANGUAGE_ID == "ru" ? $arResult["NAME"] : $arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?> </h1 */ ?>
<? $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/slide.js");
$this->addExternalJS("/local/templates/partners/js/core/libraries/jquery.fancybox.js");
$this->addExternalCss("/local/templates/partners/css/jquery.fancybox.css");?>
<?
$arWaterMark = Array(
    array(
        "name" => "watermark",
        "position" => "topright", // Положение
        "type" => "image",
        "size" => "real",
        "file" => NO_PHOTO_PATH_WATERMARK, // Путь к картинке
        "fill" => "exact",
    )
);
?>

<p>
    <? if (!empty($arResult["PROPERTIES"]["DATE_FROM"]["VALUE"][0])): ?>
        <i class="fa fa-calendar"></i> <?= substr($arResult["PROPERTIES"]["DATE_FROM"]["VALUE"][0], 0, 10) ?>
        <? if (!empty($arResult["PROPERTIES"]["DATE_FROM"]["VALUE"][1])): ?>
            - <?= substr($arResult["PROPERTIES"]["DATE_FROM"]["VALUE"][1], 0, 10) ?>
        <? endif; ?>
    <? endif; ?>
    <? if ($arParams["DISPLAY_DATE"] != "N"): ?><i class="fa fa-calendar"></i> <? echo FormatDateFromDB($arResult["DATE_CREATE"], 'SHORT'); ?> <? endif; ?>
    <?
    if (!empty($arResult["PROPERTIES"]["REGIONS"]["VALUE"])) {
        $region = strip_tags($arResult["DISPLAY_PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"]);
        if (LANGUAGE_ID != "ru") {
            $prop = getIBElementProperties($arResult["PROPERTIES"]["REGIONS"]["VALUE"]);
            $region = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
        }
    }
    if (!empty($arResult["PROPERTIES"]["TOWN"]["VALUE"])) {
        $town = strip_tags($arResult["DISPLAY_PROPERTIES"]["TOWN"]["DISPLAY_VALUE"]);
        if (LANGUAGE_ID != "ru") {
            $prop = getIBElementProperties($arResult["PROPERTIES"]["TOWN"]["VALUE"]);
            $town = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
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
    }
    ?>
    <? if (!empty($town) || !empty($region)): ?><i class="fa fa-map-marker ml-10"></i><? endif; ?>
    <? if ($region): ?><?= $region ?><? endif; ?>
    <? if ($town): ?> <?= $town ?><? endif; ?>
    <? if ($accomodation): ?> <?= $accomodation ?><? endif; ?>
    <? if ($sanatorium): ?> <?= $sanatorium ?><? endif; ?>
    <? if ($attraction): ?> <?= $attraction ?><? endif; ?></p>
<? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <div class="policies-item">
        <h4><?= GetMessage("VIDEO") ?></h4>
        <iframe width="100%" height="600" src="https://www.youtube.com/embed/<?= $arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"] ?>" frameborder="0" allowfullscreen></iframe>
    </div>
<? elseif ($arParams["HIDE_DETAIL_PICTURE"] !== "Y"): ?>
    <? $waterMark = ''; ?>
    <? if ($arParams["NO_SHOW_WATERMARK"] !== "Y"): ?>
        <? $waterMark = $arWaterMark ?>
    <? endif ?>
    <?
    if (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) == 1):
        $an_file = CFile::ResizeImageGet($arResult["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 823, 'height' => 428), BX_RESIZE_IMAGE_EXACT, true, $waterMark);
        $pre_photo_915 = $an_file["src"];
        ?>
        <img src="<?= $pre_photo_915 ?>" alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][0] ?>" class="img-responsive">
    <? elseif (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) > 1): ?>
        <section class="detail-slider">
            <!-- Lager Image -->
            <!--<div class="slide-room-lg">
                <div id="slide-room-lg">
                    <?/*
                    foreach ($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $item):
                        $file_big = CFile::ResizeImageGet($item, Array('width' => 1170, 'height' => 640), BX_RESIZE_IMAGE_EXACT, true, $waterMark);
                        $img_count++;
                        */?>
                        <img src="<?/*= $file_big["src"]; */?>"  alt="<?/*= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i] */?>">
                        <?/* $i++;
                    endforeach;
                    */?>
                </div>
            </div>-->
            <!-- End Lager Image -->
            <!-- Thumnail Image -->
            <div class="slide-room-sm">
                <div class="row">
                    <div class="col-md-12">
                        <div id="slide-room-sm">
                            <?$j=1;
                            foreach ($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $item):
                                $file_real = CFile::GetPath($item);
                                $file_small = CFile::ResizeImageGet($item, Array('width' => 180, 'height' => 120), BX_RESIZE_IMAGE_EXACT, true);
                                ?>
                                <a data-fancybox="images" href="<?=$file_real?>"><img src="<?=$file_small["src"]?>" alt="<?=$arResult["NAME"]?>-<?=$j?>" /></a>
                                <?$j++?>
                                <?
                            endforeach;
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <!-- End Thumnail Image -->
        </section>
    <? endif; ?>
<? endif; ?>
        <? if (!empty($arResult["PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <div class="policies-item">
        <p>
    <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
        </p>
    </div>
<? endif ?>
        <? if (!empty($arResult["PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["VALUE"])): ?>
    <div class="policies-item">
        <p>
    <?= $arResult["DISPLAY_PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
        </p>
    </div>
<? endif ?>
<? if (!empty($arResult["PROPERTIES"]["DOC"]["VALUE"])): ?>
	<? if (isset($arResult["DISPLAY_PROPERTIES"]["DOC"]["FILE_VALUE"][1])): ?>
    <div class="policies-item">
        <p>
    Файлы для скачивания: <?= implode(", ", array_map(function ($arItem) {return '<a href="'.$arItem["SRC"].'">'.$arItem["ORIGINAL_NAME"].'</a>';}, $arResult["DISPLAY_PROPERTIES"]["DOC"]["FILE_VALUE"])) ?>
        </p>
    </div>
<? else: ?>
	<div class="policies-item">
        <p>
			Файлы для скачивания: <a href="<?= $arResult["DISPLAY_PROPERTIES"]["DOC"]["FILE_VALUE"]["SRC"]?>"><?= $arResult["DISPLAY_PROPERTIES"]["DOC"]["FILE_VALUE"]["ORIGINAL_NAME"]?></a>
        </p>
    </div>
<? endif ?>
<? endif ?>

    <? if (!empty($arResult["PROPERTIES"]["MAP"]["VALUE"])): ?>
    <div class="hotel-detail-map">
        <h4><?= GetMessage("MAP") ?></h4>
        <?
        $arLatLon = explode(",", $arResult["PROPERTIES"]["MAP"]["VALUE"]);
        $zoom = $arResult["PROPERTIES"]["MAP_SCALE"]["VALUE"];
        $arMapSettings = Array
            (
            "google_lat" => $arLatLon[0], //55.738299999994
            "google_lon" => $arLatLon[1], //37.5946
            "google_scale" => $zoom,
            "PLACEMARKS" => Array(
                Array
                    (
                    "LON" => $arLatLon[1],
                    "LAT" => $arLatLon[0],
                    "TEXT" => LANGUAGE_ID == "ru" ? $arResult['NAME' . POSTFIX_PROPERTY] : $arResult['NAME'],
                    "ICON" => MAP_MARKER_PATH
                )
            )
        );
        ?>
        <?
        $APPLICATION->IncludeComponent("bitrix:map.google.view", "on.detail.page", array(
            "INIT_MAP_TYPE" => "MAP",
            "MAP_WIDTH" => "100%",
            "MAP_HEIGHT" => "500",
            "MAP_DATA" => serialize($arMapSettings),
            "CONTROLS" => array(
                "SMALL_ZOOM_CONTROL",
                "TYPECONTROL",
                "SCALELINE"
            ),
            "OPTIONS" => array(
                "ENABLE_SCROLL_ZOOM",
                "ENABLE_DBLCLICK_ZOOM",
                "ENABLE_DRAGGING",
                "ENABLE_KEYBOARD"
            ),
            "API_KEY" => GOOGLE_API_KEY
                )
        );
        ?>
    </div>
<? endif ?>
<div style="clear: both;"></div>
<script>
    $(function () {
        $('[data-fancybox="images"]').fancybox({
            image : {
                protect: true
            }
        });
    });
</script>
