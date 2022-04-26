<?if (empty($arResult["SERVICES"])) return;?>
<?$is_mobile = check_smartphone();
if ($is_mobile){?>
<script>
(function ($) {
$(document).ready(function (){
	$(".rooms_name_popup").magnificPopup({
        type: "inline",
        midClick: true
    });
});
})(jQuery, document);
</script>
<?}?>
<div class="main-parent">
    <div >
        <!-- Блок инфо и фильтров -->
        <div class="boxItem" style="border-bottom: 0">
            <div class="rowItem">
                <div class="rooms_info colItem">
                    <div class="accessible"><?= GetMessage('SEAT_AV_TABLE_LEGEND_AVAIL');?></div>
                </div>
                <?if ($arParams['EMTY_RESULT']=="Y"):?>
                <div class="rooms_info colItem">
                    <div class="no_places"><?= GetMessage('SEAT_AV_TABLE_LEGEND_NOT_AVAIL_EMPTY');?></div>
                </div>
                <?else:?>
                <div class="rooms_info colItem">
                    <div class="no_places"><?= GetMessage('SEAT_AV_TABLE_LEGEND_NOT_AVAIL_EMPTY');?></div><?/*<div class="request"><?= GetMessage('SEAT_AV_TABLE_LEGEND_NOT_AVAIL');?></div>*/?>
                </div>
                <?endif;?>
                <?if (count($arResult['NOARRIVAL']>0)):?>
                   <div class="rooms_info colItem">
                        <div class="no_arrival"><?= GetMessage('SEAT_AV_TABLE_LEGEND_NOT_ARRIVAL');?></div>
                   </div> 
                <?endif;?>
            </div>
            <div class="rowItem form-inline">
                <div class="form-group">
                    <label class="sr-only"><?= GetMessage('SEAT_AV_TABLE_SHOW_FROM');?></label>
                    <?if ($arParams['EMTY_RESULT']=="Y"):?>
                    <input onchange="Travelsoft.vetliva.utils.get_seat_availability()" value="<?= $arResult["DISPLAY_DATE_FROM"]?>" placeholder="Показать с" type="text" class="form-control seat-availability__date-input" readonly> 
                    
                    <?else:?>
                    <input onchange="Travelsoft.vetliva.utils.get_seat_availability()" value="<?= $arResult["DISPLAY_DATE_FROM"]?>" placeholder="Показать с" type="text" class="form-control seat-availability__date-input" readonly> 
                    <?/*<input value="<?= $arResult["DISPLAY_DATE_FROM"]?>" placeholder="Показать с" type="text" class="form-control seat-availability__date-input"> 
                    <button onclick="Travelsoft.vetliva.utils.get_seat_availability(this)" type="button" class="seat-availability__update-btn btn btn-default">Обновить</button>*/?>
                    <?endif;?>
                </div>
            </div>
        </div>
        <!-- Блок календаря  -->
        <div class="boxItem table_element">
            <?
            $first = true;
            foreach ($arResult["SERVICES"] as $service_id => $arr_service) {
                ?>
                <div class="rowItem">
                    <div class="block_rooms_name <? if ($first): ?>block_rooms_name__first<? endif ?>">
                        <div class="colItem" title="<?= $arr_service[0]["UF_NAME" . $arParams["POSTFIX_PROPERTY"]]?>">
                        <?if ($is_mobile):?>
                            <p><a class="rooms_name_popup" title="<?= $arr_service[0]["UF_NAME" . $arParams["POSTFIX_PROPERTY"]]?>" href="#rooms_name_<?=$service_id?>"><?= $arr_service[0]["UF_NAME" . $arParams["POSTFIX_PROPERTY"]] ?></a></p>
                            <div id="rooms_name_<?=$service_id?>" class="header-auth-form mfp-hide"><?= $arr_service[0]["UF_NAME" . $arParams["POSTFIX_PROPERTY"]]?></div>
                        <?else:?>
                            <p <?=$service_id?>><?= $arr_service[0]["UF_NAME" . $arParams["POSTFIX_PROPERTY"]] ?></p>
                        <?endif;?>
                        </div>
                    </div>
                    <?
                    foreach ($arResult["DATA"]["HEAD"] as $month => $arr_data):
                        ?>
                        <div class="block_rooms">
                            <?
                            if ($first) {
                                ?>
                                <div class="heading_rooms_date">
                                    <div class="colItem block_rooms_month"><span><?= $arr_data["month_name"] ?></span></div>
                                    <div class="rowItem">
                                        <? foreach ($arr_data["days"] as $timestamp => $day): ?>
                                            <div class="colItem block_rooms_day <?if ($timestamp==$arResult['CURRENT']):?>active_date<?endif;?>">
                                                <?= $day ?>
                                            </div>
                                        <? endforeach; ?>
                                    </div>
                                </div>
                                <div class="block_rooms_info">
                                    <? foreach ($arResult["DATA"]["ROWS"][$service_id][$month] as $timestamp => $quota): ?>
                                        <?if (isset($arResult['NOARRIVAL'][$service_id][$timestamp])):?>
                                        <div  class="colItem block_rooms__no_arrival"><?= $quota ?></div>
                                        <?else:?>
                                        <div <? if ($quota > 0): ?>onclick="window.location = '<?= $arParams["DATA"]["LINKS"][$timestamp] ?>'"<? endif ?> class="colItem <? if ($quota > 0): ?>block_rooms__room_active<? else: ?>block_rooms__room block_rooms__room_disable<? endif ?>"><?= $quota ?></div>
                                        <?endif;?>
                                    <? endforeach ?>
                                </div>
                            <? } else { ?>
                                <div class="block_rooms_info">
                                    <? foreach ($arResult["DATA"]["ROWS"][$service_id][$month] as $timestamp => $quota): ?>
                                        <?if (isset($arResult['NOARRIVAL'][$service_id][$timestamp]) /* && count($arResult['ALLPRICES'][$service_id][$timestamp])== count($arResult['NOARRIVAL'][$service_id][$timestamp])*/):?>
                                        <div  class="colItem block_rooms__no_arrival"><?= $quota ?></div>
                                        <?else:?>
                                        <div <? if ($quota > 0): ?>onclick="window.location = '<?= $arParams["DATA"]["LINKS"][$timestamp] ?>'"<? endif ?> class="colItem <? if ($quota > 0): ?>block_rooms__room_active<? else: ?>block_rooms__room block_rooms__room_disable<? endif ?>"><?= $quota ?></div>
                                        <?endif;?>
                                    <? endforeach ?>
                                </div>
                            <? } ?>
                        </div>


                    <? endforeach; ?>
                </div><?
                $first = false;
            }
            ?>
        </div>
    </div>
</div>
