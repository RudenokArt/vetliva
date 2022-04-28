<?php 
/**
 * 
 */
class InfoBlock {

  function getItemsList ($order=[], $filter=[], $group=false, $nav=false, $select=[]) {
    $res = CIBlockElement::GetList($order, $filter, $group, $nav, $select);
    return $this->getList_fetch($res);
  }
  
  function getList_fetch ($res) {
    $arr = [];
    while ($item = $res->fetch()) {
      array_push($arr, $item);
    }
    return $arr;
  }

}


?>