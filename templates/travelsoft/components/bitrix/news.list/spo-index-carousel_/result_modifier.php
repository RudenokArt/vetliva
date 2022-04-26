<?
$idb = array(7,33);
$item = array();
foreach ($arResult["ITEMS"] as &$arItem) $arItem['TYPE'] = 'sanatorium';
foreach ($idb as $value) {
    if ($value==33) {
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_PICTURE", "DETAIL_PAGE_URL");
        $arFilter = Array("PROPERTY_IS_EXCURSION_TOUR"=>false,"IBLOCK_ID" =>$value, "ACTIVE" => "Y");
        $res = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter, false, Array("nTopCount"=>2), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $item = $ob->GetFields();
            $item['TYPE'] = 'excursionstours';
            $item["PROPERTIES"] = $ob->GetProperties();
            $arResult["ITEMS"][] = $item;
        }
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_PICTURE", "DETAIL_PAGE_URL");
        $arFilter = Array("!PROPERTY_IS_EXCURSION_TOUR"=>false,"IBLOCK_ID" =>$value, "ACTIVE" => "Y");
        $res = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter, false, Array("nTopCount"=>2), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $item = $ob->GetFields();
            $item['TYPE'] = 'tours';
            $item["PROPERTIES"] = $ob->GetProperties();
            $arResult["ITEMS"][] = $item;
        }
    }
    else {
        $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM", "PREVIEW_PICTURE", "DETAIL_PAGE_URL");
        $arFilter = Array("IBLOCK_ID" =>$value, "ACTIVE" => "Y");
        $res = CIBlockElement::GetList(Array("SORT" => "ASC"), $arFilter, false, Array("nTopCount"=>2), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $item = $ob->GetFields();
            $item['TYPE'] = 'placement';
            $item["PROPERTIES"] = $ob->GetProperties();
            $arResult["ITEMS"][] = $item;
        }
    }
    
}
$sortplace = ['sanatorium', 'placement', 'excursionstours', 'tours'];
shuffle($sortplace);
$randItems = $types = [];
foreach ($arResult["ITEMS"] as $item) $types[$item['TYPE']][] = $item;
for ($i=0; $i<2; $i++) {
    foreach ($sortplace as $type) {
        $rand = $i;//rand(0, (count($types[$type])-1));
        if (!empty($types[$type][$rand])) $randItems[] = $types[$type][$rand];
        unset($types[$type][$rand]);
    }
}
$arResult["ITEMS"] = $randItems;
//dm($arResult["ITEMS"]);
?>