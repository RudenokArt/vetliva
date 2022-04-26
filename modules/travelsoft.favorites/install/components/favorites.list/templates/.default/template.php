<?php
if (empty($arResult["FAVORITES_LIST"])) {
    return;
}

$arr = array_chunk($arResult["FAVORITES_LIST"], 3);
?>
<div class="container-fluid favorites">
    <? foreach ($arr as $favs): ?>
        <div class="row">
            <? foreach ($favs as $fav): 
                
                $picture = CFile::ResizeImageGet($fav["DETAIL_INFO"]['PROPERTIES']["PICTURES"]['VALUE'][0], array('width'=>300, 'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                ?>
            <div class="col-md-4 col-sm-3 col-xs-12">
                <a href="<?= $fav["DETAIL_INFO"]["DETAIL_PAGE_URL"]?>" class="favorites__item favorite-item">
                    <div class="favorite-item__img">
                        <?if ($picture['src']):?>
                        <img src="<?= $picture['src']?>">
                        <?endif?>
                    </div>
                    <div class="favorite-item__title"><?= $fav["DETAIL_INFO"]["NAME"]?></div>
                    <div class="favorite-item__detail">Подробнее</div>
                </a>
            </div>
            <? endforeach; ?>
        </div>
    <? endforeach; ?>
    
</div>