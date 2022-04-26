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

if (empty($arResult['ITEMS'])) {
   ?><script>$('.show-filter-link').hide();</script><?
    return;
}
    

///// инициализируем перевод языка
use Bitrix\Main\Localization\Loc;

$loc = new Loc;
$loc->loadMessages(__FILE__);
\Bitrix\Main\Loader::includeModule("iblock");
$translator = new SFTranslator($loc, new CIBlockElement, $arResult["ITEMS"], LANGUAGE_ID, POSTFIX_PROPERTY);
$booking_city = '';
if ($arParams["__BOOKING_REQUEST"]['id'][0]>0) {
    $restest = CIBlockElement::GetByID($arParams["__BOOKING_REQUEST"]['id'][0]);
    if($ar_restest = $restest->GetNext()) $test_iblock = $ar_restest['IBLOCK_ID'];
    if ($test_iblock==5) $booking_city = $ar_restest['NAME'];
}?>
	<?/*<div class="active aside-menu active" data="toogle-menu" ><span ><?= GetMessage('SHOW_FILTER_LANG')?></span>    </div>*/?>
    <div class="active" <?/*data="collapse"*/?>>
<form id="smart_filter_form" name="smart_filter_form" action="<? echo $APPLICATION->GetCurPage(false) ?>" method="get" class="smartfilter accordion-us">
    <input name="set_filter" type="hidden" value="y">
    <? foreach ($arParams["__BOOKING_REQUEST"] as $key => $value) :
        if (is_array($value)):
            ?>
            <? foreach ($value as $kkey => $vvalue): ?>
                <input name="booking[<?= $key ?>][<?= $kkey ?>]" type="hidden" value="<?= $vvalue ?>">
            <? endforeach ?>
        <? else: ?>
            <input name="booking[<?= $key ?>]" type="hidden" value="<?= $value ?>">
        <? endif ?>
    <? endforeach ?>	
    <? foreach ($arResult['ITEMS'] as $key => &$arItem): if (empty($arItem['VALUES'])) {
            continue;
        } 
        if (!empty($arParams['FILTER_PROPERTY_CODE_SHOW'])) {
            if (!in_array($arItem['CODE'], $arParams['FILTER_PROPERTY_CODE_SHOW'])) continue;
        }			
        ?>
        <?
