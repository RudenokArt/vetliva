<?php

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/select2/select2.min.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/select2/select2.min.js");
$this->addExternalJs($templateFolder . '/views/js/transfers.js');
?>

<div id="transfers-crossale-container" class="row">
    <div id="search-preloader">

        <div id="search-page-loading">
            <div></div>
        </div>
    </div>
</div>


<template id="crossale-transfers-template">
    <div class="crossale-block-title ts-margin-top"><h2><?= GetMessage('CROSSALE_TRANSFERS_BLOCK_NAME') ?></h2></div>
    <div class="arrow-drop-down"> <span><?=GetMessage('CROSSALE_ARROW_LABEL')?></span> </div>
    <div class="container-select">
        <form action="#" id="crossale-transfers-form">
            <?= bitrix_sessid_post() ?>
            <input type="hidden" name="type" value="<?= $arParams["TYPE"]?>">
            <label for="" class="ts-mr-1"><?= GetMessage('CROSSALE_TRANSFERS_FROM') ?>: </label>
            <select class="cars-select" name="crossale_transfers[from]">
                {{departure_points_options}}
            </select>
            &nbsp;
            <label for="" class="ts-mr-1"><input name="crossale_transfers[roundtrip]" value="Y" type="checkbox"> <?= GetMessage('CROSSALE_TRANSFERS_ROUNDTRIP') ?></label>

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
 
    window.__tsconfig_transfers_crossale = {
        no_photo_src: "<?= SITE_TEMPLATE_PATH . "/images/nophoto-292x180.jpg" ?>",
        postfix_property: "<?= POSTFIX_PROPERTY ?>",
        CROSSALE_TRANSFERS_SELECT_POINT_TITLE: "<?= GetMessage("CROSSALE_TRANSFERS_SELECT_POINT_TITLE") ?>",
        sessid: "<?= bitrix_sessid() ?>",
        type: "<?= $arParams["TYPE"]?>",
        ajax_url: "<?= $templateFolder ?>/ajax.php"
    };
</script>