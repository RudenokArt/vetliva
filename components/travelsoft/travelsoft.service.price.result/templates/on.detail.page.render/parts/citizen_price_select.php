<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

if ($arParams["FILTER_BY_PRICES_FOR_CITIZEN"] == "Y" && !isset($arParams["__BOOKING_REQUEST"]["specifying"])):
?>

<? 
$this->addExternalCss($templateFolder . "/bootstrap-select.min.css");
$this->addExternalJs($templateFolder . "/bootstrap-select.min.js"); ?>

<!-- Цены для граждан 2-->
<div class="ts-wrap">
    <div class="ts-row ts-justify-content__flex-end">
<div class="citizenPrices ">

    <div class="chose-block"><?=GetMessage('CHOOSE_PRICE')?> </div>
    <select  class="form-control to-validate selectpicker" style="float: none; width: 100%" name="citizen_price">
<? foreach ($arResult["CITIZEN_PRICES"]["ITEMS"] as $ID => $F_NAME) : ?>
            <option value="<?= $ID ?>" <?
            if ($ID == $arResult["CITIZEN_PRICES"]["CURRENT"]) {
                echo "selected";
            }
            ?>><?= GetMessage($F_NAME) ?></option>
        <? endforeach ?>
    </select>
</div>
    </div>
</div>
<?endif?>