//    AddMessage2Log($arItem);
        $propExt = $arParams['PROP_EXT'][$arItem["CODE"]] ?? null;
        switch ($arItem["DISPLAY_TYPE"]) {
            case "A"://NUMBERS_WITH_SLIDER
                break;
            case "B"://NUMBERS
                break;
            case "P"://DROPDOWNS

                    ?>
                    <div class="search-sidebar facilities-sidebar">
                        <h4 class="title-sidebar accordion-us-header"><?= $translator->getTitleTranslation((int) $arItem["ID"], $propExt) ?> <i class="fa fa-angle-<?if ($arItem["DISPLAY_EXPANDED"]== "Y"):?>up<?else:?>down<?endif?>"></i></h4>

                        <div class="form-search clearfix accordion-us-body <?= ($arItem["DISPLAY_EXPANDED"] == "Y") ? 'active' : '' ?>">
                            <?$propPopular = NULL;?>
							<?$propTypeSign=NULL;?>
                            <?if(!empty($propExt['SHOW_POPULAR']) && $propExt['SHOW_POPULAR'] == "Y"):?>
                                <?$propPopular = $APPLICATION->IncludeComponent(
                                    "cit:property.popular.values",
                                    "",
                                    Array(
                                        "CACHE_TIME" => "36000000",
                                        "CACHE_TYPE" => "A",
                                        "COUNT" => $propExt['SHOW_POPULAR_COUNT'] ?? 6,
                                        "IBLOCK_ID" => $arItem['IBLOCK_ID'],
                                        "PROPERTY_CODE" => $arItem["CODE"],
                                        "ALL_PROPS" => &$arItem['VALUES'],
                                        "TRANSLATOR" => &$translator,
                                        "RETURN_TO_VARIABLE" => "Y"
                                    )
                                );?>
                            <?endif;?>
							<?							
							if(!empty($propExt['SHOW_TYPE_SIGHTS']) && $propExt['SHOW_TYPE_SIGHTS'] == "Y")
							{
								//получаем список типов достопримечательностей
								$filtenew_dop2=array("IBLOCK_ID" => $arParams["IBLOCK_ID_TYPE_SIGHTS"], "ACTIVE" => "Y");
								$listtypesign = $APPLICATION->IncludeComponent("travelsoft:travelsoft.iblock.getlist.byfilter", "", Array(
									"CACHE_TIME" => 3600,
									"CACHE_TYPE" => "A",
									"CNT" => 999999,
									"FILTER" => array_merge($filtenew_dop2),
									"FILTER_NAME" => "",
									"ORDER" => array($arParams["SORT_BY1"] => $arParams["ORDER_BY1"]),
									"RETURN_RESULT" => "Y",
									"SORT" => false,
									"SELECT" => array("ID","NAME","PROPERTY_NAME_EN","PROPERTY_NAME_BY"),
									"TITLE" => ""
										)
								);
								$arOptionstype = array(); 
					
								foreach ($listtypesign["ITEMS"] as $keytypesign=> $arValuetypsign) {							
									if (in_array($arValuetypsign["ID"],$arParams["__FILTERTYPE_REQUEST"]))  $arValuetypsign['CHECKED'] = true;
									 $arOptionstype[] = "<option " . ($arValuetypsign['CHECKED'] ? "selected" : "") . " value='" . $arValuetypsign["ID"] . "'>" . (LANGUAGE_ID == "ru" ? $arValuetypsign["NAME"] : $arValuetypsign["PROPERTY_NAME_".POSTFIX_PROPERTY."_VALUE"]). "</option>";									
								}
							
								$propTypeSign  =  '<select placeholder="'.GetMessage("select2-placeholder").'" name="typesign[]" class="select2-smart-filter"  multiple="multiple">'. implode("", $arOptionstype).'</select>';
								$display_expanded= !empty($_REQUEST['typesign']) ? true:false;
								$propTypeSign ='</div></div><div class="search-sidebar facilities-sidebar"><h4 class="title-sidebar accordion-us-header">'.GetMessage("TYPE").' <i class="fa fa-angle-'.($display_expanded ? 'up':'down').'"></i></h4>'.
												'<div class="form-search clearfix accordion-us-body '.($display_expanded ? 'active':'').'">'.$propTypeSign;
								
								}
							?>
						
                            <?
                            $arOptions = array(); //array("<option value=''>".GetMessage("choose")."</option>");
                            foreach ($arItem['VALUES'] as $id => $arValue) {
                                // выбор мед. профиля согласно запросу формы поиска цен
                                if ($arItem['CODE'] === 'TYPE' && in_array($id, $_REQUEST['booking']['id'])) {
                                    $arValue['CHECKED'] = true;
                                }
                                if ($arItem['CODE'] === 'TOWN' && $booking_city!='' &&  $booking_city==$arValue['VALUE']) {
                                    $arValue['CHECKED'] = true;
                                }
                                $arOptions[] = "<option " . ($arValue['CHECKED'] ? "selected" : "") . " value='" . $arValue['HTML_VALUE_ALT'] . "'>" . $translator->getValueTranslation($id) . "</option>";
                            }

                            if ($arOptions):
                                ?>
                            <select
                                placeholder="<?= GetMessage("select2-placeholder") ?>"
                                name="<?= $arItem['VALUES'][$id]['CONTROL_NAME_ALT'] ?>"
                                class="select2-smart-filter"
                                multiple="multiple">
                    <?= implode("", $arOptions); ?>
                            </select>
                        <?endif;?>
						
                        <?=$propPopular?>
						<?=$propTypeSign?>
                        </div>
                    </div>
                    <?
                break;
            case "U"://CALENDAR
                if ($arItem['VALUES']['MIN']['VALUE'] > $arItem['VALUES']['MAX']['VALUE']) {
                    continue;
                }
                $now = $arItem['VALUES']['MIN']['VALUE'] > time() ? $arItem['VALUES']['MIN']['VALUE'] : time();
                $locale = LANGUAGE_ID;
                if (LANGUAGE_ID=='by') $locale = 'be';
                ?>
                <div class="search-sidebar facilities-sidebar calendar-sidebar">
                    <h4 class="title-sidebar"><?= GetMessage('EVENT_DATE')?> <?/*= $translator->getTitleTranslation((int) $arItem["ID"]) */?></h4>
                    <div class="form-search clearfix">
                        <div class="form-field field-date">
                            <input name="<?= $arItem['VALUES']['MIN']['CONTROL_NAME'] ?>" value="<?= date("d.m.Y", $now) ?>" class="minDate" type="hidden">
                            <input name="<?= $arItem['VALUES']['MAX']['CONTROL_NAME'] ?>" value="<?= date("d.m.Y", $arItem['VALUES']['MAX']['VALUE']) ?>" class="maxDate" type="hidden">
                            <input
                                data-min-date="<?= $now ?>"
                                data-max-date="<?= $arItem['VALUES']['MAX']['VALUE'] ?>"
                                data-start-date="<?= $now ?>"
                                data-end-date="<?= $arItem['VALUES']['MAX']['VALUE'] ?>"
                                data-locale="<?= $locale ?>"
                                type="text" role="start-date"
                                id="ts-daterange-<?= $arItem['ID'] ?>"
                                class="field-input ts-daterange">
                        </div>
                    </div>
                </div>
            <?
            break;
        default://CHECKBOXES
            if (defined("SIMPLE_EXURSIONS_TOURS") && SIMPLE_EXURSIONS_TOURS === true && $arItem["CODE"] === "DAYS") {
                continue;
            }
            ?>
                <div class="widget-sidebar facilities-sidebar">
                    <h4 class="title-sidebar accordion-us-header"><?= $translator->getTitleTranslation((int) $arItem["ID"], $propExt) ?> <i class="fa fa-angle-<?if ($arItem["DISPLAY_EXPANDED"]== "Y"):?>up<?else:?>down<?endif?>"></i></h4>
                    <ul class="widget-ul accordion-us-body <?= ($arItem["DISPLAY_EXPANDED"] == "Y") ? 'active' : '' ?>">

            <? $check_all_id = null;
                $check_all_child = null;
            if(!empty($propExt['SHOW_CHECK_ALL']) && $propExt['SHOW_CHECK_ALL'] == 'Y'):
                $check_all_id = 'check_all_'.$arItem['CODE'];
                $check_all_child = 'check_all_child';
                ?>
                <li>
                    <div class="radio-checkbox">
                        <input id="<?=$check_all_id?>" type="checkbox"  class="checkbox check_all" name="<?=$check_all_id?>" <?=!empty($_REQUEST[$check_all_id]) ? 'checked' : ''?>>
                        <label for="<?=$check_all_id?>"><?=$propExt['CHECK_ALL_NAME_'.strtoupper(LANGUAGE_ID)]?></label>
                    </div>
                </li>
            <?endif;?>

            <? foreach ($arItem['VALUES'] as $id => $arValue): ?>
                <?
                if (!empty($propExt['EXCLUDE_VALUES_ID']) && in_array($id, $propExt['EXCLUDE_VALUES_ID'])) {
                    continue;
                }
				if (defined("SIMPLE_EXURSIONS_TOURS") && SIMPLE_EXURSIONS_TOURS === true && $arItem["CODE"] === "NEWYEAR" && $id == 350 ) {
                    continue;
                }
			if (defined("MULTIPLEDAYS_EXURSIONS_TOURS") && MULTIPLEDAYS_EXURSIONS_TOURS === true && 
				($arItem["CODE"] === "DAYS" && $arValue["VALUE"] == 1 || $arItem["CODE"] === "NEWYEAR" && ($id == 409 || $id == 346 || $id == 416 || $id == 418))) {
                    continue;
                }
                ?>
                            <li>
                                <div class="radio-checkbox">
                                    <input id="facilities-<?= $id ?>" type="checkbox" <? if ($arValue['CHECKED']): ?>checked="checked"<? endif ?> name="<?= $arValue['CONTROL_NAME'] ?>" value="<?= $arValue['HTML_VALUE'] ?>" class="checkbox <?=$check_all_id?> <?=$check_all_child?>">
                                    <label for="facilities-<?= $id ?>"><?= $translator->getValueTranslation($id) ?></label>
                                </div>
                            </li>
            <? endforeach ?>
                    </ul>
                </div>
    <? } ?>
