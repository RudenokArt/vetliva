<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);


/**
 * travelsoft booking search form component
 * CBitrixComponent extension
 */
class TravelsoftBookingSearchForm extends CBitrixComponent {
    
    /**
     * component body
     */
    public function executeComponent() {

        $_request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest()->get("booking");
        
        // зничения полей таблицы тарифов
        $UF_FIELDS = $GLOBALS['USER_FIELD_MANAGER']->GetUserFields('HLBLOCK_'. \Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "rate_hl_id"), 0);
        
        // отображать цену для
        $this->arResult["UF_FIELDS_PRICE_FOR"] = array(
            $UF_FIELDS["UF_BR_PRICES"]["ID"] => "BR_PRICE", // для граждан Беларуси 
            $UF_FIELDS["UF_RF_PRICES"]["ID"] => "RF_PRICE", // для граждан РФ
            $UF_FIELDS["UF_EU_PRICES"]["ID"] => "EU_PRICE", // для граждан Европы
        );
        
        /** формируем масиив данных из get запроса */
        
        // id элемента инфоблока по которому ищем
        if ($_request['id'] && $_request['id'] > 0) {
            $this->arResult['__get']['id'] = $_request['id'];
        } 
        
        // количество взрослых
        if ($_request['adults'] && $_request['adults'] >= 0) {
            $this->arResult['__get']['adults'] = $_request['adults'];
        }
        
        // количество детей
        if ($_request['children'] && $_request['children'] >= 0) {
            $this->arResult['__get']['children'] = $_request["children"];
        }
        
        // возраст детей
        if (is_array($_request["children_age"]) && !empty($_request["children_age"])) {
          
            $this->arResult['__get']['children_age'] = array_map(function ($it) { 
                
                $it = intVal($it);
                
                if ($it <= 0) {
                    $it = 1;
                } elseif ($it > 16) {
                    $it = 16;
                }
                
                return $it;
                
            }, $_request['children_age']);
        }
                
        // дата с (заезд, дата начала и т.д.)
        if ($_request['date_from'] && $_request['date_from'] >= 0) {
            $this->arResult['__get']['date_from'] = $_request['date_from'];
        }
        
        // дата по (выезд, дата окончания и т.д.)
        if ($_request['date_to'] && _request['date_to'] >= 0) {
            $this->arResult['__get']['date_to'] = $_request['date_to'];
        }
        
        if ($_request["price_for"]) {
            //
        }
        
        /** */
        
        $this->IncludeComponentTemplate();
        
    }

}

if (!function_exists("declension")){
    /**
     * склонение слов 
     * @param integer $number
     * @param array $words
     */
    function declension ($number, $words) {

        if ($number % 10 === 1 && $number !== 11) {
            return $number . " " . $words[0];
        }

        return $number . " " . $words[1]; 

    }
}

if (!function_exists("declension_passangers")){
    /**
     * склонение слов для трансферов
     * @param int $number
     * @param array $words
     */
    function declension_passangers ($number, $words) {

        if ($number === 1) {
            return $number . " " . $words[0];
        } elseif ($number >=2 && $number <= 4) {
            return $number . " " . $words[1];
        }
        
        return $number . " " . $words[2]; 

    }
}

if (!function_exists("getAdultsSelectOption")) {
    
    function getAdultsSelectOption ($current_val = 2, $existZero = false) {

        $arAdultsSelectOptions = null;

        if($existZero){
            $selectedTitle = Loc::getMessage("without_adults");        
            $arAdultsSelectOptions[] = "<option  value='0'>". $selectedTitle ."</option>";
        } 
        
        $adultsWords = array(Loc::getMessage("adult"), Loc::getMessage("adults"));
        
        for ($i = 1; $i <= 20; $i++) {
            $selected = ""; $title = declension($i, $adultsWords);
            if ($i == $current_val) {
                $selected = "selected";
                $selectedTitle = $title;
            }
            $arAdultsSelectOptions[] = "<option value='" . $i . "' ". $selected .">". $title ."</option>";    
        }

        $html = "";//"<span>$selectedTitle</span>";
        $html .= "<select readonly name=\"booking[adults]\">";
        $html .= implode("", $arAdultsSelectOptions);
        $html .= "</select>";
        
        return $html;
        
    }

}


if (!function_exists("getChildrenSelectOption")) {
    
    function getChildrenSelectOption ($current_val) {
        
        $arChildrenSelectOptions = null;
        
        $childrenWords = array(Loc::getMessage("child"), Loc::getMessage("children"));
        
         $selectedTitle = Loc::getMessage("without_children");
        
        $arChildrenSelectOptions[] = "<option  value='0'>". $selectedTitle ."</option>"; 
        for ($i = 1; $i <= 4; $i++) {
            $selected = ""; $title = declension($i, $childrenWords);
            if ($i == $current_val) {
                $selected = "selected";
                $selectedTitle = $title;
            }
            $arChildrenSelectOptions[] = "<option $selected  value='" . $i . "'>". declension($i, $childrenWords) ."</option>";    
        }
        
        $html = ""; //"<span>$selectedTitle</span>";
        $html .= "<select readonly name=\"booking[children]\">";
        $html .= implode("", $arChildrenSelectOptions);
        $html .= "</select>";
        
        return $html;

    }

}

if (!function_exists("getSelectAgeTpl")) {
    
    function getSelectAgeTpl ($max_age = 18) {
        
        $options = "";
        
        for ($i = 1; $i <= $max_age; $i++) {
            $options .= "<option  value='".$i."'>".$i."</option>";
        }
        
        return "<div readonly class='age-selector'>" . Loc::getMessage("age_selector_text") . " <select id='age_selector__#N#' required name='booking[children_age][]'>". $options . "</select></div>";
        
    }
    
}

if (!function_exists("getSelectPassengersTpl")) {
    function getSelectPassengersTpl ($current_val = 1) {

        $arPassengersSelectOptions = null;
        
        $passengersWords = array(Loc::getMessage("passenger"), Loc::getMessage("passengers"), Loc::getMessage("passengers2"));
        
        for ($i = 1; $i <= 20; $i++) {
            $selected = ""; $title = declension_passangers($i, $passengersWords);
            if ($i == $current_val) {
                $selected = "selected";
                $selectedTitle = $title;
            }
            $arPassengersSelectOptions[] = "<option value='" . $i . "' ". $selected .">". $title ."</option>";    
        }

        $html = "";//"<span>$selectedTitle</span>";
        $html .= "<select readonly id=\".passangers-select\" data-dropdown-parent=\"select-of-passangers\" name=\"booking[adults]\">";
        $html .= implode("", $arPassengersSelectOptions);
        $html .= "</select>";
        
        return $html;
        
    }
}