<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(false);
use Bitrix\Main\Web\Json;
$this->addExternalCss(SITE_TEMPLATE_PATH . "/js/rangePicker/jquery.comiseo.daterangepicker.css");
$this->addExternalCss(SITE_TEMPLATE_PATH . "/js/rangePicker/jquery.comiseo.daterangepicker.js");
$this->addExternalCss($templateFolder . "/style_content_".LANGUAGE_ID.".css");
if (!function_exists("setTabCurrentValues")) {
    /**
     * @param boolean $active_tab
     * @param array $__request
     * @param array $age_tpls
     * @return array
     */
    function setTabCurrentValues ($active_tab = false, $__request, $age_tpls = null) {

        $vars["active_tab"] = $active_tab;
        $vars["value"] = ($active_tab === true) ? ( !empty($__request["id"][0] ) ? "value='" . $__request["id"][0] . "'" : "" ) : "";
		$vars["date_from_value"] = ($active_tab === true) ? ($__request["date_from"] > 0 ? "value='" . $__request["date_from"] ."'" : "value='".time()."'" ) : "value='".time()."'";
        $vars["date_to_value"] = ($active_tab === true) ? ($__request["date_to"] > 0 ? "value='" . $__request["date_to"] . "'" : "value='".(time() + 86400)."'") : "value='".(time() + 86400)."'";
        $vars["adults"] = ($active_tab === true && $__request["adults"]) ? $__request["adults"] : null;
        $vars["children"] = ($active_tab === true) ? ($__request["children"] ? $__request["children"] : null) : null;

        $vars["show_children_ages"] = "";
        if ($active_tab === true && $vars["children"] && $age_tpls) {
            for ($i = 1; $i <= $vars["children"]; $i++) {

                $vars["show_children_ages"] .= str_replace("#N#", $i, $age_tpls[1]);

            }

            $vars["show_children_ages"]  = str_replace("#AGE_SELECTORS#", $vars["show_children_ages"], $age_tpls[0]);

            if ($__request["children_age"]) {

                $vars["show_children_ages"] .= "<script>(function ($) {";

                for ($i = 0, $cnt = count($__request["children_age"]); $i < $cnt; $i++) {

                    $vars["show_children_ages"] .= "$('#age_selector__".($i + 1)."').val(".$__request["children_age"][$i].");";

                }

                $vars["show_children_ages"] .= "$('#age_selector__".$i."').closest('.age-container').hide()";
                $vars["show_children_ages"] .= "})(jQuery)</script>";

            }

        }

        return $vars;

    }
}

$_SESSION['TSBSFC_searchFor'] = $_SESSION['TSBSFC_additionalGet'] = null;
if (!function_exists("setInSession")) {

    /**
     * устанавливает переменные в сессию, которые используются ./ajax.php
     * @param array $sessionParams
     * @param string $type
     */
    function setInSession ($sessionParams, $type) {

        if (!$_SESSION['TSBSFC_searchFor']) {
            #сохраняем массив id инфоблоков по которым производим поиск в сессию
            $_SESSION['TSBSFC_searchFor'] = array();
        }

        $_SESSION['TSBSFC_searchFor'] = array_merge($_SESSION['TSBSFC_searchFor'], array_keys($sessionParams));

        if (!$_SESSION['TSBSFC_additionalGet'][$type]) {
            #сохраняем массив дополнительно получаемых параметров в сессию
            $_SESSION['TSBSFC_additionalGet'][$type] = array();
        }

        foreach ($sessionParams as $iblockId => $arAdditionalGet) {
            if (!empty($arAdditionalGet)) {
                $_SESSION['TSBSFC_additionalGet'][$type][$iblockId] = $arAdditionalGet;
            }
        }

    }

}

if (!function_exists("getSessionParams")) {

    /**
     * Возвращает переменные для утсановки в сессию
     * @param array $inputParams
     * @param string $prefix
     * @return array
     */
    function getSessionParams (array $inputParams, string $prefix) {

        $params = null;

        foreach ($inputParams as $key => $val) {

            if (strpos($key, $prefix . "_additional_show_", 0) === 0) {
                if ( ( $iblockId = array_pop( explode("_", $key) ) ) ) {
                    $params[$iblockId] = $val;
                }
            }

        }

        return $params;

    }
}

$age_tpls = array("<div class='age-container'><div class='age-wrapper'><span class='age-title'>".GetMessage("AGE_TITLE")."</span><hr><div class='age-closer'>&times;</div><div class='ages'>#AGE_SELECTORS#</div></div></div>", getSelectAgeTpl());

