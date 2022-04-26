<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

$curpage = str_replace("index.php", "", $APPLICATION->GetCurPage());
if(!empty($arResult["PROPERTIES"]["IS_EXCURSION_TOUR"]["VALUE"]) && $arResult["PROPERTIES"]["IS_EXCURSION_TOUR"]["VALUE"] == "Y"){
    $APPLICATION->SetPageProperty("canonical", "https://".$_SERVER["SERVER_NAME"].str_replace("cognitive-tourism", "tours-in-belarus", $curpage)."?booking[id][]=".$arResult["ID"]);
} elseif($arResult["PROPERTIES"]["IS_EXCURSION_TOUR"]["VALUE"] == "") {
    $APPLICATION->SetPageProperty("canonical", "https://".$_SERVER["SERVER_NAME"].str_replace("tours-in-belarus", "cognitive-tourism", $curpage)."?booking[id][]=".$arResult["ID"]);
}

if (LANGUAGE_ID == "ru") {
	//$APPLICATION->SetTitle($arResult["NAME"]);
    $APPLICATION->AddChainItem($arResult["NAME"]);
} else {
    $APPLICATION->SetTitle($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
    $APPLICATION->AddChainItem($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
}

$parameters = array("id"=>array($arResult["ID"]));
$result = $APPLICATION->IncludeComponent(
	"travelsoft:travelsoft.service.price.result", 
	"on.detail.page.render", 
	array(
		"RETURN_RESULT" => "Y",
		"FILTER_BY_PRICES_FOR_CITIZEN" => "N",
		"TYPE" => "excursions",
		"__BOOKING_REQUEST" => $parameters,
		"MP" => "Y",
		"COMPONENT_TEMPLATE" => "on.detail.page.render",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"CODE" => "",
		"MAKE_ORDER_PAGE" => "/booking/",
		"POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
		"INC_JQUERY" => "Y",
		"INC_MAGNIFIC_POPUP" => "Y",
		"INC_OWL_CAROUSEL" => "Y"
	),
	false
);
$min_price_item_id = '';
if(!empty($result))
    $min_price_item_id = $result[$arResult["ID"]]["CURRENCY_ID"] == 2 ? number_format($result[$arResult["ID"]]["PRICE"], 2, '.', '') : number_format(\travelsoft\Currency::getInstance()->convertCurrency($result[$arResult["ID"]]["PRICE"], 1, 2, true), 2, '.', '');

//$min_price_item_id = $result[$arResult["ID"]]["CURRENCY_ID"] == 2 ? number_format($result[$arResult["ID"]]["PRICE"], 2, '.', '') : number_format(\travelsoft\Currency::getInstance()->convertCurrency($result[$arResult["ID"]]["PRICE"], 1, 2, true), 2, '.', '');
?>

<script>
    var price = '<?=$min_price_item_id?>', item_id = '<?=$arResult["ID"]?>', date_from = '', date_to = '';
    $(".form-cn.tab-pane").each(function () {
        if($(this).hasClass("active")){
            from = moment($(this).find("form input[name='booking[date_from]']").val()*1000);
            date_from = from.format('YYYY-MM-DD');
            to = moment($(this).find("form input[name='booking[date_to]']").val()*1000);
            date_to = to.format('YYYY-MM-DD');
        }
    });
</script>
<script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
    ga('create', 'UA-92768574-1', 'auto');
    ga('set','dimension1',item_id);
    ga('set','dimension2','offerdetail');
    ga('set','dimension3',price);
    ga('set','dimension4',date_from);
    ga('set','dimension5',date_to);
    ga('send', 'pageview');
</script>

<? // Вывод разметки Schema
if($arResult["PROPERTIES"]["SCH_SHOW"]["VALUE"]):

$schema_brand_name = "Туры по Беларуси";
$schema_string_more = "";
$schemaName = $arResult['NAME'];
$site_suff = (LANGUAGE_ID != "en")?LANGUAGE_ID:"com";
$schemaURL = "https://"."vetliva.".$site_suff.$curpage."?booking[id][]=".$arResult["ID"];

if(preg_match("/tourism\\/cognitive-tourism\\/.+/", $_SERVER["REQUEST_URI"]) != 0):

$schema_brand_name = "Экскурсии по Беларуси";
$schema_string_more = <<< EOL

<script type="application/ld+json">
  {
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [{
    "@type": "ListItem",
    "position": 1,
    "name": "Главная",
    "item": "https://vetliva.ru/"
  },{
    "@type": "ListItem",
    "position": 2,
    "name": "Туризм",
    "item": "https://vetliva.ru/tourism/"
  },{
    "@type": "ListItem",
    "position": 3,
    "name": "Экскурсии",
    "item": "https://vetliva.ru/tourism/cognitive-tourism/"
  },{
    "@type": "ListItem",
    "position": 4,
    "name": "$schemaName",
    "item": "$schemaURL"
  }]
  }
</script>
EOL;

endif;

$schemaImage = $arResult["PROPERTIES"]["SCH_IMG_PATH"]["VALUE"];
$schemaDisc = $arResult["PROPERTIES"]["SCH_SHORT_DSCR"]["VALUE"]["TEXT"];
$schemaMpn = $schemaSku = $arResult["ID"];
$schemaRatingVal = $arResult["PROPERTIES"]["SCH_REVIEW_R"]["VALUE"];
$schemaAuthorRev = $arResult["PROPERTIES"]["SCH_REVIEW_A"]["VALUE"];
$schemaAgrRating = $arResult["PROPERTIES"]["SCH_RATING"]["VALUE"];
$schemaRatingCount = $arResult["PROPERTIES"]["SCH_REVIEW_COUNT"]["VALUE"];
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
    "name": "$schema_brand_name"
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

$stringSchema .= $schema_string_more;

$APPLICATION->AddHeadString($stringSchema);

endif;
?>