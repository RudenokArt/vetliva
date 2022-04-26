<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */
//global $INTRANET_TOOLBAR;

//use Bitrix\Main\Context,
//	Bitrix\Main\Type\DateTime,
//	Bitrix\Main\Loader,
//	Bitrix\Iblock;

//if(!isset($arParams["CACHE_TIME"]))
//	$arParams["CACHE_TIME"] = 36000000;

/** @var string $arParams['ALL_PROPS'] */
/** @var SFTranslator $arParams['TRANSLATOR'] */
/** @var string $arParams['RETURN_TO_VARIABLE'] */


$arParams["IBLOCK_ID"] = trim($arParams["IBLOCK_ID"]);
$arParams["COUNT"] = trim($arParams["COUNT"]);
$arParams["PROPERTY_CODE"] = trim($arParams["PROPERTY_CODE"]);

$IBLOCK_ID = $arParams["IBLOCK_ID"];
$propName = $arParams["PROPERTY_CODE"];
$count = $arParams["COUNT"];

$cache_id = md5(serialize([$IBLOCK_ID, $propName, $count]));
$cache_dir = "/cit/prop_popular_val";

$obCache = new CPHPCache;
if($obCache->InitCache(36000, $cache_id, $cache_dir))
{
    $arElements = $obCache->GetVars();
}
elseif(CModule::IncludeModule("iblock") && $obCache->StartDataCache())
{

    /** */
    $arFilter = Array("IBLOCK_ID"=>$IBLOCK_ID, "ACTIVE" => "Y");
    $arGroupBy = array("PROPERTY_".$propName);
    $arNavStartParams = array('nTopCount' => $count);
    $res = CIBlockElement::GetList(Array("CNT"=>"desc"), $arFilter, $arGroupBy, $arNavStartParams);

    $idValProps = [];
    while($ob = $res->fetch()) {
        $idValProps[] = $ob['PROPERTY_'.$propName.'_VALUE'];
    }

    $res = CIBlockProperty::GetByID($propName, $IBLOCK_ID);
    $propInfo = $res->Fetch();

    $arSelect = Array("ID", "NAME");
    $arFilter = Array("IBLOCK_ID"=>$propInfo['LINK_IBLOCK_ID'], 'ID' => $idValProps);
    $res = CIBlockElement::GetList(Array("NAME"=>"asc"), $arFilter, false, false, $arSelect);
    while($ob = $res->fetch())
    {
        $arElements['PROPS'][] = $ob;
    }
    /** */

    global $CACHE_MANAGER;
    $CACHE_MANAGER->StartTagCache($cache_dir);
    $CACHE_MANAGER->RegisterTag("iblock_id_".$IBLOCK_ID);
//        $CACHE_MANAGER->RegisterTag("iblock_id_new");
    $CACHE_MANAGER->EndTagCache();

    $obCache->EndDataCache($arElements);
}

foreach ($arElements['PROPS'] as $ob) {
    $id = $ob['ID'];
    $ob['NAME'] = $arParams['TRANSLATOR']->getValueTranslation($id);
    $ob['CHECKED'] = $arParams['ALL_PROPS'][$id]['CHECKED'] ?? null;
    $ob['CONTROL_NAME'] = $arParams['ALL_PROPS'][$id]['CONTROL_NAME'];
    $ob['CONTROL_NAME_ALT'] = $arParams['ALL_PROPS'][$id]['CONTROL_NAME_ALT'];
    $ob['HTML_VALUE'] = $arParams['ALL_PROPS'][$id]['HTML_VALUE'];
    $ob['HTML_VALUE_ALT'] = $arParams['ALL_PROPS'][$id]['HTML_VALUE'];

    $arResult['ITEMS'][] = $ob;
    unset($arParams['ALL_PROPS'][$id]);
}

if ($arParams['RETURN_TO_VARIABLE'] == 'Y') {
    ob_start();
    $this->includeComponentTemplate();
    $propPopular = ob_get_contents();
    ob_end_clean();
    return $propPopular;
} else {
    $this->includeComponentTemplate();
}

