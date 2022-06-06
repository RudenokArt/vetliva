<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$exchange_rates = new InfoBlock();
if ($arParams['VIDGET']=='Y') {
  $arResult['current'] = $exchange_rates->getItemsList([],['IBLOCK_CODE'=>'currency_courses', 'CODE'=>date('d-m-Y')],false, false,[
    'ID', 'NAME', 'PROPERTY_USD', 'PROPERTY_EUR', 'PROPERTY_RUB',
  ]);
} else {
  $arResult = $exchange_rates->getItemsListPage(Array(
       "LOGIC" => "AND",
       array("ID" => "DESC"),
       array("DATE_ACTIVE_FROM" => "ASC")),['IBLOCK_CODE'=>'currency_courses'],false,[
    'nPageSize'=>25,
    'iNumPage'=>$_GET['page_number'],
  ],[
    'ID', 'NAME', 'PROPERTY_USD', 'PROPERTY_EUR', 'PROPERTY_RUB',
  ]);
}


$this->IncludeComponentTemplate();
?>