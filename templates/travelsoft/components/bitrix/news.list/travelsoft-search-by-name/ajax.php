<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
if ($_REQUEST['term']!='') {
    $source =[];
    $additional_filtr = [];
    //if (isset($_REQUEST['PROPERTY_IS_EXCURSION_TOUR'])) $additional_filtr = ['PROPERTY_IS_EXCURSION_TOUR'=>false];
    //elseif (isset($_REQUEST['!PROPERTY_IS_EXCURSION_TOUR'])) $additional_filtr = ['!PROPERTY_IS_EXCURSION_TOUR'=>false];
    if (isset($_REQUEST['PROPERTY_PACKAGE']))  $additional_filtr = ['PROPERTY_PACKAGE'=>false];
    if ($_REQUEST['IBLOCK_ID'] == 83) {
        $arSelect = Array("ID", "IBLOCK_ID", "NAME",  "DETAIL_PAGE_URL");
        $arFilter = Array("IBLOCK_ID" =>$_REQUEST['IBLOCK_ID'], '?NAME'=>$_REQUEST['term'], "ACTIVE" => "Y");
        $arFilter = array_merge($arFilter,$additional_filtr);
        $res = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter, false, false, $arSelect);
        while ($item = $res->GetNext()) {
			$source[] = array("label" => $item['NAME'], "value" => $item['NAME'], "link" => $item['DETAIL_PAGE_URL'] . "?booking[id][]=" . $item['ID']);
        }
    }
    elseif ($_REQUEST['POSTFIX_PROPERTY']=='') {
        $arSelect = Array("ID", "IBLOCK_ID", "NAME",  "DETAIL_PAGE_URL", "PROPERTY_IS_EXCURSION_TOUR");
        $arFilter = Array("IBLOCK_ID" =>$_REQUEST['IBLOCK_ID'], '?NAME'=>$_REQUEST['term'], "ACTIVE" => "Y");
        $arFilter = array_merge($arFilter,$additional_filtr);
        $res = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter, false, false, $arSelect);
        while ($item = $res->GetNext()) {
            //if (isset($_REQUEST['!PROPERTY_IS_EXCURSION_TOUR'])) $item['DETAIL_PAGE_URL'] = str_replace('/cognitive-tourism/','/tours-in-belarus/',$item['DETAIL_PAGE_URL']);
            if ($item['PROPERTY_IS_EXCURSION_TOUR_VALUE']!='') $item['DETAIL_PAGE_URL'] = str_replace('/cognitive-tourism/','/tours-in-belarus/',$item['DETAIL_PAGE_URL']);
            $source[] = array("label" => $item['NAME'], "value" => $item['NAME'], "link" => $item['DETAIL_PAGE_URL'] );
        }
    }
    else {
        $arSelect = Array("ID", "IBLOCK_ID", "NAME",  "DETAIL_PAGE_URL", "PROPERTY_NAME".$_REQUEST['POSTFIX_PROPERTY'], "PROPERTY_IS_EXCURSION_TOUR");
        $arFilter = Array("IBLOCK_ID" =>$_REQUEST['IBLOCK_ID'], '?PROPERTY_NAME'.$_REQUEST['POSTFIX_PROPERTY']=>$_REQUEST['term'], "ACTIVE" => "Y");
        $arFilter = array_merge($arFilter,$additional_filtr);
        $res = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter, false, false, $arSelect);
        while ($item = $res->GetNext()) {
            //if (isset($_REQUEST['!PROPERTY_IS_EXCURSION_TOUR'])) $item['DETAIL_PAGE_URL'] = str_replace('/cognitive-tourism/','/tours-in-belarus/',$item['DETAIL_PAGE_URL']);
            if ($item['PROPERTY_IS_EXCURSION_TOUR_VALUE']!='' ) $item['DETAIL_PAGE_URL'] = str_replace('/cognitive-tourism/','/tours-in-belarus/',$item['DETAIL_PAGE_URL']);
            $source[] = array("label" => $item['PROPERTY_NAME'.$_REQUEST['POSTFIX_PROPERTY'].'_VALUE'], "value" => $item['PROPERTY_NAME'.$_REQUEST['POSTFIX_PROPERTY'].'_VALUE'], "link" => $item['DETAIL_PAGE_URL'] );
        }
    }
   echo json_encode($source);
}
?>