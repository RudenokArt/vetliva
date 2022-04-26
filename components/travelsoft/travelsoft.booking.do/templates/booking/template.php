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

$this->addExternalCss($templateFolder . "/phonecode/phonecode.css");
$this->addExternalCss($templateFolder . "/bootstrap-select.min.css");
$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/webui-popover/jquery.webui-popover.min.css");
$this->addExternalJs(SITE_TEMPLATE_PATH . "/js/webui-popover/jquery.webui-popover.min.js");
$this->addExternalJs($templateFolder . "/phonecode/jquery-ui-1.10.4.custom.min.js");
$this->addExternalJs($templateFolder . "/phonecode/counties.js"); 
if (LANGUAGE_ID=='en') {
    $this->addExternalJs($templateFolder . "/phonecode/phonecode_en.js");   
}
elseif  (LANGUAGE_ID=='by') {
    $this->addExternalJs($templateFolder . "/phonecode/phonecode_by.js");   
}
else{
    $this->addExternalJs($templateFolder . "/phonecode/phonecode.js");   
} 
   
$this->addExternalJs($templateFolder . "/bootstrap-select.min.js");    

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

// show alert
function _sa($message, $return = false) {

    $stringTpl = "<div role=\"alert\" class=\"alert alert-danger\">#MESSAGE#</div></div>";

    $icon = "<i class=\"fa fa-info-circle\"></i>";

    if (is_array($message)) {

        $stack = array();
        foreach ($message as $m) {
            $stack[] = "<div>" . $icon . " " . $m . "</div>";
        }

        $string = str_replace("#MESSAGE#", implode("", $stack), $stringTpl);
    } else {

        $string = str_replace("#MESSAGE#", "<div>" . $icon . " " . $message . "</div>", $stringTpl);
    }

    if ($return) {
        return $string;
    }

    echo $string;
}

// show error
function _se($message, $br = true) {
    echo "<span class='error-container' style='color:red;font-size: 11px'>" . ($br ? "<br>" : "") . $message . ($br ? "<br>" : "") . "</span>";
}

if ($arResult['BASKET']->isEmpty()) {
    _sa(Loc::getMessage("EMPTY_CART"));
    return;
}
?>

