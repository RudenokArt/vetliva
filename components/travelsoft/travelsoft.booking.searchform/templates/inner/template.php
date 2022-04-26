<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

if ($arResult["UF_FIELDS_PRICE_FOR"]) {
?>
<div class="check-rates-form"><div class="form-search clearfix">
    <form autocomplete="off" action="<?= $APPLICATION->GetCurPageParam("", array())?>"  method="get">
        <div class="form-group">
            <div class="select">
                <select class="form-control" name="booking[price_for]">
                    <?foreach ($arResult["UF_FIELDS_PRICE_FOR"] as $id => $title):?>
                    <option value="<?= $id?>"><?= $title?></option>
                    <?endforeach?>
                </select>
            </div>
        </div>
    </form>
</div>
<?}?>
