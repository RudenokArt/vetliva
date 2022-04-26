<?php

/**
 * добавление редактирование цен
 */
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

class TravelsoftAddEditPrices extends CBitrixComponent {

    /**
     * Объект запроса
     * @var Bitrix\Main\HttpRequest
     */
    protected $request = null;

    /**
     * Настройки отображения диапозона дат
     * @var array
     */
    protected $dateRangeSettings = null;

    /**
     * Последняя возникшая ошибка
     * @var string
     */
    protected $errorsLog = null;

    /**
     * Log обработки запроса
     * @var array
     */
    protected $proccessingLog = null;

    /**
     * @var boolean 
     */
    protected $isAdmin = false;

    /**
     * @var integert
     */
    protected $userId = null;

    /**
     * @var int
     */
    protected $cache_time = 36000;

    /**
     * @var string
     */
    protected $cache_root_dir = "/travelsoft/partners";

    /**
     * Проверка групповых прав пользователя
     * @global CUser $USER
     * @return boolean
     * @throws Exception
     */
    public function checkUserGroupPermission() {

        global $USER;
        
        if (!$USER->IsAuthorized()) {
            throw new Exception("Access denided. You must be authorized");
        }

        $this->userId = $this->arParams['SUPER_USER_EDIT'] === 'Y' && $this->arParams['PROVIDER_ID'] > 0 ? $this->arParams['PROVIDER_ID'] : $USER->GetID();
        
        if ($this->arParams['SUPER_USER_EDIT'] !== 'Y' && in_array(\Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "cut_provider_group_id"), $GLOBALS["USER"]->GetUserGroupArray())) {

            $arUser = CUser::GetList(($by = "personal_country"), ($order = "desc"), array("ID" => $this->userId), array("FIELDS" => array("ID"), "SELECT" => array("UF_PROVIDER_ID")))->Fetch();

            if (!empty($arUser['UF_PROVIDER_ID'])) {
                $this->userId = $arUser['UF_PROVIDER_ID'];
            }
        }
        
        $this->arParams['ALLOW_USER_GROUPS'] = (array) $this->arParams['ALLOW_USER_GROUPS'];

        $arUserGroupsID = $USER->GetUserGroupArray();

        $cnt = count($this->arParams['ALLOW_USER_GROUPS']);

        for ($i = 0; $i < $cnt; $i++) {
            if (in_array($this->arParams['ALLOW_USER_GROUPS'][$i], $arUserGroupsID))
                return true;
        }

