<?php

namespace travelsoft\booking;

use \Bitrix\Main\Config\Option as O;
use \Bitrix\Main\Loader as L;
use \Bitrix\Highloadblock as HL;

/**
 * Класс с различными утилитами
 *
 * @author dimabresky
 */
class Utils {
    #############################
    # МЕТОДЫ РАБОТЫ С ПОСТАВЩИКАМИ
    #############################

    /**
     * проверка, что пользователь является поставщиком услуг
     * @throws Exception
     */
    public static function isProvider() {

        if (!self::checkUserIsProvider()) {
            throw new \Exception("Страница доступна только для поставщиков услуг");
        }
    }

    public static function checkUserIsProvider() {
        $arGroups = $GLOBALS["USER"]->GetUserGroupArray();

        if (in_array(self::getOpt("transfers_provider_group"), $arGroups) ||
                in_array(self::getOpt("placements_provider_group"), $arGroups) ||
                in_array(self::getOpt("sanatroium_provider_group"), $arGroups) ||
                in_array(self::getOpt("service_provider_group_id"), $arGroups) ||
                in_array(self::getOpt("excursions_provider_group"), $arGroups) ||
                in_array(self::getOpt("guide_group"), $arGroups) ||
                in_array(self::getOpt("cut_provider_group_id"), $arGroups) ||
                in_array(self::getOpt("super_manager_group_id"), $arGroups)) {
            return true;
        }
        return false;
    }

    ######################
    ###################################
    # МЕТОДЫ РАБОТЫ ПОДКЛЮЧЕНИЯ МОДУЛЕЙ
    ###################################

    /**
     * подключает модуль highloadblock
     * @throws Exception
     */
    public static function incHL() {

        if (!L::includeModule("highloadblock")) {
            throw new Exception("Модуль highloadblock не найден");
        }
    }

    /**
     * подключает модуль iblock
     * @throws Exception
     */
    public static function incIB() {

        if (!L::includeModule("iblock")) {
            throw new Exception("Модуль iblock не найден");
        }
    }

    /**
     * подключает модуль travelsoft.currency
     * @throws Exception
     */
    public static function incCurrency() {

        if (!L::includeModule("travelsoft.currency")) {
            throw new Exception("Модуль travelsoft.currency не найден");
        }
    }

    public static function incTBDT() {
        if (!L::includeModule("travelsoft.booking.dev.tools")) {
            throw new Exception("Модуль travelsoft.booking.dev.tools не найден");
        }
    }

    ######################
    #######################
    # МЕТОДЫ РАБОТЫ С ДАТАМИ
    #######################

