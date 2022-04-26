<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?><div class="hl-availability"><?
$arRequest = $arResult["REQUEST"]->getPropertiesLikeArray();


if ($arRequest["date_from"] > $arRequest["date_to"]) {
    // for transfers with one way
    $arRequest["date_to"] = $arRequest["date_from"];
}

if ($arParams["IS_AJAX"] == "Y") {
    
}

$parentName = "";
if (isset($arRequest["id"]) && is_array($arRequest["id"])) {
    // получение детальной информации по объекту
    $dbParent = CIBLockElement::GetByID($arRequest["id"][0])->GetNextElement();
    $arParentFields = $dbParent->GetFields();
    $arParentProps = $dbParent->GetProperties();
    $parentName = $arParentProps['NAME' . POSTFIX_PROPERTY]['VALUE'] ? $arParentProps['NAME' . POSTFIX_PROPERTY]['VALUE'] : $arParentFields['NAME'];
}
$similarOffersHtml = '';

if ($arParams["IS_AJAX"] == "Y") {
    ob_end_clean();
}

ob_start();

if (!empty($arResult['SIMILAR_OFFERS'])): ob_start();
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
                            <td><a href="<?= getCalculateDetailLink("", $arOffer['request_params'], array("scroll-to-sp" => 'Y')) ?>" class="awe-btn awe-btn-1 awe-btn-small"><?= GetMessage('DETAILS') ?></a></td>
                        <tr>
    <? } ?>
                </tbody>
            </table>
        </div>

    <?
    $similarOffersHtml = ob_get_clean();
endif;

