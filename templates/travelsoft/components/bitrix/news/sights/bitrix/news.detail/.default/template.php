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

use \Bitrix\Main\Localization\Loc;
?>
<?$p=$arResult["PROPERTIES"];?>
<? /* h1><?echo LANGUAGE_ID == "ru" ? $arResult["NAME"] : $arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?> </h1 */ ?>
<? //$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/slide.js"); ?>
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

$scroll = [];
?>

<? $this->SetViewTarget("head-detail-tours"); ?>
<section class="head-detail">
    <div class="head-dt-cn">
        <div class="row">
            <div class="col-sm-10">
                <h1><? echo LANGUAGE_ID == "ru" ? $arResult["NAME"] : $arResult["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?> </h1>
                <div class="start-address">
                    <span class="star">
                        <?= $arResult["DISPLAY_PROPERTIES"]["CAT_ID"]["DISPLAY_VALUE"]; ?>
                    </span>
                </div>
            </div>
            <div class="col-sm-2 text-right"><?= $GLOBALS["favorites_html"]?></div>
        </div>
    </div>
</section>

<?if ($arParams["HIDE_DETAIL_PICTURE"] !== "Y"): ?>
    <? $waterMark = ''; ?>
    <? if ($arParams["NO_SHOW_WATERMARK"] !== "Y"): ?>
        <? $waterMark = $arWaterMark ?>
    <? endif ?>
    <?
    if (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) == 1):
        $an_file = CFile::ResizeImageGet($arResult["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 1170, 'height' => 640), BX_RESIZE_IMAGE_EXACT, true, $waterMark);
        $pre_photo_915 = $an_file["src"];
        ?>
        <img loading="lazy" src="<?= $pre_photo_915 ?>" alt="<?echo LANGUAGE_ID == "ru" ? $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"]["0"] : $arResult["PROPERTIES"]["IMG_DESCRIPTION".POSTFIX_PROPERTY]["VALUE"]["0"]?>" class="img-responsive">
    <? elseif (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"]) > 1): ?>
        <div style="margin-top: 20px;">
            <div class="slider-container">
                <div id="search-preloader-slider" class="reloader-postion">

                    <div id="search-page-loading">
                        <div></div>
                    </div>
                </div>
                <div id="slider-room" class="slider-pro">
                    <div class="sp-slides">
                        <?
                        $i = 0;
                        foreach ($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $item):
                            $file_big = CFile::ResizeImageGet($item, Array('width' => 1170, 'height' => 640), BX_RESIZE_IMAGE_EXACT, true, $arWaterMark);
                            $img_count++;
                            ?>
                            <div class="sp-slide">
                                <img loading="lazy" src="<?= $file_big["src"]; ?>"  alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i] ?>">
                            </div>
                            <? $i++;
                        endforeach;
                        ?>
                    </div>
                    <div class="sp-thumbnails">
                        <?
                        $i = 0;
                        foreach ($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $item):
                            $file_small = CFile::ResizeImageGet($item, Array('width' => 220, 'height' => 100), BX_RESIZE_IMAGE_EXACT, true);
                            $img_count++;
                            ?>
                            <div class="sp-thumbnail">

                                <img loading="lazy" src="<?= $file_small["src"]; ?>"  alt="<?= $arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i] ?>">
                            </div>
                            <? $i++;
                        endforeach;
                        ?>
                    </div>
                </div>

            </div>
        </div>

    <? endif; ?>
<? endif; ?>
<? $this->EndViewTarget(); ?>

<p>
    <? if ($arParams["DISPLAY_DATE"] != "N"): ?>
        <?
        $date_need = "";
        $date_need_L = "";
        if (!empty($arResult["PROPERTIES"]["DATE_NEED"]["VALUE"])) {
            $date_need =  FormatDateFromDB($arResult["PROPERTIES"]["DATE_NEED"]["VALUE"], 'SHORT');
            $date_need_L = str_replace(" ", "T", FormatDateFromDB($arResult["PROPERTIES"]["DATE_NEED"]["VALUE"], 'YYYY-MM-DD HH:MI:SS'));
        }
        else {
            $date_need =  FormatDateFromDB($arResult["DATE_CREATE"], 'SHORT');
            $date_need_L = str_replace(" ", "T", FormatDateFromDB($arResult["DATE_CREATE"], 'YYYY-MM-DD HH:MI:SS'));
        }
        ?>
    <? endif; ?>

    <? if($arResult["IBLOCK_ID"] == 58 && $arResult["PROPERTIES"]["SCH_SHOW"]["VALUE"]): ?>
        <!-- Шаблон для обёртки статей в schema -->
        <div itemscope itemtype="https://schema.org/BlogPosting" style="display:none;">
            <? $site_suff = (LANGUAGE_ID != "en")?LANGUAGE_ID:"com"; ?>
            <meta content="<?="https://vetliva.".$site_suff."/blog/".$arResult["CODE"]."/";?>" itemprop="mainEntityOfPage">
            <h2 class="post-title" itemprop="headline"><?=$arResult["NAME"];?></h2>
            <span class="entry-date">

<time class="published" datetime="<?=$date_need_L."+00:00"?>" itemprop="datePublished"></time>
<time class="updated" datetime="<?=$date_need_L."+00:00"?>" itemprop="dateModified"><?=$date_need?></time>
</span>
            <span class="byline" itemprop="author" itemscope itemtype="https://schema.org/Person">
<span itemprop="name">VETLIVA</span>
<?=$arResult["PROPERTIES"]["SCH_AUTHOR"]["VALUE"];?>
</span>
            <link href="https://vetliva.ru/" itemprop="url">
            <div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
                <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject" style="display:none;">
                    <img loading="lazy" alt="VETLIVA" height="122" itemprop="url image" src="https://vetliva.ru/local/templates/travelsoft/images/logo-header-140.png" width="198">
                    <meta content="198" itemprop="width">
                    <meta content="122" itemprop="height">
                </div>
                <script>
                  $(document).ready(function(){
                    $('meta[itemprop=name]').attr("content", document.title);
                  });
                </script>
                <meta content="" itemprop="name">
                <meta content="+375172154808" itemprop="telephone">
                <meta content="г. Минск, ул. Мясникова, 39, 2 этаж" itemprop="address">
            </div>
            <span itemprop="articleSection">
<a href="<?="https://vetliva.".$site_suff."/blog/".$arResult["CODE"]."/";?>" rel="category tag"><?=$arResult["NAME"];?></a>
</span>
            <span itemprop="keywords">
<a href="<?="https://vetliva.".$site_suff."/blog/".$arResult["CODE"]."/";?>" rel="tag"><?=$arResult["PROPERTIES"]["SCH_KEYWORDS"]["VALUE"];?></a>
</span>
            <span itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
<img loading="lazy" alt="<?=$arResult["PROPERTIES"]["SCH_ALT"]["VALUE"];?>" height="" itemprop="url contentUrl" src="<?=$arResult["PROPERTIES"]["SCH_IMG_PATH"]["VALUE"];?>" width="">
</span>
            <div class="entry-description" itemprop="description">
                <p><?=$arResult["DISPLAY_PROPERTIES"]["SCH_SHORT_DSCR"]["DISPLAY_VALUE"];?></p>
            </div>
        </div>
    <? endif; ?>

    <? if ($arParams["DISPLAY_DATE"] != "N"): ?>
        <i class="fa fa-calendar"></i>
        <?=$date_need;?>
    <? endif; ?>
    <?
    if (!empty($arResult["PROPERTIES"]["REGIONS"]["VALUE"])) {
        $region = strip_tags($arResult["DISPLAY_PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"]);
        if (LANGUAGE_ID != "ru") {
            $prop = getIBElementProperties($arResult["PROPERTIES"]["REGIONS"]["VALUE"]);
            $region = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
        }
    }
    if (!empty($arItem["PROPERTIES"]["EXCURTION"]["VALUE"])) {
        $excurtion = strip_tags($arItem["DISPLAY_PROPERTIES"]["EXCURTION"]["DISPLAY_VALUE"]);
        if (LANGUAGE_ID != "ru") {
            $prop = getIBElementProperties($arItem["PROPERTIES"]["EXCURTION"]["VALUE"]);
            $excurtion = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
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
    <? if (!empty($town) || !empty($region)): ?><i class="fa fa-map-marker"></i><? endif; ?>
    <? /* if ($region): ?><?= $region ?><? endif; */ ?>
    <? /* if ($town): ?> <?= $town ?><? endif; */ ?>
    <?= implode2(array($region, $town, $arResult["DISPLAY_PROPERTIES"]["ADDRESS".POSTFIX_PROPERTY]["DISPLAY_VALUE"])); ?>
    <? if ($accomodation): ?> <?= $accomodation ?><? endif; ?>
    <? if ($sanatorium): ?> <?= $sanatorium ?><? endif; ?>
    <? if ($attraction): ?> <?= $attraction ?><? endif; ?>
    <? if ($excurtion): ?> <?= $excurtion ?><? endif; ?>
</p>
<?
$kitchentype = null;
if ($p["KITCHEN2"]["VALUE"]) {
    $p["KITCHEN2"]["VALUE"] = (array) $p["KITCHEN2"]["VALUE"];
    $db_res_kitchentype = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["KITCHEN2"]["LINK_IBLOCK_ID"], "ID" => $p["KITCHEN2"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
    $kitchentype = null;
    while ($res = $db_res_kitchentype->Fetch()) {
        $kitchentype[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
    }
}
if ($kitchentype):
    ?>
    <p>
        <i class="fa fa-info-circle blue"></i> <?= GetMessage('KITCHEN_TYPE')?>:	<?= implode(", ", $kitchentype) ?>
    </p>
<? endif; ?>
<?
$type = null;
if ($p["TYPE"]["VALUE"]) {
    $p["TYPE"]["VALUE"] = (array) $p["TYPE"]["VALUE"];
    $db_res_type = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $p["TYPE"]["LINK_IBLOCK_ID"], "ID" => $p["TYPE"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
    $type = null;
    while ($res = $db_res_type->Fetch()) {
        $type[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
    }
}
if ($type):
    ?>
    <p>
        <i class="fa fa-info-circle blue"></i> <?= GetMessage('TYPE')?>	<?= implode(", ", $type) ?>
    </p>
<? endif; ?>

<?
if (
        !empty($arResult["PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["VALUE"]) ||
        !empty($arResult["PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["VALUE"])
): ?>
    <?$scroll[] = ['iblock_detail', Loc::getMessage('T_SIGHTS_DETAIL_DESCRIPTION')]?>
    <section class="detail-cn" id="iblock_detail">
        <? if (!empty($arResult["PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["VALUE"])): ?>
            <div class="policies-item">
                <p<?if($arResult["IBLOCK_ID"] == NEWS_IBLOCK_ID):?> itemprop="description"<?endif?>>
                    <?= $arResult["DISPLAY_PROPERTIES"]["HD_DESC" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
                </p>
            </div>
        <? endif ?>
        <? if (!empty($arResult["PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["VALUE"])): ?>
            <div class="policies-item">
                <p<?if($arResult["IBLOCK_ID"] == NEWS_IBLOCK_ID):?> itemprop="description"<?endif?>>
                    <?= $arResult["DISPLAY_PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["DISPLAY_VALUE"] ?>
                </p>
            </div>
        <? endif ?>
    </section>
<?endif?>

<? if(!empty($arResult["PROPERTIES"]["WORKING_HOURS".POSTFIX_PROPERTY]["VALUE"])):?>
    <?$scroll[] = ['iblock_hours', GetMessage('HOURS')]?>
    <section class="detail-cn" id="iblock_hours">
        <p>
            <i class='fa fa-info-circle blue'></i>
            <?= GetMessage('WORKING_HOURS') . ': ' . $arResult["DISPLAY_PROPERTIES"]["WORKING_HOURS".POSTFIX_PROPERTY]["DISPLAY_VALUE"]?>
        </p>
    </section>
<? endif;?>

<? if (
        !empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"]) ||
        !empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])
): ?>
    <?$scroll[] = ['iblock_video', Loc::getMessage('T_SIGHTS_DETAIL_VIDEO')]?>
    <section class="detail-cn" id="iblock_video">
        <? if (!empty($arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"])): ?>
            <div class="policies-item">
                <h4><?= GetMessage("VIDEO") ?></h4>
                <link rel="preload" href="https://www.youtube.com/embed/<?= $arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"] ?>" as="document">
                <iframe width="100%" height="460" src="https://www.youtube.com/embed/<?= $arResult["PROPERTIES"]["YOUTUBE" . POSTFIX_PROPERTY]["VALUE"] ?>" frameborder="0" allowfullscreen></iframe>
            </div>
        <? endif;?>
        <? if (!empty($arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"])): ?>
            <div class="policies-item">
                <h4><?= GetMessage("VIDEO") ?></h4>
                <link rel="preload" href="https://player.vimeo.com/video/<?= $arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"] ?>" as="document">
                <iframe width="100%" height="460" src="https://player.vimeo.com/video/<?= $arResult["PROPERTIES"]["VIMEO" . POSTFIX_PROPERTY]["VALUE"] ?>" frameborder="0" allowfullscreen></iframe>
            </div>
        <? endif;?>
    </section>
<?endif?>

<? if (!empty($arResult["PROPERTIES"]["MAP"]["VALUE"])): ?>
    <?$scroll[] = ['iblock_map', GetMessage('MAP')]?>
    <section class="detail-cn" id="iblock_map">
        <div class="hotel-detail-map">
            <h4><?= GetMessage("MAP") ?></h4>
            <div style="width: 100%; height: 400px" id="placement_location_map"></div>
            <?
            $arLatLon = explode(",", $arResult["PROPERTIES"]["MAP"]["VALUE"]);
            $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/MapAdapter/MapAdapter.js");
            ?>
            <script>
              $(window).load(function () {
                var mapAdapter = new MapAdapter({
                  map_id: "placement_location_map",
                  center: {
                    lat: 53.53,
                    lng: 27.34
                  },
                  object: "ymaps",
                  zoom: 14
                });
                mapAdapter.addMarker({
                  lat: <?= $arLatLon[0]?>,
                  lng: <?= $arLatLon[1]?>,
                  icon: "<?//= MAP_MARKER_PATH?>",
                  //title: "<?= $arResult['NAME']?>",
                  content: "<span style='color: #264B87'><?= $arResult['NAME']?></span>"
                });
              });

            </script>
        </div>
    </section>
<? endif; ?>


<script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
<script src="//yastatic.net/share2/share.js"></script>
<div style="width:210px;float:left;" class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,gplus,twitter" data-counter=""
     data-lang="<?if (LANGUAGE_ID=="by"){ echo "be";} else {echo LANGUAGE_ID; }?>"
    <?if (!empty($pre_photo_915)):?>
        data-image="https://vetliva.<?=LANGUAGE_ID?><?=$pre_photo_915?>"
    <?elseif(!empty($file_big["src"])):?>
        data-image="https://vetliva.<?=LANGUAGE_ID?><?=$file_big["src"]?>"
    <?endif;?>
>
</div>

<? $this->SetViewTarget("menu-item-detail-tours"); ?>
<? if (!empty($scroll)): ?>

    <? foreach ($scroll as $s): ?>
        <? if (!empty($s)): ?>
            <li><a href="#<?= $s[0] ?>" class="anchor" title=""><?= $s[1] ?></a></li>
        <? endif ?>
    <? endforeach ?>

<? endif ?>
<? $this->EndViewTarget(); ?>