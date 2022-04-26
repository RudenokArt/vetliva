<?php

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC","Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

require ($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

$arResponse = array(
    "result" => null,
    "error" => false
);

try {

    $request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

    if(!$request->isPost())
        throw new Exception();

    $id = $request->getPost('id');
    $action = $request->getPost('actionCompare');
    $type = $request->getPost('typeCompare');

    if(!isset($_SESSION['toCompare'])){
        $_SESSION['toCompare']['placement'] = [];
        $_SESSION['toCompare']['sanatorium'] = [];
    }

    if($type == "placement"){

        if (count($_SESSION['toCompare']['placement']) < 10 && !in_array($id, $_SESSION['toCompare']['placement']) && $action == "add") {
            $_SESSION['toCompare']['placement'][] = $id;
            $arResponse["result"]["mess"] = "add object";
        } elseif(in_array($id, $_SESSION['toCompare']['placement']) && $action == "delete") {
            $key = array_search($id, $_SESSION['toCompare']['placement']);
            unset($_SESSION['toCompare']['placement'][$key]);
            $arResponse["result"]["mess"] = "delete object";
        }

    } elseif($type == "sanatorium"){

        if (count($_SESSION['toCompare']['sanatorium']) < 10 && !in_array($id, $_SESSION['toCompare']['sanatorium']) && $action == "add") {
            $_SESSION['toCompare']['sanatorium'][] = $id;
            $arResponse["result"]["mess"] = "add object";
        } elseif(in_array($id, $_SESSION['toCompare']['sanatorium']) && $action == "delete") {
            $key = array_search($id, $_SESSION['toCompare']['sanatorium']);
            unset($_SESSION['toCompare']['sanatorium'][$key]);
            $arResponse["result"]["mess"] = "delete object";
        }

    }

    $arResponse["result"]["sanatorium"] = count($_SESSION['toCompare']['sanatorium']);
    $arResponse["result"]["placement"] = count($_SESSION['toCompare']['placement']);

    echo json_encode($arResponse);

} catch(\Exception $e) {

    $arResponse["error"] = true;
    $arResponse["error_message"] = $e->getMessage();
    echo \travelsoft\sts\Utils::jsonEncode($arResponse);

}
