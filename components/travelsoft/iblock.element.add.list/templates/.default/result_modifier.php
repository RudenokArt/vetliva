<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$edit_link = "/partners/tseny-i-nalichie-mest/dobavlenie-redaktirovanie-tsen";
if (IS_EXCURSION_TOUR === "Y") {
    $edit_link = "/partners/tseny-i-nalichie-mest/dobavlenie-redaktirovanie-tsen-dlya-exc";
    if ($arParams['FILTER']["PROPERTY_IS_EXCURSION_TOUR_VALUE"] === "Y") {
        $edit_link = "/partners/tseny-i-nalichie-mest/dobavlenie-redaktirovanie-tsen-dlya-mnogodnevnykh-turov";
    }
}
// формирование ссылки на добавление по экскурсионным турам
if ($arParams["IS_EXC_TOUR"] == "Y") {

    foreach ($arResult["ELEMENTS"] as &$arElement) {
        $id[] = $arElement["ID"];
    }

    if ($id) {

        Bitrix\Main\Loader::includeModule("highloadblock");
        $data_class = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity(
            \Bitrix\Highloadblock\HighloadBlockTable::getById(SERVICES_BOOKING_HL_BLOCK)->fetch())->getDataClass();

        $db_res = $data_class::getList(array(
            "filter" => array("UF_IBLOCK_ELEMENT_ID" => $id),
            "select" => array("ID", "UF_IBLOCK_ELEMENT_ID")
        ));

        while ($res = $db_res->fetch()) {
            $arResult["add_price"][$res["UF_IBLOCK_ELEMENT_ID"]] = "<li ><a title='Добавить цены' href=\"$edit_link/?row_id=" . $res["ID"] . "\"><i class=\"icon-coins\"></i></a></li>";
        }


    }

}

// поскольку кеша нет, то отправляем письмо запроса на активацию прямо отсюда
if (bitrix_sessid_post() && key_exists("activation_request", $_REQUEST) && isset($_REQUEST["activation_request"]) && $_REQUEST["element_id"] > 0) {

    $element = CIBlockElement::GetByID($_REQUEST["element_id"])->Fetch();

    if (isset($element["NAME"]) && strlen($element["NAME"]) > 0) {
        \CEvent::Send("TRAVELSOFT_BOOKING", SITE_ID, array(
            "ELEMENT_NAME" => $element["NAME"] . " (" . $USER->GetFullName() . "[" . $USER->GetEmail() . "])"
        ), "N", ACTIVATION_REQUEST_MAIL_TEMPLATE_ID);
        $_SESSION["ACTIVATION_REQUEST_ALREADY_DONE"][] = $element["ID"];
        LocalRedirect($APPLICATION->GetCurPageParam("activation_request_ok", array("sessid", "element_id", "activation_request")));
    }
}

if (bitrix_sessid_post() && key_exists("deleting_request", $_REQUEST) && isset($_REQUEST["deleting_request"]) && $_REQUEST["element_id"] > 0) {

    $element = CIBlockElement::GetByID($_REQUEST["element_id"])->Fetch();

    if (isset($element["NAME"]) && strlen($element["NAME"]) > 0) {
        \CEvent::Send("TRAVELSOFT_BOOKING", SITE_ID, array(
            "ELEMENT_NAME" => $element["NAME"] . " (" . $USER->GetFullName() . "[" . $USER->GetEmail() . "])"
        ), "N", DELETING_REQUEST_MAIL_TEMPLATE_ID);
        //$_SESSION["ACTIVATION_REQUEST_ALREADY_DONE"][] = $element["ID"];
        LocalRedirect($APPLICATION->GetCurPageParam("deleting_request_ok", array("sessid", "element_id", "deleting_request")));
    }
}
