<?
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
/*
$arData = [
    'LOCATIONS' => \Kosmos\Main\Helpers\Data\Location::getList(),
    'LANGUAGES' => \Kosmos\Main\Helpers\Data\Language::getList(),
    'TOUR_TYPES' => \Kosmos\Main\Helpers\Data\TourType::getList(),
];

$arDataProps = [
    'RESIDENCE' => 'LOCATIONS',
    'TOUR_LANGUAGE' => 'LANGUAGES',
    'TOUR_TYPE' => 'TOUR_TYPES',
];

foreach($arResult['DISPLAY_PROPERTIES'] as $code => $arProperty){

    if(array_key_exists($code, $arDataProps)){

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

        $arResult['DISPLAY_PROPERTIES'][$code]['DISPLAY_VALUE'] = $arDisplayValues;

    }

}*/


    foreach($arResult['DISPLAY_PROPERTIES'] as $code => $arProperty){
        
        if ($arProperty['LINK_IBLOCK_ID']>0) {
            $arDisplayValues =[];
            if (is_array($arProperty['VALUE'])) foreach ($arProperty['VALUE'] as $id)  $arDisplayValues[] = Get_Name_Element($id); 
            else $arDisplayValues[] = Get_Name_Element($arProperty['VALUE']); 
            $arResult['DISPLAY_PROPERTIES'][$code]['DISPLAY_VALUE'] = $arDisplayValues;
        }
    }

$cp = $this->__component;

if (is_object($cp))
{
    $cp->arResult['PROPERTIES'] = $arResult["PROPERTIES"];
    $cp->SetResultCacheKeys(array('PROPERTIES'));
}