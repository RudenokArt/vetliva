<?
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
$this->setFrameMode(true);

$oAsset = \Bitrix\Main\Page\Asset::getInstance();

$oAsset->addCss("https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.min.css", true);
$oAsset->addJs("https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.4/build/jquery.datetimepicker.full.min.js", true);

$curPage = $APPLICATION->GetCurPage(false);

$awesome_icons = array(
    "ORDER_NUMBER" => "list-unordered",
    "PEOPLE_COUNT" => "users4",
    "DATE_FROM" => "calendar",
    "STATUS" => "checkmark-circle2",
    "TICKET_COST" => "cart2",
    "TICKET_CURRENCY" => "cash",
    "TYPE_SERVICE" => "stack-text",
    "NAME_SERVICE" => "city",
    "DATE_CREATE" => "car",
    "DETAIL" => "info3"
);
?>

<? //dm($arResult,false,false,false); ?>


<div class="filter-container">

<? if ($arResult["IS_SET_FILTER"] || !empty($arResult["LIST"])): ?>

        <form method="get" class="mt-20" id="order-list-filter" action="<?= $curPage ?>">

            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="form-group has-feedback">
                        <label><?= GetMessage("DATE_CREATEFROM"); ?></label>
                        <input id="date_create_from" class="form-control" name="order_filter[date_create][0]" type="text" value="<?= htmlspecialchars($_REQUEST["order_filter"]["date_create"][0]) ?>">
                        <i class="icon-calendar3 form-control-feedback" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-group has-feedback">
                        <label><?= GetMessage("DATE_CREATETO"); ?></label>
                        <input id="date_create_to" class="form-control" name="order_filter[date_create][1]" type="text" value="<?= htmlspecialchars($_REQUEST["order_filter"]["date_create"][1]) ?>">
                        <i class="icon-calendar3 form-control-feedback" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-group has-feedback">
                        <label><?= GetMessage("tourdatefrom"); ?></label>
                        <input id="date_from" class="form-control" name="order_filter[date_from][0]" type="text" value="<?= htmlspecialchars($_REQUEST["order_filter"]["date_from"][0]) ?>">
                        <i class="icon-calendar3 form-control-feedback" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-group has-feedback">
                        <label><?= GetMessage("tourdateto"); ?></label>
                        <input id="date_to" class="form-control" name="order_filter[date_from][1]" type="text" value="<?= htmlspecialchars($_REQUEST["order_filter"]["date_from"][1]) ?>">
                        <i class="icon-calendar3 form-control-feedback" aria-hidden="true"></i>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <label><?= GetMessage("USER_NAME"); ?></label>
                        <input class="form-control" name="order_filter[t_name]" type="text" value="<?= htmlspecialchars($_REQUEST["order_filter"]["t_name"]) ?>">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form-group">
                        <label><?= GetMessage("status"); ?></label>
                        <select class="form-control" name="order_filter[status]">
                                <? foreach ($arResult["STATUSES"] as $val) { ?>
                                <option <? if (isset($_REQUEST["order_filter"]["status"]) &&
                                            (string) $_REQUEST["order_filter"]["status"] === (string) $val) {
                                        echo "selected";
                                    }
                                    ?> value="<?= $val ?>"><?= GetMessage("status_" . $val) ?></option>
    <? } ?>
                        </select>
                        <!--<input class="form-control" name="order_filter[t_name]" type="text" value="<?= htmlspecialchars($_REQUEST["order_filter"]["t_name"]) ?>">-->
                    </div>
                </div>
            </div>

            <div class="text-right" style="margin-top: 20px;">
                <button type="submit" value="show" class="btn btn-primary"><?= GetMessage("SHOW"); ?></button>
                <button id="clearForm" class="btn btn-primary"><?= GetMessage("CLEAN"); ?></button>
            </div>

        </form>

<? endif ?>