$time = time();
$now = mktime(0, 0, 0, date("m", $time), date("d", $time), date("Y", $time));
?>
<div id="blok_js_form_search"></div>
<?ob_start();?>
<div id="searchformtab" class="banner-cn">

    <!-- Tabs Cat Form -->
    <?/*<ul class="tabs-cat text-center row tabs-cat_top">
        <li class="cate-item <?if ($arParams["active_subtab"] === "sights_tab"):?>active<?endif?> col-xs-2">
            <a href="<?=SITE_DIR?>belarus/what-to-see/" title="">
                <span><?=GetMessage('T_SEARCHFORM_SIGHTS')?></span>
            </a>
        </li>
        <li class="cate-item <?if ($arParams["active_subtab"] === "guides_tab"):?>active<?endif?> col-xs-2">
            <a href="<?=SITE_DIR?>tourism/guides-new/" title="">
                <span><?=GetMessage('T_SEARCHFORM_GUIDES')?></span>
            </a>
        </li>
        <li class="cate-item <?if ($arParams["active_subtab"] === "tickets_tab"):?>active<?endif?> col-xs-2">
            <a href="<?=SITE_DIR?>event-tickets/" title="">
                <span><?=GetMessage('T_SEARCHFORM_TICKETS')?></span>
            </a>
        </li>
        <li class="cate-item <?if ($arParams["active_subtab"] === "certificate_tab"):?>active<?endif?> col-xs-2">
            <a href="<?=SITE_DIR?>tourist/certificate/" title="">
                <span><?=GetMessage('T_SEARCHFORM_GIFTS')?></span>
            </a>
        </li>
    </ul>*/?> 
    <ul class="tabs-cat text-center row slider-nav">
	   <?if ($arParams['show_sanatorium_tab'] == "Y"):?>
			<div class="item">	
				<li class="cate-item <?if ($arParams["active_tab"] == "sanatorium_tab"):?>active<?endif?> col-xs-2" data-tab="tab1">
					<a data-toggle="tab" href="#form-sanatorium" title=""><span><?=GetMessage("RESORTS")?></span><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-sanatorium.png" alt=""></a>
				</li>
			</div>
        <?endif;?>
		
		 <?if ($arParams['show_tours_tab'] == "Y"):?>
		    <div class="item">	
				<li class="cate-item <?if ($arParams["active_tab"] == "tours_tab"):?>active<?endif?> col-xs-2" data-tab="tab2">
					<a data-toggle="tab" href="#form-tours" title=""><span><?=GetMessage("EXCURSIONS")?></span><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-exc.png" alt=""></a>
				</li>
			</div>
        <?endif;?>
		
		<?if ($arParams['show_excursionstours_tab'] == "Y"):?>
			<div class="item">	
				<li class="cate-item <?if ($arParams["active_tab"] == "excursionstours_tab"):?>active<?endif?> col-xs-2" data-tab="tab3">
					<a data-toggle="tab" href="#form-excursionstours" title=""><span><?=GetMessage("EXCURSIONS_TOURS")?></span><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-exc.png" alt=""></a>
				</li>
			</div>
        <?endif;?>
		
        <?if ($arParams['show_placement_tab'] == "Y"):?>
            <div class="item">
				<li class="cate-item <?if ($arParams["active_tab"] == "placement_tab"):?>active<?endif?> col-xs-2" data-tab="tab4">
					<a data-toggle="tab" href="#form-placements" title=""><span><?=GetMessage("ACCOMODATION")?></span><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-hotel.png" alt=""></a>
				</li>
			</div>	
		
						 
        <?endif;?>
       
        <?if ($arParams['show_transfer_tab'] == "Y"):?>
			<div class="item">
				<li class="cate-item <?if ($arParams["active_tab"] == "transfer_tab"):?>active<?endif?> col-xs-2" data-tab="tab5">
					<a data-toggle="tab" href="#form-transfer" title=""><span><?=GetMessage("TRANSFERS")?></span><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-car.png" alt=""></a>
				</li>
			</div>
        <?endif?>

		<?if ($arParams['show_avia_tab'] == "Y"):?>
			<div class="item">
				<li class="cate-item <?if ($arParams["active_tab"] == "avia_tab"):?>active<?endif?> col-xs-2" data-tab="tab5">
					<a data-toggle="tab" href="#form-transfer" title=""><span><?=GetMessage("TRANSFERS")?></span><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-car.png" alt=""></a>
				</li>
			</div>
        <?endif?>

        <div class="item">
          <li class="cate-item <?if ($arParams["active_tab"] == "placement_tab"):?>active<?endif?> col-xs-2" data-tab="tab6">
            <a data-toggle="tab" href="#belavia_booking" title=""><span><?=GetMessage("belavia_booking")?></span><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-hotel.png" alt=""></a>
          </li>
        </div>  

    </ul>
    <!-- End Tabs Cat -->

    <!-- Tabs Content -->
    <div class="tab-content main-tabs-search slider-for">
         <!-- <div id="root"></div> -->
		 
		   <?if ($arParams['show_sanatorium_tab'] == "Y"):
            $arPlacementsSessionParams = getSessionParams ($arParams, "sanatorium");
            setInSession($arPlacementsSessionParams, "sanatorium");
            extract(setTabCurrentValues("sanatorium_tab" == $arParams["active_tab"], $arResult["__get"], $age_tpls), EXTR_OVERWRITE);
            ?>
            <!-- Search Sanatorium -->
            <div class="form-cn form-hotel tab-pane <?if ($active_tab):?> in active <?endif?>" id="form-sanatorium" data-id="tab1">
                <div class="form-search wrap">
                    <form autocomplete="off" action="<?= $arParams['sanatorium_result_page']?>"  method="get" class="box">

                        <input name="scroll-to-sp" type="hidden" value="Y">


                        <div class="form-field field-destination col-all__24 col-lg__6">
                            <label style="<?if (strlen($value) > 0):?>display:none;<?endif?>padding-right:0px; max-width: 80%" for="autocomplete-field"><?=GetMessage("CMPR")?></label>
                            <input <?= $value?> type="hidden" name="booking[id][0]" class="field-input">
                            <input type="text" name="autocomplete-field" class="field-input search-text-input">
                        </div>
						<img width="20px" class="input_cross" src="/local/components/travelsoft/travelsoft.booking.searchform/templates/main.tabs/input_cross.svg">

                        <div class="form-field field-date col-all__24 col-lg__8" id="sanatorium-date-range-container">
                            <?
                            $dateRange = unserialize(\Bitrix\Main\Config\Option::get('travelsoft.booking.dev.tools', "sanatoriumDateRange"));
                            ?>
                            <div class="box_date_from">
                                <input readonly <?= $date_from_value?>  class ='calendar-input date_from  field-input ' data-date="<?= $now + (86400*$dateRange[0])?>"  >
                            </div>
                            <div class="box_date_to">
                                <input readonly <?= $date_to_value?> class="calendar-input date_to   field-input " data-date="<?= $now + (86400*($dateRange[1] + $dateRange[0]))?>">
                            </div>
                            <input readonly type="hidden" name="booking[date_from]" <?= $date_from_value?>>

                            <input readonly type="hidden" name="booking[date_to]" <?= $date_to_value?>>
                            <!-- <input readonly="" required type="text" class="field-input calendar-input" placeholder="<?=GetMessage("PERIODSTAY")?>"> -->
                        </div>
                        <div class="form-field field-select field-adults col-all__24 col-sm__12 col-lg__3">
                            <div class="select" data-currentvalue="<?=$adults?>">
                                <?php if($children) :?>
                                    <?= getAdultsSelectOption($adults ? $adults : 0, true)?>
                                <?php else : ?>
                                    <?= getAdultsSelectOption($adults ? $adults : 2, true)?>   
                                <?php endif; ?>  
                            </div>
                        </div>
                        <div class="form-field field-select field-children col-all__24 col-sm__12 col-lg__3">
                            <div class="select" data-currentvalue="<?=$children?>">
                                <?= getChildrenSelectOption($children)?>
                            </div>
                            <?= $show_children_ages?>
                        </div>
                        <div class="form-submit col-all__24 col-lg__4">
                            <button type="submit" class="awe-btn awe-btn-lager awe-search"><?=GetMessage("CHOOSE_OPTIONS")?></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Search Sanatorium -->
        <?endif?>
		
		
		<?if ($arParams['show_tours_tab'] == "Y"):
            $arPlacementsSessionParams = getSessionParams ($arParams, "tours");
            setInSession($arPlacementsSessionParams, "excursions");

            extract(setTabCurrentValues("tours_tab" == $arParams["active_tab"], $arResult["__get"], $age_tpls), EXTR_OVERWRITE);
            ?>
            <!-- Search Tours -->
            <div class="form-cn form-package tab-pane <?if ($active_tab):?> in active <?endif?>" id="form-tours" data-id="tab2">
                <div class="form-search wrap">
                    <form autocomplete="off" action="<?= $arParams['tours_result_page']?>"  method="get" class="box">
                        <input name="scroll-to-sp" type="hidden" value="Y">
                        <div class="form-field field-to col-all__24 col-lg__6">
                            <label style="<?if (strlen($value) > 0):?>display:none;<?endif?>padding-right:0px; max-width: 80%" for="autocomplete-field"><?=GetMessage("CITYATTRACTIONS")?></label>
                            <input <?= $value?> type="hidden" name="booking[id][0]" class="field-input search-text-input">
                            <input type="text" name="autocomplete-field" class="field-input search-text-input">
                        </div>
						<img width="20px" class="input_cross" src="/local/components/travelsoft/travelsoft.booking.searchform/templates/main.tabs/input_cross.svg">

                        <div class="form-field field-date col-all__24 col-lg__8" id="excursions-date-range-container">
                            <?
                            $dateRange = unserialize(\Bitrix\Main\Config\Option::get('travelsoft.booking.dev.tools', "excursionsDateRange"));
                            ?>
                            <div class="box_date_from">
                                <input readonly <?= $date_from_value?>  class ='calendar-input date_from  field-input ' data-date="<?= $now + (86400*$dateRange[0])?>"  >
                            </div>
                            <div class="box_date_to">
                                <input readonly <?= $date_to_value?> class="calendar-input date_to   field-input " data-date="<?= $now + (86400*($dateRange[1] + $dateRange[0]))?>">
                            </div>
                            <input type="hidden" name="booking[date_from]" <?= $date_from_value?>>

                            <input type="hidden" name="booking[date_to]" <?= $date_to_value?>>
                            <!--<input required readonly="" type="text" class="field-input calendar-input" placeholder="<?=GetMessage("TOURDATES")?>"> -->
                        </div>
                        <div class="form-field field-select field-adults col-all__24 col-sm__12 col-lg__3">
                            <div class="select" data-currentvalue="<?=$adults?>"> 
                                <?= getAdultsSelectOption($adults ? $adults : 1)?>
                            </div>
                        </div>
                        <div class="form-field field-select field-children col-all__24 col-sm__12 col-lg__3">
                            <div class="select" data-currentvalue="<?=$children?>">
                                <?= getChildrenSelectOption($children)?>
                            </div>
                            <?= $show_children_ages?>
                        </div>
                        <div class="form-submit col-all__24 col-lg__4">
                            <button type="submit" class="awe-btn awe-btn-medium awe-search"><?=GetMessage("CHOOSE_OPTIONS")?></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Search Tours -->
        <?endif?>

		
		 <?if ($arParams['show_excursionstours_tab'] == "Y"):
            $arPlacementsSessionParams = getSessionParams ($arParams, "excursionstours");
            setInSession($arPlacementsSessionParams, "excursionstours");
            extract(setTabCurrentValues("excursionstours_tab" == $arParams["active_tab"], $arResult["__get"], $age_tpls), EXTR_OVERWRITE);
            ?>
            <!-- Search Tours -->
            <div class="form-cn form-package tab-pane <?if ($active_tab):?> in active <?endif?>" id="form-excursionstours" data-id="tab3">
                <div class="form-search wrap">
                    <form autocomplete="off" action="<?= $arParams['excursionstours_result_page']?>"  method="get" class="box">
                        <input name="scroll-to-sp" type="hidden" value="Y">
                        <div class="form-field field-to col-all__24 col-lg__6">
                            <label style="<?if (strlen($value) > 0):?>display:none;<?endif?>padding-right:0px; max-width: 80%" for="autocomplete-field"><?=GetMessage("TOURNAME")?></label>
                            <input <?= $value?> type="hidden" name="booking[id][0]" class="field-input search-text-input">
                            <input type="text" name="autocomplete-field" class="field-input search-text-input">
                        </div>
						<img width="20px" class="input_cross" src="/local/components/travelsoft/travelsoft.booking.searchform/templates/main.tabs/input_cross.svg">

                        <div class="form-field field-date col-all__24 col-lg__8" id="excursionstours-date-range-container">
                            <?
                            $dateRange = unserialize(\Bitrix\Main\Config\Option::get('travelsoft.booking.dev.tools', "excursionsDateRange"));
                            ?>
                            <div class="box_date_from">
                                <input readonly <?= $date_from_value?>  class ='calendar-input date_from  field-input ' data-date="<?= $now + (86400*$dateRange[0])?>"  >
                            </div>
                            <div class="box_date_to">
                                <input readonly <?= $date_to_value?> class="calendar-input date_to   field-input " data-date="<?= $now + (86400*($dateRange[1] + $dateRange[0]))?>">
                            </div>
                            <input readonly type="hidden" name="booking[date_from]" <?= $date_from_value?>>

                            <input readonly type="hidden" name="booking[date_to]" <?= $date_to_value?>>
                        </div>
                        <div class="form-field field-select field-adults col-all__24 col-sm__12 col-lg__3">
                            <div class="select" data-currentvalue="<?=$adults?>">
                                <?= getAdultsSelectOption($adults ? $adults : 1)?>
                            </div>
                        </div>
                        <div class="form-field field-select field-children col-all__24 col-sm__12 col-lg__3">
                            <div class="select" data-currentvalue="<?=$children?>">
                                <?= getChildrenSelectOption($children)?>
                            </div>
                            <?= $show_children_ages?>
                        </div>
                        <div class="form-submit col-all__24 col-  col-lg__4">
                            <button type="submit" class="awe-btn awe-btn-medium awe-search"><?=GetMessage("CHOOSE_OPTIONS")?></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Search Tours -->
        <?endif?>
		
		
        <?if ($arParams['show_placement_tab'] == "Y"):
            $arPlacementsSessionParams = getSessionParams ($arParams, "placement");
            setInSession($arPlacementsSessionParams, "placements");
            extract(setTabCurrentValues("placement_tab" == $arParams["active_tab"], $arResult["__get"], $age_tpls), EXTR_OVERWRITE);
            ?>
            <!-- Search Hotel -->
            <div class="form-cn form-hotel tab-pane  <?if ($active_tab):?> in active <?endif?>" id="form-placements" data-id="tab4">
                <div class="form-search wrap">
                    <form autocomplete="off" action="<?= $arParams['placement_result_page']?>" method="get" class="box">
                        <input name="scroll-to-sp" type="hidden" value="Y">

                        <div class="form-field col-all__24 col-lg__6">
                            <label style="<?if (strlen($value) > 0):?>display:none;<?endif?>padding-right:0px; max-width: 80%" for="autocomplete-field"><?=GetMessage("CHMH")?></label>
                            <input <?= $value?> type="hidden" name="booking[id][0]" class="field-input">
                            <input type="text" name="autocomplete-field" class="field-input search-text-input">
                        </div>
						<img width="20px" class="input_cross" src="/local/components/travelsoft/travelsoft.booking.searchform/templates/main.tabs/input_cross.svg">

                        <div class="form-field field-date col-all__24 col-lg__8" id="placements-date-range-container">
                            <?
                            $dateRange = unserialize(\Bitrix\Main\Config\Option::get('travelsoft.booking.dev.tools', "placementsDateRange"));
                            ?>

                            <div class="box_date_from">
                                <input readonly <?= $date_from_value?>  class ='calendar-input date_from  field-input ' data-date="<?= $now + (86400*$dateRange[0])?>"  >
                            </div>
                            <div class="box_date_to">
                                <input readonly <?= $date_to_value?> class="calendar-input date_to   field-input " data-date="<?= $now + (86400*($dateRange[1] + $dateRange[0]))?>">
                            </div>
                            <input readonly type="hidden" name="booking[date_from]" <?= $date_from_value?>>

                            <input readonly type="hidden" name="booking[date_to]" <?= $date_to_value?>>


                            <!--<input readonly required type="text" class="field-input calendar-input" placeholder="<?/*=GetMessage("PERIODSTAY")*/?>">-->
                        </div>
                        <div class="form-field field-select field-adults col-all__24 col-sm__12 col-lg__3">
                            <div class="select" data-currentvalue="<?=$adults?>">
                                <?= getAdultsSelectOption($adults ? $adults : 2)?>
                            </div>
                        </div>
                        <div class="form-field field-select field-children col-all__24 col-sm__12 col-lg__3">
                            <div class="select" data-currentvalue="<?=$children?>">
                                <?= getChildrenSelectOption($children)?>
                            </div>
                            <?= $show_children_ages?>
                        </div>
                        <div class="form-submit col-all__24 col-lg__4">
                            <button type="submit" class="awe-btn awe-btn-lager awe-search"><?=GetMessage("CHOOSE_OPTIONS")?></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Search Hotel -->
        <?endif;?>


        <?if ($arParams['show_transfer_tab'] == "Y"):
            $this->addExternalCss(SITE_TEMPLATE_PATH . "/css/select2/select2.min.css");
            $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/select2/select2.min.js");


            $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/dateTime/jquery.datetimepicker.min.js");
            $arPlacementsSessionParams = getSessionParams ($arParams, "transfer");
            setInSession($arPlacementsSessionParams, "transfers");
            extract(setTabCurrentValues("transfer_tab" == $arParams["active_tab"], $arResult["__get"], $age_tpls), EXTR_OVERWRITE);
            ?>
            <!-- Search Transfer-->
            <div class="form-cn form-car tab-pane <?if ($active_tab):?> in active <?endif?>" id="form-transfer" data-id="tab5">
                <form autocomplete="off" action="<?= $arParams['transfer_result_page']?>"  method="get">
                    <input name="scroll-to-sp" type="hidden" value="Y">
                    <div class="roundtrip-container wrap"><input <?if ($_REQUEST["booking"]["roundtrip"] == "Y") {echo "checked";}?> name="booking[roundtrip]" value="Y" type="checkbox"><label for="roundtrip"><?=GetMessage("ROUNDTRIP")?></label></div>
                    <div class="form-search wrap">
                        <div class="box">
                            <div class="form-field field-from col-all__24 col-sm__12 col-lg__4">
                                <input
                                        required="required"
                                        data-placeholder="<?= GetMessage("POINT_DEPARTURE")?>"
                                        data-link="#select2-to"
                                        data-default="<?= htmlspecialchars($_REQUEST["booking"]["point_A"])?>"
                                        data-not-set-val="<?= htmlspecialchars($_REQUEST["booking"]["point_B"])?>"
                                        name="booking[point_A]"
                                        id="select2-from">
                            </div>
                            <div class="form-field field-to col-all__24 col-sm__12 col-lg__4">
                                <input
                                        required="required"
                                        data-placeholder="<?= GetMessage("POINT_ARRIVAL")?>"
                                        data-link="#select2-from"
                                        data-default="<?= htmlspecialchars($_REQUEST["booking"]["point_B"])?>"
                                        data-not-set-val="<?= htmlspecialchars($_REQUEST["booking"]["point_A"])?>"
                                        name="booking[point_B]"
                                        id="select2-to">
                            </div>
                            <div id="transfers-date-from" class="form-field field-date col-all__24 col-sm__12 col-lg__4" >

                                <?$dateRange = unserialize(\Bitrix\Main\Config\Option::get('travelsoft.booking.dev.tools', "excursionsDateRange"));?>
                                <input readonly <?= $date_from_value?>  class ='calendar-input date_from  field-input ' style="width: 100%" data-date="<?= $now + (86400*$dateRange[0])?>"  >
                                <input readonly type="hidden" name="booking[date_from]" <?= $date_from_value?>>

                                <!--<input readonly required type="text" data-remove-class="date" data-single-date-picker="Y" class="field-input calendar-input" placeholder="<?=GetMessage("DATE_FROM")?>">-->
                            </div>
                            
                            <?if (empty($arResult["__get"]["date_to"])) $date_to_value = 'value=""';?>
                            <div id="transfers-date-to" class="form-field field-date col-all__24 col-sm__12 col-lg__4 <?if ($_REQUEST["booking"]["roundtrip"] == "Y") {echo "withDate";}?>">
                                <input placeholder="<?=GetMessage('ROUNDTRIPNO')?>" onclick="checkdatepicker(true)" readonly <?= $date_to_value?>  class='<?if ("transfer_tab" == $arParams["active_tab"] && $arResult["__get"]["date_to"]):?>calendar-input<?else:?>calendar-input-custom<?endif;?> date_to field-input' style='width:100%'>
                                    <input type="hidden" name="booking[date_to]" <?= $date_to_value?>>
                            </div>
                            <div class="form-field field-select field-adults col-all__24 col-sm__12 col-lg__4">
                                <div class="select" data-currentvalue="<?=$adults?>">
                                    <?= getSelectPassengersTpl($adults ? $adults : 2)?>
                                </div>
                            </div>
                            <div class="form-submit  col-all__24  col-lg__4">
                                <button type="submit" class="awe-btn awe-btn-medium awe-search"><?=GetMessage("CHOOSE_OPTIONS")?></button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <!-- End Search Transfer -->
        <?endif?>

        <div class="form-cn form-car tab-pane <?if ($active_tab):?> in active <?endif?>" id="belavia_booking" data-id="tab6">
          <div class="belavia-search-form-loading" id="belavia-search-form-loading">
            <div class="preloader">
              <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
            </div>
          </div>
        </div>
        <script>
          $('#belavia-search-form-loading').load('/include/belavia_booking.php');
        </script>

    </div>
    <!-- End Tabs Content -->

