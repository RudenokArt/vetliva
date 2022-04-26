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
$this->addExternalCss($templateFolder . "/comprison.css");
?>



    <?if (empty($arResult["ITEMS"])):?>
        <div class="col-md-9 col-md-pull-0 content-page-detail">
            <div class="alert-box alert-attention"><?= GetMessage("TEXT_NOT_FOUND", array("#LINK#" => $APPLICATION->GetCurDir())) ?></div>
        </div>
        <?return;
    endif;?>

    <div class="compresion_container" style="overflow: auto">
    <table class="comparison">

        <thead>
        <tr>
            <th class="comprasion_object__item_header" style="width: 20%; ">

            </th>
            <? $i=1; ?>
            <?foreach ($arResult["ITEMS"] as $key=>$arItem):?>
                <?
                $_request_string = $arItem["DETAIL_PAGE_URL"] . "?booking[id][]=" . $arItem["ID"];
                $arParams["__BOOKING_REQUEST"]["id"] = array($arItem["ID"]);
                ?>
                <th class="comprasion_object__item_header" id="headerToCompare<?=$arItem['ID']?>" >
                    <a href="<?=$arItem["DETAIL_PAGE_URL"] ?>" title="" class="ts-d-flex">
                        <?
                        if (!empty($arItem["PREVIEW_PICTURE"])):
                            $an_file = CFile::ResizeImageGet($arItem["PREVIEW_PICTURE"], array('width' => 410, 'height' => 250), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
                            $pre_photo = $an_file["src"];
                        elseif (!empty($arItem["PROPERTIES"]["PICTURES"]["VALUE"])):
                            $an_file = CFile::ResizeImageGet($arItem["PROPERTIES"]["PICTURES"]["VALUE"][0], array('width' => 410, 'height' => 250), BX_RESIZE_IMAGE_EXACT, true, array(), false, 80);
                            $pre_photo = $an_file["src"];
                        else:
                            $pre_photo = SITE_TEMPLATE_PATH . "/images/nophoto.jpg";
                        endif;
                        ?>


                        <span class="comprasion_object__item_header_img" style="background-image: url(<?=$pre_photo?>);"></span>

                        <div class="text">
                    <span class="js_comprasion_remove" data-id="<?=$arItem["ID"]?>">
                            <svg xmlns="http://www.w3.org/2000/svg"   width="24" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    </span>
                    <span class="comprasion_object__item_header_title">
                       <? echo LANGUAGE_ID == "ru" ? $arItem["NAME"] : $arItem["PROPERTIES"]["NAME" . POSTFIX_PROPERTY]["VALUE"] ?>
                        <sup class="hotel-star">
                            <? if ($arItem["PROPERTIES"]["CAT_ID"]["VALUE"] == '1491'): ?>
                                <i class="glyphicon glyphicon-star"></i>
                                <i class="glyphicon glyphicon-star"></i>
                                <i class="glyphicon glyphicon-star"></i>
                                <i class="glyphicon glyphicon-star"></i>
                                <i class="glyphicon glyphicon-star"></i>
                            <? elseif ($arItem["PROPERTIES"]["CAT_ID"]["VALUE"] == '1492'): ?>
                                <i class="glyphicon glyphicon-star"></i>
                                <i class="glyphicon glyphicon-star"></i>
                                <i class="glyphicon glyphicon-star"></i>
                                <i class="glyphicon glyphicon-star"></i>
                            <? elseif ($arItem["PROPERTIES"]["CAT_ID"]["VALUE"] == '1493'): ?>
                                <i class="glyphicon glyphicon-star"></i>
                                <i class="glyphicon glyphicon-star"></i>
                                <i class="glyphicon glyphicon-star"></i>
                            <? elseif ($arItem["PROPERTIES"]["CAT_ID"]["VALUE"] == '1494'): ?>
                                <i class="glyphicon glyphicon-star"></i>
                                <i class="glyphicon glyphicon-star"></i>
                            <? else: ?>
                                <?
                                $cat = null;
                                if ($arItem["PROPERTIES"]["CAT_ID"]["VALUE"]) {
                                    $arItem["PROPERTIES"]["CAT_ID"]["VALUE"] = (array) $arItem["PROPERTIES"]["CAT_ID"]["VALUE"];
                                    $db_res_cat = CIBlockElement::GetList(false, array("ACTIVE" => "Y", "IBLOCK_ID" => $arItem["PROPERTIES"]["TRANSPORT"]["LINK_IBLOCK_ID"], "ID" => $arItem["PROPERTIES"]["CAT_ID"]["VALUE"]), false, false, array("ID", "NAME", "PROPERTY_NAME" . POSTFIX_PROPERTY));
                                    $cat = null;
                                    while ($res = $db_res_cat->Fetch()) {
                                        $cat[] = $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] ? $res["PROPERTY_NAME" . POSTFIX_PROPERTY . "_VALUE"] : $res["NAME"];
                                    }
                                }
                                if ($cat):
                                    ?>
                                    <?= implode(", ", $cat) ?><br>
                                <? endif; ?>
                            <? endif; ?>
                        </span>




                    <span class="address">
                        <? if (!empty($arItem["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"])): $adress = ''; ?>
                            <i class="fa fa-map-marker"></i> <? $adress = substr2($arItem["PROPERTIES"]["ADDRESS" . POSTFIX_PROPERTY]["VALUE"], 200); ?>
                            <?
                            if (!empty($arItem["PROPERTIES"]["REGIONS"]["VALUE"])) {
                                $region = strip_tags($arItem["PROPERTIES"]["REGIONS"]["DISPLAY_VALUE"]);
                                if (LANGUAGE_ID != "ru") {
                                    $prop = getIBElementProperties($arItem["PROPERTIES"]["REGIONS"]["VALUE"]);
                                    $region = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                }
                            }
                            if (!empty($arItem["PROPERTIES"]["TOWN"]["VALUE"])) {
                                $town = strip_tags($arItem["PROPERTIES"]["TOWN"]["DISPLAY_VALUE"]);
                                if (LANGUAGE_ID != "ru") {
                                    $prop = getIBElementProperties($arItem["PROPERTIES"]["TOWN"]["VALUE"]);
                                    $town = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                }
                            }
                            if (!empty($arItem["PROPERTIES"]["REGION"]["VALUE"])) {
                                $obl = strip_tags($arItem["PROPERTIES"]["REGION"]["DISPLAY_VALUE"]);
                                if (LANGUAGE_ID != "ru") {
                                    $prop = getIBElementProperties($arItem["PROPERTIES"]["REGION"]["VALUE"]);
                                    $obl = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                }
                            }
                            if (!empty($arItem["PROPERTIES"]["COUNTRY"]["VALUE"])) {
                                $country = strip_tags($arItem["PROPERTIES"]["COUNTRY"]["DISPLAY_VALUE"]);
                                if (LANGUAGE_ID != "ru") {
                                    $prop = getIBElementProperties($arItem["PROPERTIES"]["COUNTRY"]["VALUE"]);
                                    $country = $prop["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                }
                            }
                            ?>
                            <? if ($town): ?><?
                                $adress .= ", " . $town;
                                unset($town);
                                ?><? if ($region): ?><? $adress .= ", "; ?><? endif; ?><? endif; ?>
                            <? if ($region): ?><? $adress .= $region; ?><? endif; ?>
                            <? if ($obl): ?><? $adress .= ", " . $obl;?><? endif; ?>
                            <? if ($country): ?><? $adress .= ", " . $country; ?><? endif; ?>
                            <? echo $adress; unset($obl);?>
                        <? endif ?>
                    </span>
                        </div>
                    </a>
                </th>
                <? $i++ ?>
            <?endforeach;?>

        </tr>
        </thead>
        <tbody>
            <tr class="comparison__options" >
                <td class="comparison__options_item" colspan="<?= count($arResult["ITEMS"])+1 ?>"><?=GetMessage('INFO')?></td>
            </tr>
            <?foreach ($arResult["PROPERTIES"] as $prop_code=>$prop_name):?>

                <tr>
                    <td class="comparison__options_item">
						<?if($prop_code == "TYPE"):?>
							<?if($arReult["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID && !empty(GetMessage('TYPE_PLACEMENTS'))):?><?=GetMessage('TYPE_PLACEMENTS')?><?elseif($arReult["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID && !empty(GetMessage('TYPE_SANATORIUM'))):?><?=GetMessage('TYPE_SANATORIUM')?><?else:?><?=$prop_name?><?endif;?>
						<?else:?>
							<?if(!empty(GetMessage($prop_code))):?><?=GetMessage($prop_code)?><?else:?><?=$prop_name?><?endif?>
						<?endif;?>
					</td>
                    <? $i=1; ?>
                    <?foreach ($arResult["ITEMS"] as $key=>$arItem):?>

                         <td class="comparison__options_item" id="bodyToCompare<?=$arItem['ID']?>" >
                             <?if($arItem["DISPLAY_PROPERTIES"][$prop_code]["VALUE"] == "Y"):?>
                                 <!-- Галопчка -->
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" class="__check" height="24" viewBox="0 0 24 24"><path d="M0 0h24v24H0z" fill="none"/><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                             <?elseif(empty($arItem["DISPLAY_PROPERTIES"][$prop_code]["VALUE"])):?>
                                 <!-- Крестик -->
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" class="__uncheck" height="24" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                             <?else:?>
                                 <? if ($prop_code == "SERVICES" || $prop_code == "SERVICES_PAID"): ?>
                                     <?$ar_value = [];?>
                                     <? foreach ($arItem["DISPLAY_PROPERTIES"][$prop_code]["VALUE"] as $v => $value): ?>
                                         <?$ar_value[] = $arResult["SERVICES"][$value];?>
                                     <? endforeach; ?>
                                     <?if(!empty($ar_value)):?>
                                         <?=implode(', ', $ar_value)?>
                                     <? endif; ?>
                                 <? elseif ($prop_code == "TYPE"): ?>
                                     <?$ar_value = [];?>
                                     <? foreach ($arItem["DISPLAY_PROPERTIES"][$prop_code]["VALUE"] as $v => $value): ?>
                                         <?$ar_value[] = $arResult["MED_PROF"][$value];?>
                                     <? endforeach; ?>
                                     <?if(!empty($ar_value)):?>
                                         <?=implode(', ', $ar_value)?>
                                     <? endif; ?>
                                 <? elseif ($prop_code == "MED_SERVICES"): ?>
                                     <?$ar_value = [];?>
                                     <? foreach ($arItem["DISPLAY_PROPERTIES"][$prop_code]["VALUE"] as $v => $value): ?>
                                         <?$ar_value[] = $arResult["MED_SERVICES"][$value];?>
                                     <? endforeach; ?>
                                     <?if(!empty($ar_value)):?>
                                         <?=implode(', ', $ar_value)?>
                                     <? endif; ?>
                                 <?elseif(is_array($arItem["DISPLAY_PROPERTIES"][$prop_code]["VALUE"]) && !isset($arItem["DISPLAY_PROPERTIES"][$prop_code]["VALUE"]["TYPE"])):?>
                                     <?=implode2($arItem["DISPLAY_PROPERTIES"][$prop_code]["DISPLAY_VALUE"])?>
                                 <?else:?>
                                     <?if($arItem["DISPLAY_PROPERTIES"][$prop_code]["PROPERTY_TYPE"] == "E"):?>
                                         <?$str = strip_tags($arItem["DISPLAY_PROPERTIES"][$prop_code]["DISPLAY_VALUE"]);?>
                                         <?if (LANGUAGE_ID != "ru") {
                                             $str_ = getIBElementProperties($arItem["PROPERTIES"][$prop_code]["VALUE"]);
                                             $str = $str_["NAME" . POSTFIX_PROPERTY]["VALUE"];
                                         }?>
                                         <?=$str?>
                                     <?elseif($arItem["DISPLAY_PROPERTIES"][$prop_code]["USER_TYPE"] == "HTML"):?>
                                         <?=$arItem["DISPLAY_PROPERTIES"][$prop_code]["DISPLAY_VALUE"]?>
                                     <?else:?>
                                         <?=$arItem["DISPLAY_PROPERTIES"][$prop_code]["VALUE"]?>
                                     <?endif?>
                                 <?endif?>
                             <?endif?>
                         </td>
                        <? $i++ ?>
                    <?endforeach;?>
                </tr>

            <?endforeach;?>
        </tbody>
    </table>
    </div>


<? if ($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID || $arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID): ?>
<?$type_object = $arParams["IBLOCK_ID"] == PLACEMENTS_IBLOCK_ID ? "placement" : ($arParams["IBLOCK_ID"] == SANATORIUM_IBLOCK_ID ? "sanatorium" : '');?>
    <script>

        const Table = document.querySelector('table.comparison');
        const countItem = parseInt(<?= count($arResult["ITEMS"])?>);
        const TableItemTh = Table.querySelectorAll('th');
        const TableItemTd = Table.querySelectorAll('td');
        if(countItem > 2){
            Table.style.width = `${countItem*350+200}px`;
            [].slice.call(TableItemTh).forEach(item=>{
                item.style.minWidth =  '350px';
            });
            [].slice.call(TableItemTd).forEach(item=>{
                item.style.minWidth =  '350px';
            })
        }
        else{
            [].slice.call(TableItemTh).forEach(item=>{
                item.style.width =  '40%';
            });
            [].slice.call(TableItemTd).forEach(item=>{
                item.style.width =  '40%';
            });
        }




        (function ($) {

            var type_object = '<?=$type_object?>';

            $('.js_comprasion_remove').on("click", function (e) {
                e.preventDefault();
                if(type_object) {
                    var toCompareVal = $(this).data("id");
                        //отправляем удаление из сравнения
                    $.ajax({
                        type: "post",
                        url: '<?=$templateFolder?>/ajax_compare.php',
                        dataType: 'json',
                        data: {id: toCompareVal, actionCompare: "delete", typeCompare: type_object},
                        success: function (data) {
                            location.reload();
                            /*if (data.result[type_object] == 0) {
                                location.reload();
                            } else {
                                $('#headerToCompare'+toCompareVal).remove();
                                $('#bodyToCompare'+toCompareVal).remove();
                            }*/
                        }
                    });

                }

            });

        })(jQuery);
    </script>
<?
endif?>