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
if ($arResult["ERRORS"]) {
    ?>
    <div class="alert alert-danger mt-20" role="alert">

        <? for ($i = 0, $cnt = count($arResult["ERRORS"]); $i < $cnt; $i++) { ?>
            <span><?= GetMessage($arResult["ERRORS"][$i]) ?></span>
        <? } ?>

    </div>

    <?
    return;
}

$currency = htmlspecialchars($arResult["ORDER"]["currencyTour"]);

$ORDER_ID = htmlspecialchars($arResult["ORDER"]["dogovor"]["name"]);

?>
<div class="my-order">
    <div id="total__info">
        <div class="row order-info">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <h4 class="my-order__title"><?= GetMessage("ORDER"); ?> <?= $ORDER_ID ?></h4>
                <?
                if ($arResult["ORDER"]["dogovor_status"]["key"] == 19 && $_SESSION['__TRAVELSOFT']['JUST_NOW_CANCELLATION']):
                    unset($_SESSION['__TRAVELSOFT']['JUST_NOW_CANCELLATION'])
                    ?>
                    <div class='mt-20 alert-box alert-attention'><h6><?= GetMessage("IN_CANCELLATION_PROCESS") ?></h6></div>
                <? endif ?>
                <ul>
                    <li class="user__name"><span><?= GetMessage("FIO"); ?></span> <?= htmlspecialchars($arResult["ORDER"]["main_turist"]) ?></li>
                    <li class="count__people"><span><?= GetMessage("PERSONS"); ?></span> <?= htmlspecialchars($arResult["ORDER"]["count_men"]) ?></li>
                    <li class="status"><span><?= GetMessage("STATUS"); ?></span> <?= htmlspecialchars($arResult["ORDER"]["dogovor_status"]["name"]) ?></li>
                    <li class="duration"><span><?= GetMessage("DURATIONS"); ?></span> <?= htmlspecialchars($arResult["ORDER"]["duration"]) ?></li>
                    <li class="date__create"><span><?= GetMessage("CREATION"); ?></span> <?= htmlspecialchars($arResult["ORDER"]["create_date"]) ?></li>
                    <li class="date__from"><span><?= GetMessage("ARRIVAL"); ?></span> <?= htmlspecialchars($arResult["ORDER"]["tour_date"]) ?></li>
                    <?if (!empty($arResult["ORDER"]["guides"])):?>
                        <li class="to__pay"><span><?= GetMessage("GUIDES_TITLE"); ?></span> <?= implode(", ", array_map(function ($arItem) {$link = str_replace("/belarus/about-country/cities", "/private-office/putevoditeli", $arItem["DETAIL_PAGE_URL"]);return '<a target="_blank" href="'.$link.'">"'.$arItem["NAME"].'"</a>';}, $arResult["ORDER"]["guides"]))?></li>
                    <?endif?>
                    <? if (!empty($arResult["ORDER"]["documents"])): ?>
                        <?
                            for ($i = 0, $cnt = count($arResult["ORDER"]["documents"]); $i < $cnt; $i++) {

                                $item = $arResult["ORDER"]["documents"][$i];
                                if ($item['title'] == 'Счет' || $item["title"] == 'Счет и Акт') {
                                    continue;
                                }
                                $arDocs[] = "<a target=\"_blank\" class=\"document__\" href=\"" . htmlspecialchars($item["url"]) . "\">" . htmlspecialchars($item["title"]) . "</a>";
                            }


                            if (!empty($arDocs)):
                                ?>        
                                <li class="documents"><span><?= GetMessage("DOCUMENTATION"); ?></span>
                                    <? echo implode(", ", $arDocs); ?>
                                </li>
                                <?
                            endif;
                        endif;
                    ?>
                </ul>
                    
                
            </div>
            <? if (!in_array($arResult["ORDER"]["dogovor_status"]["key"], array(2, 3, 19))): ?>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="button"><a onclick="annulation();" href="<?= $APPLICATION->GetCurPageParam("order_id=" . $ORDER_ID . "&action=cancel&sessid=" . bitrix_sessid(), array("order_id", "action", "sessid")) ?>" class="btn btn-primary"><?= GetMessage("CANCEL"); ?></a></div>
                    <input type="hidden" id="js-order_info" name="js-order_info" value='<?=base64_encode(serialize($arResult["ORDER"]))?>'/>
					<!-- Если заказ не оплачен и пользователь - не агент   -->
					
                    <script>
                        function annulation() {
                            var formData2 = new FormData();  
                            formData2.append('action','AnnulationSeller');
                            formData2.append('orderdata',$('#js-order_info').val());
                            var xhr2 = new XMLHttpRequest();
                        	xhr2.open("POST", "<?= $templateFolder?>/ajax_seller.php");
                            xhr2.onreadystatechange = function() {
                        		if (xhr2.readyState == 4) {
                        			if(xhr2.status == 200) {
                        			    data = xhr2.responseText;
                                        console.log(data);
                                    }
                        		}
                        	};
                            xhr2.send(formData2); 
                        }
                    </script>
                    </div>
            <? endif ?>
                
        </div>
        <div class="row price-info">
            <table>
                <tr>
                    <th><?= GetMessage("PRICE"); ?></th>
                    <?if($arResult["ORDER"]["discount"][$currency] > 0):?>
                        <th><?= GetMessage("DISCOUNT"); ?></th>
                    <?endif?>
                    <? if (!in_array($arResult["ORDER"]["dogovor_status"]["key"], array(23))) : ?>
                        <th><?= GetMessage("PAID"); ?></th>
                    <?endif; ?>
                    <? if (!in_array($arResult["ORDER"]["dogovor_status"]["key"], array(7, 2, 3, 19, 23))): ?>
                        <th><?= GetMessage("TOPAY"); ?></th>
                     <?endif?>        
                </tr>
                <tr>
                    <td><?= htmlspecialchars($arResult["ORDER"]["price"][$currency]) . " " . $currency ?></td>
                    <?if($arResult["ORDER"]["discount"][$currency] > 0):?>
                        <td><?= htmlspecialchars($arResult["ORDER"]["discount"][$currency]) . " " . $currency ?></td>
                    <?endif?> 
                    <? if (!in_array($arResult["ORDER"]["dogovor_status"]["key"], array(23))) : ?>   
                        <td><?= $arResult["ORDER"]["paid"][$currency] . " " . $currency ?></td>
                    <?endif; ?>
                    <? if (!in_array($arResult["ORDER"]["dogovor_status"]["key"], array(7, 2, 3, 19, 23))): ?>
                        <td><?= $arResult["ORDER"]["toPay"][$currency] . " " . $currency ?></td>
                    <?endif?>    
                </tr>
            </table>
        </div>
    </div>

    <div id="services__container">
        <h4 class="my-order__title"><?= GetMessage("SERVICES"); ?></h4>
        <?
        $forspotpayment = true;
        $have_transfer = false;
        
        for ($i = 0, $cnt = count($arResult["ORDER"]["services"]); $i < $cnt; $i++):
            $item = $arResult["ORDER"]["services"][$i];
            if (!$item['parts']['for_spot_payment']) {
                $forspotpayment = false;
            }
            if ($item["services"]["service_class"] == 2) {
                $have_transfer = true;
            }           
            ?>
            
            <table>
                <tr>
                    <th colspan="3"><?= htmlspecialchars($item["title"]) ?></th>
                </tr>
                <tr>                    
                    <td>
                        <span><?= GetMessage("DATE"); ?></span>     
                        <?= htmlspecialchars($item["date_begin"]) ?>                    
                        <? if($item["date_end"]) :?>
                            - <?= htmlspecialchars($item["date_end"]) ?> 
                        <?php endif; ?>    
                    </td>
                    <td>
                        <span><?= GetMessage("SERVICEDURATIONS"); ?></span>  
                        <?= htmlspecialchars($item["duration"]) ?>
                    </td>
                    <td>
                        <span><?= GetMessage("SERVICEPRICE"); ?></span> 
                        <span class="table-price"><?= htmlspecialchars($item["price"][$currency]) . " " . $currency ?></span>
                    </td>
                </tr>
            </table>

            
        <? endfor ?>
    </div>
    
    <div class="tourists-container mt-20">

            <h4 class="my-order__title"><?= GetMessage("TOURISTS"); ?></h4>
            <div class="table-responsive">

                <table class="table bookRoom">
                    <thead>
                        <tr>
                            <th>
                                <b><?= GetMessage("TOURIST_FIRST_NAME_TITLE") ?></b>
                            </th>
                            <th>
                                <b><?= GetMessage("TOURIST_LAST_NAME_TITLE") ?></b>
                            </th>
                            <th>
                                <b><?= GetMessage("TOURIST_BIRTH_DATE_TITLE") ?></b>
                            </th>
                            <th>
                                <b><?= GetMessage("TOURIST_CITIZEN_TITLE") ?></b>
                            </th>
                            <th>
                                <b><?= GetMessage("TOURIST_PASSPORT_NUM_TITLE") ?></b>
                            </th>
                            <? if ($arResult["ORDER"]["dogovor_status"]["key"] != 7): ?><th></th><?endif;?>
                        </tr>
                    </thead>
                    <tbody>

                        <?
                        for ($i = 0, $cnt = count($arResult["ORDER"]["turists"]); $i < $cnt; $i++):
                            $tourist = $arResult["ORDER"]["turists"][$i];
                            ?>
                            <tr>
                                <td style="vertical-align: middle;" class="t-name" data-label="Имя"><?= htmlspecialchars($tourist["first_name"]) ?></td>
                                <td style="vertical-align: middle;" class="t-last-name" data-label="Фамилия"><?= htmlspecialchars($tourist["last_name"]) ?></td>
                                <td style="vertical-align: middle;" class="t-birthdate" data-label="День рождения"><?= htmlspecialchars($tourist["birth_date"]) ?></td>
                                <td style="vertical-align: middle;" class="t-citizenship" data-label="Гражданство"><?= htmlspecialchars($tourist["citizenship"]) ?></td>
                                <td style="vertical-align: middle;" class="t-passport-num" data-label="Номер паспорта"><?= htmlspecialchars($tourist["passport_num"]) ?></td>
                                <? if ($arResult["ORDER"]["dogovor_status"]["key"] != 7 && $arResult["ORDER"]["dogovor_status"]["key"] != 7): ?><td><a role="button" data-toggle="modal" data-target="#edit-tourist-modal-<?= $i ?>" class="btn-sm btn-primary" rel="nofollow" href="#"><?= GetMessage("TOURIST_REQUEST_EDIT_BTN_TITLE") ?></a></td><?endif;?>
                            </tr>

                        <? endfor ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?if(!in_array($arResult["ORDER"]["dogovor_status"]["key"], array(7, 2, 3, 19, 23)) &&
                !in_array(9, CUser::GetUserGroup($USER->GetID()))):?>
            <div class="payment-info">
            <h4><?= GetMessage("PAY_INFO_TITLE") ?></h4>
            <span><?=GetMessage("DESCRIPTION");?></span>
        </div>
        <?endif;?>

        <?
        if ($arResult["ORDER"]["dogovor_status"]["key"] != 7){
        for ($i = 0, $cnt = count($arResult["ORDER"]["turists"]); $i < $cnt; $i++):
            $tourist = $arResult["ORDER"]["turists"][$i];
            ?>
            <div class="modal fade" id="edit-tourist-modal-<?= $i ?>" tabindex="-1" role="dialog" aria-labelledby="modal-title-<?= $i ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="modal-title-<?= $i ?>"><b><?= GetMessage("EDIT_TOURIST_MODAL_TITLE") ?></b></h4>
                        </div>
                        <form action="#" method="POST" class="edit-tourist-form" enctype="text/plain">
                            <div class="modal-body">
                                <?= bitrix_sessid_post() ?>
                                <input type="hidden" name="order_id" value="<?= $ORDER_ID ?>">
                                <input type="hidden" name="editable_tourist" value="<?= $tourist["first_name"] . " " . $tourist["last_name"] ?>">
                                <div class="form-group">
                                    <label for="first_name"><?= GetMessage("TOURIST_FIRST_NAME_TITLE") ?><span class="star">*</span></label>
                                    <span class="error-container"></span>
                                    <input name="first_name" value="<?= htmlspecialchars($tourist["first_name"]) ?>" type="text" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="last_name"><?= GetMessage("TOURIST_LAST_NAME_TITLE") ?><span class="star">*</span></label>
                                    <span class="error-container"></span>
                                    <input name="last_name" type="text" value="<?= htmlspecialchars($tourist["last_name"]) ?>" class="form-control">
                                </div>
                                <div class="form-group has-feedback">
                                    <label for="birth_date"><?= GetMessage("TOURIST_BIRTH_DATE_TITLE") ?><span class="star">*</span></label>
                                    <span class="error-container"></span>
                                    <input name="birth_date" type="text" value="<?= htmlspecialchars($tourist["birth_date"]) ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="citizenship"><?= GetMessage("TOURIST_CITIZEN_TITLE") ?><span class="star">*</span></label>
                                    <span class="error-container"></span>
                                    <input name="citizenship" type="text" value="<?= htmlspecialchars($tourist["citizenship"]) ?>" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="passport_num"><?= GetMessage("TOURIST_PASSPORT_NUM_TITLE") ?><span class="star">*</span></label>
                                    <span class="error-container"></span>
                                    <input name="passport_num" type="text" value="<?= htmlspecialchars($tourist["passport_num"]) ?>" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="sender-btn" class="btn btn-primary"><?= GetMessage("TOURIST_SEND_BTN_TITLE") ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <? endfor; }?>
    <?
    
    if (in_array($arResult["ORDER"]["dogovor_status"]["key"], array(7, 20, 21, 22)) && !$arResult["ORDER"]["isPaid"]) :
        $formTpl = "<div class='mt-20 payment__form'>";
        $formTpl .= "<form target='_blank' method='POST' action='" . $arParams["URL_TO_PAY"] . "'>";
        $formTpl .= "<input type='hidden' name='token' value='" . $_SESSION["__TRAVELSOFT"]["TOKEN"] . "'>";
        $formTpl .= "<input type='hidden' name='orderCode' value='" . $ORDER_ID . "'>";
        $formTpl .= "<input type='hidden' name='method' value='login'>";
        $formTpl .= "<input type='hidden' name='typePayment' value='#TYPE_PAYMENT#'>";
        $formTpl .= "<button class='btn btn-primary' name='submit' type='submit'>" . GetMessage("PAY_BTN_TITLE") . "</button>";
        $formTpl .= "</form>";
        $formTpl .= "</div>";

        $arGroups = $USER->GetUserGroupArray();

        $expendedFirst = !$forspotpayment && !$arResult["ORDER"]["dogovor_status"]["key"] != 22;
        $expendedForSpotPayment = $forspotpayment || $arResult["ORDER"]["dogovor_status"]["key"] == 22;
        ?>
        <div id="pay__container">
            <h4 class="my-order__title"><?= GetMessage("PAYMENTOPTIONS"); ?></h4>
            <ul class="tabs-head nav-tabs-one">
                <? if ($expendedForSpotPayment): ?>
                    <li class="active"><a data-toggle="tab" aria-expanded="true" href="#section5"><?= GetMessage("FORSPOTPAYMENT"); ?></a></li>
                <? endif ?>
				<? if (!in_array("9", $arGroups)): ?>
                	<li <? if ($expendedFirst): ?>class="active"<? endif ?>><a data-toggle="tab" href="#section1"><?= GetMessage("BANKCARD"); ?></a></li>

                    <li><a data-toggle="tab" href="#section2"><?= GetMessage("ERIP"); ?></a></li>
                <? endif ?>


				<!---b---!>
				<? if (in_array("9", $arGroups)): ?>
                	<li <? if ($expendedFirst): ?>class="active"<? endif ?>><a data-toggle="tab" href="#section1"><?= GetMessage("BANKCARD"); ?></a></li>

					<li><a data-toggle="tab" href="#section6"><?= GetMessage("PAY_BY_INVOICE"); ?></a></li>
                <? endif ?>

            </ul>
            <div class="tab-content">
                <div id="section1" class="tab-pane <? if ($expendedFirst): ?>active in<? else: ?>fade<? endif ?>">
                    <?= GetMessage("PAYMENTOPTIONSTEXT", array("#PAY_FORM#" => str_replace("#TYPE_PAYMENT#", "card", $formTpl))); ?>
                </div>
                <? $arGroups = $USER->GetUserGroupArray(); ?>
                <? if (!in_array("9", $arGroups)): ?>
                    <div id="section2" class="tab-pane fade">
                        <?= GetMessage("ERIPTEXT", array("#PAY_FORM#" => str_replace("#TYPE_PAYMENT#", "erip", $formTpl))); ?>
                    </div>
                <? endif ?>
                <? /* <div id="section3" class="tab-pane fade">
                  <?= GetMessage("CASHTEXT"); ?>
                  </div>
                  <div id="section4" class="tab-pane fade">
                  <?= GetMessage("BANKPAYMENTTEXT"); ?>
                  </div> */ ?>
                <? if ($expendedForSpotPayment): ?>
                    <div id="section5" class="tab-pane active in">
                        <? if ($arResult["ORDER"]["dogovor_status"]["key"] != 22): ?>
                            <a class="btn btn-primary" href="<?= $APPLICATION->GetCurPageParam("order_id=" . $ORDER_ID . "&action=fsp&sessid=" . bitrix_sessid(), array("order_id", "action", "sessid"), false) ?>"><?= GetMessage('PAY_BTN_TITLE') ?></a>
                            <?= GetMessage("FORSPOTPAYMENTTEXT"); ?>
                        <? else: ?>
                            <p style="color: green"><b><?= GetMessage('FORSPOTPAYMENTCHOOSE') ?></b></p>
                        <? endif ?>
                    </div>
                <? endif ?>
                <div id="section6" class="tab-pane fade">
                    <?
                    for ($i = 0, $cnt = count($arResult["ORDER"]["documents"]); $i < $cnt; $i++) {
                        $item = $arResult["ORDER"]["documents"][$i];
                        if ($item["title"] == 'Счет' || $item["title"] == 'Счет и Акт') {
                            ?>
                            <a target="__blank" class="btn btn-primary" href="<?= htmlspecialchars($item['url']) ?>"><?= GetMessage("PAY_BY_INVOICE_BUTTON") ?></a>
                            <p><?= GetMessage("PAY_BY_INVOICE_TEXT"); ?></p>
                            <?
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    <? elseif (!$arResult["ORDER"]["isPaid"] && !in_array($arResult["ORDER"]["dogovor_status"]["key"], array(19, 2, 23))): ?>
        <div class='mt-20 alert-box alert-attention'><h6><?= GetMessage("NOT_ALLOW_TO_PAY") ?></h6></div>
    <? endif ?>
    
   <?if (isset($arResult["cross_sale_parameters"]) && !empty($arResult["cross_sale_parameters"]) && !$have_transfer): ?>
                            <?
                            $APPLICATION->IncludeComponent(
                                    "travelsoft:crossale", "services_detail_page", Array(
                                "SERVICE_ID" => $arResult["cross_sale_parameters"]['service_id'],
                                "TYPE" => $arResult["cross_sale_parameters"]['type'],
                                "BASKET_SERVICES_TYPES" => [$arResult["cross_sale_parameters"]['type']],
                                "DATE_FROM" => $arResult["cross_sale_parameters"]['date_from'],
                                "DATE_TO" => $arResult["cross_sale_parameters"]['date_to'],
                                "ADULTS" => $arResult["cross_sale_parameters"]['adults'],
                                "CHILDREN" => $arResult["cross_sale_parameters"]['children']
                                    )
                            );
                            ?>
                        <? endif ?>     
</div>
<script>
    /**
     * @param {jQuery} $
     * @returns {undefined}
     */
    (function ($) {

    $($("input[name='pay_variant']:checked").data("href")).show();
            $("input[name='pay_variant']").on("click", function () {

    $("#pay__variants__container div[id$='_description']").hide();
            $($("input[name='pay_variant']:checked").data("href")).show();
    });
<? if ($arResult["ORDER"]["dogovor_status"]["key"] != 7): ?>
        $(document).ready(function () {

            var errors_messages = {
                first_name: "<?= GetMessage("TOURIST_WRONG_FIRST_NAME") ?>",
                    last_name: "<?= GetMessage("TOURIST_WRONG_LAST_NAME") ?>",
                    passport_num: "<?= GetMessage("TOURIST_WRONG_PASSPORT_NUM") ?>",
                    citizenship: "<?= GetMessage("TOURIST_WRONG_CITIZENSHIP") ?>",
                    birth_date: "<?= GetMessage("TOURIST_WRONG_BIRTH_DATE") ?>"
            }

            $("form.edit-tourist-form").on("submit", function (e) {
                
                var $this = $(this);
                var fields = $this.serializeArray();
                var haveError = false;
                $this.find(".error-container").each(function () {
                    $(this).html("");
                });
                
                for (var i = 0; i < fields.length; i++) {
                    
                    switch (fields[i].name) {

                        case "first_name":
                        case "last_name":
                        case "passport_num":
                        case "citizenship":

                            if (!fields[i].value) {
                                haveError = true;
                                $(this).find(`input[name=${fields[i].name}]`).prev('.error-container').text(errors_messages[fields[i].name]);
                            }

                         break;

                        case "birth_date":
                            
                            if (/^([0-9]{2})\.([0-9]{2})\.([0-9]{4})$/.test(fields[i].name)) {
                                haveError = true;
                                $(this).find(`input[name=${fields[i].name}]`).prev('.error-container').text(errors_messages[fields[i].name]);
                            }
                            break;
                    }
                }
                
                if (!haveError) {
                    
                    $.post("<?= $templateFolder?>/ajax.php", $this.serialize(), function (resp) {
                        
                        if (resp.status === "ok") {
                            $this.find(".modal-footer").remove();
                            $this.find(".modal-body").html(`<?= GetMessage("EDIT_TOURIST_OK_MESSAGE") ?>`);
                            $this.off("submit");
                        } else {
                            alert("Some error. Try again later.");
                        }
                        
                    }, "json").fail(function () {
                        alert("Some error. Try again later.");
                    });
                }
                e.preventDefault();
            });
        });
<? endif ?>

    })(jQuery);
</script>



<div class="booking-messanger mt-20">
<?
$APPLICATION->IncludeComponent(
        "travelsoft:travelsoft.booking.messanger", ".default", array(
    "FREQREQUEST" => "20",
    "ORDER_ID" => htmlspecialchars($ORDER_ID),
    "TOKEN" => $_SESSION["__TRAVELSOFT"]["TOKEN"],
    "COMPONENT_TEMPLATE" => ".default",
    "USE_AJAX" => "Y",
    "DATE_FROM" => htmlspecialchars($arResult["ORDER"]["create_date"]) . " 00:00:00"
        ), false
);
?>
</div>