<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$belavia_search_form = new BelaviaSearchForm();
$arResult['locations_list'] = $belavia_search_form->locations_list;
$arResult['availible_locations'] = $belavia_search_form->availible_locations;
$this->IncludeComponentTemplate();
?>

<?php 
/**
 * 
 */
class BelaviaSearchForm {
  
  public $locations_list;
  public $availible_locations;
  function __construct()  {
    $str = file_get_contents('https://ibe.belavia.by/api/locales/location/'.LANGUAGE_ID);
    $arr = json_decode($str,true)['city'];
    $this->locations_list = json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $str = file_get_contents('https://ibe.belavia.by/api/settings?jipcc=B2DC');
    $arr = json_decode($str,true)['routeSetting']['routes'];
    $this->availible_locations = json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
  }
}

?>