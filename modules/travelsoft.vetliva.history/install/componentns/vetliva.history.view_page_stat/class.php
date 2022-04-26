<?php

define('VUE_DEBUG', true);

/**
 * Класс TravelsoftVetlivaHistoryPageViewStatistic
 * Класс компонента статистики просмотра страниц
 * @author dimabresky
 * @copyright (c) 2019, travelsoft
 */
class TravelsoftVetlivaHistoryPageViewStatistic extends CBitrixComponent {

    /**
     * Даты для фильтрации
     * @return array
     */
    public function filterDates() {

        if ($_REQUEST['HISTORY_VP_STAT']['DATE_FROM'] && $_REQUEST['HISTORY_VP_STAT']['DATE_TO']) {
            $this->arResult["DATE_FROM"] = $_REQUEST['HISTORY_VP_STAT']['DATE_FROM'];
            $this->arResult["DATE_TO"] = $_REQUEST['HISTORY_VP_STAT']['DATE_TO'];
        } else {
            $this->_defaultDates();
        }

        return [date("Y-m-d", strtotime($this->arResult["DATE_FROM"])), date("Y-m-d", strtotime($this->arResult["DATE_TO"]))];
    }

    /**
     * Дефолтные даты для фильтрации
     * @return array
     */
    public function _defaultDates() {
        $month = 86400 * 31;
        $date_from = time() - $month;
        $date_to = time() + 86400;

        $this->arResult["DATE_FROM"] = date("d.m.Y", $date_from);
        $this->arResult["DATE_TO"] = date("d.m.Y", $date_to);

        return [date("Y-m-d", $date_from), date("Y-m-d", $date_to)];
    }

    public function getFilter() {

        return [
            "where" => [
                "UF_PAGE_ID" => $this->arParams["ID"],
                "><UF_DATE" => $this->filterDates()
            ]
        ];
    }

    public function getResult() {

        $dbList = travelsoft\vetliva\DBHistory::getInstance()->getPageViews($this->getFilter());
        while ($arResult = $dbList->fetch()) {

            $date = $arResult["UF_DATE"]->toString();
            $unix = $arResult["UF_DATE"]->getTimestamp();
            $this->arResult["DATES_FORMATES"][$date] = [
                "UNIX" => $unix,
                "d_m" => date('d.m', $unix),
            ];
            $this->arResult["ITEMS"][$date][] = $arResult;
            $this->arResult['COUNT']['BY_DATES'][$date]["SHOWS"] = $arResult["UF_COUNTER"];
            $this->arResult['COUNT']['BY_DATES'][$date]["QUANTITY_BOOK"] = 0;
            $this->arResult['COUNT']["TOTAL"]["SHOWS"] += $arResult["UF_COUNTER"];
        }

        $this->arResult['COUNT']["TOTAL"]["QUANTITY_BOOK"] = 0;

        if (Bitrix\Main\Loader::includeModule("travelsoft.vetliva.history")) {
            $sds = new \travelsoft\booking\datastores\ServicesDataStore(array(
                'filter' => array(
                    'UF_IBLOCK_ELEMENT_ID' => $this->arParams["ID"]
                ),
                'select' => array('ID', 'UF_IBLOCK_ELEMENT_ID')
            ));

            $arDataStore = $sds->fetch();

            // костыль для того, чтобы конверсия и статистика считалась
            // с 03.06.2019 (дата установки нового модуля для ведения истории и статистики)
            $date_from = $this->arResult['DATE_FROM'];
            $date_to = $this->arResult['DATE_TO'];
            $statistic_start = "03.06.2019";
            $statistic_start_timestamp = MakeTimeStamp($statistic_start);
            if (MakeTimeStamp($date_from) < $statistic_start_timestamp) {
                $date_from = $statistic_start;
            }
            if (MakeTimeStamp($date_to) < $statistic_start_timestamp) {
                $date_to = $statistic_start;
            }
            //

            if (!empty($arDataStore)) {
                foreach ($arDataStore as $datastore) {
                    $list_of_conditions[] = [
                        'dateFrom' => $date_from,
                        'dateTo' => $date_to,
                        'serviceId' => $datastore["ID"]
                    ];
                }

                $arResponse = \Bitrix\Main\Web\Json::decode(travelsoft\booking\Gateway::getServicesStatisticsByPartner(array(
                                    'url' => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                                    'params' => array(
                                        'listOfConditions' => $list_of_conditions
                            )))
                );

                foreach ($arResponse['result'] as $arResponseResult) {
                    foreach ($arResponseResult['quantity_on_every_day'] as $date => $quantity_book) {
                        if (!isset($this->arResult['COUNT']['BY_DATES'][$date])) {
                            $this->arResult['COUNT']['BY_DATES'][$date] = [
                                "SHOWS" => 0,
                                "QUANTITY_BOOK" => $quantity_book
                            ];
                            $unix = MakeTimeStamp($date);
                            $this->arResult["DATES_FORMATES"][$date] = [
                                "UNIX" => $unix,
                                "d_m" => date('d.m', $unix),
                            ];
                        } else {
                            $this->arResult['COUNT']['BY_DATES'][$date]["QUANTITY_BOOK"] = $quantity_book;
                        }

                        $this->arResult['COUNT']["TOTAL"]["QUANTITY_BOOK"] += $quantity_book;
                    }
                }
            }

            $this->arResult['CONVERSION'] = 0;
            if ($this->arResult["COUNT"]["TOTAL"]["SHOWS"] > 0) {
                $this->arResult['CONVERSION'] = round($this->arResult['COUNT']['TOTAL']['QUANTITY_BOOK'] / $this->arResult['COUNT']['TOTAL']['SHOWS'] * 100, 2);
            }
        }

        return $this->arResult;
    }

