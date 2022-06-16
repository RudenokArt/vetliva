<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 *    med_services
 */
class SearchFilter extends InfoBlock {

  function __construct()  {
    $this->current_page_number = $this->getCurrentPageNumber();
    $this->cities_list = $this->getItemsList(['NAME'=>'asc'],[
      'IBLOCK_CODE'=>'cities',
    ], false, false, ['ID','NAME','PROPERTY_REGION','PROPERTY_NAME_BY', 'PROPERTY_NAME_EN']);
    $this->regions_list = $this->getItemsList([],[
      'IBLOCK_CODE'=>'regions'
    ], false, false, ['ID','NAME','PROPERTY_NAME_BY', 'PROPERTY_NAME_EN']);
    $this->med_profiles_list = $this->getItemsList([],[
      'IBLOCK_CODE'=>'med_profiles'
    ], false, false, ['ID','NAME','PROPERTY_NAME_BY', 'PROPERTY_NAME_EN']);
    $this->med_services_list = $this->getItemsList([],[
      'IBLOCK_CODE'=>'med_services'
    ], false, false, ['ID','NAME','PROPERTY_NAME_BY', 'PROPERTY_NAME_EN']);
    $this->search_filter_by_name = $this->getItemsIdArr('NAME');
    $this->search_filter_by_name_by = $this->getItemsIdArr('PROPERTY_NAME_BY');
    $this->search_filter_by_name_en = $this->getItemsIdArr('PROPERTY_NAME_EN');
    $this->search_filter_by_adress = [];
    $this->search_filter_by_adress_by = [];
    $this->search_filter_by_adress_en = [];
    if ($this->searchFilterFormSettings()!='tours') {
      $this->search_filter_by_adress = $this->getItemsIdArr('PROPERTY_ADDRESS');
      $this->search_filter_by_adress_by = $this->getItemsIdArr('PROPERTY_ADDRESS_BY');
      $this->search_filter_by_adress_en = $this->getItemsIdArr('PROPERTY_ADDRESS_EN');
    }    
    $this->search_filter_whole_id_arr = array_merge(
      $this->search_filter_by_name,
      $this->search_filter_by_name_by,
      $this->search_filter_by_name_en,
      $this->search_filter_by_adress,
      $this->search_filter_by_adress_by,
      $this->search_filter_by_adress_en
    );
    if ($this->search_filter_whole_id_arr) {
      $filter = [
        'ACTIVE' => 'Y',
        'IBLOCK_CODE'=> $this->searchFilterFormSettings(),
        'ID'=>$this->search_filter_whole_id_arr,
      ];
      $filter = $this->checkFilterData($filter);
      $this->items_list = $this->getItemsListPage([], $filter, false, [
        'iNumPage' => $this->current_page_number,
        'nPageSize' => 5,
      ],[
        'ID',
        'CODE',
        'IBLOCK_CODE',
        'IBLOCK_ID',
        'NAME',
      ]);
    }
  }


  function getCurrentPageNumber () {
    if (isset($_GET['page_number'])) {
      return $_GET['page_number'];
    } else {
      return 1;
    }
  }

  function checkFilterData ($filter) {
    if (isset($_GET['region'])) {
      $filter['PROPERTY_REGION'] = $_GET['region'];
      $filter['PROPERTY_REGIONS'] = $_GET['region'];
    }
    if (isset($_GET['city']) and $_GET['city']!='N') {
      $filter['PROPERTY_TOWN'] = $_GET['city'];
    }
    if (isset($_GET['med_services']) and $_GET['med_services']!='N') {
      $filter['PROPERTY_MED_SERVICES'] = $_GET['med_services'];
    }
    if (isset($_GET['med_profiles']) and $_GET['med_profiles']!='N') {
      $filter['PROPERTY_TYPE'] = $_GET['med_profiles'];
    }
    return $filter;
  }

  function itemsIdArrListMaker ($property, $search) {
    $filter = [
      'ACTIVE' => 'Y',
      'IBLOCK_CODE'=> $this->searchFilterFormSettings(),
      '%'.$property => $search,
    ];
    $filter = $this->checkFilterData($filter);
    $arr = $this->getItemsList(['ID'=>'asc'], $filter, false, false, [
      'ID',
      'IBLOCK_ID',
      'NAME',
      'PROPERTY_NAME_BY', 
      'PROPERTY_NAME_EN',
      'PROPERTY_REGION',
      'PROPERTY_REGIONS',
      'PROPERTY_TOWN',
      'PROPERTY_ADDRESS',
    ]);
    $list = [];
    foreach ($arr as $key => $value) {
      array_push($list, $value['ID']);
    }
    return $list;
  }

  function getItemsIdArr ($property) {
    $search = trim($_GET['search']);
    $list = $this->itemsIdArrListMaker($property, $search);
    if (sizeof($list) < 1) {
      $search = explode(' ', $_GET['search']);
      $list = $this->itemsIdArrListMaker($property, $search);
    }
    return $list;
  }

  function searchFilterFormSettings () {
    if (isset($_GET['filter']) and $_GET['filter']=='accomodation') {
      $settings = 'accomodation';
    }
    if (isset($_GET['filter']) and $_GET['filter']=='sanatorium') {
      $settings = 'sanatorium';
    }
    if (isset($_GET['filter']) and $_GET['filter']=='tours') {
      $settings ='tours';
    }
    return $settings;
  }

  public static function getItemById($item_id) {
    $food = new InfoBlock();
    $arr = $food->getItemsList([],['ID'=>$item_id], false, false, ['ID','NAME']);
    return $arr[0];
  }

  public static function getDetailPageUrl ($iblock_code, $item_code, $item_id) {
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

  public static function getPaginationUrlData () {
    $url = '';
    if (isset($_GET['filter'])) {
      $url = $url.'&filter='.$_GET['filter'];
    }
    if (isset($_GET['search'])) {
      $url = $url.'&search='.$_GET['search'];
    }
    if (isset($_GET['region'])) {
      $url = $url.'&region='.$_GET['region'];
    }
    if (isset($_GET['city'])) {
      $url = $url.'&city='.$_GET['city'];
    }
    return $url;
  }

  public static function getItemProperties ($iblock_id, $item_id, $order=[], $filter=[]) {
    $res = CIBlockElement::GetProperty($iblock_id, $item_id, $order, $filter);
    $arr = [];
    while ($item = $res->fetch()) {
      array_push($arr, $item);
    }
    return $arr;
  }

  public static function getServicesList ($arr) {
    foreach ($arr as $key => $value) {
      $arr[$key] = $value['VALUE'];
    }
    $services = new InfoBlock();
    $list = $services->getItemsList([],[
      'IBLOCK_CODE'=>'hotel_services',
      'PROPERTY_IN_FILTER_VALUE'=>'Y',
      'ID' => $arr,
    ], false, false,[
      'ID',
      'IBLOCK_ID',
      'NAME',
      'PROPERTY_NAME_BY',
      'PROPERTY_NAME_EN',
      'PROPERTY_IN_FILTER',
    ]);
    return $list;
  }

  public static function getItemPictures ($iblock_id, $item_id, $order=[], $filter=[]) {
    $arr = self::getItemProperties ($iblock_id, $item_id, $order, $filter);
    $list = [];
    for ($i=0; $i < 3; $i++) { 
      $list[$i] = CFile::GetFileArray($arr[$i]['VALUE'])['SRC'];
    }
    return $list;
  }

}


$this->IncludeComponentTemplate();


