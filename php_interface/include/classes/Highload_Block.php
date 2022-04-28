<?php Bitrix\Main\Loader::includeModule("highloadblock");
/**
 * 
 */
class Highload_Block extends InfoBlock {

  function __construct () {
    // $this->ts_services_list = $this->getTsServicesList();
  }



  function getTsServicesList () { // Получить номерной фонд
    $highloadblock = $this->getHighloadBlock([
      'filter'=>['TABLE_NAME' => 'ts_services',],
    ]);
    return $this->getHighloadBlockItems($highloadblock);
  }

  function getHighloadBlockItems ($highloadblock, $filter=[]) {
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
      $placement = $this->getItemsList([],['ID'=>$value['UF_IBLOCK_ELEMENT_ID']],false,false,['ID', 'NAME'])[0];
      $value['placement_id'] = $placement['ID'];
      $value['placement_name'] = $placement['NAME'];
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