<?php
if (empty($arResult["FAVORITES_LIST"])) {
    return;
}
$arr  =[];
foreach ($arResult["FAVORITES_LIST"] as $item)
$arr[$item['DETAIL_INFO']['IBLOCK_ID']][] = $item;
?>
<div class="container-fluid favorites">
    <? foreach ($arr as $iblock_id => $favs): ?>
        <div class="row">
            <?$title  =''; // $res = CIBlock::GetByID( $iblock_id ); if($ar_res = $res->GetNext()) $title =$ar_res['NAME']; ?>
            <div class="travel-title float-left">
    			<h2><?=GetMessage('IBLOCK_NAME')[$iblock_id]?></h2>
    		</div>    
            <div class="owl-carousel wish-list" id="reviews-slide">
            <? foreach ($favs as $fav): 
                $picture = CFile::ResizeImageGet($fav["DETAIL_INFO"]['PROPERTIES']["PICTURES"]['VALUE'][0], array('width'=>344, 'height'=>210), BX_RESIZE_IMAGE_EXACT, true);
                if (!$picture['src'])  $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto.jpg";
                else  $pre_photo = $picture['src'];
                $fulladress  =[];
                if ($fav["DETAIL_INFO"]['PROPERTIES']['COUNTRY']['VALUE']!='') {
                    if (is_array($fav["DETAIL_INFO"]['PROPERTIES']['COUNTRY']['VALUE'])) $fulladress[] = Get_Name_Element($fav["DETAIL_INFO"]['PROPERTIES']['COUNTRY']['VALUE'][0]);
                    else $fulladress[] = Get_Name_Element($fav["DETAIL_INFO"]['PROPERTIES']['COUNTRY']['VALUE']);
                }
                if ($fav["DETAIL_INFO"]['PROPERTIES']['TOWN']['VALUE']!='') {
                    if (is_array($fav["DETAIL_INFO"]['PROPERTIES']['TOWN']['VALUE'])) $fulladress[] = Get_Name_Element($fav["DETAIL_INFO"]['PROPERTIES']['TOWN']['VALUE'][0]);
                    else $fulladress[] = Get_Name_Element($fav["DETAIL_INFO"]['PROPERTIES']['TOWN']['VALUE']);
                }
                if ($fav["DETAIL_INFO"]['PROPERTIES']['ADDRESS']['VALUE']!='' && LANGUAGE_ID == "ru" ) $fulladress[] = $fav["DETAIL_INFO"]['PROPERTIES']['ADDRESS']['VALUE'];
                if ($fav["DETAIL_INFO"]['PROPERTIES']['ADDRESS'.POSTFIX_PROPERTY]['VALUE']!='' && LANGUAGE_ID != "ru" ) $fulladress[] = $fav["DETAIL_INFO"]['PROPERTIES']['ADDRESS'.POSTFIX_PROPERTY]['VALUE'];
                ?>
            <div class="favorite-block">
                <span class="wishitem_remove" onclick="BX.Travelsoft.deleteFromFavorites('/local/components/travelsoft/favorites.add/templates/.default/ajax.php', '<?=$fav['UF_OBJECT']?>', '<?=$fav['UF_ID']?>', '<?=$fav['UF_STORE_ID']?>', '<?=\travelsoft\favorites\Utils::createHash($fav["UF_OBJECT"], $fav["UF_ID"], $fav["UF_STORE_ID"])?>', this, 'Y')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path><path d="M0 0h24v24H0z" fill="none"></path></svg>
                </span>
                <a href="<?= $fav["DETAIL_INFO"]["DETAIL_PAGE_URL"]?>" class="favorites__item favorite-item">
                    
                    <div class="destinations-item">
                        <figure class="destinations-img"> 
                            <img src="<?= $pre_photo ?>" alt=""> 
                        </figure>
                        <div class="destinations-text">
                            <div class="destinations-name">
                                <?= LANGUAGE_ID == "ru" ? $fav["DETAIL_INFO"]["NAME"] : $fav["DETAIL_INFO"]["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?>
                            </div>
                            <div class="home-destinations-places">
                                <?if (count($fulladress)>0):?>
                                    <div class="favorite-item__adress"><?=implode(', ', $fulladress)?></div>
                                <?endif;?>
                            </div>
                            <a href="<?= $fav["DETAIL_INFO"]["DETAIL_PAGE_URL"]?>" target="_blank" class="add-to-cart awe-btn awe-btn-1 awe-btn-small2"><?=GetMessage('MORE')?></a>
                        </div>
                    </div> 
                </a>
            </div>
            
            <? endforeach; ?>
            
            </div>
        </div>
    <? endforeach; ?>
    
</div>
<script>
    $('.owl-carousel.wish-list').owlCarousel({
        items: 3,
        loop:true,
        margin:10,
        navigation:true,
        navigationText: ['<span class="prev-next-room prev-room"></span>','<span class="prev-next-room next-room"></span>'],
        dots: false
    })
</script>