</div>
<? if ($arResult["LIST"]): ?>
    <div class="mt-20"><?= GetMessage("SORT"); ?></div>
    <form id="order-sort-form" class="mt-5" action="<?= $curPage ?>" method="get">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="form-group">
                    <select name="order_sort[sort]" class="form-control">
    <? for ($i = 0, $cnt = count($arResult["SORT_ALLOW"]); $i < $cnt; $i++):
        $sort = $arResult["SORT_ALLOW"][$i];
        ?>
                            <option <? if (isset($_REQUEST["order_sort"]["sort"]) && $sort == $_REQUEST["order_sort"]["sort"]) {
            echo "selected";
        } elseif (!isset($_REQUEST["order_sort"]["sort"]) && $sort == "createdate") {
            echo "selected";
        } ?> value="<?= $sort ?>"><?= GetMessage($sort) ?></option>
                        <? endfor ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group">
                    <select name="order_sort[order]" class="form-control">
    <?
    $orders = array("asc", "desc");
    for ($i = 0, $cnt = count($orders); $i < $cnt; $i++):
        $order = $orders[$i];
        ?>
                            <option <? if (isset($_REQUEST["order_sort"]["order"]) && $order == $_REQUEST["order_sort"]["order"]) {
            echo "selected";
        } elseif (!isset($_REQUEST["order_sort"]["order"]) && $order == "desc") {
            echo "selected";
        } ?> value="<?= $order ?>"><?= GetMessage($order) ?></option>
                    <? endfor ?>
                    </select>
                </div>
            </div>
        </div>
    </form>
    <div class="table-responsive">

        <table class="table">
            <thead>
                <tr>
    <? foreach ($arResult["COLUMNS"] as $code): ?>
        <? /* <th style='background-color: #337ab7' class="text-center"><i style='color: #fff; cursor: pointer' title="<?= GetMessage($code)?>" class="icon-<?= $awesome_icons[$code]?>"></i></th> */ ?>
                        <th style='background-color: #337ab7' class="text-center"><span class="thead-label"><?= GetMessage($code) ?></span></th>
    <? endforeach ?>
                </tr>
            </thead>
            <tbody>

    <?
    for ($i = 0, $cnt = count($arResult["LIST"]); $i < $cnt; $i++):
        $item = $arResult["LIST"][$i];
        $order_id = htmlspecialchars($item["code"]["name"]);
        $key = htmlspecialchars($item["key"]);
        ?>
                    <tr class="<?= $item["row_class"]?>">
                        <td class="text-center"><a class="detail__order__link" target="__blank" href="<?= $curPage . "detail.php?key=" . $key ?>"><?= $order_id ?></a></td>
                        <td class="text-center"><?= htmlspecialchars($item["mens"]) ?></td>
                        <td class="text-center"><?= htmlspecialchars($item["create_date"]) ?></td>
                        <td class="text-center"><?= htmlspecialchars($item["status"]["name"]) ?></td>
                        <td class="text-center"><?= htmlspecialchars($item["status_dogovor"]["name"]) ?></td>
                        <td class="text-center"><?= htmlspecialchars($item["brutto"][$item["currencyTour"]]) ?></td>
                        <td class="text-center"><?= htmlspecialchars($item["discount"][$item["currencyTour"]]) ?></td>
                        <td class="text-center"><?= htmlspecialchars($item["netto"][$item["currencyTour"]]) ?></td>
                        <td class="text-center"><?= htmlspecialchars($item["currencyTour"]) ?></td>
                        <td class="text-center"><?= htmlspecialchars($item["type"]["name"]) ?></td>
                        <td><?= htmlspecialchars($item["name"]) ?></td>
                        <td class="text-center"><?= htmlspecialchars($item["begin_date"]) ?></td>
                        <td><a class="detail__order__link" target="__blank" href="<?= $curPage . "detail.php?key=" . $key ?>"><?= GetMessage("MORE"); ?></a></td>
                    </tr>
    <? endfor ?>

            </tbody>
        </table>

    </div>

    <? if ($arResult["NAV_STRING"] <> '') {
        echo $arResult["NAV_STRING"];
    } ?>

<? else: ?>
    <div class="alert alert-danger mt-20" role="alert"><?= GetMessage("ORDERSNOTFOUND"); ?></div>
<? endif ?>

<script>
    /**
     * @param {jQuery} $
     * @returns {undefined}
     */
    (function ($) {

<? if (!empty($arResult["LIST"])): ?>
            $("#order-sort-form select").on("change", function () {
                $("#order-sort-form").submit();
            });
<? endif ?>

<? if ($arResult["IS_SET_FILTER"] || !empty($arResult["LIST"])): ?>

            $.datetimepicker.setLocale('<?= LANGUAGE_ID == "by" ? "ru" : LANGUAGE_ID ?>');

            $("#date_create_from, #date_create_to, #date_from, #date_to").datetimepicker({
                timepicker: false,
                format: "d.m.Y",
                dayOfWeekStart: 1
            });

            // clear form
            $("#clearForm").on("click", function () {
                $("#order-list-filter input[type='text']").val("");
                $("#order-list-filter select option:first").prop("selected", true);
                return false;
            });

<? endif ?>

    })(jQuery);
</script>

