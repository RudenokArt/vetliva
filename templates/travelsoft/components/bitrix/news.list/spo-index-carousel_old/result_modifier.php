<?
$idb = array(7,33);
$item = array();
foreach ($idb as $value) {
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_PICTURE", "DETAIL_PAGE_URL");
    $arFilter = Array("IBLOCK_ID" =>$value, "ACTIVE" => "Y");
    $res = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter, false, Array("nTopCount"=>4), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $item = $ob->GetFields();
        $item["PROPERTIES"] = $ob->GetProperties();
        $arResult["ITEMS"][] = $item;
    }
}
?>