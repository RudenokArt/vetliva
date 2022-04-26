<?php

/*  CrossSale  */

class TravelsoftCrossSale extends CBitrixComponent {

    /**
     * @var \Bitrix\Main\HttpRequest
     */
    protected $_request = null;

    /**
     * @var int
     */
    protected $_object = null;

    /**
     * @var int
     */
    protected $_city_of_object = null;

    /**
     * Точка отправления трансфера
     * по умолчанию Аэропорт "Минск-2"
     * @var int
     */
    protected $_transfer_departure_point_id = 3608;
    protected $_iblocks_of_departure_points = [
        5, // Города и населенные пункты
        50 // Аэропорты и ЖД вокзалы
    ];

    public function prepareParameters() {

        Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");
        Bitrix\Main\Loader::includeModule("iblock");

        $this->_request = \Bitrix\Main\Context::getCurrent()->getRequest();

        $arErrors = [];

        $this->arParams["SERVICE_ID"] = intVal($this->arParams["SERVICE_ID"]);
        if ($this->arParams["SERVICE_ID"] <= 0) {
            $arErrors[] = "Not set service id parameter.";
        }

        $this->arParams["ADULTS"] = intVal($this->arParams["ADULTS"]);
        if ($this->arParams["ADULTS"] < 0) {
            $arErrors[] = "Not set adults parameter.";
        }

        $this->arParams["CHILDREN"] = intVal($this->arParams["CHILDREN"]);

        if (!isset($this->arParams["DATE_FROM"])) {
            $arErrors[] = "Not set date from parameter.";
        }

        if (!isset($this->arParams["DATE_TO"])) {
            $arErrors[] = "Not set date to parameter";
        }

        if (!strlen($this->arParams["TYPE"])) {
            $arErrors[] = "Not set type parameter.";
        }

        if (!empty($arErrors)) {
            throw new Exception(implode("<br>", $arErrors));
        }
    }

    protected function _objectDetection() {

        $arService = current(travelsoft\booking\datastores\ServicesDataStore::get([
                    "filter" => ["ID" => $this->arParams["SERVICE_ID"]],
                    "select" => ["ID", "UF_IBLOCK_ELEMENT_ID"]
        ]));

        if (@$arService["ID"] <= 0) {
            throw new Exception("service not found");
        }

        $object = \CIBlockElement::GetByID($arService["UF_IBLOCK_ELEMENT_ID"])->GetNextElement();

        if (!$object) {
            throw new Exception("service parent object not found");
        }

        $fields = $object->GetFields();
        $properties = $object->GetProperties();

        $this->_object = $fields;

        if (@$properties["CITY"]["VALUE"] > 0) {
            $this->_city_of_object = $properties["CITY"]["VALUE"];
        } elseif (@$properties["TOWN"]["VALUE"] > 0) {
            $this->_city_of_object = $properties["TOWN"]["VALUE"];
        }
    }

    protected function _searchCrossSaleOffers() {

        $method = "_searchFor" . ucfirst($this->arParams["TYPE"]) . "CrossSaleOffers";
        $this->$method();
    }

    protected function _searchForExcursionsCrossSaleOffers() {

        $request = $this->_getPlacementsRequest();
        $arr_calculation = $this->_getPlacementsPriceCalculation($request);

        // extract and find min
        foreach ($arr_calculation as $object_id => $services_data) {
            $min = exp(10);
            $arr_min_data = [];
            foreach ($services_data as $service_id => $rates_data) {
                foreach ($rates_data as $rate_id => $price_data) {
                    if (\travelsoft\booking\Utils::convertCurrency($price_data["PRICE"], $price_data["CURRENCY_ID"]) <= $min) {
                        $min = $price_data["PRICE"];
                        $arr_min_data = [
                            "rate_id" => $rate_id,
                            "object_id" => $object_id,
                            "service_id" => $service_id,
                            "price" => $price_data["PRICE"],
                            "currency_id" => $price_data["CURRENCY_ID"],
                            "date_from" => $request["date_from"],
                            "date_to" => $request["date_to"],
                            "date_from" => $request["date_from"],
                            "adults" => $request["adults"],
                            "children" => $request["children"],
                            "children_age" => $request["children_age"],
                            "duration" => $price_data["DURATION"]
                        ];
                    }
                }
            }
            if (!empty($arr_min_data)) {

                $this->_setPlacementsOffers($arr_min_data);
            }
        }

        uasort($this->arResult["PLACEMENTS_OFFERS"], function ($a, $b) {
            if ($a['price'] == $b['price']) {
                return 0;
            }

            return $a['price'] > $b['price'] ? 1 : -1;
        });
        $this->arResult["PLACEMENTS_OFFERS"] = array_values($this->arResult["PLACEMENTS_OFFERS"]);
        unset($request["id"]);
        $request["city_id"] = [$this->_city_of_object];
        $this->arResult["DETAIL_LINK"] = $this->_getCalculateDetailLink("/tourism/where-to-stay/", $request);
    }

