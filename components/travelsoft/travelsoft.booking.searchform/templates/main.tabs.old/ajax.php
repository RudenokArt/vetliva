<?php

define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

/** travelsoft.booking.searchform ajax обработчик */
$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');

header('Content-Type: application/json; charset=' . SITE_CHARSET);

use Bitrix\Main\Web\Json;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$_post = $request->getPost('searchFor');

if ($request->isPost() && check_bitrix_sessid() && is_array($_post) && !empty($_post) && !empty($_SESSION['TSBSFC_searchFor'])) {
    \Bitrix\Main\Loader::includeModule("iblock");
    $cnt = count($_post);

    $arStoresID = array_unique($_SESSION['TSBSFC_searchFor']);

    if (!defined("POSTFIX_PROPERTY")) {
        define("POSTFIX_PROPERTY", LANGUAGE_ID === "ru" ? "" : "_" . strtoupper(LANGUAGE_ID));
    }

    for ($i = 0; $i < $cnt; $i++) {

        $storeID = intVal($_post[$i]);

        if (in_array($storeID, $arStoresID)) {

            # получение объектов поиска
            # их фильтрация по наличию цены
            $filterFunc = $type . "FilterByPriceExists";
            $arr_filter = array("IBLOCK_ID" => $storeID, "ACTIVE" => "Y");
            if ($request->getPost('type') === "excursionstours") {
                $arr_filter["PROPERTY_IS_EXCURSION_TOUR_VALUE"] = "Y";
            } elseif ($request->getPost('type')  === "excursions") {
                $arr_filter["!=PROPERTY_IS_EXCURSION_TOUR_VALUE"] = "Y";
            }
            $arRes = $filterFunc(toCache($type . "GetArrayId", $arr_filter, array("ID"), null));

            if ($arRes) {
                // получение результата
                $arResult[$storeID] = toCache($type . "GetResult", array("IBLOCK_ID" => $storeID, "ACTIVE" => "Y", "ID" => $arRes), array("*"), null);
            }
        }
    }

    echo Json::encode($arResult);
}

###############
#  FUNCTIONS
###############

/**
 * @param array $brp
 */
function placementsFilterByPriceExists(array $brp) {
    return filterByPriceExists($brp, "placements");
}

/**
 * @param array $brp
 */
function sanatoriumFilterByPriceExists(array $brp) {
    return filterByPriceExists($brp, "sanatorium");
}

/**
 * @param array $brp
 */
function excursionsFilterByPriceExists(array $brp) {
    return filterByPriceExists($brp, "excursions");
}

/**
 * @param array $brp
 */
function excursionstoursFilterByPriceExists(array $brp) {
    return filterByPriceExists($brp, "excursionstours");
}

/**
 * @param array $brp
 */
function transfersFilterByPriceExists(array $brp) {
    return $brp["id"];
}

# при необходимости реализовать фильтрацию по наличию цены
# функция должна будет возвращать массив ID объектов по которым есть цены
function filterByPriceExists(array $brp, string $type) {
    
    return $brp["id"];
    
}

function toCache($callback, $arFilter, $arSelect = array(), $arOrder = null) {

    $oCache = \Bitrix\Main\Data\Cache::createInstance();

    $cacheDir = "/travelsoft/searchform/" . $arFilter["IBLOCK_ID"];

    $cacheTime = 360000000000;

    $cacheid = serialize(func_get_args()) . LANGUAGE_ID;

    if ($oCache->initCache($cacheTime, $cacheid, $cacheDir)) {
        $arResult = $oCache->getVars();
    } elseif ($oCache->startDataCache()) {
        $arResult = $callback($arFilter, $arSelect, $arOrder);
        if ($arResult) {

            if (defined("BX_COMP_MANAGED_CACHE")) {
                global $CACHE_MANAGER;
                $CACHE_MANAGER->StartTagCache($cacheDir);
                $CACHE_MANAGER->RegisterTag("iblock_id_" . $arFilter["IBLOCK_ID"]);
                $CACHE_MANAGER->EndTagCache();
            }

            $oCache->endDataCache($arResult);
        } else {

            $oCache->abortDataCache();
        }
    }
    return $arResult;
}

