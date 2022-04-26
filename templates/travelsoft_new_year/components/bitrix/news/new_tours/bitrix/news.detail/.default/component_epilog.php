<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

if (LANGUAGE_ID == "ru") {
	//$APPLICATION->SetTitle($arResult["NAME"]);
    $APPLICATION->AddChainItem($arResult["NAME"]);
} else {
    $APPLICATION->SetTitle($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
    $APPLICATION->AddChainItem($arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]);
}

$parameters = array("id"=>array($arResult["ID"]));
$result = $APPLICATION->IncludeComponent(
    "travelsoft:travelsoft.service.price.result", "on.detail.page.render", Array(
        "RETURN_RESULT" => "Y",
        "FILTER_BY_PRICES_FOR_CITIZEN" => "N",
        "TYPE" => "excursions",
        "__BOOKING_REQUEST" => $parameters,
        "MP" => "Y"
    )
);

$min_price_item_id = '';
if(!empty($result))
    $min_price_item_id = $result[$arResult["ID"]]["CURRENCY_ID"] == 2 ? number_format($result[$arResult["ID"]]["PRICE"], 2, '.', '') : number_format(\travelsoft\Currency::getInstance()->convertCurrency($result[$arResult["ID"]]["PRICE"], 1, 2, true), 2, '.', '');

//$min_price_item_id = $result[$arResult["ID"]]["CURRENCY_ID"] == 2 ? number_format($result[$arResult["ID"]]["PRICE"], 2, '.', '') : number_format(\travelsoft\Currency::getInstance()->convertCurrency($result[$arResult["ID"]]["PRICE"], 1, 2, true), 2, '.', '');
?>

<script>
    var price = '<?=$min_price_item_id?>', item_id = '<?=$arResult["ID"]?>', date_from = '', date_to = '';
    $(".form-cn.form-hotel.tab-pane").each(function () {
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