    protected function _setPlacementsOffers(array $data) {

        $placement = current(travelsoft\booking\datastores\PlacementsDataStore::get(["filter" => [
                        "ID" => $data["object_id"],
                        "select" => ["ID", "NAME"]
        ]]));

        $service = current(\travelsoft\booking\datastores\ServicesDataStore::get([
                    "filter" => ["ID" => $data["service_id"]],
                    "select" => ["ID", "UF_NAME" . POSTFIX_PROPERTY, "UF_PICTURES"]
        ]));

        $rate = current(\travelsoft\booking\datastores\RatesDataStore::get([
                    "filter" => ["ID" => $data["rate_id"]],
                    "select" => ["ID", "UF_NAME" . POSTFIX_PROPERTY]
        ]));

        // формирование параметров добавления в корзину
        $add2cart = [
            "service_id" => $data["service_id"],
            "rate_id" => $data["rate_id"],
            "adults" => $data["adults"],
            "children" => $data["children"],
            "children_age" => $data["children_age"],
            "can_buy" => true,
            "date_from" => $data["date_from"],
            "date_to" => $data["date_to"],
            "price" => $data["price"],
            "currency" => $data["currency_id"],
            "type" => "placements",
            "duration" => $data["duration"]
        ];

        $sbi = serialize(new \travelsoft\booking\placements\BasketItem($add2cart));

        $this->arResult["PLACEMENTS_OFFERS"][] = [
            "placement" => [
                "name" => $placement["NAME"]
            ],
            "service" => [
                "name" => $service["UF_NAME" . POSTFIX_PROPERTY],
                "img_src" => $this->_resize($service["UF_PICTURES"][0])
            ],
            "rate" => [
                "name" => $rate["UF_NAME" . POSTFIX_PROPERTY]
            ],
            "price" => $data["price"],
            "price_formatted" => \travelsoft\booking\Utils::convertCurrency($data["price"], $data["currency_id"]),
            "add2cart" => \travelsoft\booking\Encoder::encode(array(
                "add2cart" => $sbi,
                "hash" => \travelsoft\booking\Encoder::hash(
                        $sbi, \travelsoft\booking\Utils::getOpt("salt")
        )))
        ];
    }

    protected function _getPlacementsPriceCalculation(array $request) {
        return travelsoft\booking\Utils::getPriceCalculation([
                    "request" => new \travelsoft\booking\placements\Request($request),
                    "type" => "placements",
                    "mp" => false
        ]);
    }

    protected function _getPlacementsRequest() {

        $arr_placements_id = [];
        $count = 0;
        foreach (travelsoft\booking\datastores\PlacementsDataStore::get([
            "filter" => ["PROPERTY_TOWN" => $this->_city_of_object],
            "select" => ["ID"]
        ]) as $placement) {
            $arr_placements_id[] = $placement["ID"];
        }

        return [
            'id' => $arr_placements_id,
            'date_from' => $this->arParams["DATE_FROM"] - 86400,
            'date_to' => $this->arParams["DATE_FROM"] + 86400,
            'adults' => $this->arParams["ADULTS"],
            'children' => $this->arParams["CHILDREN"],
            'children_age' => $this->arParams["CHILDREN_AGE"],
        ];
    }

    protected function _searchForPlacementsCrossSaleOffers() {

        $this->_setTransfersDeparturePoints();
        $this->_setTransfersOffers();
    }

    protected function _searchForSanatoriumCrossSaleOffers() {

        $this->_searchForPlacementsCrossSaleOffers();
    }

