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
?>

<div class="panel panel-flat">
    <div class="form-with-select">
        <form name="serviceSelectForm" action="<?= POST_FORM_ACTION_URI ?>" method="get">
            <? if ($arParams['PROVIDER_ID'] > 0): ?>
                <input name="provider_id" type="hidden" value="<?= $arParams['PROVIDER_ID'] ?>">
            <? endif ?>
            <? if ($arResult['dateArray']["_get"]): ?>
                <input name="getDate" value="<?= $arResult['dateArray']["_get"] ?>" type="hidden">
            <? endif ?>
            <div class="form-group">
                <label><b>Выберите трансфер:</b></label>
                <select data-placeholder="..." onchange="document.serviceSelectForm.submit();" id="selectService" class="select fx-min-width-300px" name="row_id">
                    <option></option>
                    <? foreach ($arResult['SERVICES'] as $id => $arData) : ?>

                        <option <? if ($id == $arParams['ROW_ID']): ?>selected<? endif ?> value="<?= $id ?>"><?= $arData['UF_NAME'] ?></option>
                    <? endforeach ?>
                </select>
            </div>
        </form>
    </div>

    <? if (!isset($arResult['SERVICES'][$arParams['ROW_ID']])): ?>
    </div>
    <? return;
endif
?>

<div class="form-with-select">
    <form name="monthSelectForm" action="<?= POST_FORM_ACTION_URI ?>" method="get">
        <? if ($arParams['PROVIDER_ID'] > 0): ?>
            <input name="provider_id" type="hidden" value="<?= $arParams['PROVIDER_ID'] ?>">
        <? endif ?>
<? if ($arParams['ROW_ID']): ?>

            <input name="row_id" type="hidden" value="<?= $arParams['ROW_ID'] ?>">
<? endif ?>
        <div class="form-group">
            <label><b>Выберите месяц:</b></label>
            <select onchange="document.monthSelectForm.submit();" id="selectMonths" class="select fx-min-width-150px" name="getDate">
                <? foreach ($arResult['dateArray']['monthsArray'] as $arMonth) : ?>
                    <option <? if ($arResult['dateArray']["_get"] == $arMonth['unixDate']): ?>selected<? endif ?> value="<?= $arMonth['unixDate'] ?>"><?= $arMonth['title'] ?></option>
<? endforeach ?>
            </select>
        </div>
    </form>
</div>

<form method="post" name="price-table" id='price-table' action="<?= POST_FORM_ACTION_URI ?>">
    <?= bitrix_sessid_post() ?>
    <? if ($arParams['PROVIDER_ID'] > 0): ?>
        <input name="provider_id" type="hidden" value="<?= $arParams['PROVIDER_ID'] ?>">
    <? endif ?>
    <input type="hidden" name="row_id" value="<?= $arParams['ROW_ID'] ?>">
    <? if ($arResult['dateArray']["_get"]): ?>
        <input name="getDate" value="<?= $arResult['dateArray']["_get"] ?>" type="hidden">
<? endif ?>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>

                    <th width="9%"></th>
                    <? foreach ($arResult['dateArray']['daysArray'] as $arDay) : ?>
                        <th><?= $arDay['title'] ?></th>
                    <? endforeach;
                    $daysNumber = count($arResult['dateArray']['daysArray'])
                    ?>

                </tr>
            </thead>
            <tbody>
                <?
                foreach ($arResult["AUTO_CLASSES"] as $ID => $arClassAuto) :
                    $firstCurrency = current($arResult["CURRENCY"]);
                    $cc = $firstCurrency["id"];
                    $feedbackLabel = "<b>" . $firstCurrency["iso"] . "</b>";
                    if ($arResult["RATES_LINK_CLASS_AUTO"][$ID]) {
                        $cc = $arResult["RATES_LINK_CLASS_AUTO"][$ID]["UF_CURRENCY_ID"];
                        $feedbackLabel = "<b>" . $arResult["CURRENCY"][$cc]["iso"] . "</b>";
                    }

                    $currency = "<select data-change-btn-area='.change-for-" . $ID . "' id='currency-for-" . $ID . "' name='MAINFORM[" . $ID . "][CURRENCY]'>";
                    foreach ($arResult["CURRENCY"] as $arCurrecny) {
                        $currency .= "<option " . ($cc == $arCurrecny["id"] ? "selected" : "") . " value='" . $arCurrecny["id"] . "'>" . $arCurrecny["iso"] . "</option>";
                    }
                    $currency .= "</select>";
                    ?>

                    <tr>
                        <td class="grey-color fs-22" colspan="<?= $daysNumber + 1 ?>"><?= $arClassAuto['UF_NAME'] . " (" . $currency . ")" ?></td>
                    </tr>
                        <? foreach ($arResult["PRICE_TYPES"] as $arPriceTypes) : ?>
                        <tr>
                            <td  class="lightblue-color"><b><?= $arPriceTypes['UF_NAME'] ?></b> <br> [<a  data-toggle="modal" class="setModalBody change-for-<?= $ID ?>" data-feedback-label="<?= $feedbackLabel ?>" data-input-part-name="[PRICE]" data-hvalue='<?= Bitrix\Main\Web\Json::encode(array("CLASS_AUTO" => $ID, "CURRENCY_ID" => $cc, "PT_ID" => $arPriceTypes["ID"])) ?>' data-modal-title="Форма массового редактирования <b><?= $arPriceTypes['UF_NAME'] ?></b>" data-modal-body="fixWithOneHiddenInput" href="#modal_form_vertical">изменить</a>] </td>
                                <? $i = 0;
                                while ($i < $daysNumber):
                                    ?> 
                                <td class="sub-td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>__<?= $ID ?> td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>">
                                    <?
                                    $_uxd = $arResult['dateArray']['daysArray'][$i]['unixDate'];
                                    $value = $arResult['PRICES'][$ID][$arPriceTypes["ID"]][$_uxd]["UF_GROSS"];
                                    ?>
                                    <input value="<?= $value ?>" data-currency-area="#currency-for-<?= $ID ?>" name="MAINFORM[<?= $ID ?>][PRICE][<?= $arPriceTypes["ID"] ?>][<?= $_uxd ?>]" class="input-width-30px" type="text">
                                </td>
                            <? $i++;
                        endwhile;
                        ?>
                        </tr>
    <? endforeach ?>
