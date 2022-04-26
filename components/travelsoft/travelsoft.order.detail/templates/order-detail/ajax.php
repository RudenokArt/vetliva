<?php

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');

global $USER;

if (!check_bitrix_sessid()) {
    $protocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
    header($protocol . " 404 Not Found");
    exit;
}

header('Content-Type: application/json; charset=' . SITE_CHARSET);

CEvent::Send("TRAVELSOFT_BOOKING", SITE_ID, array(
    "ORDER" => $_POST["order_id"],
    "CLIENT" => $USER->GetFullName() . "[" . $USER->GetEmail() . "]",
    "EDITABLE_TOURIST" => $_POST["editable_tourist"],
    "FIRST_NAME" => $_POST["first_name"],
    "LAST_NAME" => $_POST["last_name"],
    "BIRTHDATE" => $_POST["birth_date"],
    "CITIZENSHIP" => $_POST["citizenship"],
    "PASSPORT_NUM" => $_POST["passport_num"],
        ), "N", EDIT_TOURIST_REQUEST_MAIL_TEMPLATE_ID);
echo \Bitrix\Main\Web\Json::encode(array("status" => "ok"));
