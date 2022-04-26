<?php

namespace travelsoft\booking\abstractions\commons;

use travelsoft\booking\abstractions\BasketItem as AbstractBasketItem;

use travelsoft\booking\Validation;

/**
 * Абстрактный класс расширения элемента корзины
 *
 * @author dimabresky
 */
abstract class BasketItem extends AbstractBasketItem{
    
    /**
     * @param array $itemFields
     */
    public function __construct(array $itemFields) {
        parent::__construct($itemFields);
        Validation::checkDateTo($itemFields["date_to"], $itemFields["date_from"]);
        $this->_container["date_to"] = $itemFields["date_to"];
        Validation::checkRate($itemFields["rate_id"]);
        $this->_container["rate_id"] = $itemFields["rate_id"];
        Validation::checkChildren($itemFields["children"]);
        $this->_container["children"] = $itemFields["children"];
        Validation::checkDuration($itemFields["duration"]);
        $this->_container["duration"] = $itemFields["duration"];
        $this->_container["children_age"] = $itemFields["children_age"];
        $this->_container['allocate'] = isset($itemFields['allocate']) && is_array($itemFields['allocate']) ? $itemFields['allocate'] : [];
    }
}