        throw new Exception("Access for add/edit denided");
    }

    /**
     * @global $CACHE_MANAGER
     * @param string $tag
     * @param string $cache_dir
     */
    protected function _setTagCache($tag, $cache_dir) {
        if (defined('BX_COMP_MANAGED_CACHE')) {
            global $CACHE_MANAGER;
            $CACHE_MANAGER->StartTagCache($cache_dir);
            $CACHE_MANAGER->RegisterTag($tag);
            $CACHE_MANAGER->EndTagCache();
        }
    }

    /**
     * @global $CACHE_MANAGER
     * @param string $tagName
     */
    protected function _clearTagCache($tagName) {
        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag($tagName);
    }

    /**
     * Услуги поставщика по фильтру
     * @param array $arFilter
     * @return mixed (null or array)
     * @throws Exception
     */
    protected function getListServicesInfo($arFilter) {

        Bitrix\Main\Loader::includeModule('iblock');

        $сache = Bitrix\Main\Data\Cache::createInstance();
        
        $cache_dir = $this->cache_root_dir . "/services/" . $this->__name . "/" . $this->userId;

        if ($сache->initCache(0, "getListServicesInfo_" . serialize($arFilter), $cache_dir)) {
            $arResult = $сache->getVars();
        } elseif ($сache->startDataCache()) {

            $arr_services_id = array();

            foreach ($this->getListRates(array("UF_USER_ID" => $this->userId, "UF_FOR_PLACE" => 0)) as $arr_rate) {

                $arr_services_id = array_merge($arr_services_id, (array) $arr_rate["UF_SERVICES_ID"]);
            }

            $arr_services_id = array_unique($arr_services_id);

            if (empty($arr_services_id)) {
                return null;
            }

            $dbResult = $this->__getListDBResult(
                    $this->arParams['SERVICES_BOOKING_HL_BLOCK'], array("ID" => $arr_services_id)
            );

            while ($el = $dbResult->fetch()) {

                $arResult['servicesInfo']['services'][$el['ID']] = $el;

                if ($el['UF_IBLOCK_ELEMENT_ID'] > 0) {
                    $arResult['servicesInfo']['links'][$el['UF_IBLOCK_ELEMENT_ID']][] = $el["ID"];
                }
            }

            if (!$arResult) {
                throw new Exception("Services not found");
            }

            if ($arResult['servicesInfo']['links']) {

                $dbResult = CIBlockElement::GetList(
                                array("NAME" => "ASC"), array('ID' => array_keys($arResult['servicesInfo']['links']), "PROPERTY_IS_EXCURSION_TOUR_VALUE" => "Y"), false, false, array("ID", "NAME")
                );

                while ($result = $dbResult->Fetch()) {
                    $arResult['servicesInfo']['ibElementsName'][$result['ID']] = $result['NAME'];
                }
            }

            if (empty($arResult)) {
                $сache->abortDataCache();
            } else {
                
                $this->_setTagCache("highloadblock_" . $this->arParams['SERVICES_BOOKING_HL_BLOCK'], $cache_dir);
                
                $сache->endDataCache($arResult);
            }
        }

        return $arResult;
    }

    /**
     * Получаем квоты по фильтру
     * @param array $arFilter
     * @return mixed (null or array)
     */
    protected function getListQuotes($arFilter) {

        $сache = Bitrix\Main\Data\Cache::createInstance();

        $cache_dir = $this->cache_root_dir . "/quotas/" . $this->__name . "/" . $this->userId;
        
        $arResult = null;

        if ($сache->initCache(0, serialize($arFilter), $cache_dir)) {

            $arResult = $сache->getVars();
        } elseif ($сache->startDataCache()) {
            $dbRes = $this->__getListDBResult(
                    $this->arParams['QUOTAS_BOOKING_HL_BLOCK'], $arFilter
            );

            while ($res = $dbRes->fetch()) {
                $arResult[$res["UF_RATE_ID"]][$res['UF_DATE']] = $res;
            }

            if (empty($arResult)) {
                $сache->abortDataCache();
            } else {

                $this->_setTagCache("highloadblock_" . $this->arParams["QUOTAS_BOOKING_HL_BLOCK"] . "_" . $this->arParams["ROW_ID"], $cache_dir);

                $сache->endDataCache($arResult);
            }
        }

        return $arResult;
    }

    /**
     * Получаем цены (по фильтру)
     * @param array $arFilter
     * @return array
     */
    protected function getListPrices($arFilter) {

        $сache = Bitrix\Main\Data\Cache::createInstance();

        $cache_dir = $this->cache_root_dir . "/prices/" . $this->__name . "/" . $this->userId;

        $arResult = null;

        if ($сache->initCache($this->cache_time, serialize($arFilter), $cache_dir)) {

            $arResult = $сache->getVars();
        } elseif ($сache->startDataCache()) {

            $dbRes = $this->__getListDBResult(
                    $this->arParams['PRICES_BOOKING_HL_BLOCK'], $arFilter
            );

            while ($res = $dbRes->fetch()) {
                $arResult[$res['UF_DATE']][$res["UF_PTPR_ID"]] = $res;
            }

            if (empty($arResult)) {
                $сache->abortDataCache();
            } else {

                $this->_setTagCache("highloadblock_" . $this->arParams["PRICES_BOOKING_HL_BLOCK"] . "_" . $this->arParams["ROW_ID"], $cache_dir);

                $сache->endDataCache($arResult);
            }
        }

        return $arResult;
    }

    
    /**
     * Получаем тарифы по фильтру
     * @param array $arFilter
     * @return mixed (null or array)
     */
    protected function getListRates($arFilter) {

        $сache = Bitrix\Main\Data\Cache::createInstance();

        $cache_dir = $this->cache_root_dir . "/rates/" . $this->__name . "/" . $this->userId;

        $arResult = null;

        if ($сache->initCache($this->cache_time, serialize($arFilter), $cache_dir)) {

            $arResult = $сache->getVars();
        } elseif ($сache->startDataCache()) {
            $dbRes = $this->__getListDBResult(
                    $this->arParams['RATES_BOOKING_HL_BLOCK'], $arFilter
            );

            while ($res = $dbRes->fetch()) {
                $arResult[$res['ID']] = $res;
            }

            if (empty($arResult)) {
                $сache->abortDataCache();
            } else {
                
                $this->_setTagCache("highloadblock_" . $this->arParams["RATES_BOOKING_HL_BLOCK"] . "_" . $this->arParams["ROW_ID"], $cache_dir);
                $this->_setTagCache("highloadblock_" . $this->arParams["RATES_BOOKING_HL_BLOCK"], $cache_dir);

                $сache->endDataCache($arResult);
            }
        }

        return $arResult;
    }

    /**
     * Получаем типы цен + тариф по фильтру
     * @param array $arFilter
     * @return mixed (null or array)
     */
    protected function getListPTRates($arFilter) {

        $сache = Bitrix\Main\Data\Cache::createInstance();

        $cache_dir = $this->cache_root_dir . "/ptrates/" . $this->__name . "/" . $this->userId;

        $arResult = null;

        if ($сache->initCache($this->cache_time, serialize($arFilter), $cache_dir)) {

            $arResult = $сache->getVars();
        } elseif ($сache->startDataCache()) {
            $dbRes = $this->__getListDBResult(
                    $this->arParams['PRICE_TYPES_PLUSE_RATES_BOOKING_HL_BLOCK'], $arFilter
            );

            while ($res = $dbRes->fetch()) {
                $arResult[0][] = $res['ID'];
                $arResult[1][] = $res['UF_RATE_ID'];
                $arResult[2][] = $res['UF_RATE_CATEGORY_ID'];
            }

            if (empty($arResult)) {
                $сache->abortDataCache();
            } else {

                $this->_setTagCache("highloadblock_" . $this->arParams['PRICE_TYPES_PLUSE_RATES_BOOKING_HL_BLOCK'], $cache_dir);

                $сache->endDataCache($arResult);
            }
        }

        return $arResult;
    }

    /**
     * Получаем типы цен по фильтру
     * @param array $arFilter
     * @return mixed (null or array)
     */
    protected function getListPriceTypes($arFilter) {

        $сache = Bitrix\Main\Data\Cache::createInstance();

        $cache_dir = $this->cache_root_dir . "/price_types/" . $this->__name . "/" . $this->userId;

        $arResult = null;

        if ($сache->initCache($this->cache_time, serialize($arFilter), $cache_dir)) {

            $arResult = $сache->getVars();
        } elseif ($сache->startDataCache()) {
            $dbRes = $this->__getListDBResult(
                    $this->arParams['PRICE_TYPE_BOOKING_HL_BLOCK'], $arFilter
            );

            while ($res = $dbRes->fetch()) {
                $arResult[$res['ID']] = $res;
            }

            if (empty($arResult)) {
                $сache->abortDataCache();
            } else {

                $this->_setTagCache("highloadblock_" . $this->arParams['PRICE_TYPE_BOOKING_HL_BLOCK'], $cache_dir);

                $сache->endDataCache($arResult);
            }
        }

        return $arResult;
    }

    /**
     * Формат для отображения месяцев в select
     * @param type $unixDate
     * @return type
     */
    protected function getMonthFormat($unixDate) {
        return FormatDate("f", $unixDate) . "(" . date("Y", $unixDate) . ")";
    }

    /**
     * Формат для отображения дней в table
     * @param type $unixDate
     * @return type
     */
    protected function getDaysFormat($unixDate) {
        return date("d", $unixDate) . " " . FormatDate("M", $unixDate) . ". " . FormatDate("D", $unixDate) . ".";
    }

    /**
     * Массив интервала дат в определённом формате
     * @param integer $unixStart
     * @param mixed $interval
     * @param mixed $step
     * @param string $format
     * @return array
     */
    protected function getFormattedIntervalDate($unixStart, $interval = null, $step = null, $format = null) {

        $arResult = array();

        $H = date("H", $unixStart);
        $i = date("i", $unixStart);
        $s = date("s", $unixStart);
        $m = date("m", $unixStart);
        $d = date("d", $unixStart);
        $Y = date("Y", $unixStart);

        if ($interval == "month" || $interval == "1 month" || $interval == null) {
            $mkTimeInterval = mktime($H, $i, $s, $m + 1, $d - 1, $Y);
        } elseif ($interval == "year") {
            $mkTimeInterval = mktime($H, $i, $s, $m - 1, $d, $Y + 2);
        } elseif (is_numeric($interval) && $interval > 0) {
            $mkTimeInterval = $interval;
        }

        if ($format === "getDaysFormat") {
            $callMethod = "getDaysFormat";
        } else {
            $callMethod = "getMonthFormat";
        }

        if ($step == "day" || $step == "1 day" || $step == null) {
            $step = 86400;
        }

        while ($mkTimeInterval >= $unixStart) {

            $arResult[] = array(
                "unixDate" => $unixStart,
                "title" => call_user_func_array(array($this, $callMethod), array($unixStart))
            );

            if ($step == "month" || $step == "1 month") {
                $unixStart = mktime(
                        date("H", $unixStart), date("i", $unixStart), date("s", $unixStart), date("m", $unixStart) + 1, date("d", $unixStart), date("Y", $unixStart)
                );
            } else {
                $unixStart += $step;
            }
        }

        return $arResult;
    }

    /**
     * Информация по месяцу
     * @return array
     */
    protected function getDateArray() {

        $now = time();

        $arResult['monthsArray'] = $this->getFormattedIntervalDate(
                mktime(0, 0, 0, date("n", $now), 1, date('Y', $now)), "year", "month"
        );

        $arResult['_get'] = $this->request->get('getDate');

        $unixDate = mktime(0, 0, 0, date("m", $now), date("d", $now), date('Y', $now));

        if ($arResult['_get'] > $now) {
            $unixDate = mktime(0, 0, 0, date("m", $arResult['_get']), 1, date('Y', $arResult['_get']));
        }

        $arResult['daysArray'] = $this->getFormattedIntervalDate($unixDate, "month", "day", "getDaysFormat");

        foreach ($arResult['daysArray'] as $ar) {
            $arResult['unixDaysArray'][] = $ar['unixDate'];
        }

        return $arResult;
    }
    
    protected function getDateRangeArray() {
        $now = time();

        $arResult['monthsArray'] = $this->getFormattedIntervalDate(
                mktime(0, 0, 0, date("n", $now), 1, date('Y', $now)), "year", "month"
        );
        $rangedates = $this->request->get('getDateRange');
        if ($rangedates) {
            $arDate = explode($this->dateRangeSettings['separator'], $rangedates);
            $unixStartDate = MakeTimeStamp($arDate[0], $this->dateRangeSettings['format']);
            $unixEndDate = MakeTimeStamp($arDate[1], $this->dateRangeSettings['format']);

            $arResult['daysArray'] = $this->getFormattedIntervalDate($unixStartDate, $unixEndDate, "day", "getDaysFormat");
            foreach ($arResult['daysArray'] as $ar) {
                $arResult['unixDaysArray'][] = $ar['unixDate'];
            }
            $arResult["dateStartFormat"] = $unixStartDate;
            $arResult["dateEndFormat"] = $unixEndDate;
            return $arResult;
        }
    }

    /**
     * Настройки отображения диапазона дат
     * @return array
     */
    protected function getDateRangeSettingsArray() {
        $now = time();
        $day = date("d", $now);
        $month = date("m", $now);
        $year = date('Y', $now);
        return array(
            "locale" => LANGUAGE_ID,
            "format" => "DD/MM/YYYY",
            "separator" => " - ",
            "minUnixDate" => mktime(0, 0, 0, $month, $day, $year),
            "maxUnixDate" => mktime(0, 0, 0, $month, $day, $year + 2)
        );
    }

    /**
     * Отсылает ответ, если ajax запрос
     * @param array $result
     */
    protected function isAjax($result) {
        // is ajax request
        if ($this->arParams['IS_AJAX'] == "Y") {

            echo Bitrix\Main\Web\Json::encode($result);

            die();
        }
    }

    /**
     * @param array $arDaysNumber
     * @param array $arDays
     * @return array
     */
    protected function proccessingDatePipe(Array $arDaysNumber, Array $arDays) {

        $arResult = null;

        if (!empty($arDaysNumber)) {
            foreach ($arDays as $arDay) {

                $dayNum = date("N", $arDay['unixDate']);

                if (in_array($dayNum, $arDaysNumber)) {
                    $arResult[] = $arDay;
                }
            }
        }

        return $arResult ? $arResult : $arDays;
    }

    /**
     * Подготовка данных для обработки
     * @param bool $justReturn
     * @param array $arDays
     * @param mixed $value
     * @param bool $flag
     * @param string $key
     * @return array
     */
    protected function proccessingDataPipe($justReturn, $arDays, $value, $flag = false, $key = "") {

        if ($justReturn)
            return $value;

        $arRes = array();

        if (!$flag) {

            if ($key === "quotes" || $key === "stopSales"|| $key === "releasePeriod") {

                foreach ($value as $rate_id => $v) {
                    foreach ($arDays as $arDay) {
                        $arRes[$rate_id][$arDay['unixDate']] = $v;
                    }
                }
            } else {
                foreach ($arDays as $arDay) {
                    $arRes[$arDay['unixDate']] = $value;
                }
            }
        } else {
            foreach ($arDays as $arDay) {
                $arRes[$arDay['unixDate']][$value[0]] = $value[1];
            }
        }

        return $arRes;
    }

    /**
     * Возвращает поле Highload таблицы взависимости от условий
     * @param string $key
     * @return string
     */
    protected function getTableField($key) {
        $field = "UF_NO_ARRIVALS";

        if ($key == "noDepartures") {
            $field = "UF_NO_DEPARTURES";
        } elseif ($key == "lifePeriod") {
            $field = "UF_LIFE_PERIOD";
        }

        return $field;
    }

    /** Processing post request * */
    protected function proccessingPost() {

        $isPost = $this->request->isPost();

        $uniqid = $this->request->getPost('uniqid');

        if ($isPost && check_bitrix_sessid() && $uniqid == $this->arParams['UNIQUE_ID']) {

            $_post = $this->request->getPost('mainForm');

            $justReturn = true;

            if (!empty($_post['massEdit'])) {

                $_post = $_post['massEdit'];

                if ($_post["isRange"] === "Y") {

                    if (!$_post['dateRange']) {
                        throw new Exception("Укажите период дат для массового редактирования");
                    }

                    // parse date range
                    $arDate = explode($this->dateRangeSettings['separator'], $_post['dateRange']);

                    $unixStartDate = MakeTimeStamp($arDate[0], $this->dateRangeSettings['format']);
                    $unixEndDate = MakeTimeStamp($arDate[1], $this->dateRangeSettings['format']);

                    if (!($this->dateRangeSettings['minUnixDate'] <= $unixStartDate &&
                            $this->dateRangeSettings['maxUnixDate'] >= $unixEndDate &&
                            $unixEndDate >= $unixStartDate)) {
                        throw new Exception("Указан неправильный период дат для массового редактирования");
                    }

                    $arDays = $this->proccessingDatePipe($_post['dayNumber'] ? (array) $_post['dayNumber'] : array(), $this->getFormattedIntervalDate($unixStartDate, $unixEndDate, "day", "getDaysFormat"));

                    unset($_post['dateRange']);
                } else {

                    if (empty($_post["singleDates"])) {
                        throw new Exception("Укажите конкретные даты для массового редактирования");
                    }

                    $arSingleUnixDates = array_values(array_unique(array_map(function ($date) {
                                        return MakeTimeStamp($date, $this->dateRangeSettings['format']);
                                    }, $_post["singleDates"])));

                    sort($arSingleUnixDates);

                    $arDays = [];
                    foreach ($arSingleUnixDates as $singleUnixDate) {
                        $arDays[] = array(
                            "unixDate" => $singleUnixDate,
                            "title" => $this->getDaysFormat($singleUnixDate)
                        );
                    }
                    
                    unset($_post['singleDates']);
                }

                $justReturn = false;
            }

            foreach ($_post as $key => $arValues) {

                if ($key == "quotes") {

                    $this->proccessingPostQuotes($this->proccessingDataPipe($justReturn, $arDays, $arValues, false, $key), $key);
                } elseif ($key == "releasePeriod") {
                    $this->proccessingPostReleasePeriod($this->proccessingDataPipe($justReturn, $arDays, $arValues, false, $key), $key);
                } elseif ($key == "stopSales") {

                    $this->proccessingPostStopSales($this->proccessingDataPipe($justReturn, $arDays, $arValues, false, $key), $key);
                } elseif ($key == "prices") {

                    $this->proccessingPostPrices($this->proccessingDataPipe($justReturn, $arDays, $arValues, true), $key);
                } elseif ($key == "discountpricesabs") {
                    $this->proccessingPostDiscountAbs($this->proccessingDataPipe($justReturn, $arDays, $arValues, true), $key);
                } elseif ($key == "discountpricespercent") {
                    $this->proccessingPostDiscountPercent($this->proccessingDataPipe($justReturn, $arDays, $arValues, true), $key);
                } elseif ($key == "noArrivals" || $key == "noDepartures" || $key == "lifePeriod") {

                    $this->proccessingPostLifePeriodNoArrivalsDepartures(
                            $this->proccessingDataPipe($justReturn, $arDays, $arValues, true), $key
                    );
                }
            }
        }
    }

    /**
     * Обработка запроса на квоту
     * @param array $arValues
     */
    protected function proccessingPostQuotes($arValues, $logKey) {

        $arRates = array_keys($arValues);

        $arUnixDates = [];
        foreach ($arValues as $rate_id => $arr) {
            $arUnixDates = array_merge($arUnixDates, array_keys($arr));
        }

        $arQuotas = $this->getListQuotes(array("UF_RATE_ID" => !empty($arRates) ? $arRates : -1, "UF_DATE" => !empty($arUnixDates) ? $arUnixDates : -1, "UF_SERVICE_ID" => $this->arParams['ROW_ID']));

        foreach ($arValues as $rate_id => $arr) {
            foreach ($arr as $unixDate => $value) {

                $arParams = null;

                if ($value > 0) {

                    if ($arQuotas[$rate_id][$unixDate]) {

                        if ($arQuotas[$rate_id][$unixDate]['UF_QUOTE'] != $value) {
                            $arParams = array(
                                array(
                                    $this->__getDataClass($this->arParams['QUOTAS_BOOKING_HL_BLOCK']),
                                    "update"
                                ),
                                array(
                                    $arQuotas[$rate_id][$unixDate]['ID'],
                                    array("UF_QUOTE" => $value)
                                )
                            );
                        }
                    } else {

                        $arParams = array(
                            array(
                                $this->__getDataClass($this->arParams['QUOTAS_BOOKING_HL_BLOCK']),
                                "add"
                            ),
                            array(
                                array(
                                    "UF_QUOTE" => $value,
                                    "UF_DATE" => $unixDate,
                                    "UF_RATE_ID" => $rate_id,
                                    "UF_SERVICE_ID" => $this->arParams['ROW_ID']
                                )
                            )
                        );
                    }
                } elseif ($arQuotas[$rate_id][$unixDate]) {

                    $arParams = array(
                        array(
                            $this->__getDataClass($this->arParams['QUOTAS_BOOKING_HL_BLOCK']),
                            "delete"
                        ),
                        array(
                            $arQuotas[$rate_id][$unixDate]['ID']
                        )
                    );
                }

                if ($arParams && $result = $this->__callORMBxMethod($arParams)) {

                    $toLog = array(
                        "unixDate" => $unixDate,
                        "rate_id" => $rate_id,
                        "value" => $value
                    );

                    // write to log
                    $this->proccessingLog[$logKey][] = $this->_completeLogByQoutas($arParams[0][1], $result, $toLog);
                }
            }
        }
        $this->_clearTagCache("highloadblock_" . $this->arParams["QUOTAS_BOOKING_HL_BLOCK"] . "_" . $this->arParams["ROW_ID"]);
    }


    /**
     * Обработка запроса на релиз период
     * @param array $arValues
     */
    protected function proccessingPostReleasePeriod($arValues, $logKey) {

        $arRates = array_keys($arValues);

        $arUnixDates = [];
        foreach ($arValues as $rate_id => $arr) {
            $arUnixDates = array_merge($arUnixDates, array_keys($arr));
        }

        $arQuotas = $this->getListQuotes(array("UF_RATE_ID" => !empty($arRates) ? $arRates : -1, "UF_DATE" => !empty($arUnixDates) ? $arUnixDates : -1, "UF_SERVICE_ID" => $this->arParams['ROW_ID']));

        foreach ($arValues as $rate_id => $arr) {
            foreach ($arr as $unixDate => $value) {

                $arParams = null;

                if ($value > 0) {

                    if ($arQuotas[$rate_id][$unixDate]) {

                        if ($arQuotas[$rate_id][$unixDate]['UF_RELEASE_PERIOD'] != $value) {
                            $arParams = array(
                                array(
                                    $this->__getDataClass($this->arParams['QUOTAS_BOOKING_HL_BLOCK']),
                                    "update"
                                ),
                                array(
                                    $arQuotas[$rate_id][$unixDate]['ID'],
                                    array("UF_RELEASE_PERIOD" => $value)
                                )
                            );
                        }
                    } else {

                        $arParams = array(
                            array(
                                $this->__getDataClass($this->arParams['QUOTAS_BOOKING_HL_BLOCK']),
                                "add"
                            ),
                            array(
                                array(
                                    "UF_RELEASE_PERIOD" => $value,
                                    "UF_DATE" => $unixDate,
                                    "UF_RATE_ID" => $rate_id,
                                    "UF_SERVICE_ID" => $this->arParams['ROW_ID']
                                )
                            )
                        );
                    }
                } elseif ($arQuotas[$rate_id][$unixDate]) {

                    $arParams = array(
                        array(
                            $this->__getDataClass($this->arParams['QUOTAS_BOOKING_HL_BLOCK']),
                            "update"
                        ),
                        array(
                            $arQuotas[$rate_id][$unixDate]['ID'],
                            array(
                                "UF_RELEASE_PERIOD" => $value,
                                "UF_DATE" => $unixDate,
                                "UF_RATE_ID" => $rate_id,
                                "UF_SERVICE_ID" => $this->arParams['ROW_ID']
                            )
                        )
                    );
                }

                if ($arParams && $result = $this->__callORMBxMethod($arParams)) {

                    $toLog = array(
                        "unixDate" => $unixDate,
                        "rate_id" => $rate_id,
                        "value" => $value
                    );

                    // write to log
                    $this->proccessingLog[$logKey][] = $this->_completeLogByQoutas($arParams[0][1], $result, $toLog);
                }
            }
        }
        $this->_clearTagCache("highloadblock_" . $this->arParams["QUOTAS_BOOKING_HL_BLOCK"] . "_" . $this->arParams["ROW_ID"]);
    }

    /**
     * Обработка запроса на stop sale
     * @param array $arValues
     */
    protected function proccessingPostStopSales($arValues, $logKey) {

        $arRates = array_keys($arValues);

        $arUnixDates = [];
        foreach ($arValues as $rate_id => $arr) {
            $arUnixDates = array_merge($arUnixDates, array_keys($arr));
        }

        $arQuotas = $this->getListQuotes(array("UF_RATE_ID" => !empty($arRates) ? $arRates : -1, "UF_DATE" => !empty($arUnixDates) ? $arUnixDates : -1, "UF_SERVICE_ID" => $this->arParams['ROW_ID']));

        foreach ($arValues as $rate_id => $arr) {
            foreach ($arr as $unixDate => $value) {

                $arParams = null;

                if ($value == 1) {

                    if ($arQuotas[$rate_id][$unixDate]) {

                        if ($arQuotas[$rate_id][$unixDate]['UF_STOP'] != 1) {
                            $arParams = array(
                                array(
                                    $this->__getDataClass($this->arParams['QUOTAS_BOOKING_HL_BLOCK']),
                                    "update"
                                ),
                                array(
                                    $arQuotas[$rate_id][$unixDate]['ID'],
                                    array("UF_STOP" => 1)
                                )
                            );
                        }
                    } else {

                        $arParams = array(
                            array(
                                $this->__getDataClass($this->arParams['QUOTAS_BOOKING_HL_BLOCK']),
                                "add"
                            ),
                            array(
                                array(
                                    "UF_STOP" => 1,
                                    "UF_DATE" => $unixDate,
                                    "UF_RATE_ID" => $rate_id,
                                    "UF_SERVICE_ID" => $this->arParams['ROW_ID']
                                )
                            )
                        );
                    }
                } elseif ($arQuotas[$rate_id][$unixDate]) {

                    $arParams = array(
                        array(
                            $this->__getDataClass($this->arParams['QUOTAS_BOOKING_HL_BLOCK']),
                            "update"
                        ),
                        array(
                            $arQuotas[$rate_id][$unixDate]['ID'],
                            array(
                                "UF_STOP" => 0,
                                "UF_DATE" => $unixDate,
                                "UF_RATE_ID" => $rate_id,
                                "UF_SERVICE_ID" => $this->arParams['ROW_ID']
                            )
                        )
                    );
                }

                if ($arParams && $result = $this->__callORMBxMethod($arParams)) {

                    $this->proccessingLog[$logKey][] = array(
                        "unixDate" => $unixDate,
                        "rate_id" => $rate_id,
                        "value" => $value
                    );
                }
            }
        }
        $this->_clearTagCache("highloadblock_" . $this->arParams["QUOTAS_BOOKING_HL_BLOCK"] . "_" . $this->arParams["ROW_ID"]);
    }

    /**
     * @param string $action
     * @param string $id
     * @param array $toLog
     */
    protected function _completeLogByQoutas($action, $id, array $toLog = array()) {

        if ($action === "delete") {

            $toLog = array_merge($toLog, array(
                "sold" => null,
                "onSale" => null
            ));
        } elseif ($action === "add") {

            $toLog = array_merge($toLog, array(
                "sold" => null,
                "onSale" => $toLog["value"]
            ));
        } elseif ($action === "update") {

            $dataClass = $this->__getDataClass($this->arParams['QUOTAS_BOOKING_HL_BLOCK']);
            $res = $dataClass::getList(array("filter" => array("ID" => $id), "select" => array("UF_SOLD_NUMBER", "ID")))->fetch();
            $onSale = $toLog["value"] - $res["UF_SOLD_NUMBER"];
            if ($onSale < 0) {
                $onSale = 0;
            }

            $toLog = array_merge($toLog, array(
                "sold" => $res["UF_SOLD_NUMBER"],
                "onSale" => $onSale
            ));
        }

        return $toLog;
    }

    /**
     * Обработка запроса на цены
     * @param array $arValues
     */
    protected function proccessingPostPrices($arValues, $logKey) {

        $arUnixDates = array_keys($arValues);

        $arPtrids = [];
        foreach ($arValues as $unixDate => $arValue) {

            $arPtrids = array_merge($arPtrids, array_keys($arValue));
        }

        $arPrices = $this->getListPrices(array(
            "UF_PTPR_ID" => !empty($arPtrids) ? array_unique($arPtrids) : -1,
            "UF_DATE" => !empty($arUnixDates) ? $arUnixDates : -1,
            "UF_SERVICE_ID" => $this->arParams['ROW_ID']
                )
        );

        foreach ($arValues as $unixDate => $arValue) {

            foreach ($arValue as $ptrid => $value) {

                $arParams = null;

                if ($value != "") {
                    $value = 0 == $value ? 0.0001 : $value;
                }

                if ($value > 0) {

                    if ($arPrices[$unixDate][$ptrid]) {

                        //if ($arPrices[$unixDate][$ptrid]['UF_GROSS'] != $value) {
                        $arParams = array(
                            array(
                                $this->__getDataClass($this->arParams['PRICES_BOOKING_HL_BLOCK']),
                                "update"
                            ),
                            array(
                                $arPrices[$unixDate][$ptrid]['ID'],
                                array("UF_GROSS" => $value)
                            )
                        );
                        //}
                    } else {

                        $arParams = array(
                            array(
                                $this->__getDataClass($this->arParams['PRICES_BOOKING_HL_BLOCK']),
                                "add"
                            ),
                            array(
                                array(
                                    "UF_GROSS" => $value,
                                    "UF_PTPR_ID" => $ptrid,
                                    "UF_DATE" => $unixDate,
                                    "UF_SERVICE_ID" => $this->arParams['ROW_ID'],
                                )
                            )
                        );
                    }
                } elseif ($arPrices[$unixDate][$ptrid]) {
//                    dm($arPrices[$unixDate][$ptrid]['ID'], false, false, false);
                    $arParams = array(
                        array(
                            $this->__getDataClass($this->arParams['PRICES_BOOKING_HL_BLOCK']),
                            "delete"
                        ),
                        array(
                            $arPrices[$unixDate][$ptrid]['ID']
                        )
                    );
                }

                if ($value != "") {
                    $value = $value != 0.0001 ? $value : 0;
                }

                if ($arParams && $result = $this->__callORMBxMethod($arParams)) {
                    // write to log
                    $this->proccessingLog[$logKey][] = array(
                        "unixDate" => $unixDate,
                        "value" => $value,
                        "ptrid" => $ptrid,
                        "action" => $arParams[0][1]
                    );
                }
            }
        }
        $this->_clearTagCache("highloadblock_" . $this->arParams["PRICES_BOOKING_HL_BLOCK"] . "_" . $this->arParams["ROW_ID"]);
    }
    
    protected function proccessingPostDiscountAbs($arValues, $logKey) {

        $arUnixDates = array_keys($arValues);

        $arPtrids = [];
        foreach ($arValues as $unixDate => $arValue) {

            $arPtrids = array_merge($arPtrids, array_keys($arValue));
        }

        $arPrices = $this->getListPrices(array(
            "UF_PTPR_ID" => !empty($arPtrids) ? array_unique($arPtrids) : -1,
            "UF_DATE" => !empty($arUnixDates) ? $arUnixDates : -1,
            "UF_SERVICE_ID" => $this->arParams['ROW_ID']
                )
        );

        foreach ($arValues as $unixDate => $arValue) {

            foreach ($arValue as $ptrid => $value) {

                $arParams = null;

                if ($value != "") {
                    $value = 0 == $value ? 0.0001 : $value;
                }
                if ($arPrices[$unixDate][$ptrid]) {

                    $arParams = array(
                        array(
                            $this->__getDataClass($this->arParams['PRICES_BOOKING_HL_BLOCK']),
                            "update"
                        ),
                        array(
                            $arPrices[$unixDate][$ptrid]['ID'],
                            array("UF_DISCOUNT_ABS" => $value)
                        )
                    );
                } 
                 

                if ($value != "") {
                    $value = $value != 0.0001 ? $value : 0;
                }

                if ($arParams && $result = $this->__callORMBxMethod($arParams)) {
                    $post = $this->request->getPost('mainForm');
                    // write to log
                    $this->processingLog[$logKey][] = array(
                        "unixDate" => $unixDate,
                        "value" => $value,
                        "ptrid" => $ptrid,
                        "action" => $arParams[0][1]
                    );
                    $this->processingLog["close_modal"] = !empty($post['massEdit']);
                }
            }
        }

        $this->_clearTagCache("highloadblock_" . $this->arParams["PRICES_BOOKING_HL_BLOCK"] . "_" . $this->arParams["ROW_ID"]);
    }
    
    protected function proccessingPostDiscountPercent($arValues, $logKey) {

        $arUnixDates = array_keys($arValues);

        $arPtrids = [];
        foreach ($arValues as $unixDate => $arValue) {

            $arPtrids = array_merge($arPtrids, array_keys($arValue));
        }

        $arPrices = $this->getListPrices(array(
            "UF_PTPR_ID" => !empty($arPtrids) ? array_unique($arPtrids) : -1,
            "UF_DATE" => !empty($arUnixDates) ? $arUnixDates : -1,
            "UF_SERVICE_ID" => $this->arParams['ROW_ID']
                )
        );

        foreach ($arValues as $unixDate => $arValue) {

            foreach ($arValue as $ptrid => $value) {

                $arParams = null;

                if ($value != "") {
                    $value = 0 == $value ? 0.0001 : $value;
                }
                if ($arPrices[$unixDate][$ptrid]) {

                    $arParams = array(
                        array(
                            $this->__getDataClass($this->arParams['PRICES_BOOKING_HL_BLOCK']),
                            "update"
                        ),
                        array(
                            $arPrices[$unixDate][$ptrid]['ID'],
                            array("UF_DISCOUNT_PERCENT" => $value)
                        )
                    );
                } 
                 

                if ($value != "") {
                    $value = $value != 0.0001 ? $value : 0;
                }

                if ($arParams && $result = $this->__callORMBxMethod($arParams)) {
                    $post = $this->request->getPost('mainForm');
                    // write to log
                    $this->processingLog[$logKey][] = array(
                        "unixDate" => $unixDate,
                        "value" => $value,
                        "ptrid" => $ptrid,
                        "action" => $arParams[0][1]
                    );
                    $this->processingLog["close_modal"] = !empty($post['massEdit']);
                }
            }
        }

        $this->_clearTagCache("highloadblock_" . $this->arParams["PRICES_BOOKING_HL_BLOCK"] . "_" . $this->arParams["ROW_ID"]);
    }

    /**
     * @param string $lifePeriod
     */
    protected function _prepareLifePeriodForSave(string $lifePeriod = null) {

        $result = null;

        if ($lifePeriod) {
            $expLifePeriod = explode(",", str_replace(" ", "", $lifePeriod));
            for ($i = 0, $cnt = count($expLifePeriod); $i < $cnt; $i++) {

                if (strpos($expLifePeriod[$i], "-") !== false) {

                    $tmpRes = explode("-", $expLifePeriod[$i]);
                    if ($tmpRes[1] - $tmpRes[0] >= 1) {
                        for ($j = $tmpRes[0]; $j <= $tmpRes[1]; $j++) {
                            $result[] = (int) $j;
                        }
                    }
                    continue;
                }
                $result[] = (int) $expLifePeriod[$i];
            }

            $result = array_unique($result);
            sort($result);
        }
        return $result;
    }

    /**
     * Обработка запроса на "нет заездов, нет отъездов"
     * @param array $arValues
     * @param array $logKey
     */
    protected function proccessingPostLifePeriodNoArrivalsDepartures($arValues, $logKey = "noArrivals") {

        $field = $this->getTableField($logKey);
        
        $dates = \array_keys($arValues);
        
        $rates = [];
        
        foreach ($arValues as $unixDate => $arValue) {

            foreach ($arValue as $rid => $value) {
                $rates[] = $rid;
            }
        }
        
        $arPTRates = $this->getListPTRates(array("UF_RATE_CATEGORY_ID" => $rates));

        $arPrice = $this->getListPrices(array(
                    "UF_DATE" => $dates,
                    "UF_PTPR_ID" => $arPTRates[0] ? $arPTRates[0] : -1,
                    "UF_SERVICE_ID" => $this->arParams['ROW_ID']
                        )
                );
        
        foreach ($arValues as $unixDate => $arValue) {

            foreach ($arValue as $rid => $value) {

                $arParams = null;

                if (empty($arPrice)) {
                    continue;
                }

                if ($field == "UF_LIFE_PERIOD") {
                    $value = $this->_prepareLifePeriodForSave($value);
                }
                
                if ($arPrice[$unixDate]) {

                    $arParams[0] = array(
                        $this->__getDataClass($this->arParams['PRICES_BOOKING_HL_BLOCK']),
                        "update"
                    );

                    foreach ($arPrice[$unixDate] as $ptrid => $arrPrice) {

                        $arParams[1] = array(
                            $arrPrice['ID'],
                            array($field => $value)
                        );

                        if ($result = $this->__callORMBxMethod($arParams)) {
                            // write to log
                            $this->proccessingLog[$logKey][] = array(
                                "unixDate" => $unixDate,
                                "value" => $value,
                                "rid" => $rid
                            );
                        }
                    }
                } else {

                    $arParams[0] = array(
                        $this->__getDataClass($this->arParams['PRICES_BOOKING_HL_BLOCK']),
                        "add"
                    );

                    $arParams[1] = array(
                        array(
                            "UF_GROSS" => 0.0001,
                            "UF_PTPR_ID" => $arPTRates[0][0] ? $arPTRates[0][0] : -1,
                            "UF_DATE" => $unixDate,
                            "UF_SERVICE_ID" => $this->arParams['ROW_ID'],
                            $field => $value
                        )
                    );

                    if ($result = $this->__callORMBxMethod($arParams)) {
                        // write to log
                        $this->proccessingLog[$logKey][] = array(
                            "unixDate" => $unixDate,
                            "value" => $value,
                            "rid" => $rid
                        );
                    }
                }
            }
        }
        $this->_clearTagCache("highloadblock_" . $this->arParams["PRICES_BOOKING_HL_BLOCK"] . "_" . $this->arParams["ROW_ID"]);
        $this->_clearTagCache("highloadblock_" . $this->arParams["QUOTAS_BOOKING_HL_BLOCK"] . "_" . $this->arParams["ROW_ID"]);
        $this->_clearTagCache("highloadblock_" . $this->arParams["RATES_BOOKING_HL_BLOCK"] . "_" . $this->arParams["ROW_ID"]);
    }

    /**
     * Component body
     */
    public function executeComponent() {

        try {

            $this->checkUserGroupPermission();

            \Bitrix\Main\Loader::includeModule("highloadblock");



            // получаем услуги(номера) поставщиков
            $this->arResult = $this->getListServicesInfo($this->isAdmin ? array() : array("UF_USER_ID" => $this->userId));

            $this->arParams['ROW_ID'] = (int) $this->arParams['ROW_ID'];

            if (isset($this->arResult['servicesInfo']['services'][$this->arParams['ROW_ID']])) {

                $this->request = Bitrix\Main\Application::getInstance()->getContext()->getRequest();

                $this->dateRangeSettings = $this->getDateRangeSettingsArray();

                $this->proccessingPost();

                $this->isAjax($this->proccessingLog);

                // make arParams for ajax component
                unset($_SESSION['TS_APC_PARAMS']);
                foreach ($this->arParams as $key => $val) {

                    $tilde = substr($key, 0, 1);

                    if ($tilde == "~" && $key != "~COMPONENT_TEMPLATE" && key != "~IS_AJAX") {
                        $_SESSION['TS_APC_PARAMS'][substr($key, 1)] = $val;
                    }
                }

                // получаем тарифы
                $arFilter = array(
                    array(
                        array(
                            "LOGIC" => "OR",
                            array("UF_SERVICES_ID" => array($this->arParams['ROW_ID'])),
                            array("UF_SERVICES_ID" => FALSE)
                        ),
                    ),
                    array(
                        array(
                            "LOGIC" => "OR",
                            array("UF_USER_ID" => $this->userId),
                            array("UF_USER_ID" => FALSE)
                        )
                    ),
                    "UF_FOR_PLACE" => 0
                );
                if ($this->isAdmin) {
                    $arFilter = array();
                }
                $this->arResult['rates'] = $this->getListRates($arFilter);

                // получаем тарифы + типы цен
                $this->arResult['ptRates'] = $this->getListPTRates(array("UF_RATE_CATEGORY_ID" => array_keys($this->arResult['rates'])));

                // получаем типы цен
                $this->arResult["priceTypes"] = $this->getListPriceTypes(array(
                    "ID" => array_unique($this->arResult['ptRates'][1]),
                    "UF_ACTIVE" => 1)
                );

                // массив дат
                if ($this->request->get('getDateRange')!='') $this->arResult['dateArray'] = $this->getDateRangeArray();
                else $this->arResult['dateArray'] = $this->getDateArray();
                $this->arResult['dateArray']['dateRangeSettings'] = $this->dateRangeSettings;

                // получаем квоты (если заведены)
                $this->arResult['quotes'] = $this->getListQuotes(array(
                    "UF_DATE" => $this->arResult['dateArray']['unixDaysArray'],
                    "UF_SERVICE_ID" => $this->arParams['ROW_ID'],
                    "UF_RATE_ID" => array_keys($this->arResult['rates']))
                );

                // получаем заведённые цены(если заведены)
                $this->arResult['prices'] = $this->getListPrices(array(
                    "UF_DATE" => $this->arResult['dateArray']['unixDaysArray'],
                    "UF_SERVICE_ID" => $this->arParams['ROW_ID'],
                    "UF_PTPR_ID" => $this->arResult['ptRates'][0])
                );

                if (Bitrix\Main\Loader::includeModule("travelsoft.currency")) {
                    $this->arResult['currency'] = \travelsoft\Currency::getInstance()->get('currency');
                }
            }

            $this->IncludeComponentTemplate();
        } catch (\Exception $ex) {
            $this->isAjax(array("error" => $ex->getMessage()));
            ShowError($ex->getMessage());
        }
    }

    /**
     * Методы работы с highloadblock 
     */

    /**
     * Вызов методов
     * @param array $params
     * @return mixed
     */
    protected function __callORMBxMethod($params) {

        $result = call_user_func_array($params[0], $params[1]);

        if ($result->isSuccess()) {
            return $params[0][1] != "delete" ? $result->getId() : $params[1][0];
        } else {
            $this->errorsLog = $result->getErrorMessages();
            throw new Exception("Произошла ошибка при попытке сохранения данных");
        }
    }

    /**
     *  highloadblock entity data class
     * @param integer $hlblock_id
     */
    protected function __getDataClass($hlblock_id) {

        if (!$this->_arCacheHLEntities[$hlblock_id]) {
            $hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $this->_arCacheHLEntities[$hlblock_id] = $entity->getDataClass();
        }

        return $this->_arCacheHLEntities[$hlblock_id];
    }

    /**
     * Получаем объект запроса к базе данных
     * @param integer $hlBlock_id
     * @param array $arFilter
     * @return \Bitrix\Main\DB\Result
     */
    protected function __getListDBResult($hlBlock_id = null, $arFilter = array()) {

        $dataClass = $this->__getDataClass($hlBlock_id);
        return $dataClass::getList(array(
                    "filter" => $arFilter
        ));
    }

}