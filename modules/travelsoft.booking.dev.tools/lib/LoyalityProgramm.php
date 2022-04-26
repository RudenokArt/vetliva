<?php

namespace travelsoft\booking;

/**
 * Программа лояльности
 *
 * @author dimabresky
 */
class LoyalityProgramm {

    const SCALE_TOTAL_SUM = 6250;
    const NOT_USE_LOYALITY_FOR_USER_GROUP = 23;

    /**
     * @return array
     */
    public static function toDeterminate() {

        self::clear();
        // выясняем статус клиента по программе лояльности
        $order_list_response = json_decode(\travelsoft\booking\Gateway::getOrderList(array(
                    "url" => \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "tsmo_url"),
                    "params" => array(
                        "token" => $_SESSION["__TRAVELSOFT"]["TOKEN"],
                        "paging" => [
                            "page" => 1,
                            "size" => 999999999
                        ],
                        "filter" => [
                            "createdate" => ["01.09.2019", date("d.m.Y", time())],
                            "tourdate" => [],
                            "turist" => ""
                        ],
                        "sort" => ["tourdate" => "desc"]
                    )
                )), true);

        if ($order_list_response["result"]) {
            $result = $order_list_response["result"];

            if ($result["List"]) {
                $total_orders_sum = 0;
                foreach ($result["List"] as $order) {
                    if (isset($order['paid']) && $order['paid']['BYN']) {
                        $total_orders_sum += $order['paid']['BYN'];
                    }
                }

                $discount = 0;
                if ($total_orders_sum >= 3000 && $total_orders_sum < 5000) {
                    $discount = 3;
                } elseif ($total_orders_sum >= 5000) {
                    $discount = 5;
                }

                $_SESSION['__TRAVELSOFT']["LOYALITY"] = [
                    'discount' => $discount, // в процентах
                    'total_orders_sum' => $total_orders_sum // белорусских рублях
                ];
            }
        }

        return $_SESSION['__TRAVELSOFT']["LOYALITY"];
    }

    /**
     * @param \travelsoft\booking\Basket $basket
     * @return \travelsoft\booking\Basket
     */
    public static function apply($basket) {

        $basket_items = [];
        while ($fetch = $basket->fetch()) {
            $basket_items[] = self::applyForBasketItem($fetch['item']->getPropertiesLikeArray());
        }

        if (!empty($basket_items)) {
            $basket->reset($basket_items);
        }

        return $basket;
    }

    /**
     * @global CUser $USER
     * @param array $arr_basket_item_fields
     * @return array
     */
    public static function applyForBasketItem(array $arr_basket_item_fields) {

        $discount = self::getDiscount();

        if (!$arr_basket_item_fields['loyality_applyed'] && self::canUseLoyality()) {
            $price = $arr_basket_item_fields['price'];

            if (!empty($arr_basket_item_fields['discount'])) {

                foreach ($arr_basket_item_fields['discount'] as $discount_item_price) {
                    $price -= $discount_item_price;
                }
            }

            if ($price > 0) {
                $arr_basket_item_fields["discount"][] = $price * $discount / 100;
                $arr_basket_item_fields['loyality_applyed'] = true;
            }
        }


        return $arr_basket_item_fields;
    }

    /**
     * @return int
     */
    public static function getDiscount() {
        return isset($_SESSION['__TRAVELSOFT']) && isset($_SESSION['__TRAVELSOFT']["LOYALITY"]) && $_SESSION['__TRAVELSOFT']["LOYALITY"]['discount'] > 0 ? $_SESSION['__TRAVELSOFT']["LOYALITY"]['discount'] : 0;
    }

    /**
     * @return int|float
     */
    public static function getTotalOrdersSum() {
        return isset($_SESSION['__TRAVELSOFT']) && isset($_SESSION['__TRAVELSOFT']["LOYALITY"]) && $_SESSION['__TRAVELSOFT']["LOYALITY"]['total_orders_sum'] > 0 ? $_SESSION['__TRAVELSOFT']["LOYALITY"]['total_orders_sum'] : 0;
    }

    public static function clear() {
        $_SESSION['__TRAVELSOFT']["LOYALITY"] = [
            'discount' => 0, // в процентах
            'total_orders_sum' => 0 // белорусских рублях
        ];
    }
    
    /**
     * @return boolean
     */
    public static function canUseLoyality() {
        return self::getDiscount() > 0 &&
                $GLOBALS['USER']->IsAuthorized() &&
                !Utils::isAgent() &&
                !in_array(self::NOT_USE_LOYALITY_FOR_USER_GROUP, $GLOBALS['USER']->GetUserGroupArray());
    }
    
    /**
     * @return boolean
     */
    public static function canShowLoyality() {
        return  $GLOBALS['USER']->IsAuthorized() &&
                !Utils::isAgent() &&
                !in_array(self::NOT_USE_LOYALITY_FOR_USER_GROUP, $GLOBALS['USER']->GetUserGroupArray());
    }
}
