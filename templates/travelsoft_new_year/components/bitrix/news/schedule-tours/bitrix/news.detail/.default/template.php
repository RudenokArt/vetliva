<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);
/*$arMenu = array();
foreach ($arResult["DISPLAY_PROPERTIES"] as $menu){
    if(!empty($menu["DISPLAY_VALUE"])){
        $arMenu[$menu["ID"]] = array(
            "NAME" => $menu["NAME"],
            "ANCHOR" => "hotel_".mb_strtolower($menu["CODE"])
        );
    }
}*/
$htmlMapID = "route-map";
?>
<?
$arWaterMark = Array(
    array(
        "name" => "watermark",
        "position" => "bottomright", // Положение
        "type" => "image",
        "size" => "real",
        "file" => NO_PHOTO_PATH_WATERMARK, // Путь к картинке
        "fill" => "exact",
    )
);
?>

<section class="head-detail">
                        <div class="head-dt-cn">
                            <div class="row">
                                <div class="col-sm-7">
                                    <h1><?echo LANGUAGE_ID == "ru" ? $arResult["NAME"] : $arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?> </h1>
                                    <div class="start-address">
                                        <span class="star">
											<?if ($arResult["PROPERTIES"]["CAT_ID"]["VALUE"]=='1491'):?>
											<i class="glyphicon glyphicon-star"></i>
                                            <i class="glyphicon glyphicon-star"></i>
                                            <i class="glyphicon glyphicon-star"></i>
                                            <i class="glyphicon glyphicon-star"></i>
                                            <i class="glyphicon glyphicon-star"></i>
											<?elseif($arResult["PROPERTIES"]["CAT_ID"]["VALUE"]=='1492'):?>
											<i class="glyphicon glyphicon-star"></i>
                                            <i class="glyphicon glyphicon-star"></i>
                                            <i class="glyphicon glyphicon-star"></i>
                                            <i class="glyphicon glyphicon-star"></i>
											<?elseif($arResult["PROPERTIES"]["CAT_ID"]["VALUE"]=='1493'):?>
											<i class="glyphicon glyphicon-star"></i>
                                            <i class="glyphicon glyphicon-star"></i>
                                            <i class="glyphicon glyphicon-star"></i>
											<?elseif($arResult["PROPERTIES"]["CAT_ID"]["VALUE"]=='1494'):?>
											<i class="glyphicon glyphicon-star"></i>
                                            <i class="glyphicon glyphicon-star"></i>
											<?else:?>
												<?=$arResult["DISPLAY_PROPERTIES"]["CAT_ID"]["DISPLAY_VALUE"];?>
											<?endif;?>
                                        </span>
                                    </div>
                                </div>
<!--
                                <div class="col-sm-5 text-right">
                                    <p class="price-book">
                                        От <span>300</span> BYN / ночь
                                        <a href="" title="" class="awe-btn awe-btn-1 awe-btn-lager">Бронировать</a>
                                    </p>
                                </div>
-->
                            </div>
                        </div>
                    </section>
