<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$search_filter = new InfoBlock();
$arrResultFiltered = [];
$arResult = $search_filter->getItemsListPage(['ID'=>'ASC'], [
  'ACTIVE' => 'Y',
  'IBLOCK_CODE'=> searchFilterFormSettings(),
], false, false,[
  'ID',
  'CODE',
  'NAME',
  'IBLOCK_ID',
  'IBLOCK_CODE',
  'IBLOCK_SECTION_ID',
  'IBLOCK_SECTION_CODE',
]);
foreach ($arResult['items'] as $key => $value) {
  if (!empty($value['IBLOCK_SECTION_ID'])) {
    $search_filter_section = $search_filter->getSectionsList([],['ID'=>$value['IBLOCK_SECTION_ID']])[0];
    $arResult['items'][$key]['section_id'] = $search_filter_section['ID'];
    $arResult['items'][$key]['section_code'] = $search_filter_section['CODE'];
    $arResult['items'][$key]['section_name'] = $search_filter_section['NAME'];
  }
  $search_filter_city = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'TOWN'
  ])[0]['VALUE'];
  if ($search_filter_city) {
    $arResult['items'][$key]['city'] = $search_filter->getItemsList([],['ID'=>$search_filter_city],false,false,['NAME'])[0]['NAME'];
  }
  $search_filter_region = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'REGION'
  ])[0]['VALUE'];
  if ($search_filter_region) {
    $arResult['items'][$key]['region'] = $search_filter->getItemsList([],['ID'=>$search_filter_region],false,false,['NAME'])[0]['NAME'];
  }
  $search_filter_region = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'REGIONS'
  ])[0]['VALUE'];
  if ($search_filter_region) {
    $arResult['items'][$key]['region'] = $search_filter->getItemsList([],['ID'=>$search_filter_region],false,false,['NAME'])[0]['NAME'];
  }
  $search_filter_adress = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'ADDRESS'
  ])[0]['VALUE'];
  if ($search_filter_adress) {
    $arResult['items'][$key]['ADDRESS'] = $search_filter_adress;
  }
  $search_filter_pictures = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'PICTURES'
  ]);
  if ($search_filter_pictures) {
    $arResult['items'][$key]['pictures']=[];
    for ($i=0; $i < 3; $i++) { 
      if ($search_filter_pictures[$i]['VALUE']) {
        $picture_url = CFile::GetFileArray($search_filter_pictures[$i]['VALUE'])['SRC'];
        array_push($arResult['items'][$key]['pictures'], $picture_url);
      }
    }
  }

  $search_filter_distance_center = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'DISTANCE_CENTER'
  ])[0]['VALUE'];
  if ($search_filter_distance_center) {
    $arResult['items'][$key]['DISTANCE_CENTER'] = $search_filter_distance_center;
  }
  $search_filter_distance_airport = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'DISTANCE_AIRPORT'
  ])[0]['VALUE'];
  if ($search_filter_distance_airport) {
    $arResult['items'][$key]['DISTANCE_AIRPORT'] = $search_filter_distance_airport;
  }
  $search_filter_name_en = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'NAME_EN'
  ])[0]['VALUE'];
  if ($search_filter_name_en) {
    $arResult['items'][$key]['NAME_EN'] = $search_filter_name_en;
  }
  $search_filter_name_by = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'NAME_BY'
  ])[0]['VALUE'];
  if ($search_filter_name_by) {
    $arResult['items'][$key]['NAME_BY'] = $search_filter_name_by;
  }
  $search_filter_adress_en = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'ADDRESS_EN'
  ])[0]['VALUE'];
  if ($search_filter_adress_en) {
    $arResult['items'][$key]['ADDRESS_EN'] = $search_filter_adress_en;
  }
  $search_filter_adress_by = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'ADDRESS_BY'
  ])[0]['VALUE'];
  if ($search_filter_adress_by) {
    $arResult['items'][$key]['ADDRESS_BY'] = $search_filter_adress_by;
  }
  $search_filter_departure_time = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'DEPARTURE_TIME'
  ])[0]['VALUE'];
  if ($search_filter_departure_time) {
    $arResult['items'][$key]['DEPARTURE_TIME'] = $search_filter_departure_time;
  }
  $search_filter_duration_time = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'DURATION_TIME'
  ])[0]['VALUE'];
  if ($search_filter_duration_time) {
    $arResult['items'][$key]['DURATION_TIME'] = $search_filter_duration_time;
  }
  $search_filter_food = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'FOOD'
  ])[0]['VALUE'];
  if ($search_filter_food) {
    $search_filter_food_arr = (new InfoBlock())->getItemsList([],['ID'=>$search_filter_food]);
    $arResult['items'][$key]['FOOD'] = $search_filter_food_arr[0]['NAME'];
  }
  $search_filter_distance_minsk = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'DISTANCE_MINSK'
  ])[0]['VALUE'];
  if ($search_filter_distance_minsk) {
    $arResult['items'][$key]['DISTANCE_MINSK'] = $search_filter_distance_minsk;
  }
  $search_filter_nearest_town = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'NEAREST_TOWN'
  ])[0]['VALUE'];
  if ($search_filter_nearest_town) {
    $arResult['items'][$key]['NEAREST_TOWN'] = $search_filter_nearest_town;
  }
  $search_filter_map = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'MAP'
  ])[0]['VALUE'];
  if ($search_filter_map) {
    $arResult['items'][$key]['MAP'] = $search_filter_map;
  }
  $search_filter_services = $search_filter->getInfoBlockPropertyes($value['IBLOCK_ID'], $value['ID'], [],[
    'CODE'=>'SERVICES'
  ]);
  if ($search_filter_services) {
    foreach ($search_filter_services as $subkey => $subvalue) {
      $search_filter_service_all_lang=(new InfoBlock())->getItemsList(
        [],['ID'=>$subvalue['VALUE'], 'IN_FILTER'=>'Y'],false, false,['NAME', 'PROPERTY_NAME_EN', 'PROPERTY_NAME_BY'])[0];
      $search_filter_services[$subkey] = getTextLanguage ($search_filter_service_all_lang['NAME'],
      $search_filter_service_all_lang['NAME_BY'], $search_filter_service_all_lang['NAME_EN']);
    }
    $arResult['items'][$key]['SERVICES'] = $search_filter_services;
  }

  $flag = false;
  if (
    preg_match('#'.mb_strtolower(trim($_GET['search'])).'#', mb_strtolower($arResult['items'][$key]['city']))
    or
    preg_match('#'.mb_strtolower(trim($_GET['search'])).'#', mb_strtolower($arResult['items'][$key]['region']))
    or
    preg_match('#'.mb_strtolower(trim($_GET['search'])).'#', mb_strtolower($arResult['items'][$key]['NAME']))
    or
    preg_match('#'.mb_strtolower(trim($_GET['search'])).'#', mb_strtolower($arResult['items'][$key]['ADDRESS']))
  ) {
    $arResult['items'][$key]['flag'] = 'Y';
    $flag = true;
  }
  if ($flag) {
    array_push($arrResultFiltered, $arResult['items'][$key]);
  }
}
$arResult['items'] = $arrResultFiltered;
$arResult = pagination_php($arResult['items'],10);



