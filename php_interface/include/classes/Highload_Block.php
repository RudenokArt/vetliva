<?php Bitrix\Main\Loader::includeModule("highloadblock");
/**
 * 
 */
class Highload_Block {

  public static function getHighloadBlockItems ($highloadblock) {
    $entity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($highloadblock);
    $entity_data_class = $entity->getDataClass();
    $rsData = $entity_data_class::getList(['filter'=>[]]);
    $arr = [];
    foreach ($rsData as $key => $value) {
      array_push($arr, $value);
    }
    return $arr;
  }

  public static function getHighloadBlock ($filter = []) {
    $arr = Highload_Block::getList_fetch(\Bitrix\Highloadblock\HighloadBlockTable::getList($filter));
    return $arr[0];
  }

  public static function getList_fetch ($entity) {
    $arr = [];
    while ($item = $entity->fetch()) {
      array_push($arr, $item);
    }
    return $arr;
  }
}



?>