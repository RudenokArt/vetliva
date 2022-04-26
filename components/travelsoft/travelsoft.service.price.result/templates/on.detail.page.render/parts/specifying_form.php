<? 
//$this->addExternalCss($templateFolder . "/bootstrap-select.min.css");
//$this->addExternalJs($templateFolder . "/bootstrap-select.min.js"); ?>


<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
    if (in_array($arParams["TYPE"], ['placements', 'sanatorium'])) { 
    $services = (new \travelsoft\booking\datastores\ServicesDataStore(array("filter" => [0=>['UF_IBLOCK_ELEMENT_ID'=>$arParams["__BOOKING_REQUEST"]['id'][0]]])))->fetch(['ID']);
    $parentname =Get_Name_Element($arParams["__BOOKING_REQUEST"]['id'][0]);
    $showed_services = array_keys ($arResult['CALCULATION_ALL'][$arParams["__BOOKING_REQUEST"]['id'][0]]);
    $all_services = array_keys($services);
    $needshow_services = array_diff($all_services, $showed_services);
    
     if (count($needshow_services)>0) {
        $HTML_DATA = null;
        foreach ($needshow_services as $SID) {
            $arService = $services[$SID][0];
            $HTML_DATA[$SID]["MAIN_BLOCK"]["ID"] = $SID;
            if ($arService["UF_PICTURES"][0] > 0) {
                $HTML_DATA[$SID]["MAIN_BLOCK"]["IMAGE_ID"] = $arService["UF_PICTURES"][0];
            }
            $HTML_DATA[$SID]["MAIN_BLOCK"]['PICTURES'] = $arService["UF_PICTURES"];
            $HTML_DATA[$SID]["MAIN_BLOCK"]["TITLE"] = $arService["UF_NAME" . POSTFIX_PROPERTY];
            $HTML_DATA[$SID]["MAIN_BLOCK"]["DESCRIPTION"] = substr2($arService["UF_SERVICE_DESC" . POSTFIX_PROPERTY], 150);
        }?>
        <div onclick="if ($(this).hasClass('open')) {$(this).data('next-title', '<?=GetMessage('OTHER_OFFER_BUTTON_TITLE')?>');} else {$(this).data('next-title', '<?=GetMessage('OTHER_OFFER_BUTTON_TITLE_HIDE')?>');} $(this).toggleClass('closed');$(this).toggleClass('open'); $(this).text($(this).data('next-title')); $('.other_offers').toggle()" data-next-title="<?=GetMessage('OTHER_OFFER_BUTTON_TITLE_HIDE')?>" class="btn btn-primary ts-width-100 ts-d-flex other_offers-button closed"><?=GetMessage('OTHER_OFFER_BUTTON_TITLE')?></div>
        <div class="other_offers" style="display: none;">
        <?foreach ($HTML_DATA as $arData) {?>
            <div class='row ts-mx-0'>
                <? if ($arData["MAIN_BLOCK"]): ?>
                <div class="service-info-container">
                    <?
                    $col_ = 9;
                    if ($arData["MAIN_BLOCK"]["IMAGE_ID"]): $col_ = 6;
                        ?>
                        <div class="col-lg-3 col-md-3">
    						<?if (count($arData["MAIN_BLOCK"]['PICTURES'])>1):?>
                                <div class="banners-slider-list owl-carousel">
                                <?foreach ($arData["MAIN_BLOCK"]['PICTURES'] as $photo_id):?>
                                        <?if($arParams["TYPE"] != "transfers"): ?>
											<a <? if ($arResult["SERVICE_POPUP_JS"]) : ?>data-id="<?= $arData["MAIN_BLOCK"]["ID"] ?>"<? endif ?> href="<? if ($arResult["SERVICE_POPUP_JS"]) : ?>#srv-popup-<?= $arData["MAIN_BLOCK"]["ID"] ?><? else: ?>javascript:void(0)<? endif ?>" class="pointer <? if ($arResult["SERVICE_POPUP_JS"]) : ?> open-service-popup <? endif ?>">
										<?endif;?>
											<figure>
												<img  loading="lazy"  src="<?= getSrcImage($photo_id, array('width' => 300, 'height' => 195)) ?>">
											</figure>
										<?if($arParams["TYPE"] != "transfers"): ?></a><?endif;?>
                                <?endforeach;?>
                                </div>
                                <?else:?>
										<?if($arParams["TYPE"] != "transfers"): ?>
											<a <? if ($arResult["SERVICE_POPUP_JS"]) : ?>data-id="<?= $arData["MAIN_BLOCK"]["ID"] ?>"<? endif ?> href="<? if ($arResult["SERVICE_POPUP_JS"]) : ?>#srv-popup-<?= $arData["MAIN_BLOCK"]["ID"] ?><? else: ?>javascript:void(0)<? endif ?>" class="pointer <? if ($arResult["SERVICE_POPUP_JS"]) : ?> open-service-popup <? endif ?>">
										<?endif;?>
											<figure>
												<img  loading="lazy"  src="<?= getSrcImage($arData["MAIN_BLOCK"]["IMAGE_ID"], array('width' => 300, 'height' => 195)) ?>">
											</figure>
										<?if($arParams["TYPE"] != "transfers"): ?></a><?endif;?>
                                <?endif;?>
                        </div>
                    <? endif ?> 
                    <div class="col-lg-<?= $col_ ?> col-md-<?= $col_ ?>">
                        <div class="service-title">
    						<a <? if ($arResult["SERVICE_POPUP_JS"]) : ?>data-id="<?= $arData["MAIN_BLOCK"]["ID"] ?>"<? endif ?> href="<? if ($arResult["SERVICE_POPUP_JS"]) : ?>#srv-popup-<?= $arData["MAIN_BLOCK"]["ID"] ?><? else: ?>javascript:void(0)<? endif ?>" class="pointer <? if ($arResult["SERVICE_POPUP_JS"]) : ?> open-service-popup <? endif ?>"><?= $arData["MAIN_BLOCK"]["TITLE"] ?></a>
    					</div>
                        <div class="service-short-description">
                            <a <? if ($arResult["SERVICE_POPUP_JS"]) : ?>data-id="<?= $arData["MAIN_BLOCK"]["ID"] ?>"<? endif ?> href="<? if ($arResult["SERVICE_POPUP_JS"]) : ?>#srv-popup-<?= $arData["MAIN_BLOCK"]["ID"] ?><? else: ?>javascript:void(0)<? endif ?>" class="pointer <? if ($arResult["SERVICE_POPUP_JS"]) : ?> open-service-popup <? endif ?>"><?= $arData["MAIN_BLOCK"]["DESCRIPTION"] ?></a>
    					</div>
                        <div class="ts-col-24 ts-justify-content__center">
                            <a rel='nofollow' role='button' data-roomname='<?=$parentname?>: <?= $arData["MAIN_BLOCK"]["TITLE"] ?>' onclick="$('#object_name').val($(this).data('roomname'))" data-toggle='modal' data-target='#callback-modal' id='callback-btn' class='awe-btn right'><?= GetMessage("CALLBACK_MODAL_TITLE") ?></a>
                        </div>
                     </div>
                </div>
            <? endif ?>
            </div>    
        <?}?>
        </div>
        <div class="modal fade" id="callback-modal" tabindex="-1" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="modal-title"><b><?= GetMessage("CALLBACK_MODAL_TITLE") ?></b></h4>
                    </div>

                    <div class="modal-body">

                        <input type="hidden" name="current_page" value="<?= $APPLICATION->GetCurPageParam("", array(), false) ?>">
                        <input type="hidden" id="object_name" name="object_name" value="">
                        <div class="form-group">
                            <label for="full_name"><?= GetMessage("CALLBACK_FULL_NAME_TITLE") ?><span class="star">*</span></label>
                            <span class="error-container"></span>
                            <input name="full_name" value="" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="phone"><?= GetMessage("CALLBACK_PHONE_TITLE") ?></label>
                            <span class="error-container"></span>
                            <input name="phone" type="phone" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email"><?= GetMessage("CALLBACK_EMAIL_TITLE") ?><span class="star">*</span></label>
                            <span class="error-container"></span>
                            <input name="email" type="email" value="" class="form-control">
                        </div>
                        <div class="form-group has-feedback">
                            <label for="date"><?= GetMessage("CALLBACK_DATE_TITLE") ?><span class="star">*</span></label>
                            <span class="error-container"></span>
                            <input name="date" type="text" value="" class="form-control">
                            <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
                        </div>
                        <div class="form-group">
                            <label for="comment"><?= GetMessage("CALLBACK_COMMENT_TITLE") ?><span class="star">*</span></label>
                            <span class="error-container"></span>
                            <textarea name="comment" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="sender-btn" class="btn btn-primary"><?= GetMessage("CALLBACK_SEND_BTN_TITLE") ?></button>
                    </div>
                </div>
            </div>
        </div>
        <script>
    

                $(document).on("shown.bs.modal", "#callback-modal", function () {
                    if (typeof $.datepicker === "object") {
                        $.datepicker.regional['<?= LANGUAGE_ID ?>'] = {
                            dayNames: [<?= GetMessage("CALLBACK_DAYS_TITLE") ?>],
                            dayNamesShort: [<?= GetMessage("CALLBACK_DAYSSHORT_TITLE") ?>],
                            dayNamesMin: [<?= GetMessage("CALLBACK_DAYSSHORT_TITLE") ?>],
                            monthNames: [<?= GetMessage("CALLBACK_MONTH_TITLE") ?>],
                            monthNamesShort: [<?= GetMessage("CALLBACK_MONTHSHORT_TITLE") ?>]
                        }
                        $.datepicker.setDefaults($.datepicker.regional['<?= LANGUAGE_ID ?>']);
                        $('input[name="date"]').datepicker({
                            dateFormat: "dd.mm.yy"
                        });
                    }
                });

                $(document).on("click", "#sender-btn", function () {
                    var data = {
                        type: "callback",
                        object_name: $("#callback-modal input[name='object_name']").val(),
                        full_name: $("#callback-modal input[name='full_name']").val(),
                        phone: $("#callback-modal input[name='phone']").val(),
                        email: $("#callback-modal input[name='email']").val(),
                        date: $("#callback-modal input[name='date']").val(),
                        comment: $("#callback-modal textarea[name='comment']").val(),
                        sessid: "<?= bitrix_sessid() ?>",
                        current_page: $("#callback-modal input[name='current_page']").val(),
                    };
                    var haveError = false;

                    $("#callback-modal .error-container").html("");

                    for (var k in data) {
                        switch (k) {
                            case "full_name":
                                if (data[k].length <= 2) {
                                    haveError = true;
                                    $("#callback-modal input[name='full_name']")
                                            .prev(".error-container")
                                            .text(`<?= GetMessage("CALLBACK_FULL_NAME_ERROR") ?>`);
                                }
                                break;
                            case "comment":
                                if (data[k].length <= 2) {
                                    haveError = true;
                                    $("#callback-modal textarea[name='comment']")
                                            .prev(".error-container")
                                            .text(`<?= GetMessage("CALLBACK_COMMENT_ERROR") ?>`);
                                }
                                break;
                            case "phone":
                                if (data[k].length > 0 && !(/^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$/gm).test(data[k])) {
                                    haveError = true;
                                    $("#callback-modal input[name='phone']")
                                            .prev(".error-container")
                                            .text(`<?= GetMessage("CALLBACK_PHONE_ERROR") ?>`);
                                }
                                break;
                            case "email":
                                if (!(/^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/).test(data[k])) {
                                    haveError = true;
                                    $("#callback-modal input[name='email']")
                                            .prev(".error-container")
                                            .text(`<?= GetMessage("CALLBACK_EMAIL_ERROR") ?>`);
                                }
                                break;

                            case "date":
                                if (!data[k].length) {
                                    haveError = true;
                                    $("#callback-modal input[name='date']")
                                            .prev(".error-container")
                                            .text(`<?= GetMessage("CALLBACK_DATE_ERROR") ?>`);
                                }
                                break;
                        }
                    }

                    if (!haveError) {
                        $.post('/local/components/travelsoft/travelsoft.service.price.result/ajax.php', data, function (resp) {

                            $("#callback-modal .modal-footer").remove();
                            if (resp.callback_ok) {
                                $("#callback-modal .modal-body").html(`<?= GetMessage("CALLBACK_OK_MESSAGE") ?>`);
                            } else {
                                $("#callback-modal .modal-body").html(`<?= GetMessage("CALLBACK_FAIL_MESSAGE") ?>`);
                            }

                        }).fail(function () {
                            $("#callback-modal .modal-footer").remove();
                            $("#callback-modal .modal-body").html(`<?= GetMessage("CALLBACK_FAIL_MESSAGE") ?>`);
                        });
                    }
                });

                $(document).on("click", "#callback-ok-btn", function () {
                    $("#callback-modal").modal("hide");
                });
        </script>
     <?}
}
if (isset($arResult["SPECIFYING_DATA"]) && !empty($arResult["SPECIFYING_DATA"]["RATES"])):

    /**
     * @param array $arr_grouped_rates
     * @param string $selected
     * @return string
     */
    function getSelectRatesHTML($arr_grouped_rates, $select_name, $selected = null, $min_id = null) {
        if (!$selected && $min_id) $selected = $min_id;

        $SELECT_RATES_HTML = '<select name="' . $select_name . '" class="form-control selectpicker"><option value="">...</option>';

        foreach ($arr_grouped_rates as $group => $arr_rates) {
            $SELECT_RATES_HTML .= "<optgroup label='".GetMessage($group)."'>";
            foreach ($arr_rates as $id => $name) {
                $SELECT_RATES_HTML .= '<option ' . ($selected == $id ? "selected=''" : "") . ' value="' . $id . '">' . $name . '</option>';
            }
            $SELECT_RATES_HTML .= "</optgroup>";
        }

        $SELECT_RATES_HTML .= '</select>';

        return $SELECT_RATES_HTML;
    }

    $arr_request = $arParams["__BOOKING_REQUEST"];
    if (isset($arr_request["specifying"])) {
        unset($arr_request["specifying"]);
    }
    ?>
    <?// custom
       $min_prices = []; $min_prices_currency = [];
       foreach ($arResult['CALCULATION_ALL'][$arParams["__BOOKING_REQUEST"]['id'][0]] as $tmpval)
          foreach ($tmpval as $tmprateid=>$vals) 
             if ($min_prices[$tmprateid]>$vals['PRICE'] || !$min_prices[$tmprateid]) {
                $min_prices[$tmprateid] = $vals['PRICE'];
                $min_prices_currency[$tmprateid] = $vals['CURRENCY_ID'];
             }
        $current_pirces = [];
        foreach ($arResult["SPECIFYING_DATA"]["RATES_RENDER_DATA"] as $group => &$arr_rates) {
            
            foreach ($arr_rates as $id => &$name) {
                if ($min_prices[$id])  {
                    if ($group=='UF_RF_PRICES') {
                        $price_show = \travelsoft\booking\Utils::convertCurrency($min_prices[$id], $min_prices_currency[$id], 2, false);
                        $name.=', '.$price_show;
                        $current_pirces[2][$id] = $min_prices[$id];
                    }
                    if ($group=='UF_BR_PRICES') {
                        $price_show = \travelsoft\booking\Utils::convertCurrency($min_prices[$id], $min_prices_currency[$id], 1, false);
                        $name.=', '.$price_show;
                        $current_pirces[1][$id] = $min_prices[$id];
                    }
                    if ($group=='UF_EU_PRICES') {
                        $price_show = \travelsoft\booking\Utils::convertCurrency($min_prices[$id], $min_prices_currency[$id], 3, false);
                        $name.=', '.$price_show;
                        $current_pirces[3][$id] = $min_prices[$id];
                    }
                }
                else unset($arr_rates[$id]);                
                $index++;
            }
        }
        
        if ($_SESSION['current_currency']) $current_curency = $_SESSION['current_currency'];
        else $current_curency = 1;
        $minprice =0; $min_id = 0;
        foreach ($current_pirces[$current_curency] as $id=>$price) {
            if ($minprice>$price || !$minprice) {
                $minprice = $price;
                $min_id = $id;
            }
        }
    
    ?>
    
    <div class="specifying-form ts-wrap ts-p-4 ts-mb-4">

        <form id="specifying-form" class="form-horizontal" method="GET" action="<?//= $APPLICATION->GetCurPageParam("", array(), false) ?>">

            <input name="scroll-to-sp" type="hidden" value="Y">
            <?
            foreach ($arParams["__BOOKING_REQUEST"] as $key => $value) :
                if (is_array($value)):
                    ?>
                    <? foreach ($value as $kkey => $vvalue): ?>
                        <input name="booking[<?= $key ?>][<?= $kkey ?>]" type="hidden" value="<?= $vvalue ?>">
                    <? endforeach ?>
                <? else: ?>
                    <input name="booking[<?= $key ?>]" type="hidden" value="<?= $value ?>">
                <? endif ?>
            <? endforeach ?>

            <div  class="info form-group text-center"><span><?= GetMessage("SPECIFYING_FORM_TITLE_NEW")?></span>
                <div style="text-align: right; font-size: 14px; display: inline-block;"><a data-content="<?= GetMessage("SPECIFYING_FORM_TITLE")?>" class="webuiPopover fa fa-question"></a></div>
            </div>
            
            <script>
			<? if (check_smartphone()):?>
			
                $('#specifying-form .webuiPopover').webuiPopover({
					placement: "bottom",
					trigger: "click",
					 delay: {
							show: 1,
							hide: 300
					   }
				});
                
		    <? else: ?>
			   $('#specifying-form .webuiPopover').webuiPopover({
					placement: "left",
					trigger: "hover"
				});
                
			
			<?endif;?>
				
            </script>
            <? for ($adult_number = 1; $adult_number <= $arResult["SPECIFYING_DATA"]["PEOPLE"]["ADULTS"]; $adult_number++): ?>
                <div class="form-group ts-row ts-pb-2">
                    <label style="font-weight: 400" class="ts-col-24 ts-col-sm-4 ts-align-items__center"><?= GetMessage("SPECIFYING_FORM_ADULTS_FIELD_TITLE", array(
                        "#ADULT_NUMBER#" => $adult_number
                    ))?></label>
                    <div style="font-weight: 400" class="ts-col-24 ts-col-sm-20">
                        <?= getSelectRatesHTML($arResult["SPECIFYING_DATA"]["RATES_RENDER_DATA"], "booking[specifying][rates][adults][]", $arParams["__BOOKING_REQUEST"]["specifying"]["rates"]["adults"][$adult_number - 1], $min_id) ?>
                    </div>
                </div>
            <? endfor; ?>
            <? foreach ($arResult["SPECIFYING_DATA"]["PEOPLE"]["CHILDREN"] as $children_number => $children_age): ?>

                <div class="form-group ts-row ts-pb-2">
                    <label class="ts-col-24 ts-col-sm-4  ts-align-items__center"><?= GetMessage("SPECIFYING_FORM_CHILDREN_FIELD_TITLE", array(
                        "#CHILDREN_NUMBER#" => $children_number + 1,
                        "#CHILDREN_AGE#" => $children_age
                    ))?></label>
                    <div class="ts-col-24 ts-col-sm-20">
                        <?= getSelectRatesHTML($arResult["SPECIFYING_DATA"]["RATES_RENDER_DATA"], "booking[specifying][rates][children][]", $arParams["__BOOKING_REQUEST"]["specifying"]["rates"]["children"][$children_number], $min_id) ?>
                    </div>
                </div>
            <? endforeach ?>
            <?
                $current_link = $APPLICATION->GetCurPage(false);
                if (in_array($arParams["TYPE"], ['placements', 'sanatorium'])) {
                    $res = CIBlockElement::GetByID($arParams["__BOOKING_REQUEST"]['id'][0]);
                    if($ar_res = $res->GetNext()) {
                        $uri_parts = explode('?', $ar_res['DETAIL_PAGE_URL'], 2);
                        $current_link = $uri_parts[0];
                    }
                        
                }
                
            ?>
            <div class="form-group ts-row">
                <div class="ts-col-24 ts-align-items__center ts-justify-content__flex-end spec-btn-row">
                    <a class="btn btn-primary specifying-form-btn ts-mt-0 ts-mr-2 reset-btn" href="<?echo getCalculateDetailLink($current_link, $arr_request, array("scroll-to-sp" => "Y"));?>"><?= GetMessage("SPECIFYING_FORM_BUTTON_RESET")?></a>
                    <button <?if($arResult["NEED_LAZY_LOAD"]):?>disabled=""<?endif?> type="submit" class="btn btn-primary specifying-form-btn ts-mt-0"><?= GetMessage("SPECIFYING_FORM_BUTTON_TITLE")?></button>
                </div>
            </div>
        </form>

</div>
    <script>
        (function ($) {
            $(document).ready(function () {
				$(".selectpicker").selectpicker();

                $("#specifying-form").on("submit", function (e) {
					
                    var $specifying_adults_rates = $("select[name='booking[specifying][rates][adults][]']");
                    var $specifying_children_rates = $("select[name='booking[specifying][rates][children][]']");

                    var errors = [];

                    if ($specifying_adults_rates.length) {
                        $specifying_adults_rates.each(function () {
                            if (!$(this).val()) {
                                errors.push("Необходимо выбрать тариф для всех взрослых");
                                return false;
                            }
					
                        });
                    }

                    if ($specifying_children_rates.length) {
                        $specifying_children_rates.each(function () {
                            if (!$(this).val()) {
                                errors.push("Необходимо выбрать тариф для всех детей");
                                return false;
                            }
							
                        });
                    }

                    if (errors.length) {
                        alert(errors.join("\n"));
                        e.preventDefault();
                    }
					
					$(".selectpicker").selectpicker(refresh);
					
                });

            });
        })(jQuery);

    </script>
<? endif; ?>