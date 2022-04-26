<?php

class SeatAvailability extends CBitrixComponent {

    public $request = null;

    public function prepareParameters() {

        if (!$this->arParams["DATE_FROM"]) {
            $this->arParams["DATE_FROM"] = strtotime(date('d.m.Y'));
            //throw new Exception("Seat Availability: date from parameter not set");
        }

        if (!$this->arParams["OBJECT_ID"]) {
            throw new Exception("Seat Availability: object id parameter not set");
        }

        if (!defined("POSTFIX_PROPERTY")) {

            if (LANGUAGE_ID === "ru") {
                $this->arParams["POSTFIX_PROPERTY"] = "";
            } else {
                $this->arParams["POSTFIX_PROPERTY"] = "_" . strtoupper(LANGUAGE_ID);
            }
        } else {
            $this->arParams["POSTFIX_PROPERTY"] = POSTFIX_PROPERTY;
        }
        
        Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools");

        $this->request = Bitrix\Main\Context::getCurrent()->getRequest();

        // save parameters for ajax request
        $_SESSION["travelsoft_seat_availability_component"] = [
            "OBJECT_ID" => $this->arParams["OBJECT_ID"],
            "DATE_FROM" => $this->arParams["DATE_FROM"],
            "__BOOKING_REQUEST" => $this->arParams["__BOOKING_REQUEST"]
        ];

        CJSCore::Init();
    }

