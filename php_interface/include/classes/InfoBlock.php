<?php 
/**
 * 
 */
class InfoBlock {

  function getCitysListForSamoTour () { // получить список городов (областей)
    return $this->getSectionsList([],['SECTION_ID'=>57], false, ['ID', 'NAME', 'CODE']);
  }

  function getHotelsListForSamoTour () {
    return $this->getItemsList([],[
      'SECTION_ID'=>68,
    ]);
  }

  function getSectionsList ($order=[], $filter=[], $quantity=false, $select=[], $nav=false) {
    $res = CIBlockSection::GetList($order, $filter, $quantity, $select, $nav);
    return $this->getList_fetch($res);
  }

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