    protected function _getTransferRequest(int $pointA, bool $roundtrip = false) {

        return [
            "point_A" => $pointA,
            "point_B" => $this->_object["ID"],
            "adults" => $this->arParams["ADULTS"] + $this->arParams["CHILDREN"],
            "date_from" => $this->arParams["DATE_FROM"],
            "date_to" => $this->arParams["DATE_TO"],
            "roundtrip" => $roundtrip
        ];
    }

    protected function _getTransfersPriceCalculation(array $request, $mp = false) {

        $arCalculations = travelsoft\booking\Utils::getPriceCalculation([
                    "request" => new \travelsoft\booking\transfers\Request($request),
                    "type" => "transfers",
                    "mp" => $mp
        ]);

        // если трансферов не найдено для конкретного объекта
        // то ищем трансферы в населенный пункт оъбекта 
        if (empty($arCalculations) && $this->_city_of_object > 0) {

            $request["point_B"] = $this->_city_of_object;

            $arCalculations = travelsoft\booking\Utils::getPriceCalculation([
                        "request" => new \travelsoft\booking\transfers\Request($request),
                        "type" => "transfers",
                        "mp" => $mp
            ]);
        }

        return $arCalculations;
    }

    protected function _setTransfersDeparturePoints() {

        if (!is_array($this->_request->get('crossale_transfers'))) {

            foreach ($this->_iblocks_of_departure_points as $iblock_id) {

                $db_points = \CIBlockElement::GetList(false, ['!ID' => [$this->_object['ID'], $this->_city_of_object], 'IBLOCK_ID' => $iblock_id, 'ACTIVE' => 'Y', 'PROPERTY_CROSSALE_DEP_POINT_VALUE' => 'Y'], false, false);

                while ($point = $db_points->GetNextElement()) {

                    $fields = $point->GetFields();
                    $properties = $point->GetProperties();

                    if (POSTFIX_PROPERTY !== '') {
                        $this->arResult["TRANSFERS_DEPARTURE_POINTS"][$fields['ID']] = ['NAME' => $properties['NAME' . POSTFIX_PROPERTY]['VALUE'], 'PRICE' => '', 'SELECTED' => false];
                    } else {
                        $this->arResult["TRANSFERS_DEPARTURE_POINTS"][$fields['ID']] = ['NAME' => $fields['NAME'], 'PRICE' => '', 'SELECTED' => false];
                    }
                }
            }

            $for_delete = [];

            foreach ($this->arResult["TRANSFERS_DEPARTURE_POINTS"] as $id => &$data) {

                try {
                    $calculations = $this->_getTransfersPriceCalculation($this->_getTransferRequest($id), false);

                    if (empty($calculations)) {
                        $for_delete[] = $id;
                    } else {

                        $arPrice = travelsoft\booking\Utils::_searchPriceFromCalculationData($calculations);
                        $data['PRICE'] = travelsoft\booking\Utils::convertCurrency($arPrice['PRICE'], $arPrice['CURRENCY_ID']);
                        $this->arResult['DETAIL_LINK_FOR_DEPARTURE_POINTS'][$id] = $this->_getCalculateDetailLink("/tourism/transfer/", $this->_getTransferRequest($id), ['scroll-to-sp' => 'Y']);
                    }
                } catch (Exception $ex) {

                    $for_delete[] = $id;
                }
            }

            foreach ($for_delete as $id) {
                unset($this->arResult["TRANSFERS_DEPARTURE_POINTS"][$id]);
            }
        }
    }

