<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$exchange_rates = new InfoBlock();
$arResult = $exchange_rates->getItemsListPage(['ID'=>'DESC'],['IBLOCK_CODE'=>'currency_courses'],false,[
  'nPageSize'=>25,
  'iNumPage'=>$_GET['page_number'],
],[
  'ID', 'NAME', 'PROPERTY_USD', 'PROPERTY_EUR', 'PROPERTY_RUB',
]);

$this->IncludeComponentTemplate();
?>