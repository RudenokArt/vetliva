<?php 
/**
 * 
 */
class InfoBlock {

  function getProperties ($iblock_id, $item_id, $order=[], $filter=[]) {
    $res = CIBlockElement::GetProperty($iblock_id, $item_id, $order, $filter);
    return $this->getList_fetch($res);
  }

  function getCitysListForSamoTour () { // получить список городов (областей)
    $arr = $this->getSectionsList([],['SECTION_ID'=>57], false, ['ID', 'NAME', 'CODE']);
    return $this->json_mb_encoder($arr);
  }

  function getHotelsListForSamoTour () { // получить список отелей в городе
    $arr = $this->getItemsList([],[
      'SECTION_ID'=>$_GET['getHotelsList'],
    ], false, false, ['ID', 'NAME', 'CODE']);
    return $this->json_mb_encoder($arr);
  }

  function getSectionsList ($order=[], $filter=[], $quantity=false, $select=[], $nav=false) {
    $res = CIBlockSection::GetList($order, $filter, $quantity, $select, $nav);
    return $this->getList_fetch($res);
  }

  function getItemsList ($order=[], $filter=[], $group=false, $nav=false, $select=[]) {
    $res = CIBlockElement::GetList($order, $filter, $group, $nav, $select);
    return $this->getList_fetch($res);
  }

  function getItemsListPage ($order=[], $filter=[], $group=false, $nav=false, $select=[]) {
    $res = CIBlockElement::GetList($order, $filter, $group, $nav, $select);
    return ['items'=>$this->getList_fetch($res),
    'pagination'=>[
      'page_count' => $res->NavPageCount, 
      'page_number' => $res->NavPageNomer,
    ]];
  }
  
  function getList_fetch ($res) {
    $arr = [];
    while ($item = $res->fetch()) {
      array_push($arr, $item);
    }
    return $arr;
  }

  function json_mb_encoder ($arr) {
    return json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  }

}


?>