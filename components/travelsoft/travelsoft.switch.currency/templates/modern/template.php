<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if (empty($arResult['CURRENCY']))
    return;
$lis = "";
foreach ($arResult['CURRENCY'] as $id => $arCurrency) {
    $page = $APPLICATION->GetCurPageParam("currency=" . $arCurrency["iso"], array("currency"));
    $lis .= "<li><a rel=\"nofollow\" onclick=\"return setSwitchCurrencyURL(this)\" href=\"" . $page . "\"><b>" . $arCurrency["iso"] . "</b> <span>" . GetMessage($arCurrency["iso"]) . "</span></a></li>";
}
?>

<li class="switch sw-currency">
    <a href="javascript:void(0)"><?= $arResult['CURRENT_CURRENCY']["iso"] ?></a>
    <ul class="sub-menu currency">
<?= $lis ?>
    </ul>
</li>

<li class="mobile-currency">
	<a href="#header-currency" class="header-currency"><span><?= $arResult['CURRENT_CURRENCY']["iso"] ?></span></a>
                                
		<div id="header-currency" class="header-cur-form mfp-hide">	
		   <div class="mfp-head"><?=GetMessage('CHOOSE_CURRENCY')?></div>
			<ul class="sub-menu currency">
				<?
                    foreach ($arResult['CURRENCY'] as $id => $arCurrency) {
                        $page = $APPLICATION->GetCurPageParam("currency=" . $arCurrency["iso"], array("currency"));?>
                        <li>
                            <input id="currency<?=$arCurrency["iso"]?>" type="radio" <?if ($arCurrency["iso"]==$arResult['CURRENT_CURRENCY']["iso"]):?>checked=""<?endif;?> onclick="location.href='<?= $page ?>'"/>
                            <label for="currency<?=$arCurrency["iso"]?>"><b><?=$arCurrency["iso"]?></b> <span><?=GetMessage($arCurrency["iso"])?></span></label>
                        </li>
                    <?}
                ?>
			</ul>
																	
		</div>					
</li>
<script>
    function setSwitchCurrencyURL(context) {

        if (window.location.search.indexOf("currency") !== -1) {
            
            context.href = window.location.search.replace(/currency=(BYN|EUR|USD|RUB)/, "currency=" + jQuery(context).find("b").text());
        } else if (window.location.search.indexOf("?") !== -1) {
            
            context.href = window.location.search + "&currency=" + jQuery(context).find("b").text();
        } else {
            
            context.href = window.location.search + "?currency=" + jQuery(context).find("b").text();
        }
        return true;
    }
    $(document).ready(function (){
						$(".header-currency").magnificPopup({
						type: "inline",
						mainClass: 'mfp-currency-mobile',
						midClick: true
					});
													
			});
</script>