    public function iblockElementCode() {
        Bitrix\Main\Loader::includeModule('iblock');

        $iblock_element = \Bitrix\Iblock\ElementTable::getList([
                    'filter' => ['ID' => $this->arParams['ID']],
                    'select' => ['ID', 'CODE']
                ])->fetch();

        return (string) $iblock_element['CODE'];
    }

    /**
     * @param callable $callback
     * @return array
     */
    public function fromCache(callable $callback, string $cache_part_str): array {

        $stat_result = [];
        $cache = Bitrix\Main\Data\Cache::createInstance();
        list($date_from, $date_to) = $this->filterDates();
        $cache_id = "yandex_{$cache_part_str}_statistic_for_" . serialize([$this->arParams['ID'], $date_from, $date_to]);
        $cache_dir = "/travesloft.vetliva.history/yandex-statistics/_{$cache_part_str}";
        if ($cache->initCache(1800, $cache_id, $cache_dir)) {
            $stat_result = $cache->getVars();
        } elseif ($cache->startDataCache()) {

            $code = $this->iblockElementCode();
            $stat = $callback(new \travelsoft\vetliva\YandexMetrika([
                        "date1" => $date_from,
                        "date2" => $date_to,
                        "url" => $code
            ]));
            if (!empty($stat)) {
                $stat_result = $stat;
                $cache->endDataCache($stat_result);
            } else {
                $cache->abortDataCache();
            }
        }

        return $stat_result;
    }

    /**
     * @return array
     */
    public function conversionStatistic(): array {
        return $this->getResult();
    }

    /**
     * @return array
     */
    public function ageStatistic(): array {

        return $this->fromCache(function ($yandex_metrics) {
                    $stat = $yandex_metrics->getAgeStatistic();
                    
                    if (!isset($stat['error']) && !empty($stat['data'])) {
                        $result_stat = [];
                        $result_stat['<18'] = number_format($stat['data'][0]['metrics'][0], 2);
                        $result_stat['18-24'] = number_format($stat['data'][0]['metrics'][1], 2);
                        $result_stat['25-34'] = number_format($stat['data'][0]['metrics'][2], 2);
                        $result_stat['35-45'] = number_format($stat['data'][0]['metrics'][3], 2);
                        $result_stat['45<'] = number_format($stat['data'][0]['metrics'][4], 2);
                        return $result_stat;
                    }
                    return [];
                }, "age");
    }

