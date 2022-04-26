<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

?>

<script>
    (function ($, document) {

<? include_once "scroll_js.php"; ?>

        $(document).ready(function () {
            $.runSearchOffersResultDialog({
                lazyLoad: <? if ($arResult["NEED_LAZY_LOAD"]): ?>true<? else: ?>false<? endif ?>,
                                sessid: "<?= bitrix_sessid() ?>",
                                addToCartAnchor: ".add-to-cart",
                                dynamic_calculation_placements_add2cart: <?= \json_encode($arResult["dynamic_calculation_placements_add2cart"] || [])?>,
                                initAddToCart: true,
                                initCitizenPricePopup: <? if ($arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y"): ?>true<? else: ?>false<? endif ?>,
                                                initServicePopup: <? if (isset($arResult["SERVICE_POPUP_JS"]) && $arResult["SERVICE_POPUP_JS"]): ?>true<? else: ?>false<? endif ?>,
                                                                initRatePopup: <? if (isset($arResult["RATE_POPUP_JS"]) && $arResult["RATE_POPUP_JS"]): ?>true<? else: ?>false<? endif ?>,
                                                                                initCancellationPolicyPopup: <? if (isset($arResult["CANCELLATION_POLICY_POPUP_JS"]) && $arResult["CANCELLATION_POLICY_POPUP_JS"]): ?>true<? else: ?>false<? endif ?>,
                                                                                                rateAnchor: ".open-rate-popup",
                                                                                                cancellationPolicyAnchor: ".open-cancellation-policy-popup",
                                                                                                servicesAnchor: ".open-service-popup",
                                                                                                citizenPriceAnchor: "select[name='citizen_price']",
                                                                                                specifyingFormBtnAnchor: ".specifying-form-btn",
                                                                                                sparams: <?= \Bitrix\Main\Web\Json::encode(array_merge(array("objectType" => $arParams["TYPE"]), $arParams["__BOOKING_REQUEST"], array("code" => $arParams["CODE"]))) ?>,
                                                                                                redirect: "<?= $arParams["MAKE_ORDER_PAGE"] ?>",
                                                                                                insertContainer: ".hl-availability",
                                                                                                messages: {
                                                                                                    no_result: "<?= GetMessage("NO_PRICES_CITIZEN") ?>",
                                                                                                    loadingMessage: '<?= GetMessage("LOADING_MESSAGE") ?>',
                                                                                                    maxPeople: "<?= GetMessage("MAX_PEOPLE") ?>",
                                                                                                    cntSofaBad: "<?= GetMessage("CNT_SOFA_BAD") ?>",
                                                                                                    square: "<?= GetMessage("SQUARE") ?>",
                                                                                                    servicesIn: "<?= GetMessage("SERVICES_IN") ?>",
                                                                                                    cntAddPlaces: "<?= GetMessage("ADD_PLACES") ?>",
                                                                                                    cntBad1: "<?= GetMessage("BAD1") ?>",
                                                                                                    cntBad2: "<?= GetMessage("BAD2") ?>",
                                                                                                    cntMainPlaces: "<?= GetMessage("MAIN_PLACES") ?>",
                                                                                                    cancellationPolicyDefaultText: "<?
$text = "";
if (isset($arResult["CANCELLATION_POLICY"])) {
    $text = current($arResult["CANCELLATION_POLICY"]);
}
if (strlen($text)) {
    echo $text;
} else {
    echo GetMessage("CANCELLATION_POLICY_DEF_TEXT");
}
?>"
                                                                                                }
                                                                                            });
                                                                                        });

                                                                                    })(jQuery, document);
</script>