function getArrayId($arFilter, $arSelect = array("*"), $arOrder = false) {

    $dbRes = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

    $arResult = null;

    while ($arRes = $dbRes->Fetch()) {
        $arResult[] = $arRes["ID"];
    }

    return array("id" => $arResult);
}

/**
 * ID объектов поиска для объектов размещения
 * @param array $arFilter
 * @param array $arSelect
 * @param array $arOrder
 * @return array
 */
function placementsGetArrayId(array $arFilter, array $arSelect = array("*"), array $arOrder = null) {
    return getArrayId($arFilter, $arSelect, $arOrder);
}

/**
 * ID объектов поиска для санаториев
 * @param array $arFilter
 * @param array $arSelect
 * @param array $arOrder
 * @return array
 */
function sanatoriumGetArrayId(array $arFilter, array $arSelect = array("*"), array $arOrder = null) {
    return getArrayId($arFilter, $arSelect, $arOrder);
}

/**
 * ID объектов поиска для экскурсий
 * @param array $arFilter
 * @param array $arSelect
 * @param array $arOrder
 * @return array
 */
function excursionsGetArrayId(array $arFilter, array $arSelect = array("*"), array $arOrder = null) {
    return getArrayId($arFilter, $arSelect, $arOrder);
}

/**
 * ID объектов поиска для туров
 * @param array $arFilter
 * @param array $arSelect
 * @param array $arOrder
 * @return array
 */
function excursionstoursGetArrayId(array $arFilter, array $arSelect = array("*"), array $arOrder = null) {
    return getArrayId($arFilter, $arSelect, $arOrder);
}

/**
 * ID объектов поиска для трансферов
 * @param array $arFilter
 * @return array
 */
function transfersGetArrayId(array $arFilter) {
    \Bitrix\Main\Loader::includeModule("highloadblock");
    $dataClass = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity(
            \Bitrix\Highloadblock\HighloadBlockTable::getById($arFilter["IBLOCK_ID"])->fetch())->getDataClass();
    $dbRes = $dataClass::getList();
    $arResult = null;
    while ($arRes = $dbRes->fetch()) {
        if ($arRes["UF_POINT_A"] > 0 && !in_array($arRes["UF_POINT_A"], $arResult)) {
            $arResult[] = $arRes["UF_POINT_A"];
        }
        if ($arRes["UF_POINT_B"] > 0 && !in_array($arRes["UF_POINT_B"], $arResult)) {
            $arResult[] = $arRes["UF_POINT_B"];
        }
    }
    
    return array("id" => $arResult);
}

