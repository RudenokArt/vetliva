<?php

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');
include "_functions.php";

if (
    !check_bitrix_sessid() ||
    !Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools") ||
    !Bitrix\Main\Loader::includeModule("travelsoft.vetliva.history") ||
    !Bitrix\Main\Loader::includeModule("travelsoft.currency") ||
    empty($_REQUEST["HISTORY_ARCHIVE"]) ||
    !is_array($_REQUEST["HISTORY_ARCHIVE"])
) {
   __exit();
}