echo GetMessage("NO_PRICES_CITIZEN_WITH_SIMILAR_OFFERS", array(
    "#ADDITIONAL_PART#" => strlen($similarOffersHtml) ? GetMessage("ADDITIONAL_PART_FOR_NO_PRICES_CITIZEN_WITH_SIMILAR_OFFERS") : GetMessage("CALLBACK_SEND_MESS_TITLE"),
    "#SIMILAR_OFFERS_HTML#" => $similarOffersHtml,
    "#NAME#" => $parentName,
    "#DATE_FROM#" => date('d.m.Y', $arRequest["date_from"]),
    "#DATE_TO#" => date('d.m.Y', $arRequest["date_to"])
        )
);
?><script>$("select[name='citizen_price']").closest("select").remove();</script><?
if (!strlen($similarOffersHtml)):
    ?>
        <div class="modal fade" id="callback-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="modal-title"><b><?= GetMessage("CALLBACK_MODAL_TITLE") ?></b></h4>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="current_page" value="<?= $APPLICATION->GetCurPageParam("", array(), false) ?>">
                        <div class="form-group">
                            <label for="full_name"><?= GetMessage("CALLBACK_FULL_NAME_TITLE") ?><span class="star">*</span></label>
                            <span class="error-container"></span>
                            <input name="full_name" value="" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="phone"><?= GetMessage("CALLBACK_PHONE_TITLE") ?></label>
                            <span class="error-container"></span>
                            <input name="phone" type="phone" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email"><?= GetMessage("CALLBACK_EMAIL_TITLE") ?><span class="star">*</span></label>
                            <span class="error-container"></span>
                            <input name="email" type="email" value="" class="form-control">
                        </div>
                        <div class="form-group has-feedback">
                            <label for="date"><?= GetMessage("CALLBACK_DATE_TITLE") ?><span class="star">*</span></label>
                            <span class="error-container"></span>
                            <input name="date" type="text" value="" class="form-control">
                            <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                        </div>
                        <div class="form-group">
                            <label for="comment"><?= GetMessage("CALLBACK_COMMENT_TITLE") ?><span class="star">*</span></label>
                            <span class="error-container"></span>
                            <textarea name="comment" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="sender-btn" class="btn btn-primary"><?= GetMessage("CALLBACK_SEND_BTN_TITLE") ?></button>
                    </div>
                </div>
            </div>
        </div>
        <script>
    <? if (!$arResult["NEED_LAZY_LOAD"]) {include_once "scroll_js.php";} ?>

                $(document).on("shown.bs.modal", "#callback-modal", function () {
                    if (typeof $.datepicker === "object") {
                        $.datepicker.regional['<?= LANGUAGE_ID ?>'] = {
                            dayNames: [<?= GetMessage("CALLBACK_DAYS_TITLE") ?>],
                            dayNamesShort: [<?= GetMessage("CALLBACK_DAYSSHORT_TITLE") ?>],
                            dayNamesMin: [<?= GetMessage("CALLBACK_DAYSSHORT_TITLE") ?>],
                            monthNames: [<?= GetMessage("CALLBACK_MONTH_TITLE") ?>],
                            monthNamesShort: [<?= GetMessage("CALLBACK_MONTHSHORT_TITLE") ?>]
                        }
                        $.datepicker.setDefaults($.datepicker.regional['<?= LANGUAGE_ID ?>']);
                        $('input[name="date"]').datepicker({
                            dateFormat: "dd.mm.yy"
                        });
                    }
                });

                $(document).on("click", "#sender-btn", function () {
                    var data = {
                        type: "callback",
                        full_name: $("#callback-modal input[name='full_name']").val(),
                        phone: $("#callback-modal input[name='phone']").val(),
                        email: $("#callback-modal input[name='email']").val(),
                        date: $("#callback-modal input[name='date']").val(),
                        comment: $("#callback-modal textarea[name='comment']").val(),
                        sessid: "<?= bitrix_sessid() ?>",
                        current_page: $("#callback-modal input[name='current_page']").val(),
                    };
                    var haveError = false;

                    $("#callback-modal .error-container").html("");

                    for (var k in data) {
                        switch (k) {
                            case "full_name":
                                if (data[k].length <= 2) {
                                    haveError = true;
                                    $("#callback-modal input[name='full_name']")
                                            .prev(".error-container")
                                            .text(`<?= GetMessage("CALLBACK_FULL_NAME_ERROR") ?>`);
                                }
                                break;
                            case "comment":
                                if (data[k].length <= 2) {
                                    haveError = true;
                                    $("#callback-modal textarea[name='comment']")
                                            .prev(".error-container")
                                            .text(`<?= GetMessage("CALLBACK_COMMENT_ERROR") ?>`);
                                }
                                break;
                            case "phone":
                                if (data[k].length > 0 && !(/^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$/gm).test(data[k])) {
                                    haveError = true;
                                    $("#callback-modal input[name='phone']")
                                            .prev(".error-container")
                                            .text(`<?= GetMessage("CALLBACK_PHONE_ERROR") ?>`);
                                }
                                break;
                            case "email":
                                if (!(/^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/).test(data[k])) {
                                    haveError = true;
                                    $("#callback-modal input[name='email']")
                                            .prev(".error-container")
                                            .text(`<?= GetMessage("CALLBACK_EMAIL_ERROR") ?>`);
                                }
                                break;

                            case "date":
                                if (!data[k].length) {
                                    haveError = true;
                                    $("#callback-modal input[name='date']")
                                            .prev(".error-container")
                                            .text(`<?= GetMessage("CALLBACK_DATE_ERROR") ?>`);
                                }
                                break;
                        }
                    }

                    if (!haveError) {
                        $.post('/local/components/travelsoft/travelsoft.service.price.result/ajax.php', data, function (resp) {

                            $("#callback-modal .modal-footer").remove();
                            if (resp.callback_ok) {
                                $("#callback-modal .modal-body").html(`<?= GetMessage("CALLBACK_OK_MESSAGE") ?>`);
                            } else {
                                $("#callback-modal .modal-body").html(`<?= GetMessage("CALLBACK_FAIL_MESSAGE") ?>`);
                            }

                        }).fail(function () {
                            $("#callback-modal .modal-footer").remove();
                            $("#callback-modal .modal-body").html(`<?= GetMessage("CALLBACK_FAIL_MESSAGE") ?>`);
                        });
                    }
                });

                $(document).on("click", "#callback-ok-btn", function () {
                    $("#callback-modal").modal("hide");
                });
        </script>
    <? endif;


    $buffer = ob_get_clean();

    if ($arParams["IS_AJAX"] == "Y") {
    echo $buffer;
    return;
    }

    echo $buffer;
    ?>

</div>    