<? endforeach ?>
</form>

    </div>
<?

/** Класс перевода свойств для bitrix smart filter */
class SFTranslator {

    /**
     * @var array
     */
    protected $translation = null;
    private $current_lang;

    /**
     * @param Bitrix\Main\Localization\Loc $loc
     * @param \CIBlockElement $oIBElement
     * @param array $arItems
     * @param string $current_lang_postfix
     */
    public function __construct(Bitrix\Main\Localization\Loc $loc, \CIBlockElement $oIBElement, array $arItems, string $current_lang = "ru", string $postfix_property) {

        $this->current_lang_postfix = strtoupper($current_lang);
        if ($current_lang === "ru") {

            foreach ($arItems as $property_id => $_item) {

                $this->translation["titles"][$property_id] = $_item["NAME"];

                foreach ($_item["VALUES"] as $id => $value) {

                    $this->translation["values"][$id] = $value["VALUE"];
                }
            }
        } else {

            if (!$postfix_property) {
                $postfix_property = "_" . strtoupper($current_lang);
            }

            foreach ($arItems as $property_id => $_item) {

                // заголовки описываются в языковых файлах
                $this->translation["titles"][$property_id] = $loc->getMessage($_item["CODE"], null, $current_lang);
                if (!$this->translation["titles"][$property_id]) {
                    $this->translation["titles"][$property_id] = $_item["NAME"];
                }

                // привязка к элементам инфоблока
                if ($_item["PROPERTY_TYPE"] === "E") {

                    if (!empty($_arr_id = array_keys($_item["VALUES"]))) {

                        $iblock_id = $oIBElement->GetIBlockByID($_arr_id[0]);

                        if ($iblock_id) {

                            $db_res = $oIBElement->GetList(
                                    false, array("ID" => $_arr_id, "IBLOCK_ID" => $iblock_id), false, false, array("ID", "PROPERTY_NAME" . $postfix_property));

                            while ($res = $db_res->Fetch()) {
                                $this->translation["values"][$res["ID"]] = $res["PROPERTY_NAME" . $postfix_property . "_VALUE"];
                            }
                        }
                    }
                } else if ($_item["PROPERTY_TYPE"] === "L") {

                    foreach ($_item["VALUES"] as $id => $value) {

                        $title = $loc->getMessage('PROPERTY_LIST_VALUE_ID_' . $id, null, $current_lang);
                        if (strlen($title) > 0) {

                            $this->translation["values"][$id] = $title;
                        } else {

                            $this->translation["values"][$id] = $value["VALUE"];
                        }
                    }
                } else {

                    foreach ($_item["VALUES"] as $id => $value) {
                        $this->translation["values"][$id] = $value["VALUE"];
                    }
                }
            }
        }
    }

