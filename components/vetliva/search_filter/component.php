<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$search_filter = new InfoBlock();
$arrResultFiltered = [];
$arResult = $search_filter->getItemsList(['ID'=>'ASC'], [
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
  $search_filter_adress = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'ADDRESS'
  ])[0]['VALUE'];
  if ($search_filter_adress) {
    $arResult[$key]['adress'] = $search_filter_adress;
  }
  $search_filter_pictures = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'PICTURES'
  ]);
  if ($search_filter_pictures) {
    $arResult[$key]['pictures']=[];
    for ($i=0; $i < 3; $i++) { 
      if ($search_filter_pictures[$i]['VALUE']) {
        $picture_url = CFile::GetFileArray($search_filter_pictures[$i]['VALUE'])['SRC'];
        array_push($arResult[$key]['pictures'], $picture_url);
      }
    }
  }

  $search_filter_adress = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'DISTANCE_CENTER'
  ])[0]['VALUE'];
  if ($search_filter_adress) {
    $arResult[$key]['DISTANCE_CENTER'] = $search_filter_adress;
  }
  $search_filter_adress = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'DISTANCE_AIRPORT'
  ])[0]['VALUE'];
  if ($search_filter_adress) {
    $arResult[$key]['DISTANCE_AIRPORT'] = $search_filter_adress;
  }

  $flag = false;
  if (
    preg_match('#'.mb_strtolower(trim($_GET['search'])).'#', mb_strtolower($arResult[$key]['city']))
    or
    preg_match('#'.mb_strtolower(trim($_GET['search'])).'#', mb_strtolower($arResult[$key]['region']))
    or
    preg_match('#'.mb_strtolower(trim($_GET['search'])).'#', mb_strtolower($arResult[$key]['NAME']))
    or
    preg_match('#'.mb_strtolower(trim($_GET['search'])).'#', mb_strtolower($arResult[$key]['adress']))
  ) {
    $arResult[$key]['flag'] = 'Y';
    $flag = true;
  }
  if ($flag) {
    array_push($arrResultFiltered, $arResult[$key]);
  }
}
$arResult = $arrResultFiltered;

$this->IncludeComponentTemplate();
?>
PICTURES
upload/adwex.minified/webp/5c7/