function getResult($arFilter, $arSelect = array("*"), $arOrder = false, $type) {
    $storeID = $arFilter["IBLOCK_ID"];
    unset($arFilter["IBLOCK_ID"]);
    $dbRes = CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

    $arResult = $arLinkCt = $arLinkMedProf = $arLinkSights = $arLinkRegions = $route = null;

    while ($oElements = $dbRes->GetNextElement()) {

        $arFields = $oElements->GetFields();
        $arProperties = $oElements->GetProperties();
        
        if ($arFields["ID"] > 0) {
            $arResult[$arFields["ID"]]["id"] = $arFields["ID"];
            $arResult[$arFields["ID"]]["page"] = $arFields["DETAIL_PAGE_URL"];
            $arResult[$arFields["ID"]]["city"] = "";
            $arResult[$arFields["ID"]]["region"] = "";
            $arResult[$arFields["ID"]]["type"] = $type;
            $route = getName($arProperties['ROUTE']['VALUE'], $arProperties["ROUTE" . POSTFIX_PROPERTY]["VALUE"]);
            if (in_array($type, array("excursionstours", "excursions"))) $arResult[$arFields["ID"]]["route"] = $route;

            # города
            if (in_array("TOWN", $_SESSION["TSBSFC_additionalGet"][$type][$storeID])) {
                if ($arProperties["TOWN"]["VALUE"] > 0) {
                    $arID = (array) $arProperties["TOWN"]["VALUE"];
                    $arLinkCt[$arID[0]][] = $arFields["ID"];
                } elseif ($arProperties["REGION"]["VALUE"] > 0) {
                    $arLinkRegions[$arProperties["REGION"]["VALUE"]][] = $arFields["ID"];
                }
            }
            
            # мед. профиль
            if (in_array("TYPE", $_SESSION["TSBSFC_additionalGet"][$type][$storeID]) &&
                    $arProperties["TYPE"]["VALUE"]) {
                $arID = (array) $arProperties["TYPE"]["VALUE"];
                $arLinkMedProf[$arID[0]][] = $arFields["ID"];
            }
            
            # подклеивать тип объекта к имени элемента
            $namePrefix = "";
            if (in_array("ADD_TYPE_TO_NAME", $_SESSION["TSBSFC_additionalGet"][$type][$storeID])) {

                if ($arProperties["TYPE2"]["VALUE"] > 0) {
                    $namePrefix = getNamePrefix($arProperties["TYPE2"]["VALUE"]) . " ";
                } elseif ($arProperties["TYPE"]["VALUE"]) {
                    if (is_array($arProperties["TYPE"]["VALUE"]) && $arProperties["TYPE"]["VALUE"][0] > 0) {
                        $typeId = $arProperties["TYPE"]["VALUE"][0];
                    } else {
                        $typeId = $arProperties["TYPE"]["VALUE"];
                    }
                    $namePrefix = getNamePrefix($typeId) . " ";
                }
            }

            $arResult[$arFields["ID"]]["name"] = $namePrefix . getName($arFields["NAME"], $arProperties["NAME" . POSTFIX_PROPERTY]["VALUE"]);
        }
    }

    if ($arLinkCt) {

        $dbCtRes = CIBlockElement::GetList(false, array("ID" => array_keys($arLinkCt)), false, false);
        while ($oCity = $dbCtRes->GetNextElement()) {

            $arFields = $oCity->GetFields();
            $arProperties = $oCity->GetProperties();

            if ($arFields["ID"]) {
                $arResult[$arFields["ID"]]["id"] = $arFields["ID"];
                $arResult[$arFields["ID"]]["name"] = getName($arFields["NAME"], $arProperties["NAME" . POSTFIX_PROPERTY]["VALUE"]);
                $arResult[$arFields["ID"]]["region"] = "";

                if ($arProperties["REGION"]["VALUE"]) {
                    $arLinkRegions[$arProperties["REGION"]["VALUE"]][] = $arFields["ID"];
                }

                for ($i = 0, $cnt = count($arLinkCt[$arFields["ID"]]); $i < $cnt; $i++) {
                    $arResult[$arLinkCt[$arFields["ID"]][$i]]["city"] = $arResult[$arFields["ID"]]["name"];
                }
            }
        }
    }

    if ($arLinkRegions) {

        $dbRegionRes = CIBlockElement::GetList(false, array("ID" => array_keys($arLinkRegions)), false, false);
        while ($oRegion = $dbRegionRes->GetNextElement()) {

            $arFields = $oRegion->GetFields();
            $arProperties = $oRegion->GetProperties();

            if ($arFields["ID"]) {
                
                $name = getName($arFields["NAME"], $arProperties["NAME" . POSTFIX_PROPERTY]["VALUE"]);
                
                if (!in_array("NOT_SHOW_REGIONS_LIKE_OBJECT", $_SESSION["TSBSFC_additionalGet"][$type][$storeID])) {
                    $arResult[$arFields["ID"]]["id"] = $arFields["ID"];
                    $arResult[$arFields["ID"]]["name"] = $name;
                }
                
                for ($i = 0, $cnt = count($arLinkRegions[$arFields["ID"]]); $i < $cnt; $i++) {
                    $arResult[$arLinkRegions[$arFields["ID"]][$i]]["region"] = $name;
                    for ($j = 0, $cnt2 = count($arLinkCt[$arLinkRegions[$arFields["ID"]][$i]]); $j < $cnt2; $j++) {
                        $arResult[$arLinkCt[$arLinkRegions[$arFields["ID"]][$i]][$j]]["region"] = $name;
                    }
                }
            }
        }
    }

    if ($arLinkMedProf) {

        $dbMedProf = CIBlockElement::GetList(false, array("ID" => array_keys($arLinkMedProf)), false, false);
        while ($oMedProfi = $dbMedProf->GetNextElement()) {

            $arFields = $oMedProfi->GetFields();
            $arProperties = $oMedProfi->GetProperties();

            if ($arFields["ID"]) {
                $arResult[$arFields["ID"]]["id"] = $arFields["ID"];
                $arResult[$arFields["ID"]]["name"] = getName($arFields["NAME"], $arProperties["NAME" . POSTFIX_PROPERTY]["VALUE"]);
            }
        }
    }

    return $arResult;
}

