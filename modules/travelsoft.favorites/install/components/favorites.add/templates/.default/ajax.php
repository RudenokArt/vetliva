<?php

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');

header('Content-Type: application/json; charset=' . SITE_CHARSET);

if (!check_bitrix_sessid()) {
    Bitrix\Main\Loader::includeModule("travelsoft.favorites");
    return travelsoft\favorites\Utils::sendJsonResponse(\json_encode(["error" => false]));
}

CBitrixComponent::includeComponentClass("travelsoft:favorites.add");

$component = new FavoritesAdd;

$component->arParams = [
    "OBJECT_TYPE" => $_REQUEST["OBJECT_TYPE"],
    "OBJECT_ID" => $_REQUEST["OBJECT_ID"],
    "STORE_ID" => $_REQUEST["STORE_ID"]
];

$component->prepareParameters();

$component->processingRequest();