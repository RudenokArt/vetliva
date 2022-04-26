<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

global $USER;
$userId = $USER->GetID();

$arElementId = array();
$arElements = array();

/*$db_hotel = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>PLACEMENTS_IBLOCK_ID, "ACTIVE"=>"Y", "PROPERTY_USER"=>$userId), false, false, Array("ID"));
if($db_hotel->SelectedRowsCount() > 0) {
    while ($ar_hotels = $db_hotel->GetNext()) {
        $arElementId[] = $ar_hotels["ID"];
    }
}

$db_san = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>SANATORIUM_IBLOCK_ID, "ACTIVE"=>"Y", "PROPERTY_USER"=>$userId), false, false, Array("ID"));
if($db_san->SelectedRowsCount() > 0) {
    while ($ar_san = $db_san->GetNext()) {
        $arElementId[] = $ar_san["ID"];
    }
}*/

CModule::IncludeModule("highloadblock");
$hlbl = SERVICES_BOOKING_HL_BLOCK;
$entity_table_name = $hlblock['services'];
$arResultBlock = array();
$hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);
$entity_data_class = $entity->getDataClass();
$sTableID = 'ts_'.$entity_table_name;
$db_tours = $entity_data_class::getList(array("select" => array("UF_IBLOCK_ELEMENT_ID"), "filter" => array("UF_USER_ID" => $userId), "order" => array("ID"=>"ASC")));
$db_tours_count = $db_tours->getSelectedRowsCount();
if($db_tours_count > 0){
    while($ar_tours = $db_tours->fetch()){
        $arElementId[] = $ar_tours["UF_IBLOCK_ELEMENT_ID"];
    }

}

if(!empty($arElementId))
    $GLOBALS[$this->arParams["FILTER_NAME"]] = array("PROPERTY_ITEM" => $arElementId, "ACTIVE"=> "");
else
	$GLOBALS[$this->arParams["FILTER_NAME"]] = array("ID" => -1);