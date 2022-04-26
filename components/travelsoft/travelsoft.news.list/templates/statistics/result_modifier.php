<?php

foreach ($arResult["ITEMS"] as $arItem) {
    $arItemsId[] = $arItem["ID"];
}

# собираем статистику по объектам поставщика
if ($arItemsId) {

    Bitrix\Main\Loader::includeModule("travelsoft.history");

    $now = time();

    $todayBegin = mktime(0, 0, 0, date("m", $now), date("d", $now), date('Y', $now));
    $todayEnd = mktime(23, 59, 59, date("m", $now), date("d", $now), date('Y', $now));

    $weekBegin = mktime(0, 0, 0, date("m", $now - (86400 * 7)), date("d", $now - (86400 * 7)), date('Y', $now - (86400 * 7)));

    $monthBegin = mktime(0, 0, 0, date("m", $now - (86400 * 31)), date("d", $now - (86400 * 31)), date('Y', $now - (86400 * 31)));

    $dbList = \travelsoft\History::getInstance()->get(array(
        "filter" => array("UF_ELEMENT_ID" => $arItemsId, "UF_OBJECT" => "PAGE", "UF_ACTION" => "VIEW_PAGE", "><UF_DATE" => array($monthBegin, $todayEnd))
            ), false);
    while ($arRes = $dbList->fetch()) {

        if ($arRes["UF_DATE"] <= $todayEnd && $arRes["UF_DATE"] >= $todayBegin) {
            $arResult["STATISTICS"]['SHOWS']["TODAY"][$arRes["UF_ELEMENT_ID"]] ++;
            $arResult["STATISTICS"]['SHOWS']["WEEK"][$arRes["UF_ELEMENT_ID"]] ++;
            $arResult["STATISTICS"]['SHOWS']["MONTH"][$arRes["UF_ELEMENT_ID"]] ++;
            continue;
        }

        if ($arRes["UF_DATE"] <= $todayEnd && $arRes["UF_DATE"] >= $weekBegin) {
            $arResult["STATISTICS"]['SHOWS']["WEEK"][$arRes["UF_ELEMENT_ID"]] ++;
            $arResult["STATISTICS"]['SHOWS']["MONTH"][$arRes["UF_ELEMENT_ID"]] ++;
            continue;
        }

        if ($arRes["UF_DATE"] <= $todayEnd && $arRes["UF_DATE"] >= $monthBegin) {
            $arResult["STATISTICS"]['SHOWS']["MONTH"][$arRes["UF_ELEMENT_ID"]] ++;
        }
    }

    $listOfConditions = array();

    $df = date('d.m.Y', $monthBegin);
    $dt = date('d.m.Y', $todayEnd);

    $serviceDataStore = new \travelsoft\booking\datastores\ServicesDataStore(array(
        'filter' => array('UF_IBLOCK_ELEMENT_ID' => $arItemsId),
        'select' => array('ID', 'UF_IBLOCK_ELEMENT_ID')
    ));

    $arRes = $serviceDataStore->fetch();

    $arParentLink = array();

    foreach ($arRes as $arResData) {

        $listOfConditions[] = array(
            'dateFrom' => $df,
            'dateTo' => $dt,
            'serviceId' => $arResData['ID']
        );

        $arParentLink[$arResData['ID']][] = $arResData['UF_IBLOCK_ELEMENT_ID'];
    }

    $arResponse = \Bitrix\Main\Web\Json::decode(travelsoft\booking\Gateway::getServicesStatisticsByPartner(array(
                        'url' => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                        'params' => array('listOfConditions' => $listOfConditions)
    )));

   
    
    if ($arResponse['result']) {

        foreach ($arResponse['result'] as $arResponseData) {

                foreach ($arParentLink[$arResponseData['serviceId']] as $parentId) {

                    foreach ($arResponseData['quantity_on_every_day'] as $date => $quantity) {

                            $timestamp = MakeTimeStamp($date);

                            if ($timestamp <= $todayEnd && $timestamp >= $todayBegin) {
                                $arResult["STATISTICS"]['QUANTITY_BOOK']["TODAY"][$parentId] += $quantity;
                                $arResult["STATISTICS"]['QUANTITY_BOOK']["WEEK"][$parentId] += $quantity;
                                $arResult["STATISTICS"]['QUANTITY_BOOK']["MONTH"][$parentId] += $quantity;
                                continue;
                            }

                            if ($timestamp <= $todayEnd && $timestamp >= $weekBegin) {
                                $arResult["STATISTICS"]['QUANTITY_BOOK']["WEEK"][$parentId] += $quantity;
                                $arResult["STATISTICS"]['QUANTITY_BOOK']["MONTH"][$parentId] += $quantity;
                                continue;
                            }

                            if ($timestamp <= $todayEnd && $timestamp >= $monthBegin) {
                                $arResult["STATISTICS"]['QUANTITY_BOOK']["MONTH"][$parentId] += $quantity;
                            }
                        
                    }
                }
            
        }
    }
}