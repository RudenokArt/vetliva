<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(false);

$sessid = bitrix_sessid();
?>   <span id="sp"></span><?
if ($arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y" && $arResult["CALCULATION"]) {
    ?>
    <!-- Цена для граждан -->
    <div class="citizenPrices">
        <select class="form-control" name="citizen_price">
            <? foreach ($arResult["CITIZEN_PRICES"]["ITEMS"] as $ID => $F_NAME) : ?>
                <option value="<?= $ID ?>" <?
                if ($ID == $arResult["CITIZEN_PRICES"]["CURRENT"]) {
                    echo "selected";
                }
                ?>><?= GetMessage($F_NAME) ?></option>
                    <? endforeach ?>
        </select>
    </div>
    <div class="clearfix"></div>
<? } ?>

<div class="hl-availability">

    <?
    if (!$arResult["CALCULATION"]) {


        // ИМЯ ОБЪЕКТА ПОИСКА
//        $arParent = CIBlockElement::GetList(
//                        false, array('ID' => $arResult['REQUEST']->id[0]), false, false, array('ID', 'NAME', 'PROPERTY_NAME' . POSTFIX_PROPERTY))->Fetch();

        $dbParent = CIBLockElement::GetByID($arResult['REQUEST']->id[0])->GetNextElement();
        $arParentFields = $dbParent->GetFields();
        $arParentProps = $dbParent->GetProperties();
        $parentName = $arParentProps['NAME' . POSTFIX_PROPERTY]['VALUE'] ? $arParentProps['NAME' . POSTFIX_PROPERTY]['VALUE'] : $arParentFields['NAME'];

        $similarOffersHtml = '';
        if (!empty($arResult['SIMILAR_OFFERS'])) {

            ob_start();
            ?>
            <div>
                <table class="offers-container">

                    <tbody>
                        <? foreach ($arResult['SIMILAR_OFFERS'] as $arOffer) { ?>
                            <tr>
                                <td><?=
                                    GetMessage("SPARAMETERS_FOR_SIMILAR_OFFER", array(
                                        "#NAME#" => "<b>" . $parentName . '</b><br>',
                                        "#DATE_FROM#" => $arOffer['date_from'],
                                        "#DATE_TO#" => $arOffer['date_to'] . '<br>',
                                        "#ADULTS#" => $arOffer['request_params']['adults'] . ', ',
                                        "#CHILDREN#" => $arOffer['request_params']['children'] . '<br>',
                                        "#NIGHTS#" => $arOffer['calculation']['DURATION']
                                    ))
                                    ?></td>
                                <td><b><?= GetMessage("FROM") . " " . \travelsoft\booking\Utils::convertCurrency($arOffer['calculation']['PRICE'], $arOffer['calculation']['CURRENCY_ID']) ?></b></td>
                                <td><a href="<?= getCalculateDetailLink($APPLICATION->GetCurDir(), $arOffer['request_params'], array("scroll-to-sp" => 'Y')) ?>" class="awe-btn awe-btn-1 awe-btn-small"><?= GetMessage('DETAILS') ?></a></td>
                            <tr>
                            <? } ?>
                    </tbody>
                </table>
            </div>

            <?
            $similarOffersHtml = ob_get_clean();
        }

        echo GetMessage("NO_PRICES_CITIZEN_WITH_SIMILAR_OFFERS", array(
            "#ADDITIONAL_PART#" => strlen($similarOffersHtml) ? GetMessage("ADDITIONAL_PART_FOR_NO_PRICES_CITIZEN_WITH_SIMILAR_OFFERS") : GetMessage("CALLBACK_SEND_MESS_TITLE"),
            "#SIMILAR_OFFERS_HTML#" => $similarOffersHtml,
            "#NAME#" => $parentName,
            "#DATE_FROM#" => date('d.m.Y', $arResult['REQUEST']->date_from),
            "#DATE_TO#" => date('d.m.Y', $arResult['REQUEST']->date_to)
                )
        );

        if (!strlen($similarOffersHtml)) {
            ?>
            <div class="modal fade" id="callback-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="modal-title"><b><?= GetMessage("CALLBACK_MODAL_TITLE")?></b></h4>
                        </div>

                        <div class="modal-body">
                            
                            <input type="hidden" name="current_page" value="<?= $APPLICATION->GetCurPageParam("", array(), false) ?>">
                            <div class="form-group">
                                <label for="full_name"><?= GetMessage("CALLBACK_FULL_NAME_TITLE")?><span class="star">*</span></label>
                                <span class="error-container"></span>
                                <input name="full_name" value="" type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="phone"><?= GetMessage("CALLBACK_PHONE_TITLE")?></label>
                                <span class="error-container"></span>
                                <input name="phone" type="phone" value="" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="email"><?= GetMessage("CALLBACK_EMAIL_TITLE")?><span class="star">*</span></label>
                                <span class="error-container"></span>
                                <input name="email" type="email" value="" class="form-control">
                            </div>
                            <div class="form-group has-feedback">
                                <label for="date"><?= GetMessage("CALLBACK_DATE_TITLE")?><span class="star">*</span></label>
                                <span class="error-container"></span>
                                <input name="date" type="text" value="" class="form-control">
                                <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                            </div>
                            <div class="form-group">
                                <label for="comment"><?= GetMessage("CALLBACK_COMMENT_TITLE")?><span class="star">*</span></label>
                                <span class="error-container"></span>
                                <textarea name="comment" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="sender-btn" class="btn btn-primary"><?= GetMessage("CALLBACK_SEND_BTN_TITLE")?></button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $("#callback-modal").on("shown.bs.modal", function () {
                        if (typeof $.datepicker === "object") {
                            $.datepicker.regional['<?= LANGUAGE_ID?>'] = {
                                    dayNames: [<?= GetMessage("CALLBACK_DAYS_TITLE")?>],
                                    dayNamesShort: [<?= GetMessage("CALLBACK_DAYSSHORT_TITLE")?>],
                                    dayNamesMin: [<?= GetMessage("CALLBACK_DAYSSHORT_TITLE")?>],
                                    monthNames: [<?= GetMessage("CALLBACK_MONTH_TITLE")?>],
                                    monthNamesShort: [<?= GetMessage("CALLBACK_MONTHSHORT_TITLE")?>]
                                }
                            $.datepicker.setDefaults($.datepicker.regional['<?= LANGUAGE_ID?>']);
                            $('input[name="date"]').datepicker({
                                dateFormat: "dd.mm.yy"
                            });
                        }
                    });
                    
                    $("#sender-btn").on("click", function () {
                        
                        var data = {
                            type: "callback",
                            full_name: $("#callback-modal input[name='full_name']").val(),
                            phone: $("#callback-modal input[name='phone']").val(),
                            email: $("#callback-modal input[name='email']").val(),
                            date: $("#callback-modal input[name='date']").val(),
                            comment: $("#callback-modal textarea[name='comment']").val(),
                            sessid: "<?= bitrix_sessid()?>",
                            current_page: $("#callback-modal input[name='current_page']").val(),
                        };
                        var haveError = false;
                        
                        $("#callback-modal .error-container").html("");
                        
                        for (k in data) {
                            switch (k) {
                                case "full_name":
                                    if (data[k].length <= 2) {
                                        haveError = true;
                                        $("#callback-modal input[name='full_name']")
                                                .prev(".error-container")
                                                .text(`<?= GetMessage("CALLBACK_FULL_NAME_ERROR")?>`);
                                    }
                                    break;
                                case "comment":
                                    if (data[k].length <= 2) {
                                        haveError = true;
                                        $("#callback-modal textarea[name='comment']")
                                                .prev(".error-container")
                                                .text(`<?= GetMessage("CALLBACK_COMMENT_ERROR")?>`);
                                    }
                                    break;
                                case "phone":
                                    if (data[k].length > 0 && !(/^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$/gm).test(data[k])) {
                                        haveError = true;
                                        $("#callback-modal input[name='phone']")
                                                .prev(".error-container")
                                                .text(`<?= GetMessage("CALLBACK_PHONE_ERROR")?>`);
                                    }
                                    break;
                                case "email":
                                    if (!(/^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/).test(data[k])) {
                                        haveError = true;
                                        $("#callback-modal input[name='email']")
                                                .prev(".error-container")
                                                .text(`<?= GetMessage("CALLBACK_EMAIL_ERROR")?>`);
                                    }
                                    break;
                                    
                                case "date":
                                    console.log();
                                    if (!data[k].length) {
                                        haveError = true;
                                        $("#callback-modal input[name='date']")
                                                .prev(".error-container")
                                                .text(`<?= GetMessage("CALLBACK_DATE_ERROR")?>`);
                                    }
                                    break;
                            }
                        }
                        
                        if (!haveError) {
                            $.post('/local/components/travelsoft/travelsoft.service.price.result/ajax.php', data, function (resp) {
                                
                                $("#callback-modal .modal-footer").remove();
                                if (resp.callback_ok) {
                                    $("#callback-modal .modal-body").html(`<?= GetMessage("CALLBACK_OK_MESSAGE")?>`);
                                } else {
                                    $("#callback-modal .modal-body").html(`<?= GetMessage("CALLBACK_FAIL_MESSAGE")?>`);
                                }
                                
                            }).fail(function () {
                                $("#callback-modal .modal-footer").remove();
                                $("#callback-modal .modal-body").html(`<?= GetMessage("CALLBACK_FAIL_MESSAGE")?>`);
                            });
                        }
                    });
                    
                    $(document).on("click", "#callback-ok-btn", function (){
                        $("#callback-modal").modal("hide");
                    });
                });
            </script>
            <?
        }
    }

    if ($arParams["IS_AJAX"] == "Y") {
        ob_end_clean();
    }

    ob_start();
    ?>

    <?
    foreach ($arResult["HTML_DATA"] as $arHtmlData):
        foreach ($arHtmlData as $arData) :
            ?>
            <div class='row'>
                <? if ($arData["MAIN_BLOCK"]): ?>

                    <div class="service-info-container">
                        <?
                        $col_ = 9;
                        if ($arData["MAIN_BLOCK"]["IMAGE_ID"]): $col_ = 6;
                            ?>
                            <div class="col-lg-3 col-md-3">

                                <figure>
                                    <img src="<?= getSrcImage($arData["MAIN_BLOCK"]["IMAGE_ID"], array('width' => 200, 'height' => 130)) ?>">    
                                </figure>

                            </div>
                        <? endif ?> 
                        <div class="col-lg-<?= $col_ ?> col-md-<?= $col_ ?>">
                            <div class="service-title"><a <? if ($arResult["SERVICE_POPUP_JS"]) : ?>data-id="<?= $arData["MAIN_BLOCK"]["ID"] ?>"<? endif ?> href="<? if ($arResult["SERVICE_POPUP_JS"]) : ?>#srv-popup-<?= $arData["MAIN_BLOCK"]["ID"] ?><? else: ?>javascript:void(0)<? endif ?>" class="pointer <? if ($arResult["SERVICE_POPUP_JS"]) : ?> open-service-popup <? endif ?>"><?= $arData["MAIN_BLOCK"]["TITLE"] ?></a></div>
                            <? if ($arResult['FORSPOTPAYMENT']) : $existsfsptitle = true ?>
                                <div class="fsp-container"><span class="for-spot-payment"><?= GetMessage("AVAIL_ON_SPOT") ?></span></div>
                            <? endif ?>
                            <div class="service-short-description"><?= $arData["MAIN_BLOCK"]["DESCRIPTION"] ?></div>

                        </div>
                        <div class="col-lg-3 col-md-3 text-center">
                            <div class="price-border">
                                <?= GetMessage("FROM") ?> <span class="min-price-container"><?= $arData["MAIN_BLOCK"]["PRICE"] ?></span>
                            </div>
                            <? if (isset($arData["MAIN_BLOCK"]["FOR_SALE"]) && $arData["MAIN_BLOCK"]["FOR_SALE"] < 3): ?>
                                <span class="few-number-alert"><?= GetMessage("FOR_SALE") ?> <?= $arData["MAIN_BLOCK"]["FOR_SALE"] ?></span>
                            <? endif ?>
                        </div>
                    </div>

                <? endif ?>
                <!-- Результат поиска -->
                <? if ($arData["ROWS"]): ?>

                    <div class="rate-info-container">
                        <div class="col-lg-12 col-md-12">
                            <table class="table rate-table mt-20">

                                <? if (!$arData["MAIN_BLOCK"]): ?>
                                    <tr>
                                        <td style="text-align:left">
                                            <span class="tour-date"><?= GetMessage("DATE_TEXT") ?></span>
                                            <?= GetMessage("TARIFF_TEXT") ?></td>
                                        <td><?= GetMessage("PRICE_TEXT") ?></td>
                                        <td></td>
                                    </tr>
                                <? endif ?>

                                <? foreach ($arData["ROWS"] as $key => $arRow): ?>
                                    <tr>
                                        <td style="text-align:left">
                                            <? if ($arRow["DATE"]): ?>
                                            <!-- hello world -->
                                                <span class="tour-date"><?= $arRow["DATE"] ?></span><? endif ?>
                                            <h4 class="rate-name"><?= $arRow["TITLE"] ?></h4>
                                            <div class="fsp-container" style="float: left">
                                                <? if ($arResult['FORSPOTPAYMENT'] && !$existsfsptitle) : ?>
                                                    <span class="for-spot-payment"><?= GetMessage("AVAIL_ON_SPOT") ?></span>
                                                <? endif ?>
                                                <? if ($arResult["RATE_POPUP_JS"]): ?><a class="open-rate-popup" data-id="<?= $arRow["ID"] ?>" href="#rate-popup-<?= $key ?>"><?= GetMessage("ABOUT_RATE_TITLE") ?></a> &nbsp&nbsp<? endif ?><? if ($arResult["CANCELLATION_POLICY_POPUP_JS"]): ?><a class="open-cancellation-policy-popup" data-id="<?= $arRow["ID"] ?>" href="#cancellation-policy-popup-<?= $key ?>"><?= GetMessage("CANCELLATION_POLICY_TITLE") ?></a><? endif ?>
                                            </div>
                                        </td>
                                        <td class="price-container">
                                            <?= $arRow["PRICE"]; ?>
                                        </td>
                                        <td>

                                            <a href="javascript:void(0)" data-add2cart="<?= $arRow["ADD2CART"] ?>"  class="add-to-cart awe-btn awe-btn-1 awe-btn-small"><?= GetMessage("BOOK") ?></a>

                                        </td>
                                    </tr>
                                <? endforeach ?>

                            </table>
                        </div>
                    </div>

                <? endif ?>
            </div>

            <?
        endforeach;
    endforeach;
    ?>

    <?
    $buffer = ob_get_clean();

    if ($arParams["IS_AJAX"] == "Y") {
        echo $buffer;
        return;
    }

    echo $buffer;
    ?>