    protected function _setTransfersOffers() {

        $http_request = $this->_request->get('crossale_transfers');

        if (@$http_request['from'] > 0) {
            $this->_transfer_departure_point_id = $http_request['from'];
        }

        $isRoundtrip = @$http_request['roundtrip'] === 'Y';

        $request = $this->_getTransferRequest($this->_transfer_departure_point_id, $isRoundtrip);

        $arCalculations = $this->_getTransfersPriceCalculation($request);

        // тайтл маршрута
        $pointATitle = $this->_getPointTitle($request['point_A']);
        $pointBTitle = $this->_getPointTitle($request['point_B']);


        $this->arResult["TRANSFERS_OFFERS"] = [];

        $arClassAuto = [];
        foreach (\travelsoft\booking\datastores\ClassAutoDataStore::get([
            "filter" => ["ID" => array_keys($arCalculations)]
        ]) as $class_auto) {
            $arClassAuto[$class_auto["ID"]] = $class_auto;
        }

        // формируем массив данных для вывода
        $arProviders = [];
        foreach ($arCalculations as $class_auto_id => $data_grouped_by_services) {

            // ищем минимальную цену среди классов авто
            $min = 999999999;
            $service__id = $rate_id = 0;
            $currency = null;
            $user_id = null;
            foreach ($data_grouped_by_services as $service_id => $data) {

                if ($data["PRICE"] < $min) {
                    $min = $data["PRICE"];
                    $service__id = $service_id;
                    $currency = $data["CURRENCY_ID"];
                    $rate_id = $data["RATE_ID"];
                    $user_id = $data["USER_ID"];
                }
            }

            if (!isset($arProviders[$user_id])) {
                $dbProvider = \CUser::GetList(($by = "personal_country"), ($order = "desc"), array(
                            "ID" => $user_id
                                ), array(
                            "SELECT" => array("UF_*"),
                            "FIELDS" => array("*")
                        ))->Fetch();
                $arProviders[$dbProvider["ID"]] = $dbProvider;
            }

            $this->arResult["TRANSFERS_OFFERS"][$class_auto_id] = \array_merge($arClassAuto[$class_auto_id], [
                "PRICE" => $min,
                "CURRENCY" => $currency,
                "CLASS_NAME" => $arClassAuto[$class_auto_id]["UF_NAME" . POSTFIX_PROPERTY],
                "PICTURE" => $this->_resize(@$arClassAuto[$class_auto_id]["UF_PICTURES"][0]),
                "PRICE_FORMATTED" => travelsoft\booking\Utils::convertCurrency($min, $currency),
                "PROVIDER" => $arProviders[$user_id]
            ]);


            // формирование параметров добавления в корзину
            $add2cart = [
                "service_id" => $service__id,
                "rate_id" => $rate_id,
                "adults" => $request["adults"],
                "can_buy" => true,
                "point_A" => $request["point_A"],
                "point_B" => $request["point_B"],
                "date_from" => $request["date_from"],
                "date_to" => $request["date_to"],
                "price" => $min,
                "currency" => $currency,
                "type" => "transfers",
                "roundtrip" => $isRoundtrip,
                "for_spot_payment" => $arProviders[$user_id]["UF_FOR_SPOT_PAYMENT"] === 1
            ];

            $sbi = serialize(new \travelsoft\booking\transfers\BasketItem($add2cart));
            $this->arResult["TRANSFERS_OFFERS"][$class_auto_id]['ROUTE'] = $pointATitle . " - " . $pointBTitle;
            $this->arResult["TRANSFERS_OFFERS"][$class_auto_id]["ADD2CART"] = \travelsoft\booking\Encoder::encode(array(
                        "add2cart" => $sbi,
                        "hash" => \travelsoft\booking\Encoder::hash(
                                $sbi, \travelsoft\booking\Utils::getOpt("salt")
            )));
        }

        if ((!isset($this->arResult["TRANSFERS_OFFERS"]) || empty($this->arResult["TRANSFERS_OFFERS"])) && (isset($this->arResult["TRANSFERS_DEPARTURE_POINTS"]) && !empty($this->arResult["TRANSFERS_DEPARTURE_POINTS"]))) {
            $this->_transfer_departure_point_id = key($this->arResult["TRANSFERS_DEPARTURE_POINTS"]);
            $this->_setTransfersOffers();
        } elseif (isset($this->arResult["TRANSFERS_DEPARTURE_POINTS"][$this->_transfer_departure_point_id])) {
            $this->arResult["TRANSFERS_DEPARTURE_POINTS"][$this->_transfer_departure_point_id]['SELECTED'] = true;
        }
    }

    protected function _resize($img_id) {
        $arPic = \CFile::ResizeImageGet($img_id, array('width' => 500, 'height' => 400), BX_RESIZE_IMAGE_EXACT, true);
        return (string) $arPic["src"];
    }

