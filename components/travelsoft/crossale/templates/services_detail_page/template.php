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
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/select2/select2.min.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/select2/select2.min.js");
$this->addExternalJs($templateFolder . '/crossale.js');
?>

<div id="transfers-crossale-container" class="ts-wrap ts-m-0">
    <div id="search-preloader">

        <div id="search-page-loading">
            <div></div>
        </div>
    </div>
</div>


<template id="crossale-transfers-template">
    <div class="crossale-block-title ts-margin-top"><h2><?= GetMessage('CROSSALE_TRANSFERS_BLOCK_NAME') ?></h2></div>
    <div class="arrow-drop-down"> <span>показать \ скрыть</span> </div>
    <div class="container-select">
        <form action="#" id="crossale-transfers-form" class="ts-row ts-mb-2">
            <?= bitrix_sessid_post() ?>
            <div class="ts-col-auto ts-align-items__center">
            <label for="" class="ts-mr-1 ts-mb-0"  style="line-height: 11px;"><?= GetMessage('CROSSALE_TRANSFERS_FROM') ?>: </label>
            <select class="cars-select ts-m-0" name="crossale_transfers[from]">
                {{departure_points_options}}
            </select>
            &nbsp;</div>
            <div class="ts-col-auto ts-align-items__center">
            <label for="" class="ts-mr-1"><input name="crossale_transfers[roundtrip]" value="Y" type="checkbox"> <?= GetMessage('CROSSALE_TRANSFERS_ROUNDTRIP') ?></label>
            </div>
        </form>
    </div>
    {{offers_container}}
</template>

<template id="crossale-transfers-offers-template">
    <div class="sales-cn">
        <div class="row ts-p-relative">
            <div id="interesting-slide-transfers" class="owl-carousel ">
                {{offers}}
            </div>
        </div>
        <div class="link-see-all">
            <a target="_blank" href="#"><?= GetMessage('CROSSALE_TRANSFERS_SEE_ALL')?></a></div>
    </div>

</template>

<template id="crossale-transfers-offer-template">
    <div class="sales-item">
        <a href="javascript:void(0)" class="sales-item-a">
            <div class="sales-item">
                <figure class="home-sales-img">
                    <img src="{{src}}" alt="">
                </figure>

                <div class="home-sales-text">
                    <div class="home-sales-name-places">
                        <div class="descblock class-auto ts-text-size">
                            {{class_name}}
                        </div>
                        <div class="home-sales-route">
                            {{route}}
                        </div>
                        <div class="home-sales-name color-cars">
                            {{auto_name}}
                        </div>
                    </div>
                    <div class="add2basket-block text-right">
                        <div class="price-block">{{price_formatted}}</div>
                        <a href="javascript:void(0)" data-add2cart="{{add2cart}}" class="add-to-cart awe-btn awe-btn-1 awe-btn-small"><?= GetMessage('CROSSALE_BOOKING_BTN') ?></a>
                    </div>
                </div>
            </div>
        </a>
    </div>
</template>

<script>
 
    window.__tsconfig = {
        no_photo_src: "<?= SITE_TEMPLATE_PATH . "/images/nophoto-292x180.jpg" ?>",
        postfix_property: "<?= POSTFIX_PROPERTY ?>",
        CROSSALE_TRANSFERS_SELECT_POINT_TITLE: "<?= GetMessage("CROSSALE_TRANSFERS_SELECT_POINT_TITLE") ?>",
        sessid: "<?= bitrix_sessid() ?>",
        ajax_url: "<?= $templateFolder ?>/ajax.php"
    };
</script>