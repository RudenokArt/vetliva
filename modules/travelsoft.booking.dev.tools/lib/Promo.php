<?php

namespace travelsoft\booking;

/**
 * Промокод
 *
 * @author dimabresky
 * @copyright (c) 2017, travelsoft
 * 
 * Коды результатов:
 *  12 - промокод уже применен
 *  13 - промокод неактивен
 *  14 - данный промокод закончился
 *  15 - общая стоимость корзины недостаточна, чтобы применить данный промокод
 *  16 - промокод не действует на данные виды услуг
 *  17 - промокод успешно применен
 *  18 - промокод недоступен для данной группы пользователей 
 */
class Promo {

    const IBLOCK_ID = 54;
    const CURRENCY = "BYN";
    const EXCLUDE_USER_GROUPS = array();

    public $id = null;
    public $active = null;
    public $activeFrom = null;
    public $activeTo = null;
    public $countOfUsed = null;
    public $quota = null;
    public $value = null;
    public $productType = null;
    public $discountType = null;
    public $discount = null;
    public $serviceCostFrom = null;
    public $cardCostFrom = null;
    public $productCostFrom = null;

    /**
     * Создает объект promo
     * @param string $value
     * @return \travelsoft\booking\Promo
     * @throws \Exception
     * @return $this;
     */
    public static function create(string $value) {

        if (!is_array($_SESSION['__TRAVELSOFT']['PROMO_LIST'])) {
            $_SESSION['__TRAVELSOFT']['PROMO_LIST'] = array();
        }

        \Bitrix\Main\Loader::includeModule('iblock');
        \Bitrix\Main\Loader::includeModule('travelsoft.currency');

        $arPromo = self::findPromo($value);

        $promo = new Promo();

        $promo->id = $arPromo['ID'];
        $promo->value = $arPromo['NAME'];
        $promo->active = $arPromo['ACTIVE'] === 'Y';
        $promo->activeFrom = $arPromo['DATE_ACTIVE_FROM'];
        $promo->activeTo = $arPromo['DATE_ACTIVE_TO'];
        $promo->countOfUsed = $arPromo['PROPERTY_USED_VALUE'];
        $promo->quota = $arPromo['PROPERTY_QUOTA_VALUE'];
        $promo->productType = array_values($arPromo['PROPERTY_TYPE_OF_PRODUCT_VALUE']);
        $promo->discountType = $arPromo['PROPERTY_TYPE_OF_DISCOUNT_VALUE'];
        $promo->discount = $arPromo['PROPERTY_DISCOUNT_VALUE'];

        if ($arPromo['PROPERTY_CARD_COST_FROM_VALUE'] > 0) {
            $promo->cardCostFrom = $arPromo['PROPERTY_CARD_COST_FROM_VALUE'];
        }

        if ($arPromo['PROPERTY_PRODUCT_COST_FROM_VALUE'] > 0) {
            $promo->productCostFrom = $arPromo['PROPERTY_PRODUCT_COST_FROM_VALUE'];
        }

        return $promo;
    }

    /**
     * Поиск промо по значению
     * @param string $value
     * @return array|null
     */
    protected static function findPromo(string $value) {
        return \CIBlockElement::GetList(false, array(
                    'IBLOCK_ID' => self::IBLOCK_ID,
                    'NAME' => $value,
                    ''
                        ), false, false, array(
                    'ID',
                    'NAME',
                    'ACTIVE',
                    'DATE_ACTIVE_FROM',
                    'DATE_ACTIVE_TO',
                    'PROPERTY_TYPE_OF_PRODUCT',
                    'PROPERTY_QUOTA',
                    'PROPERTY_USED',
                    'PROPERTY_DISCOUNT',
                    'PROPERTY_TYPE_OF_DISCOUNT',
                    'PROPERTY_SERVICE_COST_FROM',
                    'PROPERTY_CARD_COST_FROM'
                ))->Fetch();
    }

    /**
     * Очистка примененных пользователей промокодов
     */
    public static function clear() {
        unset($_SESSION['__TRAVELSOFT']['PROMO_LIST']);
    }

    /**
     * Возвращает список примененных промокодов пользователем
     * @return array
     */
    public static function getList() {

        return (array) $_SESSION['__TRAVELSOFT']['PROMO_LIST'];
    }

    /**
     * Применяет промокод к услугам корзины и возвращает результат применения
     *  в виде массива кодов, если применить не удалось
     * 
     * @param \travelsoft\booking\Basket $basket
     * @return string
     */
    public function apply (Basket &$basket): string {

        $status = '';
        $activeByDate = true;
        if ($this->activeFrom && $this->activeTo) {
            $activeByDate = strtotime($this->activeFrom) <= time() && strtotime($this->activeTo) >= time();
        }
        if (!in_array($this->value, $_SESSION['__TRAVELSOFT']['PROMO_LIST']) || !$activeByDate) {

            if ($this->active) {

                if ($this->quota - $this->countOfUsed > 0) {

                    $status = self::_apply($basket);

                    if ($status === "17") {
                        $_SESSION['__TRAVELSOFT']['PROMO_LIST'][] = $this->value;
                    }
                } else {
                    $status = "14";
                }
            } else {

                $status = "13";
            }
        } else {

            $status = "12";
        }

        return $status;
    }

