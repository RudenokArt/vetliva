<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
    "ORDER_NUMBER" => "list",
    "USER_NAME" => "user",
    "PEOPLE_COUNT" => "users",
    "DATE_FROM" => "car",
    "STATUS" => "check-square-o",
    "TICKET_COST" => "shopping-cart",
    "TICKET_CURRENCY" => "money",
    "DISCOUNT" => "thumbs-o-up",
    "TO_PAY" => "shopping-cart",
    "PAID" => "credit-card",
    "DATE_CREATE" => "calendar"
);

?>



<div class="filter-container">
    
    <?if ($arResult["IS_SET_FILTER"] || !empty($arResult["LIST"])): ?>
    
    <form method="get" class="mt-20" id="order-list-filter" action="<?= $curPage?>">

        <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="form-group has-feedback">
                    <label><?=GetMessage("DATE_CREATEFROM");?></label>
                    <input id="date_create_from" class="form-control" name="order_filter[date_create][0]" type="text" value="<?= htmlspecialchars($_REQUEST["order_filter"]["date_create"][0])?>">
                    <span class="glyphicon glyphicon-calendar form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="form-group has-feedback">
                    <label><?=GetMessage("DATE_CREATETO");?></label>
                    <input id="date_create_to" class="form-control" name="order_filter[date_create][1]" type="text" value="<?= htmlspecialchars($_REQUEST["order_filter"]["date_create"][1])?>">
                    <span class="glyphicon glyphicon-calendar form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="form-group has-feedback">
                    <label><?=GetMessage("tourdatefrom");?></label>
                    <input id="date_from" class="form-control" name="order_filter[date_from][0]" type="text" value="<?= htmlspecialchars($_REQUEST["order_filter"]["date_from"][0])?>">
                    <span class="glyphicon glyphicon-calendar form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="form-group has-feedback">
                    <label><?=GetMessage("tourdateto");?></label>
                    <input id="date_to" class="form-control" name="order_filter[date_from][1]" type="text" value="<?= htmlspecialchars($_REQUEST["order_filter"]["date_from"][1])?>">
                    <span class="glyphicon glyphicon-calendar form-control-feedback" aria-hidden="true"></span>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label><?=GetMessage("USER_NAME");?></label>
                    <input class="form-control" name="order_filter[t_name]" type="text" value="<?= htmlspecialchars($_REQUEST["order_filter"]["t_name"])?>">
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="form-group">
                    <label><?=GetMessage("status");?></label>
                    <select class="form-control" name="order_filter[status]">
                        <?foreach ($arResult["STATUSES"] as $val) {?>
                        <option <?if (isset($_REQUEST["order_filter"]["status"]) &&
                                (string)$_REQUEST["order_filter"]["status"] === (string)$val) {echo "selected";}?> value="<?= $val?>"><?= GetMessage("status_" . $val)?></option>
                        <?}?>
                    </select>
                    <!--<input class="form-control" name="order_filter[t_name]" type="text" value="<?= htmlspecialchars($_REQUEST["order_filter"]["t_name"])?>">-->
                </div>
            </div>
        </div>

        <div class="text-right">
            <button type="submit" value="show" class="btn btn-primary"><?=GetMessage("SHOW");?></button>
            <button id="clearForm" class="btn btn-primary"><?=GetMessage("CLEAN");?></button>
        </div>
        
    </form>
    
    <?endif?>
    
