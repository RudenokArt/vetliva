<?php Bitrix\Main\Loader::includeModule("highloadblock");
/**
 * 
 */
class Highload_Block extends InfoBlock {

  function __construct () {
    // $this->ts_services_list = $this->getTsServicesList();
  }

  function getBookingsForService ($service_id, $dete) {
    $highloadblock = $this->getHighloadBlock([
      'filter'=>['TABLE_NAME' => 'bookings',],
    ]);
    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($highloadblock);
    $entity_data_class = $entity->getDataClass();
    $rsData = $entity_data_class::getList([
      'filter' => ['UF_IBLOCK_ELEMENT_ID'=>2322],
      'select' => ['ID','UF_SERVICE_JSON'],
    ]);
    $arr = [];
    foreach ($rsData as $key => $value) {
      $value['UF_SERVICE_JSON'] = json_decode($value['UF_SERVICE_JSON'],true);
      $value['UF_SERVICE_ID'] = $value['UF_SERVICE_JSON']['parts']['roomId'];
      $value['dateBegin'] = $value['UF_SERVICE_JSON']['dateBegin'];
      $value['dateEnd'] = $value['UF_SERVICE_JSON']['dateEnd'];;
      if (
        $service_id == $value['UF_SERVICE_ID']) {
        array_push($arr, $value);
      }
    }
    return $arr;
  }

  function getTsQuotasForService ($service_id) {
    $highloadblock = $this->getHighloadBlock([
      'filter'=>['TABLE_NAME' => 'ts_quotas',],
    ]);
    // $arr = $this->getHighloadBlockItems($highloadblock, ['UF_SERVICE_ID'=>$service_id]);
    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($highloadblock);
    $entity_data_class = $entity->getDataClass();
    $rsData = $entity_data_class::getList(['filter' => ['UF_SERVICE_ID'=>$service_id]]);
    $arr = [];
    foreach ($rsData as $key => $value) {
      $value['date'] = ConvertTimeStamp($value['UF_DATE']);
      array_push($arr, $value);
    }
    return $arr;
  }

  function getTsServicesList () { // Получить номерной фонд
    $highloadblock = $this->getHighloadBlock([
      'filter'=>['TABLE_NAME' => 'ts_services',],
    ]);
    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($highloadblock);
    $entity_data_class = $entity->getDataClass();
    $rsData = $entity_data_class::getList([
      'select'=>[
        'ID',
        'UF_IBLOCK_ELEMENT_ID',
        'UF_NAME',
        'UF_SERVICE_TYPE_NAME',
        'UF_PEOPLE',
        'UF_ADULTS',
        'UF_CHILDREN',
        'UF_MIN_PEOPLE',
      ]
    ]);
    $arr = [];
    foreach ($rsData as $key => $value) {
      $placement = $this->getItemsList([],[
        'ID'=>$value['UF_IBLOCK_ELEMENT_ID'],
        'ACTIVE' => 'Y',
      ],false,false,['ID', 'NAME'])[0];
      $value['placement_id'] = $placement['ID'];
      $value['placement_name'] = $placement['NAME'];
      array_push($arr, $value);
    }
    return $arr;
  }

  function getHighloadBlockItems ($highloadblock, $filter=[]) {
    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($highloadblock);
    $entity_data_class = $entity->getDataClass();
    $rsData = $entity_data_class::getList(['filter' => $filter]);
    $arr = [];
    foreach ($rsData as $key => $value) {
      array_push($arr, $value);
    }
    return $arr;
  }

  function getHighloadBlock ($filter = []) {
    $arr = $this->getList_fetch(\Bitrix\Highloadblock\HighloadBlockTable::getList($filter));
    return $arr[0];
  }

  
}



?>