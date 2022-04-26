<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if ($_POST['action']=='increase_query' && $_POST['element_id']) {
    CModule::IncludeModule("iblock"); 
    $startValue = 0;
    $db_props = CIBlockElement::GetProperty(85, $_POST['element_id'], array("sort" => "asc"), Array("CODE"=>"COUNT_CLICK"));
            if($ar_props = $db_props->Fetch())
                $startValue = $ar_props["VALUE"];
    $startValue++;
    CIBlockElement::SetPropertyValuesEx($_POST['element_id'], false, array('COUNT_CLICK' => $startValue),"DoNotValidateLists");
}