    /**
     * возвращает заголовок свойства
     * @param integer $property_id
     * @return string
     */
    public function getTitleTranslation($property_id, &$propExt = null) {
        if (!empty($propExt) && !empty($propExt['ALT_NAME_'.$this->current_lang_postfix])) {
            return $propExt['ALT_NAME_'.$this->current_lang_postfix];
        }
        return $this->translation["titles"][$property_id];
    }

    /**
     * @param integer $value_id
     * @return string
     */
    public function getValueTranslation($value_id) {
        return $this->translation["values"][$value_id];
    }

}
?>
<style>

</style>
<script>
    $(document).ready(function (e) {
        class CollapseNav{
            constructor(node,collapseNode,navText){
                this.navText = navText;
                this.collapseNode = collapseNode;
                this.node = node;
                this.resize();
            };
            resize(node = this.node,collapseNode = this.collapseNode){
                if(window.innerWidth<768) {
                    collapseNode.classList.remove('active');
					node.classList.remove('active');
                    node.children[0].innerText = this.navText[0];
                };
            }
            collapseClick(node = this.node, collapseNode = this.collapseNode){
                node.onclick = (e) =>{
                    node.classList.toggle('active');
                    collapseNode.classList.toggle('active');
                    if(node.classList.contains('active')){
                        node.children[0].innerText = this.navText[1];
                    }
                    else{
                        node.children[0].innerText = this.navText[0];
                    }
                }
            }

        }
        if(document.querySelector('[data="toogle-menu"]')){
            const clickNode = document.querySelector('[data="toogle-menu"]');
            const collapseNode = document.querySelector('[data="collapse"]');

            let collapseNav = new CollapseNav(clickNode,collapseNode,["<?=GetMessage('SHOW_FILTER_LANG')?>","<?=GetMessage('HIDE_FILTER_LANG')?>"]);
            collapseNav.collapseClick();
        }

    })

</script>