    protected function _getPointTitle(int $point_id) {
        $point = \CIBlockElement::GetByID($point_id)->GetNextElement();

        $pointFields = $point->GetFields();
        $pointProperties = $point->GetProperties();

        $pointTitle = $pointFields["NAME"];

        if (POSTFIX_PROPERTY !== "") {
            $pointTitle = $pointProperties["NAME" . POSTFIX_PROPERTY]["VALUE"];
        }

        return $pointTitle;
    }

    /**
     * @param string $link
     * @param array $bookingRequest
     * @param array $additionalParams
     * @param string $anchor
     * @return string
     */
    protected function _getCalculateDetailLink($link, array $bookingRequest, array $additionalParams = null, string $anchor = null) {

        $arUri = null;

        if (!empty($bookingRequest)) {

            if (!empty($bookingRequest["city_id"]) && is_array($bookingRequest["city_id"])) {
                $arUri[] = __getArrayUriQueryString("id", $bookingRequest["city_id"]);
            } elseif (!empty($bookingRequest["id"]) && is_array($bookingRequest["id"])) {
                $arUri[] = __getArrayUriQueryString("id", $bookingRequest["id"]);
            }

            if ($bookingRequest["date_from"]) {
                $arUri[] = "booking[date_from]=" . $bookingRequest["date_from"];
            }

            if ($bookingRequest["date_to"]) {
                $arUri[] = "booking[date_to]=" . $bookingRequest["date_to"];
            }

            if ($bookingRequest["adults"]) {
                $arUri[] = "booking[adults]=" . $bookingRequest["adults"];
            }

            if ($bookingRequest["point_A"]) {
                $arUri[] = "booking[point_A]=" . $bookingRequest["point_A"];
            }

            if ($bookingRequest["point_B"]) {
                $arUri[] = "booking[point_B]=" . $bookingRequest["point_B"];
            }

            if ($bookingRequest["roundtrip"] == "Y") {
                $arUri[] = "booking[roundtrip]=" . $bookingRequest["roundtrip"];
            }

            if ($bookingRequest["children"]) {
                $arUri[] = "booking[children]=" . $bookingRequest["children"];
            }

            if (!empty($bookingRequest["children_age"]) && is_array($bookingRequest["children_age"])) {
                $arUri[] = __getArrayUriQueryString("children_age", $bookingRequest["children_age"]);
            }
        }

        if (!empty($additionalParams)) {
            foreach ($additionalParams as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $kkey => $vval) {
                        $arUri[] = $key . "[" . $kkey . "]=" . $vval;
                    }
                } else {
                    $arUri[] = $key . "=" . $val;
                }
            }
        }

        $result = $arUri ? $link . "?" . implode("&", $arUri) : $link;

        return $anchor ? $result . "#" . $anchor : $result;
    }

    public function dispatching() {
        if (in_array($this->arParams["TYPE"], ["placements", "sanatorium"])) {

            if (in_array("transfers", $this->arParams["BASKET_SERVICES_TYPES"]) || in_array("excursions", $this->arParams["BASKET_SERVICES_TYPES"])) {

                return false;
            }
            $this->arResult["file_name"] = "transfers";
        } elseif ($this->arParams["TYPE"] === "excursions") {

            if (in_array("placements", $this->arParams["BASKET_SERVICES_TYPES"])) {

                return false;
            }
            $this->arResult["file_name"] = "placements";
        }

        return true;
    }

    public function executeComponent() {

        try {

            $this->prepareParameters();

            if (!$this->dispatching()) {
                return;
            }

            if ($this->_request->isAjaxRequest()) {

                $this->_objectDetection();
                $this->_searchCrossSaleOffers();
                echo json_encode($this->arResult);
                die;
            } else {
                // make arParams for ajax component
                unset($_SESSION['TS_COM_PARAMS_' . $this->arParams["TYPE"]]);
                foreach ($this->arParams as $key => $val) {

                    $tilde = substr($key, 0, 1);

                    if ($tilde === "~" && $key !== "~COMPONENT_TEMPLATE") {
                        $_SESSION['TS_COM_PARAMS_' . $this->arParams["TYPE"]][substr($key, 1)] = $val;
                    }
                }
            }

            $this->IncludeComponentTemplate();
        } catch (\Exception $ex) {
            \ShowError($ex->getMessage());
        }
    }

}
