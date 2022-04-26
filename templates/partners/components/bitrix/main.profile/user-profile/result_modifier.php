<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult['arUser']['GROUPS'] = \Bitrix\Main\UserTable::getUserGroupIds($arResult['arUser']['ID']);
$arResult['arUser']['IS_GUIDE'] = in_array(\travelsoft\booking\Utils::getOpt('guide_group'), $arResult['arUser']['GROUPS']);

if($arResult['arUser']['IS_GUIDE']){

    $arResult['DATA']['LOCATIONS'] = \Kosmos\Main\Helpers\Data\Location::getList();
    $arResult['DATA']['LANGUAGES'] = \Kosmos\Main\Helpers\Data\Language::getList();
    $arResult['DATA']['TOUR_TYPES'] = \Kosmos\Main\Helpers\Data\TourType::getList();
    $arResult['DATA']['REGIONS'] = \Kosmos\Main\Helpers\Data\Region::getList();
    $arResult['DATA']['SIGHTS'] = \Kosmos\Main\Helpers\Data\Sight::getList();

    $arResult['GUIDE'] = \Kosmos\Main\Helpers\Guide::get();
}
