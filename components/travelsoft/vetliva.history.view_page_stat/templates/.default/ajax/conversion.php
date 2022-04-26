<?php

require '__common.php';

Bitrix\Main\Loader::includeModule("travelsoft.vetliva.history");

CBitrixComponent::includeComponentClass("travelsoft:vetliva.history.view_page_stat");

$component = new TravelsoftVetlivaHistoryPageViewStatistic();

$component->arParams["ID"] = intval($_POST["ID"]);

try {
    echo \json_encode($component->conversionStatistic());
} catch (Exception $ex) {
    echo \json_encode(['error' => true]);
}

die();

