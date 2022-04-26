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
$this->setFrameMode(false);?>

<a id="basket-link" href="<?= $arParams['BASKET_PAGE']?>">
    <?if ($arResult['BASKET_COUNT_ITEM'] > 0):?>
        <span id="basket-count-item"><?= $arResult['BASKET_COUNT_ITEM']?></span>
    <?endif?>
    <i class="fa fa-shopping-basket" aria-hidden="true"></i>
</a>