    /**
     * @param \travelsoft\booking\Basket $basket
     * @return string
     */
    public function _apply(Basket &$basket): string {

        $status = '';
        $apply = true;

        if ($this->cardCostFrom) {

            $cardCostFrom = \travelsoft\Currency::getInstance()->convertCurrency($this->cardCostFrom, self::CURRENCY, null, true);
            $apply = $cardCostFrom >= $basket->cost();
        }

        if ($apply) {

            $apply = true;

            $arUserGroups = $GLOBALS['USER']->GetUserGroupArray();
            foreach (self::EXCLUDE_USER_GROUPS as $groupId) {
                if (in_array($groupId, $arUserGroups)) {
                    $apply = false;
                }
            }

            if ($apply) {

                if (!empty($this->productType)) {

                    $arItems = array();
                    $applyed = false;
                    while ($basketItemData = $basket->fetch()) {

                        $item = $basketItemData["item"];

                        if (in_array($item->type, $this->productType)) {
                            $applyed = true;
                            $productCostFrom = 0;
                            if ($this->productCostFrom > 0) {

                                $productCostFrom = \travelsoft\Currency::getInstance()->convertCurrency($productCostFrom, self::CURRENCY, $basketItemData->currency);
                            }
                            
                            if ($item->price >= $productCostFrom && $this->discount) {

                                if ($this->discountType == 'fixed') {

                                    $discount = \travelsoft\Currency::getInstance()->convertCurrency($this->discount, self::CURRENCY, $basketItemData->currency);
                                } else {

                                    $discount = $item->getDiscountPrice() * $this->discount / 100;
                                }

                                $item->setDiscount($discount);
                            }
                        }

                        $arItems[] = $item;
                    }

                    if (!$applyed) {
                        $status = "16";
                    } else {
                        $status = "17";
                    }
                } else {

                    $arItems = array();
                    while ($basketItemData = $basket->fetch()) {

                        $item = $basketItemData["item"];

                        $productCostFrom = 0;
                        if ($this->productCostFrom > 0) {

                            $productCostFrom = \travelsoft\Currency::getInstance()->convertCurrency($productCostFrom, self::CURRENCY, $basketItemData->currency);
                        }

                        if ($item->price >= $productCostFrom && $this->discount) {

                            if ($this->discountType == 'fixed') {

                                $discount = \travelsoft\Currency::getInstance()->convertCurrency($this->discount, self::CURRENCY, $basketItemData->currency);
                            } else {

                                $discount = $item->getDiscountPrice() * $this->discount / 100;
                            }

                            $item->setDiscount($discount);
                        }

                        $arItems[] = $item;
                    }

                    $status = "17";
                }

                $basket->clear();
                foreach ($arItems as $item) {

                    $basket->add($item);
                }
            } else {

                $status = "18";
            }
        } else {

            $status = "15";
        }

        return $status;
    }
    
    /**
     * @param array $basket
     * @return string
     */
    public function __apply(&$basket): string {

        $status = '';
        $apply = true;

        if ($this->cardCostFrom) {

            $cardCostFrom = \travelsoft\Currency::getInstance()->convertCurrency($this->cardCostFrom, self::CURRENCY, null, true);
            $apply = $cardCostFrom >= $basket["price"];
        }

        if ($apply) {

            $apply = true;

            $arUserGroups = $GLOBALS['USER']->GetUserGroupArray();
            foreach (self::EXCLUDE_USER_GROUPS as $groupId) {
                if (in_array($groupId, $arUserGroups)) {
                    $apply = false;
                }
            }

            if ($apply) {

                if (!empty($this->productType)) {
                    
                    $applyed = false;
                    foreach ($basket as &$item) {
                        if (!isset($item["discount"])) {
                            $item["discount"] = [];
                        }
                        if (in_array($item["type"], $this->productType)) {
                            $applyed = true;
                            $productCostFrom = 0;
                            if ($this->productCostFrom > 0) {

                                $productCostFrom = \travelsoft\Currency::getInstance()->convertCurrency($productCostFrom, self::CURRENCY, $item["currency"]);
                            }

                            if ($item["price"] >= $productCostFrom && $this->discount) {

                                if ($this->discountType == 'fixed') {

                                    $discount = \travelsoft\Currency::getInstance()->convertCurrency($this->discount, self::CURRENCY, $item["currency"]);
                                } else {

                                    $discount = $item["price"] * $this->discount / 100;
                                }

                                $item["discount"][] = $discount;
                            }
                        }
                    }

                    if (!$applyed) {
                        $status = "16";
                    } else {
                        $status = "17";
                    }
                } else {

                    
                    foreach ($basket as &$item) {
                        if (!isset($item["discount"])) {
                            $item["discount"] = [];
                        }
                        $productCostFrom = 0;
                        if ($this->productCostFrom > 0) {

                            $productCostFrom = \travelsoft\Currency::getInstance()->convertCurrency($productCostFrom, self::CURRENCY, $item["currency"]);
                        }

                        if ($item["price"] >= $productCostFrom && $this->discount) {

                            if ($this->discountType == 'fixed') {

                                $discount = \travelsoft\Currency::getInstance()->convertCurrency($this->discount, self::CURRENCY, $item["currency"]);
                            } else {

                                $discount = $item["price"] * $this->discount / 100;
                            }

                            $item["discount"][] = $discount;
                        }
                    }

                    $status = "17";
                }

            } else {

                $status = "18";
            }
        } else {

            $status = "15";
        }

        return $status;
    }

    /**
     * Увеличиваем количество использований кодов
     */
    public static function increasePromoQuota() {

        foreach (self::getList() as $value) {

            $arPromo = self::findPromo($value);

            if ($arPromo['ID'] > 0) {

                \CIBlockElement::SetPropertyValuesEx($arPromo['ID'], self::IBLOCK_ID, array("USED" => ++$arPromo['PROPERTY_USED_VALUE']));
            }
        }
    }

}
