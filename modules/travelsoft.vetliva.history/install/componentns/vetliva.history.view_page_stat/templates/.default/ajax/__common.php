<?php
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');

header('Content-Type: application/json; charset=' . SITE_CHARSET);

$hash = md5(intval($_POST["ID"]));

if (!check_bitrix_sessid() || $hash !== $_POST["hash"]) {
    echo \json_encode(["error" => true]);
    die;
}