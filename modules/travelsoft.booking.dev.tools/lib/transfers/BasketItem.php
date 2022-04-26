<?php

namespace travelsoft\booking\transfers;

use travelsoft\booking\abstractions\BasketItem as AbstractBasketItem;
use travelsoft\booking\Validation;

/**
 * Корзина по трансферам
 *
 * @author dimabresky
 */
class BasketItem extends AbstractBasketItem {
    public function __construct (array $itemFields) {
        parent::__construct($itemFields);
        $this->_container["date_to"] = null;
        $this->_container["children"] = null;
        if ($itemFields["date_to"]) {
            Validation::checkDateTo($itemFields["date_to"], $itemFields["date_from"]);
            $this->_container["date_to"] = $itemFields["date_to"];
        }
        Validation::checkPointA($itemFields["point_A"]);
        $this->_container["point_A"] = $itemFields["point_A"];
        Validation::checkPointB($itemFields["point_B"]);
        $this->_container["point_B"] = $itemFields["point_B"];
        Validation::checkTransferRate($itemFields["rate_id"]);
        $this->_container["rate_id"] = $itemFields["rate_id"];
        $this->_container["roundtrip"] = boolval($itemFields["roundtrip"]);
        $this->_container["for_spot_payment"] = boolval($itemFields["for_spot_payment"]);
    }
}
