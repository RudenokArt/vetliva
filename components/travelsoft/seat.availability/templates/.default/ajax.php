<?php

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');

$APPLICATION->IncludeComponent("travelsoft:seat.availability", "", array_merge(['IS_AJAX' => 'Y', 'EMTY_RESULT'=>$_POST['EMTY_RESULT']], $_SESSION["travelsoft_seat_availability_component"]));
