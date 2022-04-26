<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

$region_desc = "";
if (!empty($arResult["PROPERTIES"]["REGION"]["VALUE"])) {
    if (LANGUAGE_ID != "ru") {
		$prop_desc = getIBElementProperties($arResult["PROPERTIES"]["REGION"]["VALUE"]);
        $region_desc = $prop_desc["NAME" . POSTFIX_PROPERTY]["VALUE"];
    }
	else {
		$prop_desc = getIBElementFields($arResult["PROPERTIES"]["REGION"]["VALUE"]);
    	$region_desc = $prop_desc["NAME"];
	}
}

if (LANGUAGE_ID == "ru") {
	$APPLICATION->SetPageProperty("title", $arResult["NAME"] . ", Беларусь: история, описание, фото");
    $APPLICATION->SetTitle($arResult["NAME"] . ", Беларусь: история, описание, фото");
    $APPLICATION->AddChainItem($arResult["NAME"]);
    $APPLICATION->SetPageProperty("description", $arResult["NAME"] . " в " . $region_desc . ", Беларусь. История, описание и фото достопримечательности Белоруссии.");
} else {
	$title_suffix = "";
	$desc_suffix = "";
	if(LANGUAGE_ID == "by") {
		$title_suffix = ", Беларусь: гісторыя, апісанне, фота";
		$desc_suffix = ", Беларусь. Гісторыя, апісанне і фота славутасці Беларусі.";
		$preposition = " у ";
	}
	else {
		$title_suffix = ", Belarus: history, review, photos";
		$desc_suffix = ", Belarus. History, review and photos of the tourist attraction in Belarus.";
		$preposition = " in ";
	}

	$APPLICATION->SetPageProperty("title", $arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"] . $title_suffix);
    $APPLICATION->SetPageProperty("keywords", $arResult["PROPERTIES"]["KEYWORDS".POSTFIX_PROPERTY]["VALUE"]);
    $APPLICATION->SetPageProperty("description", $arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"] . $preposition . $region_desc . $desc_suffix);
	$APPLICATION->SetTitle($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
    $APPLICATION->AddChainItem($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);

//    dm($arResult["PROPERTIES"]["TITLE".POSTFIX_PROPERTY]["VALUE"]);
//    dm($arResult["PROPERTIES"]["KEYWORDS".POSTFIX_PROPERTY]["VALUE"]);
//    dm($arResult["PROPERTIES"]["DESCRIPTION".POSTFIX_PROPERTY]["VALUE"]);
}

//Добавление OpenGraph разметки
if(preg_match("/blog\\/.+/", $_SERVER["REQUEST_URI"]) != 0):

$site_suff = (LANGUAGE_ID != "en")?LANGUAGE_ID:"com";
$curpage = str_replace("index.php", "", $APPLICATION->GetCurPage());
$articleName = $arResult['NAME'];
$articleURL = "https://"."vetliva.".$site_suff.$curpage;
$articleDesc = $APPLICATION->GetPageProperty("description");
$articleImage = $arResult["PROPERTIES"]["SCH_IMG_PATH"]["VALUE"];

$stringOpenGraph =  <<< EOL
<!--OPEN GRAPH-->
<meta property="og:title" content="$articleName" />
<meta property="og:type" content="article" />
<meta property="og:url" content="$articleURL" />
<meta property="og:description" content="$articleDesc" />
<meta property="og:image" content="$articleImage" />
<meta property="og:image:secure_url" content="$articleImage" />
<meta property="og:image:type" content="image/png" />
<!--OPEN GRAPH-->
EOL;

$APPLICATION->AddHeadString($stringOpenGraph);

endif;