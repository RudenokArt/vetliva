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
?>
<div class="panel panel-flat">
    <? include_once __DIR__ . "/../parts/common_filter.php"; ?>

    <? if ($arResult["LOAD_RESULT"]): ?>
        <div class="white-area">
            <h3>Архив работы с квотами</h3>
            <div data-ajax-url="<?= $templateFolder ?>/ajax/quotas.php" id="archive-container" >

                <img src="<?= $templateFolder ?>/preloader.gif" width="100" height="100" id="replace-chart">

            </div>
        </div>
    <? endif ?>
</div>