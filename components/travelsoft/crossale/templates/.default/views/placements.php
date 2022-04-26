<?php

$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/select2/select2.min.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/select2/select2.min.js");
$this->addExternalJs($templateFolder . '/views/js/placements.js');
?>

<div id="placements-crossale-container" class="row">
    <div id="search-preloader">

        <div id="search-page-loading">
            <div></div>
        </div>
    </div>
</div>


<template id="crossale-placements-template">
    <div class="crossale-block-title ts-margin-top"><h2><?= GetMessage('CROSSALE_PLACEMENTS_BLOCK_NAME') ?></h2></div>
    <div class="arrow-drop-down-placements"> <span><?=GetMessage('CROSSALE_ARROW_LABEL')?></span> </div>
    
    {{offers_container}}
</template>

<template id="crossale-placements-offers-template">
    <div class="sales-cn sales-cn-placements">
        <div class="row ts-p-relative">
            <div id="interesting-slide-placements" class="owl-carousel hello-world">
                {{offers}}
            </div>
        </div>
        <div class="link-see-all-placements">
            <a target="_blank" href="#"><?= GetMessage('CROSSALE_PLACEMENTS_SEE_ALL')?></a></div>
    </div>

</template>

<template id="crossale-placements-offer-template">
    <div class="sales-item">
        <a href="javascript:void(0)" class="sales-item-a">
            <div class="sales-item">
                <figure class="home-sales-img">
                    <img src="{{src}}" alt="">
                </figure>

                <div class="home-sales-text">
                    <div class="home-sales-name-places">
                        
                        <div class="descblock class-auto ts-text-size">
                            {{placement_name}}
                        </div>
                        <div class="home-sales-route">
                            {{service_name}}
                        </div>
                        <div class="home-sales-name color-cars">
                            {{rate_name}}
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
 
    window.__tsconfig_placements_crossale = {
        no_photo_src: "<?= SITE_TEMPLATE_PATH . "/images/nophoto-292x180.jpg" ?>",
        postfix_property: "<?= POSTFIX_PROPERTY ?>",
        sessid: "<?= bitrix_sessid() ?>",
        type: "<?= $arParams["TYPE"]?>",
        ajax_url: "<?= $templateFolder ?>/ajax.php"
    };
</script>