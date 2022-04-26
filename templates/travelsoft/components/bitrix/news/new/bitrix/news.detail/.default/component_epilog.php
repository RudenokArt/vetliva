<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
\Bitrix\Main\Loader::includeModule("travelsoft.currency");

global $APPLICATION;

if (LANGUAGE_ID == "ru") {
	//$APPLICATION->SetTitle($arResult["NAME"]);
    $APPLICATION->AddChainItem($arResult["NAME"]);
} else {
    $APPLICATION->SetTitle($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
    $APPLICATION->AddChainItem($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
}

if($arResult["IBLOCK_ID"] == 8 && $arResult["PROPERTIES"]["SCH_SHOW"]["VALUE"]):

$curpage = str_replace("index.php", "", $APPLICATION->GetCurPage());
$site_suff = (LANGUAGE_ID != "en")?LANGUAGE_ID:"com";
$schemaName = $arResult['NAME'];
$schemaImage = $arResult["PROPERTIES"]["SCH_IMG_PATH"]["VALUE"];
$schemaDisc = $arResult["PROPERTIES"]["SCH_SHORT_DSCR"]["VALUE"]["TEXT"];
$schemaMpn = $schemaSku = $arResult["ID"];
$schemaRatingVal = $arResult["PROPERTIES"]["SCH_REVIEW_R"]["VALUE"];
$schemaAuthorRev = $arResult["PROPERTIES"]["SCH_REVIEW_A"]["VALUE"];
$schemaAgrRating = $arResult["PROPERTIES"]["SCH_RATING"]["VALUE"];
$schemaRatingCount = $arResult["PROPERTIES"]["SCH_REVIEW_COUNT"]["VALUE"];
$schemaURL = "https://"."vetliva.".$site_suff.$curpage."?booking[id][]=".$arResult["ID"];//"https://vetliva.".$site_suff."/tourism/cognitive-tourism/".$arResult["CODE"]."/"."?booking[id][]=".$arResult["ID"];
$schemaPrice = $arResult["PROPERTIES"]["SCH_PRICE"]["VALUE"];
$schemaPriceUntil = "2019-12-31"; // Не известно

$stringSchema =  <<< EOL
 <script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "$schemaName",
  "image": [
  	"$schemaImage"
   ],
  "description": "$schemaDisc",
  "sku": "$schemaSku",
  "mpn": "$schemaMpn",
  "brand": {
    "@type": "Brand",
    "name": "Санатори по Беларуси"
  },
  "review": {
    "@type": "Review",
    "reviewRating": {
      "@type": "Rating",
      "ratingValue": "$schemaRatingVal",
      "bestRating": "5"
    },
    "author": {
      "@type": "Person",
      "name": "$schemaAuthorRev"
    }
  },
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "$schemaAgrRating",
    "reviewCount": "$schemaRatingCount"
  },
  "offers": {
    "@type": "Offer",
    "url": "$schemaURL",
    "priceCurrency": "BYN",
    "price": "$schemaPrice",
    "priceValidUntil": "$schemaPriceUntil",
    "itemCondition": "https://schema.org/UsedCondition",
    "availability": "https://schema.org/InStock",
    "seller": {
      "@type": "Organization",
      "name": "VETLIVA"
    }
  }
}
</script>
EOL;

$APPLICATION->AddHeadString($stringSchema);

endif;

?>