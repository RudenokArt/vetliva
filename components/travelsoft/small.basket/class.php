<?php

/**
 * Класс TravelsoftSmallBasket
 * @author dimabresky
 * @copyright (c) 2017, travelsoft
 */
class TravelsoftSmallBakset extends CBitrixComponent {

    public function executeComponent() {
        
        \Bitrix\Main\Loader::includeModule('travelsoft.booking.dev.tools');
        
        if ($this->arParams['BASKET_PAGE'] == '') {
            
            $this->arParams['BASKET_PAGE'] = '/booking/';
        }
        
        $this->arResult['BASKET_COUNT_ITEM'] = (new travelsoft\booking\Basket)->count();
        
        $this->IncludeComponentTemplate();
        
    }
    
}
