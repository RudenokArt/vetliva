<?php

/**
 * @author dimabresky
 */
class GuideFilterByOrders extends CBitrixComponent {

    public function executeComponent() {

        if (Bitrix\Main\Loader::includeModule("travelsoft.booking.dev.tools")) {

            $response = json_decode(\travelsoft\booking\Gateway::getOrderList(array(
                        "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                        "params" => array(
                            "token" => $_SESSION["__TRAVELSOFT"]["TOKEN"],
                            "paging" => array("page" => 1, "size" => 999999),
                            "filter" => array(
                                "createdate" => array(),
                                "tourdate" => array(),
                                "turist" => ""
                            ),
                            "sort" => array("tourdate" => "desc")
                        )
                    )), true);
            
            $arr_filter = array("ID" => -1);
            
            if ($response["result"]) {
                $result = $response["result"];
                $arr_cities_id_list = array();
                if ($result["List"]) {
                    foreach ($result["List"] as $arr_item) {
                        if (!empty($arr_item["cities"])) {
                            $arr_cities_id_list = array_merge($arr_cities_id_list, $arr_item["cities"]);
                        }
                    }
                    if (!empty($arr_cities_id_list)) {
                        $arr_filter["ID"] = array_values(array_unique($arr_cities_id_list));
                    }
                }
            }
            
            return $arr_filter;
            
        } else {
            ShowError("Модуль travelsoft.booking.dev.tools не найден");
        }
    }

}
