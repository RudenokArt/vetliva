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

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

CJSCore::Init();

$this->addExternalJs("https://maps.googleapis.com/maps/api/js?key=" . GOOGLE_API_KEY);
$this->addExternalJs("https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js");

$this->addExternalCss($templateFolder . "/multiselect/multiple-select.min.css");
$this->addExternalJs($templateFolder . "/multiselect/multiple-select.min.js");

$this->addExternalJs($templateFolder . "/jquery.travelsoft.map.page.min.js");

$cnt = IntVal($arResult['cntElements']);

$defPoints = !$arResult["__request"] && !empty($arParams['defPoints']) ? $arParams['defPoints'] : array();

?>

<div class="col-md-3 col-md-push-0">
    <div class="search-result">
        <p><?=GetMessage("WEFOUND")?><br><span><?=GetMessage("OBJECTS")?>:&nbsp;</span><ins id="searching__cnt__elements"><?= $cnt?></ins></p>
    </div>
</div>

<div class="col-md-9 col-md-pull-0 content-page-detail">
    <div class="mt-15 clearfix">
        <form data-lang-phrases='{"__hide": "<?= Loc::getMessage("__hide")?>","__show": "<?= Loc::getMessage("__show")?>","selectAllText": "<?= Loc::getMessage("selectAllText")?>","allSelected": "<?= Loc::getMessage("allSelected")?>","countSelected": "<?= Loc::getMessage("countSelected")?>","noMatchesFound": "<?= Loc::getMessage("noMatchesFound")?>"}'
        id="navigation-form" action="<?= $APPLICATION->GetCurPage(false)?>" 
        method="get">
            <select data-placeholder="<?=Loc::getMessage("CHOOSE_POINTS")?>" id="points" name="navigation[points][]" multiple="multiple">
                <?
                $arPoints = $arResult['navigation']['points'];
                foreach ($arPoints['items'] as $groupId => $arItems) :
                    ?>
                        <optgroup label="<?=  $arPoints['groups'][$groupId]['NAME']?>">
                                <?if (empty($arItems)):     
                                    $selected = in_array($groupId."_", $arResult['__request']['points']) || in_array($groupId, $defPoints);
                                ?>
                                        <!--option <?if ($selected) echo "selected"?> value="<?= $groupId?>_"><?=Loc::getMessage("ALL_OBJECTS")?></option-->
                                <?else:?>
                                    <?foreach ($arItems as $arItem) :
                                            $selected = in_array($groupId."_".$arItem['value'], $arResult['__request']['points']) || in_array($groupId, $defPoints);
                                        ?>
                                        <option <?if ($selected) echo "selected"?> value="<?= $groupId?>_<?= $arItem['value']?>"><?= $arItem['title']?></option>
                                    <?  endforeach;?>
                                <?endif?>
                        </optgroup>
               <?endforeach?>
            </select>
            <select data-placeholder="<?=Loc::getMessage("CHOOSE_LOCATION")?>" name="navigation[locations][]" id="locations" multiple="multiple">
                <?
                $arLocations = $arResult['navigation']['locations'];
                foreach ($arLocations['items'] as $regionId => $arItems) :?>
                        <optgroup label="<?=  $arLocations['regions'][$regionId]['NAME']?>">
                                <?foreach ($arItems as $arItem) :
                                    $selected = in_array($arItem['value'], $arResult['__request']['locations']);
                                    ?>
                                    <option <?if ($selected) echo "selected"?> value="<?= $arItem['value']?>"><?= $arItem['title']?></option>
                                <?  endforeach;?>
                        </optgroup>
               <?endforeach?>
            </select>
        </form>
    </div> 
</div>

<div class="mt-20 hl-maps-cn">
    <div data-json-items='<?= Bitrix\Main\Web\Json::encode($arResult['items'])?>' id="hotel-maps"></div>
</div>

