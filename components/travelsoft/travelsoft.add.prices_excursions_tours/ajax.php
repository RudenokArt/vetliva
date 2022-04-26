<?php

set_time_limit(0);

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot.'/bitrix/modules/main/include/prolog_before.php');

if (empty($_SESSION['TS_APC_PARAMS']) || !check_bitrix_sessid()) {
    $protocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
    header($protocol." 404 Not Found");
    exit;
}

$_SESSION['TS_APC_PARAMS']['IS_AJAX'] = "Y";

header('Content-Type: application/json; charset=' . SITE_CHARSET);

$APPLICATION->IncludeComponent(
    "travelsoft:travelsoft.add.prices_excursions_tours", 
    "", 
    $_SESSION['TS_APC_PARAMS'],
    false
);
    