    /**
     * Массив интервала дат в определённом формате
     * @param integer $unixStart
     * @param mixed $interval
     * @param mixed $step
     * @param string $format
     * @return array
     */
    public static function getFormattedIntervalDate($unixStart, $interval = null, $step = null, $format = "") {

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
            $mkTimeInterval = mktime($H, $i, $s, $m - 1, $d, $Y + 1);
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
                "title" => call_user_func_array(array(self, $callMethod), array($unixStart))
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
    public static function getDateArray() {

        $now = time();

        $arResult['monthsArray'] = self::getFormattedIntervalDate(
                        mktime(0, 0, 0, date("n", $now), 1, date('Y', $now)), "year", "month"
        );

        $arResult['_get'] = $_REQUEST['getDate'];

        $unixDate = mktime(0, 0, 0, date("m", $now), date("d", $now), date('Y', $now));

        if ($arResult['_get'] > $now) {
            $unixDate = mktime(0, 0, 0, date("m", $arResult['_get']), 1, date('Y', $arResult['_get']));
        }

        $arResult['daysArray'] = self::getFormattedIntervalDate($unixDate, "month", "day", "getDaysFormat");

        foreach ($arResult['daysArray'] as $ar) {
            $arResult['unixDaysArray'][] = $ar['unixDate'];
        }

        return $arResult;
    }

    /**
     * Настройки отображения диапазона дат
     * @return array
     */
    public static function getDateRangeSettingsArray() {
        $now = time();
        $day = date("d", $now);
        $month = date("m", $now);
        $year = date('Y', $now);
        return array(
            "locale" => LANGUAGE_ID,
            "format" => "DD/MM/YYYY",
            "separator" => " - ",
            "minUnixDate" => mktime(0, 0, 0, $month, $day, $year),
            "maxUnixDate" => mktime(0, 0, 0, $month, $day, $year + 1)
        );
    }

    /**
     * Формат для отображения месяцев в select
     * @param type $unixDate
     * @return type
     */
    public static function getMonthFormat($unixDate) {
        return FormatDate("f", $unixDate) . "(" . date("Y", $unixDate) . ")";
    }

    /**
     * Формат для отображения дней в table
     * @param type $unixDate
     * @return type
     */
    public static function getDaysFormat($unixDate) {
        return date("d", $unixDate) . " " . FormatDate("M", $unixDate) . ". " . FormatDate("D", $unixDate) . ".";
    }

    /**
     * Фильтрация дней в периоде
     * @param array $arDaysNumber
     * @param array $arDays
     * @return array
     */
    public static function filterDays(array $arDaysNumber, array $arDays) {
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
     * Фильтрует список номеров отелей по наличию флага "Расчет стоимости по дням";
     * @param array $servicesId
     * @param string $parameter
     * @return array
     */
    public static function filterServicesByParameter(array $servicesId, string $parameter): array {

        $resultServicesId = array();

        $servicesData = new datastores\ServicesDataStore(array('filter' => array(
                "ID" => $servicesId
            ), 'select' => array('UF_IBLOCK_ELEMENT_ID', 'ID')));

        $arGroupedServiceByParents = $servicesData->fetch(array("UF_IBLOCK_ELEMENT_ID"));

        if (!empty($arGroupedServiceByParents)) {

            $dbParents = \CIBlockElement::GetList(
                            false, array(
                        'ID' => array_keys($arGroupedServiceByParents)
                            ), false, false, array(
                        'ID', 'IBLOCK_ID',
            ));

            while ($parent = $dbParents->GetNextElement()) {
                $arFields = $parent->GetFields();
                $arProps = $parent->GetProperties();
                if ($arProps[$parameter]['VALUE'] == 'Y' && isset($arGroupedServiceByParents[$arFields['ID']])) {
                    foreach ($arGroupedServiceByParents[$arFields['ID']] as $arServData) {
                        $resultServicesId[] = $arServData['ID'];
                    }
                }
            }
        }

        return $resultServicesId;
    }

    #########################################
    ######################################################
    # МЕТОДЫ РАБОТЫ ДЛЯ ПОЛУЧЕНИЯ ТИПОВ ОБЪЕКТОВ БРОНИРОВАНИЯ
    ######################################################

    /**
     * @return array
     */
    public static function stypes() {
        $arUF = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields('HLBLOCK_' . self::getOpt("services"), 0);
        $dbRes = \CUserFieldEnum::GetList(array(), array(
                    "USER_FIELD_ID" => $arUF["UF_SERVICE_TYPE_NAME"]['ID'],
        ));
        while ($res = $dbRes->Fetch()) {
            $arUFV[$res["ID"]] = $res["VALUE"];
        }

        return $arUFV;
    }

    /**
     * Возвращает массив вида type => type
     * @return array
     */
    public static function stOnlyNames() {
        $arTypes = self::stypes();
        foreach ($arTypes as $type) {
            $arTypesOptVals[$type] = $type;
        }
        return $arTypesOptVals;
    }

    #####################################
    ##########################################
    # МЕТОДЫ РАБОТЫ С ХРАНИЛИЩЕМ (adapter1)
    ##########################################

    /**
     *  Возвращает имя класса для работы с таблицей
     * @param string $tablename
     * @retrun string
     */
    public static function dc(string $tablename) {
        $hlblock = HL\HighloadBlockTable::getById(self::getOpt($tablename))->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        return $entity->getDataClass();
    }

    /**
     * Проверка наличия у тарифа типа цены "Стоимость за номер для одного человека"
     * @param int $rate_id
     * @return boolean
     */
    public static function priceForOneAdultByRoomIsExists(int $rate_id) {

        $result = datastores\PTRatesDataStore::get(array('filter' => array(
                        'UF_RATE_ID' => placements\PriceCalculator::PRICE_FOR_ONE_ADULT_BY_ROOM,
                        'UF_RATE_CATEGORY_ID' => $rate_id
                    ), 'select' => array('ID')));

        if (!empty($result)) {
            return true;
        }

        return false;
    }

    /**
     * Возвращает массив строк из хранилища
     * @param string $store
     * @return array
     */
    public static function getFromStore(string $store, array $query = array()) {

        $dataClass = self::dc($store);

        if ($query["select"]) {
            if (!in_array("ID", $query["select"])) {
                $query["select"] = array("ID");
            }
        }

        $os = $dataClass::getList($query);

        $arResult = array();
        while ($data = $os->fetch()) {
            $arResult[] = $data;
        }

        return $arResult;
    }

    /**
     * Сохранение в хранилище данных
     * @param string $store
     * @param array $arSave
     * @param int $ID
     * @return boolean|int
     */
    public function saveInStore(string $store, array $arSave, int $ID = null) {

        $dataClass = self::dc($store);

        if (!empty($arSave)) {

            if ($ID) {
                return $dataClass::update($ID, $arSave)->isSuccess();
            } else {
                return $dataClass::add($arSave)->getId();
            }
        }

        return false;
    }

    /**
     * Удаление записи из хранилища
     * @param string $store
     * @param int $ID
     * @return boolean
     */
    public function deleteFromStore(string $store, int $ID) {
        $dataClass = self::dc($store);
        return $dataClass::delete($ID);
    }

    ######################
    ##########################################
    # МЕТОДЫ РАБОТЫ С ХРАНИЛИЩЕМ (adapter2)
    ##########################################

    /**
     * Возвращает массив строк из хранилища
     * @param string $store
     * @return array
     */
    public static function getFromIBStore(string $store, array $query = array()) {

        self::incIB();

        $arOrder = false;
        $arFilter = array();
        $arSelect = array();

        if ($query["order"]) {
            $arOrder = $query["order"];
        }
        if ($query["filter"]) {
            $arFilter = $query["filter"];
//            if (!$arFilter["ACTIVE"]) {
//                $arFilter["ACTIVE"] = "Y";
//            }
        }
        $arFilter["IBLOCK_ID"] = self::getOpt($store);
        if ($query["select"]) {
            $arSelect = $query["select"];
            if (!in_array("ID", $arSelect)) {
                $arSelect[] = "ID";
            }
        }

        $os = \CIBlockElement::GetList($arOrder, $arFilter, false, false, $arSelect);

        $arResult = array();
        while ($data = $os->Fetch()) {
            $arResult[] = $data;
        }

        return $arResult;
    }

    /**
     * Сохранение в хранилище данных
     * @param string $store
     * @param array $arSave
     * @param int $ID
     * @return boolean|int
     */
    public function saveInIBStore(array $arSave, int $ID = null) {

        self::incIB();

        if (!empty($arSave)) {

            if ($ID) {
                return \CIBlockElement::Update($ID, $arSave);
            } else {
                return \CIBlockElement::Add($arSave);
            }
        }

        return false;
    }

    /**
     * Удаление записи из хранилища
     * @param string $store
     * @param int $ID
     * @return boolean
     */
    public function deleteFromIBStore(int $ID) {
        self::incIB();
        return \CIBlockElement::Delete($ID);
    }

    ##########################################

    /**
     * Возвращает значение опции системы
     * @param string $option
     * @return string
     */
    public static function getOpt(string $option) {

        $moduleName = "travelsoft.booking.dev.tools";

        # сокращение названия таблиц
        if ($option == "services") {
            $option = "service_hl_id";
        } elseif ($option == "transfers") {
            $option = "transfer_hl_id";
        } elseif ($option == "transferrates") {
            $option = "transfer_rates_hl_id";
        } elseif ($option == "prices") {
            $option = "price_hl_id";
        } elseif ($option == "classauto") {
            $option = "class_auto_hl_id";
        } elseif ($option == "quotas") {
            $option = "quotas_hl_id";
        } elseif ($option == "ratesQuotas") {
            $option = "rates_quotas_hl_id";
        } elseif ($option == "ptrates") {
            $option = "ptr_hl_id";
        } elseif ($option == "rates") {
            $option = "rate_hl_id";
        } elseif ($option == "pricetypes") {
            $option = "price_type_hl_id";
        } elseif ($option == "food") {
            $option = "food_hl_id";
        } elseif ($option == "citizenship") {
            $option = "citizenship_hl_id";
        } elseif ($option == "placements") {
            $option = "placements_ib_id";
        } elseif ($option == "sanatorium") {
            $option = "sanatorium_ib_id";
        } elseif ($option == "excursions") {
            $option = "excursions_ib_id";
        }

        $value = null;

        switch ($option) {
            # ID таблицы услуг
            case "service_hl_id":
            # ID таблицы услуг
            case "rate_hl_id":
            # ID таблицы типы цен
            case "price_type_hl_id":
            # ID таблицы тарифы + типы цен
            case "ptr_hl_id":
            # ID таблицы классы авто    
            case "class_auto_hl_id":
            # ID таблицы гражданства
            case "citizenship_hl_id":
            # ID таблицы питания        
            case "food_hl_id":
            # ID таблицы трансферов
            case "transfer_hl_id":
            # ID таблицы квот для тарифов  
            case "rates_quotas_hl_id":
            # ID таблицы квот    
            case "quotas_hl_id":
            # ID таблицы цен
            case "price_hl_id":
            # ID таблицы тарифы по трансферам
            case "transfer_rates_hl_id":
            # ID таблицы по объектам размещения
            case "placements_ib_id":
            # ID таблицы по санаториям
            case "sanatorium_ib_id":
            # ID таблицы по экскурсиям
            case "excursions_ib_id":
            # ID таблицы по autostopsale
            case "autostopsale":
            # ID группы поставщиков
            case "service_provider_group_id":
            # ID группы поставщиков трансферов
            case "transfers_provider_group":
            # ID группы поставщиков объектов размещений
            case "placements_provider_group":
            # ID группы поставщиков санаториев
            case "sanatorium_provider_group":
            # ID группы поставщиков экскурсионных туров
            case "excursions_provider_group":
            # ID группы гидов
            case 'guide_group':
            # ID группы поставщиков с урезанными правами
            case "cut_provider_group_id":
            # ID группы супер менеджеров (для управления поставщиками)
            # ID группы агентов
            case "agents_group_id":
            # время кеширования цен в секундах
            case "cache_time":
            # "соль" для хеширования строк
            case "salt":
            # google api key
            case "google_server_api_key":
            # адрес стороннего сервиса (ПК-МастерТур)
            case "tsmo_url":
            # письмо о распродаже квоты-мест
            case "quota_expired_mail_template":
            case "quota_expired_by_period_mail_template":
                $value = O::get($moduleName, $option);
                break;
            # период заезда/выезда в санаторий (unix)
            case "sanatoriumDateRange":
            # период заезда/выезда в размещений (unix)
            case "placementsDateRange":
            # период заезда/выезда в экскурсии (unix)
            case "excursionsDateRange":
                $time = time();
                $step = 86400;
                $arDateCnt = unserialize(O::get($moduleName, $option));
                $startDate = mktime(0, 0, 0, date("m", $time), date("d", $time), date("Y", $time)) + $step * $arDateCnt[0];
                $value = serialize(array($startDate, $startDate + $step * $arDateCnt[1]));
                break;
            case "tbColors":
                $value = serialize(array(
                    "red" => "#E55959", // stop sales
                    "lightRed" => "#E5A0A0", // no arrivals, no departures
                    "yellow" => "#F4F993", // no sales
                    "green" => "#A5CEAC"              // quota exists
                ));
                break;
            case "super_manager_group_id":
                $value = 21;
                break;
        }

        return (string) $value;
    }

    ############################
    # МЕТОДЫ РАБОТЫ С ТРАНСФЕРАМИ
    ############################

    /**
     * Детальная информация о трансфере
     * @param string $a_coords
     * @param string $b_coords
     * @return array
     * @throws \Exception
     */
    public static function getTransferInfo(string $a_coords, string $b_coords) {

//        $response = \Bitrix\Main\Web\Json::decode(file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?origins=" . $a_coords . "&destinations=" . $b_coords . "&mode=driving&key=" . self::getOpt("google_server_api_key")));
//        if ($response["status"] != "OK") {
//            throw new \Exception("Geo request error");
//        }
//        return array(
//            "distance" => $response["rows"][0]["elements"][0]["distance"]["value"],
//            "travel_time" => $response["rows"][0]["elements"][0]["duration"]["value"]
//        );
        return array(
            "distance" => 0,
            "travel_time" => "0:00"
        );
    }

    /**
     * Получаем массив гео-точки
     * @param int $id
     * @return array|null
     */
    public static function getGeoPoint(int $id) {

        $arResult = null;

        self::incIB();

        $point = \CIBlockElement::GetList(
                        false, array("ID" => $id), false, false
                )->GetNextElement();

        if ($point) {
            $arFields = $point->GetFields();
            $arProperties = $point->GetProperties();
            $arResult["ID"] = $arFields["ID"];
            $PP = self::getPostfixProperty();
            $arResult["NAME"] = $PP == "" ? $arFields["NAME"] : $arProperties["NAME" . $PP]["VALUE"];
            $arResult["LAT"] = null;
            $arResult["LNG"] = null;
            if ($arProperties["MAP"]["VALUE"] > 0) {
                $LATLNG = explode(",", $arProperties["MAP"]["VALUE"]);
                $arResult["LAT"] = $LATLNG[0];
                $arResult["LNG"] = $LATLNG[1];
            }
        }

        return $arResult;
    }

    ########################
    #######################################################
    # МЕТОДЫ ИНИЦИАЛИЗАЦИИ ПАРАМЕТРОВ ПОИСКА ЦЕН ПО-УМОЛЧАНИЮ
    #######################################################

    /**
     * Устанавливает дефолтные обязательные параметры запроса для бронирования
     * @param array $brp
     * @param string $type
     */
    public static function prepareBookingRequest(array &$brp, string $type) {

        $prepareMethod = "prepare" . $type . "BookingRequest";
        self::$prepareMethod($brp, $type);
    }

    /**
     * Устанавливает дефолтные обязательные параметры общего запроса бронирования
     * @param array $brp
     * @param string $type
     */
    public static function prepareCommonBookingRequest(array &$brp, string $type) {
        if (!$brp["date_from"] && !$brp["date_to"]) {
            self::setDefaultDateRange($brp, $type);
        }
        if (!$brp["children"]) {
            $brp["children"] = 0;
        }
    }

    /**
     * Устанавливает дефолтные обязательные параметры запроса для размещений
     * @param array $brp
     */
    public static function preparePlacementsBookingRequest(array &$brp) {
        self::prepareCommonBookingRequest($brp, "placements");
        if (!$brp["adults"]) {
            $brp["adults"] = 2;
        }
    }

    /**
     * Устанавливает дефолтные обязательные параметры запроса для санаториев
     * @param array $brp
     */
    public static function prepareSanatoriumBookingRequest(array &$brp) {
        self::prepareCommonBookingRequest($brp, "sanatorium");
        if (!isset($brp["adults"])) {
            $brp["adults"] = 2;
        }
    }

    /**
     * Устанавливает дефолтные обязательные параметры запроса для экскурсий
     * @param array $brp
     */
    public static function prepareExcursionsBookingRequest(array &$brp) {
        self::prepareCommonBookingRequest($brp, "excursions");
        if (!$brp["adults"]) {
            $brp["adults"] = 1;
        }
    }

    /**
     * Устанавливает дефолтные обязательные параметры запроса для трансферов
     * @param array $brp
     */
    public static function prepareTransfersBookingRequest(array &$brp) {
        if (!$brp["adults"]) {
            $brp["adults"] = 2;
        }
        if (!$brp["date_from"]) {
            self::setDefaultDateRange($brp, "transfers");
        }
    }

    /**
     * Устанавливает дейфлотные настройки для дат
     * @param array $brp
     * @param string $type
     */
    public static function setDefaultDateRange(array &$brp, string $type) {

        $defDateRange = unserialize(self::getOpt($type . "DateRange"));
        $brp["date_from"] = $defDateRange[0];
        if ($defDateRange[1]) {
            $brp["date_to"] = $defDateRange[1];
        }
    }

    #########################################

    /**
     * варианты цен для граждан
     * @return array
     */
    public static function getCitizenPrices() {

        $cache = new \travelsoft\booking\Cache("citizen_price_data_for_" . SITE_ID, "/travelsoft/citizen_price_data", 3600000000000);

        $result = [];
        if (empty($result = $cache->get())) {

            $result = $cache->caching(function () {

                # зничения полей таблицы тарифов
                $UF_FIELDS = $GLOBALS['USER_FIELD_MANAGER']->GetUserFields('HLBLOCK_' . self::getOpt("rate_hl_id"), 0);

                $current = $UF_FIELDS["UF_BR_PRICES"]["ID"];
                if (LANGUAGE_ID == "ru") {
                    $current = $UF_FIELDS["UF_RF_PRICES"]["ID"];
                } elseif (LANGUAGE_ID == "en") {
                    $current = $UF_FIELDS["UF_EU_PRICES"]["ID"];
                }
                return array(
                    "ITEMS" => array(
                        # для граждан Беларуси 
                        $UF_FIELDS["UF_BR_PRICES"]["ID"] => $UF_FIELDS["UF_BR_PRICES"]["FIELD_NAME"],
                        # для граждан РФ
                        $UF_FIELDS["UF_RF_PRICES"]["ID"] => $UF_FIELDS["UF_RF_PRICES"]["FIELD_NAME"],
                        # для граждан Европы
                        $UF_FIELDS["UF_EU_PRICES"]["ID"] => $UF_FIELDS["UF_EU_PRICES"]["FIELD_NAME"]
                    ),
                    "REVERSE_ITEMS" => array(
                        # для граждан Беларуси 
                        $UF_FIELDS["UF_BR_PRICES"]["FIELD_NAME"] => $UF_FIELDS["UF_BR_PRICES"]["ID"],
                        # для граждан РФ
                        $UF_FIELDS["UF_RF_PRICES"]["FIELD_NAME"] => $UF_FIELDS["UF_RF_PRICES"]["ID"],
                        # для граждан Европы
                        $UF_FIELDS["UF_EU_PRICES"]["FIELD_NAME"] => $UF_FIELDS["UF_EU_PRICES"]["ID"]
                    ),
                    "CURRENT" => $current
                );
            });
        }

        return $result;
    }

    ##############################
    # МЕТОДЫ РАБОТЫ С  РАСЧЁТОМ ЦЕН
    ##############################

    /**
     * Возвращает результат расчёта цен по запросу
     * @param array $parameters
     * @return array|null
     */
    public static function getPriceCalculation(array $parameters) {

        $arResult = null;

        $cache_id = self::getCacheIdForSearchOffers($parameters["request"], $parameters["type"], $parameters["mp"]);

        $cache = new \travelsoft\booking\Cache($cache_id, self::getCacheRootDirForSearchOffers() . "/" . $cache_id, self::getOpt("cache_time"));
        if (empty($arResult = $cache->get())) {

            $arResult = $cache->caching(function () use ($parameters, $cache) {
                $calculator = self::getCalculator(
                                self::getDataProvider($parameters['request'], $parameters['type']), $parameters['type']);

                if (!$parameters["mp"]) {

                    $arResult = $calculator->calculate();
                } else {
                    $arResult = $calculator->minPrice();
                }

                self::setTagCacheForSearchOffers($cache, $parameters["request"]);

                return $arResult;
            });
        }
        return $arResult;
    }

    /**
     * @param \travelsoft\booking\Cache $cache
     * @param \travelsoft\booking\abstractions\Request $request
     */
    public static function setTagCacheForSearchOffers(Cache $cache, \travelsoft\booking\abstractions\Request $request) {

        $arServicesId = [];
        if (!empty($request->id)) {
            $_cache = \Bitrix\Main\Data\Cache::createInstance();
            if ($_cache->initCache(self::getOpt("cache_time"), md5(serialize($request->id)), "/travelsoft/booking/services_by_objects")) {
                $arServicesId = $_cache->getVars();
            } elseif ($_cache->startDataCache()) {

                $services = new datastores\ServicesDataStore([
                    "filter" => ["UF_IBLOCK_ELEMENT_ID" => $request->id],
                    "select" => ["ID"]
                ]);

                $arServicesId = array_keys($services->fetch(["ID"]));

                if (!empty($arServicesId)) {
                    $_cache->endDataCache($arServicesId);
                } else {
                    $_cache->abortDataCache();
                }
            }
        } else {
            $arServicesId[] = $request->service_id;
        }


        $cache->setTagCache("highloadblock_" . self::getOpt("ptrates"));
        $cache->setTagCache("highloadblock_" . self::getOpt("pricetypes"));

        foreach ($arServicesId as $service_id) {
            $cache->setTagCache("highloadblock_" . self::getOpt("prices") . "_" . $service_id);
            $cache->setTagCache("highloadblock_" . self::getOpt("rates") . "_" . $service_id);


            $cache->setTagCache("highloadblock_" . self::getOpt("quotas") . "_" . $service_id);
            $cache->setTagCache("highloadblock_" . self::getOpt("ratesQuotas") . "_" . $service_id);


            $cache->setTagCache("highloadblock_" . self::getOpt("services") . "_" . $service_id);
        }
    }

    /**
     * @param array $arServicesId
     */
    public static function clearTagCacheForSearchOffers(array $arServicesId) {

        self::clearCacheByTag("highloadblock_" . self::getOpt("ptrates"));
        self::clearCacheByTag("highloadblock_" . self::getOpt("pricetypes"));
        self::clearCacheByTag("highloadblock_" . self::getOpt("rates"));
        self::clearCacheByTag("highloadblock_" . self::getOpt("services"));
        self::clearCacheByTag("highloadblock_" . self::getOpt("quotas"));
        self::clearCacheByTag("highloadblock_" . self::getOpt("ratesQuotas"));
        foreach ($arServicesId as $service_id) {
            self::clearCacheByTag("highloadblock_" . self::getOpt("prices") . "_" . $service_id);
            self::clearCacheByTag("highloadblock_" . self::getOpt("rates") . "_" . $service_id);
            self::clearCacheByTag("highloadblock_" . self::getOpt("quotas") . "_" . $service_id);
            self::clearCacheByTag("highloadblock_" . self::getOpt("ratesQuotas") . "_" . $service_id);
            self::clearCacheByTag("highloadblock_" . self::getOpt("services") . "_" . $service_id);
        }
    }

    /**
     * @global $CACHE_MANAGER
     * @param string $tagName
     */
    public static function clearCacheByTag(string $tagName) {
        global $CACHE_MANAGER;
        $CACHE_MANAGER->ClearByTag($tagName);
    }

    /**
     * @param \travelsoft\booking\abstractions\Request $request
     * @param string $type
     * @param bool $mp
     * @param mixed $addit_params
     * @return string
     */
    public static function getCacheIdForSearchOffers(abstractions\Request $request, string $type, bool $mp = false, $addit_params = null) {
        return md5(serialize(array($request->getPropertiesLikeArray(), $type, self::getCurrentCurrency()["iso"], SITE_ID, intVal($mp), $addit_params)));
    }

    /**
     * @return string
     */
    public static function getCacheRootDirForSearchOffers() {
        return "/travelsoft/search_offers_result/" . SITE_ID;
    }

    /**
     * 
     * @param \travelsoft\booking\abstractions\Request $request
     * @param string $type
     * @return \travelsoft\booking\abstractions\DataProvider
     */
    public static function getDataProvider(abstractions\Request $request, string $type) {

        $np = "travelsoft\\booking\\" . $type;

        $dpclass = $np . "\\DataProviderBuilder";

        $dataProviderBuilder = new $dpclass($request);

        return $dataProviderBuilder->build();
    }

    public static function getCalculator(abstractions\DataProvider $dataProvider, string $type) {

        $np = "travelsoft\\booking\\" . $type;

        $calclass = $np . "\\PriceCalculator";

        return new $calclass($dataProvider);
    }

    /**
     * Возвращает "пересчитанную" корзину
     * @return travelsoft\booking\Basket
     */
    public static function getRecalculationBasket() {

        $basket = new Basket();
        $arBasketFields = null;
        while ($basketItem = $basket->fetch()) {
            $item = $basketItem["item"];

            $requestClassName = "travelsoft\\booking\\" . $item->type . "\\Request";
            $priceCalculatorClassName = "travelsoft\\booking\\" . $item->type . "\\PriceCalculator";
            $basketFields = $item->getPropertiesLikeArray();
            if (!empty($basketFields['allocate']) && is_array($basketFields['allocate'])) {
                $arCalculation = self::recalculateOnlyPriceType($basketFields, $basketFields['allocate'], false, $priceCalculatorClassName);
            } else {
                $arCalculation = self::getPriceCalculation(array(
                            "request" => new $requestClassName($basketFields),
                            "type" => $item->type,
                            "mp" => false
                ));
            }
            $basketFields['discount'] = [];
            if (!$arCalculation) {
                $basketFields["can_buy"] = false;
            } else {
                $arPrice = self::_searchPriceFromCalculationData($arCalculation);

                $basketFields["price"] = Utils::convertCurrency($arPrice["PRICE"], $arPrice["CURRENCY_ID"], $item->currency, true);
                $basketFields["currency"] = $item->currency;
                $basketFields["can_buy"] = true;

                if (isset($arPrice["DISCOUNT"]) && $arPrice["DISCOUNT"] > 0) {
                    $basketFields['discount'][] = $arPrice["DISCOUNT"];
                }
            }

            $arBasketFields[] = $basketFields;
        }
        $basket->clear();
        if ($arBasketFields) {
            for ($i = 0, $cnt = count($arBasketFields); $i < $cnt; $i++) {

                $classBasketItem = "travelsoft\\booking\\" . $arBasketFields[$i]["type"] . "\\BasketItem";
                $basket->add(new $classBasketItem($arBasketFields[$i]));
            }
        }

        // переприменение промокодов
        $arPromo = Promo::getList();

        foreach ($arPromo as $value) {

            $promo = Promo::create($value);
            $promo->_apply($basket);
        }
                
        return LoyalityProgramm::apply($basket);
    }

    /**
     * Пересчитывает цену типа цены для элемента корзины в соответствии с рассадкой
     * @param array $arBasketFields
     * @param array $arAllocate
     * @param boolean $one_man_on_source_request
     * @param string $priceCalculatorClassName
     * @return array
     */
    public static function recalculateOnlyPriceType(array $arBasketFields, array $arAllocate, $one_man_on_source_request = false, $priceCalculatorClassName = null) {

        $arCalculation = [];

        $quotas = (new datastores\QuotasDataStore([
                    "filter" => [
                        "UF_SERVICE_ID" => $arBasketFields["service_id"],
                        "!UF_QUOTE" => false,
                        "><UF_DATE" => [$arBasketFields["date_from"], $arBasketFields["date_to"]],
                        "UF_STOP" => 0
                    ],
                    "select" => ["ID", "UF_SERVICE_ID", "UF_QUOTE", "UF_SOLD_NUMBER", "UF_STOP", "UF_DATE"],
                    "order" => ["UF_DATE" => "ASC"]
                        ]))->filterAvailableForSale()->filterByAutostopsale();

        if (empty(array_keys($quotas->fetch(['ID'])))) {
            return $arCalculation;
        }

        $dates = (array) array_keys($quotas->fetch(array("UF_DATE")));

        $services = new datastores\ServicesDataStore([
            "filter" => [
                "ID" => $arBasketFields["service_id"]
            ],
            "select" => ["ID", 'UF_PLACES_MAIN']
        ]);

        $service_main_places = $services->fetch(['ID'])[$arBasketFields["service_id"]][0]['UF_PLACES_MAIN'];

        $rates = new datastores\RatesDataStore([
            "filter" => [
                "ID" => $arBasketFields["rate_id"],
            ],
            "select" => [
                "ID",
                "UF_NAME",
                "UF_FOR_PLACE",
                "UF_ADULTS",
                "UF_CHILDREN",
                "UF_PEOPLE",
                "UF_SERVICES_ID",
                "UF_CURRENCY_ID",
                "UF_BR_PRICES",
                "UF_RF_PRICES",
                "UF_EU_PRICES",
                "UF_AGE_CAT_1_MIN",
                "UF_AGE_CAT_1_MAX",
                "UF_AGE_CAT_2_MIN",
                "UF_AGE_CAT_2_MAX",
                "UF_AGE_CAT_3_MIN",
                "UF_AGE_CAT_3_MAX",
                "UF_DISCOUNT"
            ]
        ]);

        $arRatesId = array_keys($rates->fetch(["ID"]));
        if (!empty($arRatesId)) {

            $f = ["UF_ACTIVE" => 1];
            if (!$one_man_on_source_request) {
                $f["!=ID"] = abstractions\commons\PriceCalculator::PRICE_FOR_ONE_ADULT_BY_ROOM;
            }

            $priceTypes = new datastores\PriceTypesDataStore([
                "filter" => $f
            ]);

            $priceTypesRates = new datastores\PTRatesDataStore([
                "filter" => [
                    "UF_RATE_CATEGORY_ID" => $arRatesId
                ]
            ]);

            $arPriceTypesRatesId = array_keys($priceTypesRates->fetch(["ID"]));

            if (!empty($arPriceTypesRatesId)) {
                $prices = new datastores\PricesDataStore([
                    "filter" => [
                        "UF_SERVICE_ID" => $arBasketFields["service_id"],
                        "UF_PTPR_ID" => $arPriceTypesRatesId,
                        "!UF_GROSS" => false,
                        "UF_DATE" => $dates
                    ],
                    "select" => [
                        "ID", "UF_GROSS", "UF_DATE", "UF_SERVICE_ID", "UF_LIFE_PERIOD", "UF_PTPR_ID", "UF_NO_ARRIVALS"
                    ],
                    "order" => ["UF_DATE" => "ASC"]
                ]);

                if (!empty($prices->fetch())) {

                    $duration = ($arBasketFields["date_to"] - $arBasketFields["date_from"]) / 86400;

                    $arFilterByCalcByDay = self::filterServicesByParameter((array) array_keys($prices->fetch(array('UF_SERVICE_ID'))), 'CALC_BY_DAY');

                    $prices->filterByLifePeriod($duration, $arBasketFields["date_from"]);

                    $prices->filterByCalcForPlacesRates([
                        "services" => $services,
                        "prices" => $prices,
                        "priceTypesRates" => $priceTypesRates,
                        "rates" => $rates,
                        "request" => (object) ['adults' => $arBasketFields['adults'], 'children' => $arBasketFields['children']],
                        "quotas" => $quotas
                    ]);

                    $prices->filterByEmptyPricesPerRange(
                            $arBasketFields["date_from"], $duration, $quotas->fetch(array("UF_SERVICE_ID", "UF_DATE")), $arFilterByCalcByDay
                    );

                    if (!empty($prices->fetch())) {

                        $arPrices = $prices->fetch(array("UF_SERVICE_ID", "UF_PTPR_ID", "UF_DATE"));

                        $arPTRates = $priceTypesRates->fetch(array("ID"));

                        $arRates = $rates->fetch(array("ID"));

                        $arCalcByArrival = self::filterServicesByParameter([$arBasketFields["service_id"]], "CALC_BY_ARRIVAL");

                        $arCurrency = self::getCurrentCurrency();

                        $arGroupedData = array();

                        foreach ($arPrices[$arBasketFields["service_id"]] as $PTRID => $arPTRateDataPrice) {

                            $RID = $arPTRates[$PTRID][0]["UF_RATE_CATEGORY_ID"];

                            if (empty($arRates[$RID])) {
                                continue;
                            }
                            $PTID = $arPTRates[$PTRID][0]["UF_RATE_ID"];

                            if (!$arCalculation[$arBasketFields["service_id"]][$RID]) {

                                $arPrices[$arBasketFields["service_id"]][$RID] = array("DURATION" => $duration, "PRICE" => null, "CURRENCY_ID" => $arCurrency["id"]);
                            }
                            if (!$arGroupedData[$RID]) {

                                $arGroupedData[$RID] = array();
                            }

                            // ГРУППИРУЕМ ДАННЫЕ К ВИДУ ТАРИФ->ТИП ЦЕНЫ->ЦЕНА
                            // ДЛЯ ДАЛЬНЕЙШЕГО РАСЧЕТА ЦЕНЫ ПО ТАРИФУ

                            foreach ($arPTRateDataPrice as $dd => $arDateDataPrice) {

                                for ($j = 0, $cntt = count($arDateDataPrice); $j < $cntt; $j++) {

                                    $arGroupedData[$RID][$PTID][] = self::convertCurrency($arDateDataPrice[$j]["UF_GROSS"], $arRates[$RID][0]["UF_CURRENCY_ID"], $arCurrency["id"], true);
                                }
                            }
                        }

                        if (!empty($arGroupedData)) {

                            $arRatesFiltratedByAge = $rates->filterByAge($arBasketFields["children_age"])->fetch(array("ID"));

                            $arPriceTypes = $priceTypes->fetch(array('ID'));

                            foreach ($arGroupedData as $rateId => $arPriceTypesData) {

                                if (isset($arPriceTypesData[abstractions\commons\PriceCalculator::MAIN_PTID_OLD_VERSION])) {

                                    // РАСЧЕТ ЦЕНЫ ТАРИФА ПО ТИПУ "ЦЕНА"
                                    // ИСПОЛЬЗУЕТСЯ ДЛЯ ОБРАТНОЙ СОВМЕСТИМОСТИ СО СТАРОЙ ВЕРСИЕЙ МОДУЛЯ
                                    if (
                                            isset($arRatesFiltratedByAge[$rateId]) &&
                                            // ПРОВЕРКА ПОДХОДИТ ЛИ ТАРИФ ПО КОЛИЧЕСТВУ ЛЮДЕЙ
                                            $arRates[$rateId][0]['UF_ADULTS'] == $arBasketFields["adults"] &&
                                            $arRates[$rateId][0]['UF_CHILDREN'] == $arBasketFields["children"] &&
                                            $arRates[$rateId][0]['UF_PEOPLE'] == $arBasketFields["children"] + $arBasketFields["adults"]
                                    ) {

                                        $arCalculation[$arBasketFields["service_id"]][$rateId]['PRICE'] = array_sum($arPriceTypesData[4]);
                                    } else {

                                        unset($arCalculation[$arBasketFields["service_id"]][$rateId]);
                                    }
                                } else {

                                    // ХАК ДЛЯ ЗАПРОСА РАСЧЕТА СТОИМОСТИ ТОЛЬКО ПО ДЕТЯМ
                                    $onlyChildren = $arBasketFields["adults"] === 0;

                                    $arLocalPriceResult = array();

                                    foreach ($arPriceTypesData as $ptid => $arrPrices) {

                                        $arPriceType = $arPriceTypes[$ptid][0];

                                        if ($onlyChildren) {
                                            $arPriceType["UF_MAIN"] = true;
                                        }

                                        if ($arPriceType['UF_MAIN'] && $arPriceType['UF_CALC_WIDGET']) {

                                            $result = call_user_func_array(str_replace("\\\\", "\\", $arPriceType['UF_CALC_WIDGET']), array(array(
                                                    'share_price' => ['flag' => !$one_man_on_source_request, 'count_main_places' => $service_main_places ?: 1],
                                                    'id' => $ptid,
                                                    'prices' => $arPriceTypesData,
                                                    'price_types' => $arPriceTypes,
                                                    'sub_price_types' => $arPriceType['UF_SUB_PRICE_TYPES'],
                                                    'allocate' => $arAllocate,
                                                    'calc_by_arrival' => in_array($arBasketFields["service_id"], $arCalcByArrival)
                                            )));

                                            if (
                                                    $result['allocate']['main']['adults'] == 0 &&
                                                    $result['allocate']['additional']['adults'] == 0 &&
                                                    $result['allocate']['main']['children'] == 0 &&
                                                    $result['allocate']['additional']['children'] == 0 &&
                                                    $result['price'] > 0
                                            ) {

                                                $arLocalPriceResult[] = $result['price'];
                                            }
                                        }
                                    }

                                    if (!empty($arLocalPriceResult)) {

                                        $arCalculation[$arBasketFields["service_id"]][$rateId]['PRICE'] = min($arLocalPriceResult);
                                        $arCalculation[$arBasketFields["service_id"]][$rateId]['CURRENCY_ID'] = $arCurrency["id"];
                                    } else {

                                        unset($arCalculation[$arBasketFields["service_id"]][$rateId]);
                                    }
                                }
                            }
                        }

                        if (empty($arCalculation[$arBasketFields["service_id"]])) {
                            unset($arCalculation[$arBasketFields["service_id"]]);
                        }
                    }
                }
            }
        }

        $arCalculation = $priceCalculatorClassName::applyDiscount($arCalculation);

        return $arCalculation;
    }

    /**
     * @param type $arFields
     */
    public static function getRequestClass($arFields) {
        $requestClass = "\\travelsoft\\booking\\" . $arFields["type"] . "\\Request";
        return new $requestClass($arFields);
    }

    /**
     * Возвращает массив цена + валюта из данных по расчёту
     * @param array $arData
     */
    public static function _searchPriceFromCalculationData(array $arData) {
        $data = current($arData);
        if (is_array($data)) {
            if ($data["PRICE"] && $data["CURRENCY_ID"]) {

                $result = array("PRICE" => $data["PRICE"], "CURRENCY_ID" => $data["CURRENCY_ID"]);
                if (isset($data["DISCOUNT_PRICE"]) && $data["DISCOUNT_PRICE"] > 0) {
                    $result["DISCOUNT"] = $data["DISCOUNT"];
                    $result["DISCOUNT_PRICE"] = $data["DISCOUNT_PRICE"];
                }
                return $result;
            }
            return self::_searchPriceFromCalculationData($data);
        }
        return null;
    }

    ######################################
    ###############################
    # МЕТОДЫ РАБОТЫ С ВАЛЮТОЙ
    ###############################

    /**
     * Возвращает массив текущей валюты
     * @return array
     */
    public static function getCurrentCurrency() {

        self::incCurrency();
        return \travelsoft\Currency::getInstance()->get("current_currency");
    }

    /**
     * Возвращает массив всех валют
     * @return array
     */
    public static function getAllCurrency() {

        self::incCurrency();
        return \travelsoft\Currency::getInstance()->get("currency");
    }

    /**
     * Конвертирует валюту
     * @param type $price
     * @param type $in
     * @param type $out
     * @param type $onlyN
     * @return mixed
     */
    public static function convertCurrency($price = null, $in = null, $out = null, $onlyN = false) {
        self::incCurrency();
        return \travelsoft\Currency::getInstance()->convertCurrency($price, $in, $out, $onlyN);
    }

    ###########################################
    ############################
    #   МЕТОДЫ РАБОТЫ С СОБЫТИЯМИ
    ############################

    public static function setEventsHandlers() {

        $eventManager = \Bitrix\Main\EventManager::getInstance();

        $eventManager->addEventHandler(
                "iblock", "OnAfterIBlockElementDelete", array(
            "travelsoft\\booking\\Utils",
            "onAfterObjectDelete"
                )
        );

        $eventManager->addEventHandler(
                "", "TSSERVICESOnAfterDelete", array(
            "travelsoft\\booking\\Utils",
            "onAfterServiceDelete"
                )
        );

        $eventManager->addEventHandler(
                "", "TSRATESCATEGORYOnBeforeUpdate", array(
            "travelsoft\\booking\\Utils",
            "onBeforeRateUpdate"
                )
        );

        $eventManager->addEventHandler(
                "", "TSRATESCATEGORYOnAfterUpdate", array(
            "travelsoft\\booking\\Utils",
            "onAfterRateUpdate"
                )
        );

        $eventManager->addEventHandler(
                "", "TSRATESCATEGORYOnAfterDelete", array(
            "travelsoft\\booking\\Utils",
            "onAfterRateDelete"
                )
        );

        $eventManager->addEventHandler(
                "", "TSRATESPLUSECATEGORYOnAfterDelete", array(
            "travelsoft\\booking\\Utils",
            "onAfterPTRatesDelete"
                )
        );

        $eventManager->addEventHandler(
                "", "TSRATESOnAfterDelete", array(
            "travelsoft\\booking\\Utils",
            "onAfterPriceTypeDelete"
                )
        );

        $eventManager->addEventHandler(
                "", "TSAUTOCLASSOnAfterDelete", array(
            "travelsoft\\booking\\Utils",
            "onAfterClassAutoDelete"
                )
        );

        $eventManager->addEventHandler(
                "", "TSTRANSFERSRATESOnAfterDelete", array(
            "travelsoft\\booking\\Utils",
            "onAfterTransfersRateDelete"
                )
        );
    }

    /**
     * Обработчик удаления основного объекта бронирования
     */
    public static function onAfterObjectDelete(array $arFields) {
        self::clearStoreByFilter("\\travelsoft\\booking\\datastores\ServicesDataStore", array("UF_IBLOCK_ELEMENT_ID" => $arFields["ID"]));
    }

    /**
     * Обработчик удаления услуги бронирования
     */
    public static function onAfterServiceDelete($event) {
        $arrID = $event->getParameter("id");
        self::clearStoreByFilter("\\travelsoft\\booking\\datastores\PricesDataStore", array("UF_SERVICE_ID" => $arrID["ID"]));
        self::clearStoreByFilter("\\travelsoft\\booking\\datastores\QuotasDataStore", array("UF_SERVICE_ID" => $arrID["ID"]));
        self::clearStoreByFilter("\\travelsoft\\booking\\datastores\RatesQuotasDataStore", array("UF_SERVICE_ID" => $arrID["ID"]));
        self::clearServicesLinkPropertyInRates($arrID["ID"]);
    }

    /**
     * Обработчик удаления типа цены
     */
    public static function onAfterPriceTypeDelete($event) {
        $arrID = $event->getParameter("id");
        self::clearStoreByFilter("\\travelsoft\\booking\\datastores\PTRatesDataStore", array("UF_RATE_ID" => $arrID['ID']));
    }

    /**
     * Обработчик удаления тарифа + тип цены
     */
    public static function onAfterPTRatesDelete($event) {
        $arrID = $event->getParameter("id");
        self::clearStoreByFilter("\\travelsoft\\booking\\datastores\PricesDataStore", array("UF_PTPR_ID" => $arrID["ID"]));
    }

    /**
     * Обработчик удаления тарифа
     */
    public static function onAfterRateDelete($event) {
        $arrID = $event->getParameter("id");
        self::clearStoreByFilter("\\travelsoft\\booking\\datastores\PTRatesDataStore", array("UF_RATE_CATEGORY_ID" => $arrID['ID']));
    }

    public static function onBeforeRateUpdate($event) {

        $arrID = $event->getParameter("id");
        $GLOBALS["__TRAVELSOFT"]["RATE_STATE_BEFORE_UPDATE"][$arrID["ID"]] = current(datastores\RatesDataStore::get(array(
                    "filter" => array("ID" => $arrID["ID"]),
                    "select" => array("ID", "UF_SERVICES_ID")
        )));
    }

    public static function onAfterRateUpdate($event) {

        $arrID = $event->getParameter("id");
        if (isset($GLOBALS["__TRAVELSOFT"]["RATE_STATE_BEFORE_UPDATE"][$arrID["ID"]])) {
            $arr_rate = current(datastores\RatesDataStore::get(array(
                        "filter" => array("ID" => $arrID["ID"]),
                        "select" => array("ID", "UF_SERVICES_ID")
            )));
            if (isset($arr_rate["ID"]) && $arr_rate["ID"] > 0) {

                $arr_ptrates = array();
                foreach (datastores\PTRatesDataStore::get(array(
                    "filter" => array("UF_RATE_CATEGORY_ID" => $arr_rate["ID"])
                )) as $arr_ptrate) {
                    $arr_ptrates[] = $arr_ptrate["ID"];
                }

                if (!empty($arr_ptrates)) {
                    foreach ($GLOBALS["__TRAVELSOFT"]["RATE_STATE_BEFORE_UPDATE"][$arrID["ID"]]["UF_SERVICES_ID"] as $service_id) {
                        if (!in_array($service_id, $arr_rate["UF_SERVICES_ID"])) {
                            self::clearStoreByFilter("\\travelsoft\\booking\\datastores\PricesDataStore", array("UF_PTPR_ID" => $arr_ptrates, "UF_SERVICE_ID" => $service_id));
                        }
                    }
                }
            }
        }
        unset($GLOBALS["__TRAVELSOFT"]["RATE_STATE_BEFORE_UPDATE"]);
    }

    /**
     * Обработчик удаления класса авто
     */
    public static function onAfterClassAutoDelete($event) {
        $arrID = $event->getParameter("id");
        self::clearStoreByFilter("\\travelsoft\\booking\\datastores\TransfersRatesDataStore", array("UF_CLASS_AUTO" => $arrID["ID"]));
    }

    /**
     * Обработчик удаления тарифа трансфера
     */
    public static function onAfterTransfersRateDelete($event) {
        $arrID = $event->getParameter("id");
        self::clearStoreByFilter("\\travelsoft\\booking\\datastores\PTRatesDataStore", array("UF_RATE_CATEGORY_ID" => $arrID['ID']));
    }

    /**
     * Перепривязка свойства 'Отображать для услуг' у тарифов
     * @param int $serviceId
     */
    public static function clearServicesLinkPropertyInRates(int $serviceId) {
        $arRates = datastores\RatesDataStore::get(array('filter' => array("UF_SERVICES_ID" => array($serviceId)), "select" => array("ID", "UF_SERVICES_ID")));
        $GLOBALS["CSLIR_SERVICE_ID"] = $serviceId;
        $arUpdate = array();
        foreach ($arRates as $arRate) {
            $arUpdate[$arRate["ID"]] = array_filter($arRate["UF_SERVICES_ID"], function ($val) {
                return $val != $GLOBALS["CSLIR_SERVICE_ID"];
            });
        }
        if ($arUpdate) {
            foreach ($arUpdate as $rateId => $arServicesId) {
                datastores\RatesDataStore::save(array("UF_SERVICES_ID" => $arServicesId), $rateId);
            }
        }
        unset($GLOBALS["CSLIR_SERVICE_ID"]);
    }

    /**
     * Очистка хранилища по фильтру
     * @param string $storeDataClass
     * @param array $arFilter
     */
    public static function clearStoreByFilter(string $storeDataClass, array $arFilter) {
        $arElements = $storeDataClass::get(array("filter" => $arFilter, "select" => array("ID")));
        foreach ($arElements as $arElement) {
            $storeDataClass::delete($arElement["ID"]);
        }
    }

    ###########################################

    public static function getCitizenship(bool $withAllFields = false) {

        $result = null;

        $cache = new Cache(SITE_ID . "data_for_citizenship_" . intVal($withAllFields), "/travelsoft/booking/" . SITE_ID . "/data_for_citizenship", 360000000);

        if (empty($result = $cache->get())) {
            $result = $cache->caching(function () use ($cache, $withAllFields) {
                $result = array();

                foreach (datastores\CitizenshipDataStore::get(array("order" => ["UF_NAME" . self::getPostfixProperty() => "ASC"])) as $val) {
                    if ($withAllFields) {
                        $result[$val["ID"]] = $val;
                    } else {
                        $result[$val["ID"]] = $val["UF_NAME" . self::getPostfixProperty()];
                    }
                }

                $cache->setTagCache("highloadblock_" . self::getOpt("citizenship"));

                return $result;
            });
        }

        return $result;
    }

    /**
     * Возвращает массив полей по услуге по id
     * @param int $service_id
     * @return array|null
     */
    public static function getServiceById(int $service_id) {

        $cache = new Cache("data_for_service_" . $service_id, "/travelsoft/booking/data_for_services", 360000000);

        if (empty($arService = $cache->get())) {

            $arService = $cache->caching(function () use ($service_id, $cache) {

                $arService = self::getFromStore("services", array(
                            "filter" => array("ID" => $service_id)
                ));

                $cache->setTagCache("highloadblock_" . self::getOpt("services") . "_" . $service_id);

                return $arService;
            });
        }

        return $arService;
    }

    protected static function getPostfixProperty() {

        if (defined("POSTFIX_PROPERTY")) {
            return POSTFIX_PROPERTY;
        }

        return LANGUAGE_ID === "ru" ? "" : "_" . strtoupper(LANGUAGE_ID);
    }

    ############################
    # СИСТЕМА ОПОВЕЩЕНИЙ
    ############################

    /**
     * Отсылка письма об окончании квоты при бронировании
     * @param array $arr_fields
     */
    public static function quotaExpired(array $arr_fields) {

        \CEvent::Send("TRAVELSOFT_BOOKING", SITE_ID, $arr_fields, "N", self::getOpt("quota_expired_mail_template"));
    }

//
//    public static function checkQuotaByPeriod() {
//
//        // пул результатов объектов, которые не прошли проверку
//        // сгрупированы по поставщикам 
//        $pool_of_checkes = array();
//
//        $utc_timestamp = mktime(0, 0, 0, date("m"), date("j"), date("Y"));
//        $delta_timestamp = 14 * 86400;
//        // период проверки квот
//        $period = array($utc_timestamp, $utc_timestamp + $delta_timestamp);
//
//        // проверка размещений
//        $placements_data_store = new \travelsoft\booking\datastores\PlacementsDataStore(array(
//            "filter" => array("ACTIVE" => "Y"),
//            "select" => array("ID", "NAME", "PROPERTY_USER")
//        ));
//
//        if (!empty($placements_data_store->fetch())) {
//            $rooms_data_store = new datastores\ServicesDataStore(array(
//                "filter" => array("UF_IBLOCK_ELEMENT_ID" => array_keys($placements_data_store->fetch(array("ID")))),
//                "select" => array("ID", "UF_NAME", "UF_IBLOCK_ELEMENT_ID")
//            ));
//
//            if (!empty($rooms_data_store->fetch())) {
//
//                $quotas_data_store = new datastores\QuotasDataStore(array(
//                    "filter" => array("UF_SERVICE_ID" => array_keys($rooms_data_store->fetch(array("ID"))), "><UF_DATE" => $period),
//                    "select" => array("UF_DATE", "UF_SERVICE_ID"),
//                    "order" => array("UF_DATE" => "DESC")
//                ));
//
//                $arr_quotas_grouped = $quotas_data_store->Fetch(array("UF_SERVICE_ID", "UF_DATE"));
//
//                $arr_placements = $placements_data_store->fetch(array("ID"));
//
//                $arr_rooms_grouped_by_placements = $rooms_data_store->fetch(array("UF_IBLOCK_ELEMENT_ID", "ID"));
//
//                foreach ($arr_rooms_grouped_by_placements as $placement_id => $arr_rooms_data) {
//
//                    foreach ($arr_rooms_data as $room_id => $arr_data) {
//
//                        foreach ($arr_quotas_grouped[$room_id] as $arr_quotas_grouped_by_dates) {
//
//                            $timestamp = $utc_timestamp;
//
//                            $end_timestamp = $utc_timestamp + $delta_timestamp;
//                            while ($timestamp <= $end_timestamp) {
//
//                                if (!isset($arr_quotas_grouped_by_dates[$timestamp])) {
//
//                                    $pool_of_checkes[$arr_placements[$placement_id][0]["PROPERTY_USER_VALUE"]] = $arr_placements[$placement_id][0]["NAME"] . "[" . $arr_data[0]["UF_NAME"] . "]";
//                                    break;
//                                }
//                                $timestamp += 86400;
//                            }
//                        }
//                    }
//                }
//            }
//        }
//
//        // проверка санаториев
//        $sanatorium_data_store = new datastores\SanatoriumDataStore(array(
//            "filter" => array("ACTIVE" => "Y"),
//            "select" => array("ID", "NAME", "PROPERTY_USER")
//        ));
//
//        if (!empty($sanatorium_data_store->fetch())) {
//            $rooms_data_store = new datastores\ServicesDataStore(array(
//                "filter" => array("UF_IBLOCK_ELEMENT_ID" => array_keys($sanatorium_data_store->fetch(array("ID")))),
//                "select" => array("ID", "UF_NAME", "UF_IBLOCK_ELEMENT_ID")
//            ));
//
//            if (!empty($rooms_data_store->fetch())) {
//
//                $quotas_data_store = new datastores\QuotasDataStore(array(
//                    "filter" => array("UF_SERVICE_ID" => array_keys($rooms_data_store->fetch(array("ID"))), "><UF_DATE" => $period),
//                    "select" => array("UF_DATE", "UF_SERVICE_ID"),
//                    "order" => array("UF_DATE" => "DESC")
//                ));
//
//                $arr_quotas_grouped = $quotas_data_store->Fetch(array("UF_SERVICE_ID", "UF_DATE"));
//
//                $arr_sanatorium = $sanatorium_data_store->fetch(array("ID"));
//
//                $arr_rooms_grouped_by_sanatorium = $rooms_data_store->fetch(array("UF_IBLOCK_ELEMENT_ID", "ID"));
//
//                foreach ($arr_rooms_grouped_by_sanatorium as $sanatorium_id => $arr_rooms_data) {
//
//                    foreach ($arr_rooms_data as $room_id => $arr_data) {
//
//                        foreach ($arr_quotas_grouped[$room_id] as $arr_quotas_grouped_by_dates) {
//
//                            $timestamp = $utc_timestamp;
//
//                            $end_timestamp = $utc_timestamp + $delta_timestamp;
//                            while ($timestamp <= $end_timestamp) {
//
//                                if (!isset($arr_quotas_grouped_by_dates[$timestamp])) {
//
//                                    $pool_of_checkes[$arr_sanatorium[$placement_id][0]["PROPERTY_USER_VALUE"]][] = $arr_sanatorium[$placement_id][0]["NAME"] . "[" . $arr_data[0]["UF_NAME"] . "]";
//                                    break;
//                                }
//                                $timestamp += 86400;
//                            }
//                        }
//                    }
//                }
//            }
//        }
//
//        // проверка экскурсий
//        $excursions_data_store = new datastores\ExcursionsDataStore(array(
//            "filter" => array("ACTIVE" => "Y", "PROPERTY_IS_EXCURSION_TOUR_VALUE" => false),
//            "select" => array("ID", "NAME", "PROPERTY_USER_ID")
//        ));
//
//        if (!empty($excursions_data_store->fetch())) {
//            $child_excursions_data_store = new datastores\ServicesDataStore(array(
//                "filter" => array("UF_IBLOCK_ELEMENT_ID" => array_keys($excursions_data_store->fetch(array("ID")))),
//                "select" => array("ID", "UF_NAME", "UF_IBLOCK_ELEMENT_ID")
//            ));
//
//            if (!empty($child_excursions_data_store->fetch())) {
//
//                $quotas_data_store = new datastores\QuotasDataStore(array(
//                    "filter" => array("UF_SERVICE_ID" => array_keys($child_excursions_data_store->fetch(array("ID"))), "><UF_DATE" => $period),
//                    "select" => array("UF_DATE", "UF_SERVICE_ID"),
//                    "order" => array("UF_DATE" => "DESC")
//                ));
//
//                $arr_quotas_grouped = $quotas_data_store->Fetch(array("UF_SERVICE_ID", "UF_DATE"));
//
//                $arr_excursions = $excursions_data_store->fetch(array("ID"));
//
//                $arr_child_excursions_grouped_by_excursions = $child_excursions_data_store->fetch(array("UF_IBLOCK_ELEMENT_ID", "ID"));
//
//                foreach ($arr_child_excursions_grouped_by_excursions as $excursion_id => $arr_excur_data) {
//
//                    foreach ($arr_excur_data as $child_excur_id => $arr_data) {
//
//                        foreach ($arr_quotas_grouped[$child_excur_id] as $arr_quotas_grouped_by_dates) {
//
//                            $timestamp = $utc_timestamp;
//
//                            $end_timestamp = $utc_timestamp + $delta_timestamp;
//                            while ($timestamp <= $end_timestamp) {
//
//                                if (!isset($arr_quotas_grouped_by_dates[$timestamp])) {
//
//                                    $pool_of_checkes[$arr_excursions[$excursion_id][0]["PROPERTY_USER_ID"]][] = $arr_excursions[$excursion_id][0]["NAME"];
//                                    break;
//                                }
//                                $timestamp += 86400;
//                            }
//                        }
//                    }
//                }
//            }
//        }
//
//        // проверка экскурсионных туров
////        $excursionstours_data_store = new datastores\ExcursionsDataStore(array(
////            "filter" => array("ACTIVE" => "Y", "PROPERTY_IS_EXCURSION_TOUR_VALUE" => "Y"),
////            "select" => array("ID", "NAME", "PROPERTY_USER_ID")
////        ));
////
////        if (!empty($excursionstours_data_store->fetch())) {
////            $child_excursions_data_store = new datastores\ServicesDataStore(array(
////                "filter" => array("UF_IBLOCK_ELEMENT_ID" => array_keys($excursionstours_data_store->fetch(array("ID")))),
////                "select" => array("ID", "UF_NAME", "UF_IBLOCK_ELEMENT_ID")
////            ));
////
////            if (!empty($child_excursions_data_store->fetch())) {
////                
////                $arr_excursions = $excursionstours_data_store->fetch(array("ID"));
////                
////                $arr_child_excursions_grouped_by_excursions = $child_excursions_data_store->fetch(array("UF_IBLOCK_ELEMENT_ID", "ID"));
////
////                foreach ($arr_child_excursions_grouped_by_excursions as $exctours_id => $arr_child_exctours_data) {
////                    foreach ($arr_child_exctours_data as $child_exctour_id => $arr_child_exctour_data) {
////
////                        $arr_rate = current(datastores\RatesDataStore::get(array(
////                                    "filter" => array("UF_SERVICES_ID" => array($child_exctour_id)),
////                                    "select" => array("ID", "UF_SERVICES_ID", "UF_NAME")
////                        )));
////
////                        if (!empty($arr_rate)) {
////
////                            $quotas_data_store = new datastores\RatesQuotasDataStore(array(
////                                "filter" => array("UF_SERVICE_ID" => $child_exctour_id, "UF_RATE_ID" => $arr_rate["ID"]),
////                                "select" => array("UF_DATE", "ID"),
////                                "order" => array("UF_DATE" => "DESC")
////                            ));
////
////                            if ($quotas_data_store->fetch()) {
////                                $quotas_dates = array_keys($quotas_data_store->fetch(array("UF_DATE")));
////                                $timestamp = $utc_timestamp;
////
////                                $end_timestamp = $utc_timestamp + $delta_timestamp;
////                                while ($timestamp <= $end_timestamp) {
////
////                                    if (!isset($quotas_dates[$timestamp])) {
////
////                                        $pool_of_checkes[$arr_excursions[$exctours_id][0]["PROPERTY_USER_VALUE"]][] = $arr_child_exctour_data[0]["UF_NAME"] . "[" . $arr_rate["UF_NAME"] . "]";
////                                        break;
////                                    }
////                                    $timestamp += 86400;
////                                }
////                            }
////                        }
////                    }
////                }
////            }
////        }
//        include $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/include/classes/Logger.php";
//        $logger = new \travelsoft\rest\Logger();
//        $logger->write("START");
//        if (!empty($pool_of_checkes)) {
//
//            $arr_users_pool = array();
//            while ($arr_user = \CUser::GetList($by = "ID", $order = "DESC", array("ID" => array_keys($pool_of_checkes)))->Fetch()) {
//                $arr_users_pool[$arr_user["ID"]] = $arr_user;
//            }
//            foreach ($pool_of_checkes as $user_id => $arr_data) {
//
//                if (!isset($arr_users_pool[$user_id])) {
//                    $logger->write(serialize($arr_data));
//                }
//            }
//        }
//        $logger->write("END");
//    }

    public static function checkQuotas($class_name, $user_field) {
        $date_start_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y")) + 60 * 86400;
        $date_end_timestamp = $date_start_timestamp + 14 * 86400;

        // период проверки квот
        $period = array($date_start_timestamp, $date_end_timestamp);

        // пул проверок
        $pool_of_checks = [];

        // проверка объектов
        $class = "\\travelsoft\\booking\\datastores\\{$class_name}";
        $filter = array("ACTIVE" => "Y");
        if ($class_name === "ExcursionsDataStore") {
            $filter["PROPERTY_IS_EXCURSION_TOUR"] = false;
        }
        $data_store = new $class(array(
            "filter" => $filter,
            "select" => array("ID", "NAME", $user_field)
        ));

        if (!empty($data_store->fetch())) {
            $objects_data_store = new datastores\ServicesDataStore(array(
                "filter" => array("UF_IBLOCK_ELEMENT_ID" => array_keys($data_store->fetch(array("ID")))),
                "select" => array("ID", "UF_NAME", "UF_IBLOCK_ELEMENT_ID")
            ));

            if (!empty($objects_data_store->fetch())) {

                $quotas_data_store = new datastores\QuotasDataStore(array(
                    "filter" => array("UF_SERVICE_ID" => array_keys($objects_data_store->fetch(array("ID"))), "><UF_DATE" => $period),
                    "order" => array("UF_DATE" => "DESC")
                ));

                $arr_quotas_grouped = $quotas_data_store->fetch(array("UF_SERVICE_ID", "UF_DATE"));

                $arr_parent_objects = $data_store->fetch(array("ID"));

                $arr_objects_grouped_by_parents = $objects_data_store->fetch(array("UF_IBLOCK_ELEMENT_ID", "ID"));

                foreach ($arr_objects_grouped_by_parents as $parent_id => $arr_objects_data) {

                    foreach ($arr_objects_data as $object_id => $arr_data) {

                        $timestamp = $period[0];

                        $end_timestamp = $period[1];

                        $count_times = 0;

                        while ($timestamp <= $end_timestamp) {

                            if (!isset($arr_quotas_grouped[$object_id][$timestamp])) {

                                $count_times++;
                            }
                            $timestamp += 86400;
                        }

                        if ($count_times === (($period[1] - $period[0]) / 86400) + 1) {
                            $pool_of_checks[$arr_parent_objects[$parent_id][0]["{$user_field}_VALUE"]][] = $arr_data[0]["UF_NAME"] . "[" . $arr_parent_objects[$parent_id][0]["NAME"] . "]";
                        }
                    }
                }

                if (!empty($pool_of_checks)) {

                    self::sendNotification([
                        "context" => "квота",
                        "date_from" => date("d.m.Y", $period[0]),
                        "date_to" => date("d.m.Y", $period[1]),
                        "checks_pool" => $pool_of_checks
                    ]);
                }
            }
        }
    }

    /**
     * Проверка квот у объектов размещений
     * через 60 дней, начиная с сегоднешней, на 14 дней
     * 
     * @return void
     */
    public static function checkPlacementsQuotas() {

        self::checkQuotas("PlacementsDataStore", "PROPERTY_USER");
    }

    /**
     * Проверка квот у санаториев
     * через 60 дней, начиная с сегоднешней, на 14 дней
     * 
     * @return void
     */
    public static function checkSanatoriumQuotas() {

        self::checkQuotas("SanatoriumDataStore", "PROPERTY_USER");
    }

    /**
     * Проверка квот у объектов экскурсий
     * через 60 дней, начиная с сегоднешней, на 14 дней
     * 
     * @return void
     */
    public static function checkExcursionsQuotas() {

        self::checkQuotas("ExcursionsDataStore", "PROPERTY_USER_ID");
    }

    /**
     * Проверка квот у объектов экскурсионных туров
     * через 60 дней, начиная с сегоднешней, на 14 дней
     * 
     * @return void
     */
    public static function checkExcursiontoursQuotas() {
        $date_start_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y")) + 60 * 86400;
        $date_end_timestamp = $date_start_timestamp + 14 * 86400;

        // период проверки квот
        $period = array($date_start_timestamp, $date_end_timestamp);

        // пул проверок
        $pool_of_checks = [];

        // проверка размещений
        $class = "\\travelsoft\\booking\\datastores\\ExcursionsDataStore";
        $data_store = new $class(array(
            "filter" => array("ACTIVE" => "Y", "!PROPERTY_IS_EXCURSION_TOUR" => FALSE),
            "select" => array("ID", "NAME", "PROPERTY_USER_ID")
        ));

        if (!empty($data_store->fetch())) {

            $objects_data_store = new datastores\ServicesDataStore(array(
                "filter" => array("UF_IBLOCK_ELEMENT_ID" => array_keys($data_store->fetch(array("ID")))),
                "select" => array("ID", "UF_NAME", "UF_IBLOCK_ELEMENT_ID")
            ));


            if (!empty($objects_data_store->fetch())) {

                $rates_data_store = new datastores\RatesDataStore([
                    "filter" => [
                        "UF_SERVICES_ID" => \array_values(\array_keys($objects_data_store->fetch(["ID"])))
                    ],
                ]);

                if (!empty($rates_data_store->fetch())) {

                    $quotas_data_store = new datastores\RatesQuotasDataStore(array(
                        "filter" => array("UF_RATE_ID" => \array_values(\array_keys($rates_data_store->fetch(["ID"]))), "UF_SERVICE_ID" => array_keys($objects_data_store->fetch(array("ID"))), "><UF_DATE" => $period),
                        "order" => array("UF_DATE" => "DESC")
                    ));

                    $arr_quotas_grouped = $quotas_data_store->fetch(array("UF_SERVICE_ID", "UF_RATE_ID", "UF_DATE"));

                    $arr_parent_objects = $data_store->fetch(array("ID"));

                    $arr_rates_objects = $rates_data_store->fetch(array("ID"));

                    $arr_objects_grouped_by_parents = $objects_data_store->fetch(array("UF_IBLOCK_ELEMENT_ID", "ID"));

                    foreach ($arr_objects_grouped_by_parents as $parent_id => $arr_objects_data) {

                        foreach ($arr_objects_data as $object_id => $arr_data) {

                            foreach ($arr_quotas_grouped[$object_id] as $rate_id => $quotas_by_timestamp) {
                                $timestamp = $period[0];

                                $end_timestamp = $period[1];

                                $count_times = 0;

                                while ($timestamp <= $end_timestamp) {


                                    if (!isset($quotas_by_timestamp[$timestamp])) {

                                        $count_times++;
                                    }
                                    $timestamp += 86400;
                                }


                                if ($count_times === (($period[1] - $period[0]) / 86400) + 1) {

                                    $pool_of_checks[$arr_parent_objects[$parent_id][0]["PROPERTY_USER_ID_VALUE"]][] = $arr_parent_objects[$parent_id][0]["NAME"] . "[" . $arr_rates_objects[$rate_id][0]["UF_NAME"] . "]";
                                }
                            }
                        }
                    }

                    if (!empty($pool_of_checks)) {

                        self::sendNotification([
                            "context" => "квота",
                            "date_from" => date("d.m.Y", $period[0]),
                            "date_to" => date("d.m.Y", $period[1]),
                            "checks_pool" => $pool_of_checks
                        ]);
                    }
                }
            }
        }
    }

    public static function checkExcursionsPrice() {
        self::checkPrice("ExcursionsDataStore", "PROPERTY_USER_ID");
    }

    public static function checkPlacementsPrice() {
        self::checkPrice("PlacementsDataStore", "PROPERTY_USER");
    }

    public static function checkSanatoriumPrice() {
        self::checkPrice("SanatoriumDataStore", "PROPERTY_USER");
    }

    public static function checkTransfersPrice() {

        $date_start_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y")) + 60 * 86400;
        $date_end_timestamp = $date_start_timestamp + 14 * 86400;

        // период проверки квот
        $period = array($date_start_timestamp, $date_end_timestamp);

        // пул проверок
        $pool_of_checks = [];

        $dbUsers = \CUser::GetList(($by = "personal_country"), ($order = "desc"), [
                    "GROUPS_ID" => [self::getOpt("transfers_provider_group")]
                        ], [
                    "FIELDS" => ["ID"]
        ]);

        $arr_users_list_id = [];
        while ($user = $dbUsers->Fetch()) {
            $arr_users_list_id[] = $user["ID"];
        }

        if (!empty($arr_users_list_id)) {

            $rates_data_store = new datastores\TransfersRatesDataStore([
                "filter" => ["UF_USER_ID" => $arr_users_list_id]
            ]);

            $rates_grouped_by_users_transfers = $rates_data_store->fetch(["UF_USER_ID", "UF_TRANSFER", "ID"]);

            $ptr_data_store = new datastores\PTRatesDataStore([
                "filter" => [
                    "UF_RATE_CATEGORY_ID" => \array_values(\array_keys($rates_data_store->fetch(["ID"])))
                ]
            ]);

            $ptr_grouped_by_rates = $ptr_data_store->fetch(["UF_RATE_CATEGORY_ID", "ID"]);

            if (!empty($ptr_data_store->fetch())) {
                $prices_data_store = new datastores\PricesDataStore([
                    "filter" => [
                        "UF_PTPR_ID" => \array_values(\array_keys($ptr_data_store->fetch(["ID"]))),
                        "><UF_DATE" => $period
                    ]
                ]);

                $prices_grouped_by_ptr = $prices_data_store->fetch([
                    "UF_PTPR_ID", "UF_SERVICE_ID", "UF_DATE"
                ]);

                foreach ($rates_grouped_by_users_transfers as $user_id => $data_grouped_by_transfers) {

                    foreach ($data_grouped_by_transfers as $transfer_id => $data_grouped_by_rates) {
                        if ($transfer_id <= 0) {
                            continue;
                        }

                        $is_empty = true;
                        foreach ($data_grouped_by_rates as $rate_id => $rates_data) {

                            if (isset($ptr_grouped_by_rates[$rate_id])) {
                                $ptr_list_id = \array_values(\array_keys($ptr_grouped_by_rates[$rate_id]));
                                foreach ($ptr_list_id as $ptr_id) {
                                    if (isset($prices_grouped_by_ptr[$ptr_id])) {

                                        $is_empty = false;
                                        break;
                                    }
                                }
                            }

                            if (!$is_empty) {
                                break;
                            }
                        }

                        if ($is_empty) {
                            $transfer = current(transfers\Transfer::get(["filter" => ["ID" => $transfer_id]]));
                            $point_a = \CIBlockElement::GetByID(intval($transfer["UF_POINT_A"]))->Fetch();
                            $point_b = \CIBlockElement::GetByID(intval($transfer["UF_POINT_B"]))->Fetch();
                            $pool_of_checks[$user_id][] = "{$point_a["NAME"]} - {$point_b["NAME"]}";
                        }
                    }
                }
            }

            self::sendNotification([
                "context" => "цены",
                "date_from" => date("d.m.Y", $period[0]),
                "date_to" => date("d.m.Y", $period[1]),
                "checks_pool" => $pool_of_checks
            ]);
        }
    }

    public static function checkPrice($class_name, $user_field) {

        $date_start_timestamp = mktime(0, 0, 0, date("m"), date("d"), date("Y")) + 60 * 86400;
        $date_end_timestamp = $date_start_timestamp + 14 * 86400;

        // период проверки квот
        $period = array($date_start_timestamp, $date_end_timestamp);

        // пул проверок
        $pool_of_checks = [];

        // проверка объектов
        $class = "\\travelsoft\\booking\\datastores\\{$class_name}";
        $filter = array("ACTIVE" => "Y");

        $data_store = new $class(array(
            "filter" => $filter,
            "select" => array("ID", "NAME", $user_field)
        ));

        if (!empty($data_store->fetch())) {
            $objects_data_store = new datastores\ServicesDataStore(array(
                "filter" => array("UF_IBLOCK_ELEMENT_ID" => array_keys($data_store->fetch(array("ID")))),
                "select" => array("ID", "UF_NAME", "UF_IBLOCK_ELEMENT_ID")
            ));

            if (!empty($objects_data_store->fetch())) {

                $rates_data_store = new datastores\RatesDataStore([
                    "filter" => [
                        "UF_SERVICES_ID" => \array_values(\array_keys($objects_data_store->fetch(["ID"])))
                    ],
                ]);

                $rates_grouped_by_id = $rates_data_store->fetch(["ID"]);

                $rates_grouped_by_objects = [];
                foreach ($rates_grouped_by_id as $id => $arr_data) {

                    foreach ($arr_data[0]["UF_SERVICES_ID"] as $service_id) {

                        $rates_grouped_by_objects[$service_id][] = $id;
                    }
                }

                if (!empty($rates_data_store->fetch())) {

                    $ptr_data_store = new datastores\PTRatesDataStore([
                        "filter" => [
                            "UF_RATE_CATEGORY_ID" => \array_values(\array_keys($rates_grouped_by_id))
                        ]
                    ]);

                    $ptr_grouped_by_rates = $ptr_data_store->fetch(["UF_RATE_CATEGORY_ID", "ID"]);

                    if (!empty($ptr_data_store->fetch())) {

                        $prices_data_store = new datastores\PricesDataStore([
                            "filter" => [
                                "UF_SERVICE_ID" => \array_values(\array_keys($objects_data_store->fetch(["ID"]))),
                                "UF_PTPR_ID" => \array_values(\array_keys($ptr_data_store->fetch(["ID"]))),
                                "><UF_DATE" => $period
                            ]
                        ]);

                        $prices_grouped_by_objects = $prices_data_store->fetch([
                            "UF_SERVICE_ID", "UF_PTPR_ID", "UF_DATE"
                        ]);

                        $arr_objects_grouped_by_parents = $objects_data_store->fetch(array("UF_IBLOCK_ELEMENT_ID", "ID"));

                        $arr_parent_objects = $data_store->fetch(array("ID"));

                        foreach ($arr_objects_grouped_by_parents as $parent_id => $arr_objects_data) {

                            $objects_id = \array_values(\array_keys($arr_objects_data));
                            foreach ($objects_id as $object_id) {

                                foreach ($rates_grouped_by_objects[$object_id] as $rate_id) {

                                    $pt_list_id = \array_values(\array_keys($ptr_grouped_by_rates[$rate_id]));

                                    $count_pt_list_id = count($pt_list_id);

                                    $count_pt_list_id_for_compare = 0;

                                    foreach ($pt_list_id as $pt_id) {

                                        $timestamp = $period[0];

                                        $end_timestamp = $period[1];

                                        $count_times = 0;

                                        while ($timestamp <= $end_timestamp) {


                                            if (!isset($prices_grouped_by_objects[$object_id][$pt_id][$timestamp])) {

                                                $count_times++;
                                            }
                                            $timestamp += 86400;
                                        }

                                        if ($count_times === (($period[1] - $period[0]) / 86400) + 1) {

                                            $count_pt_list_id_for_compare++;
                                        }
                                    }

                                    if ($count_pt_list_id === $count_pt_list_id_for_compare) {

                                        $pool_of_checks[$arr_parent_objects[$parent_id][0]["{$user_field}_VALUE"]][] = $arr_parent_objects[$parent_id][0]["NAME"] . "[" . $rates_grouped_by_id[$rate_id][0]["UF_NAME"] . "]";
                                    }
                                }
                            }
                        }

                        if (!empty($pool_of_checks)) {

                            self::sendNotification([
                                "context" => "цены",
                                "date_from" => date("d.m.Y", $period[0]),
                                "date_to" => date("d.m.Y", $period[1]),
                                "checks_pool" => $pool_of_checks
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param array $arr_data
     * @return void
     */
    public static function sendNotification(array $arr_data) {
        $dbUsers = $GLOBALS["USER"]->GetList(($by = "personal_country"), ($order = "desc"), [
            "ID" => implode(" | ", \array_values(\array_keys($arr_data["checks_pool"])))
                ], [
            "FIELDS" => [
                "ID", "EMAIL"
            ]
        ]);

        $arr_users = [];
        while ($arr_user = $dbUsers->Fetch()) {
            $arr_users[$arr_user["ID"]] = $arr_user["EMAIL"];
        }

        foreach ($arr_data["checks_pool"] as $user_id => $arr_objects_name) {

            if ($arr_users[$user_id]) {

                \CEvent::Send("TRAVELSOFT_BOOKING", "s1", [
                    "EMAIL_TO" => $arr_users[$user_id],
                    "OBJECTS" => implode("<br>", $arr_objects_name),
                    "DATE_FROM" => $arr_data["date_from"],
                    "DATE_TO" => $arr_data["date_to"],
                    "CONTEXT" => $arr_data["context"]
                        ], "N", 101);
            }
        }
    }

    ############################
    ############################
    # Методы работы с рассадкой 
    ############################

    /**
     * Возвращает измененную исходную рассадку на основе текущей
     * @param array $arSourceAllocate
     * @param array $arCurrentAllocate
     */
    public static function getChangedSourceAllocate(array $arSourceAllocate, array $arCurrentAllocate) {
//dm($arCurrentAllocate);
        foreach (array_keys($arSourceAllocate) as $place_type) {

            $arSourceAllocate[$place_type]["adults"] -= $arCurrentAllocate[$place_type]["adults"];
            $arSourceAllocate[$place_type]["children"] -= $arCurrentAllocate[$place_type]["children"];
            $arSourceAllocate[$place_type]["children_age"] = array_slice($arSourceAllocate[$place_type]["children_age"], $arCurrentAllocate[$place_type]["children"]);
        }
//dm($arSourceAllocate);
        return $arSourceAllocate;
    }

    /**
     * Получение текущей рассадки на основе исходной полученной рассадки людей
     * @param array $arAllocate
     * @param array $arPeople
     */
    public static function getCurrentAllocate(array $arAllocate, array $arPeople) {

        $arCurrentAllocate = [
            "main" => [
                "adults" => 0,
                "children" => 0,
                "children_age" => []
            ],
            "additional" => [
                "adults" => 0,
                "children" => 0,
                "children_age" => []
            ]
        ];

        foreach ($arAllocate as $place_type => $arSubPeople) {

            if ($arPeople["adults"] > 0 && $arSubPeople["adults"] > 0) {

                if ($arSubPeople["adults"] >= $arPeople["adults"]) {
                    $arCurrentAllocate[$place_type]["adults"] = $arPeople["adults"];
                    $arPeople["adults"] = 0;
                } else {
                    $arCurrentAllocate[$place_type]["adults"] = $arSubPeople["adults"];
                    $arPeople["adults"] -= $arSubPeople["adults"];
                }
            }

            if ($arPeople["children"] > 0 && $arSubPeople["children"] > 0) {

                if ($arSubPeople["children"] >= $arPeople["children"]) {
                    $arCurrentAllocate[$place_type]["children"] = $arPeople["children"];
                    $arCurrentAllocate[$place_type]["children_age"] = array_slice($arSubPeople["children_age"], 0, $arPeople["children"]);
                    $arPeople["children"] = 0;
                } else {
                    $arCurrentAllocate[$place_type]["children"] = $arPeople["children"];
                    $arCurrentAllocate[$place_type]["children_age"] = array_slice($arSubPeople["children_age"], 0, $arPeople["children"]);
                    $arPeople["children"] -= $arSubPeople["children"];
                }
            }
        }

        return $arCurrentAllocate;
    }

    ##########################

    /**
     * Отправка json-строки
     * @global \CMain $APPLICATION
     * @param string $body
     */
    public static function sendJsonResponse(string $body) {
        global $APPLICATION;
        \header('Content-Type: application/json; charset=' . \SITE_CHARSET);
        $APPLICATION->RestartBuffer();
        echo $body;
        die();
    }

    public static function isAgent() {
        global $USER;
        return in_array(self::getOpt('agents_group_id'), $USER->GetUserGroupArray());
    }

}
