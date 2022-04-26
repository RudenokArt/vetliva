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

// default colors array
$__arColors = array(
    "red" => "#E55959", // stop sales
    "lightRed" => "#E5A0A0", // no arrivals, no departures
    "yellow" => "#F4F993", // no sales
    "green" => "#A5CEAC"              // quota exists
);

$isCutUser = in_array(\Bitrix\Main\Config\Option::get("travelsoft.booking.dev.tools", "cut_provider_group_id"), $GLOBALS["USER"]->GetUserGroupArray()) ||
        in_array(17, $GLOBALS["USER"]->GetUserGroupArray()) && $arParams['SUPER_USER_EDIT'] !== 'Y';

/**
 * формирует период проживания для отображения
 * @param array $arLifePeriod
 */
function __formateLifePeriod(array $arLifePeriod = null) {
    $result = array();
    $end = count($arLifePeriod);
    $start = 0;
    $startVal = null;
    sort($arLifePeriod);
    while ($start < $end) {
        $startVal = $arLifePeriod[$start];
        while ($start < $end) {
            if (($arLifePeriod[$start] + 1) != $arLifePeriod[$start + 1]) {
                array_push($result, ($startVal != $arLifePeriod[$start] ? $startVal . "-" . $arLifePeriod[$start] : $startVal));
                $start++;
                break;
            }
            $start++;
        }
    }

    return implode(", ", $result);
}

Bitrix\Main\Loader::includeModule("travesoft.booking.dev.tools");
$arService = current(travelsoft\booking\datastores\ServicesDataStore::get(array("filter" => array("ID" => $arParams['ROW_ID']))));
?>
<div class="panel panel-flat">
    <div class="form-with-select">
        <div class="row">
            <div class="col-md-4">
                <form name="serviceSelectForm" action="<?= POST_FORM_ACTION_URI ?>" method="get">
                    <? if ($arParams['PROVIDER_ID']): ?>
                        <input name="provider_id" type="hidden" value="<?= $arParams['PROVIDER_ID'] ?>">
                    <? endif ?>
                    <? if ($arResult['dateArray']["_get"]): ?>
                        <input name="getDate" value="<?= $arResult['dateArray']["_get"] ?>" type="hidden">
                    <? endif ?>
                    <div class="form-group">
                        <label><b>Выберите экскурсионный тур:</b></label>
                        <select data-placeholder="..." onchange="document.serviceSelectForm.submit();" id="selectService" class="select fx-min-width-300px" name="row_id">
                            <option></option>
                            <? foreach ($arResult['servicesInfo']['links'] as $id => $arRowsId) : ?>
                                <? if (isset($arResult['servicesInfo']['ibElementsName'][$id])): ?>

                                    <? foreach ($arRowsId as $el_id): ?>
                                        <option <? if ($el_id == $arParams['ROW_ID']): ?>selected<? endif ?> value="<?= $el_id ?>"><?= $arResult['servicesInfo']['services'][$el_id]['UF_NAME'] ?></option>
                                    <? endforeach ?>

                                <? endif ?>
                            <? endforeach ?>
                        </select>
                    </div>
                </form>
                <? if (@$_REQUEST['row_id'] > 0): ?>
                <div class="form-with-select">
                    <form name="monthSelectForm" action="<?= POST_FORM_ACTION_URI ?>" method="get">
                        <? if ($arParams['ROW_ID']): ?>
                            <input name="row_id" type="hidden" value="<?= $arParams['ROW_ID'] ?>">
                        <? endif ?>
                        <? if ($arParams['PROVIDER_ID']): ?>
                            <input name="provider_id" type="hidden" value="<?= $arParams['PROVIDER_ID'] ?>">
                        <? endif ?>
                        <div class="form-group">
                            <label><b>Выберите период:</b></label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                    <input type="text"  name="getDateRange" class="form-control daterange-filtr fx-min-width-150px" value=""> 
                                </div>
                            <?/*<select onchange="document.monthSelectForm.submit();" id="selectMonths" class="select fx-min-width-150px"
                                    name="getDate">
                                        <? foreach ($arResult['dateArray']['monthsArray'] as $arMonth) : ?>
                                    <option <? if ($arResult['dateArray']["_get"] == $arMonth['unixDate']): ?>selected<? endif ?>
                                                                                                              value="<?= $arMonth['unixDate'] ?>"><?= $arMonth['title'] ?></option>
                                                                                                          <? endforeach ?>
                            </select>*/?>
                        </div>
                    </form>
                </div>
                <?endif;?>
            </div>
            <? if (@$_REQUEST['row_id'] > 0): ?>
                <div class="col-md-4">
                    <form id="serviceSelectFormFiltr" name="serviceSelectFormFiltr" action="<?= POST_FORM_ACTION_URI ?>" method="get">
                        <div class="form-group">
                            <label><b>Выберите тариф:</b></label>
                            <?
                               $uniquerates = []; foreach ($arResult['rates'] as $rateId => $arRate) { 
                                $arKeys = array_keys($arResult['ptRates'][2], $rateId);
                                if (empty($arKeys))
                                    continue;
                                    $uniquerates[abs(crc32($arRate['UF_NAME']))] = $arRate['UF_NAME'];
                               }
                            ?>
                            <select data-placeholder="..." onchange="applyfiltr();" id="selectRate" class="select fx-min-width-300px" name="selectRate">
                                <option></option>
                                <? foreach ($uniquerates as $ratesign => $rate) :?>
                                    <option value="<?=$ratesign?>"><?=$rate?></option>
                                <? endforeach ?>
                            </select>
                            <?$tmpcurrency = ["BYN", "RUB", "EUR", "USD"];?>
                            <label><b>Выберите валюту тарифа:</b></label>
                            <select data-placeholder="..."  onchange="applyfiltr();" id="selectCurrency" class="select fx-min-width-300px" name="selectCurrency">
                                <option value="">Все валюты</option>
                                <? foreach ($tmpcurrency as $tmpcur) : ?>
                                    <option value="<?=$tmpcur?>"><?=$tmpcur?></option>
                                <? endforeach ?>
                            </select>
                        </div>
                        <a href="javascript:void(0);" style="text-align: right;" onclick="resetfiltr(); return false;">Сбросить фильтры</a>
                    </form>
                </div>
                <div class="col-md-2 text-right">
                    <button type="button" id="clear-cache" class="btn btn-success">Сохранить</button>
                </div>
            <? endif ?>
        </div>
    </div>

    <? if (@$_REQUEST['row_id'] > 0): ?>
        <div class="form-with-select copy-column">
            <form method="POST" action="<?= POST_FORM_ACTION_URI ?>">
                <div class="row">  
                    <?= bitrix_sessid_post() ?>
                    <input type="hidden" name="uniqid" value="<?= $arParams['UNIQUE_ID'] ?>">    
                    <input type="hidden" name="mainForm[massEdit][isRange]" value="Y">    

                    <div class="col-md-4">
                        <label><b>Выберите дату</b></label>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                            <input autocomplete="off" type="text" id="column_for_copy" class="form-control daterange-single"> 
                        </div>
                    </div>    
                    
                    <div class="col-md-4">
                        <label><b>Выберите период</b></label>
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                <input type="text" name="mainForm[massEdit][dateRange]" class="form-control daterange-basic" value=""> 
                            </div>
                        </div> 
                    </div> 

                    <div class="col-md-2 text-right">
                        <button type="submit" class="btn btn-success copyColumn">Скопировать столбец</button>
                    </div> 
                </div>     
                <div class="row"> 
                    <div class="col-md-4">
                        <label><b>Выберите тариф</b></label>
                        <div class="form-group">
                            <div class="input-group">                                    
                                <?
                                    $uniquerates = []; foreach ($arResult['rates'] as $rateId => $arRate) { 
                                    $arKeys = array_keys($arResult['ptRates'][2], $rateId);
                                    if (empty($arKeys))
                                        continue;

                                        $cur = $arResult['currency'][$arResult['rates'][$rateId]['UF_CURRENCY_ID']]['name'];
                                        $uniquerates[abs(crc32($arRate['UF_NAME']))] = $arRate['UF_NAME'] . "(". $cur . ")";
                                   }
                                ?>
                                <select data-placeholder="..." id="selectCopyRate" class="select fx-min-width-300px">
                                    <option value="0">Все</option>
                                    <? foreach ($uniquerates as $ratesign => $rate) :?>
                                        <option value="<?=$ratesign?>"><?=$rate?></option>
                                    <? endforeach ?>
                                </select>
                            </div>
                        </div> 
                    </div> 
                </div>                 
                </div>
            </form>
        </div>                 
    <?endif;?> 

    <? if (!isset($arResult['servicesInfo']['services'][$arParams['ROW_ID']])): ?>
    </div>
    <?
    return;
