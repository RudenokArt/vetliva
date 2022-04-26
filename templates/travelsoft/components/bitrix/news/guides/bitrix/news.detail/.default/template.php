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
<div class="col-md-12 desc-block">
    <?= $arResult["PROPERTIES"]["DETAIL_TEXT" . POSTFIX_PROPERTY]["~VALUE"]["TEXT"] ?>
</div>

<div class="clear"></div>

<?
if (!empty($arResult["ATTRACTIONS"])) {
    __renderBlockItem(array(
        "title" => GetMessage("ATTRACTIONS_TITLE"),
        "link_title" => GetMessage("ATTRACTIONS_LINK_TITLE"),
        "link" => "/tourism/what-to-see/?set_filter=y&arrFilter_36=" . $arResult["CRC32_ID"],
        "elements" => $arResult["ATTRACTIONS"]
    ));
} 

if (!empty($arResult["TOURS"])) {
    __renderBlockItem(array(
        "title" => GetMessage("EXURSIONS_TITLE"),
        "link_title" => GetMessage("EXURSIONS_LINK_TITLE"),
        "link" => "/tourism/tours-in-belarus/?set_filter=y&arrFilter_591_".$arResult["CRC32_ID"]."=Y",
        "elements" => $arResult["TOURS"]
    ));
}

if (!empty($arResult["PLACEMENTS"])) {
    __renderBlockItem(array(
        "title" => GetMessage("PLACEMENTS_TITLE"),
        "link_title" => GetMessage("PLACEMENTS_LINK_TITLE"),
        "link" => "/tourism/where-to-stay/?set_filter=y&".LANGUAGE_ID."AccomFilter_45=".$arResult["CRC32_ID"],
        "elements" => $arResult["PLACEMENTS"]
    ));
}

if (!empty($arResult["EVENTS"])) {
    __renderBlockItem(array(
        "title" => GetMessage("EVENTS_TITLE"),
        "link_title" => GetMessage("EVENTS_LINK_TITLE"),
        "link" => "/poster/?set_filter=y&arrFilter_128_MIN=" . date("d.m.Y", time()),
        "elements" => $arResult["EVENTS"]
    ));
}
?>