/**
 * Результат поиска объектов для размещений
 * @param array $arFilter
 * @param array $arSelect
 * @param array $arOrder
 * @return array|null
 */
function placementsGetResult(array $arFilter, array $arSelect = array("*"), array $arOrder = null) {
    return getResult($arFilter, $arSelect, $arOrder, "placements");
}

/**
 * Результат поиска объектов для санаториев
 * @param array $arFilter
 * @param array $arSelect
 * @param array $arOrder
 * @return array|null
 */
function sanatoriumGetResult(array $arFilter, array $arSelect = array("*"), array $arOrder = null) {
    return getResult($arFilter, $arSelect, $arOrder, "sanatorium");
}

/**
 * Результат поиска объектов для экскурсий
 * @param array $arFilter
 * @param array $arSelect
 * @param array $arOrder
 * @return array|null
 */
function excursionsGetResult(array $arFilter, array $arSelect = array("*"), array $arOrder = null) {
    return getResult($arFilter, $arSelect, $arOrder, "excursions");
}

/**
 * Результат поиска объектов для туров
 * @param array $arFilter
 * @param array $arSelect
 * @param array $arOrder
 * @return array|null
 */
function excursionstoursGetResult(array $arFilter, array $arSelect = array("*"), array $arOrder = null) {
    $arFilter["!PROPERTY_IS_EXCURSION_TOUR"] = false;
    $result = getResult($arFilter, $arSelect, $arOrder, "excursionstours");
    
    foreach ($result as &$vv) {
            if (isset($vv["page"])) {
                
                $vv["page"] = str_replace("cognitive-tourism", "tours-in-belarus", $vv["page"]);
            }
        
    }
    return $result;
}

/**
 * Результат поиска объектов для трансферов
 * @param array $arFilter
 * @param array $arSelect
 * @param array $arOrder
 * @return array|null
 */
function transfersGetResult(array $arFilter, array $arSelect = array("*"), array $arOrder = null) {
    return getResult($arFilter, $arSelect, $arOrder, "transfers");
}

/**
 * возвращает имя в зависимости от языка
 * @param string $name
 * @param string $namebyLang
 * @return string
 */
function getName($name, $namebyLang) {

    if (LANGUAGE_ID == "ru") {
        return $name;
    }

    return $namebyLang;
}

function getNamePrefix(int $id) {
    $oEL = CIBlockElement::GetByID($id)->GetNextElement();
    $arFields = $oEL->GetFields();
    $arProperties = $oEL->GetProperties();
    return getName($arFields["NAME"], $arProperties["NAME" . POSTFIX_PROPERTY]["VALUE"]);
}
