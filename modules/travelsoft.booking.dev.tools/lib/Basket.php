<?php

namespace travelsoft\booking;

/**
 * Корзина услуг
 *
 * @author dimabresky
 */
class Basket {
    
    /**
     * @var array 
     */
    protected $_basket = null;
    
    /**
     * @var array
     */
    protected $_tmp_basket_fields = null;
    
    /**
     * @var \stdClass 
     */
    protected $_discount = null;
    
    /**
     * @var boolean
     */
    public $_change_blocked = false;
    
    public function __construct() {
        
        if (!is_array($_SESSION['__TRAVELSOFT']["BASKET"])) {
            $_SESSION['__TRAVELSOFT']["BASKET"] = array();
        }
        
        if (!is_array($_SESSION['__TRAVELSOFT']["TMP_BASKET_FIELDS"])) {
            $_SESSION['__TRAVELSOFT']["TMP_BASKET_FIELDS"] = array();
        }
        
        if (!isset($_SESSION['__TRAVELSOFT']["BASKET_CHANGE_BLOCKED"])) {
            $_SESSION['__TRAVELSOFT']["BASKET_CHANGE_BLOCKED"] = false;
        }
        
        # init basket
        $this->_basket = &$_SESSION['__TRAVELSOFT']["BASKET"];
        $this->_tmp_basket_fields = &$_SESSION['__TRAVELSOFT']["TMP_BASKET_FIELDS"];
        $this->_change_blocked = &$_SESSION['__TRAVELSOFT']["BASKET_CHANGE_BLOCKED"];
        
        reset($this->_basket);
    }
    
    /**
     * @param array $arBasketFields
     */
    public function setTmpBasketFields (array $arBasketFields) {
        $this->_tmp_basket_fields = $arBasketFields;
    }
    
    /**
     * @param array $arBasketFields
     */
    public function getTmpBasketFields () {
        return $this->_tmp_basket_fields;
    }
    
    /**
     * проверка корзины на пустоту
     * @return boolean
     */
    public function isEmpty() {

        if (empty($this->_basket)) {
            return true;
        }

        return false;
    }

    /**
     * проверка существования позиции
     * @param int $position
     * @return boolean
     */
    public function isExists(int $position) {

        if (isset($position, $this->_basket)) {
            return true;
        }

        return false;
    }

    /**
     * добавление позиции в корзину
     * @param array $basketItem
     * @return int
     */
    public function add(abstractions\BasketItem $basketItem) {
        
        return (array_push($this->_basket, serialize($basketItem)) - 1);
        
    }

    /**
     * удаление позиции из корзины
     * @param int $position
     */
    public function delete(int $position) {
        if ($this->isExists($position)) {
            unset($this->_basket[$position]);
        }
    }
    
    /**
     * @return mixed
     */
    public function fetch () {
        $item = each($this->_basket);
        if ($item["value"]) {
            
            return array("item" => unserialize($item["value"]), "position" => $item["key"]);
        }
        else { reset($this->_basket); }
        return false;
    }
    
    /**
     * Общая стоимость корзины c учетом скидки
     * @return float|null
     */
    public function total () {

        $total = $this->cost();
        
        $discount = $this->discount();
        
        if ($discount > 0) {
             $total -= $discount;
        }
        
        return $total;
    }
    
    /**
     * Возвращает отформатированную результирующую стоимость
     * @return string
     */
    public function formattedTotal () {
        $total = $this->total();
        if ($total > 0) {
            return Utils::convertCurrency($this->total(), Utils::getCurrentCurrency()['iso']);
        } else {
            return '0.00';
        }
    }
    
    /**
     * Стоимость корзины
     * @return float
     */
    public function cost () {
        $cost = 0.00;
        foreach ($this->_basket as $item) {
            $item = unserialize($item);
            if (!$item->can_buy) {
                continue;
            }
            $cost += (float) Utils::convertCurrency($item->price, $item->currency, null, true); 
        }
        return $cost;
    }
    
    /**
     * Возвращает отформатированную стоимость
     * @return string
     */
    public function formattedCost () {
        $cost = $this->cost();
        if ($cost > 0.01) {
            return Utils::convertCurrency($cost, Utils::getCurrentCurrency()['iso']);
        } else {
            return "0.00";
        }
    }
    
    /**
     * Возвращает скидку
     * @return float
     */
    public function discount () {
       
        $discount = 0.00;
        foreach ($this->_basket as $item) {
            $item = unserialize($item);
            if (!$item->can_buy) {
                continue;
            }
            $arDiscount = $item->discount;
            if (!empty($arDiscount)) {
                
                foreach ($arDiscount as $discount_) {
                    
                    if ($discount_ > 0) {
                        $discount += (float) \travelsoft\Currency::getInstance()->convertCurrency($discount_, $item->currency, null, true); 
                    }
                    
                }
                
            }
        }
        
        return $discount;
    }
    
    /**
     * Число элементов в корзине
     * @return int
     */
    public function count () {
        return count($this->_basket);
    }
    
    /**
     * Отформатированное значение скидки
     * @return string
     */
    public function formattedDiscount () {
        
        $discount = $this->discount();
        if ($discount > 0) {
            return Utils::convertCurrency($discount, Utils::getCurrentCurrency()['iso']);
        } else {
            return '0.00';
        }
    }
    
    /**
     * очистка всей корзины
     */
    public function clear() {
       $this->_basket = array();
       $this->_tmp_basket_fields = array();
       $this->_change_blocked = false;
    }
    
    /**
     * Количество человек по заказу
     * @return int
     */
    public function countOfPeople () {
        $count = 0;
        foreach ($this->_basket as $item) {
            $item = unserialize($item);
            if (!$item->can_buy) {
                continue;
            }
            
            $count += $item->adults + $item->children;
            
        }
        return $count;
    }
    
    /**
     * Очистка скидок
     */
    public function clearDiscount () {
        
        $items = array();
        
        foreach ($this->_basket as $item) {
            $item = unserialize($item);
            $items[] = $item->getPropertiesLikeArray();
        }
        
        $this->clear();
        
        if ($items) {
            
            for ($i = 0, $cnt = count($items); $i < $cnt; $i++) {
                
                $classBasketItem = "travelsoft\\booking\\" . $items[$i]["type"] . "\\BasketItem";
                $this->add(new $classBasketItem($items[$i]));
                
            }
            
        }
    }
    
    /**
     * @param array $basket_fields
     */
    public function reset(array $basket_fields) {
        $this->clear();
        foreach ($basket_fields as $fields) {
            $classBasketItem = "travelsoft\\booking\\" . $fields["type"] . "\\BasketItem";
            $this->add(new $classBasketItem($fields));
        }
    }
}
