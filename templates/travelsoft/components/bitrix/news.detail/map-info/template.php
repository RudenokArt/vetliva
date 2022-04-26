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

use \Bitrix\Main\Localization\Loc;
?>
<div class="link-map" style="text-align:center;padding: 5px 0 0 15px;max-width: 180px;">
    <?php if($arResult['PREVIEW_PICTURE']):?>
        <img src="<?=$arResult['PREVIEW_PICTURE']['RESIZE'][0]['SIZES']['DEFAULT']?>" alt="<?=$arResult['PREVIEW_PICTURE']['RESIZE'][0]['META']['ALT']?>">
    <?php endif?>
    <br><br>
    <a href="<?=$arResult['DETAIL_PAGE_URL']?>" target="_blank" title="<?=$arResult['NAME_LANG']?>"><?=$arResult['NAME_LANG']?></a>
</div>