<? endforeach; ?>
            </tbody>
        </table>
    </div>
</form>
</div>

<!-- Vertical form modal -->
<div id="modal_form_vertical" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close-modal" data-dismiss="modal">&times;</button>
                <h5 class="modal-title"></h5>
            </div>

            <form action="<?= POST_FORM_ACTION_URI ?>">
<?= bitrix_sessid_post() ?>
                <div class="modal-body">
                    <label>Выберите период </label>
                    <div class="form-group">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                            <input type="text" name="MASSEDIT[dateRange]" class="form-control daterange-basic" value=""> 
                        </div>
                    </div>
                    <label>По заданным дням недели</label>
                    <div class="form-group">
                        <input type="checkbox" name="MASSEDIT[dayNumber][0]" value="1">
                        <label><b>Понедельник</b></label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="MASSEDIT[dayNumber][1]" value="2">
                        <label><b>Вторник</b></label>  
                    </div>
                    <div class="form-group"> 
                        <input type="checkbox" name="MASSEDIT[dayNumber][2]" value="3">
                        <label><b>Среда</b></label> 
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="MASSEDIT[dayNumber][3]" value="4">
                        <label><b>Четверг</b></label>  
                    </div>
                    <div class="form-group"> 
                        <input type="checkbox" name="MASSEDIT[dayNumber][4]" value="5">
                        <label><b>Пятница</b></label> 
                    </div>
                    <div class="form-group">  
                        <input type="checkbox" name="MASSEDIT[dayNumber][5]" value="6">
                        <label><b>Суббота</b></label>
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="MASSEDIT[dayNumber][6]" value="7">
                        <label><b>Воскресенье</b></label>  
                    </div>
                    <div class="__modal-body mt-20"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link close-modal" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary sendRequest">Сохранить</button>
                </div>
            </form>

            <div class="preloader-up" style="display: none; text-align: center; margin: 50px">
                <div class="preloader-up-text">
                    <b>Происходит загрузка данных. Это может занять несколько минут. Благодарим за ожидание.</b>
                </div>
                <div class="preloader-up-text"><img src="<?= $templateFolder ?>/loading7_black.gif"></div>
            </div>

        </div>
    </div>