$this->IncludeComponentTemplate();

function searchFilterGetDetailPageUrl ($iblock_code, $item_code, $item_id) {
  if ($iblock_code == 'sanatorium') {
    $url = '/tourism/health-tourism/'.$item_code.'/?booking[id][]='.$item_id.'&scroll-to-sp=Y';
  } 
  elseif ($iblock_code == 'accomodation') {
    $url = '/tourism/where-to-stay/'.$item_code.'/?booking[id][]='.$item_id.'&scroll-to-sp=Y';
  }
  elseif ($iblock_code == 'tours') {
    $url = '/tourism/cognitive-tourism/'.$item_code.'/?booking[id][]='.$item_id.'&scroll-to-sp=Y';
  }
  return $url;
}

function searchFilterFormSettings () {
  $arr = [];
  if (isset($_GET['accomodation']) and $_GET['accomodation']=='on') {
    array_push($arr, 'accomodation');
  }
  if (isset($_GET['sanatorium']) and $_GET['sanatorium']=='on') {
    array_push($arr, 'sanatorium');
  }
  if (isset($_GET['tours']) and $_GET['tours']=='on') {
    array_push($arr, 'tours');
  }
  if (sizeof($arr) < 1) {
    $arr = ['accomodation', 'sanatorium', 'tours'];
  }
  return $arr;
}

function searchFilterUrlData () {
  $url = '';
  if (isset($_GET['accomodation']) and $_GET['accomodation']=='on') {
    $url = $url.'&accomodation='.$_GET['accomodation'];
  }
  if (isset($_GET['sanatorium']) and $_GET['sanatorium']=='on') {
    $url = $url.'&sanatorium='.$_GET['sanatorium'];
  }
  if (isset($_GET['tours']) and $_GET['tours']=='on') {
    $url = $url.'&tours='.$_GET['tours'];
  }
  if (isset($_GET['search'])) {
    $url = $url.'&search='.$_GET['search'];
  }
  return $url;
}

function searchFilterCurrentPageNumber () {
  if (isset($_GET['page'])) {
    return $_GET['page'];
  } else {
    return 1;
  }
}


?>

