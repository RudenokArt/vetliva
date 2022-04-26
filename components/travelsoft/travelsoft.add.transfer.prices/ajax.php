<?php

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot.'/bitrix/modules/main/include/prolog_before.php');

if (empty( $_SESSION["__TRAVELSOFT"]["CP"]["TAP"] ) || !check_bitrix_sessid()) {
    $protocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
    header($protocol." 404 Not Found");
    exit;
}

header('Content-Type: application/json; charset=' . SITE_CHARSET);

CBitrixComponent::includeComponentClass("travelsoft:travelsoft.add.transfer.prices");

$component = new TravelsoftAddEditTransfersPrices;

$component->arParams["ROW_ID"] = $_SESSION["__TRAVELSOFT"]["CP"]["TAP"]["ROW_ID"];
if ($_SESSION["__TRAVELSOFT"]["CP"]["TAP"]["PROVIDER_ID"]) {
    $component->arParams["PROVIDER_ID"] = $_SESSION["__TRAVELSOFT"]["CP"]["TAP"]["PROVIDER_ID"];
    $component->arParams["SUPER_USER_EDIT"] = 'Y';
}

$component->arParams["SERVICE_TYPE"] = $_SESSION["__TRAVELSOFT"]["CP"]["TAP"]["SERVICE_TYPE"];

$component->checks();

$component->processingRequest();

$component->sendResponse();

    