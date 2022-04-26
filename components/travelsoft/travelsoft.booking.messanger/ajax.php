<?php

/**
 * Oбработчик ajax запроса
 * component travelsoft.booking.messanger
 * @author dimabresky
 * @copyright (c) 2017, travelsoft
 */


if (!function_exists("send404")) {
    function send404 () {
        header(filter_input(INPUT_SERVER, 'SERVER_PROTOCOL') . " 404 Not Found");
        exit;
    }
}

if (!function_exists("sendResponse")) {
    function sendResponse ($arResponse) {
        header('Content-Type: application/json; charset=' . SITE_CHARSET);
        echo \Bitrix\Main\Web\Json::encode($arResponse);
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
    require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');
    
    if (!check_bitrix_sessid()) {
        send404();
    }
    
    define("NO_KEEP_STATISTIC", true);
    define("NO_AGENT_STATISTIC",true);
    define('NO_AGENT_CHECK', true);

    CBitrixComponent::includeComponentClass("travelsoft:travelsoft.booking.messanger");
    
    $component = new TravelsoftBookingMessanger;
    
    $component->arParams = array(
        "ORDER_ID" => $_SESSION["__TRAVELSOFT"]["CTBM"]["ORDER_ID"],
        "TOKEN" => $_SESSION["__TRAVELSOFT"]["CTBM"]["TOKEN"],
        "DATE_FROM" => $_SESSION["__TRAVELSOFT"]["CTBM"]["DATE_FROM"]
    );
    
    $component->prepareInputParameters();
    if (
            ($_POST['action'] == "watch" && ($arResponse = $component->getMessages(date("d.m.Y H:i:s")))) ||
            ($_POST["action"] == "sendmessage" && ($arResponse = $component->sendMessage(strip_tags($_POST["message"]))))
    ) {
        sendResponse($arResponse);
    } else {
        sendResponse(array("error" => true));
    }
}

send404();

