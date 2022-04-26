<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if (isset($arResult["SPECIFYING_DATA"]) && !empty($arResult["SPECIFYING_DATA"]["RATES"])):

    /**
     * @param array $arr_grouped_rates
     * @param string $selected
     * @return string
     */
    function getSelectRatesHTML($arr_grouped_rates, $select_name, $selected = null) {

        $SELECT_RATES_HTML = '<select name="' . $select_name . '" class="form-control"><option value="">...</option>';

        foreach ($arr_grouped_rates as $group => $arr_rates) {
            $SELECT_RATES_HTML .= "<optgroup label='".GetMessage($group)."'>";
            foreach ($arr_rates as $id => $name) {
                $SELECT_RATES_HTML .= '<option ' . ($selected == $id ? "selected=''" : "") . ' value="' . $id . '">' . $name . '</option>';
            }
            $SELECT_RATES_HTML .= "</optgroup>";
        }

        $SELECT_RATES_HTML .= '</select>';

        return $SELECT_RATES_HTML;
    }

    $arr_request = $arParams["__BOOKING_REQUEST"];
    if (isset($arr_request["specifying"])) {
        unset($arr_request["specifying"]);
    }
    ?>
    <div class="specifying-form ts-wrap ts-p-4 ts-mb-4">

        <form id="specifying-form" class="form-horizontal" method="GET" action="<?= $APPLICATION->GetCurPageParam("", array(), false) ?>">

            <input name="scroll-to-sp" type="hidden" value="Y">
            <?
            foreach ($arParams["__BOOKING_REQUEST"] as $key => $value) :
                if (is_array($value)):
                    ?>
                    <? foreach ($value as $kkey => $vvalue): ?>
                        <input name="booking[<?= $key ?>][<?= $kkey ?>]" type="hidden" value="<?= $vvalue ?>">
                    <? endforeach ?>
                <? else: ?>
                    <input name="booking[<?= $key ?>]" type="hidden" value="<?= $value ?>">
                <? endif ?>
            <? endforeach ?>

            <div style="font-size: 18px;" class="info form-group text-center"><b><?= GetMessage("SPECIFYING_FORM_TITLE")?></b></div>
            <? for ($adult_number = 1; $adult_number <= $arResult["SPECIFYING_DATA"]["PEOPLE"]["ADULTS"]; $adult_number++): ?>
                <div class="form-group ts-row ts-pb-2">
                    <label style="font-weight: 400" class="ts-col-24 ts-col-sm-4 ts-align-items__center"><?= GetMessage("SPECIFYING_FORM_ADULTS_FIELD_TITLE", array(
                        "#ADULT_NUMBER#" => $adult_number
                    ))?></label>
                    <div style="font-weight: 400" class="ts-col-24 ts-col-sm-20">
                        <?= getSelectRatesHTML($arResult["SPECIFYING_DATA"]["RATES_RENDER_DATA"], "booking[specifying][rates][adults][]", $arParams["__BOOKING_REQUEST"]["specifying"]["rates"]["adults"][$adult_number - 1]) ?>
                    </div>
                </div>
            <? endfor; ?>
            <? foreach ($arResult["SPECIFYING_DATA"]["PEOPLE"]["CHILDREN"] as $children_number => $children_age): ?>

                <div class="form-group ts-row ts-pb-2">
                    <label class="ts-col-24 ts-col-sm-4  ts-align-items__center"><?= GetMessage("SPECIFYING_FORM_CHILDREN_FIELD_TITLE", array(
                        "#CHILDREN_NUMBER#" => $children_number + 1,
                        "#CHILDREN_AGE#" => $children_age
                    ))?></label>
                    <div class="ts-col-24 ts-col-sm-20">
                        <?= getSelectRatesHTML($arResult["SPECIFYING_DATA"]["RATES_RENDER_DATA"], "booking[specifying][rates][children][]", $arParams["__BOOKING_REQUEST"]["specifying"]["rates"]["children"][$children_number]) ?>
                    </div>
                </div>
            <? endforeach ?>

            <div class="form-group ts-row">
                <div class="ts-col-24 ts-align-items__center ts-justify-content__flex-end">
                    <a class="btn btn-primary specifying-form-btn ts-mt-0 ts-mr-2" href="<?echo getCalculateDetailLink($APPLICATION->GetCurPage(false), $arr_request, array("scroll-to-sp" => "Y"));?>">Сбросить</a>
                    <button <?if($arResult["NEED_LAZY_LOAD"]):?>disabled=""<?endif?> type="submit" class="btn btn-primary specifying-form-btn ts-mt-0"><?= GetMessage("SPECIFYING_FORM_BUTTON_TITLE")?></button>
                </div>
            </div>
        </form>

</div>
    <script>
        (function ($) {
            $(document).ready(function () {

                $("#specifying-form").on("submit", function (e) {

                    var $specifying_adults_rates = $("select[name='booking[specifying][rates][adults][]']");
                    var $specifying_children_rates = $("select[name='booking[specifying][rates][children][]']");

                    var errors = [];

                    if ($specifying_adults_rates.length) {
                        $specifying_adults_rates.each(function () {
                            if (!$(this).val()) {
                                errors.push("Необходимо выбрать тариф для всех взрослых");
                                return false;
                            }
                        });
                    }

                    if ($specifying_children_rates.length) {
                        $specifying_children_rates.each(function () {
                            if (!$(this).val()) {
                                errors.push("Необходимо выбрать тариф для всех детей");
                                return false;
                            }
                        });
                    }

                    if (errors.length) {
                        alert(errors.join("\n"));
                        e.preventDefault();
                    }
                });

            });
        })(jQuery);

    </script>
<? endif; ?>