<?if (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"])==1):
   $an_file = CFile::ResizeImageGet($arResult["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width'=>1170, 'height'=>641), BX_RESIZE_IMAGE_EXACT, true);
   $pre_photo=$an_file["src"];
   ?>
<img src="<?=$pre_photo?>" alt="<?=$arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][0]?>" class="img-responsive">
<?elseif (count($arResult["PROPERTIES"]["PICTURES"]["VALUE"])>1):?>
					<section class="detail-slider">
                        <!-- Lager Image -->
                        <div class="slide-room-lg">
                            <div id="slide-room-lg">
<?foreach($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $item):
            $file_big=CFile::ResizeImageGet($item, Array('width'=>1170, 'height'=>640),BX_RESIZE_IMAGE_EXACT,true,$arWaterMark);
            $img_count++;
            ?>
                                <img src="<?=$file_big["src"];?>"  alt="<?=$arResult["PROPERTIES"]["PICTURES"]["DESCRIPTION"][$i]?>">
<? $i++; endforeach;?>
                            </div>
                        </div>
                        <!-- End Lager Image -->
                        <!-- Thumnail Image -->
                        <div class="slide-room-sm">
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2">
                                    <div id="slide-room-sm">
<?foreach($arResult["PROPERTIES"]["PICTURES"]["VALUE"] as $item):
                  $file_small=CFile::ResizeImageGet($item, Array('width'=>90, 'height'=>60),BX_RESIZE_IMAGE_EXACT,true);
                  ?>
                                        <img src="<?=$file_small["src"];?>" alt="">
<? $i++; endforeach;?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Thumnail Image -->
                    </section>
<?endif;?>
                    <!-- Hotel Content One -->
                    <section class="hotel-content check-rates detail-cn" id="hotel-content">
                        <div class="row">                        
                            <div class="col-lg-3 detail-sidebar">
                                <!-- Hight Light -->
                                <div class="hight-light">

                                    <!-- Vote Text -->
                                    <div class="row">
                                        <!-- Recommend -->

                                        <div class="scroll-heading col-xs-12 col-sm-12 col-md-6 col-lg-12">

										<!-- Custom link field -->
										<div class="customer-like">
											<span class="cs-like-label">
												<?=$arResult["DISPLAY_PROPERTIES"]["REGIONS".POSTFIX_PROPERTY]["DISPLAY_VALUE"]?>
                                                <?if(!empty($arResult["DISPLAY_PROPERTIES"]["TOWN".POSTFIX_PROPERTY]["DISPLAY_VALUE"]) && is_array($arResult["DISPLAY_PROPERTIES"]["TOWN".POSTFIX_PROPERTY]["DISPLAY_VALUE"])):?>

                                                    <ul id="city_menu">
                                                        <li style="display: block;"><b><?=GetMessage('CITIES')?>: </b></li>
                                                        <?foreach ($arResult["DISPLAY_PROPERTIES"]["TOWN".POSTFIX_PROPERTY]["DISPLAY_VALUE"] as $k=>$city):?>
                                                            <li style="display:inline-block;margin-top: 0;clear: none !important;"><?=$city?><?if(!empty($arResult["DISPLAY_PROPERTIES"]["TOWN".POSTFIX_PROPERTY]["DISPLAY_VALUE"][$k+1])):?><?echo ", "?><?endif?></li>
                                                        <?endforeach;?>
                                                    </ul>
                                                <?endif?>
											</span>
										</div>
										<!-- End Custom link field -->

                                            <?if(!empty($arResult["DISPLAY_PROPERTIES"]["DAYS".POSTFIX_PROPERTY]["DISPLAY_VALUE"])):?>
                                                <b><?=$arResult["DISPLAY_PROPERTIES"]["DAYS".POSTFIX_PROPERTY]["NAME"]?>: </b>
                                                <?=$arResult["DISPLAY_PROPERTIES"]["DAYS".POSTFIX_PROPERTY]["DISPLAY_VALUE"]?><br>
                                            <?endif;?>
                                            <?if(!empty($arResult["DISPLAY_PROPERTIES"]["TRANSPORT".POSTFIX_PROPERTY]["DISPLAY_VALUE"])):?>
                                                <b><?=$arResult["DISPLAY_PROPERTIES"]["TRANSPORT".POSTFIX_PROPERTY]["NAME"]?>: </b>
                                                <?=strip_tags($arResult["DISPLAY_PROPERTIES"]["TRANSPORT".POSTFIX_PROPERTY]["DISPLAY_VALUE"])?><br>
                                            <?endif;?>
                                            <?if(!empty($arResult["DISPLAY_PROPERTIES"]["DURATION".POSTFIX_PROPERTY]["DISPLAY_VALUE"])):?>
                                                <b><?=$arResult["DISPLAY_PROPERTIES"]["DURATION".POSTFIX_PROPERTY]["NAME"]?>: </b>
                                                <?=strip_tags($arResult["DISPLAY_PROPERTIES"]["DURATION".POSTFIX_PROPERTY]["DISPLAY_VALUE"])?><br>
                                            <?endif;?>
                                            <?if(!empty($arResult["DISPLAY_PROPERTIES"]["DURATION_TIME".POSTFIX_PROPERTY]["DISPLAY_VALUE"])):?>
                                                <b><?=$arResult["DISPLAY_PROPERTIES"]["DURATION_TIME".POSTFIX_PROPERTY]["NAME"]?>: </b>
                                                <?=$arResult["DISPLAY_PROPERTIES"]["DURATION_TIME".POSTFIX_PROPERTY]["DISPLAY_VALUE"]?>
                                            <?endif;?>
                                            <!-- End Quote -->
                                        </div>
                                    </div>
                                    <!-- End Vote Text -->

                                    

                                </div>
                                <!-- End Hight Light -->
                            </div>
                            <a name="block_<?=mb_strtolower($arResult["PROPERTIES"]["HD_DESC".POSTFIX_PROPERTY]["CODE"])?>"></a>
                            <!-- Description -->
                            <div class="col-lg-9 hl-customer-like">
							<h2><?echo LANGUAGE_ID == "ru" ? $arResult["NAME"] : $arResult["PROPERTIES"]["NAME".POSTFIX_PROPERTY]["VALUE"]?> </h2>

								<?if (!empty($arResult["PROPERTIES"]["HD_DESC".POSTFIX_PROPERTY]["VALUE"])):?>
									  <?=$arResult["DISPLAY_PROPERTIES"]["HD_DESC".POSTFIX_PROPERTY]["DISPLAY_VALUE"]?><br><br>
								<?endif?>
                                <?if(!empty($arResult["DISPLAY_PROPERTIES"]["THEME_TOURS".POSTFIX_PROPERTY]["DISPLAY_VALUE"])):?>
                                    <b><?=$arResult["DISPLAY_PROPERTIES"]["THEME_TOURS".POSTFIX_PROPERTY]["NAME"]?>: </b>
                                    <?=strip_tags($arResult["DISPLAY_PROPERTIES"]["THEME_TOURS".POSTFIX_PROPERTY]["DISPLAY_VALUE"])?><br>
                                <?endif;?>
                                <?if(!empty($arResult["DISPLAY_PROPERTIES"]["TOURTYPE".POSTFIX_PROPERTY]["DISPLAY_VALUE"])):?>
                                    <b><?=$arResult["DISPLAY_PROPERTIES"]["TOURTYPE".POSTFIX_PROPERTY]["NAME"]?>: </b>
                                    <?=strip_tags($arResult["DISPLAY_PROPERTIES"]["TOURTYPE".POSTFIX_PROPERTY]["DISPLAY_VALUE"])?><br>
                                <?endif;?>
                                <?if(!empty($arResult["DISPLAY_PROPERTIES"]["DEPARTURE_EXC_TEXT".POSTFIX_PROPERTY]["DISPLAY_VALUE"])):?>
                                    <b><?=$arResult["DISPLAY_PROPERTIES"]["DEPARTURE_EXC_TEXT".POSTFIX_PROPERTY]["NAME"]?>: </b>
                                    <?=strip_tags($arResult["DISPLAY_PROPERTIES"]["DEPARTURE_EXC_TEXT".POSTFIX_PROPERTY]["DISPLAY_VALUE"])?><br>
                                <?endif;?>
                                <?if(!empty($arResult["DISPLAY_PROPERTIES"]["ROUTE".POSTFIX_PROPERTY]["DISPLAY_VALUE"])):?>
                                    <b><?=$arResult["DISPLAY_PROPERTIES"]["ROUTE".POSTFIX_PROPERTY]["NAME"]?>: </b>
                                    <?=strip_tags($arResult["DISPLAY_PROPERTIES"]["ROUTE".POSTFIX_PROPERTY]["DISPLAY_VALUE"])?><br>
                                <?endif;?>

                            </div>
                            <!-- End Description -->
                        </div>

                    </section>
                    <!-- End Hotel Content One -->
                    <!-- Check Rates-->
                    <section class="check-rates detail-cn" id="check-rates">
                        <div class="row">
                            <div class="col-lg-3 detail-sidebar">
                                <div class="scroll-heading">
                                    <?=showItem("PRICES",$arResult["MENU_ITEM"]);?>
                                </div>
                            </div>
                            <div class="col-lg-9 check-rates-cn">

                                <!-- Form Check Hotel Availability -->
                                <div class="check-rates-form">
                                    <h3><a class="color" name="block_prices"><?=GetMessage('PRICES_TITLE')?></a></h3>
                                    <div class="form-search clearfix">
                                        <div class="form-field field-date">
                                            <input type="text" class="field-input calendar-input" placeholder="Заезд">
                                        </div>
                                        <div class="form-field field-date">
                                            <input type="text" class="field-input calendar-input" placeholder="Выселение">
                                        </div>
                                        <div class="form-field field-select">
                                            <div class="select">
                                                <span>Взрослых</span>
                                                <select>
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                </select>
                                            </div>
                                        </div>
										<div class="form-field field-select">
                                            <div class="select">
                                                <span>Детей</span>
                                                <select>
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-submit">
                                            <button type="submit" class="awe-btn awe-btn-4 arrow-right awe-btn-medium">Найти</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- Hotel Availability -->
                                <div class="hl-availability">
                                    <div class="table-responsive">
                                        <table class="table table-availability">
                                            <tr>
                                                <th>Номера</th>
                                                <th>Цена</th>
                                            </tr>
                                            <tr>
                                                <td class="avai-td-text">
                                                    <figure>
														<img src="<?= SITE_TEMPLATE_PATH?>/images/hotel/img-5.jpg" alt="">    
                                                    </figure>
                                                    <h3>2-местный</h3>
                                                    <p>2-местный 1-комнатный с одной двухспальной кроватью (double)
													<p>Спутниковые каналы, Душ, Фен, Телефон, Чайник, Холодильник</p>
													<p><b>Питание:</b> 4-х разовое</p>
                                                    <p><a href="popup/popup-room.php" class="a-popup-room">Условия отмены</a> | <a href="popup/popup-room.php" class="a-popup-room">Подробнее</a></p>
                                                </td>

                                                <td class="avai-td-book avai-td-price">
												    <div class="select">
                                                        <span data-placeholder="select room">1 комната</span>
                                                        <select name="room">
                                                            <option value="1">1 комната</option>
                                                            <option selected value="1">2 комнаты</option>
                                                            <option value="1">3 комнаты</option>
                                                            <option value="1">4 комнаты</option>
                                                        </select>
                                                    </div>
													<span class="price">345 <small>BYN</small></span>
                                                    <a href="" class="awe-btn awe-btn-1 awe-btn-small">Бронировать</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="avai-td-text">
                                                    <figure>
														<img src="<?= SITE_TEMPLATE_PATH?>/images/hotel/img-5.jpg" alt="">    
                                                    </figure>
                                                    <h3>2-местный</h3>
                                                    <p>2-местный 1-комнатный с одной двухспальной кроватью (double)</p>
                                                    <a href="popup/popup-room.php" class="a-popup-room">подробнее</a>
                                                </td>

                                                <td class="avai-td-book avai-td-price">
												    <div class="select">
                                                        <span data-placeholder="select room">1 комната</span>
                                                        <select name="room">
                                                            <option value="1">1 комната</option>
                                                            <option selected value="1">2 комнаты</option>
                                                            <option value="1">3 комнаты</option>
                                                            <option value="1">4 комнаты</option>
                                                        </select>
                                                    </div>
													<span class="price">345 <small>BYN</small></span>
                                                    <a href="" class="awe-btn awe-btn-1 awe-btn-small">Бронировать</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="avai-td-text">
                                                    <figure>
														<img src="<?= SITE_TEMPLATE_PATH?>/images/hotel/img-5.jpg" alt="">    
                                                    </figure>
                                                    <h3>2-местный</h3>
                                                    <p>2-местный 1-комнатный с одной двухспальной кроватью (double)</p>
                                                    <a href="popup/popup-room.php" class="a-popup-room">подробнее</a>
                                                </td>
                                                <td class="avai-td-book avai-td-price">
												    <div class="select">
                                                        <span data-placeholder="select room">1 room</span>
                                                        <select name="room">
                                                            <option value="1">1 комната</option>
                                                            <option selected value="1">2 комнаты</option>
                                                            <option value="1">3 комнаты</option>
                                                            <option value="1">4 комнаты</option>
                                                        </select>
                                                    </div>
													<span class="price">345 <small>BYN</small></span>
                                                    <a href="" class="awe-btn awe-btn-1 awe-btn-small">Бронировать</a>
                                                </td>
                                            </tr>
                                           <tr>
                                                <td class="avai-td-text">
                                                    <figure>
														<img src="<?= SITE_TEMPLATE_PATH?>/images/hotel/img-5.jpg" alt="">    
                                                    </figure>
                                                    <h3>2-местный</h3>
                                                    <p>2-местный 1-комнатный с одной двухспальной кроватью (double)</p>
                                                    <a href="popup/popup-room.php" class="a-popup-room">подробнее</a>
                                                </td>

                                                <td class="avai-td-book avai-td-price">
												    <div class="select">
                                                        <span data-placeholder="select room">1 комната</span>
                                                        <select name="room">
                                                            <option value="1">1 комната</option>
                                                            <option selected value="1">2 комнаты</option>
                                                            <option value="1">3 комнаты</option>
                                                            <option value="1">4 комнаты</option>
                                                        </select>
                                                    </div>
													<span class="price">345 <small>BYN</small></span>
                                                    <a href="" class="awe-btn awe-btn-1 awe-btn-small">Бронировать</a>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <!-- Hotel Availability -->
                                <?if (!empty($arResult["PROPERTIES"]["PRICE_INCLUDE".POSTFIX_PROPERTY]["VALUE"])):?>
                                    <div class="featured-service">
                                        <div class="policies-item">
                                            <h3><?=$arResult["PROPERTIES"]["PRICE_INCLUDE".POSTFIX_PROPERTY]["NAME"]?></h3>
                                            <?=$arResult["DISPLAY_PROPERTIES"]["PRICE_INCLUDE".POSTFIX_PROPERTY]["DISPLAY_VALUE"]?>
                                        </div>
                                    </div>
                                <?endif?>
                                <?if (!empty($arResult["PROPERTIES"]["PRICE_NO_INCLUDE".POSTFIX_PROPERTY]["VALUE"])):?>
                                    <div class="featured-service">
                                        <div class="policies-item">
                                            <h3><?=$arResult["PROPERTIES"]["PRICE_NO_INCLUDE".POSTFIX_PROPERTY]["NAME"]?></h3>
                                            <?=$arResult["DISPLAY_PROPERTIES"]["PRICE_NO_INCLUDE".POSTFIX_PROPERTY]["DISPLAY_VALUE"]?>
                                        </div>
                                    </div>
                                <?endif?>
                                <?if(!empty($arResult["DISPLAY_PROPERTIES"]["SERVICES".POSTFIX_PROPERTY]["VALUE"])):?>
                                    <div class="featured-service">
                                        <h3><?=$arResult["PROPERTIES"]["SERVICES".POSTFIX_PROPERTY]["NAME"]?></h3>
                                        <ul class="service-list">
                                            <?foreach($arResult["DISPLAY_PROPERTIES"]["SERVICES".POSTFIX_PROPERTY]["VALUE"] as $services):?>
                                                <li class="unselected">
                                                    <ul class="icon-list">
                                                        <li class="first">
                                                            <div class="icon-service<?if(strlen($arResult["SERVICES_ICON"][$services]["ICON"]) > 0):?> <?=$arResult["SERVICES_ICON"][$services]["ICON"]?><?endif?>">
                                                            </div>
                                                        </li>
                                                        <li class="last">
                                                            <figcaption><?=$arResult["SERVICES_ICON"][$services]["TITLE"]?></figcaption>
                                                        </li>
                                                    </ul>
                                                </li>
                                            <?endforeach;?>
                                        </ul>
                                    </div>
                                <?endif;?>
                                <?if (!empty($arResult["PROPERTIES"]["FOOD".POSTFIX_PROPERTY]["VALUE"])):?>
                                    <div class="featured-service">
                                        <div class="policies-item">
                                            <h3><?=$arResult["PROPERTIES"]["FOOD".POSTFIX_PROPERTY]["NAME"]?></h3>
                                            <?=strip_tags($arResult["DISPLAY_PROPERTIES"]["FOOD".POSTFIX_PROPERTY]["DISPLAY_VALUE"])?>
                                        </div>
                                    </div>
                                <?endif?>
                            </div>
                        </div>
                    </section>
                    <!-- End Check Rates -->
                    <!-- Hotel Features -->
                    <section class="hl-features detail-cn" id="block_ndays">
                        <div class="row">
                            <div class="col-lg-3 detail-sidebar">
                                <div class="scroll-heading">
                                    <?=showItem("NDAYS",$arResult["MENU_ITEM"]);?>
                                </div>
                            </div>
                            <div class="col-lg-9 hl-features-cn">
                                <?if (!empty($arResult["PROPERTIES"]["NDAYS".POSTFIX_PROPERTY]["VALUE"])):?>
                                    <a class="color" name="block_<?=mb_strtolower($arResult["PROPERTIES"]["NDAYS".POSTFIX_PROPERTY]["CODE"])?>"></a>
                                    <div class="featured-service">
                                        <div class="policies-item">
                                            <h3><?=$arResult["PROPERTIES"]["NDAYS".POSTFIX_PROPERTY]["NAME"]?></h3>
                                            <div class="panel-group no-margin" id="accordion">
                                                <?$p = 1;?>
                                                <?foreach ($arResult["DISPLAY_PROPERTIES"]["NDAYS".POSTFIX_PROPERTY]["DISPLAY_VALUE"] as $k=>$value):?>
                                                    <div class="panel">
                                                        <div class="panel-heading" id="heading<?=$p?>">
                                                            <h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$p?>">
                                                                <small><?=GetMessage('DAY')?> <?=$p?>:</small><?=$arResult["DISPLAY_PROPERTIES"]["NDAYS".POSTFIX_PROPERTY]["DESCRIPTION"][$k]?> <span class="icon fa fa-angle-down"></span>
                                                            </a></h4>
                                                        </div>
                                                        <div id="collapse<?=$p?>" class="panel-collapse collapse<?if($p == 1):?> in<?endif?>" aria-labelledby="heading<?=$p?>">
                                                            <div class="panel-body">
                                                                <?=$value?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <?$p++?>
                                                <?endforeach;?>
                                            </div>
                                        </div>
                                    </div>
                                <?endif?>
                                <?if (!empty($arResult["PROPERTIES"]["ADDITIONAL".POSTFIX_PROPERTY]["VALUE"])):?>
                                    <div class="policies-item">
                                        <h3><?=$arResult["PROPERTIES"]["ADDITIONAL".POSTFIX_PROPERTY]["NAME"]?></h3>
                                        <?=$arResult["DISPLAY_PROPERTIES"]["ADDITIONAL".POSTFIX_PROPERTY]["DISPLAY_VALUE"]?>
                                    </div>
                                <?endif?>
                            </div>
                        </div>
                    </section>
                    <!-- End Hotel Features -->
                    <?if (!empty($arResult["PROPERTIES"]["DOCUMENT".POSTFIX_PROPERTY]["VALUE"])):?>
                        <!-- Details-Policies -->
                        <section class="details-policies detail-cn" id="block_document">
                            <div class="row">
                                <div class="col-lg-3 detail-sidebar">
                                    <div class="scroll-heading">
                                        <?=showItem("DOCUMENT",$arResult["MENU_ITEM"]);?>
                                    </div>
                                </div>
                                <div class="col-lg-9 details-policies-cn">

                                    <a class="color" name="block_<?=mb_strtolower($arResult["PROPERTIES"]["DOCUMENT".POSTFIX_PROPERTY]["CODE"])?>"></a>
                                    <div class="policies-item">
                                        <h3><?=$arResult["PROPERTIES"]["DOCUMENT".POSTFIX_PROPERTY]["NAME"]?></h3>
                                        <?=$arResult["DISPLAY_PROPERTIES"]["DOCUMENT".POSTFIX_PROPERTY]["DISPLAY_VALUE"]?>
                                    </div>

                            </div>
                        </section>
                        <!-- End Details Policies Item -->
                    <?endif?>
                    <?if(!empty($arResult["PROPERTIES"]["TOWN".POSTFIX_PROPERTY]["VALUE"])):?>
                   <section class="about-area detail-cn" id="about-area">
                        <div class="row">
                            <div class="col-lg-3 detail-sidebar">
                                <div class="scroll-heading">
                                    <?=showItem("MAP",$arResult["MENU_ITEM"]);?>
                                </div>
                            </div>
                            <div class="col-lg-9 details-policies-cn">
                                <a class="color" name="block_map"></a>
                                <div class="policies-item">
                                    <h3><?=GetMessage('MAP')?></h3>
                                    <?if (!empty($arResult['ROUTE_INFO'])):

                                        $this->addExternalJs("https://maps.googleapis.com/maps/api/js?key=AIzaSyAV5vry8G8fZEURwW2XQUx-X9TzVA-ih0I");
                                        $this->addExternalJs($templateFolder . "/jquery-custom-google-map-lib.js");?>

                                        <div style="width: 100%; height: 400px" id="<?= $htmlMapID?>"></div>
                                        <script>
                                            (function (gm) {
                                                // init map and draw route
                                                gm.createGoogleMap("<?= $htmlMapID?>", {center: gm.LatLng(0,0), zoom: 5})
                                                    .drawRoute(<?= \Bitrix\Main\Web\Json::encode($arResult['ROUTE_INFO'])?>);
                                            })(window.GoogleMapFunctionsContainer)
                                        </script>
                                    <? endif; ?>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?endif?>
                    <?if(!empty($arResult["PROPERTIES"]["YOUTUBE".POSTFIX_PROPERTY]["VALUE"]) || !empty($arResult["PROPERTIES"]["VIMEO".POSTFIX_PROPERTY]["VALUE"])):?>
                    <section class="details-policies detail-cn" id="block_youtube">
                        <div class="row">
                            <div class="col-lg-3 detail-sidebar">
                                <div class="scroll-heading">
                                    <?=showItem("YOUTUBE",$arResult["MENU_ITEM"]);?>
                                </div>
                            </div>
                            <div class="col-lg-9 details-policies-cn">
                                <!-- Details Policies Item -->
                                <?if(!empty($arResult["PROPERTIES"]["YOUTUBE".POSTFIX_PROPERTY]["VALUE"]) || !empty($arResult["PROPERTIES"]["VIMEO".POSTFIX_PROPERTY]["VALUE"])):?>
                                    <a name="block_<?=mb_strtolower($arResult["PROPERTIES"]["YOUTUBE".POSTFIX_PROPERTY]["CODE"])?>"></a>
                                    <div class="policies-item">
                                        <h3><?=GetMessage('VIDEO')?></h3>
                                        <?if(!empty($arResult["PROPERTIES"]["YOUTUBE".POSTFIX_PROPERTY]["VALUE"])):?>
                                            <div class="video-block">
                                                <iframe width="812" height="350" style="border: none;" src="https://www.youtube.com/embed/<?=$arResult["PROPERTIES"]["YOUTUBE".POSTFIX_PROPERTY]["VALUE"]?>" allowfullscreen=""></iframe>
                                            </div>
                                        <?endif?>
                                        <?if(!empty($arResult["PROPERTIES"]["VIMEO".POSTFIX_PROPERTY]["VALUE"])):?>
                                            <div class="video-block">
                                                <iframe width="812" height="350" style="border: none;" src="https://player.vimeo.com/video/<?=$arResult["PROPERTIES"]["YOUTUBE".POSTFIX_PROPERTY]["VALUE"]?>" allowfullscreen="" frameborder="0" webkitallowfullscreen mozallowfullscreen></iframe>
                                            </div>
                                        <?endif?>
                                    </div>
                                <?endif?>
                                <!-- End Details Policies Item -->
                            </div>
                    </section>
                    <?endif?>
                    <?if(!empty($arResult["PROPERTIES"]["SIGHTS".POSTFIX_PROPERTY]["VALUE"])):?>
                    <section class="details-policies detail-cn" id="block_sights">
                        <div class="row">
                            <div class="col-lg-3 detail-sidebar">
                                <div class="scroll-heading">
                                    <?=showItem("SIGHTS",$arResult["MENU_ITEM"]);?>
                                </div>
                            </div>
                            <a name="block_<?=mb_strtolower($arResult["PROPERTIES"]["SIGHTS".POSTFIX_PROPERTY]["CODE"])?>"></a>
                            <div class="col-lg-9 details-policies-cn">
                                <!-- Details Policies Item -->
                                <div class="policies-item">
                                    <h3><?=$arResult["PROPERTIES"]["SIGHTS".POSTFIX_PROPERTY]["NAME"]?></h3>
                                    <?$GLOBALS['arrFilterSights']["ID"] = $arResult["PROPERTIES"]["SIGHTS".POSTFIX_PROPERTY]["VALUE"];?>
                                    <?$APPLICATION->IncludeComponent(
                                        "bitrix:news.list",
                                        "sights",
                                        Array(
                                            "IBLOCK_TYPE" => "Dictionaries",
                                            "IBLOCK_ID" => $arResult["PROPERTIES"]["SIGHTS".POSTFIX_PROPERTY]["LINK_IBLOCK_ID"],
                                            "NEWS_COUNT" => 50,
                                            "SORT_BY1" => "ACTIVE_FROM",
                                            "SORT_ORDER1" => "DESC",
                                            "SORT_BY2" => "SORT",
                                            "SORT_ORDER2" => "ASC",
                                            "FIELD_CODE" => Array(),
                                            "PROPERTY_CODE" => array(
                                                0 => "COUNTRY",
                                                1 => "REGION",
                                                2 => "TOWN",
                                                3 => "ADDRESS",
                                                4 => "TYPE",
                                                5 => "MAP",
                                                6 => "MAP_SCALE",
                                                7 => "DETAIL_TEXT",
                                                8 => "YOUTUBE",
                                                9 => "VIMEO",
                                                10 => "PREVIEW_TEXT",
                                                11 => "REGIONS",
                                                12 => "HD_DESC",
                                                13 => "PICTURES",
                                                14 => "",
                                            ),
                                            "DETAIL_URL" => "/tourism/what-to-see/#ELEMENT_CODE#/",
                                            "SECTION_URL" => "/tourism/what-to-see/",
                                            "IBLOCK_URL" => "/tourism/what-to-see/",
                                            "SET_TITLE" => "N",
                                            "SET_LAST_MODIFIED" => "N",
                                            "MESSAGE_404" => "N",
                                            "SET_STATUS_404" => "N",
                                            "SHOW_404" => "N",
                                            "FILE_404" => "",
                                            "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                            "CACHE_FILTER" => "N",
                                            "CACHE_GROUPS" => "Y",
                                            "CACHE_TIME" => "36000000",
                                            "CACHE_TYPE" => "A",
                                            "DISPLAY_TOP_PAGER" => "N",
                                            "DISPLAY_BOTTOM_PAGER" => "N",
                                            "PAGER_TITLE" => "",
                                            "PAGER_TEMPLATE" => "",
                                            "PAGER_SHOW_ALWAYS" => "N",
                                            "PAGER_DESC_NUMBERING" => "N",
                                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                            "PAGER_SHOW_ALL" => "N",
                                            "PAGER_BASE_LINK_ENABLE" => "N",
                                            "PAGER_PARAMS_NAME" => "",
                                            "DISPLAY_DATE" => "N",
                                            "DISPLAY_NAME" => "Y",
                                            "DISPLAY_PICTURE" => "Y",
                                            "DISPLAY_PREVIEW_TEXT" => "Y",
                                            "PREVIEW_TRUNCATE_LEN" => "",
                                            "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                            "USE_PERMISSIONS" => "N",
                                            "FILTER_NAME" => "arrFilterSights",
                                            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                            "CHECK_DATES" => "Y",
                                        ),
                                        false
                                    );?>

                                <!-- End Details Policies Item -->
                            </div>
                        </div>
                    </section>
                    <?endif?>
                    <?if(!empty($arResult["PROPERTIES"]["HOTEL".POSTFIX_PROPERTY]["VALUE"])):?>
                        <section class="details-policies detail-cn" id="block_hotel">
                            <div class="row">
                                <div class="col-lg-3 detail-sidebar">
                                    <div class="scroll-heading">
                                        <?=showItem("HOTEL",$arResult["MENU_ITEM"]);?>
                                    </div>
                                </div>
                                <a name="block_<?=mb_strtolower($arResult["PROPERTIES"]["HOTEL".POSTFIX_PROPERTY]["CODE"])?>"></a>
                                <div class="col-lg-9 details-policies-cn">
                                    <!-- Details Policies Item -->
                                    <div class="policies-item">
                                        <h3><?=$arResult["PROPERTIES"]["HOTEL".POSTFIX_PROPERTY]["NAME"]?></h3>
                                        <?$GLOBALS['arrFilterHotel']["ID"] = $arResult["PROPERTIES"]["HOTEL".POSTFIX_PROPERTY]["VALUE"];?>
                                        <?$APPLICATION->IncludeComponent(
                                            "bitrix:news.list",
                                            "hotel",
                                            Array(
                                                "IBLOCK_TYPE" => "Dictionaries",
                                                "IBLOCK_ID" => $arResult["PROPERTIES"]["HOTEL".POSTFIX_PROPERTY]["LINK_IBLOCK_ID"],
                                                "NEWS_COUNT" => 50,
                                                "SORT_BY1" => "ACTIVE_FROM",
                                                "SORT_ORDER1" => "DESC",
                                                "SORT_BY2" => "SORT",
                                                "SORT_ORDER2" => "ASC",
                                                "FIELD_CODE" => Array(),
                                                "PROPERTY_CODE" => array(
                                                    0 => "COUNTRY",
                                                    1 => "REGIONS",
                                                    2 => "TOWN",
                                                    3 => "ADDRESS",
                                                    4 => "MAP",
                                                    5 => "YOUTUBE",
                                                    6 => "VIMEO",
                                                    7 => "SERVICES",
                                                    8 => "HD_DESC",
                                                    9 => "SEARCH",
                                                    10 => "TYPE",
                                                    11 => "MAP_SCALE",
                                                    12 => "PICTURES",
                                                    13 => "",
                                                ),
                                                "DETAIL_URL" => "/tourism/where-to-stay/#ELEMENT_CODE#/",
                                                "SECTION_URL" => "/tourism/where-to-stay/",
                                                "IBLOCK_URL" => "/tourism/where-to-stay/",
                                                "SET_TITLE" => "N",
                                                "SET_LAST_MODIFIED" => "N",
                                                "MESSAGE_404" => "N",
                                                "SET_STATUS_404" => "N",
                                                "SHOW_404" => "N",
                                                "FILE_404" => "",
                                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                                "CACHE_FILTER" => "N",
                                                "CACHE_GROUPS" => "Y",
                                                "CACHE_TIME" => "36000000",
                                                "CACHE_TYPE" => "A",
                                                "DISPLAY_TOP_PAGER" => "N",
                                                "DISPLAY_BOTTOM_PAGER" => "N",
                                                "PAGER_TITLE" => "",
                                                "PAGER_TEMPLATE" => "",
                                                "PAGER_SHOW_ALWAYS" => "N",
                                                "PAGER_DESC_NUMBERING" => "N",
                                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                                "PAGER_SHOW_ALL" => "N",
                                                "PAGER_BASE_LINK_ENABLE" => "N",
                                                "PAGER_PARAMS_NAME" => "",
                                                "DISPLAY_DATE" => "N",
                                                "DISPLAY_NAME" => "Y",
                                                "DISPLAY_PICTURE" => "Y",
                                                "DISPLAY_PREVIEW_TEXT" => "Y",
                                                "PREVIEW_TRUNCATE_LEN" => "",
                                                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                                "USE_PERMISSIONS" => "N",
                                                "FILTER_NAME" => "arrFilterHotel",
                                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                                "CHECK_DATES" => "Y",
                                            ),
                                            false
                                        );?>

                                        <!-- End Details Policies Item -->
                                    </div>
                                </div>
                        </section>
                    <?endif?>