</div>
<!-- End Banner Content -->
<?$block_search = ob_get_clean();?>
<div id="form-stub"><div>
        <?
        if ($arParams['inc_date_js']) {
            $this->addExternalCss(SITE_TEMPLATE_PATH . "/css/icomoon/style.min.css");
            $this->addExternalCss(SITE_TEMPLATE_PATH . "/css/date/daterangepicker.min.css");
            $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/date/moment.min.js");
            $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/date/moment_locales.min.js");
            $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/date/daterangepicker.js");
        }
        ?>
        <script >

            /**
             * @param {jQuery} $
             * @param {Window} window
             */
                (function ($, moment, window) {
                $(document).ready(function () {
				$('#blok_js_form_search').html(<?=json_encode($block_search)?>);   	
					
					if ($(window).width() < 960) {
						
							
						$('.tabs-cat').slick({
							arrows: true, 
							dots: false,
							infinite: true, 
							centerMode: true,
							centerPadding: 0,
							slidesToShow: 3,
							slidesToScroll: 1, 
							speed: 500, 
							asNavFor: '.slider-for',
							focusOnSelect: true,
							swipeToSlide: true,
							//cssEase: "cubic-bezier(0.77, 0, 0.175, 1)"
		
						});
						
						$('.slider-for').slick({

							  slidesToShow: 1,
							  slidesToScroll: 1,
							  arrows: false,
							  fade: true,
							  asNavFor: '.slider-nav',
							  adaptiveHeight: true,
							  swipeToSlide: true,
							 
						});
						
						
						// replace getNavigableIndexes without rehosting hack

						$(".tabs-cat, .slider-for").each(function() {
									this.slick.getNavigableIndexes = function() {

									var _ = this,
										breakPoint = 0,
										counter = 0,
										indexes = [],
										max;

									if (_.options.infinite === false) {
										max = _.slideCount;
									} else {
										breakPoint = _.options.slideCount * -1;
										counter = _.options.slideCount * -1;
										max = _.slideCount * 2;
									}

									while (breakPoint < max) {
										indexes.push(breakPoint);
										breakPoint = counter + _.options.slidesToScroll;
										counter += _.options.slidesToScroll <= _.options.slidesToShow ? _.options.slidesToScroll : _.options.slidesToShow;
									}

									return indexes;

								};
						});
						
						// replace active tabs respective to open page
						
						if (window.location.href.indexOf("/tourism/where-to-stay/") > -1) {
							  $(function () {
								const index = $('#form-placements').attr("data-slick-index");
								$('.tabs-cat').slick('slickGoTo',index,true);
								$('.slider-for').slick('slickGoTo',index,true);
							});
						}
						
						if (window.location.href.indexOf("/tourism/cognitive-tourism/") > -1) {
							  $(function () {
								const index = $('#form-tours').attr("data-slick-index");
								$('.tabs-cat').slick('slickGoTo',index,true);
								$('.slider-for').slick('slickGoTo',index,true);
							});
						}
						
						if (window.location.href.indexOf("/tourism/tours-in-belarus/") > -1) {
							  $(function () {
								const index = $('#form-excursionstours').attr("data-slick-index");
								$('.tabs-cat').slick('slickGoTo',index,true);
								$('.slider-for').slick('slickGoTo',index,true);
							});
						}
						
						if (window.location.href.indexOf("/tourism/transfer/") > -1) {
							  $(function () {
								const index = $('#form-transfer').attr("data-slick-index");
								$('.tabs-cat').slick('slickGoTo',index,true);
								$('.slider-for').slick('slickGoTo',index,true);
							});
						}
						
					
						
						/*$(".tabs-cat .item").on("click", function() {
							const index = $(this).attr("data-slick-index");
							$(".tabs-cat").slick("slickGoTo", index);
			
						}); 
						
						if ($(".cate-item").parents(".slick-current").length) {
								$elem.addClass("active");
							}
						  else {
							  $(".cate-item").removeClass("active");
						} */
						
					
				}
		
				
				if ($(window).width() > 960) {
					$('#searchformtab').each(function(){
						var tab = $(this);
						tab.find('.tab__block:first').addClass('active');
						tab.find('.cate-item').click(function(){
							tab.find('.cate-item').removeClass('active');
							//tab.find('.cate-item').removeClass('notactive-prev');
							//tab.find('.cate-item').removeClass('notactive-next');
							$(this).addClass('active');
							//$(this).parents().prev().find('.cate-item').addClass('notactive-prev');
							//$(this).parents().next().find('.cate-item').addClass('notactive-next');
							tab.find('.tab-pane').removeClass('active');
							tab.find(".tab-pane[data-id="+$(this).attr("data-tab")+"]").addClass('active');
						});
					}); 
					
					
				}
                    var
                        /**
                         * кеш инфы
                         * @type Array
                         */
                        cacheData = {},

                        /**
                         * @type Array
                         */
                        toDetailPage = <?= Json::encode($arParams['to_detail_page'])?> || [],

                        /**
                         * @type Array
                         */
                        __request_id = <?= Json::encode($arResult['__get']["id"])?> || [],

                        /**
                         * @type Object
                         */
                        curVals = {},

                        /**
                         * @type Object
                         */
                        defAge = {},

                        /**
                         * масиив инфы по табам
                         * @type Array
						 
                         */
						 
						 
                        tabs = {

                            <?if ($arParams['show_placement_tab'] == "Y"):?>
                            placement_tab: {
                                href: "#form-placements",
                                method: function (tab) {

                                    __initAutocomplete({href: tab.href, cacheKeys: tab.cacheKeys, defPage: tab.defPage, type: tab.type, onlyDefPage: false, _initMethod_: __initUIAutocomplete});

                                    tab.done = true;
                                },
                                type: "placements",
                                eventInit: "focusin",
                                done: false,
                                defPage: "<?= $arParams['placement_result_page']?>",
                                cacheKeys: <?= Json::encode($arParams['placement_tab'])?>
                            },
							

                            <?endif?>

                            <?if ($arParams['show_sanatorium_tab'] == "Y"):?>
                            sanatorium_tab: {
                                href: "#form-sanatorium",
                                method: function (tab) {
                                    __initAutocomplete({href: tab.href, cacheKeys: tab.cacheKeys, defPage: tab.defPage, type: tab.type, onlyDefPage: false, _initMethod_: __initUIAutocomplete});
                                    tab.done = true;
                                },
                                type: "sanatorium",
                                eventInit: "focusin",
                                done: false,
                                defPage: "<?= $arParams['sanatorium_result_page']?>",
                                cacheKeys: <?= Json::encode($arParams['sanatorium_tab'])?>
                            },
                            <?endif?>

                            <?if ($arParams['show_tours_tab'] == "Y"):?>
                            tours_tab: {
                                href: "#form-tours",
                                method: function (tab) {
                                    __initAutocomplete({href: tab.href, cacheKeys: tab.cacheKeys, defPage: tab.defPage, type: tab.type, onlyDefPage: false, _initMethod_: __initUIAutocomplete});
                                    tab.done = true;
                                },
                                type: "excursions",
                                eventInit: "focusin",
                                done: false,
                                defPage: "<?= $arParams['tours_result_page']?>",
                                cacheKeys: <?= Json::encode($arParams['tours_tab'])?>
                            },
                            <?endif?>

                            <?if ($arParams['show_excursionstours_tab'] == "Y"):?>
                            excursionstours_tab: {
                                href: "#form-excursionstours",
                                method: function (tab) {
                                    __initAutocomplete({href: tab.href, cacheKeys: tab.cacheKeys, defPage: tab.defPage, type: tab.type, onlyDefPage: false, _initMethod_: __initUIAutocomplete});
                                    tab.done = true;
                                },
                                type: "excursionstours",
                                eventInit: "focusin",
                                done: false,
                                defPage: "<?= $arParams['excursionstours_result_page']?>",
                                cacheKeys: <?= Json::encode($arParams['excursionstours_tab'])?>
                            },
                            <?endif?>

                            <?if ($arParams['show_transfer_tab'] == "Y"):?>
                            transfer_tab: {
                                href: "#form-transfer",
                                eventInit: null,
                                method: function (tab) {
                                    __initAutocomplete({href: tab.href, cacheKeys: tab.cacheKeys, defPage: tab.defPage, type: tab.type, onlyDefPage: true, _initMethod_: __initSelect2});
                                    tab.done = true;
                                },
                                type: "transfers",
                                done: false,
                                defPage: "<?= $arParams['transfer_result_page']?>",
                                cacheKeys: <?= Json::encode($arParams['transfer_tab'])?>
                            }
                            <?endif?>
                        },

                        /**
                         * инициализируе tab
                         * @param {object} tab
                         */
                        __initTab = function (tab) {

                            var notFound;

                            notFounded = __checkCache (tab.cacheKeys, tab.type);
                            if (!notFounded.length) {
                                tab.method(tab);
                            } else {
                                __complementCache(notFounded, tab.method, tab, tab.type);
                            }

                        },

                        /**
                         * проверка наличия инфы в кеше
                         * @param {Array} keys
                         * @returns {Array}
                         */
                        __checkCache = function (keys, type) {

                            var j, cnt = keys.length, resp = [];

                            for (j = 0; j < cnt; j++) {
                                if (typeof cacheData[type] === "undefined") {
                                    cacheData[type] = {};
                                }
                                if (typeof cacheData[type][keys[j]] === "undefined") {
                                    resp.push(keys[j]);
                                }
                            }
                            return resp;

                        },

                        /**
                         * Дополняет инфу в кеше и выполняет callback
                         * @param {Array} keys
                         * @param {function} callback
                         * @param {object} callbackParams
                         */
                        __complementCache = function (keys, callback, callbackParams, type) {

                            $.post("<?= $templateFolder?>/ajax.php", {sessid: "<?= bitrix_sessid()?>" , searchFor: keys, type: type}, function (data) {

                                var key, keys, i, index;

                                if (data) {

                                    for (key in data) {
                                        cacheData[type][key] = data[key]; keys = [];
                                        for (i = 0; i < __request_id.length; i++) {
                                            if (typeof data[key][__request_id[i]] !== "undefined") {
                                                curVals[__request_id[i]] = {
                                                    name: data[key][__request_id[i]].name,
                                                    page: typeof data[key][__request_id[i]].page !== "undefined" ? data[key][__request_id[i]].page : null,
                                                    key: key
                                                };
                                                keys.push(__request_id[i]);
                                            }
                                        }
                                        for (i = 0; i < keys.length; i++) {
                                            index = $.inArray(keys[i], __request_id);
                                            if (index !== -1) {
                                                delete(__request_id[index]);
                                            }
                                        }
                                    }

                                    if (typeof callback === "function") {
                                        callback(callbackParams);
                                    }

                                }

                            }, "json");

                        },

                        /**
                         * @param {object} ul
                         * @param {object} it
                         */
                        __highlight = function (ul, it) {

                            var v = $(this.element[0]).val(), w = it.label;

                            if (v != '') {
                                w = w.replace(new RegExp("("+$.ui.autocomplete.escapeRegex(v)+")", "ig" ), "<strong>$1</strong>");
                                
                                if (it.type !== "excursions" && it.type!=='excursionstours') {
                                    if (it.region) {
                                        w = w + ", " + it.region;
                                    }
                                    if (it.city) {
                                        w = w + ", " + it.city;
                                    }
                                }

                            }

                            return $( "<li></li>" )
                                .data( "ui-autocomplete-item", it)
                                .append( w )
                                .appendTo( ul );
                        },

                        /**
                         * инициализируем автозаполнения полей по объектам поиска
                         * @param {object} options
                         */
                        __initAutocomplete = function (options) {

                            var input = $(options.href + " input[name='autocomplete-field']"), source = [],
                                cnt = options.cacheKeys.length, key, j, values;

                            if (!input.length) {
                                input = $(options.href + " input[name^='booking[point_']");
                            }

                            if (input.length) {

                                for (j = 0; j < cnt; j++) {
                                    values = cacheData[options.type][options.cacheKeys[j]];
                                    for (key in values) {

                                        source.push({
                                            label: values[key].name,
                                            value: values[key].name,
                                            id: values[key].id,
                                            city: values[key].city,
                                            region: values[key].region,
                                            type: values[key].type,
                                            route: values[key].route,
                                            page: (typeof values[key].page !== "undefined" && !options.onlyDefPage) ? values[key].page : options.defPage
                                        });

                                    }
                                }

                                if (source) {

                                    options._initMethod_({
                                        source: source,
                                        inputs: input,
                                        onlyDefPage: options.onlyDefPage,
                                        defPage: options.defPage
                                    });

                                }
                            }

                        },
                        __customTermTranslit = function (term, lang) {

                            var translit = {

                                en: ["q","w","e","r","t","y","u","i","o","p","[","]","a","s","d","f","g","h","j","k","l",";","'","z","x","c","v","b","n","m",",",".","Q","W","E","R","T","Y","U","I","O","P","{","}","A","S","D","F","G","H","J","K","L",":","\"","Z","X","C","V","B","N","M","<",">","`","~"],
                                by: ["й","ц","у","к","е","н","г","ш","ў","з","х","'","ф","ы","в","а","п","р","о","л","д","ж","э","я","ч","с","м","і","т","ь","б","ю","Й","Ц","У","К","Е","Н","Г","Ш","Ў","З","Х","'","Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э","Я","Ч","С","М","І","Т","Ь","Б","Ю","ё","Ё"],
                                ru: ["й","ц","у","к","е","н","г","ш","щ","з","х","ъ","ф","ы","в","а","п","р","о","л","д","ж","э","я","ч","с","м","и","т","ь","б","ю","Й","Ц","У","К","Е","Н","Г","Ш","Щ","З","Х","Ъ","Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э","Я","Ч","С","М","И","Т","Ь","Б","Ю","ё","Ё"]

                            };

                            if (term && term.length) {

                                term = $.map(term.split(''), function (el) {
                                    var i = -1;
                                    for (var k in translit) {
                                        i = translit[k].indexOf(el);
                                        if (i !== -1) {
                                            return translit[lang][i]
                                        }
                                    }

                                    return '';
                                }).join('');
                            }

                            return term;

                        },
                        /**
                         * инициализируем ui.autocomplete
                         * @param {object} options
                         */
                        __initUIAutocomplete = function (options) {
                            options.inputs.each(function (i) {

                                var $this = $(this), inputWithIdName = $this.parent().find("input[name^='booking[']"), defVal = inputWithIdName.val();

                                $this.autocomplete({
                                    source: (function (data) {
                                        return function (request, response) {
                                            var term_ru = __customTermTranslit(request.term, 'ru');
                                            var term_en = __customTermTranslit(request.term, 'en');
                                            var term_by = __customTermTranslit(request.term, 'by');
                                            var source = [];

                                            for (var k in data) {
                                                if (
                                                    (data[k].label.toLowerCase().indexOf(term_ru.toLowerCase()) !== -1 ||
                                                        data[k].value.toLowerCase().indexOf(term_ru.toLowerCase()) !== -1 ||
                                                        (typeof data[k].city === "string" && data[k].city.toLowerCase().indexOf(term_ru.toLowerCase()) !== -1) ||
                                                        (typeof data[k].region === "string" && data[k].region.toLowerCase().indexOf(term_ru.toLowerCase()) !== -1))
                                                        || (typeof data[k].route === "string" && data[k].route.toLowerCase().indexOf(term_ru.toLowerCase()) !== -1)
                                                    ||
                                                    (data[k].label.toLowerCase().indexOf(term_en.toLowerCase()) !== -1 ||
                                                        data[k].value.toLowerCase().indexOf(term_en.toLowerCase()) !== -1 ||
                                                        (typeof data[k].city === "string" && data[k].city.toLowerCase().indexOf(term_en.toLowerCase()) !== -1) ||
                                                        (typeof data[k].region === "string" && data[k].region.toLowerCase().indexOf(term_en.toLowerCase()) !== -1))
                                                        || (typeof data[k].route === "string" && data[k].route.toLowerCase().indexOf(term_en.toLowerCase()) !== -1)
                                                    ||
                                                    (data[k].label.toLowerCase().indexOf(term_by.toLowerCase()) !== -1 ||
                                                        data[k].value.toLowerCase().indexOf(term_by.toLowerCase()) !== -1 ||
                                                        (typeof data[k].city === "string" && data[k].city.toLowerCase().indexOf(term_by.toLowerCase()) !== -1) ||
                                                        (typeof data[k].region === "string" && data[k].region.toLowerCase().indexOf(term_by.toLowerCase()) !== -1))
                                                        || (typeof data[k].route === "string" && data[k].route.toLowerCase().indexOf(term_by.toLowerCase()) !== -1)
                                                ) {
                                                    source.push(data[k]);
                                                }
                                            }

                                            response(source);
                                        }
                                    })(options.source),
                                    search: function (event, ui) {
                                        console.log(0);
                                    },
                                    select: function ( event, ui ) {

                                        $this.closest('form').attr("action", ui.item.page);
                                        inputWithIdName.val(ui.item.id);
                                    }
                                }).on("input", function () {

								}).on('focusin', function () { $(this).autocomplete('search'); })
                                    .on('focusout', function () {
                                        if(document.querySelector('button.awe-btn').classList.contains('heartBeat')){
                                            [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                                item.classList.remove('heartBeat');
                                            })
                                        }
                                        setTimeout(function () {
                                            [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                                item.classList.add('heartBeat');
                                            })
                                        },300)
                                    })

                                    .data( "ui-autocomplete" )._renderItem = __highlight;

                                if (typeof curVals[defVal] !== "undefined") {
                                    $this.val(curVals[defVal].name);
                                    $this.closest('form').attr("action", (curVals[defVal].page && !options.onlyDefPage) ? curVals[defVal].page : options.defPage);
                                    $this.attr("placeholder", "");
                                }

                            });
                        },

                        /**
                         * инициализируем select2
                         * @param {object} options
                         */
                        __initSelect2 = function (options) {

                            // получаем данные для выборки
                            function getDataSelect2 (source, notSetVal) {
                                var i = 0, cnt = source.length, data = [];
                                for (i = 0; i < cnt; i++) {
                                    if (notSetVal != source[i].id) {
                                        data.push({id: source[i].id, text: options.source[i].label});
                                    }
                                }
                                return data;
                            }

                            // инициализируем select
                            function select2 (jqInput, source, notSetVal) {
                                jqInput.select2({
                                    allowClear: true,
                                    data: getDataSelect2(source, notSetVal),
                                    formatNoMatches: function () {
                                        return "<?= GetMessage("NOMATCHES")?>";
                                    }
                                }).on("change", function (e) {
                                    $( $(this).data("link") ).select2("destroy");
                                    select2($( $(this).data("link") ), source, e.val);
                                });

                            }

                            options.inputs.each(function (i) {
                                var $this = $(this);
                                select2($this, options.source, $this.data("not-set-val"));
                                $this.select2("val", $this.data("default"));
                            });

                        },

                        /**
                         * @param {String} tk
                         */
                        __setEventHandler = function (tk) {
                            $(tabs[tk].href).one(tabs[tk].eventInit, function ( e ) {

                                __initTab(tabs[tk]);

                                e.preventDefault();

                            });
                        },

                        dateFormat = "DD.MM.YYYY",

                        /**
                         * @type {String}
                         */
                        tab_k;

                    <?if ($arParams["active_tab"]) :?>
                    if (__request_id.length) {
                        __initTab(tabs["<?= $arParams["active_tab"]?>"]);
                    }
                    <?endif?>

                    // инициализация работы вкладок
                    for (tab_k in tabs) {
                        if (!tabs[tab_k].done) {
                            if (tabs[tab_k].eventInit) {
                                __setEventHandler(tab_k);
                            } else {
                                __initTab(tabs[tab_k]);
                            }
                        }
                    }

                    
                    var momentLocaleData = moment.locale("<?= (LANGUAGE_ID === "by" ? "be" : LANGUAGE_ID)?>");

                    $.datepicker.regional['user'] = {
                        dayNamesShort: moment.weekdaysShort(),
                        monthNames: moment.months(),
                        dayNames:moment.weekdaysShort(),
                        dayNamesShort: moment.weekdaysShort(),
                        dayNamesMin: moment.weekdaysShort(),
                        firstDay: moment.localeData().firstDayOfWeek()

                        //dateFormat: 'yy-mm-dd' // "2016-11-22". date formatting tokens are not easily interchangeable between momentjs and jQuery UI (https://github.com/moment/moment/issues/890)
                    };
                    $.datepicker.setDefaults($.datepicker.regional['user']);
                    let current;
                    let pr;
                    function initDatePicker ($this) {
                        pr = $this['0'].closest('.form-cn');
                        let dateHover;
                        if ($this.val() == '') {
                            default_data = $this['0'].dataset[Object.keys($this['0'].dataset)];
                            default_data = moment.unix(default_data).format('DD.MM.YYYY');
                            if ($this.hasClass('date_from')) {
                                var input = pr.querySelector('[name="booking[date_from]"]');
                                $(input).val(moment(default_data, 'DD.MM.YYYY').format('X'));
                            } else {
                                var input = pr.querySelector('[name="booking[date_to]"]');
                                $(input).val(moment(default_data, 'DD.MM.YYYY').format('X'));
                            }
                        } else {
                            default_data = $this.val();
                            default_data = moment.unix(default_data).format('DD.MM.YYYY');
                        }


                        var minDate;
                        var maxDate;

                        if(pr.id != 'form-transfer'){

                            minDate= pr.querySelector('[name="booking[date_from]"]').value;
                            maxDate =  pr.querySelector('[name="booking[date_to]"]').value ;
                        }
                        else{
                            minDate= pr.querySelector('[name="booking[date_from]"]').value;
                        };
                        $this.css('color', '#264B87');
                        return (function (minDate, maxDate,pr) {
                            $this.datepicker({
                                dateFormat: 'dd.mm.yy',
                                numberOfMonths: window.innerWidth < 640 ? 1 : 2,
                                minDate: new Date(),
                                beforeShow: function (obj) {

                                    if ($this.hasClass('date_to')) {
                                        setTimeout(function () {
                                            document.querySelector('#ui-datepicker-div').classList.add('date_to__calendar');
                                        }, 100)

                                    }



                                },
                                beforeShowDay: function (date) {

                                    if(pr.id != 'form-transfer'){
                                        date = moment(date).format('X');
                                        minDate = moment(pr.querySelector('[name="booking[date_from]"]').value,'X').format('X');
                                        maxDate =  moment(pr.querySelector('[name="booking[date_to]"]').value,'X').format('X');

                                        if ($this.hasClass('date_to')) {
                                            if(!document.querySelector('.header_calendar')) {
                                                let header = document.createElement('div');
                                                header.classList.add('header_calendar');
                                                header.innerText = '<?= GetMessage("BACK") ?>';

                                                document.querySelector('#ui-datepicker-div').insertBefore(header, document.querySelector('#ui-datepicker-div').children[0]);

                                                setTimeout(function (e) {
                                                    if(!document.querySelector('.footer_calendar')) {
                                                        let footer = document.createElement('span');
                                                        footer.classList.add('footer_calendar');
                                                        footer.innerText = `<?= GetMessage("NIGHT") ?> ${moment(maxDate, 'X').diff(moment(minDate, 'X'), 'days') + 1}`;
                                                        document.querySelector('#ui-datepicker-div').appendChild(footer, document.querySelector('#ui-datepicker-div'));
                                                    }
                                                },100);
                                            }


                                        } else {
                                            if(!document.querySelector('.header_calendar')) {
                                                let header = document.createElement('div');
                                                header.innerText = '<?= GetMessage("TO") ?>';
                                                header.classList.add('header_calendar');
                                                document.querySelector('#ui-datepicker-div').insertBefore(header, document.querySelector('#ui-datepicker-div').children[0]);
                                            }

                                        }
                                        //date = moment(date).format('X');
                                        if ($this.hasClass('date_to')) {
                                            if ( date >= minDate && date < maxDate) {
                                                return date == minDate ? [true, 'light-date-start', ''] : [true, 'light-date', ''];
                                            } else return [true, '', '']
                                        } else {
                                            if (date > minDate && date <= maxDate) {
                                                return date  == maxDate ? [true, 'light-date-end', ''] : [true, 'light-date', ''];
                                            } else return [true, '', '']
                                        }
                                    }
                                    else return [true,''];

                                },

                                onSelect: function (string, object) {
                                    console.log(string);
                                    let parent = $this['0'].closest('.field-date');
                                    if ($this.hasClass('date_from')) {

                                        let inputFrom = parent.querySelector('[name="booking[date_from]"]');
                                        $(inputFrom).val(moment(string, 'DD.MM.YYYY').format('X'));
                                        let nextDate =  moment(string, 'DD.MM.YYYY').add(1,'days').format('DD.MM.YYYY');

                                        if(parent.querySelector('[name="booking[date_to]"]')) {
                                            let inputTo = parent.querySelector('[name="booking[date_to]"]');
                                            $(inputTo).val(moment(string, 'DD.MM.YYYY').add(1,'days').format('X'));
                                            $(parent.querySelector('.date_to')).datepicker('option', 'minDate', string);
                                            $(parent.querySelector('.date_to')).datepicker('setDate', nextDate);
                                        }
                                    } else {
                                        let input = parent.querySelector('[name="booking[date_to]"]');
                                        $(input).val(moment(string, 'DD.MM.YYYY').format('X'));
                                        if(window.innerWidth > 768 && $(pr.querySelector("select[name='booking[adults]']")).closest('.select').data('currentvalue')==''){
                                            $(pr.querySelector("select[name='booking[adults]']")).select2('open');
                                        }
                                    }

                                    
                                    $('.tour-list-item a, .hotel-list-item a').each(function(index){
                                        var href = $(this).attr('href');                                            
                                        if (typeof href !== "undefined" && href.indexOf("booking[id]") >= 0){

                                            if(href.indexOf("booking[date_from]") >= 0) {
                                                let regEx = /([?&]booking\[date_from\])=([^#&]*)/g;
                                                href = href.replace(regEx, '$1=' + $(parent.querySelector('[name="booking[date_from]"]')).val());
                                            } else {
                                                href = href + '&booking[date_from]=' + $(parent.querySelector('[name="booking[date_from]"]')).val();
                                            }

                                            if(href.indexOf("booking[date_to]") >= 0) {
                                                let regEx = /([?&]booking\[date_to\])=([^#&]*)/g;
                                                href = href.replace(regEx, '$1=' + $(parent.querySelector('[name="booking[date_to]"]')).val());
                                            } else {
                                                href = href + '&booking[date_to]=' + $(parent.querySelector('[name="booking[date_to]"]')).val();
                                            }
                                            
                                            $(this).attr('href', href);
                                        }                                           
                                    });

                                },
                                onClose: function () {
                                    document.querySelector('#ui-datepicker-div').classList.remove('date_to__calendar');


									/*if(window.innerWidth > 768) {*/
                                        if ($this.hasClass('date_from')) {
                                            if (pr.id == 'form-transfer') {
                                                if ($('#transfers-date-to .date_to').data("datepicker") != null) {
                                                    let parent = $this['0'].closest('.form-search');
                                                    $(parent.querySelector('.date_to')).datepicker('show');
                                                }
                                            }
                                            else {
                                                let parent = $this['0'].closest('.form-search');
                                                $(parent.querySelector('.date_to')).datepicker('show');
                                            }
                                        }
									//}

                                    if(document.querySelector('button.awe-btn').classList.contains('heartBeat')){
                                        [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                            item.classList.remove('heartBeat');
                                        })
                                    }
                                    setTimeout(function () {
                                        [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                            item.classList.add('heartBeat');
                                        })
                                    },300)


                                }
                            })
                            $this.datepicker('setDate', default_data);
                        })(minDate,maxDate,pr)
                    }



                    $('.banner-cn .calendar-input').each(function () {
                        initDatePicker($(this), undefined);
                    });

                    function hoverDays(e){
                        let target = e.target;
                        if (target.closest('td') && current != target && target.closest('.comparison') == null) {
                            let par = document.querySelector('.form-cn.active');
                            current = target;
                            switch (target.tagName) {
                                case 'A':
                                    day = target.innerText;
                                    month = target.closest('td').getAttribute('data-month');
                                    year = target.closest('td').getAttribute('data-year');
                                    break;
                                case 'TD':
                                    month = target.getAttribute('data-month');
                                    year = target.getAttribute('data-year');
                                    day = target.children[0].innerText;
                                    break;
                            }
                            dateHover = moment(`${day}.${parseInt(month)+1}.${year}`, 'DD.MM.YYYY').format('X');

                            //console.log(moment(par.querySelector('[name="booking[date_from]"]').value,'X').format('DD.MM.YYYY'));
                            let minDate = moment(par.querySelector('[name="booking[date_from]"]').value,'X');
                            let maxDate = moment(dateHover,'X');
                            var days = moment(maxDate,"X").diff(moment(minDate,"X"), 'days') + 1;

                            $('.footer_calendar').text( `<?= GetMessage("NIGHT") ?>: ${days <= 0 ? 0 : days}`);
                        }
                    }
                    $(document).on("mouseenter",'[data-handler="selectDay"]',hoverDays);
                    //document.addEventListener('mouseover',hoverDays);
                    <?// устанавливаем дефолтные значения возраста

                    if ($arResult["__get"]["children_age"]):
                    for ($i = 0, $cnt = count($arResult["__get"]["children_age"]); $i < $cnt; $i++) {
                    ?>
                    defAge[<?= $i?>] = <?= $arResult["__get"]["children_age"][$i]?>;
                    <?} endif?>

                    // инициируем область с вводом возраста детей
                    var change_indicator = true;
                    $("select[name='booking[children]']").on("change", function (e) {
                        var $this = $(this), val = Number($this.val()), parent = $this.parent(),
                            age_tpls = [], age_selector = "", i;
                        change_indicator = false;
                        $this.closest('.select').data('currentvalue',$this.val());
                        age_tpls.push("<?= $age_tpls[0]?>");
                        age_tpls.push("<?= $age_tpls[1]?>");
                        if(document.querySelector('button.awe-btn').classList.contains('heartBeat')){
                            [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                item.classList.remove('heartBeat');
                            });
							
						document.querySelector('.slider-for').classList.add('auto-height');
						
						
							/*var p = $(".age-container");
							var s = $(".slider-for");
							var sHeight = s.outerHeight();
							var pHeight = p.outerHeight();
							console.log('Высота блока age-container' + pHeight);
							console.log('Высота блока slider-for' + sHeight);
							$('.auto-height').css('height', sHeight + pHeight); */

							
                        }
                        setTimeout(function () {
                            [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                item.classList.add('heartBeat');
                            })
                        },300)
                        parent.next(".age-container").remove();
                        if (val > 0) {
                            for (i = 1; i <= val; i++) {
                                age_selector += (age_tpls[1].replace("#N#", i)).replace("#N#", i);
                            }
                            parent.after(age_tpls[0].replace("#AGE_SELECTORS#", age_selector));
                            for (i in defAge) {
                                $("#age_selector__" + (Number(i) + 1)).val(defAge[i]);
                            }
							
					
							document.querySelector('.slider-for').classList.add('auto-height');
							
							/* var p = $(".age-container");
							var s = $(".slider-for");
							var sHeight = s.outerHeight();
							var pHeight = p.outerHeight();
							console.log('Высота блока age-container' + pHeight);
							console.log('Высота блока slider-for' + sHeight);
							
							$('.auto-height').css('height', sHeight + pHeight); */
							
							
                        }
                    });
                    $("select[name='booking[children]']").on('select2-close', function (e) { $(this).trigger("change"); });    
                    $("select[name='booking[children]']").on("click", function (e) {
                        if (change_indicator) {
                            $(this).trigger("change");
                        }
                        change_indicator = true;
						
						
                    });

                    // инициализируем запоминание введённого возраста из формы
                    $(document).on("change", "select[id^='age_selector__']", function (e) {
                         var $this = $(this), index = Number($this.attr("id").substr("age_selector__".length)) - 1;
                        defAge[index] = $this.val();

                        var endHref = '';
                        for(let n = 0; n < index; n++){
                            let el = n + 1;                         
                            endHref = endHref + '&booking[children_age][' + n + ']=' + $("#age_selector__" + el).val();
                        }

                        $('.tour-list-item a, .hotel-list-item a').each(function(i){
                            var href = $(this).attr('href');                                            
                            if (typeof href !== "undefined" && href.indexOf("booking[id]") >= 0){                                

                                href = href + '&booking[children_age][' + index + ']=' + $this.val() + '&' + endHref;
                                
                                $(this).attr('href', href);
                            }                                           
                        });
                    });

                    // закрытие области с возрастом по клику на "крестик"
                    $(".form-search").on("click", ".age-closer", function (e) {
                        $(this).closest(".age-container").hide();
                        if(document.querySelector('button.awe-btn').classList.contains('heartBeat')){
                            [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                item.classList.remove('heartBeat');
                            })
                        }
						document.querySelector('.slider-for').classList.remove('auto-height');
						$('.slider-for').css('height', 'auto');
                        setTimeout(function () {
                            [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                item.classList.add('heartBeat');
                            })
                        },300)
                    });

                    // прекращаем всплытие событий по клику из области формы (.form-search)
                    $(".form-search").on("click" , function (e) {
                        e.stopPropagation();
                    })

                    // скрытие области с возрастом при клике на любую другую область, кроме области фильтра (.form-search)
                    $("body").on("click", function () {
						
                        $(".age-container").hide();
						document.querySelector('.slider-for').classList.remove('auto-height');
                        if(document.querySelector('button.awe-btn').classList.contains('heartBeat')){
                            [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                item.classList.remove('heartBeat');
                            })
                        }
                        setTimeout(function () {
                            [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                item.classList.add('heartBeat');
                            })
                        },300)
                    });
					
					
					
					
					 $(".form-search").on("click", ".ages", function (e) {
						 
						var div = $('.age-selector > select'); 
						if (!div.is(e.target) // если клик был не по нашему блоку
							&& div.has(e.target).length === 0)  { // и не по его дочерним элементам
							$(this).closest(".age-container").hide();
							$(this).closest('.slider-for').removeClass('auto-height');
						}
						
					  
                       /* $('.slider-for').css('height', 'auto');*/
					   
                    });

					
					
                    // выключаем вспомогательные поля для формы
                    $('.banner-cn form').submit(function (e) {
                        $(this).find("input[name='autocomplete-field']").prop('disabled', true);
                        $(this).find(".calendar-field").prop('disabled', true);
                    });

                    <?
                    // экранирование от cross-site-scripting
                    $round_trip = htmlspecialchars($_REQUEST["booking"]["roundtrip"])?>
                    $("input[name='booking[roundtrip]']").on("change", function () {
                        if ($(this).is(":checked")) {
                            checkdatepicker(false);
                            $('#transfers-date-to .date_to').val(moment.unix(parseInt($('#transfers-date-from input[name="booking[date_from]"]').val())+86400).format('DD.MM.YYYY')); 
                            $('#transfers-date-to input[name="booking[date_to]"]').val(parseInt($('#transfers-date-from input[name="booking[date_from]"]').val())+86400);
							$('#transfers-date-to').addClass('withDate');
							
                        } else {
                            $('#transfers-date-to .date_to').val('');
                            $('#transfers-date-to input[name="booking[date_to]"]').val('');
                            $("#txtSearch").datepicker("disable");
							$('#transfers-date-to').removeClass('withDate');
                        }
                    })
					
					
					 
					 

                    function isMobile () {

                        return $(window).outerWidth() < 1200;
                    }
                    var target = document.body;

                    var autoComp;


                    var observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {

                            mutation.addedNodes.forEach(item=>{
                                if(item.tagName == 'UL'){
                                    if(item.classList.contains('ui-autocomplete') && item != undefined){
                                        autoComp = item;
                                        observer.disconnect();
                                    }
                                }


                            })
                        });
                    });
                    var config = { attributes: true, childList: true, characterData: true };

                    observer.observe(target,  config);




                    // форма fixed position;
                    $(window).on("scroll", function () {

                        var windown_top = $(window).scrollTop();
                        //  let form = document.querySelectorAll('input[name = "autocomplete-field"]');
                        if(autoComp){
                            autoComp.getBoundingClientRect().top;

                        }

                        if (windown_top > 320) {

                            if ($('#searchformtab').hasClass('fixed-position-form') == false) {

                                $('#searchformtab').addClass('fixed-position-form');
                                if(autoComp){
                                    autoComp.classList.add('fixed-autoComp');


                                }
                                if (!isMobile()) {
                                    $('#form-stub').css({height: "290px"});
                                }
                            }

                        } else {
                            if(autoComp){
                                autoComp.classList.remove('fixed-autoComp');
                            }
                            $('#searchformtab').removeClass('fixed-position-form');
                            if (!isMobile()) {
                                $('#form-stub').css({height: "0px"});
                            }

                        }

                    });




                    $("select[name='booking[adults]']").each(function () {
                        var $this = $(this);
                        $this.select2({
                            minimumResultsForSearch: -1,
                            shouldFocusInput: function() {
                                return false;
                            },
                            dropdownCssClass: $this.data("dropdown-parent") !== "" ? $this.data("dropdown-parent") : '',
                        }).on('select2-close',function(e){
                            let parent = $this.closest('.form-search');
                            $this.closest('.select').data('currentvalue',$this.val());

                            if($this.val() == 0 && $(parent.find("select[name='booking[children]']")).val() == 0){
                                $(parent.find("select[name='booking[children]']")).select2("val", "1");
                                $(parent.find("select[name='booking[children]']")).select2('open');
                            }

                            if(window.innerWidth> 768 && $(parent.find("select[name='booking[children]']")).closest('.select').data('currentvalue')==''){
                                $(parent.find("select[name='booking[children]']")).select2('open');
                                //$("#select2-drop-mask").remove();
                            }
                            if(document.querySelector('button.awe-btn').classList.contains('heartBeat')){
                                [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                    item.classList.remove('heartBeat');
                                })
                            }
                            setTimeout(function () {
                                [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                    item.classList.add('heartBeat');
                                })
                            },300)

                            $('.tour-list-item a, .hotel-list-item a').each(function(index){
                                var href = $(this).attr('href');                                            
                                if (typeof href !== "undefined" && href.indexOf("booking[id]") >= 0){

                                    if(href.indexOf("booking[adults]") >= 0) {
                                        let regEx = /([?&]booking\[adults\])=([^#&]*)/g;
                                        href = href.replace(regEx, '$1=' + $this.val());
                                    } else {
                                        href = href + '&booking[adults]=' + $this.val();
                                    }
                                    
                                    $(this).attr('href', href);
                                }                                           
                            });

                        })
                    });
                    
                    $(".form-field .field-input").on("keydown",function(){var b=$(this).parent(".form-field").find("label");
                    !1==b.hasClass("forcus")&&b.addClass("focus")}).on("keyup",function(){var b=$(this),c=b.parent(".form-field").find("label");
                    ""==b.val()?c.removeClass("focus"):!1==c.hasClass("forcus")&&c.addClass("focus")});

					// Очистка текстового поля при клике на крестик
					$(".input_cross").click(function() {
    					$('[name="autocomplete-field"]').val('');
						var $label = $('[name="autocomplete-field"]').parent('.form-field').find('label');
						$label.removeClass("focus");
                        $label.show();
						// Защита от повторного появления текста после первого клика очистки и последующего выставления курсора
						$('[name="autocomplete-field"]').focusin(); 
    					$('[name="autocomplete-field"]').val('');
                        var $this = $(this);
                        let parent = $this.closest('.tab-pane');   
                        var hrefcompare = '#'+parent.attr('id');
                        let inputFrom = parent.find('[name="booking[id][0]"]');
                        $(inputFrom).val('');
                        $.each(tabs, function(index, value) {
                           if (hrefcompare==value.href) $this.closest('form').attr("action", value.defPage);
                        });                  
					});

					// Адаптирование положения инпута под моб-ую версию
					if(navigator.userAgent.match(/Android|iPhone|iPad|iPod|Opera Mini|IEMobile/i))
					{
						$(".input_cross").css("margin-top", "16px");
						
						$('input[type="text"]').focusin(function() {
							$('body').addClass('focused');
							$('body').find('.b24-widget-button-wrapper').css("display", "none");
						});

						$('input[type="text"]').focusout(function() {
							$('body').removeClass('focused');
							$('body').find('.b24-widget-button-wrapper').css("display", "block");
						});
						

					}
                    $("select[name='booking[children]']").select2({
                        minimumResultsForSearch: -1,
                    }).on('select2-close',function (e) {
                        var $this = $(this);

                        let parent = $this.closest('.form-search');
                        
                        if($this.val() == 0 && $(parent.find("select[name='booking[adults]']")).val() == 0){
                            $(parent.find("select[name='booking[adults]']")).select2("val", "1");
                            $(parent.find("select[name='booking[adults]']")).select2('open');
                        }
                        
                        if(document.querySelector('button.awe-btn').classList.contains('heartBeat')){
                            [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                item.classList.remove('heartBeat');
                            })
                        }
                        setTimeout(function () {
                            [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                item.classList.add('heartBeat');
                            })
                        },300)

                        $('.tour-list-item a, .hotel-list-item a').each(function(index){
                            var href = $(this).attr('href');                                            
                            if (typeof href !== "undefined" && href.indexOf("booking[id]") >= 0){

                                if(href.indexOf("booking[children]") >= 0) {
                                    let regEx = /([?&]booking\[children\])=([^#&]*)/g;
                                    href = href.replace(regEx, '$1=' + $this.val());
                                } else {
                                    href = href + '&booking[children]=' + $this.val();
                                }
                                
                                $(this).attr('href', href);
                            }                                           
                        });

                    })


                });
                })(jQuery, moment, window);
    </script>
    <script>
        function checkdatepicker(show) {
            if($('#transfers-date-to .date_to').data("datepicker") != null){
               // datepicker initialized
               //$('#transfers-date-to .date_to').datepicker('setDate', moment.unix(parseInt($('#transfers-date-from input[name="booking[date_from]"]').val())+86400).format('DD.MM.YYYY'));
    		   
            }
            else {
                initDatePickerCustom($('#transfers-date-to .date_to'));
                if (show) $('#transfers-date-to .date_to').datepicker("show");
                //$('#transfers-date-to .date_to').datepicker('setDate', moment.unix(parseInt($('#transfers-date-from input[name="booking[date_from]"]').val())+86400).format('DD.MM.YYYY'));
            }
        }
        function initDatePickerCustom ($this) {
            pr = $this['0'].closest('.form-cn');
            let dateHover;
            var default_data = '';
            if ($this.val() == '') {
                //default_data = moment.unix(<?=(time() + 86400)?>).format('DD.MM.YYYY');
                //var input = pr.querySelector('[name="booking[date_to]"]');
                //$(input).val(moment(default_data, 'DD.MM.YYYY').format('X'));
            } else {
                
                default_data = $this.val();
                default_data = moment.unix(default_data).format('DD.MM.YYYY');
            }
            
            var minDate;
            var maxDate;
            minDate= moment.unix(<?=(time() + 86400)?>).format('DD.MM.YYYY');
            $this.css('color', '#264B87');
            return (function (minDate, maxDate,pr) {
                $this.datepicker({
                    dateFormat: 'dd.mm.yy',
                    numberOfMonths: window.innerWidth < 640 ? 1 : 2,
                    minDate: new Date(),
                    beforeShow: function (obj) {
                        setTimeout(function () {
                            document.querySelector('#ui-datepicker-div').classList.add('date_to__calendar');
                        }, 100)
                    },
                    onSelect: function (string, object) {
                        let parent = $this['0'].closest('.field-date');
                        let input = parent.querySelector('[name="booking[date_to]"]');
                        $(input).val(moment(string, 'DD.MM.YYYY').format('X'));
                        $("input[name='booking[roundtrip]']").prop('checked', true);
    					$('#transfers-date-to').addClass('withDate');
                        if(window.innerWidth > 768 && $(pr.querySelector("select[name='booking[adults]']")).closest('.select').data('currentvalue')=='')  $(pr.querySelector("select[name='booking[adults]']")).select2('open');
                    },
                    onClose: function () {
                        document.querySelector('#ui-datepicker-div').classList.remove('date_to__calendar');
                        if(document.querySelector('button.awe-btn').classList.contains('heartBeat')){
                            [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                item.classList.remove('heartBeat');
                            })
                        }
                        setTimeout(function () {
                            [].slice.call(document.querySelectorAll('button.awe-btn')).forEach(function (item) {
                                item.classList.add('heartBeat');
                            })
                        },300)
    
    
                    }
                })
                $this.datepicker('setDate', default_data);
            })(minDate,maxDate,pr)
        }
    </script>