</div>
<!-- /vertical form modal -->
<?
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/plugins/ui/moment/moment.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/plugins/ui/moment/moment_locales.min.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/plugins/pickers/daterangepicker.js");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/plugins/notifications/sweet_alert.min.js");
?>
<script>
    (function ($, document, BX, moment, JSON) {

        /**
         * Объект вспомогательных функций
         * @type {object}
         */
        var Utilites = {

            /**
             * параметры post-запроса по-умолчанию
             */
            postRequestDataDefault: {
                sessid: BX.bitrix_sessid()
            },

            /**
             * Обёртка для jQuery.serializeArray()
             * @param {array} serializeData
             * @returns {object}
             */
            makePostDataByForm(serializeData) {
                var obj = {};
                $.each(serializeData, function (i, field) {
                    obj[field.name] = field.value;
                });

                return obj;
            },

            /**
             * Формирует данные post запроса из инпута
             * @param {jQuery} $this
             * @returns {object}
             */
            makePostDataByInput: function ($this) {

                var data = {}, currencyselect = $($this.data("currency-area"));

                data[$this.attr("name")] = $this.val();
                if (currencyselect.length) {
                    data[currencyselect.attr("name")] = currencyselect.val();
                }


                return $.extend({}, Utilites.postRequestDataDefault, data);

            },

            /**
             * Обработчик post запроса
             * @param {mixed} data
             * @returns {Boolean}
             */
            postSuccess: function (data) {

                var k, classAuto, j, arDate = [], cnt;
                __toDateArray = function (date) {
                    if ($.inArray(date, arDate) === -1) {
                        arDate.push(date);
                    }
                };

                if (data !== null && typeof data === 'object') {

                    if (typeof data.error !== "undefined") {

                        // close modal
                        $("#modal_form_vertical").modal('hide');

                        // show error alert
                        swal({
                            title: "Oops...",
                            text: data.error,
                            confirmButtonColor: "#EF5350",
                            type: "error"
                        });

                        return false;

                    }

                    for (classAuto in data) {
                        for (k in data[classAuto]) {
                            switch (k) {

                                case "PRICE":

                                    for (j in data[classAuto][k]) {
                                        for (var date in data[classAuto][k][j]) {
                                            $("#price-table input[name='MAINFORM[" + classAuto + "][" + k + "][" + j + "][" + date + "]']").val(parseFloat(data[classAuto][k][j][date]) || "");
                                            __toDateArray(date);
                                        }
                                    }
                                    break;

                                case "UPDATE_RATE_CURRENCY":

                                    $($("select[name='MAINFORM[" + classAuto + "][CURRENCY]']").data("change-btn-area")).data("feedback-label", "<b>" + data[classAuto][k] + "</b>");

                                    break;

                            }
                        }
                    }
                    // paint column
                    cnt = arDate.length;
                    for (j = 0; j < cnt; j++) {
                        Utilites.paintColumn(arDate[j]);
                    }

                }

                // close modal
                $("#modal_form_vertical").modal('hide');

            },

            /**
             * Установка заголовка модального окна
             * @param {string} title
             * @returns {undefined}
             */
            setModalTitle: function (title) {

                if (!title) {
                    title = "";
                }

                $('.modal-title').html(title);

            },

            /**
             * html модального окнаparameters
             * @param {string} modalBody
             * @param {object} parameters
             */
            setModalBody: function (modalBody, parameters) {

                switch (modalBody) {


                    case "fixWithOneHiddenInput":

                        $(".__modal-body").html(Utilites.__fixWithOneHiddenInputModalBody(parameters.inputPartName, parameters.hvalue, parameters.feedbackLabel));
                        break;

                    default:

                        break;

                }

            },

            /**
             * "Тело" модального окна с установкой фиксированного значения и одним скратым инпутом
             * @param {string} inputPartName
             * @param {string} hvalue
             * @param {string} feedbackLabel
             * @returns {String}
             */
            __fixWithOneHiddenInputModalBody: function (inputPartName, hvalue, feedbackLabel) {

                var html = "<div class=\"form-group\">",
                        inputs = "<label>Значение</label>" +
                        "<input type=\"hidden\" name=\"MASSEDIT" + inputPartName + "[0]\" value='" + hvalue + "' >" +
                        "<input class=\"form-control\" type=\"text\" name=\"MASSEDIT" + inputPartName + "[1]\" value=\"\" >";


                if (feedbackLabel) {
                    html += "<div class=\"form-group has-feedback has-feedback-left\">" + inputs +
                            "<div class=\"form-control-feedback\">" + feedbackLabel + "</div></div>";
                } else {
                    html += inputs;
                }

                html += "</div>";

                return html;

            },

            /**
             * post ajax request
             * @param {object} postData
             */
            postAjax: function (postData, beforeRequest, afterResponse) {

                if (typeof beforeRequest === "function") {
                    beforeRequest();
                }

                $.post("<?= $componentPath ?>/ajax.php", postData, Utilites.postSuccess, "json").always(function () {
                    if (typeof afterResponse === "function") {
                        afterResponse();
                    }
                });

            },

            /**
             * column colors
             * @type {object}
             */
            __colors: <?= \Bitrix\Main\Web\Json::encode(unserialize(travelsoft\booking\Utils::getOpt("tbColors"))); ?>,

            /**
             * paint color columns
             * @param {jQuery object} tds
             * @param {string} color
             */
            __paint: function (tds, color) {
                tds.each(function () {
                    $(this).css({"background-color": color});
                });
            },

            /**
             * @param {string} date
             * @param {array} rid
             * @returns {undefined}
             */
            paintColumn: function (date) {


                Utilites.__paint($(".td__" + date), Utilites.__colors.yellow);

                // check sub td's
                $("td[class^=sub-td__" + date + "] input[name^='MAINFORM[']").each(function () {
                    if ($(this).val() != "") {
                        Utilites.__paint($(this).parent(), Utilites.__colors.green);
                    } else {
                        Utilites.__paint($(this).parent(), Utilites.__colors.yellow);
                    }

                    return;
                });

            },

            keyPressComboSwitchMonth: function () {

                // key code
                // 18 - Alt
                // 37 - стрелка влево
                // 39 - стрелка вправо

                var altPressed = false;

                var nextMonth = null;

                var prevMonth = null;

                var monthSelect = $("#selectMonths");

                $(window).on("keydown", function (e) {

                    if (e.keyCode === 18) {
                        altPressed = true;
                        e.preventDefault();
                        return;
                    }

                    if (altPressed && e.keyCode === 39) {
                        // перелистываем на след. месяц
                        nextMonth = monthSelect.find(`option[value=${monthSelect.val()}]`).next();
                        if (nextMonth.attr("value")) {
                            monthSelect.val(nextMonth.attr("value"));
                            monthSelect.trigger("change");
                        }
                        e.preventDefault();
                        return;
                    }

                    if (altPressed && e.keyCode === 37) {
                        // перелистываем на предыдущий месяц
                        prevMonth = monthSelect.find(`option[value=${monthSelect.val()}]`).prev();
                        if (prevMonth.attr("value")) {
                            monthSelect.val(prevMonth.attr("value"));
                            monthSelect.trigger("change");
                        }
                        e.preventDefault();
                        return;
                    }
                });

                $(window).on("keyup", function (e) {
                    if (e.keyCode === 18) {
                        altPressed = false;
                        e.preventDefault();
                    }
                });

            }
        };

        // painting cells after table print
<? foreach ($arResult['dateArray']['daysArray'] as $arDay) : ?>

            Utilites.paintColumn("<?= $arDay['unixDate'] ?>");
<? endforeach ?>

        $(document).on("change", "#price-table select[name^='MAINFORM[']", function () {
            var data = {};
            data[$(this).attr("name")] = $(this).val();
            Utilites.postAjax($.extend({}, data, Utilites.postRequestDataDefault));
        });

        $(document).on('focusout', '#price-table input[type="text"]', function () {
            Utilites.postAjax(Utilites.makePostDataByInput($(this)));
        });

        $(document).on("click", ".setModalBody", function () {
            var $this = $(this),
                    modalBody = $this.data("modal-body"),
                    title = $this.data("modal-title"),
                    parameters = {
                        inputPartName: $this.data("input-part-name"),
                        hvalue: JSON.stringify($this.data("hvalue")) || "",
                        feedbackLabel: $this.data('feedback-label'),
                    };

            Utilites.setModalTitle(title);
            Utilites.setModalBody(modalBody, parameters);
        });

        $(document).on('click', '.sendRequest', function () {
            var form = $(this).closest('form');
            Utilites.postAjax(Utilites.makePostDataByForm(form.serializeArray()), function () {
                form.hide();
                form.siblings(".preloader-up").show();
            }, function () {
                form.show();
                form.siblings(".preloader-up").hide();
            });
            return false;
        });

        $("#modal_form_vertical").on("hide.bs.modal", function () {
            // убираем динамические input
            $(".__modal-body").html("");
            // убираем заголовок
            $(".modal-title").html("");
        });

        moment.locale("<?= $arResult['dateArray']['dateRangeSettings']['locale'] ?>");
        $('.daterange-basic').daterangepicker({
            applyClass: 'bg-slate-600',
            cancelClass: 'btn-default',
            minDate: moment.unix(<?= $arResult['dateArray']['dateRangeSettings']['minUnixDate'] ?>),
            maxDate: moment.unix(<?= $arResult['dateArray']['dateRangeSettings']['maxUnixDate'] ?>),
            autoApply: true,
            locale: {
                format: '<?= $arResult['dateArray']['dateRangeSettings']['format'] ?>',
                separator: '<?= $arResult['dateArray']['dateRangeSettings']['separator'] ?>',
                applyLabel: 'Применить',
                startLabel: 'Начальная дата',
                endLabel: 'Конечная дата',
                cancelLabel: 'Отменить',
                weekLabel: 'W',
                customRangeLabel: 'Custom Range',
                daysOfWeek: moment.weekdaysMin(),
                monthNames: moment.monthsShort(),
                firstDay: moment.localeData().firstDayOfWeek()
            }
        });

        Utilites.keyPressComboSwitchMonth();
    })(jQuery, document, BX, moment, JSON);
</script>