<?php
define("STOP_STATISTICS", true);
define("NO_KEEP_STATISTIC", "Y");
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("BX_SECURITY_SHOW_MESSAGE", true);

$documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
require_once($documentRoot . '/bitrix/modules/main/include/prolog_before.php');

$hash = md5(serialize($_POST["items_id"]));
$response = ["error" => true, 'content' => '', 'data' => [], 'detail_info' => ''];
$data = [];

if (check_bitrix_sessid() && isset($_POST["hash"]) && $hash === $_POST["hash"] && !empty($_POST["items_id"])) {


    Bitrix\Main\Loader::includeModule("travelsoft.vetliva.history");
    Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");

    $dbItemsList = CIBlockElement::GetList(false, [
                "ID" => $_POST["items_id"],
                    ], false, false, [
                "ID", "NAME"
    ]);

    $arResult["ITEMS"] = [];
    while ($arItem = $dbItemsList->Fetch()) {
        $arResult["ITEMS"][] = ["ID" => $arItem["ID"], "NAME" => $arItem["NAME"]];
    }

    if (!empty($arResult["ITEMS"])) {
        $now = time();

        $todayBegin = mktime(0, 0, 0, date("m", $now), date("d", $now), date('Y', $now));
        $todayEnd = mktime(23, 59, 59, date("m", $now), date("d", $now), date('Y', $now));

        $weekBegin = mktime(0, 0, 0, date("m", $now - (86400 * 7)), date("d", $now - (86400 * 7)), date('Y', $now - (86400 * 7)));

        $monthBegin = mktime(0, 0, 0, date("m", $now - (86400 * 31)), date("d", $now - (86400 * 31)), date('Y', $now - (86400 * 31)));

        $dbList = travelsoft\vetliva\DBHistory::getInstance()->getPageViews(
                [
                    "where" => [
                        "UF_PAGE_ID" => \array_values(\array_column($arResult["ITEMS"], "ID")),
                        "><UF_DATE" => [date("Y-m-d", $monthBegin), date("Y-m-d", $todayEnd)]
                    ]
                ]
        );



        while ($arRes = $dbList->fetch()) {

            $date_timestamp = $arRes["UF_DATE"]->getTimestamp();
            $date = $arRes["UF_DATE"]->toString();

            if (!isset($data['COUNT']['BY_DATES'][$date])) {
                $data['COUNT']['BY_DATES'][$date] = [
                    "SHOWS" => $arRes["UF_COUNTER"],
                    "QUANTITY_BOOK" => 0
                ];
                $unix = MakeTimeStamp($date);
                $data["DATES_FORMATES"][$date] = [
                    "UNIX" => $date_timestamp,
                    "d_m" => date('d.m', $date_timestamp)
                ];
            } else {

                $data['COUNT']['BY_DATES'][$date]["SHOWS"] += $arRes["UF_COUNTER"];
                $data['COUNT']['BY_DATES'][$date]["QUANTITY_BOOK"] = 0;
                $data['COUNT']["TOTAL"]["SHOWS"] += $arRes["UF_COUNTER"];
            }

            if ($date_timestamp <= $todayEnd && $date_timestamp >= $todayBegin) {
                $arResult["STATISTICS"]['SHOWS']["TODAY"][$arRes["UF_PAGE_ID"]] += $arRes["UF_COUNTER"];
                $arResult["STATISTICS"]['SHOWS']["WEEK"][$arRes["UF_PAGE_ID"]] += $arRes["UF_COUNTER"];
                $arResult["STATISTICS"]['SHOWS']["MONTH"][$arRes["UF_PAGE_ID"]] += $arRes["UF_COUNTER"];
                continue;
            }

            if ($date_timestamp <= $todayEnd && $date_timestamp >= $weekBegin) {
                $arResult["STATISTICS"]['SHOWS']["WEEK"][$arRes["UF_PAGE_ID"]] += $arRes["UF_COUNTER"];
                $arResult["STATISTICS"]['SHOWS']["MONTH"][$arRes["UF_PAGE_ID"]] += $arRes["UF_COUNTER"];
                continue;
            }

            if ($date_timestamp <= $todayEnd && $date_timestamp >= $monthBegin) {
                $arResult["STATISTICS"]['SHOWS']["MONTH"][$arRes["UF_PAGE_ID"]] += $arRes["UF_COUNTER"];
            }
        }

        $listOfConditions = array();

        // костыль для того, чтобы конверсия и статистика считалась
        // с 03.06.2019 (дата установки нового модуля для ведения истории и статистики)
        $statistic_start = "03.06.2019";
        $statistic_start_timestamp = MakeTimeStamp($statistic_start);
        $df = date('d.m.Y', $monthBegin);
        $dt = date('d.m.Y', $todayEnd);
        if ($statistic_start_timestamp > $monthBegin) {
            $df = $statistic_start;
        }
        if ($statistic_start_timestamp > $todayEnd) {
            $dt = $statistic_start;
        }
        //

        $serviceDataStore = new \travelsoft\booking\datastores\ServicesDataStore(array(
            'filter' => array('UF_IBLOCK_ELEMENT_ID' => \array_values(\array_column($arResult["ITEMS"], "ID"))),
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

                        if (!isset($data['COUNT']['BY_DATES'][$date])) {
                            $data['COUNT']['BY_DATES'][$date] = [
                                "SHOWS" => 0,
                                "QUANTITY_BOOK" => $quantity
                            ];
                            $unix = MakeTimeStamp($date);
                            $data["DATES_FORMATES"][$date] = [
                                "UNIX" => $unix,
                                "d_m" => date('d.m', $unix),
                            ];
                        } else {
                            $data['COUNT']['BY_DATES'][$date]["QUANTITY_BOOK"] += $quantity;
                        }

                        $data['COUNT']["TOTAL"]["QUANTITY_BOOK"] += $quantity;

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
        
        ob_start();
        ?>
        <div calss="total-shows-block">
            Просмотры Вашей страницы<br>
            <?= intval($data['COUNT']['TOTAL']['SHOWS']) ?>
        </div>
        <div calss="total-booking-block">
            Бронирования<br>
            <?= intval($data['COUNT']['TOTAL']['QUANTITY_BOOK']) ?>
        </div>
        <div class="arrow-block">&rarr;</div>
        <div calss="conversion-block">
            Конверсия<br>
            <?= ($data['COUNT']['TOTAL']['SHOWS'] ? round($data['COUNT']['TOTAL']['QUANTITY_BOOK'] / $data['COUNT']['TOTAL']['SHOWS'] * 100, 2) : 0) ?>%
        </div>
        <?
        $response["detail_info"] = str_replace("\r\n", "", ob_get_clean());

        ob_start();
        ?>
        <table class="table table-bordered">

            <thead>
                <tr>
                    <th rowspan="2">ID</th>
                    <th rowspan="2">Название</th>
                    <th rowspan="2"></th>
                    <th colspan="2">За сегодня</th>
                    <th colspan="2">За неделю</th>
                    <th colspan="2">За месяц</th>
                </tr>
                <tr>
                    <th>Кол-во просмотров</th>
                    <th>Кол-во заказов</th>
                    <th>Кол-во просмотров</th>
                    <th>Кол-во заказов</th>
                    <th>Кол-во просмотров</th>
                    <th>Кол-во заказов</th>
                </tr>
            </thead>
            <tbody>
                <? foreach ($arResult["ITEMS"] as $arItem): ?>
                    <tr>
                        <td><?= $arItem["ID"] ?></td>
                        <td><a href="./detail.php?ID=<?= $arItem["ID"] ?>"><?= $arItem["NAME"] ?></a></td>
                        <td><a href="./detail.php?ID=<?= $arItem["ID"] ?>">Подробнее</a></td>
                        <td><?= intVal($arResult["STATISTICS"]['SHOWS']["TODAY"][$arItem["ID"]]) ?></td>
                        <td><?= intVal($arResult["STATISTICS"]['QUANTITY_BOOK']["TODAY"][$arItem["ID"]]) ?></td>
                        <td><?= intVal($arResult["STATISTICS"]['SHOWS']["WEEK"][$arItem["ID"]]) ?></td>
                        <td><?= intVal($arResult["STATISTICS"]['QUANTITY_BOOK']["WEEK"][$arItem["ID"]]) ?></td>
                        <td><?= intVal($arResult["STATISTICS"]['SHOWS']["MONTH"][$arItem["ID"]]) ?></td>
                        <td><?= intVal($arResult["STATISTICS"]['QUANTITY_BOOK']["MONTH"][$arItem["ID"]]) ?></td>
                    </tr>
                <? endforeach; ?>
            </tbody>
        </table> 
        <?
        $response["content"] = str_replace("\r\n", "", ob_get_clean());
        $response["error"] = false;
        $response["data"] = $data;
    }
}
\travelsoft\booking\Utils::sendJsonResponse(json_encode($response));

