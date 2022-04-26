<?php

/**
 * Oбработчик ajax запроса
 * component travelsoft.booking.messanger
 * @author dimabresky
 * @copyright (c) 2017, travelsoft
 */
if (!function_exists("send404")) {

    function send404() {
        header(filter_input(INPUT_SERVER, 'SERVER_PROTOCOL') . " 404 Not Found");
        exit;
    }

}

if (!function_exists("sendResponse")) {

    function sendResponse($arResponse) {
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
    define("NO_AGENT_STATISTIC", true);
    define('NO_AGENT_CHECK', true);

    \Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");

    if ($_POST["maxAge"] > 0 && $_POST["minAge"] >= 0 && $_POST["maxAge"] > $_POST["minAge"]) {

        if ($_POST["without_place"] === "Y") {
            
            $data = \travelsoft\booking\datastores\PriceTypesDataStore::get(array(
                        "filter" => array("UF_AGE_MIN" => $_POST["minAge"],
                            "UF_AGE_MAX" => $_POST["maxAge"], "UF_WPHP" => 1)
            ));
            
            if (empty($data)) {
                
                $id_ = \travelsoft\booking\datastores\PriceTypesDataStore::save(array(
                            "UF_NAME" => "Стоимость для ребенка без места (ребенок " . $_POST["minAge"] . "-" . $_POST["maxAge"] . " лет)",
                            "UF_ACTIVE" => 1,
                            "UF_SUB_PRICE_TYPES" => array(),
                            "UF_CALC_WIDGET" => "\\\\travelsoft\\\\booking\\\\CalculationWidgets::priceForChildWithoutPlace",
                            "UF_FOR_MDEXC" => 1,
                            "UF_FOR_ROOM" => 1,
                            "UF_TL_CODE" => "childBandBed",
                            "UF_WPHP" => 1,
                            "UF_AGE_MAX" => $_POST['maxAge'],
                            "UF_AGE_MIN" => $_POST['minAge']
                ));
                
                $pt_ = current(\travelsoft\booking\datastores\PriceTypesDataStore::get(array(
                            "filter" => array("ID" => 7)
                )));

                if (!empty($pt_)) {
                    $pt_["UF_SUB_PRICE_TYPES"][] = $id_;
                    \travelsoft\booking\datastores\PriceTypesDataStore::save(array(
                        "UF_SUB_PRICE_TYPES" => $pt_["UF_SUB_PRICE_TYPES"]
                            ), $pt_["ID"]);
                }

                $pt__ = current(\travelsoft\booking\datastores\PriceTypesDataStore::get(array(
                            "filter" => array("ID" => 9)
                )));

                if (!empty($pt__)) {
                    $pt__["UF_SUB_PRICE_TYPES"][] = $id_;
                    \travelsoft\booking\datastores\PriceTypesDataStore::save(array(
                        "UF_SUB_PRICE_TYPES" => $pt__["UF_SUB_PRICE_TYPES"]
                            ), $pt__["ID"]);
                }
                
            }
            
            sendResponse(array(
                    array(
                        "ID" => $id_,
                        "UF_NAME" => "Стоимость для ребенка без места (ребенок " . $_POST["minAge"] . "-" . $_POST["maxAge"] . " лет)",
                        "UF_ACTIVE" => 1,
                        "UF_FOR_MDEXC" => 1,
                        "UF_SUB_PRICE_TYPES" => array(),
                        "UF_FOR_ROOM" => 1,
                        "UF_TL_CODE" => "childBandBed",
                        "UF_WPHP" => 1,
                        "UF_AGE_MAX" => $_POST['maxAge'],
                        "UF_AGE_MIN" => $_POST['minAge']
                    )
                ));
            
        } else {

            $data = \travelsoft\booking\datastores\PriceTypesDataStore::get(array(
                        "filter" => array("UF_AGE_MIN" => $_POST["minAge"],
                            "UF_AGE_MAX" => $_POST["maxAge"], array("LOGIC" => "OR", array("UF_MCHP" => 1), array("UF_ADCHP" => 1)))
            ));

            if (empty($data)) {

                $id_ = \travelsoft\booking\datastores\PriceTypesDataStore::save(array(
                            "UF_NAME" => "Стоимость за место (ребенок " . $_POST["minAge"] . "-" . $_POST["maxAge"] . " лет)",
                            "UF_ACTIVE" => 1,
                            "UF_SUB_PRICE_TYPES" => array(),
                            "UF_CALC_WIDGET" => "\\\\travelsoft\\\\booking\\\\CalculationWidgets::priceForPlaceChild",
                            "UF_FOR_MDEXC" => 1,
                            "UF_FOR_ODEXC" => 1,
                            "UF_FOR_PLACE" => 1,
                            "UF_TL_CODE" => "childBandBed",
                            "UF_MCHP" => 1,
                            "UF_AGE_MAX" => $_POST['maxAge'],
                            "UF_AGE_MIN" => $_POST['minAge']
                ));


                $id__ = \travelsoft\booking\datastores\PriceTypesDataStore::save(array(
                            "UF_NAME" => "Стоимость за дополнительное место (ребенок " . $_POST["minAge"] . "-" . $_POST["maxAge"] . " лет)",
                            "UF_ACTIVE" => 1,
                            "UF_SUB_PRICE_TYPES" => array(),
                            "UF_CALC_WIDGET" => "\\\\travelsoft\\\\booking\\\\CalculationWidgets::priceForAdditionalPlaceChild",
                            "UF_FOR_MDEXC" => 1,
                            "UF_FOR_ROOM" => 1,
                            "UF_FOR_PLACE" => 1,
                            "UF_TL_CODE" => "childBandExtraBed",
                            "UF_ADCHP" => 1,
                            "UF_AGE_MAX" => $_POST['maxAge'],
                            "UF_AGE_MIN" => $_POST['minAge']
                ));

                $pt_ = current(\travelsoft\booking\datastores\PriceTypesDataStore::get(array(
                            "filter" => array("ID" => 7)
                )));

                if (!empty($pt_)) {
                    $pt_["UF_SUB_PRICE_TYPES"][] = $id__;
                    \travelsoft\booking\datastores\PriceTypesDataStore::save(array(
                        "UF_SUB_PRICE_TYPES" => $pt_["UF_SUB_PRICE_TYPES"]
                            ), $pt_["ID"]);
                }

                $pt__ = current(\travelsoft\booking\datastores\PriceTypesDataStore::get(array(
                            "filter" => array("ID" => 9)
                )));

                if (!empty($pt__)) {
                    $pt__["UF_SUB_PRICE_TYPES"][] = $id_;
                    $pt__["UF_SUB_PRICE_TYPES"][] = $id__;
                    \travelsoft\booking\datastores\PriceTypesDataStore::save(array(
                        "UF_SUB_PRICE_TYPES" => $pt__["UF_SUB_PRICE_TYPES"]
                            ), $pt__["ID"]);
                }

                sendResponse(array(
                    array(
                        "ID" => $id_,
                        "UF_NAME" => "Стоимость за место (ребенок " . $_POST["minAge"] . "-" . $_POST["maxAge"] . " лет)",
                        "UF_ACTIVE" => 1,
                        "UF_FOR_MDEXC" => 1,
                        "UF_SUB_PRICE_TYPES" => array(),
                        "UF_FOR_ODEXC" => 1,
                        "UF_FOR_PLACE" => 1,
                        "UF_TL_CODE" => "childBandBed",
                        "UF_MCHP" => 1,
                        "UF_AGE_MAX" => $_POST['maxAge'],
                        "UF_AGE_MIN" => $_POST['minAge']
                    ),
                    array(
                        "ID" => $id__,
                        "UF_NAME" => "Стоимость за дополнительное место (ребенок " . $_POST["minAge"] . "-" . $_POST["maxAge"] . " лет)",
                        "UF_ACTIVE" => 1,
                        "UF_FOR_MDEXC" => 1,
                        "UF_SUB_PRICE_TYPES" => array(),
                        "UF_FOR_ROOM" => 1,
                        "UF_FOR_PLACE" => 1,
                        "UF_TL_CODE" => "childBandExtraBed",
                        "UF_ADCHP" => 1,
                        "UF_AGE_MAX" => $_POST['maxAge'],
                        "UF_AGE_MIN" => $_POST['minAge']
                    )
                ));
            }
        }
    }

    sendResponse(array("error" => true));
}

send404();

