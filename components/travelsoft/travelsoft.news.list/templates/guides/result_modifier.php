<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/*$arData = [
    'LOCATIONS' => \Kosmos\Main\Helpers\Data\Location::getList(),
    'LANGUAGES' => \Kosmos\Main\Helpers\Data\Language::getList(),
    'TOUR_TYPES' => \Kosmos\Main\Helpers\Data\TourType::getList(),
];

$arDataProps = [
    'RESIDENCE' => 'LOCATIONS',
    'TOUR_LANGUAGE' => 'LANGUAGES',
    'TOUR_TYPE' => 'TOUR_TYPES',
];*/

foreach($arResult['ITEMS'] as $key => $arItem){

    foreach($arItem['DISPLAY_PROPERTIES'] as $code => $arProperty){
        
        if ($arProperty['LINK_IBLOCK_ID']>0) {
            $arDisplayValues =[];
            if (is_array($arProperty['VALUE'])) foreach ($arProperty['VALUE'] as $id)  $arDisplayValues[] = Get_Name_Element($id); 
            else $arDisplayValues[] = Get_Name_Element($arProperty['VALUE']); 
            $arResult['ITEMS'][$key]['DISPLAY_PROPERTIES'][$code]['DISPLAY_VALUE'] = $arDisplayValues;
        }
        /*if(array_key_exists($code, $arDataProps)){

            $arValues = $arProperty['VALUE'];
            if(!is_array($arValues)){
                $arValues = [$arValues];
            }

            $arDisplayValues = [];
            foreach($arValues as $id){

                foreach($arData[ $arDataProps[$code] ] as $arDataValue){

                    if($arDataValue['ID'] == $id){
                        $arDisplayValues[] = $arDataValue['NAME'];
                        break;
                    }

                }

            }

            $arResult['ITEMS'][$key]['DISPLAY_PROPERTIES'][$code]['DISPLAY_VALUE'] = $arDisplayValues;

        }*/

    }

}