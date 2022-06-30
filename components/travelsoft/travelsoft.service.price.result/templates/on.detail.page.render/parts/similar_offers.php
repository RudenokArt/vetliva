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
if ($arParentFields['IBLOCK_CODE']=='tours') {
    $arRequesttmp = [];
    $datesData = $arRequesttmp = $arRequest;
    $datesData['date_to'] = strtotime("+180 days");
    unset($arRequesttmp['id']);
    unset($arRequesttmp['date_from']);
    unset($arRequesttmp['date_to']);
    $custom_link =  getCalculateDetailLink($arParentFields['DETAIL_PAGE_URL'], $arRequesttmp, array("scroll-to-sp" => 'Y'));
    $result_price = $APPLICATION->IncludeComponent(
        "travelsoft:travelsoft.service.price.result",
        "on.detail.page.render",
        Array(
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => 3600,
            "COMPONENT_TEMPLATE" => "on.detail.page.render",
            "CODE" => [$arParentFields['CODE']],
            "FILTER_BY_PRICES_FOR_CITIZEN" => $arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y" ? "Y" : "N",
            "INC_JQUERY" => "N",
            "INC_MAGNIFIC_POPUP" => "N",
            "INC_OWL_CAROUSEL" => "N",
            "TYPE" => $arParams["TYPE"],
            "MAKE_ORDER_PAGE" => "/booking/",
            "POSTFIX_PROPERTY" => POSTFIX_PROPERTY,
            "__BOOKING_REQUEST" => $datesData,
            "RETURN_RESULT" => "Y",
            "FOLDER_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"],
            "CURRENCY" => travelsoft\Currency::getInstance()->get("current_currency")["iso"]
        )
    );
    $dates  =[];
    foreach ($result_price  as $id  =>$val)
        foreach ($val as $service_idtmp => $valtmp)
            foreach ($valtmp as $dataunix => $valtmp2)
            if (!in_array($dataunix, $dates[$id])) $dates[$id][] = $dataunix;
    if (count($dates)>0) {
        $propstmp = getIBElementProperties($arParentFields['ID']);
        ob_start();
         $arForSaletmp = (new \travelsoft\booking\datastores\QuotasDataStore(array(
                "filter" => array("UF_SERVICE_ID" => $service_idtmp, "><UF_DATE" => array($arRequest["date_from"], $arRequest["date_to"])),
                "select" => array("ID", "UF_SERVICE_ID", "UF_QUOTE", "UF_SOLD_NUMBER", "UF_DATE", "UF_RELEASE_PERIOD")
                    )))->filterAvailableForSale()->getServiceCountOnSale();
            if ($arForSaletmp[$service_idtmp]>0)  echo '<div class="place_count">'.GetMessage('count_place_avail').$arForSaletmp[$service_idtmp].'</div>';?>
			<div class="calendar_block">
                <a <?if ($propstmp['IS_EXCURSION_TOUR']['VALUE']!=''):?>data-countday=<?=$propstmp['DAYS']['VALUE']?><?endif;?> href="javascript:void(0)" data-dates="<?=json_encode($dates[$arParentFields['ID']])?>" data-link="<?=$custom_link?>" data-id="<?=$arParentFields['ID']?>" class="selectdates">
                    <?=GetMessage('select_other_date')?>
                </a>
                <input type="text" name="eventdate" style="outline: none; color: white; border:none; height: 0px; background: transparent;" />
            </div>
            <script>
            $( document ).ready(function() {
				$(".place_count, .calendar_block").wrapAll("<div class='d-flex'></div>");
                $(".selectdates").on("click", function (e) {
                    var  $this = $(this), alloweddatestrue  =[], countday = $this.data('countday'), link = $this.data('link'), id = $this.data('id'),  alloweddates = $this.data('dates'), input = $this.closest('div').find('input');
                    alloweddates.forEach(element => alloweddatestrue.push( moment(element, 'X').format('DD.MM.YYYY')));
                    input.datepicker({
                        beforeShowDay: function (date) {
                            if (countday >1) {
                                showdate = false;
                                datetmp = moment(date).format('X');
                                var mindates = [], maxdates = [];
                                alloweddates.forEach(function(item, i, arr) {
                                    minDate = item;
                                    maxDate = moment(minDate, 'X').add(countday,'days').format('X');
                                    mindates.push(minDate); maxdates.push((maxDate-86400));
                                    if (datetmp >= minDate && datetmp < maxDate)  showdate = true;

                                });
                                if (showdate) {
                                    datetmp = parseInt(datetmp);
                                    if (mindates.includes(datetmp)) return [true, ' ui-datepicker-current-day green-day', ''];
                                    else if (maxdates.includes(datetmp)) return [true, ' light-date-end green-day', ''];
                                    else return [true, 'light-date', ''];
                                }
                                else return [false, '', ''];
                            }
                            else {
                                datetmp = moment(date).format('DD.MM.YYYY');
                                if(jQuery.inArray(datetmp, alloweddatestrue) != -1) {
                                    return [true, 'light-date green-day', ''];
                                }
                                else return [false, '', ''];
                            }
                        },
                        onSelect: function (string, object) {
                            if (countday >1) {
                                datetmp = moment(string, 'DD.MM.YYYY').format('X');
                                alloweddates.forEach(function(item, i, arr) {
                                    minDate = item;
                                    maxDate = moment(minDate, 'X').add(countday,'days').format('X');
                                    if (datetmp >= minDate && datetmp < maxDate) {
                                        $('#form-excursionstours [name="booking[date_to]"]').val(maxDate);
                                        $('#form-excursionstours [name="booking[date_from]"]').val(minDate);
                                        $('#form-excursionstours [name="booking[id][0]"]').val(id);
                                        $('#form-excursionstours form').attr('action', link);
                                        $('#form-excursionstours form').submit();
                                    }
                                });
                            }
                            else {
                                $('#form-tours [name="booking[date_to]"]').val(moment(string, 'DD.MM.YYYY').format('X'));
                                $('#form-tours [name="booking[date_from]"]').val(moment(string, 'DD.MM.YYYY').format('X'));
                                $('#form-tours [name="booking[id][0]"]').val(id);
                                $('#form-tours form').attr('action', link);
                                $('#form-tours form').submit();
                            }
                        },
                        minDate:alloweddatestrue[0],
                        maxDate:moment(alloweddatestrue[0], 'DD.MM.YYYY').add(1,'year').format('DD.MM.YYYY'),
                        dateFormat: 'dd.mm.yy',
                        numberOfMonths: window.innerWidth < 640 ? 1 : 2,
                        allowedDates :alloweddatestrue
                       });
                       input.datepicker('show');

                });
            });
            </script>
            <?
        $addtionalInfo = ob_get_clean();
    }

}
if ($arParentFields['IBLOCK_CODE']=='tours')
echo GetMessage("NO_PRICES_CITIZEN_WITH_SIMILAR_OFFERS_TOURS", array(
    "#CHOOSE_DATE#" =>$addtionalInfo,
    "#ADDITIONAL_PART#" => strlen($similarOffersHtml) ? GetMessage("ADDITIONAL_PART_FOR_NO_PRICES_CITIZEN_WITH_SIMILAR_OFFERS") : GetMessage("CALLBACK_SEND_MESS_TITLE"),
    "#SIMILAR_OFFERS_HTML#" => $similarOffersHtml,
    "#NAME#" => $parentName,
    "#DATE_FROM#" => date('d.m.Y', $arRequest["date_from"]),
    "#DATE_TO#" => date('d.m.Y', $arRequest["date_to"])
        )
);
else
echo GetMessage("NO_PRICES_CITIZEN_WITH_SIMILAR_OFFERS", array(
    "#CHOOSE_DATE#" =>$addtionalInfo,
    "#ADDITIONAL_PART#" => strlen($similarOffersHtml) ? GetMessage("ADDITIONAL_PART_FOR_NO_PRICES_CITIZEN_WITH_SIMILAR_OFFERS") : GetMessage("CALLBACK_SEND_MESS_TITLE"),
    "#SIMILAR_OFFERS_HTML#" => $similarOffersHtml,
    "#NAME#" => $parentName,
    "#DATE_FROM#" => date('d.m.Y', $arRequest["date_from"]),
    "#DATE_TO#" => date('d.m.Y', $arRequest["date_to"])
        )
);
?>
<script>$("select[name='citizen_price']").closest("select").remove();</script>
<?if ($arParentFields['IBLOCK_CODE']=='sanatorium' || $arParentFields['IBLOCK_CODE']=='accomodation'):?>
<script>
$(".seat-availability").insertBefore("#sp");
</script>
<?endif;?>
<?
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
                        <input type="hidden" name="object_name" value="<?= $parentName ?>">
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
                        object_name: $("#callback-modal input[name='object_name']").val(),
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
                                window.location.replace("/success");
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
    //include_once 'specifying_form.php';

    $buffer = ob_get_clean();

    if ($arParams["IS_AJAX"] == "Y") {
    echo $buffer;
    return;
    }

    echo $buffer;
    ?>

</div>