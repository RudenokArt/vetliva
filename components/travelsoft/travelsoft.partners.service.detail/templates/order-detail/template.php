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

//dm($arResult["SERVICE"],false,false,false);

$currency = htmlspecialchars($arResult["SERVICE"]["currencyTour"]);

$ORDER_ID = htmlspecialchars($arResult["SERVICE"]["code"]["name"]);

?>
<div class="my-order">
    <div id="total__info">
        <h4 class="my-order__title content-group text-semibold"><?= GetMessage("ORDER"); ?> <?=$ORDER_ID?></h4>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="panel panel-body border-top-blue">
                    <ul class="list-feed">
                        <li class="border-success-400 count__people"><span><?= GetMessage("PERSONS"); ?></span> <?= htmlspecialchars($arResult["SERVICE"]["mens"]) ?></li>
                        <li class="border-success-400 status"><span><?= GetMessage("STATUS"); ?></span> <?= htmlspecialchars($arResult["SERVICE"]["status"]["name"]) ?></li>
                        <li class="border-success-400 duration"><span><?= GetMessage("DURATIONS"); ?></span> <?= htmlspecialchars($arResult["SERVICE"]["nday"]) ?></li>
                        <li class="border-success-400 date__create"><span><?= GetMessage("CREATION"); ?></span> <?= htmlspecialchars($arResult["SERVICE"]["create_date"]) ?></li>
                        <li class="border-success-400 date__from"><span><?= GetMessage("ARRIVAL"); ?></span> <?= htmlspecialchars($arResult["SERVICE"]["begin_date"]) ?></li>
                        <li class="border-success-400 date__to"><span><?= GetMessage("DEPARTURE"); ?></span> <?= htmlspecialchars($arResult["SERVICE"]["end_date"]) ?></li>
                        <li class="border-success-400 price"><span><?= GetMessage("PRICE"); ?></span> <?= htmlspecialchars($arResult["SERVICE"]["netto"][$currency]) . " " . $currency ?></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div id="total__info">
        <h4 class="my-order__title"><?= GetMessage("TURISTS"); ?></h4>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="panel panel-body border-top-blue">
                    <?for($i=0;$i<count($arResult["SERVICE"]["turists"]);$i++):?>
                        <?$tourist = $arResult["SERVICE"]["turists"][$i];$k=$i+1;?>
                        <ul class="list-feed list-feed-solid">
                            <h4 class="border-warning-400 my-order__title"><?= GetMessage("TURIST")." ".$k ?></h4>
                            <li class="border-warning-400 user__name"><span><?= GetMessage("FIO"); ?></span> <?= htmlspecialchars($tourist["last_name"]) ?> <?= htmlspecialchars($tourist["first_name"]) ?></li>
                            <li class="border-warning-400 user__bday"><span><?= GetMessage("BDAY"); ?></span> <?= htmlspecialchars($tourist["birth_date"]) ?></li>
                            <li class="border-warning-400 user__sex"><span><?= GetMessage("SEX"); ?></span> <?= GetMessage("SEX-".htmlspecialchars($tourist["sex"])); ?></li>
                            <li class="border-warning-400 user__citizenship"><span><?= GetMessage("CITIZENSHIP"); ?></span> <?= htmlspecialchars($tourist["citizenship"]); ?></li>
                            <li class="border-warning-400 user__pasnum"><span><?= GetMessage("PASSPORT_NUM"); ?></span> <?= htmlspecialchars($tourist["passport_num"]); ?></li>
                            <li class="border-warning-400 user__pasdate"><span><?= GetMessage("PASSPORT_DATE"); ?></span> <?= htmlspecialchars($tourist["passport_date"]); ?></li>
                        </ul>
                    <?endfor;?>
                </div>
            </div>
        </div>
    </div>
    
    <div id="services__container">
        <h4 class="my-order__title"><?= GetMessage("SERVICES"); ?></h4>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="panel panel-body border-top-blue">
                    <ul class="list-feed">
                        <li class="border-success-400 service__type"><span><?= GetMessage("TYPE_SERVICE"); ?></span> <?= htmlspecialchars($arResult["SERVICE"]["type"]["name"]) ?></li>
                        <li class="border-success-400 service__title"><span><?= GetMessage("NAME"); ?></span> <?= htmlspecialchars($arResult["SERVICE"]["name"]) ?></li>
                        <li class="border-success-400 service__duration"><span><?= GetMessage("SERVICEDURATIONS"); ?></span> <?= htmlspecialchars($arResult["SERVICE"]["nday"]) ?></li>
                        <li class="border-success-400 service__status"><span><?= GetMessage("SERVICESTATUS"); ?></span> <?= htmlspecialchars($arResult["SERVICE"]["status"]["name"]) ?></li>
                        <li class="border-success-400 service__price"><span><?= GetMessage("SERVICEPRICE"); ?></span> <?= htmlspecialchars($arResult["SERVICE"]["netto"][$currency]) . " " . $currency ?></li>
                    </ul>
                    <?if ($arResult["SERVICE"]["status"]["key"] == 19):?>
                    <div class="forspotpayment">
                        <span class="fsp-title">К оплате клиенту:</span> <b><?= htmlspecialchars($arResult["SERVICE"]["brutto"][$currency]) . " " . $currency ?></b> <a class="btn btn-success" onclick="return confirm('Вы уверены ?')" href="<?= $APPLICATION->GetCurPageParam("fsp=yes", array('fsp'), false)?>">Клиент оплатил на месте</a> &nbsp; <a class="btn btn-primary" onclick="return confirm('Услуга будет аннулирована безвозвратно. Вы уверены ?')" href="<?= $APPLICATION->GetCurPageParam("fsp=no", array('fsp'), false)?>">Клиент не оплатил на месте</a>
                    </div>
                    <?endif?>
                </div>
            </div>
        </div>
    </div>
    <?if(strlen($arResult["SERVICE"]['comment']) > 0):?>
    <div id="total__info">
        <h4 class="my-order__title content-group text-semibold"><?= GetMessage("USER_COMMENT")?></h4>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="panel panel-body border-top-blue">
                    <?= $arResult["SERVICE"]['comment']?>
                </div>
            </div>
        </div>
    </div>
    <?endif?>
    <p class="ts-float-right"><a href="/partners/spisok-zakazov/" class="btn btn-primary"><?=GetMessage('BACK_BTN_LIST')?></a></p>
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

    })(jQuery);
</script>