<div class="row">
    <div class="container">
        <div class="main-cn bg-white clearfix">
             <div class="booking-h1">
				<a class="back-link desk-back-link awe-btn awe-btn-1 awe-btn-small" href="javascript:void(0)" onclick="window.history.go(-1); return false;"><?= Loc::getMessage('BACK') ?></a>
				<h1><?= $APPLICATION->ShowTitle(false) ?></h1>
			 </div>
			<div class="basket-title"><h3><img src="<?= SITE_TEMPLATE_PATH ?>/images/cart-head.svg"><?= GetMessage('BASKET_TITLE') ?></h3></div>
                        <a class="back-link mob-back-link" href="javascript:void(0)" onclick="window.history.go(-1); return false;"><?= Loc::getMessage('BACK') ?></a>
                        <?
                        if ($arResult["ERRORS"]["BOOKING"]) {
                            _sa(Loc::getMessage("BOOKING_ERROR"));
                        } elseif ($arResult["ERRORS"]["WRONG_VERIFICATION_BY_CITIZENSHIP"]) {
                            _sa(Loc::getMessage("WRONG_VERIFICATION_BY_CITIZENSHIP"));
                        } else if ($arResult["ERRORS"]["FORM"]) {
                            foreach ($arResult["ERRORS"]["FORM"] as $key => $val) {
                                if ((string) $key != "TOURIST") {
                                    $stack[] = Loc::getMessage($val);
                                } else {
                                    $stack[] = Loc::getMessage("WRONG_SOME_TOURIST");
                                }
                            }
                            _sa($stack);
                        }

                        $max_people = 0;
                        $arCrossSaleData = [];
                        $max_timestamp = exp(100);
                        while ($basketItem = $arResult["BASKET"]->fetch()) :
                            $get_data_array = [];
                            $arItem = $basketItem["item"]->getPropertiesLikeArray();
                            $arItem["position"] = $basketItem["position"];

                            if ($USER->IsAuthorized()) {
                                $bitrix_male =array("M"=>'м', "F"=>'ж');
                                $userfullinfo = CUser::GetByID($USER->GetID())->Fetch(); 
                                // заполнение полей  
                                if($arItem['adults'] > 0){ 
                                    if (empty($arParams["_POST"]["tourist"][0]['name']) && !empty($userfullinfo['NAME'])) $arParams["_POST"]["tourist"][0]['name'] = $userfullinfo['NAME'];
                                    if (empty($arParams["_POST"]["tourist"][0]['last_name']) && !empty($userfullinfo['LAST_NAME'])) $arParams["_POST"]["tourist"][0]['last_name'] = $userfullinfo['LAST_NAME'];
                                    if (empty($arParams["_POST"]["tourist"][0]['male']) && !empty($userfullinfo['PERSONAL_GENDER'])) $arParams["_POST"]["tourist"][0]['male'] = $bitrix_male[$userfullinfo['PERSONAL_GENDER']];
                                    if (empty($arParams["_POST"]["tourist"][0]['citizenship']) && !empty($userfullinfo['UF_TSCITIZENSHIP'])) $arParams["_POST"]["tourist"][0]['citizenship'] = $userfullinfo['UF_TSCITIZENSHIP'];
                                    if (empty($arParams["_POST"]["tourist"][0]['birthdate']) && !empty($userfullinfo['PERSONAL_BIRTHDAY'])) $arParams["_POST"]["tourist"][0]['birthdate'] = $userfullinfo['PERSONAL_BIRTHDAY'];
                                } else {
                                    if (empty($arParams["_POST"]["resp_adult"]['name']) && !empty($userfullinfo['NAME'])) $arParams["_POST"]["resp_adult"]['name'] = $userfullinfo['NAME'];
                                    if (empty($arParams["_POST"]["resp_adult"]['last_name']) && !empty($userfullinfo['LAST_NAME'])) $arParams["_POST"]["resp_adult"]['last_name'] = $userfullinfo['LAST_NAME'];
                                    if (empty($arParams["_POST"]["resp_adult"]['male']) && !empty($userfullinfo['PERSONAL_GENDER'])) $arParams["_POST"]["resp_adult"]['male'] = $bitrix_male[$userfullinfo['PERSONAL_GENDER']];
                                    if (empty($arParams["_POST"]["resp_adult"]['citizenship']) && !empty($userfullinfo['UF_TSCITIZENSHIP'])) $arParams["_POST"]["resp_adult"]['citizenship'] = $userfullinfo['UF_TSCITIZENSHIP'];
                                    if (empty($arParams["_POST"]["resp_adult"]['birthdate']) && !empty($userfullinfo['PERSONAL_BIRTHDAY'])) $arParams["_POST"]["resp_adult"]['birthdate'] = $userfullinfo['PERSONAL_BIRTHDAY'];
                                }
                            }

                            if ($arItem["can_buy"]) {
                                $people = $arItem["adults"] + $arItem["children"];
                                $total_people_count += $people;
                                $max_people = $people > $max_people ? $people : $max_people;
                                $active_service_count++;

                                // формируем массив данных для вызова компонентов crosssale
                                $arBasketTypes[] = $arItem['type'];
                                if (in_array($arItem['type'], ['excursions', 'sanatorium', 'placements']) && $arItem['date_from'] <= $max_timestamp) {
                                  
                                    $max_timestamp = $arItem['date_from'];
                                    
                                    $crossale = null;
                                    if (in_array($arItem['type'], ["placements", "sanatroium"])) {
                                        $crossale = "transfers";
                                    } elseif (in_array($arItem['type'], ["excursions"])) {
                                        $crossale = "placements";
                                    }
                                    $arCrossSaleData[$crossale] = [
                                        'type' => $arItem['type'],
                                        'date_from' => $arItem['date_from'],
                                        'date_to' => $arItem['date_to'],
                                        'adults' => $arItem['adults'],
                                        'children' => $arItem['children'],
                                        'children_age' => $arItem['children_age'],
                                        'service_id' => $arItem['service_id']
                                    ];
                                }
                            }
                            $get_data_array['booking'] = $arItem;
                            $get_data = http_build_query($get_data_array);
                            ?>
                            <div class="payment-room mb-10 pos-rel item-cart">
                                <div class="row">
									<?
									$html_id = "delete-position-" . $arItem["position"];
									$arDelPos[] = array("position" => $arItem["position"], "html_id" => $html_id);
									?>
                                    <div class="col-lg-12 payment-room-priceservice">
                                    <? if ($arItem['type'] != 'transfers'): ?>
                                            <h2 class="mt-none">
                                                <a target="_blank" href="<?= $arResult["PARENT_ELEMENTS"][$arItem["type"]][$arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["DETAIL_PAGE_URL"] ?>&<?=$get_data?>&scroll-to-sp=Y"><?= $arResult["PARENT_ELEMENTS"][$arItem["type"]][$arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_IBLOCK_ELEMENT_ID"]]["NAME"] ?></a>
                                            </h2>
                                        <? else: ?>
                                            <h2 class="mt-none">
                                                <a target="_blank" href="/tourism/transfer/?scroll-to-sp=Y&<?=$get_data?>"><?= $arResult['RATES'][$arItem['type']][$arItem['rate_id']]['UF_NAME' . POSTFIX_PROPERTY] ?></a>
                                            </h2>
                                        <? endif ?>
                                        <div class="delete-position"><span id="<?= $html_id ?>"><img src="<?= SITE_TEMPLATE_PATH ?>/images/close-payment.svg"></span></div>
                                        <div class="payment-price">
                                            <div class="col-lg-6">
												<? if (!empty($arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["PICTURE"]['src'])): ?>
                                                    <figure>
                                                        <img src="<?= $arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["PICTURE"]['src'] ?>" alt="<?= $arItem["UF_NAME" . POSTFIX_PROPERTY] ?>">
                                                    </figure>
												<? endif; ?>
                                                <div class="mt-5 room-mt">
                                                    <span class="d-cell tarif-label"><?= Loc::getMessage("NAME") ?>:</span> <span class="d-cell"><?= $arResult["SERVICES"][$arItem["type"]][$arItem["service_id"]]["UF_NAME" . POSTFIX_PROPERTY] ?></span>
                                                </div>
                                                <div class="mt-5 room-mt">
                                                    <span class="d-cell tarif-label"><?= Loc::getMessage("RATE") ?>:</span> <span class="d-cell"><?= $arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["UF_NAME" . POSTFIX_PROPERTY] ?>
                                                    <?=$arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["DESCRIPTION"]?>    </span>
                                                </div>
												<?
												$food = null;
												for ($i = 0, $cnt = count($arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["UF_FOOD_ID"]); $i < $cnt; $i++) {
													$food[] = $arResult["FOOD"][$arResult["RATES"][$arItem["type"]][$arItem["rate_id"]]["UF_FOOD_ID"][$i]];
												}
												?>
                                                <?
                                                $food = array_filter($food, function ($it) {
                                                    return $it > 0;
                                                });
                                                if ($food):
                                                    ?>
                                                    <div class="mt-5">
                                                        <b><?= Loc::getMessage("FOOD") ?>:</b> <?= implode(", ", $food) ?>
                                                    </div>
    <? endif ?>
                                            </div>
                                            <div class="col-lg-3">
    <?
    if (!$arItem["can_buy"]) {
        _sa(Loc::getMessage("CAN'T_BOOKING"));
    }
    $date_from = date("d.m.Y", $arItem["date_from"]);
    $date_to = date("d.m.Y", $arItem["date_to"]);
    ?>
                                                <div class="mt-5"><span class="d-cell tarif-label"><?= Loc::getMessage("DATE_FROM") ?>:</span> <span class="d-cell"><?= $date_from ?></span></div>
                                                <? if (($arItem["date_to"] && $arItem['type'] != "transfers") || $arItem['roundtrip']) : ?>
                                                    <div class="mt-5"><span class="d-cell tarif-label"><?= Loc::getMessage("DATE_TO") ?>:</span>  <span class="d-cell"><?= $date_to ?></span></div>
                                                <? endif ?>
                                                <? if ($arItem["duration"]): ?>
                                                    <div class="mt-5"><span class="d-cell tarif-label"><?= Loc::getMessage("DAYS") ?>:</span>  <span class="d-cell"><?= $arItem["duration"]; ?></span></div>
                                                <? endif ?>
                                                <div class="mt-5"><span class="d-cell tarif-label"><?= Loc::getMessage("ADULTS") ?>:</span> <span class="d-cell"> <?= $arItem["adults"] ?></span></div>
                                                <? if ($arItem["children"] > 0): ?>
                                                    <div class="mt-5"><span class="d-cell tarif-label"><?= Loc::getMessage("CHILDREN") ?>:</span> <span class="d-cell"> <?= $arItem["children"] ?></span></div>
                                                <? endif ?>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="price-in-basket">
                                                    <b class="cost-label"><?= Loc::getMessage("PRICE") ?>: </b><span class="cost-number"><?= \travelsoft\Currency::getInstance()->convertCurrency($arItem["price"], $arItem["currency"]) ?></span>
                                                </div>
    <?
    if (is_array($arItem["discount"]) && !empty($arItem["discount"])):

        $total_discount = 0.00;
        $discount_price = $arItem["price"];
        foreach ($arItem["discount"] as $discount) {
            $discount_price -= $discount;
            $total_discount += $discount;
        }
        ?>
                                                    <?/*<div class="price-in-basket">
                                                        <b><?= Loc::getMessage("DISCOUNT") ?>: </b><?= \travelsoft\Currency::getInstance()->convertCurrency($total_discount, $arItem["currency"]) ?>
                                                    </div>
                                                    <div class="price-in-basket">
                                                        <b><?= Loc::getMessage("TOTAL_COST") ?>: </b><?= \travelsoft\Currency::getInstance()->convertCurrency($discount_price, $arItem["currency"]) ?>
                                                    </div>*/?>
                                                <? endif ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <? endwhile; ?>
						  <? //if ($USER->IsAdmin()): ?>
                                    <div class="promo-row info-form alert-box alert-success">
										
                                        <div class="promo-quest">
												<div class="form-field promo">
													<h4>
														<input class="have-promo" type="checkbox">
														<span class="checkbox_fake"></span>
														<?= Loc::getMessage("PROMO_TITLE") ?> <i data-hint="<?= Loc::getMessage("PROMO_HINT") ?>" class="hint fa fa-question-circle" aria-hidden="true"></i>
													</h4>
													<div class="promo-input-area hidden"><input name="promocode" value="" type="text" class="field-input"> <button data-input-link="input[name=promocode]" class="awe-btn awe-btn-1 awe-btn-lager" type="button" name="promo_apply"><?= Loc::getMessage("APPLY_PROMO") ?></button></div>
												</div>
												<table class="promo-disclaim">
													<tbody>
														<tr>
															<td>
															<?= Loc::getMessage("APPLYED_PROMO") ?>:&nbsp; </td><td id="applyed-promo-area"><? $promocodes = travelsoft\booking\Promo::getList();
																if (!empty($promocodes)) {
																	echo implode(', ', $promocodes);
																} else {
																	echo Loc::getMessage("NO_PROMO_APPLYED");
																} ?>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
                                            <table id="total-cost-info">

                                                <tbody>

                                                    <?/*<tr><td><?= Loc::getMessage("COST") ?>:&nbsp; </td><td id="cost-area"><?= $arResult['BASKET']->formattedCost() ?></td></tr>*/?>
                                                    <tr><td class="cost-label"><?= Loc::getMessage("DISCOUNT") ?>:</td><td id="discount-area" class="cost-number"><?= $arResult['BASKET']->formattedDiscount() ?></td></tr>
                                                    <tr><td class="cost-label"><?= Loc::getMessage("TOTAL_COST") ?>:</td><td id="total-area" class="cost-number"><?= $arResult['BASKET']->formattedTotal() ?></td></tr>
                                                </tbody>
                                            </table>
                                       
                                    </div>
                            <? //endif?>
						
                        <div class="addittional-booking-links">
                            <span class="title"><?=Loc::getMessage("ADDITIONAL_BOOKING_TITLE");?></span>
							<div class="additional-booking__row">
								<a href="/tourism/where-to-stay/" class="awe-btn awe-btn-1 awe-btn-small"><?=Loc::getMessage("ACCOMODATION");?></a>
								<a href="/tourism/health-tourism/" class="awe-btn awe-btn-1 awe-btn-small"><?=Loc::getMessage("RESORTS");?></a>
								<a href="/tourism/cognitive-tourism/" class="awe-btn awe-btn-1 awe-btn-small"><?=Loc::getMessage("EXCURSIONS");?></a>
								<a href="/tourism/tours-in-belarus/" class="awe-btn awe-btn-1 awe-btn-small"><?=Loc::getMessage("EXCURSIONS_TOURS");?></a>
								<a href="/tourism/transfer/" class="awe-btn awe-btn-1 awe-btn-small"><?=Loc::getMessage("TRANSFERS");?></a>
							</div>
						</div>
                        <? if (isset($arCrossSaleData) && !empty($arCrossSaleData)):
                            
                            foreach ($arCrossSaleData as $crossale => $data) :

                                $APPLICATION->IncludeComponent(
                                        "travelsoft:crossale", "", Array(
                                    "SERVICE_ID" => $data['service_id'],
                                    "TYPE" => $data['type'],
                                    "BASKET_SERVICES_TYPES" => $arBasketTypes,
                                    "DATE_FROM" => $data['date_from'],
                                    "DATE_TO" => $data['date_to'],
                                    "ADULTS" => $data['adults'],
                                    "CHILDREN" => $data['children'],
                                    "CHILDREN_AGE" => $data['children_age']
                                        )
                                );
                                ?>
                            <? endforeach ?>
                        <? endif ?>
                        <?
                        if (count($arResult['POST']['tourist']) > $max_people) {
                            $max_people = count($arResult['POST']['tourist']);
                        }
                        if (!$USER->IsAuthorized()) {?>
                        <div class="auth-registration-block">
                            <div class="col-md-12">
                                <ul class="nav-tabs">
                                    <li class="active"><a data-toggle="tab" class="registration-switch" href="#booking-registration"><?=GetMessage('DIALOG_DO_REGISTRATION_BTN')?></a></li>
                                    <li><a data-toggle="tab"  class="authorization-switch"  href="#booking-authorization"><?=GetMessage('DIALOG_DO_AUTHORIZE_BTN')?></a></li>
                                </ul>
                                <div class="tab-content clearfix">
                                    <div class="tab-pane active" id="booking-registration">
                                        <?
                                        $APPLICATION->IncludeComponent("bitrix:system.auth.registration", "booking-flat", Array());
                                        ?>
                                    </div>
                                    <div class="tab-pane" id="booking-authorization">
                                        <?
                						$APPLICATION->IncludeComponent("bitrix:system.auth.form", "booking-auth", Array(
                							"REGISTER_URL" => "/private-office/index.php",
                							"FORGOT_PASSWORD_URL" => "/private-office/index.php",
                							"PROFILE_URL" => "/private-office/",
                							"SHOW_ERRORS" => "Y",
                								)
                						);
                						?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?}
                        if ($USER->IsAuthorized() && $_SESSION['NEW_USER_REGISTER'][$USER->GetID()] == 'YES'):?>
                            <div id="succes_register" class="header-auth-form mfp-hide">
                                <div class="bx-authform register-link">
                                <div class="logotype"></div>
                                <?=GetMessage('DIALOG_STATUS_NEW')?>
                                </div>
                            </div>
                            <a class="show_succes_register" href="#succes_register" ></a>
                            <script>
                                $(".show_succes_register").magnificPopup({
                                    type: "inline",
                                    midClick: true
                                });
                                $(".show_succes_register").click();
                            </script>
                        <?unset ($_SESSION['NEW_USER_REGISTER'][$USER->GetID()]); endif;
                        if ($max_people && $USER->IsAuthorized()):
                            ?>

                            <form id="booking" action="<?= $APPLICATION->GetCurPage(false) ?>" method="POST">
                                <div id="form-shading"></div>
    <?= bitrix_sessid_post() ?>
                                <div class="payment-form">
                                    <div class="row form bg-none tourist-row">
    <?
    $tag_open = true;
    $er = $arResult["ERRORS"]["FORM"]["TOURIST"];
    for ($i = 0; $i < $max_people; $i++):
        ?>
                                            <div class="col-md-12 tourist-block">
                                                <h3><?= Loc::getMessage("TOURIST") ?> <?= $i + 1 ?></h3>
                                                <div class="form-field col-md-3"><span><?= Loc::getMessage("PLACEHOLDER_NAME") ?></span><?
                                    if (in_array("WRONG_NAME", $er[$i])) {
                                        _se(Loc::getMessage("WRONG_NAME"));
                                    }
        ?><input data-error-type="WRONG_NAME" value="<?= $arParams["_POST"]["tourist"][$i]['name'] ?>" type="text" name="make_booking[tourist][<?= $i ?>][name]" placeholder="" class="field-input to-validate"></div>
                                                <div class="form-field col-md-3"><span><?= Loc::getMessage("PLACEHOLDER_LAST_NAME") ?></span><?
                                                        if (in_array("WRONG_LAST_NAME", $er[$i])) {
                                                            _se(Loc::getMessage("WRONG_LAST_NAME"));
                                                        }
                                                        ?><input  data-error-type="WRONG_LAST_NAME" value="<?= $arParams["_POST"]["tourist"][$i]['last_name'] ?>" type="text" name="make_booking[tourist][<?= $i ?>][last_name]" placeholder="" class="field-input to-validate"></div>
                                                
                                                <?if ($i==0):?>
                                                <input  <? if ($arResult["USER"]["email"]): ?>value="<?= $arResult["USER"]["email"] ?>" readonly<? endif ?>  name="make_booking[email]" id="main-email" type="hidden" placeholder="mail@example.com">
                                                <div class="form-field phone-field col-md-3"><span><?= Loc::getMessage("PLACEHOLDER_YOUR_PHONE") ?></span>
                                                    <?
                                                    $sr = array_search("WRONG_PHONE", $arResult["ERRORS"]["FORM"]);
                                                    if ($sr !== null && $sr !== false) {
                                                        _se(Loc::getMessage("WRONG_PHONE"), false);
                                                    }
                                                    ?>
                                                    <input  type="text" style="width: 80%;" name="customer_phone" placeholder="<?= Loc::getMessage("PLACEHOLDER_YOUR_PHONE") ?>" id="customer_phone" class="field-input to-validate" required /> 
    						                        <input data-error-type="WRONG_PHONE" minlength="9" maxlength="10" value="<?= $arParams["_POST"]['phone'] ?>" pattern="<?= $arResult["PATTERNS"]["phone"] ?>" name="make_booking[phone]" id="phone-email" type="hidden" placeholder="+375 XX XXXXXXX" class="field-input to-validate">
                                                  
    											</div>
                                                <?endif;?>
                                                <div class="form-field col-md-3  <?if (in_array("WRONG_MALE")){?> male-field <?}?>">
                                                        <?
                                                        if (in_array("WRONG_MALE", $er[$i])) {
                                                            _se(Loc::getMessage("WRONG_MALE"));
                                                        }
                                                        ?>
                                                    <span><?= Loc::getMessage("SEX") ?></span><br>
                                                    <select data-error-type="WRONG_MALE" class="to-validate wrong_male" name="make_booking[tourist][<?= $i ?>][male]">
        <? foreach ($arResult["MALE"] as $val => $title): ?>
                                                            <option <? if ($arParams["_POST"]["tourist"][$i]['male'] == $val): ?>selected<? endif ?> value="<?= $val ?>"><?= Loc::getMessage($title) ?></option>
                                                        <? endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="form-field col-md-3"><span><?= Loc::getMessage("PLACEHOLDER_BIRTHDATE") ?></span><?
                                                if (in_array("WRONG_BIRTHDATE", $er[$i])) {
                                                    _se(Loc::getMessage("WRONG_BIRTHDATE"));
                                                }
                                                        ?><input data-error-type="WRONG_BIRTHDATE" value="<?= $arParams["_POST"]['tourist'][$i]["birthdate"] ?>" pattern="<?= $arResult["PATTERNS"]["birthdate"] ?>" name="make_booking[tourist][<?= $i ?>][birthdate]" type="text" placeholder="<?= Loc::getMessage("DDMMYYYY") ?>" class="birthdate-field field-input to-validate"></div>
                                                <div class="form-field col-md-3"><span><?= Loc::getMessage("PLACEHOLDER_PASSPORT") ?></span><?
                                                        if (in_array("WRONG_PASSPORT", $er[$i])) {
                                                            _se(Loc::getMessage("WRONG_PASSPORT"));
                                                        }
                                                        ?><input data-error-type="WRONG_PASSPORT" value="<?= $arParams["_POST"]['tourist'][$i]["passport"] ?>" name="make_booking[tourist][<?= $i ?>][passport]" type="text" placeholder="" class="field-input to-validate"></div>
												<div class="form-field col-md-3"><span><?= Loc::getMessage("PLACEHOLDER_CITIZEN") ?></span><a id="citizen_empty"></a><?
                                                        if (in_array("WRONG_CITIZENSHIP", $er[$i])) {
                                                            _se(Loc::getMessage("WRONG_CITIZENSHIP"));
                                                        }
                                                        ?><br>
                                                    <select data-error-type="WRONG_CITIZENSHIP" title="<?=Loc::getMessage("PLACEHOLDER_CITIZEN_SELECT")?>" class="to-validate selectpicker wrong_citizenship" data-live-search="true" name="make_booking[tourist][<?= $i ?>][citizenship]">
                                                        <option data-hidden="true"><?=Loc::getMessage("PLACEHOLDER_CITIZEN_SELECT")?></option>
                                                        <?
                                                        //asort($arResult["CITIZENSHIPS"]);
                                                        foreach ($arResult["CITIZENSHIPS"] as $ID => $name) {
                                                            ?>
                                                            <option <?
                                                if ($arParams["_POST"]['tourist'][$i]["citizenship"] == $ID) {
                                                    echo "selected";
                                                }
                                                            ?> value="<?= $ID ?>"><?= $name ?></option>
                                                            <? } ?>
                                                    </select>
                                                </div>
                                                <? /* <div class="radio-checkbox"><input type="checkbox" class="checkbox" id="accept"><label for="accept">нужна виза</label></div> */ ?>
                                            </div>
                                            <?
                                            if (($i + 1) % 2 === 0):
                                                $tag_open = false;
                                                ?>
                                            </div>
                                            <?
                                            if (($i + 1) < $max_people):
                                                $tag_open = true;
                                                ?>
                                                <div class="row form bg-none tourist-row">
                                                <? endif ?>
                                            <? endif ?>
                                        <? endfor ?>
                                        <? if ($tag_open): ?>
                                            <span class="additional-tourist-area"></span>
                                        </div>
                                    <? endif ?>
                                    <? if ($total_people_count > $max_people): ?>
                                        <div class="row form bg-none text-center">
                                            <div class="col-md-12">
                                                <button type="button" id="add-tourist-btn" class="awe-btn awe-btn-1">
                                                    <?= Loc::getMessage('ADD_TOURIST_BTN_TITLE') ?></button></div>
                                        </div>
                                    <? endif ?>

                                    <? if($arItem["adults"] == 0) : ?>
                                        <div class="row form bg-none adult-row">                                            
                                            <div class="col-md-12 adult-block">
                                                <h3><?= Loc::getMessage("RESPONSIBLE_ADULT") ?></h3>
                                                <div class="form-field col-md-3">
                                                    <span><?= Loc::getMessage("PLACEHOLDER_NAME") ?></span>
                                                    <input value="<?= $arParams["_POST"]["resp_adult"]['name'] ?>" 
                                                        type="text" name="make_booking[resp_adult][name]" class="field-input to-validate">
                                                </div>
                                                <div class="form-field col-md-3">
                                                    <span><?= Loc::getMessage("PLACEHOLDER_LAST_NAME") ?></span>
                                                    <input value="<?= $arParams["_POST"]["resp_adult"]['last_name'] ?>" 
                                                        type="text" name="make_booking[resp_adult][last_name]"  class="field-input to-validate">
                                                </div>
                                                <div class="form-field col-md-3 male-field">
                                                    <span><?= Loc::getMessage("SEX") ?></span><br>
                                                    <select class="to-validate" name="make_booking[resp_adult][male]">
                                                        <? foreach ($arResult["MALE"] as $val => $title): ?>
                                                            <option <? if ($arParams["_POST"]["resp_adult"]['male'] == $val): ?>selected<? endif ?> value="<?= $val ?>"><?= Loc::getMessage($title) ?></option>
                                                        <? endforeach ?>
                                                    </select>
                                                </div>
                                                <div class="form-field col-md-3"><span><?= Loc::getMessage("PLACEHOLDER_BIRTHDATE") ?></span>
                                                    <input value="<?= $arParams["_POST"]['resp_adult']["birthdate"] ?>" pattern="<?= $arResult["PATTERNS"]["birthdate"] ?>" 
                                                        name="make_booking[resp_adult][birthdate]" type="text" placeholder="<?= Loc::getMessage("DDMMYYYY") ?>" 
                                                        class="birthdate-field field-input to-validate">
                                                </div>
                                                <div class="form-field col-md-3"><span><?= Loc::getMessage("PLACEHOLDER_PASSPORT") ?></span>
                                                    <input name="make_booking[resp_adult][passport]" type="text" placeholder="" class="field-input to-validate">
                                                </div>
												<div class="form-field col-md-3 citizen-field"><span><?= Loc::getMessage("PLACEHOLDER_CITIZEN") ?></span><a id="citizen_empty"></a><br>
                                                    <select title="<?=Loc::getMessage("PLACEHOLDER_CITIZEN_SELECT")?>" class="to-validate selectpicker" data-live-search="true"
                                                             name="make_booking[resp_adult][citizenship]">
                                                        <option data-hidden="true"><?=Loc::getMessage("PLACEHOLDER_CITIZEN_SELECT")?></option>
                                                        <? foreach ($arResult["CITIZENSHIPS"] as $ID => $name) : ?>
                                                            <option value="<?= $ID ?>"
                                                                <? if ($arParams["_POST"]['resp_adult']["citizenship"] == $ID) : ?>
                                                                    selected
                                                                <? endif; ?> 
                                                            >
                                                                <?= $name ?>
                                                            </option>
                                                        <? endforeach; ?>
                                                    </select>
                                                </div>
                                            </div>                                            
                                        </div>           
                                    <? endif; ?>

                                    <div class="row form bg-none">
                                        <div class="col-md-12">
                                            <div class="form-field"><span><?= Loc::getMessage($arParams["NEED_TRANSFER_COMMENT"] ? "TRANSFER_COMMENT" : "COMMENT") ?></span> <i data-hint="<?= Loc::getMessage($arParams["NEED_TRANSFER_COMMENT"] ? "TRANSFER_COMMENT_HINT" : "COMMENT_HINT") ?>" class="hint fa fa-question-circle wish-hint" aria-hidden="true"></i><textarea value="<?= $arParams["_POST"]["comment"] ?>" name="make_booking[comment]" placeholder="<?= Loc::getMessage("PLACEHOLDER_COMMENT") ?>" class="field-input"></textarea></div>
                                        </div>
                                    </div>
                                </div>

                                
                                <?/*<div class="info-form alert-box alert-success">
                                    <div class="row form">
                                        <div class="col-md-12">
                                            <div class="form-field mail-field col-md-6">
											
											<h3><?= Loc::getMessage("PLACEHOLDER_YOUR_EMAIL") ?><?if (!$USER->IsAuthorized()):?><span class="input-description"><?= Loc::getMessage("EMAIL_DESCRIPTION") ?></span><?endif;?></h3><?
												$sr = array_search("WRONG_EMAIL", $arResult["ERRORS"]["FORM"]);
												if ($sr !== null && $sr !== false) {
													_se(Loc::getMessage("WRONG_EMAIL"), false);
												}
												?><input data-error-type="WRONG_EMAIL" <? if ($arResult["USER"]["email"]): ?>value="<?= $arResult["USER"]["email"] ?>" readonly<? endif ?>  name="make_booking[email]" id="main-email" type="email" placeholder="mail@example.com" class="field-input to-validate">
															
											</div>
                                           

										   <div class="form-field phone-field col-md-6"><h3><?= Loc::getMessage("PLACEHOLDER_YOUR_PHONE") ?><span class="input-description"><?= Loc::getMessage("PHONE_DESCRIPTION") ?></span></h3>
                                                <?
                                                $sr = array_search("WRONG_PHONE", $arResult["ERRORS"]["FORM"]);
                                                if ($sr !== null && $sr !== false) {
                                                    _se(Loc::getMessage("WRONG_PHONE"), false);
                                                }
                                                ?>
                                                <input  type="text" style="width: 80%;" name="customer_phone" placeholder="<?= Loc::getMessage("PLACEHOLDER_YOUR_PHONE") ?>" id="customer_phone" class="field-input to-validate" required /> 
						                        <input data-error-type="WRONG_PHONE" minlength="9" maxlength="10" value="<?= $arParams["_POST"]['phone'] ?>" pattern="<?= $arResult["PATTERNS"]["phone"] ?>" name="make_booking[phone]" id="phone-email" type="hidden" placeholder="+375 XX XXXXXXX" class="field-input to-validate">
                                              
											</div>
											
                                        </div>
                                    </div>
                                    
                                </div> */?>
								     
									 
								<div class="total-cost-info">
									<table>
                                         <tbody>
                                              <tr><td class="cost-label"><?= Loc::getMessage("TOTAL_COST_BOTTOM") ?>:&nbsp; </td><td id="total-area-footer" class="cost-number"><?= $arResult['BASKET']->formattedTotal() ?></td></tr>
                                         </tbody>
									</table>
                                </div>
								
								
                                <div class="info-form submit text-center">

                                    <? $arGroups = $USER->GetUserGroupArray(); ?>
                                    <? if (!$USER->IsAuthorized() || !in_array("9", $arGroups)): ?>
                                        <?$APPLICATION->IncludeComponent(
                                            "cit:booking.condition.checkboxes",
                                            "",
                                            Array(
                                                "CACHE_TIME" => "36000000",
                                                "CACHE_TYPE" => "A",
                                                "DETAIL_URL" => "",
                                                "IBLOCK_ID" => BOOKING_CONDITION_IBLOCK_ID,
                                                "IBLOCK_TYPE" => "additional_settings"
                                            )
                                        );?>
                                    <? endif ?>
                                    <button name="make_booking[submit]" id="booking_now" value="submit" type="submit" class="awe-btn awe-btn-1 awe-btn-lager" ><?= Loc::getMessage("BOOKING_BTN") ?></button>
                                </div>
                            </form>
<? endif ?>
                        </div>
                        </div>
                        </div>

                        <script>
                            (function ($) {

                            
                            $.make_booking_component_dialog({
                            email_input_id: "main-email",
                                    mount_point_id: "phone-email",
                                    booking_form_id: "booking",
                                    booking_btn_id: "booking_now",
                                    empty_cart_message: '<?= _sa(Loc::getMessage("EMPTY_CART"), $alertMessageTpl) ?>',
                                    active_service_count: <?= $active_service_count > 0 ? $active_service_count : 0 ?>,
                                    cart_item_class_container: "item-cart",
                                    cart_items_count: <?
$cnt = $arResult["BASKET"]->count();
echo ($cnt > 0 ? $cnt : 0)
?>,
                                    preloader: "<?= $templateFolder ?>/25.gif",
                                    del_pos: <?= Bitrix\Main\Web\Json::encode($arDelPos) ?>,
                                    sessid: "<?= bitrix_sessid() ?>",
                                    form_shading_selector: "#form-shading",
<?
// промо
if ($cnt > 0/* && $USER->IsAdmin() */):
    ?>
                                promoSelectorBtn: "button[name=promo_apply]",
                                        totalAreaSelector: "#total-area",
                                        totalAreaSelectorFooter: "#total-area-footer",
                                        discountAreaSelector: "#discount-area",
                                        costAreaSelector: "#cost-area",
                                        promoAreaSelector: "#applyed-promo-area",
<? endif; ?>
                            tourists_fields_selector: "[name^='make_booking[tourist]']",
                                    citizenships: <?= \Bitrix\Main\Web\Json::encode($arResult["CITIZENSHIPS"]) ?>,
<? if ($total_people_count > $max_people): ?>
                                additional_tourists: {
                                count: <?= $total_people_count - $max_people ?>,
                                        index: <?= $max_people ?>,
                                        template: '<div class="col-md-12 tourist-block">' +
                                        '<h3><?= Loc::getMessage("TOURIST") ?> #number#</h3>' +
                                        '<div class="delete-blocktourist"><span><?= Loc::getMessage('DELETE_TOURIST_BTN_TITLE') ?></span></div>' +
                                        '<div class="form-field col-md-3"><span><?= Loc::getMessage("PLACEHOLDER_NAME") ?></span>' +
                                        '<input data-error-type="WRONG_NAME" value="" type="text" name="make_booking[tourist][#index#][name]" placeholder="" class="field-input to-validate">' +
                                        '</div>' +
                                        '<div class="form-field col-md-3"><span><?= Loc::getMessage("PLACEHOLDER_LAST_NAME") ?></span>' +
                                        '<input  data-error-type="WRONG_LAST_NAME" value="" type="text" name="make_booking[tourist][#index#][last_name]" placeholder="" class="field-input to-validate">' +
                                        '</div>' +
                                        '<div class="form-field col-md-3  male-field"><span><?= Loc::getMessage("SEX") ?></span><br>' +
                                        '<select data-error-type="WRONG_MALE" class="to-validate" name="make_booking[tourist][#index#][male]">' +
    <?
    $options = '';
    foreach ($arResult["MALE"] as $val => $title) {
        $options .= '<option value="' . $val . '">' . Loc::getMessage($title) . '</option>';
    }
    ?>
                                '<?= $options ?>' +
                                        '</select>' +
                                        '</div>' +
                                        '<div class="form-field col-md-3"><span><?= Loc::getMessage("PLACEHOLDER_BIRTHDATE") ?></span>' +
                                        '<input data-error-type="WRONG_BIRTHDATE" value="" pattern="<?= str_replace('\\', '\\\\', $arResult["PATTERNS"]["birthdate"]) ?>" name="make_booking[tourist][#index#][birthdate]" type="text" placeholder="<?= Loc::getMessage("DDMMYYYY") ?>" class="birthdate-field field-input to-validate">' +
                                        '</div>' +
                                        '<div class="form-field col-md-3"><span><?= Loc::getMessage("PLACEHOLDER_PASSPORT") ?></span>' +
                                        '<input data-error-type="WRONG_PASSPORT" value="" name="make_booking[tourist][#index#][passport]" type="text" placeholder="" class="field-input to-validate">' +
                                        '</div>' +
                                        '<div class="form-field col-md-3 citizen-field"><span><?= Loc::getMessage("PLACEHOLDER_CITIZEN") ?></span><br>' +
                                        '<select data-error-type="WRONG_CITIZENSHIP" title="<?=Loc::getMessage("PLACEHOLDER_CITIZEN_SELECT")?>" class="to-validate selectpicker" data-live-search="true" name="make_booking[tourist][#index#][citizenship]">' +
    <?
    $options = '';
    foreach ($arResult["CITIZENSHIPS"] as $ID => $name) {

        $options .= '<option value=\"' . $ID . '\">' . $name . '</option>';
    }
    ?>
                                "<?= $options ?>" +
                                        '</select>' +
                                        '</div>' +
                                        '</div>'
                                },
<? endif ?>
                            is_authorized: <? if ($arResult["USER"]["is_authorized"]): ?>true<? else: ?>false<? endif ?>,
                                        is_agent: <? if ($USER->IsAuthorized() && in_array("9", $arGroups)): ?>true<? else: ?>false<? endif ?>,
                                                    forgot_password_link: "/private-office/user-profile/index.php?forgot_password=yes",
                                                    messages: {
                                                    booking_btn: "<?= Loc::getMessage("BOOKING_BTN") ?>",
                                                            no_promo: "<?= Loc::getMessage("NO_PROMO_APPLYED") ?>",
                                                            empty_email: "<?= Loc::getMessage("DIALOG_EMPTY_EMAIL") ?>",
                                                            confirm_email_error: "<?= Loc::getMessage("DIALOG_CONFIRM_EMAIL_ERROR") ?>",
                                                            confirm_email_not_equal_email: "<?= Loc::getMessage("DIALOG_CONFIRM_EMAIL_NOT_EQUAL_EMAIL") ?>",
                                                            placeholder_confirm_email: "<?= Loc::getMessage("DIALOG_PLACEHOLDER_CONFIRM_EMAIL") ?>",
                                                            placeholder_password: "<?= Loc::getMessage("DIALOG_PLACEHOLDER_PASSWORD") ?>",
                                                            do_registration_button: "<?= Loc::getMessage("DIALOG_DO_REGISTRATION_BTN") ?>",
                                                            do_authorize_button: "<?= Loc::getMessage("DIALOG_DO_AUTHORIZE_BTN") ?>",
                                                            forgot_password: "<?= Loc::getMessage("DIALOG_FORGOT_PASSWORD") ?>",
                                                            enter_the_password: "<?= Loc::getMessage("DIALOG_ENTER_THE_PASSWORD") ?>",
                                                            _404: "<?= Loc::getMessage("DIALOG_404") ?>",
                                                            _500: "<?= Loc::getMessage("DIALOG_505") ?>",
                                                            default_error_text: "<?= Loc::getMessage("DIALOG_DEFAULT_ERROR_TEXT") ?>",
                                                            status_0: "<?= Loc::getMessage("DIALOG_STATUS_0") ?>",
                                                            status_1: "<?= Loc::getMessage("DIALOG_STATUS_1") ?>",
                                                            status_2: "<?= Loc::getMessage("DIALOG_STATUS_2") ?>",
                                                            status_3: "<?= Loc::getMessage("DIALOG_STATUS_3") ?>",
                                                            status_4: "<?= Loc::getMessage("DIALOG_STATUS_4") ?>",
                                                            status_5: "<?= Loc::getMessage("DIALOG_STATUS_5") ?>",
                                                            status_6: "<?= Loc::getMessage("DIALOG_STATUS_6") ?>",
                                                            status_7: "<?= Loc::getMessage("DIALOG_STATUS_7") ?>",
                                                            status_8: "<?= Loc::getMessage("DIALOG_STATUS_8") ?>",
                                                            status_9: "<?= Loc::getMessage("DIALOG_STATUS_9") ?>",
<? if ($cnt > 0/* && $USER->IsAdmin() */): ?>
                                                        status_10: "<?= Loc::getMessage("DIALOG_STATUS_10") ?>",
                                                                status_11: "<?= Loc::getMessage("DIALOG_STATUS_11") ?>",
                                                                status_12: "<?= Loc::getMessage("DIALOG_STATUS_12") ?>",
                                                                status_13: "<?= Loc::getMessage("DIALOG_STATUS_13") ?>",
                                                                status_14: "<?= Loc::getMessage("DIALOG_STATUS_14") ?>",
                                                                status_15: "<?= Loc::getMessage("DIALOG_STATUS_15") ?>",
                                                                status_16: "<?= Loc::getMessage("DIALOG_STATUS_16") ?>",
                                                                status_17: "<?= Loc::getMessage("DIALOG_STATUS_17") ?>",
                                                                status_18: "<?= Loc::getMessage("DIALOG_STATUS_18") ?>",
<? endif ?>
                                                    citizen_popup: {
                                                    date_from: "<?= GetMessage('DATE_FROM') ?>",
                                                            date_to: "<?= GetMessage('DATE_TO') ?>",
                                                            adults: "<?= GetMessage('ADULTS') ?>",
                                                            children: "<?= GetMessage('CHILDREN') ?>",
                                                            choose_tourists: "<?= GetMessage("CITIZEN_POPUP_CHOOSE_TOURISTS") ?>",
                                                            choose_adults: "<?= GetMessage("CITIZEN_POPUP_CHOOSE_ADULTS") ?>",
                                                            choose_children: "<?= GetMessage("CITIZEN_POPUP_CHOOSE_CHILDREN") ?>",
                                                            continue_booking: "<?= GetMessage("CITIZEN_POPUP_CONTINUE_BOOKING") ?>",
                                                            title: "<?= GetMessage("CITIZEN_POPUP_TITLE") ?>",
                                                            not_avail_offer: "<?= GetMessage("CITIZEN_POPUP_NOT_AVAIL_OFFER") ?>",
                                                            recalculate: "<?= GetMessage("CITIZEN_POPUP_RECALCULATE") ?>",
                                                            not_choose_tourist: "<?= GetMessage("CITIZEN_POPUP_NOT_CHOOSE_TOURIST") ?>",
                                                            recalculated: "<?= GetMessage("CITIZEN_POPUP_NOT_CHOOSE_TOURIST") ?>",
                                                            name: "<?= GetMessage("NAME") ?>",
                                                            rate: "<?= GetMessage("RATE") ?>",
                                                            cost_before: "<?= GetMessage("CITIZEN_POPUP_COST_BEFORE") ?>",
                                                            cost_after: "<?= GetMessage("CITIZEN_POPUP_COST_AFTER") ?>",
                                                            cost: "<?= GetMessage("PRICE") ?>",
                                                            discount: "<?= GetMessage("DISCOUNT") ?>",
                                                            total_cost: "<?= GetMessage("TOTAL_COST") ?>",
                                                            cost: "<?= GetMessage("PRICE") ?>",
                                                            close: "<?= GetMessage("CITIZEN_POPUP_CLOSE") ?>",
                                                    }
                                                    },
                                                    sendBeforeForm: function (data) {

                                                    function scrollTo(element) {
                                                    $('html, body').animate({
                                                    scrollTop: $(element).offset().top - 110
                                                    }, 1000);
                                                    }

                                                    var errorsContainer = null;
                                                    var $first_tourist_citizenship = null;
                                                    if (!data.is_authorized) {
                                                    alert("<?= Loc::getMessage("MUST_BE_AUTHORIZE_NOTIFY") ?>");
                                                    return false;
                                                    }

                                                    if (!data.active_service_count) {
                                                    alert("<?= Loc::getMessage("NO_ACTIVE_SERVICES_NOTIFY") ?>");
                                                    return false;
                                                    }

                                                    // VALIDATION FIELDS
                                                    $(".error-container").remove();
                                                    $(".to-validate").each(function () {

                                                    var $this = $(this), val = $(this).val(), errorType = $this.data("error-type"), error = null;
                                                    if (errorType == "WRONG_NAME") {
                                                    if (val.length <= 0) {
                                                    error = "<? _se(Loc::getMessage("WRONG_NAME")) ?>";
                                                    }
                                                    } else if (errorType == "WRONG_LAST_NAME") {
                                                    if (val.length <= 0) {
                                                    error = "<? _se(Loc::getMessage("WRONG_LAST_NAME")) ?>";
                                                    }
                                                    } else if (errorType == "WRONG_MALE") {
                                                    if (!val) {
                                                    error = "<? _se(Loc::getMessage("WRONG_MALE")) ?>";
                                                    }
                                                    } else if (errorType == "WRONG_BIRTHDATE") {
                                                    if (!val) {
                                                    error = "<? _se(Loc::getMessage("WRONG_BIRTHDATE")) ?>";
                                                    }
                                                    } else if (errorType == "WRONG_PASSPORT") {
                                                    if (!val) {
                                                    error = "<? _se(Loc::getMessage("WRONG_PASSPORT")) ?>";
                                                    }
                                                    } else if (errorType == "WRONG_CITIZENSHIP") {
                                                    if (!val) {
                                                    error = "<? _se(Loc::getMessage("WRONG_CITIZENSHIP")) ?>"
                                                    }
                                                    } else if (errorType == "WRONG_EMAIL") {
                                                    if (!val) {
                                                    error = "<? _se(Loc::getMessage("WRONG_EMAIL"), false) ?>";
                                                    }
                                                    } else if (errorType == "WRONG_PHONE") {
                                                    if (!val) {
                                                    error = "<? _se(Loc::getMessage("WRONG_PHONE"), false) ?>";
                                                    }
                                                    } else if (errorType == "WRONG_ACCEPT" && !data.is_agent) {
                                                    if (!$this.is(":checked")) {
                                                    error = "<? _se(Loc::getMessage("WRONG_ACCEPT")) ?>";
                                                        let error_text = $(this).data('error-text');
                                                        error = "<span class='error-container' style='color:red;font-size: 11px'><br>" + error_text +  "<br></span>";
                                                    }
                                                    }

                                                    if (error) {
                                                    $this.before(error);
                                                    }

                                                    });
                                                    // проверка необходимости конвертации корзины в BYN
<? if (travelsoft\booking\Utils::getCurrentCurrency()["iso"] !== "BYN"): ?>
                                                        $first_tourist_citizenship = $("select[name='make_booking[tourist][0][citizenship]']");
                                                        if ($first_tourist_citizenship.val() == "1") {
                                                        if (!confirm("<?= Loc::getMessage("BYN_CONVERTER_TEXT") ?>")) {
                                                        $first_tourist_citizenship.before("<? _se("", false) ?>");
                                                        }
                                                        }
<? endif; ?>

                                                    errorsContainer = $(".error-container");
                                                    if (errorsContainer.length) {
                                                    scrollTo(errorsContainer.get(0));
                                                    return false;
                                                    }


                                                    $('#form-shading').show();
                                                    return true;
                                                    }
                                            });
<? if ($arParams["INC_JQUERY_MASKEDINPUT"] == "Y"): ?>
                                                $(".birthdate-field").mask("99.99.9999");
<? endif ?>

                                            $("#show-popup, .show-popup").magnificPopup({
                                            type: "ajax",
                                                    midClick: true
                                            });
                                            $('.hint').each(function () {
                                            $(this).webuiPopover({
                                            content: $(this).data('hint'),
                                                    width: $(window).outerWidth() <= 390 ? "200px" : "300px",
                                                    trigger: $(window).outerWidth() <= 990 ? 'click' : 'hover',
                                                    placement: "auto"
                                            })
                                            });
                                            $('.have-promo').on('click', function () {
                                            var $this = $(this);
                                            if ($this.is(":checked")) {
                                            $('.promo-input-area').removeClass("hidden");
                                            } else {
                                            $('.promo-input-area').addClass("hidden");
                                            }
                                            });
                                            $('#customer_phone').on('change', function () {
                                                $('#phone-email').val('+'+$( "input[name='__phone_prefix']" ).val()+$(this).val());
                                            });
                                           })(jQuery);
                                           $(window).load(function() {
                                                $(function(){
                                                    $('#customer_phone').phonecode({
                                                        preferCo: 'by'
                                                    });
                                                });
                                                
                                                <?= Loc::getMessage("YA_GOAL_OPN_PAGE") ?>
                                            });
											
											$.fn.setCursorPosition = function(pos) {
											if ($(this).get(0).setSelectionRange) {
											  $(this).get(0).setSelectionRange(pos, pos);
												} else if ($(this).get(0).createTextRange) {
												  var range = $(this).get(0).createTextRange();
												  range.collapse(true);
												  range.moveEnd('character', pos);
												  range.moveStart('character', pos);
												  range.select();
												}
											  };
										  $('input[name="customer_phone"]').on('click', function(){
											$(this).setCursorPosition(1);  // set position number
										  }); 
										  
										  

                                            
                        </script>