endif
?>
<form method="post" name="price-table" id='price-table' action="<?= POST_FORM_ACTION_URI ?>">
    <?= bitrix_sessid_post() ?>
    <?$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();?>
    <? if ($arParams['PROVIDER_ID']): ?>
        <input name="provider_id" type="hidden" value="<?= $arParams['PROVIDER_ID'] ?>">
    <? endif ?>
    <input type="hidden" name="uniqid" value="<?= $arParams['UNIQUE_ID'] ?>">
    <input type="hidden" name="row_id" value="<?= $arParams['ROW_ID'] ?>">
    <? if ($arResult['dateArray']["_get"]): ?>
        <input name="getDate" value="<?= $arResult['dateArray']["_get"] ?>" type="hidden">
    <? endif ?>
    <? if ($request->get('getDateRange')!=''): ?>
        <input name="getDateRange" value="<?= $request->get('getDateRange') ?>" type="hidden">
    <? endif ?>
    <div style="position: relative" class="table-responsive" id="prices-table-area">
        <!--<div style="-moz-user-select: none; -khtml-user-select: none; user-select: none; font-size: 104px; position: fixed; z-index: 9999; right: 32px; cursor: pointer;" class="scroll-div-nav"><span class="scroll-left">&laquo;</span>&nbsp;<span class="scroll-right">&raquo;</span></div>-->
         <!-- <span style="-moz-user-select: none; -khtml-user-select: none; user-select: none; display: none; font-size: 150px; position: fixed; color: rgba(102, 102, 102, 0.48); z-index: 9999; left: 500px; cursor: pointer;" class="scroll-left">&laquo;</span>&nbsp;
        <span style="display: none; -moz-user-select: none; -khtml-user-select: none; user-select: none; font-size: 150px; position: fixed; z-index: 9999; right: 32px; color: rgba(102, 102, 102, 0.48); cursor: pointer;" class="scroll-right">&raquo;</span> -->
