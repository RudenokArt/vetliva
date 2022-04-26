<?php

set_time_limit(0);

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("BX_SECURITY_SHOW_MESSAGE", false);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot.'/bitrix/modules/main/include/prolog_before.php');

if (empty($_SESSION['TS_COM_PARAMS_' . $_REQUEST["type"]]) || !\check_bitrix_sessid()) {
    $protocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
    header($protocol." 404 Not Found");
    exit;
}

header('Content-Type: application/json; charset=' . SITE_CHARSET);

$APPLICATION->IncludeComponent(
    "travelsoft:crossale", 
    "", 
    $_SESSION['TS_COM_PARAMS_' . $_REQUEST["type"]],
    false
);