</div>

<script>
    (function ($, document) {

<?
if ($_REQUEST['scroll-to-sp'] === 'Y'):
    // HACK: SCROLL TO PRICE RESULT POSITION
    ?>
        var scrollTo = 0;
        if ($(window).outerWidth() >= 1200) {
        scrollTo = $('#sp').offset().top - 400;
        } else {
        scrollTo = $('#sp').offset().top - $('#header').outerHeight();
        }
        $('html, body').animate({
        scrollTop: scrollTo
        }, "fast");
<? endif ?>
<? if ($arResult["CALCULATION"]): ?>
        $(document).ready(function () {

    <?
    if ($arResult["SERVICE_POPUP_JS"]):
        ?>
            $.initServicePopup({
            sessid: "<?= $sessid ?>",
                    anchor: ".open-service-popup",
                    messages: {
                    loadingMessage: '<?= GetMessage("LOADING_MESSAGE") ?>',
                            maxPeople: "<?= GetMessage("MAX_PEOPLE") ?>",
                            cntSofaBad: "<?= GetMessage("CNT_SOFA_BAD") ?>",
                            square: "<?= GetMessage("SQUARE") ?>",
                            servicesIn: "<?= GetMessage("SERVICES_IN") ?>",
                            cntAddPlaces: "<?= GetMessage("ADD_PLACES") ?>",
                            cntBad1: "<?= GetMessage("BAD1") ?>",
                            cntBad2: "<?= GetMessage("BAD2") ?>",
                            cntMainPlaces: "<?= GetMessage("MAIN_PLACES") ?>"
                    }
            });
    <? endif ?>
    <? if ($arResult["RATE_POPUP_JS"]): ?>
            $.initRatePopup({
            sessid: "<?= $sessid ?>",
                    anchor: ".open-rate-popup",
                    messages: {loadingMessage: '<?= GetMessage("LOADING_MESSAGE") ?>'}
            });
    <? endif ?>
    <? if ($arResult["CANCELLATION_POLICY_POPUP_JS"]): ?>
            $.initCancellationPolicyPopup({
            sessid: "<?= $sessid ?>",
                    anchor: ".open-cancellation-policy-popup",
                    messages: {
                    loadingMessage: '<?= GetMessage("LOADING_MESSAGE") ?>',
                            cancellationPolicyDefaultText: <?
        $text = current($arResult["CANCELLATION_POLICY"]);
        if ($text):
            ?>'<?= $text ?>'<? else: ?>'<?= GetMessage("CANCELLATION_POLICY_DEF_TEXT") ?>'<? endif ?>
                    }
            });
    <? endif ?>
        $.addToCartInit({
        sessid: "<?= bitrix_sessid() ?>",
                anchor: ".add-to-cart",
                redirect: "<?= $arParams["MAKE_ORDER_PAGE"] ?>"
        });
    <?
    if ($arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y"):
        $sparams = \Bitrix\Main\Web\Json::encode(array_merge(array("objectType" => $arParams["TYPE"]), $arResult["REQUEST"]->getPropertiesLikeArray()));
        ?>
            $.citizenPricesInit({
            sessid: "<?= $sessid ?>",
                    anchor: "select[name='citizen_price']",
                    addToCartAnchor: ".add-to-cart",
                    initAddToCart: true,
                    initServicePopup: <? if ($arResult["SERVICE_POPUP_JS"]): ?>true<? else: ?>false<? endif ?>,
                                initRatePopup: <? if ($arResult["RATE_POPUP_JS"]): ?>true<? else: ?>false<? endif ?>,
                                            initCancellationPolicyPopup: <? if ($arResult["CANCELLATION_POLICY_POPUP_JS"]): ?>true<? else: ?>false<? endif ?>,
        <? if ($arResult["RATE_POPUP_JS"]): ?>
                                                    rateAnchor: ".open-rate-popup",
        <? endif ?>
        <? if ($arResult["CANCELLATION_POLICY_POPUP_JS"]): ?>
                                                    cancellationPolicyAnchor: ".open-cancellation-policy-popup",
        <? endif ?>
        <? if ($arResult["SERVICE_POPUP_JS"]): ?>
                                                    servicesAnchor: ".open-service-popup",
                                                            sparams: <?= $sparams ?>,
                                                            redirect: "<?= $arParams["MAKE_ORDER_PAGE"] ?>",
                                                            insertContainer: ".hl-availability",
        <? endif ?>
                                                messages: {
                                                no_result: "<?= GetMessage("NO_PRICES_CITIZEN") ?>",
                                                        loadingMessage: '<?= GetMessage("LOADING_MESSAGE") ?>',
        <? if ($arResult["SERVICE_POPUP_JS"]): ?>
                                                    maxPeople: "<?= GetMessage("MAX_PEOPLE") ?>",
                                                            cntSofaBad: "<?= GetMessage("CNT_SOFA_BAD") ?>",
                                                            square: "<?= GetMessage("SQUARE") ?>",
                                                            servicesIn: "<?= GetMessage("SERVICES_IN") ?>",
                                                            cntAddPlaces: "<?= GetMessage("ADD_PLACES") ?>",
                                                            cntBad1: "<?= GetMessage("BAD1") ?>",
                                                            cntBad2: "<?= GetMessage("BAD2") ?>",
                                                            cntMainPlaces: "<?= GetMessage("MAIN_PLACES") ?>",
        <? endif ?>
        <? if ($arResult["CANCELLATION_POLICY_POPUP_JS"]): ?>
                                                    cancellationPolicyDefaultText: <? if ($text): ?>'<?= $text ?>'<? else: ?>'<?= GetMessage("CANCELLATION_POLICY_DEF_TEXT") ?>'<? endif ?>
        <? endif ?>
                                                }});
    <? endif ?>
                                            });
<? endif ?>
                                        })(jQuery, document);
</script>