    /**
     * @return array
     */
    public function maleStatistic(): array {

        return $this->fromCache(function ($yandex_metrics) {
                    $stat = $yandex_metrics->getMaleStatistic();
                    if (!isset($stat['error']) && !empty($stat['data'])) {
                        $result_stat = [
                            "man" => $stat['data'][0]['metrics'][0],
                            "women" => $stat['data'][0]['metrics'][1]
                        ];
                        return $result_stat;
                    }
                    return [];
                }, "male");
    }

    /**
     * @return array
     */
    public function geographyStatistic(): array {

        return $this->fromCache(function ($yandex_metrics) {

                    $stat = $yandex_metrics->getGeographyStatistic();
                    if (!isset($stat['error']) && !empty($stat['data'])) {
                        $result_stat = [];
                        $total = 0;

                        foreach ($stat['data'] as $data_stat) {
                            $result_stat[$data_stat['dimensions'][0]['name']] = [
                                "percent" => 0,
                                "count" => intval($data_stat['metrics'][0])
                            ];
                            $total += intval($data_stat['metrics'][0]);
                        }
                        foreach ($result_stat as &$data) {
                            $data['percent'] += round($data['count'] / $total * 100, 2);
                        }
                        return $result_stat;
                    }
                    return [];
                }, "geography");
    }

    /**
     * @return array
     */
    public function devicesStatistic(): array {

        return $this->fromCache(function ($yandex_metrics) {
                    $stat = $yandex_metrics->getDevicesStatistic();
                    if (!isset($stat['error']) && !empty($stat['data'])) {
                        $result_stat = [];
                        $total = 0;

                        foreach ($stat['data'] as $data_stat) {
                            $result_stat[$data_stat['dimensions'][0]['name']] = [
                                "percent" => 0,
                                "count" => intval($data_stat['metrics'][0])
                            ];
                            $total += intval($data_stat['metrics'][0]);
                        }
                        foreach ($result_stat as &$data) {
                            $data['percent'] += round($data['count'] / $total * 100, 2);
                        }
                        return $result_stat;
                    }
                    return [];
                }, "devices");
    }

    public function executeComponent() {

        try {

            if (!Bitrix\Main\Loader::includeModule("travelsoft.vetliva.history")) {
                throw new Exception("history page view stat.: Модуль travelsoft.vetliva.history не найден");
            }

            $this->arParams["ID"] = intVal($this->arParams["ID"]);
            if ($this->arParams["ID"] <= 0) {
                throw new Exception("history page view stat.: Укажите ID страницы (ID > 0)");
            }

            if (strlen($_REQUEST["HISTORY_VP_STAT"]['RESET']) > 0) {
                LocalRedirect($GLOBALS["APPLICATION"]->GetCurPageParam("", array("HISTORY_VP_STAT")));
            }

            $this->_defaultDates();

            $this->IncludeComponentTemplate();

            if ($this->arParams["BOOTSTRAP"] == "Y") {
                \Bitrix\Main\Page\Asset::getInstance()->addString('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">', true);
                \Bitrix\Main\Page\Asset::getInstance()->addString('<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">', true);
            }

            if ($this->arParams["CHARTS_JS"] == "Y") {
                \Bitrix\Main\Page\Asset::getInstance()->addString('<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/4.8.0/d3.min.js"></script>');
                \Bitrix\Main\Page\Asset::getInstance()->addString('<script src="https://cdnjs.cloudflare.com/ajax/libs/dimple/2.3.0/dimple.latest.min.js"></script>');
            }

            \Bitrix\Main\UI\Extension::load("ui.vue");
            \Bitrix\Main\UI\Extension::load("ui.vue.vuex");
            CJSCore::Init(array('date', 'ajax'));
        } catch (\Exception $e) {
            ShowError($e->getMessage());
        }
    }

}
