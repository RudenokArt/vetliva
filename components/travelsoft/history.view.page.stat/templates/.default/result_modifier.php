<?php

$sds = new \travelsoft\booking\datastores\ServicesDataStore(array(
    'filter' => array(
        'UF_IBLOCK_ELEMENT_ID' => $arParams['ID']
    ),
    'select' => array('ID', 'UF_IBLOCK_ELEMENT_ID')
        ));

$arDataStore = current($sds->fetch());

$arResponse = \Bitrix\Main\Web\Json::decode(travelsoft\booking\Gateway::getServicesStatisticsByPartner(array(
                    'url' => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                    'params' => array(
                        'listOfConditions' => array(
                            array(
                                'dateFrom' => $arResult['DATE_FROM'],
                                'dateTo' => $arResult['DATE_TO'],
                                'serviceId' => $arDataStore['ID']
                            )
                        )
            )))
);

$arCountResult = array();
$total = 0;
foreach ($arResult['COUNT']['BY_DATES'] as $date => $count) {
    
    if (!isset($arCountResult['BY_DATES'][$date])) {
        $arCountResult['BY_DATES'][$date]['SHOWS'] = $count;
    }
    
    foreach ($arResponse['result'] as $arResponseResult) {
        
        $arCountResult['BY_DATES'][$date]['QUANTITY_BOOK'] += $arResponseResult['quantity_on_every_day'][$date];
        $total += $arResponseResult['quantity_on_every_day'][$date];
    }
    
}

$arCountResult['TOTAL']['SHOWS'] = $arResult['COUNT']['TOTAL'];
$arCountResult['TOTAL']['QUANTITY_BOOK'] = $total;

$arResult['COUNT'] = $arCountResult;