<!--            <table id="hidden-table-head" style="display: none; position: absolute">

        </table>-->
        <table style="position: relative;" id="prices-table"  class="table table-bordered">
            <thead id="unvisible-thead" style="display: none; position: absolute; z-index: 10">
                <tr>

                    <th id="first-unvisible-th" class="" style="background-color: #fff; border: none !important;"></th>
                    <?
                    $cnt = 1;
                    foreach ($arResult['dateArray']['daysArray'] as $arDay) :
                        ?>
                        <th style="background-color: #fff;<? if (count($arResult['dateArray']['daysArray']) == $cnt): ?>border-right: none !important<? endif ?>"><?= $arDay['title'] ?></th>
                        <?
                        $cnt++;
                    endforeach;
                    ?>

                </tr>
            </thead>
            <thead id="main-thead">
                <tr>

                    <th class="first-th"></th>
                    <? foreach ($arResult['dateArray']['daysArray'] as $arDay) : ?>
                        <th><?= $arDay['title'] ?></th>
                        <?
                    endforeach;
                    $daysNumber = count($arResult['dateArray']['daysArray'])
                    ?>

                </tr>
            </thead>
            <tbody>
                <?
                $pt_ids_sorted = array_keys((new travelsoft\booking\datastores\PriceTypesDataStore([
                            "filter" => ["ID" => $arResult['ptRates'][1]],
                            "order" => ["UF_SORT" => "ASC", "UF_AGE_MIN" => "DESC"]
                                ]))->fetch(["ID"]));
                foreach ($arResult['rates'] as $rateId => $arRate):
                    $arKeys = array_keys($arResult['ptRates'][2], $rateId);
                    if (empty($arKeys))
                        continue;
                    $currency = $arResult['currency'][$arResult['rates'][$rateId]['UF_CURRENCY_ID']]['name'];
                    ?>
                    <tr class="rateblock-header" data-ratesign="<?=abs(crc32($arRate['UF_NAME']))?>" data-currency="<?=$currency?>">
                        <td style="padding-left: 80px !important; text-align: left; position: relative" class="grey-color fs-22" colspan="<?= $daysNumber + 1 ?>"><span class="rate-title"><i class="icon-arrow-down5"></i><i class="icon-arrow-up5"></i><?= $arRate['UF_NAME'] . " (" . $currency . ")" ?></span></td>
                    </tr>
                    
                    <tr class="scroll-tr">  
                        <td>
                            <span style="top: calc(77% - 115px); -moz-user-select: none; -khtml-user-select: none; user-select: none; display: none; font-size: 150px; position: fixed; color: rgba(102, 102, 102, 0.48); z-index: 9999; left: 500px; cursor: pointer;" class="scroll-left">&laquo;</span>&nbsp;
                            <span style="top: calc(77% - 115px); display: none; -moz-user-select: none; -khtml-user-select: none; user-select: none; font-size: 150px; position: fixed; z-index: 9999; right: 32px; color: rgba(102, 102, 102, 0.48); cursor: pointer;" class="scroll-right">&raquo;</span>
                         </td> 
                    </tr>

                    <tr class="rateblock-row" data-ratesign="<?=abs(crc32($arRate['UF_NAME']))?>" data-currency="<?=$currency?>">
                        <td style="height: 55px" class="main-td" class="lightgreen-color"><b>Квота<br><?= $quotaSubTitle ?></b> <? if (!$isCutUser): ?><br> [<a class="setModalBody" data-input-part-name="[quotes][<?= $rateId ?>]" data-modal-title="Форма массового редактирования <b>Квот</b>" data-modal-body="fix" data-toggle="modal" href="#modal_form_vertical">изменить</a>]<? endif ?></td>
                        <td style="height: 55px; display: none" class="unvisible-td lightgreen-color"><b>Квота<br><?= $quotaSubTitle ?></b> <? if (!$isCutUser): ?><br> [<a class="setModalBody" data-input-part-name="[quotes][<?= $rateId ?>]" data-modal-title="Форма массового редактирования <b>Квот</b>" data-modal-body="fix" data-toggle="modal" href="#modal_form_vertical">изменить</a>]<? endif; ?></td>

                        <?
                        $i = 0;
                        while ($i < $daysNumber):
                            ?>
                            <td data-rid="<?= $rateId ?>" style="height: 55px" class="sub-td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>__<?= $rateId ?> td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>">
                                <input <? if ($isCutUser): ?>readonly="" disabled=""<? endif ?> name="mainForm[quotes][<?= $rateId ?>][<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>]" value="<?= $arResult['quotes'][$rateId][$arResult['dateArray']['daysArray'][$i]['unixDate']]['UF_QUOTE'] ?>" class="input-width-30px" type="text">
                            </td>
                            <?
                            $i++;
                        endwhile;
                        ?>
                    </tr>
                    <tr class="rateblock-row" data-ratesign="<?=abs(crc32($arRate['UF_NAME']))?>" data-currency="<?=$currency?>">
                        <td style="height: 25px !important;" class="main-td lightgreen-color"><b>Продано</b></td>
                        <td style="height: 25px; display: none;" class="unvisible-td lightgreen-color"><b>Продано</b></td>
                        <?
                        $i = 0;
                        while ($i < $daysNumber):
                            ?>
                            <td style="height: 25px !important;" class="sub-td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>__<?= $rateId ?> td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>" id="quotes_sold__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>__<?= $rateId ?>">
                                <?
                                if (!empty($arResult['quotes'][$rateId][$arResult['dateArray']['daysArray'][$i]['unixDate']]['UF_SOLD_NUMBER'])) {
                                    echo $arResult['quotes'][$rateId][$arResult['dateArray']['daysArray'][$i]['unixDate']]['UF_SOLD_NUMBER'];
                                }
                                ?>
                            </td>
                            <?
                            $i++;
                        endwhile;
                        ?>
                    </tr>
                    <tr class="rateblock-row" data-ratesign="<?=abs(crc32($arRate['UF_NAME']))?>" data-currency="<?=$currency?>">
                        <td style="height: 25px !important;" class="main-td lightgreen-color"><b>В продаже</b></td>
                        <td style="height: 25px !important; display: none" class="unvisible-td lightgreen-color"><b>В продаже</b></td>
                        <?
                        $i = 0;
                        while ($i < $daysNumber):
                            ?>
                            <td data-rid="<?= $rateId ?>" style="height: 25px !important;"  class="sub-td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>__<?= $rateId ?> td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>" id="quotes_onsale__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>__<?= $rateId ?>">

                                <?
                                $quote = (int) $arResult['quotes'][$rateId][$arResult['dateArray']['daysArray'][$i]['unixDate']]['UF_QUOTE'];
                                $sold = (int) $arResult['quotes'][$rateId][$arResult['dateArray']['daysArray'][$i]['unixDate']]['UF_SOLD_NUMBER'];
                                $toSale = $quote - $sold;
                                if ($toSale) {
                                    echo $toSale;
                                }
                                ?>
                            </td>
                            <?
                            $i++;
                        endwhile;
                        ?>
                    </tr>

                    <tr class="rateblock-row" data-ratesign="<?=abs(crc32($arRate['UF_NAME']))?>" data-currency="<?=$currency?>">
                        <td style="height: 55px" class="main-td" class="lightgreen-color"><b>Релиз период (ночи)<br></b> <? if (!$isCutUser): ?>
                                <br> [<a class="setModalBody" data-input-part-name="[releasePeriod][<?= $rateId ?>]" data-modal-title="Форма массового редактирования <b>Релиз периода (ночи)</b>" data-modal-body="fix" data-toggle="modal" href="#modal_form_vertical">изменить</a>]<? endif ?></td>
                        <td style="height: 55px; display: none" class="unvisible-td lightgreen-color"><b>Релиз период (ночи)<br></b> <? if (!$isCutUser): ?>
                                <br> [<a class="setModalBody" data-input-part-name="[releasePeriod][<?= $rateId ?>]" data-modal-title="Форма массового редактирования <b>Релиз периода (ночи)</b>" data-modal-body="fix" data-toggle="modal" href="#modal_form_vertical">изменить</a>]<? endif; ?></td>

                        <?
                        $i = 0;
                        while ($i < $daysNumber):
                            ?>
                            <td data-rid="<?= $rateId ?>" style="height: 55px" class="sub-td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>__<?= $rateId ?> td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>">
                                <input <? if ($isCutUser): ?>readonly="" disabled=""<? endif ?> name="mainForm[releasePeriod][<?= $rateId ?>][<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>]" value="<?= $arResult['quotes'][$rateId][$arResult['dateArray']['daysArray'][$i]['unixDate']]['UF_RELEASE_PERIOD'] ?>" class="input-width-30px" type="text">
                            </td>
                            <?
                            $i++;
                        endwhile;
                        ?>
                    </tr>

                    <tr class="rateblock-row" data-ratesign="<?=abs(crc32($arRate['UF_NAME']))?>" data-currency="<?=$currency?>">
                        <td style="height: 40px !important;" class="main-td lightgreen-color"><b>Stop sale</b> <? if (!$isCutUser): ?><br> [<a class="setModalBody" data-input-part-name="[stopSales][<?= $rateId ?>]" data-modal-title="Форма массового редактирования <b>Stop sale</b>" data-modal-body="toggle" data-toggle="modal" href="#modal_form_vertical">изменить</a>]<? endif ?></td>
                        <td style="height: 40px !important;; display: none" class="unvisible-td lightgreen-color"><b>Stop sale</b> <? if (!$isCutUser): ?><br> [<a class="setModalBody" data-input-part-name="[stopSales][<?= $rateId ?>]" data-modal-title="Форма массового редактирования <b>Stop sale</b>" data-modal-body="toggle" data-toggle="modal" href="#modal_form_vertical">изменить</a>]<? endif ?></td>
                        <?
                        $i = 0;
                        while ($i < $daysNumber):
                            ?>
                            <td data-rid="<?= $rateId ?>" style="height:40px !important;"  class="sub-td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>__<?= $rateId ?> td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>">
                                <? $value = $arResult['quotes'][$rateId][$arResult['dateArray']['daysArray'][$i]['unixDate']]["UF_STOP"]; ?>
                                <input <? if ($isCutUser): ?>disabled=""<? endif ?> <? if ($value == 1): ?>checked<? endif ?> name="mainForm[stopSales][<?= $rateId ?>][<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>]" value="1" type="checkbox">
                            </td>
                            <?
                            $i++;
                        endwhile;
                        ?>
                    </tr>

                    <?
                    $feedbackLabel = $currency ? "<b>" . $currency . "</b>" : $currency;

                    // Осторожно!!! Говнокод для сортировки!!!
                    $ptRates = [];
                    foreach ($arKeys as $key) {
                        $ptRates[$key] = $arResult['ptRates'][1][$key];
                    }

                    $ptRates_sorted = [];
                    foreach ($pt_ids_sorted as $id) {
                        $key_ = \array_search($id, $ptRates);
                        if ($key_ !== false) {
                            $ptRates_sorted[$key_] = $id;
                        }
                    }
                    ////////////////////////////////////////////
                    $mainptrid = 0;
                    foreach ($ptRates_sorted as $key => $v):
                        $ptid = $v;
                        $ptrid = $arResult['ptRates'][0][$key];
                        if ($arResult['priceTypes'][$ptid]) {
                            if ($arResult['priceTypes'][$ptid]['UF_MAIN']=='1') $mainptrid = $ptrid;
                            ?>
                            <tr class="rateblock-row" data-ratesign="<?=abs(crc32($arRate['UF_NAME']))?>" data-currency="<?=$currency?>">
                                <td style="height: 100px"  class="main-td lightblue-color"><b><?= $arResult['priceTypes'][$ptid]['UF_NAME'] ?></b> <br> <? if (!$isCutUser): ?>[<a data-toggle="modal" class="setModalBody" data-feedback-label="<?= $feedbackLabel ?>" data-input-part-name="[prices]" data-hvalue="<?= $ptrid ?>" data-modal-title="Форма массового редактирования <b><?= $arResult['priceTypes'][$ptid]['UF_NAME'] ?></b>" data-modal-body="fixWithOneHiddenInput" href="#modal_form_vertical">изменить</a>] <? endif ?></td>
                                <td style="display: none" class="unvisible-td lightblue-color"><b><?= $arResult['priceTypes'][$ptid]['UF_NAME'] ?></b> <br> <? if (!$isCutUser): ?>[<a data-toggle="modal" class="setModalBody" data-feedback-label="<?= $feedbackLabel ?>" data-input-part-name="[prices]" data-hvalue="<?= $ptrid ?>" data-modal-title="Форма массового редактирования <b><?= $arResult['priceTypes'][$ptid]['UF_NAME'] ?></b>" data-modal-body="fixWithOneHiddenInput" href="#modal_form_vertical">изменить</a>] <? endif ?></td>
                                <?
                                $i = 0;
                                while ($i < $daysNumber):
                                    ?>
                                    <td style="height: 100px" class="sub-td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>__<?= $rateId ?> td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>">
                                        <?
                                        $_uxd = $arResult['dateArray']['daysArray'][$i]['unixDate'];
                                        $value = $arResult['prices'][$_uxd][$ptrid]["UF_GROSS"] != 0.0001 ? $arResult['prices'][$_uxd][$ptrid]["UF_GROSS"] : 0;

                                        /* no arrivals, no departures, life period */
                                        if (!$arNoArrivals[$_uxd][$rateId]) {
                                            $arNoArrivals[$_uxd][$rateId] = $arResult['prices'][$_uxd][$ptrid]['UF_NO_ARRIVALS'];
                                        }
                                        if (!$arNoDepartures[$_uxd][$rateId]) {
                                            $arNoDepartures[$_uxd][$rateId] = $arResult['prices'][$_uxd][$ptrid]['UF_NO_DEPARTURES'];
                                        }
                                        if (!$arLifePeriod[$_uxd][$rateId]) {
                                            $arLifePeriod[$_uxd][$rateId] = __formateLifePeriod($arResult['prices'][$_uxd][$ptrid]['UF_LIFE_PERIOD'] ? $arResult['prices'][$_uxd][$ptrid]['UF_LIFE_PERIOD'] : null);
                                        }
                                        ?>
                                        <input <? if ($isCutUser): ?>readonly="" disabled=""<? endif ?> data-rid="<?= $rateId ?>"  value="<?= $value ?>" name="mainForm[prices][<?= $_uxd ?>][<?= $ptrid ?>]" class="input-width-30px" type="text">
                                    </td>
                                    <?
                                    $i++;
                                endwhile;
                                ?>
                            </tr>
                            <?
                        }
                    endforeach;
                    ?>
                    <?// редактирование скидок?>
                    <?if ($mainptrid):?>
                        <tr class="rateblock-row absolute-discount-<?=abs(crc32($arRate['UF_NAME']))?> discount-row hide"  data-ratesign="<?=abs(crc32($arRate['UF_NAME']))?>" data-currency="<?=$currency?>">
                            <td style="height: 100px" class="main-td lightblue-color">
                                <b>
                                Скидка в абсолютных единицах</b> <br> <? if (!$isCutUser): ?>[<a
                                        data-toggle="modal" class="setModalBody"
                                        data-feedback-label="<?= $feedbackLabel ?>" data-input-part-name="[discountpricesabs]"
                                        data-hvalue="<?= $mainptrid ?>"
                                        data-modal-title="Форма массового редактирования <b>Скидка в абсолютных единицах</b>"
                                        data-modal-body="fixWithOneHiddenInput"
                                        href="#modal_form_vertical">изменить</a>] <? endif ?>
                                        <input type="checkbox" value="" onchange="$('.percent-discount-<?=abs(crc32($arRate['UF_NAME']))?>').removeClass('hide'); $('.absolute-discount-<?=abs(crc32($arRate['UF_NAME']))?>').addClass('hide'); $( this ).prop( 'checked', false );" id="check-percent-<?=abs(crc32($arRate['UF_NAME']))?>"/>
                                        <label for="check-percent-<?=abs(crc32($arRate['UF_NAME']))?>">В процентах</label>
                                        </td>
                            <td style="display: none" class="unvisible-td lightblue-color">
                                <b>Скидка в абсолютных единицах</b> <br> <? if (!$isCutUser): ?>[<a
                                        data-toggle="modal" class="setModalBody"
                                        data-feedback-label="<?= $feedbackLabel ?>" data-input-part-name="[discountpricesabs]"
                                        data-hvalue="<?= $mainptrid ?>"
                                        data-modal-title="Форма массового редактирования <b>Скидка в абсолютных единицах</b>"
                                        data-modal-body="fixWithOneHiddenInput"
                                        href="#modal_form_vertical">изменить</a>] <? endif ?></td>
                                <?
                                $i = 0;
                                while ($i < $daysNumber):
                                    ?>
                                <td style="height: 100px"
                                    class="sub-td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>__<?= $rateId ?> td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>">
                                        <?
                                        $_uxd = $arResult['dateArray']['daysArray'][$i]['unixDate'];
                                        $value = $arResult['prices'][$_uxd][$mainptrid]["UF_DISCOUNT_ABS"] != 0.0001 ? $arResult['prices'][$_uxd][$mainptrid]["UF_DISCOUNT_ABS"] : 0;
                                        ?>
                                    <input <? if ($isCutUser): ?>readonly="" disabled=""<? endif ?>
                                                                 data-rid="<?= $rateId ?>" value="<?= $value ?>"
                                                                 name="mainForm[discountpricesabs][<?= $_uxd ?>][<?= $mainptrid ?>]" class="input-width-30px"
                                                                 type="text">
                                </td>
                                <?
                                $i++;
                            endwhile;
                            ?>
                        </tr>
                        <tr class="rateblock-row percent-discount-<?=abs(crc32($arRate['UF_NAME']))?> discount-row" data-ratesign="<?=abs(crc32($arRate['UF_NAME']))?>" data-currency="<?=$currency?>">
                            <td style="height: 100px" class="main-td lightblue-color">
                                <b>
                                Скидка в %</b> <br> <? if (!$isCutUser): ?>[<a
                                        data-toggle="modal" class="setModalBody"
                                        data-feedback-label="%" data-input-part-name="[discountpricespercent]"
                                        data-hvalue="<?= $mainptrid ?>"
                                        data-modal-title="Форма массового редактирования <b>Скидка в %</b>"
                                        data-modal-body="fixWithOneHiddenInput"
                                        href="#modal_form_vertical">изменить</a>] <? endif ?></td>
                            <td style="display: none" class="unvisible-td lightblue-color">
                                <b>Скидка в %</b> <br> <? if (!$isCutUser): ?>[<a
                                        data-toggle="modal" class="setModalBody"
                                        data-feedback-label="%" data-input-part-name="[discountpricespercent]"
                                        data-hvalue="<?= $mainptrid ?>"
                                        data-modal-title="Форма массового редактирования <b>Скидка в %</b>"
                                        data-modal-body="fixWithOneHiddenInput"
                                        href="#modal_form_vertical">изменить</a>] <? endif ?>
                                        <input type="checkbox" value="" onchange="$('.percent-discount-<?=abs(crc32($arRate['UF_NAME']))?>').addClass('hide'); $('.absolute-discount-<?=abs(crc32($arRate['UF_NAME']))?>').removeClass('hide'); $( this ).prop( 'checked', false );" id="check-abs-<?=abs(crc32($arRate['UF_NAME']))?>"/>
                                        <label for="check-abs-<?=abs(crc32($arRate['UF_NAME']))?>">В абослютных</label>
                                        </td>
                                <?
                                $i = 0;
                                while ($i < $daysNumber):
                                    ?>
                                <td style="height: 100px"
                                    class="sub-td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>__<?= $rateId ?> td__<?= $arResult['dateArray']['daysArray'][$i]['unixDate'] ?>">
                                        <?
                                        $_uxd = $arResult['dateArray']['daysArray'][$i]['unixDate'];
                                        $value = $arResult['prices'][$_uxd][$mainptrid]["UF_DISCOUNT_PERCENT"] != 0.0001 ? $arResult['prices'][$_uxd][$mainptrid]["UF_DISCOUNT_PERCENT"] : 0;
                                        ?>
                                    <input <? if ($isCutUser): ?>readonly="" disabled=""<? endif ?>
                                                                 data-rid="<?= $rateId ?>" value="<?= $value ?>"
                                                                 name="mainForm[discountpricespercent][<?= $_uxd ?>][<?= $mainptrid ?>]" class="input-width-30px"
                                                                 type="text">
                                </td>
                                <?
                                $i++;
                            endwhile;
                            ?>
                        </tr>
                    <?endif;?>
                    <tr class="rateblock-row" data-ratesign="<?=abs(crc32($arRate['UF_NAME']))?>" data-currency="<?=$currency?>">
                        <td style="height: 100px" class="main-td"><b><? if ($isExcursion): ?>Продолжительность тура (дней)<? else: ?>Период проживания (дней)<? endif ?></b> <? if (!$isCutUser): ?><br> [<a data-toggle="modal" class="setModalBody" data-input-part-name="[lifePeriod]" data-hvalue="<?= $rateId ?>" data-modal-title="Форма массового редактирования <b><? if ($isExcursion): ?>Продолжительность тура (дней)<? else: ?>Период проживания (дней)<? endif ?></b>" data-modal-body="fixWithOneHiddenInput" href="#modal_form_vertical">изменить</a>]<? endif ?></td>
                        <td style="display: none" class="unvisible-td"><b><? if ($isExcursion): ?>Продолжительность тура (дней)<? else: ?>Период проживания (дней)<? endif ?></b> <? if (!$isCutUser): ?><br> [<a data-toggle="modal" class="setModalBody" data-input-part-name="[lifePeriod]" data-hvalue="<?= $rateId ?>" data-modal-title="Форма массового редактирования <b><? if ($isExcursion): ?>Продолжительность тура (дней)<? else: ?>Период проживания (дней)<? endif ?></b>" data-modal-body="fixWithOneHiddenInput" href="#modal_form_vertical">изменить</a>]<? endif ?></td>

                        <?
                        $i = 0;
                        while ($i < $daysNumber):
                            $_uxd = $arResult['dateArray']['daysArray'][$i]['unixDate'];
                            ?>
                            <td style="height: 100px" class="td__<?= $_uxd ?> sub-td__<?= $_uxd ?>__<?= $rateId ?>"><input <? if ($isCutUser): ?>readonly="" disabled=""<? endif ?> data-rid="<?= $rateId ?>" value="<?= $arLifePeriod[$_uxd][$rateId] ?>" name="mainForm[lifePeriod][<?= $_uxd ?>][<?= $rateId ?>]" class="input-width-30px" type="text"></td>
                            <?
                            $i++;
                        endwhile;
                        ?>
                    </tr>

                <? endforeach ?>

            </tbody>
        </table>
    </div>
