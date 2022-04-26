<?php

namespace travelsoft\booking\abstractions;

use travelsoft\booking\Validation;

/**
 * Абстрактный класс элемента корзины
 *
 * @author dimabresky
 */
abstract class BasketItem extends Getter {

    protected $_container = array(
        "service_id" => null,
        "date_from" => null,
        "adults" => null,
        "currency" => null,
        "can_buy" => null,
        "discount" => null,
        "price" => null,
        "type" => null,
        "position" => null,
        "agent" => null,
        "xml_id" => null,
        "discount" => null,
        "loyality_applyed" => false
    );

    /**
     * @param array $itemFields
     */
    public function __construct(array $itemFields) {
        Validation::checkService($itemFields["service_id"]);
        $this->_container["service_id"] = $itemFields["service_id"];
        Validation::checkDateFrom($itemFields["date_from"]);
        $this->_container["date_from"] = $itemFields["date_from"];
        Validation::checkAdults($itemFields["adults"]);
        $this->_container["adults"] = $itemFields["adults"];
        Validation::checkCurrency($itemFields["currency"]);
        $this->_container["currency"] = $itemFields["currency"];
        $this->_container["can_buy"] = boolval($itemFields["can_buy"]);
        Validation::checkType($itemFields["type"]);
        $this->_container["type"] = $itemFields["type"];
        Validation::checkPrice($itemFields["price"]);
        $this->_container["price"] = $itemFields["price"];
        if ($itemFields['loyality_applyed'] === true) {
            $itemFields['loyality_applyed'] = true;
        }
        if ($itemFields["position"]) {
            Validation::checkBasketItemPosition($itemFields["position"]);
            $this->_container["position"] = $itemFields["position"];
        }
        if ($itemFields["agent"]) {
            $this->_container["agent"] = $itemFields["agent"];
        }
        $this->_container["xml_id"] = $itemFields["xml_id"];
        if (!empty($itemFields["discount"]))
            if (is_array($itemFields["discount"])) {
                foreach ($itemFields["discount"] as $d) {
                    $this->setDiscount($d);
                }
            } elseif ($itemFields["discount"] > 0) {
                $this->setDiscount($itemFields["discount"]);
            }
    }

    /**
     * @param float $discount
     */
    public function setDiscount(float $discount) {

        $this->_container['discount'][] = $discount;
    }

    /**
     * @return float;
     */
    public function getDiscountPrice() {

        $price = $this->_container['price'];

        if (!empty($this->_container['discount'])) {

            foreach ($this->_container['discount'] as $discount) {
                $price -= $discount;
            }
        }

        return $price > 0 ? (float) $price : 0.00;
    }

}
