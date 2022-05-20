<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$search_filter = new InfoBlock();
$arResult = $search_filter->getItemsList([], [
  'IBLOCK_CODE'=>[
    'accomodation', 'sanatorium', 'tours'],
], false, false,[
  'ID',
  'CODE',
  'NAME',
  'IBLOCK_ID',
  'IBLOCK_CODE',
  'IBLOCK_SECTION_ID',
  'IBLOCK_SECTION_CODE',
]);
foreach ($arResult as $key => $value) {
  if (!empty($value['IBLOCK_SECTION_ID'])) {
    $search_filter_section = $search_filter->getSectionsList([],['ID'=>$value['IBLOCK_SECTION_ID']])[0];
    $arResult[$key]['section_id'] = $search_filter_section['ID'];
    $arResult[$key]['section_code'] = $search_filter_section['CODE'];
    $arResult[$key]['section_name'] = $search_filter_section['NAME'];
  }
  $search_filter_city = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'TOWN'
  ])[0]['VALUE'];
  if ($search_filter_city) {
    $arResult[$key]['city'] = $search_filter->getItemsList([],['ID'=>$search_filter_city],false,false,['NAME'])[0]['NAME'];
  }
  $search_filter_region = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'REGION'
  ])[0]['VALUE'];
  if ($search_filter_region) {
    $arResult[$key]['region'] = $search_filter->getItemsList([],['ID'=>$search_filter_region],false,false,['NAME'])[0]['NAME'];
  }
  $search_filter_region = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'REGIONS'
  ])[0]['VALUE'];
  if ($search_filter_region) {
    $arResult[$key]['region'] = $search_filter->getItemsList([],['ID'=>$search_filter_region],false,false,['NAME'])[0]['NAME'];
  }
  // if (!empty($value['PROPERTY_TOWN_VALUE'])) {
  //   $arResult[$key]['city'] = $search_filter->getItemsList([],['ID'=>$value['PROPERTY_TOWN_VALUE']],false,false,['NAME'])[0]['NAME'];
  // }
  // if (!empty($value['PROPERTY_REGION_VALUE'])) {
  //   $arResult[$key]['region'] = $search_filter->getItemsList([],['ID'=>$value['PROPERTY_REGION_VALUE']],false,false,['NAME'])[0]['NAME'];
  // }
}


$this->IncludeComponentTemplate();
?>

REGION
NEAREST_TOWN
search_filter_properties
REGIONS