    public function executeComponent() {

        $TSDateNow = strtotime(date('Y-m-d'));
        $this->prepareParameters();
        $this->arResult["IS_AJAX"] = $this->arParams['IS_AJAX'] === 'Y';
        if ($this->arResult["IS_AJAX"] && check_bitrix_sessid()) {
            $services = new \travelsoft\booking\datastores\ServicesDataStore([
               "filter" => [
                  "UF_IBLOCK_ELEMENT_ID" => $this->arParams["OBJECT_ID"],
           			"!UF_SERVICE_TYPE_NAME" => 7, // ИСКЛЮЧАЕМ ТРАНСФЕРЫ
              ],
              "select" => ["ID", "UF_NAME" . $this->arParams["POSTFIX_PROPERTY"]]
          ]);

            $this->arResult["DATA"] = [
                "HEAD" => [],
                "ROWS" => []
            ];
            if (!empty($services->fetch())) {
                $this->arResult["SERVICES"] = $arr_services = $services->fetch(["ID"]);
                dm($this->arResult["SERVICES"], false, true);
                $date_from_timestamp = MakeTimeStamp($this->arParams["DATE_FROM"]);
                if ($this->request->getPost("date_from") && ($request_date_from = MakeTimeStamp($this->request->getPost("date_from"))) > $date_from_timestamp) {
                    $date_from_timestamp = $request_date_from;
                    $this->arResult["DISPLAY_DATE_FROM"] = htmlspecialchars($this->request->getPost("date_from"));
                    
                }
               // if (MakeTimeStamp(date('d.m.Y'))>$date_from_timestamp -(7 * 86400)) $date_from_timestamp  = MakeTimeStamp(date('d.m.Y'));
               // else $date_from_timestamp = $date_from_timestamp -(7 * 86400);
                $startday = $date_from_timestamp;
                $date_from_timestamp = $date_from_timestamp -(7 * 86400);
                $date_to_timestamp = $date_from_timestamp + (90 * 86400);
                $quotas = new \travelsoft\booking\datastores\QuotasDataStore([
                    "filter" => [
                        "UF_SERVICE_ID" => array_values(array_keys($arr_services)),
                        "><UF_DATE" => [$date_from_timestamp, $date_to_timestamp]
                    ],
                    "order" => ["UF_DATE" => "DESC"]
                ]);

                $arr_gquotas = $quotas->fetch(["UF_SERVICE_ID", "UF_DATE"]);
                
                $prices = new \travelsoft\booking\datastores\PricesDataStore([
                    "filter" => [
                        "UF_SERVICE_ID" => array_values(array_keys($arr_services)),
                        "><UF_DATE" => [$date_from_timestamp, $date_to_timestamp]
                    ],
                    "order" => ["UF_DATE" => "DESC"]
                ]);
                $arr_gprices = $prices->fetch(["UF_SERVICE_ID", "UF_DATE"]);
                
                $pricesnoarrival = new \travelsoft\booking\datastores\PricesDataStore([
                    "filter" => [
                        "UF_SERVICE_ID" => array_values(array_keys($arr_services)),
                        "!UF_NO_ARRIVALS"=>false,
                        "><UF_DATE" => [$date_from_timestamp, $date_to_timestamp]
                    ],
                    "order" => ["UF_DATE" => "DESC"]
                ]);
                $arr_gpricesnoarrival = $pricesnoarrival->fetch(["UF_SERVICE_ID", "UF_DATE"]);
                foreach ($arr_gpricesnoarrival as $service_id=>$tmpval) {
                    foreach ($tmpval as $timestamp=>$tmpval2) {
                        if (count($arr_gprices[$service_id][$timestamp])!=count($tmpval2)) unset($arr_gpricesnoarrival[$service_id][$timestamp]);
                    }
                }
                
                $this->arResult['CURRENT'] = $startday;
                $this->arResult['NOARRIVAL'] = $arr_gpricesnoarrival;
                //$this->arResult['ALLPRICES'] = $arr_gprices;                
                $timestamp = $date_from_timestamp;// -(7 * 86400);
                $duration = ($this->arParams["__BOOKING_REQUEST"]["date_to"] - $this->arParams["__BOOKING_REQUEST"]["date_from"])/86400;
                
                while ($timestamp <= $date_to_timestamp) {
                    $this->arParams["__BOOKING_REQUEST"]["date_from"] = $timestamp;
                    $this->arParams["__BOOKING_REQUEST"]["date_to"] = $timestamp + (86400 * $duration);
                    $this->arParams["DATA"]["LINKS"][$timestamp] = htmlentities(getCalculateDetailLink("", $this->arParams["__BOOKING_REQUEST"], ["scroll-to-sp" => "Y"]));
                    
                    $this->arResult["DATA"]["HEAD"][date("m", $timestamp)]["month_name"] = FormatDate("f", $timestamp);
                    $this->arResult["DATA"]["HEAD"][date("m", $timestamp)]["days"][$timestamp] = "<small>" . FormatDate("D", $timestamp) . "</small> " . date("d", $timestamp);
                    foreach ($arr_services as $arr_service) {
                        if (isset($arr_gquotas[$arr_service[0]["ID"]][$timestamp]) && $timestamp>=$date_from_timestamp && isset($arr_gprices[$arr_service[0]["ID"]][$timestamp])) {

                            $sale = $arr_gquotas[$arr_service[0]["ID"]][$timestamp][0]["UF_QUOTE"] - $arr_gquotas[$arr_service[0]["ID"]][$timestamp][0]["UF_SOLD_NUMBER"];

                            $gquote = $arr_gquotas[$arr_service[0]["ID"]][$timestamp][0];
                            if ($sale < 0 || $gquote["UF_STOP"] > 0) {
                                $sale = 0;
                            } elseif (!empty($gquote['UF_RELEASE_PERIOD'])) {  // проверка Релиз периода
                                $releasePeriod = $gquote['UF_RELEASE_PERIOD'];
                                $TSReleasePeriod = strtotime("-$releasePeriod day", $gquote['UF_DATE']);
                                if ($TSDateNow >= $TSReleasePeriod) {
                                    $sale = 0;
                                }
                            }

                            $this->arResult["DATA"]["ROWS"][$arr_service[0]["ID"]][date("m", $timestamp)][$timestamp] = $sale;
                        } else {
                            $this->arResult["DATA"]["ROWS"][$arr_service[0]["ID"]][date("m", $timestamp)][$timestamp] = 0;
                        }
                    }
                    $timestamp += 86400;
                }
            }
        }

        $this->includeComponentTemplate();
    }

}
