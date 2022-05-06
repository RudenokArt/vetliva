<?php

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Web\Json;

if (!check_bitrix_sessid()) {
    $protocol = filter_input(INPUT_SERVER, 'SERVER_PROTOCOL');
    header($protocol . " 404 Not Found");
    exit;
}

header('Content-Type: application/json; charset=' . SITE_CHARSET);

try {

    CBitrixComponent::includeComponentClass("travelsoft:travelsoft.service.price.result");

    if (!defined("POSTFIX_PROPERTY")) {
        define("POSTFIX_PROPERTY", LANGUAGE_ID === "ru" ? "" : "_" . strtoupper(LANGUAGE_ID));
    }

    $component = new TravelsoftServicePriceResult ();

    $component->includeModules();

    $component->setRequest();
    if ($component->attemptAddToCart()) {
    ob_start();
        $APPLICATION->IncludeComponent(
                "travelsoft:small.basket", "vetliva", Array()
        );   
        $buffer = ob_get_clean(); 
        echo Json::encode(array("message_ok" => "ok", "baskethtml"=>$buffer));
    } elseif ((string) $component->request->get("type") === "settling_by_rooms_add2basket" && !empty($component->request->get("add2basket_offers"))) {

        foreach ($component->request->get("add2basket_offers") as $serialize_offer) {

            $dec_add2cart = \travelsoft\booking\Encoder::decode($serialize_offer);
            if (\travelsoft\booking\Encoder::checkhash($dec_add2cart['add2cart'], $dec_add2cart["hash"], \travelsoft\booking\Utils::getOpt("salt"))) {

                (new travelsoft\booking\Basket)->add(unserialize($dec_add2cart['add2cart']));
            }
        }
        echo Json::encode(array("status" => "ok"));
    } elseif ((string) $component->request->get("type") === "rate" && ($id = (int) $component->request->get("id"))) {

        $arRates = ( new travelsoft\booking\datastores\RatesDataStore(array(
            "filter" => array("ID" => $id),
            "select" => array(
                "ID",
                "UF_NAME" . POSTFIX_PROPERTY,
                "UF_NOTE" . POSTFIX_PROPERTY
            )
                )))->fetch();

        echo Json::encode(array(
            "NAME" => $arRates[0]["UF_NAME" . POSTFIX_PROPERTY],
            "ID" => $arRates[0]["ID"],
            "NOTE" => $arRates[0]["UF_NOTE" . POSTFIX_PROPERTY]
        ));
    } elseif ((string) $component->request->get("type") === "service" && ($id = (int) $component->request->get("id"))) {


        $arService = ( new travelsoft\booking\datastores\ServicesDataStore(array(
            "filter" => array("ID" => $id),
            "select" => array(
                "ID",
                "UF_NAME" . POSTFIX_PROPERTY,
                "UF_SERVICE_DESC" . POSTFIX_PROPERTY,
                "UF_SERVICES_IN_ROOM",
                "UF_PICTURES",
                "UF_PEOPLE",
                "UF_PLACES_ADD",
                "UF_SOFA_BAD",
                "UF_SQUARE",
                "UF_PLACES_ADD",
                "UF_PLACES_MAIN",
                "UF_BAD1",
                "UF_BAD2"
            ))))->fetch();

        foreach ($arService[0]["UF_PICTURES"] as $img_id) {

            $file_small = CFile::ResizeImageGet($img_id, array("width" => 120, "height" => 90), BX_RESIZE_IMAGE_EXACT, true);
            $file_big = CFile::ResizeImageGet($img_id, array("width" => 500, "height" => 400), BX_RESIZE_IMAGE_EXACT, true);

            $img_marks['small'][] = $file_small["src"];
            $img_marks["big"][] = $file_big["src"];
        }

        $result["PICTURES"] = $img_marks;

        if (!empty($arService[0]["UF_SERVICES_IN_ROOM"])) {

            \Bitrix\Main\Loader::includeModule("iblock");

            $arSelect = array("ID");

            $dbRes = CIBlockElement::GetList(false, array("ID" => $arService[0]["UF_SERVICES_IN_ROOM"], "ACTIVE" => "Y"), false, false, array("*"));

            $arService[0]["SERVICES"] = null;
            while ($arRes = $dbRes->GetNextElement()) {
                $arFields = $arRes->GetFields();
                $arProperties = $arRes->GetProperties();
                $result["SERVICES"][] = LANGUAGE_ID == "ru" ? $arFields["NAME"] : $arProperties["NAME" . POSTFIX_PROPERTY]["VALUE"];
            }
        }

        $result["ID"] = $arService[0]["ID"];
        $result["NAME"] = $arService[0]["UF_NAME" . POSTFIX_PROPERTY];
        $result["DESC"] = $arService[0]["UF_SERVICE_DESC" . POSTFIX_PROPERTY];
        $result["PEOPLE"] = $arService[0]["UF_PEOPLE"];
        $result["SOFA_BAD"] = $arService[0]["UF_SOFA_BAD"];
        $result["SQUARE"] = $arService[0]["UF_SQUARE"];
        $result["PLACES_ADD"] = $arService[0]["UF_PLACES_ADD"];
        $result["PLACES_MAIN"] = $arService[0]["UF_PLACES_MAIN"];
        $result["BAD1"] = $arService[0]["UF_BAD1"];
        $result["BAD2"] = $arService[0]["UF_BAD2"];

        echo Json::encode($result);
    } elseif ((string) $component->request->get("type") === "cprice") {
        $sparams = $component->request->get("sparams");
        $type = $sparams["objectType"];
        unset($sparams["objectType"]);

        ob_start();
        $APPLICATION->IncludeComponent(
                "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
            "FILTER_BY_PRICES_FOR_CITIZEN" => "Y",
            "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
            "TYPE" => $type,
            "IS_AJAX" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600,
            "__BOOKING_REQUEST" => $sparams
                )
        );

        echo Json::encode(array("content" => ob_get_clean()));
    } elseif ((string) $component->request->get("type") === "get_search_offers_result") {

        $sparams = (array) $component->request->get("sparams");
        $type = $sparams["objectType"];
        unset($sparams["objectType"]);
        ob_start();
        $parameters = $APPLICATION->IncludeComponent(
                "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
            "CODE" => $sparams["code"],
            "FILTER_BY_PRICES_FOR_CITIZEN" => $component->request->get("filter_by_prices_for_citizen") ? "Y" : "N",
            "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
            "TYPE" => $type,
            "IS_AJAX" => "Y",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600,
            "__BOOKING_REQUEST" => $sparams
                )
        );

        echo Json::encode(array("content" => ob_get_clean(), "parameters" => $parameters));
    } elseif ((string) $component->request->get("type") === "cancellation_policy" && ($id = (int) $component->request->get("id"))) {

        $arRate = travelsoft\booking\datastores\RatesDataStore::get(array(
                    "filter" => array("ID" => $id),
                    "select" => array(
                        "ID",
                        "UF_CANCEL_POLICY" . POSTFIX_PROPERTY
        )));

        $result["ID"] = null;
        $result["CANCELLATION_POLICY_TEXT"] = $arRate[0]["UF_CANCEL_POLICY" . POSTFIX_PROPERTY];

        echo Json::encode($result);
    } elseif ((string) $component->request->getPost("type") === "callback") {

        Bitrix\Main\Loader::includeModule("iblock");

        $el = new \CIBlockElement;

        $element_id = $el->Add(array(
            "IBLOCK_ID" => CALLBACK_IBLOCK_ID,
            "NAME" => $request->getPost("full_name"),
            "DETAIL_TEXT" => $request->getPost("comment"),
            "PROPERTY_VALUES" => array(
                "EMAIL" => $request->getPost("email"),
                "PHONE" => $request->getPost("phone"),
                "DATE" => $request->getPost("date"),
                "OBJECTNAME" =>$request->getPost("object_name"),
                "PAGE" => $_SERVER["HTTP_REFERER"]
            )
        ));

        if ($element_id > 0) {
          $request_form_lead = new B24_Leads();
          $request_form_lead->getRequestFormData();
            CEvent::Send("FEEDBACK_FORM", SITE_ID, array(
                "PAGE" => $_SERVER["HTTP_REFERER"],
                "OBJECTNAME" => $request->getPost("object_name"),
                "NAME" => $request->getPost("full_name"),
                "EMAIL" => $request->getPost("email"),
                "PHONE" => $request->getPost("phone"),
                "DATE" => $request->getPost("date"),
                "COMMENT" => strip_tags($request->getPost("comment"))
                    ), "N", 86); //CALLBACK_MAIL_MESSAGE_ID);
            echo Json::encode(array("callback_ok" => true));
        } else {
            echo Json::encode(array("callback_error" => true));
        }
    } else {

        throw new Exception("Неизветсный тип запроса");
    }
} catch (Exception $e) {
    echo Json::encode(array("error_message" => "Возникла ошибка при обработке запроса. "
        . "За дополнительной информацией обратитесь к администратору сайта"));
}