</div>
<?if ($arResult["LIST"]):?>
    <div class="mt-20"><?=GetMessage("SORT");?></div>
    <form id="order-sort-form" class="mt-5" action="<?= $curPage?>" method="get">
        <div class="row">
            <div class="col-lg-3 col-md-3">
                <div class="form-group">
                    <select name="order_sort[sort]" class="form-control">
                        <?for ($i = 0, $cnt = count($arResult["SORT_ALLOW"]); $i < $cnt; $i++):
                            $sort = $arResult["SORT_ALLOW"][$i]?>
                        <option <?if ($sort == $_REQUEST["order_sort"]["sort"]) { echo "selected"; }?> value="<?= $sort?>"><?= GetMessage($sort)?></option>
                        <?endfor?>
                    </select>
                </div>
            </div>
            <div class="col-lg-3 col-md-3">
                <div class="form-group">
                    <select name="order_sort[order]" class="form-control">
                        <?
                        $orders = array("asc", "desc");
                        for ($i = 0, $cnt = count($orders); $i < $cnt; $i++):
                            $order = $orders[$i]?>
                        <option <?if ($order == $_REQUEST["order_sort"]["order"]) { echo "selected"; }?> value="<?= $order?>"><?= GetMessage($order)?></option>
                        <?endfor?>
                    </select>
                </div>
            </div>
        </div>
    </form>

    <div class="table-responsive">

           <table class="table bookRoom">
              <thead>
                 <tr>
                    <?foreach ($arResult["COLUMNS"] as $code):?>
                     <th style='background-color: #337ab7' class="text-center"><i style='color: #fff; cursor: pointer' title="<?= GetMessage($code)?>" class="fa fa-<?= $awesome_icons[$code]?>"></i></th>
                    <?endforeach?>
                 </tr>
              </thead>
              <tbody>

                   <?for ($i = 0, $cnt = count($arResult["LIST"]); $i < $cnt; $i++):
                       $item = $arResult["LIST"][$i];
                       $order_id = htmlspecialchars($item["dogovor"]["name"]);
                       ?>
                    <tr>
                        <td data-label="<?= GetMessage("ORDER_NUMBER")?>"><?= $order_id?></td>
                        <td data-label="<?= GetMessage("USER_NAME")?>"><?= htmlspecialchars($item["main_turist"])?></td>
                        <td data-label="<?= GetMessage("PEOPLE_COUNT")?>"><?= htmlspecialchars($item["count_men"])?></td>
                        <td data-label="<?= GetMessage("DATE_FROM")?>"><?= htmlspecialchars($item["tour_date"])?></td>
                        <td data-label="<?= GetMessage("STATUS")?>"><?= htmlspecialchars($item["dogovor_status"]["name"])?></td>
                        <td data-label="<?= GetMessage("TICKET_COST")?>"><?= htmlspecialchars($item["price"][$item["currencyTour"]])?></td>
                        <td data-label="<?= GetMessage("TICKET_CURRENCY")?>"><?= htmlspecialchars($item["currencyTour"])?></td>
                        <?if($arResult["IS_AGENT"]):?>
                        <td data-label="<?= GetMessage("DISCOUNT")?>"><?= htmlspecialchars($item["discount"][$item["currencyTour"]])?></td>
                        <?endif?>
                        <td data-label="<?= GetMessage("TO_PAY")?>"><?= htmlspecialchars($item["toPay"][$item["currencyTour"]])?></td>
                        <td data-label="<?= GetMessage("PAID")?>"><?= htmlspecialchars($item["paid"][$item["currencyTour"]])?></td>
                        <td data-label="<?= GetMessage("DATE_CREATE")?>"><?= htmlspecialchars($item["create_date"])?></td>
                        
                    </tr>
                   <?endfor?>
              </tbody>
           </table>
    </div>
    <?if ($arResult["NAV_STRING"] <> '') { echo $arResult["NAV_STRING"]; }?>
    
<?else:?>
    <div class="alert alert-danger mt-20" role="alert"><?=GetMessage("ORDERSNOTFOUND");?></div>
<?endif?>
    
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
    <?endif?>
    
    <? if ($arResult["IS_SET_FILTER"] || !empty($arResult["LIST"])): ?>
    
    $.datetimepicker.setLocale('<?= LANGUAGE_ID == "by" ? "ru": LANGUAGE_ID?>');
    
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
    
    <?endif?>
        
})(jQuery);
</script>

