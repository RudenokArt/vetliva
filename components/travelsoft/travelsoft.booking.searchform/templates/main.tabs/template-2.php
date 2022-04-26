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


$this->addExternalCss(SITE_TEMPLATE_PATH . "/css/dateTime/jquery.datetimepicker.min.css");

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
        $vars["date_from_value"] = ($active_tab === true) ? ($__request["date_from"] > 0 ? "value='" . $__request["date_from"] ."'" : "" ) : "";
        $vars["date_to_value"] = ($active_tab === true) ? ($__request["date_to"] > 0 ? "value='" . $__request["date_to"] . "'" : "" ) : "";
        $vars["adults"] = ($active_tab === true && $__request["adults"]) ? $__request["adults"] : null;
        $vars["children"] = ($active_tab === true) ? ($__request["children"] ? $__request["children"] : 0) : 0;

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

$age_tpls = array("<div class='age-container'><div class='age-wrapper'><span class='age-title'>".GetMessage("AGE_TITLE")."</span><hr><div class='age-closer'>&times;</div>#AGE_SELECTORS#</div></div>", getSelectAgeTpl());

$time = time();
$now = mktime(0, 0, 0, date("m", $time), date("d", $time), date("Y", $time));
?>

<style>
    /* Transfers Form */
    .select2-container {}
    .select2-choice {border-radius:0px !important; border-bottom:1px solid #e6e6e6; border-top:none !important;
        border-left:none !important; border-right:none !important;}
    .select2-choice .select2-chosen {color: #264b87; font-size:16px; font-weight:bold;margin-right: 16px !important;}
    .select2-choice .select2-arrow::after {content: "\f107"; font-family: FontAwesome !important;font-size: 24px !important; color: #b9b9b9 !important; right: 0 !important;}
    .select2-choice abbr {color: #b9b9b9 !important; right: 0 !important; margin-top: -4px !important; opacity: 1 !important;}
    .select2-search input {border-radius:none !important}
    .select2-search {padding:3px 4px !important;}
    .select2-results .select2-result-label {padding:6px !important;}
    .select2-result-selectable .select2-match, .select2-result-unselectable .select2-match {font-weight: bold; text-decoration:none !important; color:#264b87}
    .form-search .form-field.field-select .select2-container [role="presentation"]{
        display: none;
    }
    .daterangepicker .date::after {
        content: "<?=GetMessage('STARTDATE')?>";
        display: block;
        font-size: 9px;
        margin: -2px 0 -5px;
        color: #fff;
    }
    .daterangepicker .date::after {
        content: "<?=GetMessage('ENDDATE')?>";
        display: block;
        font-size: 9px;
        margin: -2px -3px -5px;
        color: #fff;
    }
    .fixed-autoComp{
        position: fixed;
        top:117px !important;
    }
</style>

<!-- Banner Content -->
<div id="searchformtab" class="banner-cn">

    <!-- Tabs Cat Form -->
    <ul class="tabs-cat text-center row">
        <?if ($arParams['show_placement_tab'] == "Y"):?>
            <li class="cate-item <?if ($arParams["active_tab"] == "placement_tab"):?>active<?endif?> col-xs-2">
                <a data-toggle="tab" href="#form-placements" title=""><span><?=GetMessage("ACCOMODATION")?></span><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-hotel.png" alt=""></a>
            </li>
        <?endif;?>
        <?if ($arParams['show_sanatorium_tab'] == "Y"):?>
            <li class="cate-item <?if ($arParams["active_tab"] == "sanatorium_tab"):?>active<?endif?> col-xs-2">
                <a data-toggle="tab" href="#form-sanatorium" title=""><span><?=GetMessage("RESORTS")?></span><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-sanatorium.png" alt=""></a>
            </li>
        <?endif;?>
        <?if ($arParams['show_tours_tab'] == "Y"):?>
            <li class="cate-item <?if ($arParams["active_tab"] == "tours_tab"):?>active<?endif?> col-xs-2">
                <a data-toggle="tab" href="#form-tours" title=""><span><?=GetMessage("EXCURSIONS")?></span><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-exc.png" alt=""></a>
            </li>
        <?endif;?>
        <?if ($arParams['show_excursionstours_tab'] == "Y"):?>
            <li class="cate-item <?if ($arParams["active_tab"] == "excursionstours_tab"):?>active<?endif?> col-xs-2">
                <a data-toggle="tab" href="#form-excursionstours" title=""><span><?=GetMessage("EXCURSIONS_TOURS")?></span><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-exc.png" alt=""></a>
            </li>
        <?endif;?>
        <?if ($arParams['show_transfer_tab'] == "Y"):?>
            <li class="cate-item <?if ($arParams["active_tab"] == "transfer_tab"):?>active<?endif?> col-xs-2">
                <a data-toggle="tab" href="#form-transfer" title=""><span><?=GetMessage("TRANSFERS")?></span><img src="<?= SITE_TEMPLATE_PATH ?>/images/icon-car.png" alt=""></a>
            </li>
        <?endif?>
    </ul>
    <!-- End Tabs Cat -->

    <!-- Tabs Content -->
    <div class="tab-content main-tabs-search">
        <div id="root"></div>
        <?if ($arParams['show_placement_tab'] == "Y"):
            $arPlacementsSessionParams = getSessionParams ($arParams, "placement");
            setInSession($arPlacementsSessionParams, "placements");
            extract(setTabCurrentValues("placement_tab" == $arParams["active_tab"], $arResult["__get"], $age_tpls), EXTR_OVERWRITE);
            ?>
            <!-- Search Hotel -->
            <div class="form-cn form-hotel tab-pane  <?if ($active_tab):?> in active <?endif?>" id="form-placements">
                <div class="form-search wrap">
                    <form autocomplete="off" action="<?= $arParams['placement_result_page']?>" method="get" class="box">
                        <input name="scroll-to-sp" type="hidden" value="Y">

                            <div class="form-field col-all__24 col-lg__6">
                                <?if (strlen($value) == 0):?>
                                    <label for="autocomplete-field"><?=GetMessage("CHMH")?></label>
                                <?endif?>
                                <input <?= $value?> type="hidden" name="booking[id][0]" class="field-input">
                                <input type="text" name="autocomplete-field" class="field-input">
                            </div>


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
                            <input type="hidden" name="booking[date_from]" <?= $date_from_value?>>

                            <input type="hidden" name="booking[date_to]" <?= $date_to_value?>>


                            <!--<input readonly required type="text" class="field-input calendar-input" placeholder="<?/*=GetMessage("PERIODSTAY")*/?>">-->
                        </div>
                        <div class="form-field field-select field-adults col-all__24 col-sm__12 col-lg__3">
                            <div class="select">
                                <?= getAdultsSelectOption($adults ? $adults : 2)?>
                            </div>
                        </div>
                        <div class="form-field field-select field-children col-all__24 col-sm__12 col-lg__3">
                            <div class="select">
                                <?= getChildrenSelectOption($children)?>
                            </div>
                            <?= $show_children_ages?>
                        </div>
                        <div class="form-submit col-all__24 col-lg__4">
                            <button type="submit" class="awe-btn awe-btn-lager awe-search"><?=GetMessage("SEARCH")?></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Search Hotel -->
        <?endif;?>

        <?if ($arParams['show_sanatorium_tab'] == "Y"):
            $arPlacementsSessionParams = getSessionParams ($arParams, "sanatorium");
            setInSession($arPlacementsSessionParams, "sanatorium");
            extract(setTabCurrentValues("sanatorium_tab" == $arParams["active_tab"], $arResult["__get"], $age_tpls), EXTR_OVERWRITE);
            ?>
            <!-- Search Sanatorium -->
            <div class="form-cn form-hotel tab-pane <?if ($active_tab):?> in active <?endif?>" id="form-sanatorium">
                <div class="form-search wrap">
                    <form autocomplete="off" action="<?= $arParams['sanatorium_result_page']?>"  method="get" class="box">

                        <input name="scroll-to-sp" type="hidden" value="Y">


                        <div class="form-field field-destination col-all__24 col-lg__6">
                            <?if ($value == ""):?>
                                <label for="autocomplete-field"><?=GetMessage("CMPR")?></label>
                            <?endif?>
                            <input <?= $value?> type="hidden" name="booking[id][0]" class="field-input">
                            <input type="text" name="autocomplete-field" class="field-input">
                        </div>


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
                            <input type="hidden" name="booking[date_from]" <?= $date_from_value?>>

                            <input type="hidden" name="booking[date_to]" <?= $date_to_value?>>
                            <!-- <input readonly="" required type="text" class="field-input calendar-input" placeholder="<?=GetMessage("PERIODSTAY")?>"> -->
                        </div>
                        <div class="form-field field-select field-adults col-all__24 col-sm__12 col-lg__3">
                            <div class="select">
                                <?= getAdultsSelectOption($adults ? $adults : 2)?>
                            </div>
                        </div>
                        <div class="form-field field-select field-children col-all__24 col-sm__12 col-lg__3">
                            <div class="select">
                                <?= getChildrenSelectOption($children)?>
                            </div>
                            <?= $show_children_ages?>
                        </div>
                        <div class="form-submit col-all__24 col-lg__4">
                            <button type="submit" class="awe-btn awe-btn-lager awe-search"><?=GetMessage("SEARCH")?></button>
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
            <div class="form-cn form-package tab-pane <?if ($active_tab):?> in active <?endif?>" id="form-tours">
                <div class="form-search wrap">
                    <form autocomplete="off" action="<?= $arParams['tours_result_page']?>"  method="get" class="box">
                        <input name="scroll-to-sp" type="hidden" value="Y">
                        <div class="form-field field-to col-all__24 col-lg__6">
                            <?if ($value == ""):?>
                                <label for="autocomplete-field"><?=GetMessage("CITYATTRACTIONS")?></label>
                            <?endif?>
                            <input <?= $value?> type="hidden" name="booking[id][0]" class="field-input">
                            <input type="text" name="autocomplete-field" class="field-input">
                        </div>
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
                            <div class="select">
                                <?= getAdultsSelectOption($adults ? $adults : 1)?>
                            </div>
                        </div>
                        <div class="form-field field-select field-children col-all__24 col-sm__12 col-lg__3">
                            <div class="select">
                                <?= getChildrenSelectOption($children)?>
                            </div>
                            <?= $show_children_ages?>
                        </div>
                        <div class="form-submit col-all__24 col-lg__4">
                            <button type="submit" class="awe-btn awe-btn-medium awe-search"><?=GetMessage("SEARCH")?></button>
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
            <div class="form-cn form-package tab-pane <?if ($active_tab):?> in active <?endif?>" id="form-excursionstours">
                <div class="form-search wrap">
                    <form autocomplete="off" action="<?= $arParams['excursionstours_result_page']?>"  method="get" class="box">
                        <input name="scroll-to-sp" type="hidden" value="Y">
                        <div class="form-field field-to col-all__24 col-lg__6">
                            <?if ($value == ""):?>
                                <label for="autocomplete-field"><?=GetMessage("TOURNAME")?></label>
                            <?endif?>
                            <input <?= $value?> type="hidden" name="booking[id][0]" class="field-input">
                            <input type="text" name="autocomplete-field" class="field-input">
                        </div>
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
                            <input type="hidden" name="booking[date_from]" <?= $date_from_value?>>

                            <input type="hidden" name="booking[date_to]" <?= $date_to_value?>>
                        </div>
                        <div class="form-field field-select field-adults col-all__24 col-sm__12 col-lg__3">
                            <div class="select">
                                <?= getAdultsSelectOption($adults ? $adults : 1)?>
                            </div>
                        </div>
                        <div class="form-field field-select field-children col-all__24 col-sm__12 col-lg__3">
                            <div class="select">
                                <?= getChildrenSelectOption($children)?>
                            </div>
                            <?= $show_children_ages?>
                        </div>
                        <div class="form-submit col-all__24 col-  col-lg__4">
                            <button type="submit" class="awe-btn awe-btn-medium awe-search"><?=GetMessage("SEARCH")?></button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- End Search Tours -->
        <?endif?>

        <?if ($arParams['show_transfer_tab'] == "Y"):
            $this->addExternalCss(SITE_TEMPLATE_PATH . "/css/select2/select2.min.css");
            $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/select2/select2.min.js");


            $this->addExternalJs(SITE_TEMPLATE_PATH . "/js/dateTime/jquery.datetimepicker.min.js");
            $arPlacementsSessionParams = getSessionParams ($arParams, "transfer");
            setInSession($arPlacementsSessionParams, "transfers");
            extract(setTabCurrentValues("transfer_tab" == $arParams["active_tab"], $arResult["__get"], $age_tpls), EXTR_OVERWRITE);
            ?>
            <!-- Search Transfer-->
            <div class="form-cn form-car tab-pane <?if ($active_tab):?> in active <?endif?>" id="form-transfer">
                <form autocomplete="off" action="<?= $arParams['transfer_result_page']?>"  method="get">
                    <input name="scroll-to-sp" type="hidden" value="Y">
                    <div class="roundtrip-container wrap"><input <?if ($_REQUEST["booking"]["roundtrip"] == "Y") {echo "checked";}?> name="booking[roundtrip]" value="Y" type="checkbox"><label for="roundtrip"><?=GetMessage("ROUNDTRIP")?></label></div>
                    <div class="form-search wrap">
                        <div class="box">
                            <div class="form-field field-from col-all__24 col-sm__12 col-lg__4">
                                <input
                                        required
                                        data-placeholder="<?= GetMessage("POINT_DEPARTURE")?>"
                                        data-link="#select2-to"
                                        data-default="<?= htmlspecialchars($_REQUEST["booking"]["point_A"])?>"
                                        data-not-set-val="<?= htmlspecialchars($_REQUEST["booking"]["point_B"])?>"
                                        name="booking[point_A]"
                                        id="select2-from">
                            </div>
                            <div class="form-field field-to col-all__24 col-sm__12 col-lg__4">
                                <input
                                        required
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
                                    <input type="hidden" name="booking[date_from]" <?= $date_from_value?>>

                                <!--<input readonly required type="text" data-remove-class="date" data-single-date-picker="Y" class="field-input calendar-input" placeholder="<?=GetMessage("DATE_FROM")?>">-->
                            </div>
                            <?if ($_REQUEST["booking"]["roundtrip"] == "Y"):?>
                                <div  class="form-field field-date  calendar-input col-all__24 col-sm__12 col-lg__4">
                                    <div id="transfers-date-to">
                                    <input <?= $date_to_value?> class="calendar-input date_to   field-input" style="width: 100%" data-date="<?= $now + (86400*($dateRange[1] + $dateRange[0]))?>">
                                    <input type="hidden" name="booking[date_to]" <?= $date_to_value?>>
                                    </div>
                                    <!--<input readonly required type="text" data-remove-class="date" data-single-date-picker="Y" class="field-input calendar-input" placeholder="<?=GetMessage("DATE_TO")?>"> -->
                                </div>
                            <?endif?>
                            <div class="form-field field-select field-adults col-all__24 col-sm__12 col-lg__4">
                                <div class="select">
                                    <?= getSelectPassengersTpl($adults ? $adults : 2)?>
                                </div>
                            </div>
                            <div class="form-submit  col-all__24  col-lg__4">
                                <button type="submit" class="awe-btn awe-btn-medium awe-search"><?=GetMessage("SEARCH")?></button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <!-- End Search Transfer -->
        <?endif?>
    </div>
    <!-- End Tabs Content -->

</div>
<!-- End Banner Content -->
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

                            if (it.type !== "excursions") {
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

                            en: ["q","w","e","r","t","y","u","i","o","p","[","]","a","s","d","f","g","h","j","k","l",";","'","z","x","c","v","b","n","m",",",".","Q","W","E","R","T","Y","U","I","O","P","{","}","A","S","D","F","G","H","J","K","L",":","\"","Z","X","C","V","B","N","M","<",">"],
                            by: ["й","ц","у","к","е","н","г","ш","ў","з","х","'","ф","ы","в","а","п","р","о","л","д","ж","э","я","ч","с","м","і","т","ь","б","ю","Й","Ц","У","К","Е","Н","Г","Ш","Ў","З","Х","'","Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э","Я","Ч","С","М","І","Т","Ь","Б","Ю"],
                            ru: ["й","ц","у","к","е","н","г","ш","щ","з","х","ъ","ф","ы","в","а","п","р","о","л","д","ж","э","я","ч","с","м","и","т","ь","б","ю","Й","Ц","У","К","Е","Н","Г","Ш","Щ","З","Х","Ъ","Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э","Я","Ч","С","М","И","Т","Ь","Б","Ю"]

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
                                                ||
                                                (data[k].label.toLowerCase().indexOf(term_en.toLowerCase()) !== -1 ||
                                                    data[k].value.toLowerCase().indexOf(term_en.toLowerCase()) !== -1 ||
                                                    (typeof data[k].city === "string" && data[k].city.toLowerCase().indexOf(term_en.toLowerCase()) !== -1) ||
                                                    (typeof data[k].region === "string" && data[k].region.toLowerCase().indexOf(term_en.toLowerCase()) !== -1))
                                                ||
                                                (data[k].label.toLowerCase().indexOf(term_by.toLowerCase()) !== -1 ||
                                                    data[k].value.toLowerCase().indexOf(term_by.toLowerCase()) !== -1 ||
                                                    (typeof data[k].city === "string" && data[k].city.toLowerCase().indexOf(term_by.toLowerCase()) !== -1) ||
                                                    (typeof data[k].region === "string" && data[k].region.toLowerCase().indexOf(term_by.toLowerCase()) !== -1))
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

                            }).on('focusin', function () { $(this).autocomplete('search'); }).data( "ui-autocomplete" )._renderItem = __highlight;

                            // set default value
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

                /*function initDatePicker ($this) {
                             var parent = $this.parent(".field-date"),
                             date_from = parent.find(".minDate"), date_to = parent.find(".maxDate"), options = {};

                             options.minDate = moment.unix(<?= time()?>);

                    if (date_from.length) {
                        options.startDate = (function (date_from) {

                            var val = date_from.val(), defVal;
                            if (!val) {
                                defVal = date_from.data("date");
                                date_from.val(defVal);
                                return moment.unix(defVal);
                            }

                            return moment.unix( val );

                        })(date_from);
                    }

                    if (date_to.length) {
                        options.endDate = (function (date_to) {

                            var val = date_to.val(), defVal;
                            if (!val) {
                                defVal = date_to.data("date");
                                date_to.val(defVal);
                                return moment.unix( defVal );
                            }

                            return moment.unix( val );

                        })(date_to);
                    }
                    //options.singleDatePicker: true,
                    //options.singleDatePicker = true;

                    options.autoApply = true;
                    options.locale = {
                            format: dateFormat,
                            //separator: ' - ',
                            daysOfWeek: moment.weekdaysMin(),
                            monthNames: moment.monthsShort(),
                            firstDay: moment.localeData().firstDayOfWeek(),
                        }

                    $this.daterangepicker(options).on("apply.daterangepicker", function () {

                        var arVals = $(this).val().split(" - ") || [], parent;

                        if (arVals.length) {

                            date_from.val(moment(arVals[0], dateFormat).unix());
                            if (date_to.length) {
                                date_to.val(moment(arVals[1], dateFormat).unix());
                            }

                        }


                    }).on("show.daterangepicker", function (ev, picker) {

                        var calendars = picker.container.find('.calendars');
                        var textDuration = calendars.find('.text-duration');
                        var momentStartDate = moment(picker.startDate._d);
                        var momentEndDate = moment(picker.endDate._d);
                        var days = momentEndDate.diff(momentStartDate, 'days') + 1;

                        if (!textDuration.length) {

                            calendars.append('<div class="clearfix"></div><div class="text-center text-duration-area"><b><?= GetMessage('DURATION_CALENDAR_TITLE')?>: <span class="text-duration">0<span></b></div>');

                            textDuration = calendars.find('.text-duration');

                            calendars.on('mouseenter.daterangepicker', 'td.available', function () {

                                if (!$(this).hasClass('available')) return;

                                var title = $(this).attr('data-title');
                                var row = title.substr(1, 1);
                                var col = title.substr(3, 1);
                                var cal = $(this).parents('.calendar');
                                var date = cal.hasClass('left') ? picker.leftCalendar.calendar[row][col] : picker.rightCalendar.calendar[row][col];
                                var tmpEndDate = moment(date._d);
                                var tmpDays = tmpEndDate.diff(momentStartDate, 'days') + 1;
                                textDuration.text(tmpDays > 0 ? tmpDays : 0);
                            }).on('mouseleave.daterangepicker', 'td.available', function () {

                                if (!momentEndDate) {
                                    days = 0;
                                } else {
                                    days = momentEndDate.diff(momentStartDate, 'days') + 1;
                                }

                                textDuration.text(days);

                            }).on('mousedown.daterangepicker', 'td.available', function () {

                                var title = $(this).attr('data-title');
                                var row = title.substr(1, 1);
                                var col = title.substr(3, 1);
                                var cal = $(this).parents('.calendar');
                                var date = cal.hasClass('left') ? picker.leftCalendar.calendar[row][col] : picker.rightCalendar.calendar[row][col];
                                momentStartDate = moment(date._d);
                                momentEndDate = null;
                            });
                        }

                        textDuration.text(days);
                    });

                    // remove class on datepicker
                    if ($this.data("remove-class")) {
                        $this.one("show.daterangepicker", function (ev, picker) {
                            $(picker.container).find("." + $this.data("remove-class")).removeClass($this.data("remove-class"));
                        });
                    }
       }



       */
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





                    function initDatePicker ($this){
                      /*  if($this.hasClass('date_from')){
                            $this.datetimepicker({
                                format:'Y/m/d',
                                onShow:function( ct ){
                                    console.log($('.date_to').val());
                                    this.setOptions({
                                        maxDate:$('.date_to').val() ? $('.date_to').val():false
                                    })
                                },
                                timepicker:false
                            });
                        }

                        else{
                            $this.daterangepicker({
                                format:'Y/m/d',
                                onShow:function( ct ){
                                    this.setOptions({
                                        minDate:$('.date_from').val()? $('.date_from').val():false
                                    })
                                },
                                timepicker:false
                            });
                        }
                    }*!/*/

                    //устанавливаем дефолтные значения
                    if($this.val() == '') {
                        default_data = $this['0'].dataset[Object.keys($this['0'].dataset)];
                        default_data = moment.unix(default_data).format('DD.MM.YYYY');
                        var parent = $this.parent('.field-date');
                        if($this.hasClass('date_from')) {
                            var input = parent.find('[name="booking[date_from]"]');
                            input.val(moment(default_data, 'DD.MM.YYYY').format('X'));
                        }
                        else{
                            var input =  parent.find('[name="booking[date_to]"]');
                            input.val(moment(default_data,'DD.MM.YYYY').format('X'));
                        }
                    }
                    else {
                        default_data = $this.val();
                        default_data = moment.unix(default_data).format('DD.MM.YYYY');
                    }


                    var minDate = new Date();
                    // инициализируем
                    $this.datepicker({
                        range: 'period',
                        dateFormat:'dd.mm.yy',
                        numberOfMonths: window.innerWidth < 640 ? 1 : 2,
                        minDate: new Date(),
                        beforeShow: function(obj){
                            if($this.hasClass('date_to')){
                                setTimeout(function(){
                                    document.querySelector('#ui-datepicker-div').classList.add('date_to__calendar');
                                    var currentDayIndex;
                                    var beforeDay=[];
                                    let allDay= document.querySelectorAll('.date_to__calendar td');

                                    [].slice.call(allDay).forEach((item,index)=>{
                                        //console.log(item.classList.contains('ui-datepicker-current-day'));
                                       if(item.classList.contains('ui-datepicker-current-day')){
                                          currentDayIndex=index;
                                       }
                                    });

                                    [].slice.call(allDay).forEach((item,index)=>{
                                        if(!item.classList.contains('ui-state-disabled') && index < currentDayIndex){
                                            beforeDay.push(item);
                                        }
                                    });
                                    /!*
                                    beforeDay.forEach((item,index)=>{

                                        index != 0 ? item.classList.add('rangeDay') :   item.classList.add('startDay');
                                    })


                                },100)

                            }
                        },
                        onSelect: function (string,object){
                            let parent = $this['0'].closest('.field-date');
                            if($this.hasClass('date_from')){
                                let input = parent.querySelector('[name="booking[date_from]"]');
                                $(input).val(moment(string,'DD.MM.YYYY').format('X'));
                                $(parent.querySelector('.date_to')).datepicker('option','minDate',string);
                                $(parent.querySelector('.date_to')).datepicker('setDate',moment(string,'DD.MM.YYYY').add(5,'day').format('DD.MM.YYYY'));


                            }
                            else{
                                let input =  parent.querySelector('[name="booking[date_to]"]');
                                $(input).val(moment(string,'DD.MM.YYYY').format('X'));
                                $(parent.querySelector('.date_form')).datepicker('option','maxDate',string);


                                //$(parent.querySelector('.date_to')).datepicker('setDate',string);
                            }
                        },
                        onClose: function () {

                            document.querySelector('#ui-datepicker-div').classList.remove('date_to__calendar');
                            if($this.hasClass('date_from')) {
                                let parent = $this['0'].closest('.form-search');

                                if(parent.querySelector('.date_to')){

                                    $(parent.querySelector('.date_to')).datepicker('show');
                                }
                                else {
                                    $(parent.querySelector("select[name='booking[adults]']")).select2('open');
                                }
                            }
                            else{
                                let parent = $this.closest('.form-search');
                                $(parent.find("select[name='booking[adults]']")).select2('open');
                            }
                        }

                    });

                    $this.datepicker('setDate',default_data);



                }


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
                    age_tpls.push("<?= $age_tpls[0]?>");
                    age_tpls.push("<?= $age_tpls[1]?>");

                    parent.next(".age-container").remove();
                    if (val > 0) {
                        for (i = 1; i <= val; i++) {
                            age_selector += (age_tpls[1].replace("#N#", i)).replace("#N#", i);
                        }
                        parent.after(age_tpls[0].replace("#AGE_SELECTORS#", age_selector));
                        for (i in defAge) {
                            $("#age_selector__" + (Number(i) + 1)).val(defAge[i]);
                        }
                    }
                });

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
                });

                // закрытие области с возрастом по клику на "крестик"
                $(".form-search").on("click", ".age-closer", function (e) {
                    $(this).closest(".age-container").hide();
                });

                // прекращаем всплытие событий по клику из области формы (.form-search)
                $(".form-search").on("click" , function (e) {
                    e.stopPropagation();
                })

                // скрытие области с возрастом при клике на любую другую область, кроме области фильтра (.form-search)
                $("body").on("click", function () {
                    $(".age-container").hide();
                });

                // выключаем вспомогательные поля для формы
                $('.banner-cn form').submit(function (e) {
                    $(this).find("input[name='autocomplete-field']").prop('disabled', true);
                    $(this).find(".calendar-field").prop('disabled', true);
                });


                $("input[name='booking[roundtrip]']").on("change", function () {
                    if ($(this).is(":checked")) {
                        var startDate = $("#transfers-date-from .calendar-input").val(),
                            date_to_html = "<div id=\"transfers-date-to\" class=\"form-field field-date col-all__24 col-sm__12 col-lg__4\">";
                        date_to_html +=
                            "<input readonly <?= $date_to_value?> class='calendar-input date_to field-input'; style='width:100%' data-date='<?= $now + (86400*($dateRange[1] + $dateRange[0]))?>'>";
                        date_to_html +=
                            " <input type=\"hidden\" name=\"booking[date_to]\" <?= $date_to_value?>></div>";
                        $("#transfers-date-from").after(date_to_html);
                        initDatePicker($("#transfers-date-to .calendar-input"));
                    } else {
                        console.log( $("#transfers-date-to"));
                        //$("#transfers-date-to .calendar-input").data("daterangepicker").remove();
                        $("#transfers-date-to").remove();
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
                        dropdownCssClass: $this.data("dropdown-parent") !== "" ? $this.data("dropdown-parent") : '',
                    }).on('change',function(e){
                        console.log('hello');
                        let parent = $this.closest('.form-search');
                        $(parent.find("select[name='booking[children]']")).select2('open');

                    })
                });
                $("select[name='booking[children]']").select2();
            })(jQuery, moment, window);
          </script>

        <script type="text/babel">

            [].slice.call(document.querySelectorAll('.calendar-input')).forEach(item=>{
                ReactDOM.render(<div>hello world</div>, item);
            })



        </script>