</form>
<div class="preloader-up-main" style="display: none; text-align: center; margin: 50px">
    <div class="preloader-up-text">
        <b>Происходит загрузка данных. Это может занять несколько минут. Благодарим за ожидание.</b>
    </div>
    <div class="preloader-up-text"><img src="<?= $templateFolder ?>/loading7_black.gif"></div>
</div>
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
                <input type="hidden" name="uniqid" value="<?= $arParams['UNIQUE_ID'] ?>">
                <div class="modal-body">
                    <label><input name="mainForm[massEdit][isRange]" type="radio" checked value="Y"> Выберите период </label> &nbsp; или &nbsp; <label><input name="mainForm[massEdit][isRange]" type="radio" value="N"> выберите конкретные даты </label>
                    <div class="range-dates-block">
                        <div class="form-group">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                <input type="text" name="mainForm[massEdit][dateRange]" class="form-control daterange-basic" value=""> 
                            </div>
                        </div>
                        <label>По заданным дням недели</label>
                        <div class="form-group">
                            <input type="checkbox" name="mainForm[massEdit][dayNumber][0]" value="1">
                            <label><b>Понедельник</b></label>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" name="mainForm[massEdit][dayNumber][1]" value="2">
                            <label><b>Вторник</b></label>  
                        </div>
                        <div class="form-group"> 
                            <input type="checkbox" name="mainForm[massEdit][dayNumber][2]" value="3">
                            <label><b>Среда</b></label> 
                        </div>
                        <div class="form-group">
                            <input type="checkbox" name="mainForm[massEdit][dayNumber][3]" value="4">
                            <label><b>Четверг</b></label>  
                        </div>
                        <div class="form-group"> 
                            <input type="checkbox" name="mainForm[massEdit][dayNumber][4]" value="5">
                            <label><b>Пятница</b></label> 
                        </div>
                        <div class="form-group">  
                            <input type="checkbox" name="mainForm[massEdit][dayNumber][5]" value="6">
                            <label><b>Суббота</b></label>
                        </div>
                        <div class="form-group">
                            <input type="checkbox" name="mainForm[massEdit][dayNumber][6]" value="7">
                            <label><b>Воскресенье</b></label>  
                        </div>
                    </div>
                    <div class="single-dates-block hidden">
                        <div class="form-group form-group__daterage-single-block">
                            <div class="input-group">
                                <span class="input-group-addon"><i class="icon-calendar22"></i></span>
                                <input autocomplete="off" type="text" name="mainForm[massEdit][singleDates][<?= time() ?>]" class="form-control daterange-single"> 
                            </div>
                        </div>
                        <div class="add-single-date-btn-block mt-20 text-right"><button type="button" class="btn btn-primary add-single-date-block">+Еще</button></div>
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
    (function ($, document, BX, moment) {

        /**
         * Объект вспомогательных функций
         * @type {object}
         */
        var Utilites = {

            /**
             * параметры post-запроса по-умолчанию
             */
            postRequestDataDefault: {
                sessid: BX.bitrix_sessid(),
                uniqid: "<?= $arParams['UNIQUE_ID'] ?>"
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

                var data = {};
                if ($this.attr('type') == "checkbox") {
                    data[$this.attr("name")] = $this.is(":checked") ? 1 : 0;
                } else {
                    data[$this.attr("name")] = $this.val();
                }

                return $.extend({}, Utilites.postRequestDataDefault, data);

            },

            /**
             * Обработчик post запроса
             * @param {mixed} data
             * @returns {Boolean}
             */
            postSuccess: function (data) {

                var toUpdateLifePeriods = [];
                var currentInput = null;
                var k, j, checked, arDate = [], arRid = {}, cnt,
                        __toDateArray = function (date) {
                            if ($.inArray(date, arDate) === -1) {
                                arDate.push(date);
                            }
                        },
                        __formateLifePeriod = function (arLifePeriod) {

                            var result = [], end = arLifePeriod.length, start = 0, startVal;
                            arLifePeriod.sort(function (a, b) {
                                return a - b;
                            });
                            while (start < end) {
                                startVal = arLifePeriod[start];
                                while (start < end) {
                                    if ((arLifePeriod[start] + 1) != arLifePeriod[start + 1]) {
                                        result.push(startVal != arLifePeriod[start] ? startVal + "-" + arLifePeriod[start] : startVal);
                                        start++;
                                        break;
                                    }
                                    start++;
                                }
                            }

                            return result.join(",");

                        }

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

                    for (k in data) {

                        switch (k) {

                            case "quotes":

                                for (j = 0; j < data[k].length; j++) {
                                    $("#price-table input[name='mainForm[" + k + "][" + data[k][j]["rate_id"] + "][" + data[k][j]["unixDate"] + "]']").val(parseInt(data[k][j]["value"]));

                                    data[k][j]["sold"] = parseInt(data[k][j]["sold"]);

                                    $("#" + k + "_sold__" + data[k][j]["unixDate"] + "__" + data[k][j]["rate_id"]).text(data[k][j]["sold"] > 0 ? data[k][j]["sold"] : "");

                                    data[k][j]["onSale"] = parseInt(data[k][j]["onSale"]);

                                    $("#" + k + "_onsale__" + data[k][j]["unixDate"] + "__" + data[k][j]["rate_id"]).text(data[k][j]["onSale"] > 0 ? data[k][j]["onSale"] : "");

                                    data[k][j]["value"] = parseInt(data[k][j]["value"]);

                                    $("#price-table input[name='mainForm[" + k + "][" + data[k][j]["rate_id"] + "][" + data[k][j]["unixDate"] + "]']").val(data[k][j]["value"] > 0 ? data[k][j]["value"] : "");

                                    __toDateArray(data[k][j]["unixDate"]);
                                }

                                break;

                            case "releasePeriod":

                                for (j = 0; j < data[k].length; j++) {
                                    $("#price-table input[name='mainForm[" + k + "][" + data[k][j]["rate_id"] + "][" + data[k][j]["unixDate"] + "]']").val(parseInt(data[k][j]["value"]));

                                    data[k][j]["sold"] = parseInt(data[k][j]["sold"]);

                                    $("#" + k + "_sold__" + data[k][j]["unixDate"] + "__" + data[k][j]["rate_id"]).text(data[k][j]["sold"] > 0 ? data[k][j]["sold"] : "");

                                    data[k][j]["onSale"] = parseInt(data[k][j]["onSale"]);

                                    $("#" + k + "_onsale__" + data[k][j]["unixDate"] + "__" + data[k][j]["rate_id"]).text(data[k][j]["onSale"] > 0 ? data[k][j]["onSale"] : "");

                                    data[k][j]["value"] = parseInt(data[k][j]["value"]);

                                    $("#price-table input[name='mainForm[" + k + "][" + data[k][j]["rate_id"] + "][" + data[k][j]["unixDate"] + "]']").val(data[k][j]["value"] > 0 ? data[k][j]["value"] : "");

                                    __toDateArray(data[k][j]["unixDate"]);
                                }

                                break;

                            case "prices":

                                for (j = 0; j < data[k].length; j++) {
                                    currentInput = $("#price-table input[name='mainForm[" + k + "][" + data[k][j]["unixDate"] + "][" + data[k][j]["ptrid"] + "]']");
                                    currentInput.val(data[k][j]["value"].toString() != "" ? data[k][j]["value"] : "");
                                    __toDateArray(data[k][j]["unixDate"]);
                                    if ($.inArray(data[k][j]["unixDate"], toUpdateLifePeriods) === -1 && data[k][j]["action"] !== "delete") {
                                        toUpdateLifePeriods.push(data[k][j]["unixDate"]);
                                        setTimeout(function (timestamp, currentInput) {
                                            $("#price-table input[name='mainForm[lifePeriod][" + timestamp + "][" + currentInput.data("rid") + "]']").trigger("focusout");
                                        }, 500, data[k][j]["unixDate"], currentInput);
                                    }
                                }

                                break;
                            
                            case "discountpricesabs":

                                for (j = 0; j < data[k].length; j++) {
                                    currentInput = $("#price-table input[name='mainForm[" + k + "][" + data[k][j]["unixDate"] + "][" + data[k][j]["ptrid"] + "]']");
                                    currentInput.val(data[k][j]["value"].toString() != "" ? data[k][j]["value"] : "");
                                    __toDateArray(data[k][j]["unixDate"]);
                                }

                                break;
                            case "discountpricespercent":

                                for (j = 0; j < data[k].length; j++) {
                                    currentInput = $("#price-table input[name='mainForm[" + k + "][" + data[k][j]["unixDate"] + "][" + data[k][j]["ptrid"] + "]']");
                                    currentInput.val(data[k][j]["value"].toString() != "" ? data[k][j]["value"] : "");
                                    __toDateArray(data[k][j]["unixDate"]);
                                }

                                break;

                            case "stopSales":

                                for (j = 0; j < data[k].length; j++) {

                                    checked = data[k][j]["value"] == 1 ? true : false;
                                    $("#price-table input[name='mainForm[" + k + "][" + data[k][j]["rate_id"] + "][" + data[k][j]["unixDate"] + "]']").prop("checked", checked);
                                    __toDateArray(data[k][j]["unixDate"]);

                                }

                                break;

                            case "lifePeriod":

                                for (j = 0; j < data[k].length; j++) {

                                    if (data[k][j]["value"]) {

                                        data[k][j]["value"] = __formateLifePeriod($.map(data[k][j]["value"], function (val) {
                                            //to Int
                                            return parseInt(val);
                                        }));
                                    } else {
                                        data[k][j]["value"] = "";
                                    }

                                    $("#price-table input[name='mainForm[" + k + "][" + data[k][j]["unixDate"] + "][" + data[k][j]["rid"] + "]']").val(data[k][j]["value"]);
                                    __toDateArray(data[k][j]["unixDate"]);
                                }

                                break;
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

                    case "fix":

                        $(".__modal-body").html(Utilites.__fixModalBody(parameters.inputPartName));
                        break;

                    case "fixWithOneHiddenInput":

                        $(".__modal-body").html(Utilites.__fixWithOneHiddenInputModalBody(parameters.inputPartName, parameters.hvalue, parameters.feedbackLabel));
                        break;

                    case "toggleWithOneHiddenInput":

                        $(".__modal-body").html(Utilites.__toggleWithOneHiddenInputModalBody(parameters.inputPartName, parameters.hvalue, parameters.feedbackLabel));
                        break;

                    case "toggle":

                        $(".__modal-body").html(Utilites.__toggleModalBody(parameters.inputPartName));
                        break

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
                        "<input type=\"hidden\" name=\"mainForm[massEdit]" + inputPartName + "[0]\" value=\"" + hvalue + "\" >" +
                        "<input class=\"form-control\" type=\"text\" name=\"mainForm[massEdit]" + inputPartName + "[1]\" value=\"\" >";


                if (feedbackLabel) {
                    html += "<div class=\"form-group has-feedback has-feedback-left\">" + inputs +
                            "<div class=\"form-control-feedback\">" + feedbackLabel + "</div></div>";
                } else {
                    html += inputs;
                }

                html += "</div>";

                return html;

            },

            __toggleWithOneHiddenInputModalBody: function (inputPartName, hvalue) {

                return "<div class=\"radio\">" +
                        "<input type=\"hidden\" name=\"mainForm[massEdit]" + inputPartName + "[0]\" value=\"" + hvalue + "\">" +
                        "<label>Включить</label>" +
                        "<input type=\"radio\" name=\"mainForm[massEdit]" + inputPartName + "[1]\" value=\"1\" >" +
                        "</div>" +
                        "<div class=\"radio\">" +
                        "<label>Выключить</label>" +
                        "<input type=\"radio\" name=\"mainForm[massEdit]" + inputPartName + "[1]\" value=\"0\" >" +
                        "</div>";

            },

            /**
             * "Тело" модального окна с установкой фиксированного значения
             * @param {string} inputPartName
             * @returns {String}
             */
            __fixModalBody: function (inputPartName) {

                return   "<div class=\"form-group\">" +
                        "<label>Значение</label>" +
                        "<input class=\"form-control\" type=\"text\" name=\"mainForm[massEdit]" + inputPartName + "\" value=\"\" >" +
                        "</div>";

            },

            /**
             * "Тело" модального окна с переключателем
             * @param {String} inputPartName
             * @returns {String}
             */
            __toggleModalBody: function (inputPartName) {
                return "<div class=\"radio\">" +
                        "<label>Включить</label>" +
                        "<input type=\"radio\" name=\"mainForm[massEdit]" + inputPartName + "\" value=\"1\" >" +
                        "</div>" +
                        "<div class=\"radio\">" +
                        "<label>Выключить</label>" +
                        "<input type=\"radio\" name=\"mainForm[massEdit]" + inputPartName + "\" value=\"0\" >" +
                        "</div>";
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
            __colors: <?= \Bitrix\Main\Web\Json::encode($__arColors); ?>,

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

                $(".td__" + date + " input[name^='mainForm[quotes]']").each(function () {
                    var $this = $(this);
                    var rid = $this.parent().data("rid");

                    if ($(".td__" + date + " input[name^='mainForm[stopSales][" + rid + "]']:checked").length
                            || ($this.val() > 0 && Number($("#quotes_sold__" + date + "__" + rid).text()) === Number($this.val()))) {

                        Utilites.__paint($(".sub-td__" + date + "__" + rid), Utilites.__colors.red);

                    } else if ($this.val() > 0) {

                        Utilites.__paint($(".sub-td__" + date + "__" + rid), Utilites.__colors.green);
                    } else {

                        Utilites.__paint($(".sub-td__" + date + "__" + rid), Utilites.__colors.yellow);
                    }

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

            },

            initDatepicker: function ($context, startDate, single) {
                var options = {
                    applyClass: 'bg-slate-600',
                    cancelClass: 'btn-default',
                    singleDatePicker: !!single || false,
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
                };

                if (startDate) {
                    options.startDate = startDate;
                }


                $context.daterangepicker(options);
            },
            initDatepickerFiltr: function ($context, startDate, single) {
                var options = {
                    applyClass: 'bg-slate-600',
                    cancelClass: 'btn-default',
                    singleDatePicker: !!single || false,
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
                };
                <?if ($arResult['dateArray']['dateStartFormat']):?>   
                    options.startDate = moment.unix('<?=$arResult['dateArray']['dateStartFormat']?>');
                <?endif;?>
                <?if ($arResult['dateArray']['dateEndFormat']):?>   
                    options.endDate = moment.unix('<?=$arResult['dateArray']['dateEndFormat']?>');
                <?endif;?>


                $context.daterangepicker(options);
            }

        };

        // painting cells after table print
<? foreach ($arResult['dateArray']['daysArray'] as $arDay) : ?>
            Utilites.paintColumn("<?= $arDay['unixDate'] ?>");
<? endforeach ?>

        $(document).on('focusout', '#price-table input[type="text"]', function () {
            Utilites.postAjax(Utilites.makePostDataByInput($(this)));
        });

        $(document).on('click', '#price-table input[type="checkbox"]', function () {
            Utilites.postAjax(Utilites.makePostDataByInput($(this)));
        });

        $(document).on("click", ".setModalBody", function () {
            var $this = $(this),
                    modalBody = $this.data("modal-body"),
                    title = $this.data("modal-title"),
                    parameters = {
                        inputPartName: $this.data("input-part-name"),
                        hvalue: $this.data("hvalue") || "",
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

        $(document).on('click', '.copyColumn', function () {

            $("#price-table").hide();
            $(".preloader-up-main").show();

            var form = $(this).closest('form');

            var dateInput = $('#column_for_copy').val();
            var partsDate = dateInput.split("/");
            var dateUnix = new Date(partsDate[2],partsDate[1] -1,partsDate[0]).getTime() / 1000;
            var tarif  = $('#selectCopyRate').val(); 
            
            if(tarif == 0){
                var elem = $('input[name*="[' + dateUnix + ']"]').not('input[name*="[quotes]"], input[name*="[stopSales]"], input[name*="[releasePeriod]"]');;
            }  else {
                var elem = $('tr:not(.rateblock-row) input[name*="[' + dateUnix + ']"], tr[data-ratesign="'+tarif+'"] input[name*="[' + dateUnix + ']"]').not('input[name*="[quotes]"], input[name*="[stopSales]"], input[name*="[releasePeriod]"]');;
            }  

            form.find('.added_hidden_fields').remove();

            var countRequests = elem.length;

            elem.each(function( index, element ) {  

                var inputedVal;
                if($(this).is(":checkbox")){
                    if($(this).is(":checked")){
                        inputedVal = 1;
                    } else {
                        inputedVal = 0;
                    }                   
                } else {
                    inputedVal = $(this).val();
                }

                var link_button = $(this).closest('tr').find('.setModalBody').first();
                
                var type = link_button.data("modal-body"),
                    parameters = {
                            inputPartName: link_button.data("input-part-name"),
                            hvalue: link_button.data("hvalue") || "",
                    };

                switch (type) {

                    case "fix":
                        form.append('<input class="tmp_field" type="hidden" name="mainForm[massEdit]' + parameters.inputPartName + '" value="'+ inputedVal +'" >');
                        Utilites.postAjax(Utilites.makePostDataByForm(form.serializeArray()), '', function () {
                            if(index == countRequests-1){
                                $(".preloader-up-main").hide();
                                $("#price-table").show();
                            }
                        });
                        $('.tmp_field').remove();
                        break;
                    case "fixWithOneHiddenInput":
                        form.append('<input class="tmp_field" type="hidden" name="mainForm[massEdit]' + parameters.inputPartName + '[0]" value="' + parameters.hvalue + '" >' +
                        '<input class="tmp_field" type="hidden" name="mainForm[massEdit]' + parameters.inputPartName + '[1]" value="'+ inputedVal +'" >');
                        Utilites.postAjax(Utilites.makePostDataByForm(form.serializeArray()), '', function () {
                            if(index == countRequests-1){
                                $(".preloader-up-main").hide();
                                $("#price-table").show();
                            }
                        });
                        $('.tmp_field').remove();
                        break;
                    case "toggleWithOneHiddenInput":
                        form.append('<input class="tmp_field" type="hidden" name="mainForm[massEdit]' + parameters.inputPartName + '[0]" value="' + parameters.hvalue + '">' +
                        '<input class="tmp_field" type="hidden" name="mainForm[massEdit]' + parameters.inputPartName + '[1]" value="' + inputedVal + '">');
                        Utilites.postAjax(Utilites.makePostDataByForm(form.serializeArray()), '', function () {
                            if(index == countRequests-1){
                                $(".preloader-up-main").hide();
                                $("#price-table").show();
                            }
                        });
                        $('.tmp_field').remove();
                        break;
                    case "toggle":
                        form.append('<input class="tmp_field" type="hidden" name="mainForm[massEdit]' + parameters.inputPartName + '" value="'+ inputedVal +'" >');
                        Utilites.postAjax(Utilites.makePostDataByForm(form.serializeArray()), '', function () {
                            if(index == countRequests-1){
                                $(".preloader-up-main").hide();
                                $("#price-table").show();
                            }
                        });
                        $('.tmp_field').remove();
                        break
                    default:
                        break;
                }      
            });

            return false;
        });

        $("input[name=\"mainForm[massEdit][isRange]\"]").on("change", function () {

            var $this = $(this);

            if (this.value === "Y") {
                $(".range-dates-block").removeClass("hidden");
                $(".single-dates-block").addClass("hidden");
            } else {
                $(".range-dates-block").addClass("hidden");
                $(".single-dates-block").removeClass("hidden");
            }

        });

        $("#modal_form_vertical").on("hide.bs.modal", function () {
            // убираем динамические input
            $(".__modal-body").html("");
            // убираем заголовок
            $(".modal-title").html("");
        });

        moment.locale("<?= $arResult['dateArray']['dateRangeSettings']['locale'] ?>");
        Utilites.initDatepicker($('.daterange-basic'));
        Utilites.initDatepickerFiltr($('.daterange-filtr'));
        Utilites.initDatepicker($('.daterange-single'), null, true);

        $(".add-single-date-block").on("click", function () {

            var $singleDatesBlock = $(this).closest(".single-dates-block");
            var $formGroup = $singleDatesBlock.find(".form-group").last();
            var $formGroupClone = $formGroup.clone();
            var $daterangeSingle = $formGroupClone.find(".daterange-single");
            var span = null;
            $daterangeSingle.attr("name", "mainForm[massEdit][singleDates][" + ((new Date()).getTime()) + "]");
            if (!$formGroupClone.find(".remove-daterange-single").length) {
                span = document.createElement("span");
                span.className = "remove-daterange-single";
                span.innerHTML = "&times;";
                $daterangeSingle.after(span);
            }

            Utilites.initDatepicker($daterangeSingle, $daterangeSingle.val(), true);

            $formGroup.after($formGroupClone);

        });


        var table = $('#prices-table');
        var unvisibleThead = $('#unvisible-thead');
        var mainThead = $('#main-thead');
        var tablePosition = table.offset().top;
        var mainTh = mainThead.find('th');
        var unvisibleTh = unvisibleThead.find('th');
        var priceTableArea = $('#prices-table-area');
        var firstUnvisibleTh = $('#first-unvisible-th');
        unvisibleTh.each(function (index) {
            $(this).css({width: $(mainTh[index]).outerWidth()});
        });
        $(document).on('scroll', function () {

            var scrollPosition = $(this).scrollTop() + 33;

            var top = scrollPosition - tablePosition;

            if (top > 0) {

                mainThead.hide();
                unvisibleThead.show();
                firstUnvisibleTh.addClass('first-th1');
                unvisibleThead.css({top: top});
            } else {

                mainThead.show();
                unvisibleThead.hide();
                firstUnvisibleTh.removeClass('first-th1');
            }

            if (scrollPosition > 100) {
                if ((priceTableArea[0].scrollWidth - priceTableArea.scrollLeft()) > priceTableArea.width()) {
                    // $('.scroll-right').show();
                }

                if (priceTableArea.scrollLeft() > 300) {
                    // $('.scroll-left').show();
                }

            } else {
                // $('.scroll-left').hide();
                // $('.scroll-right').hide();
            }
        });


        var mainTd = $('.main-td');
        var unvisibleTd = $('.unvisible-td');
        var firstMainTdWidth = $(mainTd[0]).outerWidth();
        var firstUnvisibleTh = $('#first-unvisible-th');
        unvisibleTd.each(function (index) {
            $(this).css({width: $(mainTd[index]).outerWidth()});
            $(this).css({height: $(mainTd[index]).outerHeight()});
            $(this).css({position: 'absolute'});
//                $(this).css({top: $(this).offset().top - 3});
            $(this).css({'background-color': '#fff'});
        });
        var tdPosition = $(mainTd[0]).outerWidth();
        var tdMain = document.querySelectorAll('.main-td');
        var rtTitle = document.querySelectorAll('.rate-title');

        priceTableArea.on('scroll', function () {

            let scrollPosition = $(this).scrollLeft();
            document.querySelector('.first-th, .first-th1').style.transform = `translateX(${scrollPosition - 1}px)`;
            //document.querySelector('.first-th1').style.transform = `translateX(${scrollPosition - 1}px)`;
            [].slice.call(tdMain).forEach(item => item.style.transform = `translateX(${scrollPosition - 1}px)`);
            [].slice.call(rtTitle).forEach(item => item.style.transform = `translateX(${scrollPosition - 1}px)`);
        });


        $('.scroll-right').show();


        priceTableArea.on('scroll', function () {

            let scrollPosition = $(this).scrollLeft();

           // let left = scrollPosition - tdPosition;
            if (scrollPosition > 0) {/*
             /*firstUnvisibleTh.css({width: "auto"});
             unvisibleTd.each(function (index) {
             $(this).css({left: scrollPosition});
             $(this).show();
             $(mainTd[index]).hide();
             });*/

                /*$('.rate-title').each(function () {
                 $(this).css({
                 position: "absolute",
                 top: "12px",
                 left: scrollPosition + 150
                 });
                 });*/
                $('.scroll-left').show();
            } else {
                /* firstUnvisibleTh.css({width: firstMainTdWidth});
                 unvisibleTd.each(function (index) {
                 $(this).hide();
                 $(mainTd[index]).show();
                 });
                 
                 $('.rate-title').each(function () {
                 $(this).css({
                 position: "unset"
                 });
                 });*/
                $('.scroll-left').hide();
            }

            if (priceTableArea[0].scrollWidth - scrollPosition <= priceTableArea.width()) {
                $('.scroll-right').hide();
            } else {
                $('.scroll-right').show();
            }
        });

        $(document).on('click', '.scroll-left, .scroll-right', function () {

            var $this = $(this);

            var pos = null;
            $(document).trigger("scroll");
            if ($this.hasClass('scroll-left')) {
                priceTableArea.animate({scrollLeft: priceTableArea.scrollLeft() - 500}, 'fast');

            } else if ($this.hasClass('scroll-right')) {
                priceTableArea.animate({scrollLeft: priceTableArea.scrollLeft() + 500}, 'fast');
            }
        });

        $(document).on('click', '#clear-cache', function () {

            BX.showWait();
            $.get('<?= $templateFolder ?>/ajax.php', {sessid: BX.bitrix_sessid(), action: 'clear-cache'}, function () {

                alert('Цены сохранены.');

            }).fail(function () {
                alert('Ошибка сервера. Авторизуйтесь в кабинете еще раз и попробуйте актуализировать цены снова.');
            }).always(function () {
                BX.closeWait();
            });

        });

        Utilites.keyPressComboSwitchMonth();

    })(jQuery, document, BX, moment);


</script>