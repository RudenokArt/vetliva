<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

if($arResult["IBLOCK_ID"] == 31 || $arResult["IBLOCK_ID"] == 30){

	$town_desc = "";
	$region_desc = "";
	if (!empty($arResult["PROPERTIES"]["REGION"]["VALUE"])) {
		$prop_town_prep = getIBElementProperties($arResult["PROPERTIES"]["TOWN"]["VALUE"]);
		if (LANGUAGE_ID != "ru") {
			$prop_town = getIBElementProperties($arResult["PROPERTIES"]["TOWN"]["VALUE"]);
			$prop_reg = getIBElementProperties($arResult["PROPERTIES"]["REGION"]["VALUE"]);
			$region_desc = $prop_reg["NAME" . POSTFIX_PROPERTY]["VALUE"];
			$town_desc = $prop_town["NAME" . POSTFIX_PROPERTY]["VALUE"];
			$town_desc_prep = $prop_town_prep["CN_NAME_gde" . POSTFIX_PROPERTY]["VALUE"];
			$address_desc = $arResult["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"];
		}
		else {
			$prop_town = getIBElementFields($arResult["PROPERTIES"]["TOWN"]["VALUE"]);
			$prop_desc = getIBElementFields($arResult["PROPERTIES"]["REGION"]["VALUE"]);
			$region_desc = $prop_desc["NAME"];
			$town_desc = $prop_town["NAME"];
			$town_desc_prep = $prop_town_prep["CN_NAME_gde" . POSTFIX_PROPERTY]["VALUE"];
			$address_desc = $arResult["PROPERTIES"]["ADDRESS"]["VALUE"];
		}
	}
	
	if (LANGUAGE_ID == "ru") {
		$APPLICATION->SetPageProperty("title", $arResult["NAME"] . " " . $town_desc_prep . ":  адрес, фото, описание");
		$APPLICATION->SetTitle($arResult["NAME"]);
		$APPLICATION->AddChainItem($arResult["NAME"]);
		$APPLICATION->SetPageProperty("description", $arResult["NAME"] . " по адресу " . $address_desc . 
										", " . $town_desc . ", " . $region_desc . ". Фото и описание заведения в Беларуси");
	} else {
		$title_suffix = "";
		$desc_suffix = "";
		if(LANGUAGE_ID == "by") {
			$title_suffix = ": адрас, фота, апісанне";
			$desc_suffix = ". Фота і апісанне ўстановы ў Беларусі.";
			$address_prep = " па адрасе ";
		}
		else {
			$title_suffix = ": address, photos, review";
			if($arResult["IBLOCK_ID"] == 30)
				$desc_suffix = ". Photos and review of the shop in Belarus.";
			else
				$desc_suffix = ". Photos and review of the " . $arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"] . " in Belarus.";
			$address_prep = " at address ";
		}
	
		$APPLICATION->SetPageProperty("title", $arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"] . 
										" " . $town_desc_prep . $title_suffix);
		$APPLICATION->SetPageProperty("keywords", $arResult["PROPERTIES"]["KEYWORDS".POSTFIX_PROPERTY]["VALUE"]);
		$APPLICATION->SetPageProperty("description", $arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"] . 
							$address_prep . $address_desc . ", " . $town_desc . ", " . $region_desc . $desc_suffix);
		$APPLICATION->SetTitle($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
		$APPLICATION->AddChainItem($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
	}
}
else
{
	if (LANGUAGE_ID == "ru") {
		$APPLICATION->SetPageProperty("title", $arResult["NAME"]);
		$APPLICATION->SetTitle($arResult["NAME"]);
		$APPLICATION->AddChainItem($arResult["NAME"]);
	} else {
	
		$APPLICATION->SetTitle($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
		$APPLICATION->AddChainItem($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
		$APPLICATION->SetPageProperty("title", $arResult["PROPERTIES"]["TITLE".POSTFIX_PROPERTY]["VALUE"]);
		$APPLICATION->SetPageProperty("keywords", $arResult["PROPERTIES"]["KEYWORDS".POSTFIX_PROPERTY]["VALUE"]);
		$APPLICATION->SetPageProperty("description", $arResult["PROPERTIES"]["DESCRIPTION".POSTFIX_PROPERTY]["VALUE"]);
	}
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