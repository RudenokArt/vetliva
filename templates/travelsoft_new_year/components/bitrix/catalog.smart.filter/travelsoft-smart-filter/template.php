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

if (empty($arResult['ITEMS'])) return;

///// инициализируем перевод языка
use Bitrix\Main\Localization\Loc;
$loc = new Loc;
$loc->loadMessages(__FILE__);
\Bitrix\Main\Loader::includeModule("iblock");
$translator = new SFTranslator($loc, new CIBlockElement, $arResult["ITEMS"], LANGUAGE_ID, POSTFIX_PROPERTY);
?>

<form id="smart_filter_form" name="smart_filter_form" action="<? echo $APPLICATION->GetCurPage(false) ?>" method="get" class="smartfilter">
    <input name="set_filter" type="hidden" value="y">
    <?foreach ($arParams["__BOOKING_REQUEST"] as $key => $value) :
        if (is_array($value)):?>
            <?foreach ($value as $kkey => $vvalue):?>
    <input name="booking[<?= $key?>][<?= $kkey?>]" type="hidden" value="<?= $vvalue?>">
            <?endforeach?>
        <?else:?>
    <input name="booking[<?= $key?>]" type="hidden" value="<?= $value?>">
        <?endif?>
    <?endforeach?>
<?foreach ($arResult['ITEMS'] as $key => &$arItem):?>
    <?switch ($arItem["DISPLAY_TYPE"]) {
        case "A"://NUMBERS_WITH_SLIDER
            break;
        case "B"://NUMBERS
            break;
        case "P"://DROPDOWNS
           
                $arOptions = array(); //array("<option value=''>".GetMessage("choose")."</option>");
                foreach ($arItem['VALUES'] as $id => $arValue) {
                    $arOptions[] = "<option ".($arValue['CHECKED'] ? "selected" : "")." value='" . $arValue['HTML_VALUE_ALT'] . "'>" . $translator->getValueTranslation($id) . "</option>";
                }
                if ($arOptions):?>
                <div class="search-sidebar facilities-sidebar">
                    <h4 class="title-sidebar"><?= $translator->getTitleTranslation((int)$arItem["ID"])?></h4>
                        <div class="form-search clearfix">
                            <select
                                placeholder="<?= GetMessage("select2-placeholder")?>"
                                name="<?= $arItem['VALUES'][$id]['CONTROL_NAME_ALT']?>"
                                class="select2-smart-filter"
                                multiple="multiple">
                                <?= implode("", $arOptions);?>
                            </select>
                            
                        </div>
                </div>
            <?
            endif;
            break;
        case "U"://CALENDAR
                if ($arItem['VALUES']['MIN']['VALUE'] > $arItem['VALUES']['MAX']['VALUE']) {
                    continue;
                }
                $now = $arItem['VALUES']['MIN']['VALUE'] > time() ? $arItem['VALUES']['MIN']['VALUE'] : time();
                ?>
                <div class="search-sidebar facilities-sidebar">
                    <h4 class="title-sidebar"><?= $translator->getTitleTranslation((int)$arItem["ID"])?></h4>
                        <div class="form-search clearfix">
                            <div class="form-field field-date">
                                <input name="<?= $arItem['VALUES']['MIN']['CONTROL_NAME']?>" value="<?= date("d.m.Y", $now)?>" class="minDate" type="hidden">
                                <input name="<?= $arItem['VALUES']['MAX']['CONTROL_NAME']?>" value="<?= date("d.m.Y", $arItem['VALUES']['MAX']['VALUE'])?>" class="maxDate" type="hidden">
                                <input 
                                    data-min-date="<?= $now?>"
                                    data-max-date="<?= $arItem['VALUES']['MAX']['VALUE']?>"
                                    data-start-date="<?= $now?>"
                                    data-end-date="<?= $arItem['VALUES']['MAX']['VALUE']?>"
                                    data-locale="<?= LANGUAGE_ID?>"
                                    type="text" role="start-date"
                                    id="ts-daterange-<?= $arItem['ID']?>"
                                    class="field-input ts-daterange">
                            </div>
                        </div>
                </div>
                <?
            break;
        default://CHECKBOXES
            ?>
            <div class="widget-sidebar facilities-sidebar">
                <h4 class="title-sidebar"><?= $translator->getTitleTranslation((int)$arItem["ID"])?></h4>
                <ul class="widget-ul">
                    <?foreach ($arItem['VALUES'] as $id => $arValue):?>
                    <li>
                        <div class="radio-checkbox">
                            <input id="facilities-<?= $id?>" type="checkbox" <?if($arValue['CHECKED']):?>checked="checked"<?endif?> name="<?= $arValue['CONTROL_NAME']?>" value="<?= $arValue['HTML_VALUE']?>" class="checkbox">
                            <label for="facilities-<?= $id?>"><?= $translator->getValueTranslation($id)?></label>
                        </div>
                    </li>
                    <?endforeach?>
                </ul>
            </div>
    <?}?>
<?endforeach?>
</form>


<?

/** Класс перевода свойств для bitrix smart filter */
class SFTranslator {
    
    /**
     * @var array
     */
    protected $translation = null;
    
    /**
     * @param Bitrix\Main\Localization\Loc $loc
     * @param \CIBlockElement $oIBElement
     * @param array $arItems
     * @param string $current_lang
     */
    public function __construct(Bitrix\Main\Localization\Loc $loc, \CIBlockElement $oIBElement, array $arItems, string $current_lang = "ru", string $postfix_property) {
        
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
                    
                    if ( !empty( $_arr_id = array_keys($_item["VALUES"]) ) ) {

                        $iblock_id = $oIBElement->GetIBlockByID($_arr_id[0]);

                        if ($iblock_id) {

                            $db_res = $oIBElement->GetList(
                                    false, 
                                    array("ID" => $_arr_id, "IBLOCK_ID" => $iblock_id),
                                    false,
                                    false,
                                    array("ID", "PROPERTY_NAME" . $postfix_property));
                            
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
    public function getTitleTranslation ($property_id) {
        return $this->translation["titles"][$property_id];
    }
    
    /**
     * @param integer $value_id
     * @return string
     */
    public function getValueTranslation ($value_id) {
        return $this->translation["values"][$value_id];
    }
    
}

?>