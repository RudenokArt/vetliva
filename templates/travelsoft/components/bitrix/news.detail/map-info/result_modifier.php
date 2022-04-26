<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
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

if(!$arResult['PREVIEW_PICTURE']){
    $arResult['PREVIEW_PICTURE'] = $arResult['PROPERTIES']['PICTURES']['VALUE'][0];
}

\Kosmos\Main\Helpers\Image::setSelfResizeArray($arResult['PREVIEW_PICTURE'],[165, 100]);


$arResult['NAME_LANG'] = $arResult['NAME'];
switch ($arParams['LANGUAGE_ID']){
	case 'en':
		if($arResult['PROPERTIES']['NAME_EN']['VALUE']){
			$arResult['NAME_LANG'] = $arResult['PROPERTIES']['NAME_EN']['VALUE'];
		}
		break;
	case 'by':
		if($arResult['PROPERTIES']['NAME_BY']['VALUE']) {
			$arResult['NAME_LANG'] = $arResult['PROPERTIES']['NAME_BY']['VALUE'];
		}
		break;
}